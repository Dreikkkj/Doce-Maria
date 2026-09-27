<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/database.php';

$checkoutError = '';
$checkoutSuccess = (string) ($_SESSION['checkout_success'] ?? '');
unset($_SESSION['checkout_success']);

$cart = $_SESSION['carrinho'] ?? [];
$cart = is_array($cart) ? $cart : [];
$cartItems = [];
foreach ($cart as $productId => $quantity) {
    $productId = filter_var($productId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $quantity = filter_var($quantity, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 99]]);
    if ($productId !== false && $quantity !== false) {
        $cartItems[$productId] = $quantity;
    }
}

$products = [];
$subtotalCents = 0;
if ($cartItems !== []) {
    $placeholders = implode(',', array_fill(0, count($cartItems), '?'));
    $statement = database()->prepare(
        "SELECT id_produto, img_produto, nome_produto, preco, estoque FROM produtos WHERE id_produto IN ({$placeholders})"
    );
    $statement->execute(array_keys($cartItems));
    foreach ($statement->fetchAll() as $product) {
        $productId = (int) $product['id_produto'];
        $products[$productId] = $product;
        $subtotalCents += (int) round((float) $product['preco'] * 100) * $cartItems[$productId];
    }
    if (count($products) !== count($cartItems)) {
        $checkoutError = 'Um produto do carrinho não está mais disponível.';
    }
}

$tipCents = 500;
$paymentMethods = ['Cartão', 'Pix', 'Dinheiro'];
$collectionMethods = ['Retirada', 'Entrega'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_user'])) {
        $checkoutError = 'Entre na sua conta para finalizar o pedido.';
    } elseif (!hash_equals($_SESSION['csrf_token'], (string) ($_POST['csrf_token'] ?? ''))) {
        $checkoutError = 'Sua sessão expirou. Atualize a página e tente novamente.';
    } elseif ($cartItems === [] || count($products) !== count($cartItems)) {
        $checkoutError = 'Adicione produtos disponíveis ao carrinho antes de finalizar.';
    } else {
        $collector = trim((string) ($_POST['nome_coletor'] ?? ''));
        $note = trim((string) ($_POST['nota_pedido'] ?? ''));
        $collectionMethod = (string) ($_POST['metodo_coleta'] ?? '');
        $paymentMethod = (string) ($_POST['metodo_pagamento'] ?? '');
        $tip = (string) ($_POST['gorjeta'] ?? '');
        $zipCode = trim((string) ($_POST['cep'] ?? ''));
        $deliveryAddress = trim((string) ($_POST['endereco_entrega'] ?? ''));

        if ($collector === '' || mb_strlen($collector) > 100 || mb_strlen($note) > 2000) {
            $checkoutError = 'Preencha o nome de quem receberá o pedido e confira a observação.';
        } elseif (!in_array($collectionMethod, $collectionMethods, true)) {
            $checkoutError = 'Selecione retirada ou entrega.';
        } elseif (!in_array($paymentMethod, $paymentMethods, true)) {
            $checkoutError = 'Selecione uma forma de pagamento válida.';
        } elseif (!in_array($tip, ['0', '5', '7', '9', '0.00', '5.00', '7.00', '9.00'], true)) {
            $checkoutError = 'Selecione uma gorjeta válida.';
        } elseif ($collectionMethod === 'Entrega' && ($zipCode === '' || mb_strlen($zipCode) > 10 || $deliveryAddress === '' || mb_strlen($deliveryAddress) > 500)) {
            $checkoutError = 'Informe um CEP válido e um endereço de até 500 caracteres.';
        } else {
            $tipCents = (int) round((float) $tip * 100);
            $freightCents = 0;

            try {
                $connection = database();
                $connection->beginTransaction();
                $lockedProducts = [];
                $subtotalCents = 0;

                foreach ($cartItems as $productId => $quantity) {
                    $statement = $connection->prepare(
                        'SELECT id_produto, nome_produto, preco, estoque FROM produtos WHERE id_produto = ? FOR UPDATE'
                    );
                    $statement->execute([$productId]);
                    $product = $statement->fetch();

                    if (!$product || (int) $product['estoque'] < $quantity) {
                        throw new DomainException('Estoque insuficiente para um ou mais produtos do carrinho.');
                    }

                    $lockedProducts[$productId] = $product;
                    $subtotalCents += (int) round((float) $product['preco'] * 100) * $quantity;
                }

                $totalCents = $subtotalCents + $tipCents + $freightCents;
                $statement = $connection->prepare(
                    'INSERT INTO pedidos (id_cliente, metodo_coleta, nome_coletor, nota_pedido, cep, endereco_entrega, subtotal, valor_frete, gorjeta, valor_total, metodo_pagamento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $statement->execute([
                    (int) $_SESSION['id_user'],
                    $collectionMethod,
                    $collector,
                    $note,
                    $collectionMethod === 'Entrega' ? $zipCode : null,
                    $collectionMethod === 'Entrega' ? $deliveryAddress : null,
                    number_format($subtotalCents / 100, 2, '.', ''),
                    number_format($freightCents / 100, 2, '.', ''),
                    number_format($tipCents / 100, 2, '.', ''),
                    number_format($totalCents / 100, 2, '.', ''),
                    $paymentMethod,
                ]);
                $orderId = (int) $connection->lastInsertId();

                $itemStatement = $connection->prepare(
                    'INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)'
                );
                $stockStatement = $connection->prepare(
                    "UPDATE produtos SET status_estoque = CASE WHEN estoque - ? <= 0 THEN 'Esgotado' WHEN estoque - ? <= 5 THEN 'Estoque baixo' ELSE 'Em estoque' END, estoque = estoque - ? WHERE id_produto = ?"
                );

                foreach ($cartItems as $productId => $quantity) {
                    $price = number_format((float) $lockedProducts[$productId]['preco'], 2, '.', '');
                    $itemStatement->execute([$orderId, $productId, $quantity, $price]);
                    $stockStatement->execute([$quantity, $quantity, $quantity, $productId]);
                }

                $connection->commit();
                unset($_SESSION['carrinho']);
                $_SESSION['checkout_success'] = "Pedido #{$orderId} registrado com sucesso.";
                header('Location: carrinhopag.php');
                exit;
            } catch (DomainException $exception) {
                if (isset($connection) && $connection->inTransaction()) {
                    $connection->rollBack();
                }
                $checkoutError = $exception->getMessage();
            } catch (Throwable $exception) {
                if (isset($connection) && $connection->inTransaction()) {
                    $connection->rollBack();
                }
                error_log($exception->getMessage());
                $checkoutError = 'Não foi possível registrar o pedido agora. Tente novamente.';
            }
        }
    }
}

function format_brl(int $cents): string
{
    return 'R$ ' . number_format($cents / 100, 2, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Doce Maria</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./andreicss/carrinhopag.css">
    <link rel="stylesheet" href="./css/bia.css">
</head>
<body>
<?php
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'header.php';

?>
    <form method="post" id="checkout-form">
    <input type="hidden" name="csrf_token" value="<?= escape_html($_SESSION['csrf_token']) ?>">
    <main class="container">
        <?php if ($checkoutError !== ''): ?>
            <p class="checkout-feedback erro" role="alert"><?= escape_html($checkoutError) ?></p>
        <?php elseif ($checkoutSuccess !== ''): ?>
            <p class="checkout-feedback sucesso" role="status"><?= escape_html($checkoutSuccess) ?></p>
        <?php endif; ?>
        
        <div class="coluna-esq">
            
            <section class="cartao">
                <h2 class="titulo">Finalizar pedido</h2>
                
                <div class="grid-info">
                    <div class="bloco-info">
                        <h3>Método de Coleta</h3>
                        <div class="grupo-botoes">
                            <button class="botao-opcao ativo" type="button" data-metodo="Retirada">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                Retirada
                            </button>
                            <button class="botao-opcao" type="button" data-metodo="Entrega">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h.01"/><path d="M17 7h.01"/><path d="M7 17h.01"/><path d="M17 17h.01"/></svg>
                                Entrega
                            </button>
                        </div>
                        <input type="hidden" name="metodo_coleta" id="metodo-coleta" value="Retirada">
                        <p class="texto-pequeno" id="texto-coleta">Retire seu pedido no balcão de coleta</p>
                    </div>

                    <div class="icone-texto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <div>
                            <p class="texto-destaque">Pedido pendente</p>
                            <p class="texto-pequeno">A loja confirmará o preparo</p>
                        </div>
                    </div>

                    <div class="icone-texto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <div id="campos-entrega" hidden>
                            <div class="campo-grupo">
                                <label for="cep">CEP</label>
                                <input class="input-texto" id="cep" name="cep" maxlength="10" autocomplete="postal-code">
                            </div>
                            <div class="campo-grupo">
                                <label for="endereco-entrega">Endereço</label>
                                <input class="input-texto" id="endereco-entrega" name="endereco_entrega" maxlength="500" autocomplete="street-address">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="label-input" for="nome-coletor">Quem receberá o pedido?</label>
                    <input type="text" class="input-texto" id="nome-coletor" name="nome_coletor" maxlength="100" placeholder="Nome e sobrenome" required>
                </div>
            </section>

            <section class="cartao">
                <h2 class="titulo">Minha Sacola</h2>
                
                <label class="texto-pequeno" for="nota-pedido" style="display: block; margin-bottom: 8px;">Adicione uma nota para o pedido (opcional)</label>
                <div class="input-com-contador">
                    <input type="text" class="input-texto" id="nota-pedido" name="nota_pedido" maxlength="2000" placeholder="Observação">
                    <span class="contador">2000</span>
                </div>

                <?php if ($cartItems === []): ?>
                    <p class="texto-pequeno">Seu carrinho está vazio. Adicione produtos antes de finalizar.</p>
                <?php else: ?>
                    <?php foreach ($cartItems as $productId => $quantity): ?>
                        <?php if (!isset($products[$productId])) { continue; } ?>
                        <?php $product = $products[$productId]; ?>
                        <?php $lineCents = (int) round((float) $product['preco'] * 100) * $quantity; ?>
                        <div class="produto">
                            <?php if (!empty($product['img_produto'])): ?>
                                <img src="./imagens/<?= escape_html(basename((string) $product['img_produto'])) ?>" alt="<?= escape_html($product['nome_produto']) ?>" class="produto-imagem">
                            <?php endif; ?>
                            <div class="produto-detalhes">
                                <div class="produto-cabecalho">
                                    <h3 class="produto-titulo"><?= escape_html($product['nome_produto']) ?></h3>
                                    <span class="produto-titulo"><?= format_brl($lineCents) ?></span>
                                </div>
                                <p class="texto-pequeno">Quantidade: <?= $quantity ?> · <?= format_brl((int) round((float) $product['preco'] * 100)) ?> cada</p>
                                <p class="texto-pequeno">Estoque disponível: <?= (int) $product['estoque'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="rodapé-sacola">
                        <span>Quantidade: <?= array_sum($cartItems) ?></span>
                        <span><?= format_brl($subtotalCents) ?></span>
                    </div>
                <?php endif; ?>
            </section>

        </div>

        <aside class="coluna-dir">
            
            <section class="cartao">
                <h2 class="titulo-menor" style="margin-top: 0;">Detalhes do Pedido</h2>
                
                <div class="linha-valor">
                    <span>Subtotal</span>
                    <span id="valor-subtotal"><?= format_brl($subtotalCents) ?></span>
                </div>
                <div class="linha-valor">
                    <span>Gorjeta</span>
                    <span id="valor-gorjeta"><?= format_brl($tipCents) ?></span>
                </div>

                <div class="grid-gorjetas">
                    <button class="botao-gorjeta ativo" type="button" data-valor="5.00">R$ 5</button>
                    <button class="botao-gorjeta" type="button" data-valor="7.00">R$ 7</button>
                    <button class="botao-gorjeta" type="button" data-valor="9.00">R$ 9</button>
                    <button class="botao-gorjeta" type="button" data-valor="0.00">Sem gorjeta</button>
                </div>
                <input type="hidden" name="gorjeta" id="gorjeta" value="5.00">
                <p class="texto-pequeno" style="margin-bottom: 16px;">100% das gorjetas vão para os padeiros</p>

                <div class="total-pedido">
                    <span>Total</span>
                    <span id="valor-total"><?= format_brl($subtotalCents + $tipCents) ?></span>
                </div>

                <h2 class="titulo-menor">Pagamento</h2>
                
                <div class="box-pagamento">
                    <div class="aba-cartao">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        Forma de pagamento
                    </div>
                    <div class="form-cartao">
                        <div class="campo-grupo">
                            <label for="metodo-pagamento">Selecione como vai pagar</label>
                            <select class="input-texto" id="metodo-pagamento" name="metodo_pagamento" required>
                                <option value="Cartão">Cartão</option>
                                <option value="Pix">Pix</option>
                                <option value="Dinheiro">Dinheiro</option>
                            </select>
                        </div>
                    </div>
                </div>

                <p class="aviso-legal">
                    O pedido será registrado como pendente. O pagamento não é processado nesta página; não informe número ou código do cartão.
                </p>

                <button class="botao-cupom" type="button">
                    <span>+</span> Cartão de Presente, Voucher ou Código Promocional
                </button>

                <p class="termos">
                    Ao prosseguir, você concorda com nossos <a href="#">Termos e Condições</a> e confirma que leu e compreende nossa <a href="#">Política de Privacidade</a>.
                </p>
                <?php if (isset($_SESSION['id_user'])): ?>
                    <button class="botao-finalizar" type="submit" <?= $cartItems === [] || count($products) !== count($cartItems) ? 'disabled' : '' ?>>Confirmar pedido</button>
                <?php else: ?>
                    <a class="botao-finalizar" href="login.php">Entre para finalizar o pedido</a>
                <?php endif; ?>
            </section>

        </aside>

    </main>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const botoesColeta = document.querySelectorAll('.botao-opcao');
            const campoMetodoColeta = document.getElementById('metodo-coleta');
            const camposEntrega = document.getElementById('campos-entrega');
            const cep = document.getElementById('cep');
            const enderecoEntrega = document.getElementById('endereco-entrega');
            const textoColeta = document.getElementById('texto-coleta');

            botoesColeta.forEach(botao => {
                botao.addEventListener('click', function() {
                    botoesColeta.forEach(b => b.classList.remove('ativo'));
                    this.classList.add('ativo');
                    campoMetodoColeta.value = this.dataset.metodo;

                    const entregaSelecionada = this.dataset.metodo === 'Entrega';
                    camposEntrega.hidden = !entregaSelecionada;
                    cep.required = entregaSelecionada;
                    enderecoEntrega.required = entregaSelecionada;
                    textoColeta.textContent = entregaSelecionada
                        ? 'Informe onde o pedido deve ser entregue'
                        : 'Retire seu pedido no balcão de coleta';
                });
            });

            const subtotalCentavos = <?= (int) $subtotalCents ?>;
            const campoGorjeta = document.getElementById('gorjeta');
            const elementoGorjeta = document.getElementById('valor-gorjeta');
            const elementoTotal = document.getElementById('valor-total');
            const formatarReais = centavos => new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(centavos / 100);

            const botoesGorjeta = document.querySelectorAll('.botao-gorjeta');
            botoesGorjeta.forEach(botao => {
                botao.addEventListener('click', function() {
                    botoesGorjeta.forEach(b => b.classList.remove('ativo'));
                    this.classList.add('ativo');

                    const gorjetaCentavos = Math.round(parseFloat(this.dataset.valor) * 100);
                    campoGorjeta.value = (gorjetaCentavos / 100).toFixed(2);
                    elementoGorjeta.textContent = formatarReais(gorjetaCentavos);
                    elementoTotal.textContent = formatarReais(subtotalCentavos + gorjetaCentavos);
                });
            });

        });
    </script>
</body>
</html>
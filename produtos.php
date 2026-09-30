<?php 
// 1. CARREGA O CABEÇALHO
require_once __DIR__ . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'header.php';

// 2. CONEXÃO COM A BASE DE DADOS
$host = 'localhost';
$usuario = 'root';
$senha = ''; 
$banco = 'doce_maria'; 

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
// 3. CONSULTA OS PRODUTOS (SEM REPETIDOS)
$categoria_selecionada = $_GET['categoria'] ?? '';

if (!empty($categoria_selecionada)) {
    $categoria_safe = $conn->real_escape_string($categoria_selecionada);
    // GROUP BY nome_produto garante que cada nome só aparece uma vez
    $sql = "SELECT * FROM produtos WHERE categoria = '$categoria_safe' GROUP BY nome_produto";
} else {
    // DISTINCT ou GROUP BY remove os produtos com nomes duplicados
    $sql = "SELECT * FROM produtos GROUP BY nome_produto";
}

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Todos</title>
    <link rel="stylesheet" href="produtos.css">
    <link rel="stylesheet" href="bia.css">
</head>
<body>

    <main class="pagina-produtos">
        <h1>Ver todos</h1>

        <div class="layout-conteudo">
            <aside class="sidebar-categorias">
                <h2>Categorias</h2>
                <form method="GET" action="" id="form-filtro">
                    <ul>
                        <?php 
                        $categorias = ['Alfajor', 'Brigadeiro', 'Brownie', 'Bolos', 'Cannoli', 'Cupcakes', 'Donuts', 'Eclair', 'Macaron', 'Pirulitos'];
                        foreach ($categorias as $cat): 
                            $checked = ($categoria_selecionada === $cat) ? 'checked' : '';
                        ?>
                            <li>
                                <label>
                                    <input type="radio" name="categoria" value="<?php echo $cat; ?>" <?php echo $checked; ?> onchange="this.form.submit()">
                                    <?php echo $cat; ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <?php if (!empty($categoria_selecionada)): ?>
                        <a href="produtos.php" style="display:inline-block; margin-top:10px; font-size:13px; color:#d9534f; text-decoration:none; font-weight:bold;">
                            ✕ Limpar filtro
                        </a>
                    <?php endif; ?>
                </form>
            </aside>

           
            <section class="grid-produtos">
                <?php 
                if ($resultado && $resultado->num_rows > 0) {
                    while ($produto = $resultado->fetch_assoc()) { 
                        // Tratamento de colunas com fallbacks
                        $nome   = $produto['nome_produto'] ?? $produto['nome'] ?? 'Produto';
                        $preco  = $produto['preco'] ?? 0;
                        $imagem = $produto['img_produto'] ?? $produto['imagem'] ?? 'cookie.png';
                ?>
                        <div class="card-produtos">
                            <img src="img-produtos/<?php echo htmlspecialchars($imagem); ?>" alt="<?php echo htmlspecialchars($nome); ?>" class="img-produto">
                            
                            <!-- Nome -->
                            <p class="titulo-produto"><?php echo htmlspecialchars($nome); ?></p>
                            
                            <!-- Preço -->
                            <p class="preco-produto">R$ <?php echo number_format((float)$preco, 2, ',', '.'); ?></p>

                            <!-- Botões de Ação -->
                            <div class="acoes-card">
                                <a href="detalhes.php?id_produto=<?php echo $produto['id_produto']; ?>" class="btn-compre">Compre</a>
                                <div class="contador-qtd">
                                    <button type="button" class="btn-qtd btn-menos">-</button>
                                    <span class="qtd-valor">1</span>
                                    <button type="button" class="btn-qtd btn-mais">+</button>
                                </div>
                            </div>
                        </div>
                <?php 
                    }
                } else {
                    echo "<p>Nenhum produto encontrado.</p>";
                }
                ?>
            </section>
        </div>
    </main>

    <!-- SCRIPT JAVASCRIPT PARA OS BOTÕES DE + E - FUNCIONAREM -->
    <script>
        document.querySelectorAll('.contador-qtd').forEach(container => {
            const btnMenos = container.querySelector('.btn-menos');
            const btnMais  = container.querySelector('.btn-mais');
            const spanQtd  = container.querySelector('.qtd-valor');

            btnMais.addEventListener('click', () => {
                let valor = parseInt(spanQtd.textContent);
                spanQtd.textContent = valor + 1;
            });

            btnMenos.addEventListener('click', () => {
                let valor = parseInt(spanQtd.textContent);
                if (valor > 1) {
                    spanQtd.textContent = valor - 1;
                }
            });
        });
    </script>

</body>
</html>
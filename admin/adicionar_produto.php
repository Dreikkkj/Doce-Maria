<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../php/crud.php';

// Proteção: Apenas admin pode acessar
if (!isset($_SESSION['autenticado']) || ($_SESSION['tipo_usuario'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$erro = "";
$sucesso = "";

// Processa a inserção do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = filter_var($_POST['preco'] ?? null, FILTER_VALIDATE_FLOAT);
    $estoque = filter_var($_POST['estoque'] ?? null, FILTER_VALIDATE_INT);
    $categorias = ['bolos' => 'Bolos', 'docinhos' => 'Doces'];
    $categoria = $categorias[$_POST['categoria'] ?? ''] ?? null;

    if ($nome === '' || strlen($nome) > 100 || $preco === false || $preco <= 0 || $preco > 99999999.99 || $estoque === false || $estoque < 0) {
        $erro = "Preencha todos os campos obrigatórios!";
    } elseif ($categoria === null) {
        $erro = "A categoria selecionada não existe no banco de dados.";
    } else {
        $novoProduto = [
            'nome_produto' => $nome,
            'descricao' => $descricao,
            'preco' => $preco,
            'estoque' => $estoque,
            'categoria' => $categoria,
            'status_estoque' => $estoque === 0 ? 'Esgotado' : ($estoque <= 10 ? 'Estoque baixo' : 'Em estoque')
        ];
        
        $inseriu = create($pdo, 'produtos', $novoProduto);
        
        if ($inseriu) {
            header("Location: estoque.php");
            exit();
        } else {
            $erro = "Erro ao adicionar produto no banco de dados.";
        }
    }

    if ($erro !== '') {
        http_response_code(422);
        exit(htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'));
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="add_produto.css">
    <link rel="icon" type="image/png" href="imagens/logo.png">
    <title>Adicionar Produto - DoceMaria Admin</title>
</head>

<body>
    <div class="admin-container">
        <div class="admin-header">
            <div class="icon-topo">
                <i class="fa-solid fa-plus"></i>
            </div>
            <h1>Novo Produto</h1>
            <p>Cadastre um novo item para o seu estoque.</p>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label>Nome do Produto *</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-cake-candles"></i></span>
                    <input type="text" name="nome" placeholder="Ex: Brigadeiro Gourmet" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label>Preço (R$) *</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fa-solid fa-tag"></i></span>
                        <input type="number" step="0.01" name="preco" placeholder="0.00" required>
                    </div>
                </div>

                <div class="form-group half">
                    <label>Qtd. Inicial *</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fa-solid fa-cubes"></i></span>
                        <input type="number" name="estoque" placeholder="0" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Categoria</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-list"></i></span>
                    <select name="categoria">
                        <option value="bolos">Bolos Decorados</option>
                        <option value="docinhos">Docinhos</option>
                        <option value="tortas">Tortas</option>
                        <option value="bebidas">Bebidas</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Descrição</label>
                <div class="input-wrapper textarea-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-align-left"></i></span>
                    <textarea name="descricao" rows="4" placeholder="Detalhes dos ingredientes, peso, etc."></textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="estoque.php" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-submit">Adicionar Produto</button>
            </div>
        </form>
    </div>
</body>

</html>
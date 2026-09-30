<?php 
// 1. Inclui o cabeçalho
require_once __DIR__ . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'header.php';

// 2. Conexão com a base de dados
$host = 'localhost';
$usuario = 'root';
$senha = ''; 
$banco = 'doce_maria'; 

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// 3. Captura o id_produto vindo da URL (?id_produto=123)
$id_produto = $_GET['id_produto'] ?? null;

if (!$id_produto) {
    // Se não receber o id_produto pela URL, volta para a lista
    header('Location: produtos.php');
    exit;
}

// 4. Consulta o produto no banco filtrando pela coluna id_produto
$id_produto_safe = $conn->real_escape_string($id_produto);
$sql = "SELECT * FROM produtos WHERE id_produto = '$id_produto_safe'";
$resultado = $conn->query($sql);

if (!$resultado) {
    die("Erro na consulta SQL: " . $conn->error);
}

$produto = $resultado->fetch_assoc();

// Se o produto não existir no banco
if (!$produto) {
    echo "<div style='text-align:center; padding: 50px;'>";
    echo "<h2>Produto não encontrado!</h2>";
    echo "<a href='produtos.php' style='color:#d1a07d;'>Voltar para a lista</a>";
    echo "</div>";
    exit;
}

// Trata os valores da tabela doce_maria
$nome      = $produto['nome_produto'] ?? 'Produto sem nome';
$preco     = $produto['preco'] ?? 0;
$imagem    = $produto['img_produto'] ?? 'cookie.png';
$descricao = $produto['descricao'] ?? 'Sem descrição disponível para este produto.';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($nome); ?> - Detalhes</title>
    <link rel="stylesheet" href="produtos.css">
    <style>
        .container-detalhes {
            max-width: 900px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            display: flex;
            gap: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .detalhes-img {
            width: 300px;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }
        .detalhes-info {
            flex-grow: 1;
        }
        .btn-voltar {
            display: inline-block;
            margin-bottom: 20px;
            color: #d1a07d;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <main class="container-detalhes">
        <div class="detalhes-foto">
            <img src="img/<?php echo htmlspecialchars($imagem); ?>" alt="<?php echo htmlspecialchars($nome); ?>" class="detalhes-img">
        </div>

        <div class="detalhes-info">
            <a href="produtos.php" class="btn-voltar">← Voltar para a lista</a>
            <h1><?php echo htmlspecialchars($nome); ?></h1>
            <h2 style="color: #d1a07d;">R$ <?php echo number_format((float)$preco, 2, ',', '.'); ?></h2>
            
            <p><strong>Descrição:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($descricao)); ?></p>

            <button class="btn-compre" style="margin-top: 20px; padding: 10px 20px; font-size: 16px;">Adicionar ao Carrinho</button>
        </div>
    </main>

</body>
</html>
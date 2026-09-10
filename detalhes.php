<?php
require_once './crud.php';

$idPessoa = $_GET['id'] ?? '';


$stmt = $pdo->prepare('SELECT * FROM dados_pessoais WHERE id = ?');
$stmt->execute([$idPessoa]);
$pessoa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pessoa) {
    echo "Currículo não encontrado!";
    exit;
}

$stmtContatos = $pdo->prepare('SELECT * FROM contatos WHERE dados_pessoais_id = ?');
$stmtContatos->execute([$idPessoa]);
$contatos = $stmtContatos->fetchAll(PDO::FETCH_ASSOC);

$stmtExp = $pdo->prepare('SELECT * FROM experiencias WHERE dados_pessoais_id = ?');
$stmtExp->execute([$idPessoa]);
$experiencias = $stmtExp->fetchAll(PDO::FETCH_ASSOC);

$stmtForm = $pdo->prepare('SELECT * FROM formacao WHERE dados_pessoais_id = ?');
$stmtForm->execute([$idPessoa]);
$formacoes = $stmtForm->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="all.css">

</head>

<body>

    <a href="index.php">Voltar a Página principal</a>

    <br><br>

    <?php
    echo
        '<img src="' . $pessoa['foto'] . '">
            <br><hr><br>

        <h1>Informações Pessoais</h1>
        <h3>' . $pessoa['nome'] . '</h3>
        <h3>' . $pessoa['cargo'] . '</h3>
        <h3>' . $pessoa['bio'] . '</h3>
        <h3>' . $pessoa['info_principal'] . '</h3>
        
    <br><hr><br>';

    echo '<h1>Contatos</h1>';
    foreach ($contatos as $ct) {
        echo '
        <h3>' . ($ct['email']) . '</h3>
        <h3>' . ($ct['telefone']) . '</h3>
        <h3>' . ($ct['perfil_profis']) . '</h3>
        <br><hr><br>';
    }

    echo '<h1>Experiências</h1>';
    foreach ($experiencias as $exp) {
        echo '
        <h3>' . ($exp['empresa']) . '</h3>
        <h3>' . ($exp['funcao']) . '</h3>
        <h3>' . ($exp['periodo_trabalho']) . '</h3>
        <h3>' . ($exp['descricao']) . '</h3>
        <br><hr><br>';
    }

    echo '<h1>Formação</h1>';
    foreach ($formacoes as $forma) {
        echo '
        <h3>' . ($forma['instituicao']) . '</h3>
        <h3>' . ($forma['curso']) . '</h3>
        <h3>' . ($forma['periodo']) . '</h3>
        <br><hr><br>';
    }


    ?>
</body>

</html>
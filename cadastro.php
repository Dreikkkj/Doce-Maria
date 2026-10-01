<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'crud.php'; 
$mensagem = "";
$tipo_mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $senha    = trim($_POST['senha'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $tipo_usuario = 'cliente'; 


    if (empty($nome) || empty($email) || empty($senha)) {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
        $tipo_mensagem = "erro";
    } else {
      
        $query = "SELECT id_user FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $mensagem = "Este e-mail já está cadastrado. Tente outro.";
            $tipo_mensagem = "erro";
        } else {
           
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

           
            $dados = [
                'nome'          => $nome,
                'email'         => $email,
                'senha'         => $senha_hash,
                'telefone'      => $telefone,
                'tipo_usuario'  => $tipo_usuario,
                'pontos_fidelidade' => 0, 
                'data_cadastro' => date('Y-m-d')
            ];

        
            $sucesso = create($pdo, 'usuarios', $dados);

            if ($sucesso) {
                $_SESSION['mensagem'] = "Cadastro realizado com sucesso! Faça login para continuar.";
                $_SESSION['tipo_mensagem'] = "sucesso";
                header("Location: login.php");
                exit();
            } else {
                $mensagem = "Erro ao cadastrar. Tente novamente mais tarde.";
                $tipo_mensagem = "erro";
            }
        }
    }
}


if (!empty($mensagem)) {
    $_SESSION['mensagem'] = $mensagem;
    $_SESSION['tipo_mensagem'] = $tipo_mensagem;
    header("Location: cadastro.php");
    exit();
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
    <link rel="stylesheet" href="cssEloah/identificacao.css">
    <link rel="icon" type="image/png" href="imagens/logo.png">
    <title>Cadastro - DoceMaria</title>
</head>

<body>
    <div class="cadastro-container">
        <div class="cadastro-header">
            <div class="icon-topo">
                <img src="imagens/logo.png" class="logo-form" alt="Logo Doce Maria">
            </div>
            <h1>Crie sua conta! <i class="fa-solid fa-heart color-pink"></i></h1>
            <p>Junte-se à Doce Maria e faça parte dessa história.</p>
        </div>

        <form action="processa_cadastro.php" method="POST">

            <div class="form-group">
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-regular fa-user"></i></span>
                    <input type="text" id="nome" name="nome" placeholder="Nome completo" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                    <input type="email" id="email" name="email" placeholder="E-mail" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="senha" name="senha" placeholder="Senha" required>
                </div>
            </div>


            <button type="submit" class="btn-submit">Cadastrar</button>
        </form>

        <div class="divisor">
            <span>ou</span>
        </div>

        <div class="cadastro-footer">
            <p>
                Já tem uma conta? <a href="login.php">Entrar</a>
            </p>
        </div>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="cssEloah/identificacao.css">
    <link rel="icon" type="image/png" href="">
    <title>Página de Login - DoceMaria</title>
</head>

<body>



</body>

</html>       
    
       <div class="login-container">
        <div class="login-header">
            <img src="imagens/logo.png" class="logo-form">
            <h1>Bem vindo de volta!</h1>
            <p>Faça login para acessar sua conta</p>
        </div>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="usuario">E-mail</label>
                <input
                    type="email"
                    id="usuario"
                    name="usuario"
                    class="input-control"
                    placeholder="exemplo@maria.com"
                    required>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    class="input-control"
                    placeholder="••••••••"
                    required>
            </div>

            <button type="submit" class="btn-submit">Entrar</button>
        </form>

        <div class="login-footer">
            <p style>
                Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a>
            </p>
            <p style>
                <a href="editar_senha.php?modo=recuperar">Esqueceu a senha?</a> 
            </p>
        </div>
    </div>
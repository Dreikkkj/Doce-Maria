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
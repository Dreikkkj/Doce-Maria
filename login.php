<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], (string) ($_POST['csrf_token'] ?? ''))) {
        $error = 'Sua sessão expirou. Atualize a página e tente novamente.';
    } else {
        $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
        $password = (string) ($_POST['senha'] ?? '');

        if ($email !== false && $password !== '') {
            $statement = database()->prepare('SELECT id_user, nome, senha FROM usuarios WHERE email = ?');
            $statement->execute([$email]);
            $user = $statement->fetch();

            if ($user && password_verify($password, $user['senha'])) {
                session_regenerate_id(true);
                $_SESSION['id_user'] = (int) $user['id_user'];
                $_SESSION['nome_usuario'] = $user['nome'];
                header('Location: carrinhopag.php');
                exit;
            }
        }

        $error = 'E-mail ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Doce Maria</title>
    <link rel="stylesheet" href="./andreicss/carrinhopag.css">
    <link rel="stylesheet" href="./css/bia.css">
    <style>
        .auth-page { max-width: 440px; width: calc(100% - 32px); margin: 110px auto 40px; }
        .auth-page .campo-grupo { margin-bottom: 16px; }
        .auth-page label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: 600; }
        .auth-page button { width: 100%; }
        .auth-message { margin-bottom: 16px; color: #b42318; }
        .auth-link { display: block; margin-top: 16px; color: #623822; text-align: center; }
    </style>
</head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main class="auth-page">
    <section class="cartao">
        <h1 class="titulo">Entrar</h1>
        <?php if ($error !== ''): ?><p class="auth-message"><?= escape_html($error) ?></p><?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= escape_html($_SESSION['csrf_token']) ?>">
            <div class="campo-grupo">
                <label for="email">E-mail</label>
                <input class="input-texto" id="email" name="email" type="email" autocomplete="email" required>
            </div>
            <div class="campo-grupo">
                <label for="senha">Senha</label>
                <input class="input-texto" id="senha" name="senha" type="password" autocomplete="current-password" required>
            </div>
            <button class="botao-gorjeta ativo" type="submit">Entrar</button>
        </form>
        <a class="auth-link" href="cadastro.php">Criar uma conta</a>
    </section>
</main>
</body>
</html>
<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], (string) ($_POST['csrf_token'] ?? ''))) {
        $error = 'Sua sessão expirou. Atualize a página e tente novamente.';
    } else {
        $name = trim((string) ($_POST['nome'] ?? ''));
        $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
        $phone = trim((string) ($_POST['telefone'] ?? ''));
        $password = (string) ($_POST['senha'] ?? '');

        if ($name === '' || mb_strlen($name) > 100 || $email === false || mb_strlen($phone) > 20 || strlen($password) < 8) {
            $error = 'Confira os dados. A senha deve ter pelo menos 8 caracteres.';
        } else {
            try {
                $statement = database()->prepare(
                    'INSERT INTO usuarios (nome, email, senha, telefone) VALUES (?, ?, ?, ?)'
                );
                $statement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $phone]);

                session_regenerate_id(true);
                $_SESSION['id_user'] = (int) database()->lastInsertId();
                $_SESSION['nome_usuario'] = $name;
                header('Location: carrinhopag.php');
                exit;
            } catch (PDOException $exception) {
                if ($exception->getCode() === '23000') {
                    $error = 'Este e-mail já está cadastrado.';
                } else {
                    throw $exception;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar conta - Doce Maria</title>
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
        <h1 class="titulo">Criar conta</h1>
        <?php if ($error !== ''): ?><p class="auth-message"><?= escape_html($error) ?></p><?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= escape_html($_SESSION['csrf_token']) ?>">
            <div class="campo-grupo">
                <label for="nome">Nome</label>
                <input class="input-texto" id="nome" name="nome" maxlength="100" autocomplete="name" required>
            </div>
            <div class="campo-grupo">
                <label for="email">E-mail</label>
                <input class="input-texto" id="email" name="email" type="email" maxlength="100" autocomplete="email" required>
            </div>
            <div class="campo-grupo">
                <label for="telefone">Telefone</label>
                <input class="input-texto" id="telefone" name="telefone" maxlength="20" autocomplete="tel">
            </div>
            <div class="campo-grupo">
                <label for="senha">Senha (mínimo 8 caracteres)</label>
                <input class="input-texto" id="senha" name="senha" type="password" minlength="8" autocomplete="new-password" required>
            </div>
            <button class="botao-gorjeta ativo" type="submit">Criar conta</button>
        </form>
        <a class="auth-link" href="login.php">Já tenho uma conta</a>
    </section>
</main>
</body>
</html>
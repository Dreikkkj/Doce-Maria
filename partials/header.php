<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tipo_usuario = $_SESSION['tipo'] ?? 'visitante';

define('BASE_URL', 'http://localhost/Doce-Maria-ofc/');
$base = BASE_URL;
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Belleza&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Qwitcher+Grypen:wght@700&display=swap" rel="stylesheet">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/bia.css">

<header>
    <nav>
        <div class="menu-superior">
            <img src="./Imagens/Doce_Maria.png" alt="Doce Maria Logo" class="header-logo">

            <ul class="nav-links">
                <li><a href="<?= $base ?>index.php">Home</a></li>
                <li><a href="<?= $base ?>sobre.php">Sobre</a></li>
                <li><a href="<?= $base ?>produtos.php">Catálogo</a></li>
            </ul>

            <div class="buttons">
                <?php if ($tipo_usuario === 'visitante'): ?>
                    <li>
                        <a class="btnentrar" href="<?= $base ?>login.php">Entrar</a>
                    </li>
                    <li>
                        <a class="btncad" href="<?= $base ?>cadastro.php">Cadastre-se</a>
                    </li>
                <?php else: ?>

                    <?php
                    if ($tipo_usuario === 'admin') {
                        echo '<a href="' . $base . 'admin/estoque.php">
                        
                                Dashboard
                            </a>
                            <a href="' . $base . 'admin/pedidos.php">
                                
                                Pedidos
                            </a>

                              <form method="POST" action="estoque.php">
                            <button type="submit" class="btn-add" name="logout" style="margin-right: 10px;">
                                <div class="btn-adicionar" style="text-decoration: none;">
                                    <i class="fa-solid fa-list-check"></i> Sair
                                </div>
                            </button>
                            </form>

                            ';
                    } elseif ($tipo_usuario === 'cliente') {
                        echo '
                                <a href="' . $base . 'user/minha_conta.php">
                                  
                                    Perfil
                                </a>
                                
                                <form method="POST" action="minha_conta.php">
                               <button type="submit" class="btn-add" name="logout" style="margin-right: 10px;">
                                  <div class="btn-adicionar" style="text-decoration: none;">
                                    <i class="fa-solid fa-list-check"></i> Logout
                                  </div>
                               </button>
                               </form>
                                ';
                    }
                    ?>

    </nav>
<?php endif; ?>
</div>
</div>
</nav>

</header>
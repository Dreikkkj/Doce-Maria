<header>
    <nav>
            <ul class="menu-superior">
                <h1 class="logo_menu">Doce Maria</h1>
                <?php if (isset($_SESSION['id_user'])): ?>
                <li>
                    <a class="btnentrar" href="./logout.php">Sair</a>
                </li>
                <li>
                    <a class="btncad" href="./carrinhopag.php">Finalizar pedido</a>
                </li>
                <?php else: ?>
                <li>
                    <a class="btnentrar" href="./login.php">Entrar</a>
                </li>
                <li>
                    <a class="btncad" href="./cadastro.php">Cadastre-se</a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
</header>
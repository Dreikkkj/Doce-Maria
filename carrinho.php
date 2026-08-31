<html>

<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./andreicss/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="./css/bia.css">
</head>

<body>
    <?php
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'header.php';
    ?>

    <div class="container">

        <!-- Coleta card -->

        <div class="retirada">
            <h3>Pedido para retirada</h3>
            <div class="coletainfo">
                <h3>Método da Coleta</h3>
                <div class="options">
                    <button class="retirada"><i class="fa-solid fa-bag-shopping"></i>Retirada</button>
                    <button class="delivery"><i class="fa-solid fa-motorcycle"></i>Delivery</button>
                    <p>retire seu pedido no balcão de coleta</p>
                </div>
                <div class="coletanome">
                    <h3>Quem irá coletar seu pedido?</h3>
                    <input type="text" class="nomecoleta">
                </div>
            </div>
        </div>

        <!-- Sacola Card -->

         <div class="sacola2">
            <h3>Minha sacola</h3>
            <div class="produtos-carrinho">
            <img src="https://placehold.co/100x100" class="imagemprodutoxd" alt="Imagem de teste">
            <div class="produto"

            </div>

         </div>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Belleza&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Qwitcher+Grypen:wght@700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/bia.css">
</head>

<body>
    <?php
    require_once "./partials/header.php"
        ?>
    <main>
        <div class="container-foto">
            <img src="./imagens/homeft.png" class="fthome">
            <h1 class="texto-foto">Felicidade em cada pedaço</h1>
            <button type="button" class="btn-foto">Descubra sua Felicidade</button>
        </div>
        <div class="bck-pink"></div>
    </main>

    <section>
        <h3 class="txtseller">Nossos Best <strong class="brw">Sellers</strong></h3>
        <div class="box-seller">
            <div class="box-best">
                <img src="./imagens/Doce_Maria.png" width="300px">
                <h3>Nome Do Produto</h3>
            </div>
            <div class="box-best">
                <img src="./imagens/Doce_Maria.png" width="300px">
                <h3>Nome Do Produto</h3>
            </div>
            <div class="box-best">
                <img src="./imagens/Doce_Maria.png" width="300px">
                <h3>Nome Do Produto</h3>
            </div>
            <div class="box-best">
                <img src="./imagens/Doce_Maria.png" width="300px">
                <h3>Nome Do Produto</h3>
            </div>
        </div>
    </section>

    <article>
        <h3 class="pontos">Cadastra-se, faça seus <br> pedidos e acumule <strong class="brw">pontos</strong></h3>
        <h1 class="cfn">Como funciona?</h1>
        <div class="box-pontos">
            <div class="box-cfn">
                <img src="./imagens/circulods.png" width="200px">
                <strong class="txtpt">Acumule Doces</strong>
                <p class="txtpts">Ganhe doces a cada compra realizada, sem pagar nenhuma taxa para
                    participar.</p>
            </div>
            <div class="box-cfn">
                <img src="./imagens/flowers.png" width="200px">
                <strong class="txtpt">Acompanhe o Progresso</strong>
                <p class="txtpts">Fique de olho no seu saldo e veja sua evolução rumo aos 255
                    doces.</p>
            </div>
            <div class="box-cfn">
                <img src="./imagens/sun.png" width="200px">
                <strong class="txtpt">Desbloqueie Descontos</strong>
                <p class="txtpts">Completou a meta? Resgate um super desconto exclusivo para o seu
                    próximo pedido.</p>
            </div>
            <div class="box-cfn">
                <img src="./imagens/stars.png" width="200px">
                <strong class="txtpt">Peça e Ganhe Mais</strong>
                <p class="txtpts">Quanto mais você pede, mais rápido alcança suas recompensas!</p>
            </div>
        </div>
    </article>

    <article>
        <h3 class="avalia">Últimas Avaliações</h3>
        <h1 class="textcfg">O que nossos clientes estão dizendo sobre a <strong class="cfg">gente!</strong></h1>

        <div class="box-avalia">

            <div class="box-cfg">
                <div class="card-header">
                    <img src="#" class="avatar">
                    <div class="user-info">
                        <strong class="txtavaliar">Mariana Souza</strong>
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
                <p class="txtavalia">
                    Melhor loja virtual de doces! Comprei minhas sobremesas e chocolates favoritos sem precisar sair de
                    casa. Chegou tudo bem protegido e delicioso.
                </p>
                <div class="data">
                    <span class="icon">📅</span> 10-10-2000
                </div>
            </div>

            <div class="box-cfg">
                <div class="card-header">
                    <img src="./imagens/mocasaidasacada.png" class="avatar">
                    <div class="user-info">
                        <strong class="txtavaliar">Mariana Souza</strong>
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
                <p class="txtavalia">
                    Melhor loja virtual de doces! Comprei minhas sobremesas e chocolates favoritos sem precisar sair de
                    casa. Chegou tudo bem protegido e delicioso.
                </p>
                <div class="data">
                    <span class="icon">📅</span> 10-10-2000
                </div>
            </div>

            <div class="box-cfg">
                <div class="card-header">
                    <img src="#" class="avatar">
                    <div class="user-info">
                        <strong class="txtavaliar">Mariana Souza</strong>
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
                <p class="txtavalia">
                    Melhor loja virtual de doces! Comprei minhas sobremesas e chocolates favoritos sem precisar sair de
                    casa. Chegou tudo bem protegido e delicioso.
                </p>
                <div class="data">
                    <span class="icon">📅</span> 10-10-2000
                </div>
            </div>

        </div>
    </article>

    <?php
    require_once "./partials/footer.php"
        ?>

</body>

</html>
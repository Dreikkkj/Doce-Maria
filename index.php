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
            <a href="#"><button type="button" class="btn-foto">Descubra sua Felicidade</button></a>
        </div>
        <div class="bck-pink"></div>

        <section class="container-creme">
            <br>
            <h1 class="title">Nossos Best-<strong class="destaque">Sellers</strong></h1>
            <div class="grid-best">
                <div class="grid-seller">
                    <div class="img-seller">
                        <img src="./imagens/Doce_Maria.png" />
                        <p class="destaque-best">Mais Vendido</p>
                    </div>
                    <h4 class="nomeProduto">Nome do Produto</h4>
                    <div class="avaliacoes">
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                    </div>
                    <p class="price">R$20,00</p>
                    <a href="#" class="btn-add">Adicionar ao Carrinho</a>
                </div>
                <div class="grid-seller">
                    <div class="img-seller">
                        <img src="./imagens/Doce_Maria.png" />
                        <p class="destaque-best">Mais Vendido</p>
                    </div>
                    <h4 class="nomeProduto">Nome do Produto</h4>
                    <div class="avaliacoes">
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                    </div>
                    <p class="price">R$20,00</p>
                    <a href="#" class="btn-add">Adicionar ao Carrinho</a>
                </div>
                <div class="grid-seller">
                    <div class="img-seller">
                        <img src="./imagens/Doce_Maria.png" />
                        <p class="destaque-best">Mais Vendido</p>
                    </div>
                    <h4 class="nomeProduto">Nome do Produto</h4>
                    <div class="avaliacoes">
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                    </div>
                    <p class="price">R$20,00</p>
                    <a href="#" class="btn-add">Adicionar ao Carrinho</a>
                </div>
                <div class="grid-seller">
                    <div class="img-seller">
                        <img src="./imagens/Doce_Maria.png" />
                        <p class="destaque-best">Mais Vendido</p>
                    </div>
                    <h4 class="nomeProduto">Nome do Produto</h4>
                    <div class="avaliacoes">
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                        <img src="./imagens/star.png" class="stars" />
                    </div>
                    <p class="price">R$20,00</p>
                    <a href="#" class="btn-add">Adicionar ao Carrinho</a>
                </div>
            </div>
        </section>

        <section>
            <br>
            <h1 class="titlepts">Cadastre-se, faça seus<br> pedidos e acumule<strong class="destaque">pontos</strong>
            </h1>
            <h1 class="cfn">Como funciona?</h1>
            <div class="grid-pts">
                <div class="box-cfn">
                    <img src="./imagens/acumule.png" width="230px">
                    <strong class="txtpt">Acumule Doces</strong>
                    <p class="txtpts">Ganhe doces a cada compra realizada, sem pagar nenhuma taxa para
                        participar.</p>
                </div>
                <div class="box-cfn">
                    <img src="./imagens/progresso.png" width="230px">
                    <strong class="txtpt">Acompanhe o Progresso</strong>
                    <p class="txtpts">Fique de olho no seu saldo e veja sua evolução rumo aos 255
                        doces.</p>
                </div>
                <div class="box-cfn">
                    <img src="./imagens/descontos.png" width="230px">
                    <strong class="txtpt">Desbloqueie Descontos</strong>
                    <p class="txtpts">Completou a meta? Resgate um super desconto exclusivo para o seu
                        próximo pedido.</p>
                </div>
                <div class="box-cfn">
                    <img src="./imagens/pecaMais.png" width="230px">
                    <strong class="txtpt">Peça e Ganhe Mais</strong>
                    <p class="txtpts">Quanto mais você pede, mais rápido alcança suas recompensas!</p>
                </div>
            </div>
            <a href="#"><button>Criar Conta e Ganhar Pontos</button></a>
        </section>

    </main>

    <?php
    require_once "./partials/footer.php"
        ?>

</body>

</html>
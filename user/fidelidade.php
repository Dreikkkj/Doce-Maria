<?php
session_start();
require_once __DIR__ . '/../php/crud.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado'])) {
    header("Location: ../login.php");
    exit();
}

$usuario = read(
    $pdo,
    'usuarios',
    'id_user = :id_user',
    [':id_user' => (int) $_SESSION['id_user']]
);

if (!$usuario) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}

// Recompensas disponíveis para resgate (pode vir de um banco ou ser estático)
$recompensas = [
    [
        'id' => 1,
        'titulo' => 'Cupom R$ 10,00 de desconto',
        'descricao' => 'Válido em qualquer pedido acima de R$ 30,00.',
        'pontos_necessarios' => 1000,
        'icone' => 'confirmation_number'
    ],
    [
        'id' => 2,
        'titulo' => '1 Donut Especial à sua escolha',
        'descricao' => 'Escolha qualquer donut do nosso cardápio de graça!',
        'pontos_necessarios' => 1500,
        'icone' => 'bakery_dining'
    ],
    [
        'id' => 3,
        'titulo' => 'Caixa Sortida com 6 Brigadeiros',
        'descricao' => 'Caixa presenteável artesanal da Doce Maria.',
        'pontos_necessarios' => 2500,
        'icone' => 'featured_seasonal'
    ]
];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doce Maria | Programa de Fidelidade</title>

    <!-- Fontes e Ícones -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #FAF6F8;
            color: #333;
            padding-bottom: 60px;
        }

        /* Topbar */
        .topbar-cliente {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 25px 60px;
            background-color: transparent;
        }

        .user-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-profile-menu {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar-sm {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: #FCE8EF;
            color: #D81B60;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .user-name-sm {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        /* Container Principal */
        .account-container {
            max-width: 850px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Cabeçalho */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-text h1 {
            font-size: 26px;
            font-weight: 700;
            color: #222;
            margin-bottom: 6px;
        }

        .header-text p {
            font-size: 14px;
            color: #777;
        }

        .btn-voltar {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #FFF0F5;
            color: #333;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-voltar:hover {
            background-color: #FCE8EF;
            color: #F07FA6;
        }

        /* Banner Principal dos Pontos */
        .points-banner {
            background: linear-gradient(135deg, #F07FA6 0%, #D81B60 100%);
            color: white;
            border-radius: 20px;
            padding: 35px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 20px rgba(216, 27, 96, 0.15);
            margin-bottom: 35px;
        }

        .banner-info label {
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .banner-info .total-points {
            font-size: 44px;
            font-weight: 800;
            margin: 8px 0;
            line-height: 1;
        }

        .banner-info p {
            font-size: 13px;
            opacity: 0.85;
        }

        .banner-icon {
            font-size: 64px;
            opacity: 0.3;
        }

        /* Seção Regras / Como Funciona */
        .rules-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 25px 30px;
            border: 1px solid #F0E6EA;
            margin-bottom: 35px;
        }

        .rules-card h3 {
            font-size: 16px;
            font-weight: 700;
            color: #222;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rules-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .rule-item {
            text-align: center;
            padding: 10px;
        }

        .rule-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #FFF0F5;
            color: #D81B60;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 22px;
        }

        .rule-item h4 {
            font-size: 13px;
            font-weight: 700;
            color: #333;
            margin-bottom: 4px;
        }

        .rule-item p {
            font-size: 12px;
            color: #777;
        }

        /* Seção Recompensas */
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #222;
            margin-bottom: 20px;
        }

        .rewards-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .reward-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 20px 25px;
            border: 1px solid #F0E6EA;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s ease;
        }

        .reward-card:hover {
            border-color: #F07FA6;
            box-shadow: 0 4px 12px rgba(240, 127, 166, 0.08);
        }

        .reward-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .reward-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background-color: #FCE8EF;
            color: #D81B60;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reward-details h4 {
            font-size: 15px;
            font-weight: 700;
            color: #222;
            margin-bottom: 4px;
        }

        .reward-details p {
            font-size: 12px;
            color: #777;
        }

        .reward-right {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }

        .reward-points-cost {
            font-size: 14px;
            font-weight: 700;
            color: #D81B60;
        }

        .btn-resgatar {
            background-color: #F07FA6;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-resgatar:hover {
            background-color: #D81B60;
        }

        .btn-resgatar:disabled {
            background-color: #E0E0E0;
            color: #999;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <!-- Menu Superior -->
    <header class="topbar-cliente">
        <div class="user-nav">
            <div class="user-profile-menu">
                <div class="avatar-sm">
                    <?php echo htmlspecialchars(strtoupper(substr($usuario['nome'], 0, 1)), ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <span class="user-name-sm">
                    <?php echo htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </div>
        </div>
    </header>

    <main class="account-container">
        
        <!-- Cabeçalho -->
        <div class="page-header">
            <div class="header-text">
                <h1>Clube Doce Maria</h1>
                <p>Acumule pontos em suas compras e troque por gostosuras!</p>
            </div>
            
            <a href="minha_conta.php" class="btn-voltar">
                <span class="material-symbols-outlined">arrow_back</span>
                Voltar
            </a>
        </div>

        <!-- Banner com Total de Pontos -->
        <div class="points-banner">
            <div class="banner-info">
                <label>Seu Saldo Atual</label>
                <div class="total-points"><?php echo number_format((int) $usuario['pontos_fidelidade'], 0, ',', '.'); ?> Pts</div>
                <p>Você é uma cliente especial no nosso Clube de Fidelidade!</p>
            </div>
            <span class="material-symbols-outlined banner-icon">workspace_premium</span>
        </div>

        <!-- Como Funciona -->
        <section class="rules-card">
            <h3><span class="material-symbols-outlined" style="color: #F07FA6;">info</span> Como funciona o clube?</h3>
            <div class="rules-grid">
                <div class="rule-item">
                    <div class="rule-icon"><span class="material-symbols-outlined">shopping_cart</span></div>
                    <h4>1. Faça seus pedidos</h4>
                    <p>Cada R$ 1,00 em compras na Doce Maria equivale a 10 pontos.</p>
                </div>
                <div class="rule-item">
                    <div class="rule-icon"><span class="material-symbols-outlined">stars</span></div>
                    <h4>2. Acumule pontos</h4>
                    <p>Os pontos são creditados automaticamente após a confirmação do pedido.</p>
                </div>
                <div class="rule-item">
                    <div class="rule-icon"><span class="material-symbols-outlined">card_giftcard</span></div>
                    <h4>3. Troque por prêmios</h4>
                    <p>Escolha seus prêmios abaixo e resgate a qualquer momento!</p>
                </div>
            </div>
        </section>

    </main>

</body>
</html>
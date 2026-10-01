<?php
session_start();
require_once __DIR__ . '/../php/crud.php';

if (!isset($_SESSION['autenticado'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['autenticado']) || ($_SESSION['tipo_usuario'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$produtos = readAll($pdo, 'produtos');

$valorTotalEstoque = 0;
$totalItens = 0;
$estoqueBaixoCount = 0;

if (!empty($produtos)) {
    foreach ($produtos as $prod) {
        $totalItens += $prod['estoque'];
        $valorTotalEstoque += ($prod['preco'] * $prod['estoque']);
        if ($prod['status_estoque'] === 'Estoque baixo') {
            $estoqueBaixoCount++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doce Maria | Controle de Estoque</title>

    <link rel="stylesheet" href="../admin/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        .badge-danger {
            background-color: #FCE8EF;
            color: #DC2626;
        }
    </style>
</head>

<body>
    <header class="topbar-maria">
        <div class="user-profile">
            <div class="user-avatar">M</div>
            <div class="user-info">
                <span class="user-name">Maria</span>
                <span class="user-role">Administradora</span>
            </div>
            <span class="material-symbols-outlined">expand_more</span>
        </div>
    </header>

    <main class="admin-container">
        <div class="admin-header-saudacao">
            <div class="linha-saudacao">
                <h1>Olá, <?php echo htmlspecialchars($_SESSION['nome'] ?? 'Maria', ENT_QUOTES, 'UTF-8'); ?>! <i class="fa-solid fa-heart color-pink"></i></h1>
            </div>
            <p>Aqui está o controle do seu estoque.</p>
        </div>

        <section class="dashboard-cards">
            <div class="card">
                <div class="card-icon"><span class="material-symbols-outlined">payments</span></div>
                <div class="card-info">
                    <h3>Valor total do estoque</h3>
                    <div class="number">R$ <?php echo number_format($valorTotalEstoque, 2, ',', '.'); ?></div>
                </div>
            </div>

            <div class="card">
                <div class="card-icon"><span class="material-symbols-outlined">inventory_2</span></div>
                <div class="card-info">
                    <h3>Itens em estoque</h3>
                    <div class="number"><?php echo $totalItens; ?></div>
                </div>
            </div>

            <div class="card card-warning">
                <div class="card-icon"><span class="material-symbols-outlined">warning</span></div>
                <div class="card-info">
                    <h3>Estoque baixo</h3>
                    <div class="number"><?php echo $estoqueBaixoCount; ?></div>
                </div>
            </div>
        </section>

        <section class="section-box">
            <div class="box-header">
                <div class="box-title">
                    <span class="material-symbols-outlined box-icon">inventory_2</span>
                    <div>
                        <h2>Controle de estoque</h2>
                        <p>Gerencie seus produtos, edite quantidades e mantenha seu estoque sempre atualizado.</p>
                    </div>
                </div>

                <div class="box-actions">

                    <button class="btn-add" style="margin-right: 10px;">
                        <a href="pedidos.php" class="btn-adicionar" style="text-decoration: none;">
                            <i class="fa-solid fa-list-check"></i> Ver Pedidos
                        </a>
                    </button>

                    <button class="btn-add">
                        <a href="adicionar_produto.php" class="btn-adicionar">
                            <i class="fa-solid fa-plus"></i> Adicionar Produto
                        </a>
                    </button>
                </div>
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th>Estoque</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($produtos)): ?>
                    <?php foreach ($produtos as $prod): ?>
                    <tr>
                        <td>
                            <div class="produto-info">
                                <img src="../img/produtos/<?php echo htmlspecialchars($prod['img_produto'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="produto-img">
                                <div class="produto-textos">
                                    <span class="produto-nome"><?php echo htmlspecialchars($prod['nome_produto'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span class="produto-desc"><?php echo htmlspecialchars($prod['peso_tamanho'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-categoria"><?php echo htmlspecialchars($prod['categoria'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars((string) $prod['estoque'], ENT_QUOTES, 'UTF-8'); ?></strong>
                        </td>
                        <td>
                            <?php
                                        $statusClass = 'badge-success';
                                        if ($prod['status_estoque'] === 'Estoque baixo') {
                                            $statusClass = 'badge-warning';
                                        } elseif ($prod['status_estoque'] === 'Esgotado') {
                                            $statusClass = 'badge-danger';
                                        }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($prod['status_estoque'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </td>
                        <td>
                            <div class="acoes-flex">
                                <a href="editar_produto.php?id=<?php echo (int) $prod['id_produto']; ?>" class="btn-icon"><span class="material-symbols-outlined">edit</span></a>
                                <a href="excluir_produto.php?id=<?php echo (int) $prod['id_produto']; ?>" class="btn-icon"><span class="material-symbols-outlined">delete</span></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #9C858D; padding: 20px;">
                            Nenhum produto cadastrado.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>
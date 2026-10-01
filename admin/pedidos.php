<?php
session_start();
require_once __DIR__ . '/../php/crud.php';

if (!isset($_SESSION['autenticado']) || ($_SESSION['tipo_usuario'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$filtro_status = $_GET['status'] ?? 'Todos';
$statusPermitidos = ['Todos', 'Pendente', 'Em preparo', 'Pronto', 'Entregue', 'Cancelado'];
if (!is_string($filtro_status) || !in_array($filtro_status, $statusPermitidos, true)) {
    $filtro_status = 'Todos';
}

$sql = "SELECT p.id_pedido, u.nome AS nome_cliente, p.data_pedido, p.status_pedido, p.valor_total 
        FROM pedidos p 
        INNER JOIN usuarios u ON p.id_cliente = u.id_user";

if ($filtro_status !== 'Todos') {
    $sql .= " WHERE p.status_pedido = :status";
}
$sql .= " ORDER BY p.data_pedido DESC";

$stmt = $pdo->prepare($sql);
if ($filtro_status !== 'Todos') {
    $stmt->bindParam(':status', $filtro_status);
}
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

function formatarData($dataHora) {
    $data = new DateTime($dataHora);
    return $data->format('d/m/Y - H:i');
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doce Maria | Pedidos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #FAFAFA;
            margin: 0;
            padding: 40px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        h1 {
            font-size: 24px;
            margin-bottom: 8px;
        }
        p.subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 40px;
        }
        .tabs {
            display: flex;
            gap: 30px;
            border-bottom: 1px solid #eee;
            margin-bottom: 30px;
        }
        .tabs a {
            text-decoration: none;
            color: #666;
            font-size: 14px;
            font-weight: 500;
            padding-bottom: 15px;
            position: relative;
        }
        .tabs a.active {
            color: #333;
            font-weight: 600;
        }
        .tabs a.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #F07FA6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            text-align: left;
            font-weight: 500;
            color: #666;
            font-size: 14px;
            padding: 15px 10px;
            border-bottom: 1px solid #eee;
        }
        td {
            padding: 20px 10px;
            font-size: 14px;
            border-bottom: 1px solid #f9f9f9;
            color: #333;
        }
        .pedido-id {
            color: #F07FA6;
            font-weight: 600;
        }
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .badge-pendente { background-color: #FFF3E0; color: #E65100; }
        .badge-preparo { background-color: #FFE0B2; color: #E65100; }
        .badge-pronto { background-color: #E3F2FD; color: #1565C0; }
        .badge-entregue { background-color: #E8F5E9; color: #2E7D32; }
        .badge-cancelado { background-color: #F5F5F5; color: #616161; }
        
        .btn-view {
            background-color: #FFF0F5;
            color: #333;
            border: none;
            border-radius: 8px;
            padding: 8px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-view:hover { background-color: #FCE8EF; }
        
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #666;
        }
        .page-numbers {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .page-numbers a, .page-numbers span {
            text-decoration: none;
            color: #333;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }
        .page-numbers .active {
            background-color: #F07FA6;
            color: #fff;
            font-weight: 600;
        }
        .page-numbers a:hover:not(.active) {
            background-color: #eee;
        }

        .header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.header-title h1 {
    margin-bottom: 8px;
}

.header-title p.subtitle {
    margin-bottom: 0;
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
    transition: background-color 0.2s, color 0.2s;
}

.btn-voltar:hover {
    background-color: #FCE8EF;
    color: #F07FA6;
}
    </style>
</head>

<body>
    <div class="container">
        <h1>Pedidos</h1>   <a href="estoque.php" class="btn-voltar">
            <span class="material-symbols-outlined">arrow_back</span>
            Voltar ao Estoque
        </a>
        <p class="subtitle">Acompanhe e gerencie todos os pedidos da sua loja.</p>

        <div class="tabs">
            <?php
            $abas = ['Todos', 'Pendente', 'Em preparo', 'Pronto', 'Entregue', 'Cancelado'];
            foreach ($abas as $aba): 
                $activeClass = ($filtro_status === $aba) ? 'active' : '';
                $url = '?status=' . urlencode($aba);
                $labelAba = $aba === 'Pendente' ? 'Pendentes' : ($aba === 'Pronto' ? 'Prontos' : ($aba === 'Entregue' ? 'Entregues' : ($aba === 'Cancelado' ? 'Cancelados' : $aba)));
            ?>
                <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $activeClass; ?>"><?php echo htmlspecialchars($labelAba, ENT_QUOTES, 'UTF-8'); ?></a>
            <?php endforeach; ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Total</th>

                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pedidos)): ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td class="pedido-id">#<?php echo htmlspecialchars((string) $pedido['id_pedido'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($pedido['nome_cliente'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo formatarData($pedido['data_pedido']); ?></td>
                            <td>
                                <?php
                                $statusClass = '';
                                switch ($pedido['status_pedido']) {
                                    case 'Pendente': $statusClass = 'badge-pendente'; break;
                                    case 'Em preparo': $statusClass = 'badge-preparo'; break;
                                    case 'Pronto': $statusClass = 'badge-pronto'; break;
                                    case 'Entregue': $statusClass = 'badge-entregue'; break;
                                    case 'Cancelado': $statusClass = 'badge-cancelado'; break;
                                }
                                ?>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($pedido['status_pedido'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td>R$ <?php echo number_format((float) $pedido['valor_total'], 2, ',', '.'); ?></td>
            
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">Nenhum pedido encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination">
            <span>Mostrando 1 a <?php echo count($pedidos); ?> de <?php echo count($pedidos); ?> pedidos</span>
            <div class="page-numbers">
                <a href="#"><span class="material-symbols-outlined" style="font-size: 18px;">chevron_left</span></a>
                <span class="active">1</span>
                <a href="#">2</a>
                <a href="#">3</a>
                <span>...</span>
                <a href="#">5</a>
                <a href="#"><span class="material-symbols-outlined" style="font-size: 18px;">chevron_right</span></a>
            </div>
        </div>
    </div>
</body>
</html>
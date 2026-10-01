<?php
session_start();
require_once __DIR__ . '/../php/crud.php';

if (!isset($_SESSION['autenticado']) || ($_SESSION['tipo_usuario'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$idProduto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$idProduto || $idProduto < 1) {
    header('Location: estoque.php');
    exit();
}

try {
    delete($pdo, 'produtos', 'id_produto = ?', [$idProduto]);
} catch (PDOException $exception) {
    if ($exception->getCode() === '23000') {
        http_response_code(409);
        exit('Este produto está vinculado a pedidos e não pode ser excluído.');
    }
    throw $exception;
}

header('Location: estoque.php');
exit();

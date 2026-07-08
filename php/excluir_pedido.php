<?php
require_once 'conexao.php';
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'ID não informado.']);
    exit;
}

$id = $_GET['id'];

try {
    // 1. Primeiro apagamos os doces amarrados a este pedido
    $sqlItens = "DELETE FROM itens_pedido WHERE pedido_id = ?";
    $stmtItens = $conexao->prepare($sqlItens);
    $stmtItens->execute([$id]);

    // 2. Depois apagamos o pedido principal
    $sqlPedido = "DELETE FROM pedidos WHERE id = ?";
    $stmtPedido = $conexao->prepare($sqlPedido);
    $stmtPedido->execute([$id]);

    echo json_encode(['sucesso' => true]);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
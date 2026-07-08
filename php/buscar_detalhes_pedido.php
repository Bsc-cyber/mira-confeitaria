<?php
require_once 'conexao.php';
header('Content-Type: application/json');

// Verifica se o JavaScript mandou o ID do pedido
if (!isset($_GET['id'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'ID do pedido não informado.']);
    exit;
}

$id = $_GET['id'];

try {
    // 1. Busca os dados gerais do pedido na tabela principal
    $sqlPedido = "SELECT * FROM pedidos WHERE id = ?";
    $stmtPedido = $conexao->prepare($sqlPedido);
    $stmtPedido->execute([$id]);
    $pedido = $stmtPedido->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        echo json_encode(['sucesso' => false, 'erro' => 'Pedido não encontrado no banco.']);
        exit;
    }

    // 2. Busca todos os doces vinculados a este pedido na tabela secundária
    $sqlItens = "SELECT produto, quantidade, preco_total FROM itens_pedido WHERE pedido_id = ?";
    $stmtItens = $conexao->prepare($sqlItens);
    $stmtItens->execute([$id]);
    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

    // Junta tudo num pacote só e devolve para o JavaScript
    $pedido['itens'] = $itens;
    echo json_encode(['sucesso' => true, 'dados' => $pedido]);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
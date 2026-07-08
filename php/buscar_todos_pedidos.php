<?php
require_once 'conexao.php';
header('Content-Type: application/json');

try {
    // Busca todos os pedidos ordenados do mais novo para o mais antigo (DESC)
    // E já conta quantos doces tem dentro de cada pedido usando uma subquery
    $sql = "SELECT p.id, p.cliente, p.status, p.total, 
                   (SELECT COUNT(*) FROM itens_pedido i WHERE i.pedido_id = p.id) as qtd_itens
            FROM pedidos p 
            ORDER BY p.id DESC";
            
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['sucesso' => true, 'pedidos' => $pedidos]);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
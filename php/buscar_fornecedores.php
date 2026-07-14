<?php
require_once 'conexao.php';
header('Content-Type: application/json');

try {
    $sql = "SELECT * FROM fornecedores ORDER BY id DESC";
    $stmt = $conexao->query($sql);
    $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['sucesso' => true, 'fornecedores' => $fornecedores]);
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
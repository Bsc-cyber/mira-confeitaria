<?php
require_once 'conexao.php';
header('Content-Type: application/json');

try {
    $sql = "SELECT * FROM receitas ORDER BY id DESC";
    $stmt = $conexao->query($sql);
    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['sucesso' => true, 'receitas' => $receitas]);
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
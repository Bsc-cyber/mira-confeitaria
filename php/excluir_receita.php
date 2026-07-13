<?php
require_once 'conexao.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    try {
        $sql = "DELETE FROM receitas WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$_GET['id']]);
        
        echo json_encode(['sucesso' => true]);
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
    }
} else {
    echo json_encode(['sucesso' => false, 'erro' => 'ID não fornecido.']);
}
?>
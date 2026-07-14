<?php
require_once 'conexao.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    try {
        $sql = "DELETE FROM fornecedores WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$_GET['id']]);
        echo json_encode(['sucesso' => true]);
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
    }
}
?>
<?php
require_once 'conexao.php';
header('Content-Type: application/json');

try {
    // Busca todos os produtos, mostrando os recém-cadastrados primeiro
    $sql = "SELECT * FROM produtos ORDER BY id DESC";
    $stmt = $conexao->query($sql);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['sucesso' => true, 'produtos' => $produtos]);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
<?php
require_once 'conexao.php';
header('Content-Type: application/json');

$conteudo = file_get_contents('php://input');
$dados = json_decode($conteudo, true);

if (!isset($dados['id']) || !isset($dados['status'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Dados de atualização incompletos.']);
    exit;
}

try {
    // Atualiza apenas a coluna de status do ID informado
    $sql = "UPDATE pedidos SET status = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$dados['status'], $dados['id']]);

    echo json_encode(['sucesso' => true, 'mensagem' => 'Status atualizado com sucesso no banco!']);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
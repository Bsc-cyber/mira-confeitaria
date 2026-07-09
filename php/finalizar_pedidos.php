<?php
require_once 'conexao.php';
header('Content-Type: application/json');

$conteudo = file_get_contents('php://input');
$dados = json_decode($conteudo, true);

// Verifica se chegou alguma lista de IDs
if (!isset($dados['ids']) || !is_array($dados['ids']) || count($dados['ids']) === 0) {
    echo json_encode(['sucesso' => false, 'erro' => 'Nenhum pedido foi selecionado.']);
    exit;
}

try {
    // Cria pontos de interrogação baseados na quantidade de pedidos selecionados (?, ?, ?)
    $interrogacoes = implode(',', array_fill(0, count($dados['ids']), '?'));
    
    // Atualiza todos de uma vez só!
    $sql = "UPDATE pedidos SET status = 'Finalizado' WHERE id IN ($interrogacoes)";
    $stmt = $conexao->prepare($sql);
    $stmt->execute($dados['ids']);

    echo json_encode(['sucesso' => true, 'mensagem' => 'Pedidos finalizados!']);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
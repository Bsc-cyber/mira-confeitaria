<?php
require_once 'conexao.php';
header('Content-Type: application/json');

$conteudo = file_get_contents('php://input');
$dados = json_decode($conteudo, true);

if (empty($dados['nome']) || empty($dados['ingredientes']) || empty($dados['preparo'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Preencha todos os campos obrigatórios.']);
    exit;
}

try {
    if (!empty($dados['id'])) {
        // ATUALIZAR UMA RECEITA EXISTENTE
        $sql = "UPDATE receitas SET nome = ?, ingredientes = ?, preparo = ? WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$dados['nome'], $dados['ingredientes'], $dados['preparo'], $dados['id']]);
        $mensagem = "Receita atualizada com sucesso!";
    } else {
        // INSERIR UMA RECEITA NOVA
        $sql = "INSERT INTO receitas (nome, ingredientes, preparo) VALUES (?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$dados['nome'], $dados['ingredientes'], $dados['preparo']]);
        $mensagem = "Receita guardada com sucesso!";
    }
    echo json_encode(['sucesso' => true, 'mensagem' => $mensagem]);
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
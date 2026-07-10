<?php
require_once 'conexao.php';
header('Content-Type: application/json');

$conteudo = file_get_contents('php://input');
$dados = json_decode($conteudo, true);

// Validação de campos obrigatórios
if (empty($dados['nome']) || empty($dados['categoria']) || empty($dados['preco'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Preencha todos os campos obrigatórios (*).']);
    exit;
}

try {
    // Limpa a formatação do preço (transforma "89,90" em "89.90" para o banco)
    $precoLimpo = str_replace(['R$', ' '], '', $dados['preco']);
    $precoLimpo = str_replace(',', '.', $precoLimpo);
    
    // Converte o booleano do status para 1 (Ativo) ou 0 (Inativo)
    $statusNum = $dados['status'] ? 1 : 0;

    if (!empty($dados['id'])) {
        // ATUALIZAR PRODUTO EXISTENTE
        $sql = "UPDATE produtos SET nome = ?, categoria = ?, tamanho = ?, preco = ?, sabores = ?, descricao = ?, status = ? WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            $dados['nome'], $dados['categoria'], $dados['tamanho'], $precoLimpo, 
            $dados['sabores'], $dados['descricao'], $statusNum, $dados['id']
        ]);
        $mensagem = "Produto atualizado com sucesso!";
    } else {
        // CADASTRAR NOVO PRODUTO
        $sql = "INSERT INTO produtos (nome, categoria, tamanho, preco, sabores, descricao, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            $dados['nome'], $dados['categoria'], $dados['tamanho'], $precoLimpo, 
            $dados['sabores'], $dados['descricao'], $statusNum
        ]);
        $mensagem = "Produto cadastrado com sucesso!";
    }

    echo json_encode(['sucesso' => true, 'mensagem' => $mensagem]);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
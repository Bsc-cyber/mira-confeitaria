<?php
require_once 'conexao.php';
header('Content-Type: application/json');

$conteudo = file_get_contents('php://input');
$dados = json_decode($conteudo, true);

if (empty($dados['nome']) || empty($dados['tipo'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Preencha os campos obrigatórios (*).']);
    exit;
}

try {
    if (!empty($dados['id'])) {
        // ATUALIZA FORNECEDOR EXISTENTE
        $sql = "UPDATE fornecedores SET nome=?, tipo=?, cnpj=?, telefone=?, email=?, rua=?, numero=?, bairro=?, cidade=?, complemento=?, info_adicional=? WHERE id=?";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            $dados['nome'], $dados['tipo'], $dados['cnpj'], $dados['telefone'], $dados['email'], 
            $dados['rua'], $dados['numero'], $dados['bairro'], $dados['cidade'], $dados['complemento'], 
            $dados['info_adicional'], $dados['id']
        ]);
        $mensagem = "Fornecedor atualizado com sucesso!";
    } else {
        // CADASTRA NOVO FORNECEDOR
        $sql = "INSERT INTO fornecedores (nome, tipo, cnpj, telefone, email, rua, numero, bairro, cidade, complemento, info_adicional) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            $dados['nome'], $dados['tipo'], $dados['cnpj'], $dados['telefone'], $dados['email'], 
            $dados['rua'], $dados['numero'], $dados['bairro'], $dados['cidade'], $dados['complemento'], 
            $dados['info_adicional']
        ]);
        $mensagem = "Fornecedor cadastrado com sucesso!";
    }
    echo json_encode(['sucesso' => true, 'mensagem' => $mensagem]);
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
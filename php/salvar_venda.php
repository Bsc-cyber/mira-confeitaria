<?php
require_once 'conexao.php';
header('Content-Type: application/json');

$conteudo = file_get_contents('php://input');
$dados = json_decode($conteudo, true);

if (empty($dados['itens'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'O carrinho está vazio.']);
    exit;
}

try {
    // Inicia a transação de segurança (ou salva tudo, ou cancela tudo se der erro)
    $conexao->beginTransaction();

    // 1. Salva o recibo principal na tabela 'vendas'
    $sqlVenda = "INSERT INTO vendas (cliente, subtotal, desconto, total_liquido, forma_pagamento, pedido_origem_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sqlVenda);
    $stmt->execute([
        $dados['cliente'], 
        $dados['subtotal'], 
        $dados['desconto'],
        $dados['total_liquido'], 
        $dados['forma_pagamento'], 
        $dados['pedido_origem_id']
    ]);
    
    // Pega o número do cupom que acabou de ser gerado
    $idVenda = $conexao->lastInsertId();

    // 2. Salva cada doce na tabela 'vendas_itens'
    $sqlItem = "INSERT INTO vendas_itens (venda_id, produto_nome, quantidade, preco_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
    $stmtItem = $conexao->prepare($sqlItem);
    
    foreach ($dados['itens'] as $item) {
        $stmtItem->execute([
            $idVenda, 
            $item['nome'], 
            $item['qtd'], 
            $item['preco'], 
            $item['subtotal']
        ]);
    }


    // 3. SE VEIO DE PEDIDO(S), ATUALIZA TODOS ELES PARA "CONCLUÍDO/PAGO"
    if (!empty($dados['pedido_origem_id'])) {
        // Divide os IDs caso venha mais de um separado por vírgula (Ex: "13,14")
        $idsArray = explode(',', $dados['pedido_origem_id']);
        
        // Cria a quantidade necessária de interrogações "?" para a query SQL (Ex: "?, ?")
        $interrogacoes = implode(',', array_fill(0, count($idsArray), '?'));
        
        $sqlPed = "UPDATE pedidos SET status = 'Concluído' WHERE id IN ($interrogacoes)";
        $stmtPed = $conexao->prepare($sqlPed);
        $stmtPed->execute($idsArray);
    }

    $conexao->commit(); // Confirma o salvamento no banco
    echo json_encode(['sucesso' => true, 'id_venda' => $idVenda]);

} catch (Exception $e) {
    $conexao->rollBack(); // Desfaz caso dê erro de queda de energia ou afins
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
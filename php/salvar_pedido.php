<?php
// 1. Chama a conexão com o banco de dados
require_once 'conexao.php';

// 2. Avisa que a resposta será no formato JSON
header('Content-Type: application/json');

// 3. Recebe o "pacote" enviado pelo JavaScript
$conteudo = file_get_contents('php://input');
$dados = json_decode($conteudo, true);

// Se não chegou nada, devolve um erro
if (!$dados) {
    echo json_encode(['sucesso' => false, 'erro' => 'Nenhum dado foi recebido pelo servidor.']);
    exit;
}

try {
    // Inicia a transação usando a variável correta: $conexao
    $conexao->beginTransaction();

    // 4. Salva os dados gerais na tabela 'pedidos'
    $sqlPedido = "INSERT INTO pedidos (cliente, data_pedido, data_entrega, status, total) VALUES (?, ?, ?, ?, ?)";
    $stmtPedido = $conexao->prepare($sqlPedido);
    $stmtPedido->execute([
        $dados['cliente'], 
        $dados['data_pedido'], 
        $dados['data_entrega'], 
        $dados['status'], 
        $dados['total']
    ]);

    // Pega o ID do pedido que acabou de ser salvo
    $id_pedido = $conexao->lastInsertId();

    // 5. Salva cada doce do carrinho na tabela 'itens_pedido'
    $sqlItem = "INSERT INTO itens_pedido (pedido_id, produto, sabor, tamanho, quantidade, preco_total) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtItem = $conexao->prepare($sqlItem);

    foreach ($dados['itens'] as $item) {
        $stmtItem->execute([
            $id_pedido, 
            $item['produtoNome'], 
            $item['sabor'], 
            $item['tamanho'], 
            $item['qtd'], 
            $item['precoTotalItem']
        ]);
    }

    // Se chegou até aqui sem erros, confirma o salvamento
    $conexao->commit();

    // Avisa ao JavaScript que deu tudo certo e DEVOLVE O ID GERADO!
    echo json_encode([
        'sucesso' => true, 
        'mensagem' => 'Pedido salvo com sucesso!',
        'id_pedido' => $id_pedido
    ]);

} catch (Exception $e) {
    // Se der erro, desfaz tudo usando a variável correta
    $conexao->rollBack();
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
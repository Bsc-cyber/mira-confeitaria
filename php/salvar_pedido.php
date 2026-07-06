<?php
// 1. Chama a conexão com o banco de dados
require_once 'conexao.php';

// 2. Avisa que a resposta será no formato JSON (formato que o JavaScript entende)
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
    // Inicia uma "Transação" (Se der erro em algum doce, ele cancela tudo para não salvar pedido pela metade)
    $pdo->beginTransaction();

    // 4. Salva os dados gerais na tabela 'pedidos'
    $sqlPedido = "INSERT INTO pedidos (cliente, data_pedido, data_entrega, status, total) VALUES (?, ?, ?, ?, ?)";
    $stmtPedido = $pdo->prepare($sqlPedido);
    $stmtPedido->execute([
        $dados['cliente'], 
        $dados['data_pedido'], 
        $dados['data_entrega'], 
        $dados['status'], 
        $dados['total']
    ]);

    // Pega o ID do pedido que acabou de ser salvo (ex: Pedido nº 15)
    $id_pedido = $pdo->lastInsertId();

    // 5. Salva cada doce do carrinho na tabela 'itens_pedido', vinculando ao ID do pedido
    $sqlItem = "INSERT INTO itens_pedido (pedido_id, produto, sabor, tamanho, quantidade, preco_total) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtItem = $pdo->prepare($sqlItem);

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

    // Se chegou até aqui sem erros, confirma o salvamento no banco
    $pdo->commit();

    // Avisa ao JavaScript que deu tudo certo!
    echo json_encode(['sucesso' => true, 'mensagem' => 'Pedido salvo com sucesso!']);

} catch (Exception $e) {
    // Se algo der errado (ex: nome da tabela errado), desfaz tudo e avisa o erro
    $pdo->rollBack();
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
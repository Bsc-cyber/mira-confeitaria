<?php
require_once 'conexao.php';
header('Content-Type: application/json');

// 1. Lê o que o JavaScript enviou pelos filtros
$filtroData   = isset($_GET['data']) ? $_GET['data'] : '';
$filtroStatus = isset($_GET['status']) ? $_GET['status'] : '';
$filtroBusca  = isset($_GET['busca']) ? $_GET['busca'] : '';

$where = [];
$parametros = [];

// 2. Se escolheu Data (Filtra pela data de entrega)
if (!empty($filtroData)) {
    $where[] = "p.data_entrega = ?"; // Mude para p.data_pedido se preferir filtrar pela data de criação
    $parametros[] = $filtroData;
}

// 3. Se escolheu Status (Ignora se for "Todos" ou vazio)
if (!empty($filtroStatus) && $filtroStatus !== 'Todos' && $filtroStatus !== 'Status') {
    $where[] = "p.status = ?";
    $parametros[] = $filtroStatus;
}

// 4. Se digitou algo na Busca (Ignora maiúsculas/minúsculas e busca por partes)
if (!empty($filtroBusca)) {
    // O comando LOWER() transforma tanto o banco quanto o que você digitou em minúsculas para comparar
    // Os símbolos % antes e depois da variável garantem que ele procure aquele texto em qualquer parte do nome
    $where[] = "(LOWER(p.cliente) LIKE LOWER(?) OR p.id LIKE ?)";
    $parametros[] = "%$filtroBusca%";
    $parametros[] = "%$filtroBusca%";
}
}

try {
    $sql = "SELECT p.id, p.cliente, p.status, p.total, p.data_pedido, p.data_entrega, 
                   (SELECT COUNT(*) FROM itens_pedido i WHERE i.pedido_id = p.id) as qtd_itens
            FROM pedidos p";

    // Se existe algum filtro preenchido, adiciona o "WHERE" no código SQL
    if (count($where) > 0) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    $sql .= " ORDER BY p.id DESC"; // Ordena do mais recente para o mais antigo
            
    $stmt = $conexao->prepare($sql);
    $stmt->execute($parametros);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['sucesso' => true, 'pedidos' => $pedidos]);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
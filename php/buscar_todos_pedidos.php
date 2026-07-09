<?php
require_once 'conexao.php';
header('Content-Type: application/json');

$filtroData   = isset($_GET['data']) ? $_GET['data'] : '';
$filtroStatus = isset($_GET['status']) ? $_GET['status'] : '';
$filtroBusca  = isset($_GET['busca']) ? $_GET['busca'] : '';

$where = [];
$parametros = [];

// 1. Filtro de Data (Proteção DATE() ignora as horas, minutos e segundos do banco)
if (!empty($filtroData)) {
    $where[] = "DATE(p.data_entrega) = ?";
    $parametros[] = $filtroData;
}

// 2. Filtro de Status
if (!empty($filtroStatus) && $filtroStatus !== 'Todos' && $filtroStatus !== 'Status') {
    $where[] = "p.status = ?";
    $parametros[] = $filtroStatus;
}

// 3. Filtro de Busca por Nome ou ID (Ignora maiúsculas/minúsculas)
if (!empty($filtroBusca)) {
    $where[] = "(LOWER(p.cliente) LIKE LOWER(?) OR p.id LIKE ?)";
    $parametros[] = "%$filtroBusca%";
    $parametros[] = "%$filtroBusca%";
}

try {
    // Comando SQL principal
    $sql = "SELECT p.id, p.cliente, p.status, p.total, p.data_pedido, p.data_entrega, 
                   (SELECT COUNT(*) FROM itens_pedido i WHERE i.pedido_id = p.id) as qtd_itens
            FROM pedidos p";

    // Adiciona os filtros na query se houver algum
    if (count($where) > 0) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    
    $sql .= " ORDER BY p.id DESC"; 
            
    $stmt = $conexao->prepare($sql);
    $stmt->execute($parametros);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolve para o JavaScript
    echo json_encode(['sucesso' => true, 'pedidos' => $pedidos]);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>
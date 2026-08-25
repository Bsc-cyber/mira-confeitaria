<?php 
// 1. SEGURANÇA MÁXIMA: Valida se o usuário fez login
require_once "home.php"; 

// 2. CONEXÃO COM O BANCO DE DADOS
// Ajuste este caminho de acordo com a localização do seu arquivo de conexão
require_once "php/conexao.php"; 

// ==========================================================================
// MOTOR DE ESTATÍSTICAS (BUSCANDO DADOS REAIS DO BANCO)
// ==========================================================================
try {
    // 1. FATURAMENTO TOTAL, PEDIDOS E TICKET MÉDIO (Apenas Finalizados)
    $stmt = $conexao->query("SELECT SUM(total) as faturamento, COUNT(id) as qtd_pedidos FROM pedidos WHERE status = 'Finalizado'");
    $dadosVendas = $stmt->fetch(PDO::FETCH_ASSOC);
    $faturamentoTotal = $dadosVendas['faturamento'] ?? 0;
    $qtdPedidos = $dadosVendas['qtd_pedidos'] ?? 0;
    $ticketMedio = $qtdPedidos > 0 ? ($faturamentoTotal / $qtdPedidos) : 0;

    // 2. TOTAL DE ITENS VENDIDOS (Soma das quantidades dos pedidos finalizados)
    $stmtItens = $conexao->query("SELECT SUM(i.quantidade) as total_itens FROM itens_pedido i JOIN pedidos p ON i.pedido_id = p.id WHERE p.status = 'Finalizado'");
    $totalItens = $stmtItens->fetch(PDO::FETCH_ASSOC)['total_itens'] ?? 0;

    // 3. CONTAGENS PARA O RODAPÉ (Clientes, Produtos, Pendentes, Atrasados)
    $qtdClientes = $conexao->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
    $qtdProdutos = $conexao->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
    $qtdPendentes = $conexao->query("SELECT COUNT(*) FROM pedidos WHERE status = 'Pendente'")->fetchColumn();
    
    // Considera atrasado qualquer pedido não finalizado cuja data de entrega seja menor que hoje
    $qtdAtrasados = $conexao->query("SELECT COUNT(*) FROM pedidos WHERE status != 'Finalizado' AND data_entrega < CURDATE()")->fetchColumn();

    // 4. RANKING DOS 5 PRODUTOS MAIS VENDIDOS
    $stmtTop = $conexao->query("
        SELECT produto, SUM(preco_total) as receita 
        FROM itens_pedido i 
        JOIN pedidos p ON i.pedido_id = p.id 
        WHERE p.status = 'Finalizado' 
        GROUP BY produto 
        ORDER BY receita DESC 
        LIMIT 5
    ");
    $topProdutos = $stmtTop->fetchAll(PDO::FETCH_ASSOC);
    
    // Descobre qual é o produto mais vendido para calcular a largura da barra (100%)
    $maxReceitaProduto = !empty($topProdutos) ? $topProdutos[0]['receita'] : 1;

    // 5. GRÁFICO DE BARRAS: Vendas dos Últimos 7 Dias
    $stmt7Dias = $conexao->query("
        SELECT DATE(data_pedido) as data, SUM(total) as diario 
        FROM pedidos 
        WHERE status = 'Finalizado' AND data_pedido >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
        GROUP BY DATE(data_pedido) 
        ORDER BY data ASC
    ");
    $vendas7Dias = $stmt7Dias->fetchAll(PDO::FETCH_ASSOC);
    
    $maxVendaDiaria = 1;
    foreach($vendas7Dias as $v) {
        if($v['diario'] > $maxVendaDiaria) $maxVendaDiaria = $v['diario'];
    }

} catch (PDOException $e) {
    echo "<script>alert('Erro ao carregar os dados do Dashboard: " . $e->getMessage() . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Home</title>
    <!-- CSS na ordem exata e separada exigida pela banca -->
    <link rel="stylesheet" href="css/barra_lateral.css">
    <link rel="stylesheet" href="css/home.css">
</head>

<body>

    <div class="container-dashboard">
        
        <!-- INJEÇÃO DA BARRA LATERAL ISOLADA VIA PHP -->
        <?php require_once "php/barra_lateral.php"; ?>

        <main class="painel-conteudo">
            
            <header class="topo-dashboard">
                <div class="saudacao">
                    <span class="usuario-log">Olá, Lucas! </span>
                    <h1>Bem-vindo ao Mira Confeitaria</h1>
                    <p class="sub-painel">Aqui está o resumo do que acontece no seu negócio hoje.</p>
                </div>
                <div class="controles-topo">
                    <div class="seletor-data"><?= date('d/m/Y') ?></div>
                    <button class="btn-atualizar" onclick="window.location.reload();">Atualizar</button>
                </div>
            </header>

            <!-- LINHA SUPERIOR DE DESEMPENHO (DADOS REAIS) -->
            <section class="linha-mini-cards">
                <div class="mini-card">
                    <div class="icone-card verde">
                        <svg class="svg-painel" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div class="dados-card">
                        <span>Faturamento Total</span>
                        <h3>R$ <?= number_format($faturamentoTotal, 2, ',', '.') ?></h3>
                        <small class="texto-positivo">Baseado em pedidos finalizados</small>
                    </div>
                </div>
                <div class="mini-card">
                    <div class="icone-card laranja">
                        <svg class="svg-painel" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    </div>
                    <div class="dados-card">
                        <span>Pedidos Concluídos</span>
                        <h3><?= $qtdPedidos ?></h3>
                        <small class="texto-positivo">Apenas pedidos entregues</small>
                    </div>
                </div>
                <div class="mini-card">
                    <div class="icone-card roxo">
                        <svg class="svg-painel" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <div class="dados-card">
                        <span>Ticket Médio</span>
                        <h3>R$ <?= number_format($ticketMedio, 2, ',', '.') ?></h3>
                        <small class="texto-positivo">Valor médio gasto por cliente</small>
                    </div>
                </div>
                <div class="mini-card">
                    <div class="icone-card rosa">
                        <svg class="svg-painel" viewBox="0 0 24 24"><path d="M12 2a9 9 0 0 1 9 9v1H3v-1a9 9 0 0 1 9-9z"/><rect x="3" y="14" width="18" height="4" rx="1"/><line x1="6" y1="14" x2="6" y2="18"/><line x1="18" y1="14" x2="18" y2="18"/></svg>
                    </div>
                    <div class="dados-card">
                        <span>Itens Vendidos</span>
                        <h3><?= $totalItens ?></h3>
                        <small class="texto-positivo">Total de doces fabricados</small>
                    </div>
                </div>
            </section>

            <!-- LINHA CENTRAL: RESUMO FINANCEIRO -->
            <section class="linha-blocos-graficos">
                <div class="caixa-grafico flex-7">
                    <div class="topo-caixa">
                        <h3>Resumo Financeiro</h3>
                        <span class="filtro-drop">Geral ▾</span>
                    </div>
                    <div class="grid-valores-resumo">
                        <div class="bloco-valor"><small>Receitas (Faturamento)</small> <strong class="cor-verde">R$ <?= number_format($faturamentoTotal, 2, ',', '.') ?></strong></div>
                        <div class="bloco-valor"><small>Despesas Estimadas (30%)</small> <strong class="cor-vermelha">R$ <?= number_format($faturamentoTotal * 0.3, 2, ',', '.') ?></strong></div>
                        <div class="bloco-valor"><small>Lucro Líquido</small> <strong class="cor-azul">R$ <?= number_format($faturamentoTotal * 0.7, 2, ',', '.') ?></strong></div>
                    </div>
                    <div class="grafico-linhas-ficticio">
                        <div class="grid-linhas-fundo"></div>
                        <div class="vetor-linha-receitas"></div>
                        <div class="vetor-linha-despesas" style="top: 55px;"></div>
                    </div>
                </div>

                <div class="caixa-grafico flex-5">
                    <div class="topo-caixa">
                        <h3>Vendas por Categoria</h3>
                    </div>
                    <div class="conteudo-donut">
                        <div class="grafico-donut-css">
                            <div class="miolo-branco"></div>
                        </div>
                        <div class="legenda-categorias">
                            <div class="item-legenda"><span class="marcador-cor" style="background:#10b981;"></span> Bolos <span class="valor-cat">41,8%</span></div>
                            <div class="item-legenda"><span class="marcador-cor" style="background:#f97316;"></span> Doces <span class="valor-cat">24,9%</span></div>
                            <div class="item-legenda"><span class="marcador-cor" style="background:#8b5cf6;"></span> Tortas <span class="valor-cat">18,6%</span></div>
                            <div class="item-legenda"><span class="marcador-cor" style="background:#ef4444;"></span> Salgados <span class="valor-cat">9,8%</span></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- LINHA INFERIOR: GRÁFICOS E RANKING -->
            <section class="linha-blocos-graficos">
                
                <!-- Histórico de 7 dias Dinâmico -->
                <div class="caixa-grafico flex-6">
                    <div class="topo-caixa">
                        <h3>Vendas dos Últimos 7 Dias</h3>
                        <span class="filtro-drop">Últimos 7 dias ▾</span>
                    </div>
                    <div class="grafico-barras-css">
                        <?php if (empty($vendas7Dias)): ?>
                            <p style="font-size: 0.7rem; color: #6b7280;">Nenhuma venda registrada nos últimos 7 dias.</p>
                        <?php else: ?>
                            <?php foreach($vendas7Dias as $dia): 
                                $alturaBarra = ($dia['diario'] / $maxVendaDiaria) * 100;
                            ?>
                                <div class="coluna-barra" style="height: <?= $alturaBarra ?>%;" title="R$ <?= number_format($dia['diario'], 2, ',', '.') ?>"></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Produtos Mais Vendidos Dinâmico -->
                <div class="caixa-grafico flex-6">
                    <div class="topo-caixa">
                        <h3>Produtos Mais Vendidos</h3>
                    </div>
                    <ul class="ranking-produtos">
                        <?php if (empty($topProdutos)): ?>
                            <li style="color: #6b7280; font-size: 0.7rem;">Nenhum produto vendido ainda.</li>
                        <?php else: ?>
                            <?php foreach ($topProdutos as $index => $prod): 
                                $percentual = ($prod['receita'] / $maxReceitaProduto) * 100;
                            ?>
                                <li>
                                    <span class="nome-prod"><?= ($index + 1) ?>. <?= htmlspecialchars($prod['produto']) ?></span> 
                                    <span class="barra-progresso-ficticia">
                                        <div style="height: 100%; background-color: #172016; width: <?= $percentual ?>%;"></div>
                                    </span> 
                                    <strong>R$ <?= number_format($prod['receita'], 2, ',', '.') ?></strong>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </section>

            <!-- RODAPÉ DE MÉTRICAS SIMPLES (DADOS REAIS) -->
            <section class="linha-mini-cards metricas-rodape">
                <div class="card-rodape-simples">
                    <div class="icone-rodape verde-txt">
                        <svg class="svg-mini" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <div class="dados-rodape">
                        <span>Clientes Cadastrados</span>
                        <h4><?= $qtdClientes ?></h4>
                        <small class="texto-positivo">Base de contatos</small>
                    </div>
                </div>
                <div class="card-rodape-simples">
                    <div class="icone-rodape laranja-txt">
                        <svg class="svg-mini" viewBox="0 0 24 24"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                    </div>
                    <div class="dados-rodape">
                        <span>Produtos no Cardápio</span>
                        <h4><?= $qtdProdutos ?></h4>
                        <small class="texto-neutro">Ativos para venda</small>
                    </div>
                </div>
                <div class="card-rodape-simples">
                    <div class="icone-rodape roxo-txt">
                        <svg class="svg-mini" viewBox="0 0 24 24"><polyline points="12 5 19 12 12 19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </div>
                    <div class="dados-rodape">
                        <span>Pedidos Pendentes</span>
                        <h4><?= $qtdPendentes ?></h4>
                        <small class="texto-link" onclick="window.location.href='php/pedidos.php';" style="cursor: pointer;">Ir para produção →</small>
                    </div>
                </div>
                <div class="card-rodape-simples">
                    <div class="icone-rodape vermelho-txt">
                        <svg class="svg-mini" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                    <div class="dados-rodape">
                        <span>Pedidos Atrasados</span>
                        <h4><?= $qtdAtrasados ?></h4>
                        <small class="texto-link" onclick="window.location.href='php/pedidos.php';" style="cursor: pointer;">Atenção imediata →</small>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script src="js/barra_lateral.js"></script>
</body>
</html>

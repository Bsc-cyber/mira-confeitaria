<?php 
// 1. SEGURANÇA MÁXIMA: Valida se o usuário fez login
require_once "home.php"; 

// 2. CONEXÃO COM O BANCO DE DADOS
// Ajuste este caminho de acordo com a localização do seu arquivo de conexão
require_once "php/conexao.php"; 

// Inicializa variáveis para evitar erros visuais caso alguma tabela esteja vazia
$faturamentoTotal = 0;
$qtdVendas = 0;
$ticketMedio = 0;
$totalItens = 0;
$qtdClientes = 0;
$qtdProdutos = 0;
$qtdPendentes = 0;
$qtdAtrasados = 0;
$topProdutos = [];
$vendas7Dias = [];
$maxReceitaProduto = 1;
$maxVendaDiaria = 1;

// ==========================================================================
// MOTOR DE ESTATÍSTICAS (BUSCANDO DADOS REAIS DO BANCO)
// ==========================================================================

// BLOCO 1: FINANCEIRO E VENDAS (Vem EXCLUSIVAMENTE da tabela 'vendas' do PDV)
try {
    // Busca apenas as vendas concretizadas no PDV
    $stmtVendas = $conexao->query("SELECT SUM(total_liquido) as faturamento, COUNT(id) as qtd_vendas FROM vendas");
    $dadosVendas = $stmtVendas->fetch(PDO::FETCH_ASSOC);
    
    $faturamentoTotal = $dadosVendas['faturamento'] ?? 0;
    $qtdVendas = $dadosVendas['qtd_vendas'] ?? 0;
    $ticketMedio = $qtdVendas > 0 ? ($faturamentoTotal / $qtdVendas) : 0;
} catch (Exception $e) {
    // Se a tabela 'vendas' não existir ou a coluna tiver outro nome, não quebra a tela
}

// BLOCO 2: ITENS VENDIDOS E RANKING (Vem da tabela 'itens_venda' do PDV)
try {
    // Conta quantos itens passaram pelo caixa
    $stmtItens = $conexao->query("SELECT SUM(quantidade) as total_itens FROM itens_venda");
    $totalItens = $stmtItens->fetch(PDO::FETCH_ASSOC)['total_itens'] ?? 0;

    // Ranking dos 5 mais vendidos que deram entrada financeira
    $stmtTop = $conexao->query("
        SELECT nome as produto, SUM(subtotal) as receita 
        FROM itens_venda 
        GROUP BY nome 
        ORDER BY receita DESC 
        LIMIT 5
    ");
    $topProdutos = $stmtTop->fetchAll(PDO::FETCH_ASSOC);
    $maxReceitaProduto = !empty($topProdutos) ? $topProdutos[0]['receita'] : 1;
} catch (Exception $e) { }

// BLOCO 3: MÉTRICAS OPERACIONAIS (Vem das tabelas 'clientes', 'produtos' e 'pedidos')
try {
    $qtdClientes = $conexao->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
    $qtdProdutos = $conexao->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
    
    // Pedidos na cozinha (Pendente ou Em Produção)
    $qtdPendentes = $conexao->query("SELECT COUNT(*) FROM pedidos WHERE status IN ('Pendente', 'Em Produção')")->fetchColumn();
    
    // Pedidos Atrasados (A data de entrega já passou e ainda não foram finalizados)
    $qtdAtrasados = $conexao->query("SELECT COUNT(*) FROM pedidos WHERE status != 'Finalizado' AND data_entrega < CURDATE()")->fetchColumn();
} catch (Exception $e) { }

// BLOCO 4: GRÁFICO DE 7 DIAS (Vem da tabela 'vendas' do PDV)
try {
    $stmt7Dias = $conexao->query("
        SELECT DATE(data_venda) as data, SUM(total_liquido) as diario 
        FROM vendas 
        WHERE data_venda >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
        GROUP BY DATE(data_venda) 
        ORDER BY data ASC
    ");
    $vendas7Dias = $stmt7Dias->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($vendas7Dias as $v) {
        if($v['diario'] > $maxVendaDiaria) $maxVendaDiaria = $v['diario'];
    }
} catch (Exception $e) { }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Home</title>
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
                        <small class="texto-positivo">Dinheiro em Caixa (PDV)</small>
                    </div>
                </div>
                <div class="mini-card">
                    <div class="icone-card laranja">
                        <svg class="svg-painel" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    </div>
                    <div class="dados-card">
                        <span>Vendas Concluídas</span>
                        <h3><?= $qtdVendas ?></h3>
                        <small class="texto-positivo">Faturadas no PDV</small>
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
                        <small class="texto-positivo">Total de doces cobrados</small>
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
                        <div class="bloco-valor"><small>Receitas (PDV)</small> <strong class="cor-verde">R$ <?= number_format($faturamentoTotal, 2, ',', '.') ?></strong></div>
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
                            <p style="font-size: 0.7rem; color: #6b7280; padding-bottom: 25px;">Nenhuma venda faturada nos últimos 7 dias.</p>
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
                            <li style="color: #6b7280; font-size: 0.7rem;">Nenhum produto faturado no PDV ainda.</li>
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
                        <span>Pedidos na Cozinha</span>
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
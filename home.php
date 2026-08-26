<?php 
// 1. CONFIGURAÇÃO DE FUSO HORÁRIO PARA O BRASIL
date_default_timezone_set('America/Sao_Paulo');

// 2. SEGURANÇA MÁXIMA: Valida se o usuário fez login
require_once "php/logica_php/home.php"; 

// 3. CONEXÃO COM O BANCO DE DADOS
require_once "php/conexao.php"; 

// ==========================================================================
// CAPTURA DO FILTRO DE DIAS (Se não escolher nada, o padrão é 7 dias)
// ==========================================================================
$periodoFiltro = isset($_GET['periodo']) ? (int)$_GET['periodo'] : 7;
// Trava de segurança para aceitar apenas os valores permitidos
if (!in_array($periodoFiltro, [7, 15, 30])) {
    $periodoFiltro = 7;
}

// Inicializa variáveis base
$faturamentoTotal = 0;
$qtdVendas = 0;
$ticketMedio = 0;
$totalItens = 0;
$qtdClientes = 0;
$qtdProdutos = 0;
$qtdPendentes = 0;
$qtdAtrasados = 0;
$topProdutos = [];
$vendasGrafico = [];
$maxReceitaProduto = 1;
$maxVendaDiaria = 1;

// ==========================================================================
// BLOCO 1: FINANCEIRO E VENDAS
// ==========================================================================
try {
    $stmtVendas = $conexao->query("SELECT SUM(total_liquido) as faturamento, COUNT(id) as qtd_vendas FROM vendas");
    $dadosVendas = $stmtVendas->fetch(PDO::FETCH_ASSOC);
    
    $faturamentoTotal = $dadosVendas['faturamento'] ?? 0;
    $qtdVendas = $dadosVendas['qtd_vendas'] ?? 0;
    $ticketMedio = $qtdVendas > 0 ? ($faturamentoTotal / $qtdVendas) : 0;
} catch (Exception $e) { }

// ==========================================================================
// BLOCO 2: ITENS VENDIDOS E RANKING
// ==========================================================================
try {
    $stmtItens = $conexao->query("SELECT SUM(quantidade) as total_itens FROM vendas_itens");
    $totalItens = $stmtItens->fetch(PDO::FETCH_ASSOC)['total_itens'] ?? 0;

    $stmtTop = $conexao->query("
        SELECT produto_nome as nome, SUM(subtotal) as receita 
        FROM vendas_itens 
        GROUP BY produto_nome 
        ORDER BY receita DESC 
        LIMIT 5
    ");
    $topProdutos = $stmtTop->fetchAll(PDO::FETCH_ASSOC);
    
    if(!empty($topProdutos)) {
        $maxReceitaProduto = $topProdutos[0]['receita'] > 0 ? $topProdutos[0]['receita'] : 1;
    }
} catch (Exception $e) { }

// ==========================================================================
// BLOCO 3: VENDAS POR CATEGORIA (Gráfico Donut)
// ==========================================================================
$categorias = [
    'Bolos' => ['valor' => 0, 'cor' => '#10b981'],
    'Doces' => ['valor' => 0, 'cor' => '#f97316'],
    'Tortas' => ['valor' => 0, 'cor' => '#8b5cf6'],
    'Salgados' => ['valor' => 0, 'cor' => '#ef4444'],
    'Outros' => ['valor' => 0, 'cor' => '#6b7280']
];
$totalCategorias = 0;

try {
    $itensCat = $conexao->query("SELECT produto_nome as nome, subtotal as valor FROM vendas_itens")->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($itensCat as $item) {
        $nomeProd = strtolower($item['nome']);
        $valor = floatval($item['valor']);
        $totalCategorias += $valor;
        
        if (strpos($nomeProd, 'bolo') !== false) {
            $categorias['Bolos']['valor'] += $valor;
        } elseif (strpos($nomeProd, 'doce') !== false || strpos($nomeProd, 'brigadeiro') !== false || strpos($nomeProd, 'macaron') !== false) {
            $categorias['Doces']['valor'] += $valor;
        } elseif (strpos($nomeProd, 'torta') !== false || strpos($nomeProd, 'cheesecake') !== false) {
            $categorias['Tortas']['valor'] += $valor;
        } elseif (strpos($nomeProd, 'salgado') !== false || strpos($nomeProd, 'coxinha') !== false || strpos($nomeProd, 'empada') !== false) {
            $categorias['Salgados']['valor'] += $valor;
        } else {
            $categorias['Outros']['valor'] += $valor;
        }
    }
} catch (Exception $e) { }

$deg = 0;
$gradient = [];
foreach($categorias as $cat => $dados) {
    if ($dados['valor'] > 0 && $totalCategorias > 0) {
        $percent = ($dados['valor'] / $totalCategorias) * 100;
        $start = $deg;
        $end = $deg + $percent;
        $gradient[] = "{$dados['cor']} {$start}% {$end}%";
        $deg = $end;
    }
}
$stringDonutCss = !empty($gradient) ? implode(', ', $gradient) : '#e5e7eb 0% 100%';

// ==========================================================================
// BLOCO 4: MÉTRICAS OPERACIONAIS E GRÁFICO DINÂMICO DE DIAS
// ==========================================================================
try {
    $qtdClientes = $conexao->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
    $qtdProdutos = $conexao->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
    $qtdPendentes = $conexao->query("SELECT COUNT(*) FROM pedidos WHERE status IN ('Pendente', 'Em Produção')")->fetchColumn();
    $qtdAtrasados = $conexao->query("SELECT COUNT(*) FROM pedidos WHERE status != 'Finalizado' AND data_entrega < CURDATE()")->fetchColumn();
} catch (Exception $e) { }

try {
    // Utiliza a variável $periodoFiltro para ditar quantos dias puxar do banco
    $stmtGrafico = $conexao->prepare("
        SELECT DATE(data_venda) as data, SUM(total_liquido) as diario 
        FROM vendas 
        WHERE data_venda >= DATE_SUB(CURDATE(), INTERVAL ? DAY) 
        GROUP BY DATE(data_venda) 
        ORDER BY data ASC
    ");
    $stmtGrafico->execute([$periodoFiltro]);
    $vendasGrafico = $stmtGrafico->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($vendasGrafico as $v) { 
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
                        <div class="grafico-donut-css" style="background: conic-gradient(<?= $stringDonutCss ?>);">
                            <div class="miolo-branco"></div>
                        </div>
                        <div class="legenda-categorias">
                            <?php foreach($categorias as $nomeCat => $dados): ?>
                                <?php 
                                    $pct = $totalCategorias > 0 ? ($dados['valor'] / $totalCategorias) * 100 : 0; 
                                    if ($dados['valor'] > 0 || $totalCategorias == 0):
                                ?>
                                <div class="item-legenda">
                                    <span class="marcador-cor" style="background:<?= $dados['cor'] ?>;"></span> <?= $nomeCat ?> 
                                    <span class="valor-cat">R$ <?= number_format($dados['valor'], 2, ',', '.') ?></span>
                                    <span class="porcentagem"><?= number_format($pct, 1, ',', '') ?>%</span>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>

            <section class="linha-blocos-graficos">
                
                <div class="caixa-grafico flex-6">
                    <div class="topo-caixa">
                        <!-- Título atualiza dinamicamente conforme os dias escolhidos -->
                        <h3>Vendas dos Últimos <?= $periodoFiltro ?> Dias</h3>
                        
                        <!-- Formulário que atualiza a página automaticamente ao mudar a opção -->
                        <form method="GET" action="home.php" style="margin: 0;">
                            <select name="periodo" class="filtro-drop" onchange="this.form.submit()" style="cursor: pointer; background-color: #ffffff; outline: none;">
                                <option value="7" <?= $periodoFiltro == 7 ? 'selected' : '' ?>>Últimos 7 dias</option>
                                <option value="15" <?= $periodoFiltro == 15 ? 'selected' : '' ?>>Últimos 15 dias</option>
                                <option value="30" <?= $periodoFiltro == 30 ? 'selected' : '' ?>>Últimos 30 dias</option>
                            </select>
                        </form>
                    </div>
                    <div class="grafico-barras-css">
                        <?php if (empty($vendasGrafico)): ?>
                            <p style="font-size: 0.7rem; color: #6b7280; padding-bottom: 25px;">Nenhuma venda faturada nos últimos <?= $periodoFiltro ?> dias.</p>
                        <?php else: ?>
                            <?php foreach($vendasGrafico as $dia): 
                                $alturaBarra = ($dia['diario'] / $maxVendaDiaria) * 100;
                                // Formata a data para dia/mês/ano para aparecer no aviso flutuante
                                $dataVisual = date('d/m/Y', strtotime($dia['data']));
                            ?>
                                <div class="coluna-barra" style="height: <?= $alturaBarra ?>%;" title="<?= $dataVisual ?> - R$ <?= number_format($dia['diario'], 2, ',', '.') ?>"></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

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
                                    <span class="nome-prod" title="<?= htmlspecialchars($prod['nome']) ?>"><?= ($index + 1) ?>. <?= htmlspecialchars($prod['nome']) ?></span> 
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
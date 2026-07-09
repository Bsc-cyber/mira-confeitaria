<?php 
// 1. SEGURANÇA MÁXIMA: Valida se o usuário fez login puxando a regra da subpasta
require_once "php/logica_php/home.php"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Home</title>
    <!-- CSS na ordem exata e separada exigida pela banca -->
    <link rel="stylesheet" href="css/barra_lateral.css">
    <link rel="stylesheet" href="css/home.css">  <!-- ESSA LINHA DEVE ESTAR AQUI -->
</head>

<body>

    <!-- CONTAINER DO DASHBOARD -->
    <div class="container-dashboard">
        
        <!-- INJEÇÃO DA BARRA LATERAL ISOLADA VIA PHP -->
        <?php require_once "php/barra_lateral.php"; ?>

        <!-- ÁREA DE TRABALHO PRINCIPAL (CONTEÚDO COMPACTO) -->
        <main class="painel-conteudo">
            
            <!-- Bloco 1: Cabeçalho Superior de Boas-Vindas -->
            <header class="topo-dashboard">
                <div class="saudacao">
                    <span class="usuario-log">Olá, Lucas! </span>
                    <h1>Bem-vindo ao Mira Confeitaria</h1>
                    <p class="sub-painel">Aqui está o resumo do que acontece no seu negócio hoje.</p>
                </div>
                <div class="controles-topo">
                    <div class="seletor-data">01/05/2024 - 07/05/2024</div>
                    <button class="btn-atualizar">Atualizar</button>
                </div>
            </header>

            <!-- Bloco 2: Linha Superior de Mini Cartões de Desempenho -->
            <section class="linha-mini-cards">
                <div class="mini-card">
                    <div class="icone-card verde">
                        <svg class="svg-painel" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div class="dados-card">
                        <span>Faturamento Total</span>
                        <h3>R$ 12.540,00</h3>
                        <small class="texto-positivo">↑ 18,6% vs. período anterior</small>
                    </div>
                </div>
                <div class="mini-card">
                    <div class="icone-card laranja">
                        <svg class="svg-painel" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    </div>
                    <div class="dados-card">
                        <span>Pedidos</span>
                        <h3>128</h3>
                        <small class="texto-positivo">↑ 12,3% vs. período anterior</small>
                    </div>
                </div>
                <div class="mini-card">
                    <div class="icone-card roxo">
                        <svg class="svg-painel" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <div class="dados-card">
                        <span>Ticket Médio</span>
                        <h3>R$ 98,75</h3>
                        <small class="texto-positivo">↑ 8,7% vs. período anterior</small>
                    </div>
                </div>
                <div class="mini-card">
                    <div class="icone-card rosa">
                        <svg class="svg-painel" viewBox="0 0 24 24"><path d="M12 2a9 9 0 0 1 9 9v1H3v-1a9 9 0 0 1 9-9z"/><rect x="3" y="14" width="18" height="4" rx="1"/><line x1="6" y1="14" x2="6" y2="18"/><line x1="18" y1="14" x2="18" y2="18"/></svg>
                    </div>
                    <div class="dados-card">
                        <span>Itens Vendidos</span>
                        <h3>256</h3>
                        <small class="texto-positivo">↑ 15,2% vs. período anterior</small>
                    </div>
                </div>
            </section>
            <!-- Bloco 3: Linha Central de Gráficos Principais -->
            <section class="linha-blocos-graficos">
                
                <!-- Resumo Financeiro -->
                <div class="caixa-grafico flex-7">
                    <div class="topo-caixa">
                        <h3>Resumo Financeiro</h3>
                        <span class="filtro-drop">Este Mês ▾</span>
                    </div>
                    <div class="grid-valores-resumo">
                        <div class="bloco-valor"><small>Receitas</small> <strong class="cor-verde">R$ 12.540,00</strong></div>
                        <div class="bloco-valor"><small>Despesas</small> <strong class="cor-vermelha">R$ 4.230,00</strong></div>
                        <div class="bloco-valor"><small>Lucro Líquido</small> <strong class="cor-azul">R$ 8.310,00</strong></div>
                    </div>
                    <div class="grafico-linhas-ficticio">
                        <div class="grid-linhas-fundo"></div>
                        <div class="vetor-linha-receitas"></div>
                        <div class="vetor-linha-despesas"></div>
                    </div>
                </div>

                <!-- Vendas por Categoria -->
                <div class="caixa-grafico flex-5">
                    <div class="topo-caixa">
                        <h3>Vendas por Categoria</h3>
                    </div>
                    <div class="conteudo-donut">
                        <div class="grafico-donut-css">
                            <div class="miolo-branco"></div>
                        </div>
                        <div class="legenda-categorias">
                            <div class="item-legenda"><span class="marcador-cor b-bolos"></span> Bolos <span class="valor-cat">R$ 5.240,00</span> <span class="porcentagem">41,8%</span></div>
                            <div class="item-legenda"><span class="marcador-cor b-doces"></span> Doces <span class="valor-cat">R$ 3.120,00</span> <span class="porcentagem">24,9%</span></div>
                            <div class="item-legenda"><span class="marcador-cor b-tortas"></span> Tortas <span class="valor-cat">R$ 2.330,00</span> <span class="porcentagem">18,6%</span></div>
                            <div class="item-legenda"><span class="marcador-cor b-salgados"></span> Salgados <span class="valor-cat">R$ 1.230,00</span> <span class="porcentagem">9,8%</span></div>
                        </div>
                    </div>
                </div>

            </section>

            <!-- Bloco 4: Linha Inferior de Estatísticas Adicionais -->
            <section class="linha-blocos-graficos">
                
                <!-- Histórico de 7 dias -->
                <div class="caixa-grafico flex-6">
                    <div class="topo-caixa">
                        <h3>Vendas dos Últimos 7 Dias</h3>
                        <span class="filtro-drop">Últimos 7 dias ▾</span>
                    </div>
                    <div class="grafico-barras-css">
                        <div class="coluna-barra" style="height: 35%;"></div>
                        <div class="coluna-barra" style="height: 55%;"></div>
                        <div class="coluna-barra" style="height: 80%;"></div>
                        <div class="coluna-barra" style="height: 45%;"></div>
                        <div class="coluna-barra" style="height: 70%;"></div>
                        <div class="coluna-barra" style="height: 60%;"></div>
                        <div class="coluna-barra" style="height: 40%;"></div>
                    </div>
                </div>

                <!-- Produtos Mais Vendidos -->
                <div class="caixa-grafico flex-6">
                    <div class="topo-caixa">
                        <h3>Produtos Mais Vendidos</h3>
                        <a href="#" class="link-acao-topo">Ver todos</a>
                    </div>
                    <ul class="ranking-produtos">
                        <li><span class="nome-prod">1. Bolo de Chocolate Premium</span> <span class="barra-progresso-ficticia p-100"></span> <strong>R$ 2.450,00</strong></li>
                        <li><span class="nome-prod">2. Cheesecake de Frutas Vermelhas</span> <span class="barra-progresso-ficticia p-80"></span> <strong>R$ 1.980,00</strong></li>
                        <li><span class="nome-prod">3. Torta de Limão Siciliano</span> <span class="barra-progresso-ficticia p-60"></span> <strong>R$ 1.350,00</strong></li>
                        <li><span class="nome-prod">4. Brigadeiro Gourmet</span> <span class="barra-progresso-ficticia p-50"></span> <strong>R$ 1.120,00</strong></li>
                        <li><span class="nome-prod">5. Macarons Sortidos</span> <span class="barra-progresso-ficticia p-40"></span> <strong>R$ 980,00</strong></li>
                    </ul>
                </div>
            </section>

            <!-- Bloco 5: Rodapé de Métricas Simples -->
            <section class="linha-mini-cards metricas-rodape">
                <div class="card-rodape-simples">
                    <div class="icone-rodape verde-txt">
                        <svg class="svg-mini" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <div class="dados-rodape">
                        <span>Clientes Ativos</span>
                        <h4>532</h4>
                        <small class="texto-positivo">↑ 9,5% vs. período anterior</small>
                    </div>
                </div>
                <div class="card-rodape-simples">
                    <div class="icone-rodape laranja-txt">
                        <svg class="svg-mini" viewBox="0 0 24 24"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                    </div>
                    <div class="dados-rodape">
                        <span>Produtos Cadastrados</span>
                        <h4>87</h4>
                        <small class="texto-neutro">— sem alterações</small>
                    </div>
                </div>
                <div class="card-rodape-simples">
                    <div class="icone-rodape roxo-txt">
                        <svg class="svg-mini" viewBox="0 0 24 24"><polyline points="12 5 19 12 12 19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </div>
                    <div class="dados-rodape">
                        <span>Pedidos Pendentes</span>
                        <h4>23</h4>
                        <small class="texto-link">Ver pedidos →</small>
                    </div>
                </div>
                <div class="card-rodape-simples">
                    <div class="icone-rodape vermelho-txt">
                        <svg class="svg-mini" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                    <div class="dados-rodape">
                        <span>Pedidos Atrasados</span>
                        <h4>3</h4>
                        <small class="texto-link">Ver pedidos →</small>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script src="js/barra_lateral.js"></script>
</body>
</html>

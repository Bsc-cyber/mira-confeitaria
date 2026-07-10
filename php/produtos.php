<?php 
// SEGURANÇA MÁXIMA: Valida se o usuário fez login puxando a regra da subpasta
require_once "logica_php/home.php"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Produtos</title>
    <!-- Inclusão das folhas de estilo unificadas locais -->
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <!-- Versão atualizada para forçar o navegador a ler o layout novo imediatamente -->
    <link rel="stylesheet" href="../css/produtos.css?v=35.0">
</head>
<body>

    <!-- 💡 BANCO NATIVO DE SUGESTÕES (DATALISTS) PARA O AUTOCOMPLETAR INTELIGENTE IGUAL A PEDIDOS -->
    <datalist id="listaCategoriasDoces">
        <option value="Bolos">
        <option value="Cheesecakes">
        <option value="Tortas">
        <option value="Doces">
    </datalist>

    <div class="container-dashboard">
        
        <!-- Injeção da barra lateral componentizada -->
        <?php require_once "barra_lateral.php"; ?>

        <!-- ÁREA PRINCIPAL DA GESTÃO DE PRODUTOS -->
        <main class="painel-conteudo-produtos">
            
            <!-- Cabeçalho Superior Limpo sem elementos soltos no topo direito -->
            <header class="topo-produtos">
                <div class="titulo-pagina-produtos">
                    <div class="icone-titulo-prod">
                        <svg class="svg-topo-prod" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    </div>
                    <div class="alinhamento-texto-topo">
                        <h1>Produtos</h1>
                        <p>Cadastre e gerencie os produtos da sua confeitaria.</p>
                    </div>
                </div>
            </header>

            <!-- Grid de Trabalho Repartido (50% / 50%) -->
            <div class="grid-produtos-container">
                
                <!-- COLUNA DA ESQUERDA: Cadastro com o Scroll Grosso e Militar de Pedidos -->
                <div class="coluna-esquerda-cadastro">
                    <div class="card-formulario-produtos">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg> Cadastro de Produto</h3>
                        
                        <form id="formCadastroProduto" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                            <!-- wrapper-inputs-scroll-cadastro: Recebe a rolagem mestre cinza por CSS -->
                            <div class="wrapper-inputs-scroll-cadastro">
                                
                                <!-- Campo: Nome do Produto -->
                                <div class="grupo-input-produtos">
                                    <label>Produto <span class="obrigatorio">*</span></label>
                                    <input type="text" id="nomeProduto" placeholder="Digite o nome do produto" required>
                                </div>

                                <!-- Campo: Categoria (Botão + removido, largura 100% limpa com datalist) -->
                                <div class="grupo-input-produtos m-t-8">
                                    <label>Categoria <span class="obrigatorio">*</span></label>
                                    <input type="text" id="categoriaProduto" list="listaCategoriasDoces" placeholder="Selecione ou digite a categoria..." required>
                                </div>
                                <!-- Campo: Tamanho -->
                                <div class="grupo-input-produtos m-t-8">
                                    <label>Tamanho</label>
                                    <input type="text" id="tamanhoProduto" placeholder="Digite o tamanho (ex: 20cm, 15 un, 1kg)">
                                </div>

                                <!-- Campo: Preço com Moeda Integrada -->
                                <div class="grupo-input-produtos m-t-8">
                                    <label>Preço <span class="obrigatorio">*</span></label>
                                    <div class="prefixo-moeda-wrapper">
                                        <span class="prefixo-rs">R$</span>
                                        <input type="text" id="precoProduto" placeholder="Digite o preço" required>
                                    </div>
                                </div>

                                <!-- Campo: Sabores -->
                                <div class="grupo-input-produtos m-t-8">
                                    <label>Sabores</label>
                                    <input type="text" id="saboresProduto" placeholder="Digite os sabores (ex: Chocolate, Ninho)">
                                </div>

                                <!-- Campo: Descrição -->
                                <div class="grupo-input-produtos m-t-8">
                                    <label>Descrição</label>
                                    <textarea id="descricaoProduto" placeholder="Descreva o produto, ingredientes, detalhes..."></textarea>
                                </div>

                                <!-- Campo: Switch Deslizante de Status Ativo -->
                                <div class="grupo-input-produtos m-t-8">
                                    <label>Ativo</label>
                                    <div class="alinhamento-switch-ativo">
                                        <label class="switch-container-item">
                                            <input type="checkbox" id="statusProduto" checked>
                                            <span class="slider-switch-bola"></span>
                                        </label>
                                        <span class="texto-switch-label" id="labelStatusFiltro">Sim, produto ativo</span>
                                    </div>
                                </div>

                                <!-- Os botões moram aqui dentro do scroll, colados no final do formulário! -->
                                <div class="botoes-acoes-formulario-prod-triplo">
                                    <button type="submit" class="btn-prod-base salvar-btn">💾 Salvar</button>
                                    <button type="button" class="btn-prod-base limpar-btn" id="btnLimparProd">🧹 Limpar</button>
                                </div>

                            </div> <!-- Fecha a div wrapper-inputs-scroll-cadastro DEPOIS dos botões -->
                        </form>
                    </div>
                </div>
                <!-- COLUNA DA DIREITA: Listagem e Tabela de Produtos Cadastrados -->
                <div class="coluna-direita-listagem">
                    <div class="card-tabela-produtos">
                        <div class="topo-tabela-acoes-prod">
                            <h3><svg class="svg-card-title-prod" viewBox="0 0 24 24" width="14" height="14"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Produtos Cadastrados</h3>
                        </div>

                        <!-- Barra de pesquisa interna e recarregar embutidos dentro do card da tabela -->
                        <div class="linha-pesquisa-interna-prod">
                            <div class="wrapper-busca-tabela-prod">
                                <svg class="svg-busca-tabela-interna" viewBox="0 0 24 24" width="12" height="12"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" id="inputPesquisaGlobal" placeholder="Pesquisar produto...">
                            </div>
                            <button type="button" class="btn-mini-tabela-topo" id="btnRecarregarProdutos" title="Recarregar Tabela">
                                <svg class="svg-mini-topo" viewBox="0 0 24 24" width="12" height="12"><path d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                            </button>
                        </div>

                        <!-- Área de Rolagem Otimizada com Blindagem Inline Direta contra bugs flex -->
                        <div class="wrapper-tabela-produtos-scroll">
                            <table class="tabela-dados-produtos" style="display: table !important; width: 100% !important; table-layout: fixed !important; border-collapse: collapse !important;">
                                <thead>
                                    <tr style="display: table-row !important;">
                                        <th style="width: 8%;">ID</th>
                                        <th style="width: 25%;">Produto</th>
                                        <th style="width: 15%;">Categoria</th>
                                        <th style="width: 12%;">Tamanho</th>
                                        <th style="width: 12%;">Preço</th>
                                        <th style="width: 13%;">Sabores</th>
                                        <th style="width: 10%;">Status</th>
                                        <th style="width: 8%; text-align: center;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="corpoTabelaProdutos">
                                    <!-- Registro 1 -->
                                    <tr class="linha-selecionavel-prod" data-id="101" style="display: table-row !important;">
                                        <td>101</td>
                                        <td><strong>Bolo de Chocolate Premium</strong></td>
                                        <td><span class="badge-cat b-bolos">Bolos</span></td>
                                        <td>20 cm</td>
                                        <td>R$ 89,90</td>
                                        <td>Chocolate</td>
                                        <td><span class="badge-status ativo">Ativo</span></td>
                                        <td class="celula-acoes-prod">
                                            <button type="button" class="btn-linha-prod edit"><svg viewBox="0 0 24 24" width="12" height="12"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                            <button type="button" class="btn-linha-prod del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 2 -->
                                    <tr class="linha-selecionavel-prod" data-id="102" style="display: table-row !important;">
                                        <td>102</td>
                                        <td><strong>Cheesecake de Frutas Vermelhas</strong></td>
                                        <td><span class="badge-cat b-cheesecakes">Cheesecakes</span></td>
                                        <td>18 cm</td>
                                        <td>R$ 74,90</td>
                                        <td>Frutas Vermelhas</td>
                                        <td><span class="badge-status ativo">Ativo</span></td>
                                        <td class="celula-acoes-prod">
                                            <button type="button" class="btn-linha-prod edit"><svg viewBox="0 0 24 24" width="12" height="12"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                            <button type="button" class="btn-linha-prod del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 3 -->
                                    <tr class="linha-selecionavel-prod" data-id="103" style="display: table-row !important;">
                                        <td>103</td>
                                        <td><strong>Torta de Limão Siciliano</strong></td>
                                        <td><span class="badge-cat b-tortas">Tortas</span></td>
                                        <td>22 cm</td>
                                        <td>R$ 69,90</td>
                                        <td>Limão</td>
                                        <td><span class="badge-status ativo">Ativo</span></td>
                                        <td class="celula-acoes-prod">
                                            <button type="button" class="btn-linha-prod edit"><svg viewBox="0 0 24 24" width="12" height="12"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                            <button type="button" class="btn-linha-prod del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 4 -->
                                    <tr class="linha-selecionavel-prod" data-id="104" style="display: table-row !important;">
                                        <td>104</td>
                                        <td><strong>Brigadeiro Gourmet</strong></td>
                                        <td><span class="badge-cat b-doces">Doces</span></td>
                                        <td>15 un</td>
                                        <td>R$ 45,00</td>
                                        <td>Chocolate</td>
                                        <td><span class="badge-status ativo">Ativo</span></td>
                                        <td class="celula-acoes-prod">
                                            <button type="button" class="btn-linha-prod edit"><svg viewBox="0 0 24 24" width="12" height="12"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                            <button type="button" class="btn-linha-prod del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 5 -->
                                    <tr class="linha-selecionavel-prod" data-id="105" style="display: table-row !important;">
                                        <td>105</td>
                                        <td><strong>Macarons Sortidos</strong></td>
                                        <td><span class="badge-cat b-doces">Doces</span></td>
                                        <td>10 un</td>
                                        <td>R$ 55,00</td>
                                        <td>Variados</td>
                                        <td><span class="badge-status inativo">Inativo</span></td>
                                        <td class="celula-acoes-prod">
                                            <button type="button" class="btn-linha-prod edit"><svg viewBox="0 0 24 24" width="12" height="12"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                            <button type="button" class="btn-linha-prod del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 6 -->
                                    <tr class="linha-selecionavel-prod" data-id="106" style="display: table-row !important;">
                                        <td>106</td>
                                        <td><strong>Bolo Red Velvet</strong></td>
                                        <td><span class="badge-cat b-bolos">Bolos</span></td>
                                        <td>20 cm</td>
                                        <td>R$ 99,90</td>
                                        <td>Red Velvet</td>
                                        <td><span class="badge-status ativo">Ativo</span></td>
                                        <td class="celula-acoes-prod">
                                            <button type="button" class="btn-linha-prod edit"><svg viewBox="0 0 24 24" width="12" height="12"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                            <button type="button" class="btn-linha-prod del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 7 -->
                                    <tr class="linha-selecionavel-prod" data-id="107" style="display: table-row !important;">
                                        <td>107</td>
                                        <td><strong>Brownie com Nozes</strong></td>
                                        <td><span class="badge-cat b-doces">Doces</span></td>
                                        <td>12 un</td>
                                        <td>R$ 42,00</td>
                                        <td>Chocolate, Nozes</td>
                                        <td><span class="badge-status ativo">Ativo</span></td>
                                        <td class="celula-acoes-prod">
                                            <button type="button" class="btn-linha-prod edit"><svg viewBox="0 0 24 24" width="12" height="12"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                            <button type="button" class="btn-linha-prod del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <!-- Fecha o wrapper-tabela-produtos-scroll -->

                        <!-- Rodapé limpo contendo apenas a contagem total de itens em conformidade com o mockup -->
                        <div class="rodape-paginacao-produtos">
                            <span class="info-contagem-prod">Mostrando 1 a 7 de 7 produtos cadastrados</span>
                        </div>

                    </div> <!-- Fecha o card-tabela-produtos -->
                </div> <!-- Fecha a coluna-direita-listagem -->

            </div> <!-- Fecha o grid-produtos-container -->
        </main>
    </div> <!-- Fecha o container-dashboard -->

    <!-- Motor JavaScript nativo original -->
    <script src="../js/produtos.js"></script>
</body>
</html>

<?php 
// SEGURANÇA MÁXIMA: Valida se o usuário fez login puxando a regra da subpasta
require_once "logica_php/home.php"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Cadastro de Produtos</title>
    <!-- Inclusão das folhas de estilo unificadas de forma local -->
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <!-- Força o carregamento do CSS renovado na versão 12.0 -->
    <link rel="stylesheet" href="../css/produtos.css?v=12.0">
</head>
<body>

    <!-- 💡 BANCO NATIVO DE SUGESTÕES (DATALISTS) PARA O AUTOCOMPLETAR CHROME -->
    <datalist id="listaCategorias">
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
            
            <!-- Cabeçalho Superior Limpo sem buscas soltas no topo direito, igual ao seu print -->
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
            <!-- Bloco Geral Dividido em Duas Colunas Simétricas (50% / 50%) -->
            <div class="grid-produtos-container">
                
                <!-- COLUNA DA ESQUERDA: Formulário Contínuo com Scroll Interno dos Inputs -->
                <div class="coluna-esquerda-cadastro-produtos">
                    <div class="card-formulario-produtos-unico">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg> Cadastro de Produto</h3>
                        
                        <form id="formCadastroProduto">
                            <!-- wrapper-inputs-scroll-produtos: Segura a rolagem interna de forma limpa -->
                            <div class="wrapper-inputs-scroll-produtos">
                                
                                <!-- Campo: Produto -->
                                <div class="grupo-input-produtos">
                                    <label>Produto <span class="obrigatorio">*</span></label>
                                    <input type="text" id="nomeProduto" placeholder="Digite o nome do produto" required>
                                </div>

                                <!-- Campo: Categoria (SEM BOTÃO +, LARGURA 100% LIMPA!) -->
                                <div class="grupo-input-produtos m-t-8">
                                    <label>Categoria <span class="obrigatorio">*</span></label>
                                    <input type="text" id="categoriaProduto" list="listaCategorias" placeholder="Selecione ou digite a categoria..." required>
                                </div>

                                <!-- Campo: Tamanho -->
                                <div class="grupo-input-produtos m-t-8">
                                    <label>Tamanho</label>
                                    <input type="text" id="tamanhoProduto" placeholder="Digite o tamanho (ex: 20cm, 1kg)">
                                </div>

                                <!-- Campo: Preço com moeda integrada -->
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
                                    <textarea id="descricaoProduto" placeholder="Digite detalhes ou descrição do produto..."></textarea>
                                </div>

                                <!-- Campo: Switch Ativo -->
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
                            </div>

                            <!-- Fileira de dois botões grandes da mesma cor e tamanho, idêntico ao seu print! -->
                            <div class="botoes-acoes-formulario-prod-duplo">
                                <button type="submit" class="btn-prod-base salvar-btn">💾 Salvar Produto</button>
                                <button type="button" class="btn-prod-base limpar-btn" id="btnLimparProd">🧹 Limpar Campos</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- COLUNA DA DIREITA: Listagem, Tabela e Busca Unificada em Linha -->
                <div class="coluna-direita-listagem">
                    <div class="card-tabela-produtos">
                        <div class="topo-tabela-acoes-prod">
                            <h3><svg class="svg-card-title-prod" viewBox="0 0 24 24" width="14" height="14"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Produtos Cadastrados</h3>
                        </div>

                        <!-- Pesquisa interna de produto e botão de atualizar unificados em linha única (Estilo Fornecedores) -->
                        <div class="linha-pesquisa-interna-prod">
                            <div class="wrapper-busca-tabela-prod">
                                <svg class="svg-busca-tabela-interna" viewBox="0 0 24 24" width="12" height="12"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" id="inputPesquisaGlobal" placeholder="Pesquisar produto...">
                            </div>
                            <button type="button" class="btn-mini-tabela-topo" id="btnRecarregarProdutos" title="Recarregar/Atualizar">
                                <svg class="svg-mini-topo-prod" viewBox="0 0 24 24" width="12" height="12"><path d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                            </button>
                        </div>

                        <!-- Área Ocupada pela Tabela com Rolagem Interna Travada e Blindagem Direta -->
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
                                    <!-- Registro 4: Brigadeiro Gourmet -->
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
                                    <!-- Registro 5: Macarons Sortidos -->
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
                                    <!-- Registro 6: Bolo Red Velvet -->
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
                                    <!-- Registro 7: Brownie com Nozes -->
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

                        <!-- Barra de Rodapé com Contagem e Paginação Simétrica baseada em Pedidos -->
                        <div class="rodape-paginacao-produtos">
                            <span class="info-contagem-prod">Mostrando 1 a 7 de 7 produtos</span>
                            <div class="controles-paginacao-direita-prod">
                                <div class="seletor-linhas-pagina-prod">
                                    <select>
                                        <option value="10">10 por página</option>
                                        <option value="20">20 por página</option>
                                    </select>
                                </div>
                                <div class="botoes-passar-pagina-prod">
                                    <button type="button" class="btn-pagi-prod"><svg viewBox="0 0 24 24" width="10" height="10"><polyline points="11 17 6 12 11 7"/><polyline points="18 17 13 12 18 7"/></svg></button>
                                    <button type="button" class="btn-pagi-prod"><svg viewBox="0 0 24 24" width="10" height="10"><polyline points="15 18 9 12 15 6"/></svg></button>
                                    <button type="button" class="btn-pagi-prod ativo-pagi-prod">1</button>
                                    <button type="button" class="btn-pagi-prod"><svg viewBox="0 0 24 24" width="10" height="10"><polyline points="9 18 15 12 9 6"/></svg></button>
                                    <button type="button" class="btn-pagi-prod"><svg viewBox="0 0 24 24" width="10" height="10"><polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/></svg></button>
                                </div>
                            </div>
                        </div>

                    </div> <!-- Fecha o card-tabela-produtos -->
                </div> <!-- Fecha a coluna-direita-listagem -->

            </div> <!-- Fecha o grid-produtos-container -->
        </main>
    </div> <!-- Fecha o container-dashboard -->

    <!-- Vinculação externa única do motor manipulador JavaScript de Produtos -->
    <script src="../js/produtos.js"></script>
</body>
</html>

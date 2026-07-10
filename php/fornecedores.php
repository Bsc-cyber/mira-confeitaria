<?php 
// SEGURANÇA MÁXIMA: Valida se o usuário fez login puxando a regra da subpasta
require_once "logica_php/home.php"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Fornecedores</title>
    <!-- Inclusão das folhas de estilo unificadas locais -->
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <!-- Força o recarregamento do CSS calibrado na versão mestre final -->
    <link rel="stylesheet" href="../css/fornecedores.css?v=50.0">
</head>
<body>

    <!-- 💡 BANCO NATIVO DE SUGESTÕES PARA O AUTOCOMPLETAR IGUAL A PEDIDOS -->
    <datalist id="listaTiposFornecedores">
        <option value="Ingredientes">
        <option value="Embalagens">
        <option value="Laticínios">
        <option value="Hortifrúti">
        <option value="Aromas">
    </datalist>

    <div class="container-dashboard">
        
        <!-- Injeção da barra lateral componentizada -->
        <?php require_once "barra_lateral.php"; ?>

        <!-- ÁREA PRINCIPAL DA GESTÃO DE FORNECEDORES -->
        <main class="painel-conteudo-fornecedores">
            
            <!-- Cabeçalho Superior Limpo -->
            <header class="topo-fornecedores">
                <div class="titulo-pagina-fornecedores">
                    <div class="icone-titulo-forn">
                        <svg class="svg-topo-forn" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polyline points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div class="alinhamento-texto-topo">
                        <h1>Fornecedores</h1>
                        <p>Cadastre e gerencie os fornecedores da sua confeitaria.</p>
                    </div>
                </div>
            </header>

            <!-- Grid de Trabalho Repartido (50% / 50%) -->
            <div class="grid-fornecedores-container">
                
                <!-- COLUNA DA ESQUERDA: Cadastro com o Scroll Grosso e Militar de Pedidos -->
                <div class="coluna-esquerda-cadastro">
                    <div class="card-formulario-fornecedores">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Cadastro de Fornecedor</h3>
                        
                        <form id="formCadastroFornecedor" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                            <!-- wrapper-inputs-scroll-cadastro: Recebe a rolagem mestre cinza-militar -->
                            <div class="wrapper-inputs-scroll-cadastro">
                                
                                <div class="grupo-input-fornecedores">
                                    <label>Nome <span class="obrigatorio">*</span></label>
                                    <input type="text" id="nomeFornecedor" placeholder="Digite o nome do fornecedor" required>
                                </div>

                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>Tipo de Fornecedor <span class="obrigatorio">*</span></label>
                                    <input type="text" id="tipoFornecedor" list="listaTiposFornecedores" placeholder="Selecione ou digite o tipo..." required>
                                </div>
                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>CPF/CNPJ</label>
                                    <input type="text" id="cnpjFornecedor" placeholder="Digite o CPF ou CNPJ">
                                </div>

                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>Telefone</label>
                                    <input type="text" id="telFornecedor" placeholder="(00) 00000-0000">
                                </div>

                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>E-mail</label>
                                    <input type="email" id="emailFornecedor" placeholder="exemplo@fornecedor.com">
                                </div>

                                <div class="linha-inputs-dupla m-t-8">
                                    <div class="grupo-input-fornecedores flex-8">
                                        <label>Rua</label>
                                        <input type="text" id="ruaFornecedor" placeholder="Digite a rua">
                                    </div>
                                    <div class="grupo-input-fornecedores flex-4">
                                        <label>Nº</label>
                                        <input type="text" id="numFornecedor" placeholder="Número">
                                    </div>
                                </div>

                                <div class="linha-inputs-dupla m-t-8">
                                    <div class="grupo-input-fornecedores">
                                        <label>Bairro</label>
                                        <input type="text" id="bairroFornecedor" placeholder="Digite o bairro">
                                    </div>
                                    <div class="grupo-input-fornecedores">
                                        <label>Cidade</label>
                                        <input type="text" id="cidadeFornecedor" placeholder="Digite a cidade">
                                    </div>
                                </div>

                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>Complemento</label>
                                    <input type="text" id="compFornecedor" placeholder="Digite o complemento (opcional)">
                                </div>

                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>Informações adicionais</label>
                                    <textarea id="infoFornecedor" placeholder="Digite observações adicionais sobre o fornecedor..."></textarea>
                                </div>

                                <!-- 💡 MÁGICA DE FLUXO: Os botões foram colocados AQUI DENTRO da div de scroll, logo no final do formulário! -->
                                <div class="botoes-acoes-formulario-forn-triplo">
                                    <button type="submit" class="btn-forn-base salvar-btn">💾 Salvar</button>
                                    <button type="button" class="btn-forn-base limpar-btn" id="btnLimparForn">🧹 Limpar</button>
                                </div>

                            </div> <!-- Fecha a div wrapper-inputs-scroll-cadastro DEPOIS dos botões -->
                        </form>
                    </div>
                </div>
                <!-- COLUNA DA DIREITA: Listagem e Tabela de Fornecedores Cadastrados -->
                <div class="coluna-direita-listagem">
                    <div class="card-tabela-fornecedores">
                        <div class="topo-tabela-acoes-prod">
                            <h3><svg class="svg-card-title-prod" viewBox="0 0 24 24" width="14" height="14"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Fornecedores Cadastrados</h3>
                        </div>

                        <!-- Barra de pesquisa interna e recarregar embutidos dentro da tabela -->
                        <div class="linha-pesquisa-interna-forn">
                            <div class="wrapper-busca-tabela-forn">
                                <svg class="svg-busca-tabela-interna" viewBox="0 0 24 24" width="12" height="12"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" id="inputPesquisaGlobal" placeholder="Pesquisar fornecedor...">
                            </div>
                            <button type="button" class="btn-mini-tabela-topo" id="btnRecarregarFornecedores" title="Recarregar Tabela">
                                <svg class="svg-mini-topo" viewBox="0 0 24 24" width="12" height="12"><path d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                            </button>
                        </div>

                        <!-- Área de Rolagem Otimizada com Blindagem Inline Direta -->
                        <div class="wrapper-tabela-fornecedores-scroll">
                            <table class="tabela-dados-fornecedores" style="display: table !important; width: 100% !important; table-layout: fixed !important; border-collapse: collapse !important;">
                                <thead>
                                    <tr style="display: table-row !important;">
                                        <th style="width: 8%;">ID</th>
                                        <th style="width: 25%;">Nome</th>
                                        <th style="width: 15%;">Tipo</th>
                                        <th style="width: 25%;">CPF/CNPJ</th>
                                        <th style="width: 20%;">Telefone</th>
                                        <th style="width: 7%; text-align: center;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="corpoTabelaFornecedores">
                                    <!-- Registro 1 -->
                                    <tr class="linha-selecionavel-forn" data-id="101" style="display: table-row !important;">
                                        <td>101</td>
                                        <td><strong>Doces & Cia</strong></td>
                                        <td><span class="badge-tipo b-ingredientes">Ingredientes</span></td>
                                        <td>12.345.678/0001-90</td>
                                        <td>(11) 98765-4321</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 2 -->
                                    <tr class="linha-selecionavel-forn" data-id="102" style="display: table-row !important;">
                                        <td>102</td>
                                        <td><strong>Embalagens São Paulo</strong></td>
                                        <td><span class="badge-tipo b-embalagens">Embalagens</span></td>
                                        <td>23.456.789/0001-01</td>
                                        <td>(11) 97654-3210</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 3 -->
                                    <tr class="linha-selecionavel-forn" data-id="103" style="display: table-row !important;">
                                        <td>103</td>
                                        <td><strong>Laticínios Boa Qualidade</strong></td>
                                        <td><span class="badge-tipo b-laticinios">Laticínios</span></td>
                                        <td>34.567.890/0001-23</td>
                                        <td>(11) 96543-2109</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 4 -->
                                    <tr class="linha-selecionavel-forn" data-id="104" style="display: table-row !important;">
                                        <td>104</td>
                                        <td><strong>Nuts Brasil</strong></td>
                                        <td><span class="badge-tipo b-ingredientes">Ingredientes</span></td>
                                        <td>45.678.901/0001-34</td>
                                        <td>(11) 95432-1098</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 5 -->
                                    <tr class="linha-selecionavel-forn" data-id="105" style="display: table-row !important;">
                                        <td>105</td>
                                        <td><strong>Chocolate Premium</strong></td>
                                        <td><span class="badge-tipo b-ingredientes">Ingredientes</span></td>
                                        <td>56.789.012/0001-45</td>
                                        <td>(11) 94321-0987</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 6 -->
                                    <tr class="linha-selecionavel-forn" data-id="106" style="display: table-row !important;">
                                        <td>106</td>
                                        <td><strong>Frutas & Frutas</strong></td>
                                        <td><span class="badge-tipo b-hortifruti">Hortifrúti</span></td>
                                        <td>67.890.123/0001-56</td>
                                        <td>(11) 93210-9876</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 7 -->
                                    <tr class="linha-selecionavel-forn" data-id="107" style="display: table-row !important;">
                                        <td>107</td>
                                        <td><strong>Aromas & Cia</strong></td>
                                        <td><span class="badge-tipo b-aromas">Aromas</span></td>
                                        <td>78.901.234/0001-67</td>
                                        <td>(11) 92109-8765</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 8 -->
                                    <tr class="linha-selecionavel-forn" data-id="108" style="display: table-row !important;">
                                        <td>108</td>
                                        <td><strong>Farinha & Arte</strong></td>
                                        <td><span class="badge-tipo b-ingredientes">Ingredientes</span></td>
                                        <td>89.012.345/0001-78</td>
                                        <td>(11) 91098-7654</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha del"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <!-- Fecha o wrapper-tabela-fornecedores-scroll -->

                        <!-- Rodapé de contagem limpo e idêntico à sua nova especificação -->
                        <div class="rodape-paginacao-fornecedores">
                            <span class="info-contagem-itens">Mostrando 1 a 8 de 8 fornecedores cadastrados</span>
                        </div>

                    </div> <!-- Fecha o card-tabela-fornecedores -->
                </div> <!-- Fecha a coluna-direita-listagem -->

            </div> <!-- Fecha o grid-fornecedores-container -->
        </main>
    </div> <!-- Fecha o container-dashboard -->

    <!-- Vinculação do motor operacional em JavaScript -->
    <script src="../js/fornecedores.js"></script>
</body>
</html>

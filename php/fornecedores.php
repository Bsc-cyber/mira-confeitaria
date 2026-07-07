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
    <!-- Inclusão das folhas de estilo unificadas a partir da subpasta -->
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <link rel="stylesheet" href="../css/fornecedores.css?v=4.0">
</head>
<body>

    <!-- 💡 BANCO DE SUGESTÕES NATIVAS (DATALIST) PARA O AUTOCOMPLETAR ESTILO CHROME -->
    <datalist id="listaTiposFornecedores">
        <option value="Ingredientes">
        <option value="Embalagens">
        <option value="Laticínios">
        <option value="Hortifruti">
        <option value="Aromas">
    </datalist>

    <div class="container-dashboard">
        
        <!-- Injeção da barra lateral isolada e componentizada -->
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

            <!-- Bloco Geral Dividido em Duas Colunas Simétricas -->
            <div class="grid-fornecedores-container">
                
                <!-- COLUNA DA ESQUERDA: Formulário com Rolagem Interna Segura (Igual Pedidos) -->
                <div class="coluna-esquerda-cadastro">
                    <div class="card-formulario-fornecedores">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Cadastro de Fornecedor</h3>
                        
                        <form id="formCadastroFornecedor">
                            <!-- MÁGICA DO SCROLL: Essa caixa segura a rolagem interna dos inputs -->
                            <div class="wrapper-inputs-scroll-cadastro">
                                
                                <!-- Campo: Nome (SEM o botão lateral +) -->
                                <div class="grupo-input-fornecedores">
                                    <label>Nome <span class="obrigatorio">*</span></label>
                                    <input type="text" id="nomeFornecedor" placeholder="Digite o nome do fornecedor" required>
                                </div>

                                <!-- Campo: Tipo de fornecedor (Com autocompletar inteligente) -->
                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>Tipo de fornecedor <span class="obrigatorio">*</span></label>
                                    <input type="text" id="tipoFornecedor" list="listaTiposFornecedores" placeholder="Selecione ou digite o tipo..." required>
                                </div>

                                <!-- Campo: CPF/CNPJ -->
                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>CPF/CNPJ</label>
                                    <input type="text" id="cnpjFornecedor" placeholder="Digite o CPF ou CNPJ">
                                </div>

                                <!-- Campo: Telefone -->
                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>Telefone</label>
                                    <input type="text" id="telFornecedor" placeholder="(00) 00000-0000">
                                </div>

                                <!-- Campo: E-mail -->
                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>E-mail</label>
                                    <input type="email" id="emailFornecedor" placeholder="exemplo@fornecedor.com">
                                </div>

                                <!-- Linha Inline: Rua e Número -->
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

                                <!-- Linha Inline: Bairro e Cidade -->
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

                                <!-- Campo: Complemento -->
                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>Complemento</label>
                                    <input type="text" id="compFornecedor" placeholder="Digite o complemento (opcional)">
                                </div>

                                <!-- Campo: Informações adicionais -->
                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>Informações adicionais</label>
                                    <textarea id="infoFornecedor" placeholder="Digite informações adicionais sobre o fornecedor..."></textarea>
                                </div>
                            </div>

                            <!-- Apenas dois botões (Salvar e Limpar) divididos de forma simétrica -->
                            <div class="botoes-acoes-formulario-forn-duplo">
                                <button type="submit" class="btn-forn-base salvar-btn">💾 Salvar Fornecedor</button>
                                <button type="button" class="btn-forn-base limpar-btn" id="btnLimparForn">🧹 Limpar Campos</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- COLUNA DA DIREITA: Listagem e Tabela Sem Rodapé de Paginação -->
                <div class="coluna-direita-listagem">
                    <div class="card-tabela-fornecedores">

                        <!-- Barra de pesquisa e recarregar unificados em linha -->
                        <div class="linha-pesquisa-interna-forn">
                            <div class="wrapper-busca-tabela-forn">
                                <svg class="svg-busca-tabela-interna" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" id="inputPesquisaFornecedores" placeholder="Pesquisar fornecedor...">
                            </div>
                            <button type="button" class="btn-mini-tabela-topo" id="btnRecarregarTabela" title="Recarregar/Atualizar">
                                <svg class="svg-mini-topo" viewBox="0 0 24 24"><path d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                            </button>
                        </div>

                        <!-- Área Ocupada pela Tabela com Rolagem Interna Travada -->
                        <div class="wrapper-tabela-fornecedores-scroll">
                            <table class="tabela-dados-fornecedores">
                                <thead>
                                    <tr>
                                        <th style="width: 8%;">ID</th>
                                        <th style="width: 32%;">Nome</th>
                                        <th style="width: 15%;">Tipo</th>
                                        <th style="width: 25%;">CPF/CNPJ</th>
                                        <th style="width: 15%;">Telefone</th>
                                        <th style="width: 5%; text-align: center;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="corpoTabelaFornecedores">
                                    <tr>
                                        <td>101</td>
                                        <td><strong>Doces & Cia</strong></td>
                                        <td><span class="badge-tipo b-ingredientes">Ingredientes</span></td>
                                        <td>12.345.678/0001-90</td>
                                        <td>(11) 98765-4321</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha edit"><svg class="svg-linha-acao" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                            <button type="button" class="btn-acao-linha del"><svg class="svg-linha-acao" viewBox="0 0 24 24"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>102</td>
                                        <td><strong>Embalagens São Paulo</strong></td>
                                        <td><span class="badge-tipo b-embalagens">Embalagens</span></td>
                                        <td>23.456.789/0001-01</td>
                                        <td>(11) 97654-3210</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha edit"><svg class="svg-linha-acao" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                            <button type="button" class="btn-acao-linha del"><svg class="svg-linha-acao" viewBox="0 0 24 24"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Rodapé de paginação removido por completo daqui! -->
                    </div>
                </div>
    </div>

    <!-- Script de controle dinâmico mantido original -->
    <script src="../js/fornecedores.js"></script>
</body>
</html>

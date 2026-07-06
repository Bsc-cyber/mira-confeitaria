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
    <!-- Inclusão das folhas de estilo voltando uma pasta para achar o diretório css/ -->
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <link rel="stylesheet" href="../css/fornecedores.css">
</head>
<body>

    <div class="container-dashboard">
        
        <!-- Injeção da barra lateral localizada na mesma pasta corrente -->
        <?php require_once "barra_lateral.php"; ?>

        <!-- ÁREA PRINCIPAL DA GESTÃO DE FORNECEDORES -->
        <main class="painel-conteudo-fornecedores">
            
            <!-- Cabeçalho Superior da Tela idêntico à imagem enviada -->
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
                
                <!-- Controles de Pesquisa Rápidos do Topo Direito -->
                <div class="controles-topo-fornecedores">
                    <div class="wrapper-busca-topo">
                        <svg class="svg-busca-topo" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" placeholder="Pesquisar fornecedor...">
                    </div>
                    <button class="btn-filtro-topo">
                        <svg class="svg-btn-inline" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    </button>
                    <button class="btn-novo-fornecedor-topo">
                        <svg class="svg-btn-inline" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Novo Fornecedor
                    </button>
                </div>
            </header>
            <!-- Bloco Geral Dividido em Duas Colunas (Cadastro Esquerdo e Tabela Direita) -->
            <div class="grid-fornecedores-container">
                
                <!-- COLUNA DA ESQUERDA: Formulário de Cadastro de Fornecedores -->
                <div class="coluna-esquerda-cadastro">
                    <div class="card-formulario-fornecedores">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Cadastro de Fornecedor</h3>
                        
                        <form id="formCadastroFornecedor">
                            <div class="wrapper-inputs-scroll-cadastro">
                                
                                <!-- Campo: Nome -->
                                <div class="grupo-input-fornecedores">
                                    <label>Nome <span class="obrigatorio">*</span></label>
                                    <div class="input-com-botao-lateral">
                                        <input type="text" id="nomeFornecedor" placeholder="Digite o nome do fornecedor" required>
                                        <button type="button" class="btn-form-add-contato"><svg class="svg-btn-menor" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="17" y1="11" x2="23" y2="11"/></svg></button>
                                    </div>
                                </div>

                                <!-- Campo: Tipo de fornecedor -->
                                <div class="grupo-input-fornecedores m-t-8">
                                    <label>Tipo de fornecedor <span class="obrigatorio">*</span></label>
                                    <select id="tipoFornecedor" required>
                                        <option value="">Selecione o tipo de fornecedor</option>
                                        <option value="Ingredientes">Ingredientes</option>
                                        <option value="Embalagens">Embalagens</option>
                                        <option value="Laticínios">Laticínios</option>
                                        <option value="Hortifruti">Hortifruti</option>
                                        <option value="Aromas">Aromas</option>
                                    </select>
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
                                        <input type="text" id="numFornecedor" placeholder="Digite o número">
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

                            <!-- Fileira de Ações Inferiores do Formulário -->
                            <div class="botoes-acoes-formulario">
                                <button type="submit" class="btn-form-forn salvar-btn">
                                    <svg class="svg-btn-inline" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg> Salvar
                                </button>
                                <button type="button" class="btn-form-forn editar-btn" disabled>
                                    <svg class="svg-btn-inline" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Editar
                                </button>
                                <button type="button" class="btn-form-forn limpar-btn" id="btnLimparForn">
                                    <svg class="svg-btn-inline" viewBox="0 0 24 24"><path d="M12 22c5.523 0 9-4.477 9-10S17.523 2 12 2 3 6.477 3 12s3.477 10 9 10z"/><path d="M8 12h8"/></svg> Limpar
                                </button>
                                <button type="button" class="btn-form-forn excluir-btn" disabled>
                                    <svg class="svg-btn-inline" viewBox="0 0 24 24"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg> Excluir
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- COLUNA DA DIREITA: Listagem e Tabela de Fornecedores -->
                <div class="coluna-direita-listagem">
                    <div class="card-tabela-fornecedores">
                        <div class="topo-tabela-acoes">
                            <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Fornecedores Cadastrados</h3>
                            <div class="icones-topo-tabela">
                                <button type="button" class="btn-mini-tabela-topo" id="btnRecarregarTabela" title="Recarregar">
                                    <svg class="svg-mini-topo" viewBox="0 0 24 24"><path d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                                </button>
                                <button type="button" class="btn-mini-tabela-topo" title="Adicionar Linha">
                                    <svg class="svg-mini-topo" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Área Ocupada pela Tabela com Rolagem Interna Travada -->
                        <div class="wrapper-tabela-fornecedores-scroll">
                            <table class="tabela-dados-fornecedores">
                                <thead>
                                    <tr>
                                        <th style="width: 8%;">ID</th>
                                        <th style="width: 28%;">Nome</th>
                                        <th style="width: 17%;">Tipo</th>
                                        <th style="width: 22%;">CPF/CNPJ</th>
                                        <th style="width: 15%;">Telefone</th>
                                        <th style="width: 10%; text-align: center;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="corpoTabelaFornecedores">
                                    <!-- Os registros fixos simulados e os injetados via JS vão aparecer aqui -->
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

                        <!-- Barra Inferior de Paginação e Métricas -->
                        <div class="rodape-paginacao-fornecedores">
                            <span class="info-contagem-itens">Mostrando 1 a 8 de 8 fornecedores</span>
                            
                            <div class="controles-paginacao-direita">
                                <div class="seletor-linhas-pagina">
                                    <select>
                                        <option value="10">10 por página</option>
                                        <option value="20">20 por página</option>
                                    </select>
                                </div>
                                <div class="botoes-passar-pagina">
                                    <button type="button" class="btn-pagi"><svg class="svg-pag" viewBox="0 0 24 24"><polyline points="11 17 6 12 11 7"/><polyline points="18 17 13 12 18 7"/></svg></button>
                                    <button type="button" class="btn-pagi"><svg class="svg-pag" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg></button>
                                    <button type="button" class="btn-pagi ativo-pagi">1</button>
                                    <button type="button" class="btn-pagi"><svg class="svg-pag" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></button>
                                    <button type="button" class="btn-pagi"><svg class="svg-pag" viewBox="0 0 24 24"><polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/></svg></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Script de controle dinâmico -->
    <script src="../js/fornecedores.js"></script>
</body>
</html>

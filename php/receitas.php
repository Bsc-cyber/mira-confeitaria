<?php 
// SEGURANÇA MÁXIMA: Valida se o usuário fez login puxando a regra da subpasta
require_once "logica_php/home.php"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Receitas</title>
    <!-- Inclusão das folhas de estilo voltando uma pasta para achar o diretório css/ -->
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <link rel="stylesheet" href="../css/receitas.css?v=80.0">

</head>
<body>

    <div class="container-dashboard">
        
        <!-- Injeção da barra lateral localizada na mesma pasta corrente -->
        <?php require_once "barra_lateral.php"; ?>

        <!-- ÁREA PRINCIPAL DA GESTÃO DE RECEITAS -->
        <main class="painel-conteudo-receitas">
            
            <!-- Cabeçalho Superior da Tela padronizado com os SVGs das outras telas -->
            <header class="topo-receitas">
                <div class="titulo-pagina-receitas">
                    <div class="icone-titulo-rec">
                        <svg class="svg-topo-rec" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    </div>
                    <div class="alinhamento-texto-topo">
                        <h1>Receitas</h1>
                        <p>Cadastre e gerencie as receitas da sua confeitaria.</p>
                    </div>
                </div>
            </header>
            <!-- Bloco Geral Dividido em Duas Colunas (Cadastro e Listagem) -->
            <div class="grid-receitas-container">
                
                <!-- COLUNA DA ESQUERDA: Formulário de Cadastro de Receitas -->
                <div class="coluna-esquerda-cadastro">
                    <div class="card-formulario-receitas">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Cadastro de Receita</h3>
                        
                        <form id="formCadastroReceita">
                            <!-- Campo 1: Nome da Receita -->
                            <div class="grupo-input-receitas">
                                <label>Nome da Receita <span class="obrigatorio">*</span></label>
                                <div class="input-com-icone-interno">
                                    <svg class="svg-interno-input" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    <input type="text" id="nomeReceita" placeholder="Digite o nome da receita" required>
                                </div>
                            </div>

                            <!-- Campo 2: Ingredientes -->
                            <div class="grupo-input-receitas m-t-12">
                                <label>Ingredientes <span class="obrigatorio">*</span></label>
                                <textarea id="ingredientesReceita" placeholder="Liste todos os ingredientes utilizados na receita..." required></textarea>
                                <small class="dica-campo">Dica: Separe cada ingrediente em uma nova linha.</small>
                            </div>

                            <!-- Campo 3: Modo de Preparo -->
                            <div class="grupo-input-receitas m-t-12">
                                <label>Modo de Preparo <span class="obrigatorio">*</span></label>
                                <textarea id="preparoReceita" placeholder="Descreva o passo a passo do modo de preparo..." required></textarea>
                            </div>

                            <!-- Fileira de Ações Inferiores do Formulário -->
                            <div class="botoes-acoes-formulario">
                                <button type="submit" class="btn-form-rec salvar-btn">
                                    <svg class="svg-btn-inline" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Salvar
                                </button>
                                <button type="button" class="btn-form-rec editar-btn" disabled>
                                    <svg class="svg-btn-inline" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Editar
                                </button>
                                <button type="button" class="btn-form-rec limpar-btn" id="btnLimparForm">
                                    <svg class="svg-btn-inline" viewBox="0 0 24 24"><path d="M12 22c5.523 0 9-4.477 9-10S17.523 2 12 2 3 6.477 3 12s3.477 10 9 10z"/><path d="M8 12h8"/></svg> Limpar
                                </button>
                                <button type="button" class="btn-form-rec excluir-btn" disabled>
                                    <svg class="svg-btn-inline" viewBox="0 0 24 24"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg> Excluir
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- COLUNA DA DIREITA: Listagem e Tabela das Receitas Cadastradas -->
                <div class="coluna-direita-listagem">
                    <div class="card-tabela-receitas">
                        <div class="topo-tabela-acoes">
                            <!-- ÍCONE CORRIGIDO: Agora usando o desenho correto de livro sem blocos pretos -->
                            <h3>
                                <svg class="svg-card-titulo" viewBox="0 0 24 24">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                </svg> 
                                Receitas Cadastradas
                            </h3>
                        </div>

                        <!-- Barra de Pesquisa Interna da Tabela -->
                        <div class="linha-pesquisa-tabela">
                            <div class="wrapper-busca-tabela">
                                <svg class="svg-busca-tabela-interna" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" id="inputPesquisaTabela" placeholder="Pesquisar receita...">
                            </div>
                            <button type="button" class="btn-pesquisar-tabela">Pesquisar</button>
                        </div>

                        <!-- Área Ocupada pela Tabela com Rolagem Interna Travada -->
                        <div class="wrapper-tabela-receitas-scroll">
                            <table class="tabela-dados-receitas">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">ID</th>
                                        <th style="width: 25%;">Nome da Receita</th>
                                        <th style="width: 50%;">Ingredientes</th>
                                        <th style="width: 15%; text-align: center;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="corpoTabelaReceitas">
                                    <tr>
                                        <td>101</td>
                                        <td><strong>Brigadeiro Gourmet</strong></td>
                                        <td class="txt-truncado">Leite condensado, chocolate em pó, creme de leite, manteiga, granulado</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha edit"><svg class="svg-linha-acao" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                            <button type="button" class="btn-acao-linha del"><svg class="svg-linha-acao" viewBox="0 0 24 24"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>102</td>
                                        <td><strong>Bolo de Chocolate</strong></td>
                                        <td class="txt-truncado">Farinha de trigo, açúcar, chocolate em pó, ovos, leite, óleo, fermento</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha edit"><svg class="svg-linha-acao" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                            <button type="button" class="btn-acao-linha del"><svg class="svg-linha-acao" viewBox="0 0 24 24"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Barra Inferior de Paginação e Métricas -->
                        <div class="rodape-paginacao-receitas">
                            <span class="info-contagem-itens">Mostrando 1 a 7 de 7 receitas</span>
                            
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
    <script src="../js/receitas.js"></script>
</body>
</html>

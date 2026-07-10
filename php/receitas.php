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
    <!-- Folhas de estilo locais e integradas -->
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <!-- Força o navegador a ler o layout novo imediatamente sem reter lixo na memória -->
    <link rel="stylesheet" href="../css/receitas.css?v=200.0">
</head>
<body>

    <div class="container-dashboard">
        
        <!-- Injeção da barra lateral componentizada -->
        <?php require_once "barra_lateral.php"; ?>

        <!-- ÁREA PRINCIPAL DA GESTÃO DE RECEITAS -->
        <main class="painel-conteudo-receitas">
            
            <!-- Cabeçalho Superior Limpo padrão MIRA -->
            <header class="topo-receitas">
                <div class="titulo-pagina-receitas">
                    <div class="icone-titulo-rece">
                        <svg class="svg-topo-rece" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    </div>
                    <div class="alinhamento-texto-topo">
                        <h1>Receitas</h1>
                        <p>Cadastre e gerencie as receitas da sua confeitaria.</p>
                    </div>
                </div>
            </header>

            <!-- Grid de Trabalho Repartido (50% / 50%) -->
            <div class="grid-receitas-container">
                
                <!-- COLUNA DA ESQUERDA: Cadastro com o Scroll Grosso e Militar de Pedidos -->
                <div class="coluna-esquerda-cadastro">
                    <div class="card-formulario-receitas">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg> Cadastro de Receita</h3>
                        
                        <!-- Formulário limpo sem textos soltos flutuando para não empurrar os cards -->
                        <form id="formCadastroReceita" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                            <!-- wrapper-inputs-scroll-cadastro: Ativa de verdade a esteira de rolagem cinza lateral -->
                            <div class="wrapper-inputs-scroll-cadastro">
                                
                                <!-- Campo: Nome da Receita -->
                                <div class="grupo-input-receitas">
                                    <label>Nome da Receita <span class="obrigatorio">*</span></label>
                                    <input type="text" id="nomeReceita" placeholder="Digite o nome da receita" required>
                                </div>

                                <!-- Campo: Ingredients (Textarea Alta) -->
                                <div class="grupo-input-receitas m-t-8">
                                    <label>Ingredientes <span class="obrigatorio">*</span></label>
                                    <textarea id="ingredientesReceita" class="textarea-alta" placeholder="Liste todos os ingredientes da receita..." required></textarea>
                                </div>

                                <!-- Campo: Modo de Preparo (Textarea Extra Alta) -->
                                <div class="grupo-input-receitas m-t-8">
                                    <label>Modo de Preparo <span class="obrigatorio">*</span></label>
                                    <textarea id="preparoReceita" class="textarea-extra-alta" placeholder="Descreva o passo a passo do modo de preparo..." required></textarea>
                                </div>

                                <!-- 🛒 APENAS 2 BOTÕES NA BASE: Simétricos, idênticos e embutidos na rolagem contínua -->
                                <div class="botoes-acoes-formulario-rece-duplo">
                                    <button type="submit" class="btn-rece-base salvar-btn">💾 Salvar</button>
                                    <button type="button" class="btn-rece-base limpar-btn" id="btnLimparRece">🧹 Limpar</button>
                                </div>

                            </div> <!-- Fecha a div wrapper-inputs-scroll-cadastro -->
                        </form>
                    </div>
                </div>
                <!-- COLUNA DA DIREITA: Listagem e Tabela de Receitas Cadastradas -->
                <div class="coluna-direita-listagem">
                    <div class="card-tabela-receitas">
                        <div class="topo-tabela-acoes-prod">
                            <h3><svg class="svg-card-title-prod" viewBox="0 0 24 24" width="14" height="14" stroke="#171d14" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Receitas Cadastradas</h3>
                        </div>

                        <!-- Barra de pesquisa interna e recarregar embutidos dentro do card -->
                        <div class="linha-pesquisa-interna-rece">
                            <div class="wrapper-busca-tabela-rece">
                                <svg class="svg-busca-tabela-interna" viewBox="0 0 24 24" width="12" height="12"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" id="inputPesquisaGlobal" placeholder="Pesquisar receita...">
                            </div>
                            <button type="button" class="btn-mini-tabela-topo" id="btnRecarregarReceitas" title="Recarregar Tabela">
                                <svg class="svg-mini-topo" viewBox="0 0 24 24" width="12" height="12"><path d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                            </button>
                        </div>

                        <!-- Área de Rolagem Otimizada com Blindagem Inline Direta -->
                        <div class="wrapper-tabela-receitas-scroll">
                            <table class="tabela-dados-receitas" style="display: table !important; width: 100% !important; table-layout: fixed !important; border-collapse: collapse !important;">
                                <thead>
                                    <tr style="display: table-row !important;">
                                        <th style="width: 10%;">ID</th>
                                        <th style="width: 30%;">Nome da Receita</th>
                                        <th style="width: 50%;">Ingredientes</th>
                                        <th style="width: 10%; text-align: center;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="corpoTabelaReceitas">
                                    <!-- Registro 1: Brigadeiro Gourmet com botão Excluir -->
                                    <tr class="linha-selecionavel-rece" data-id="101" style="display: table-row !important;">
                                        <td>101</td>
                                        <td><strong>Brigadeiro Gourmet</strong></td>
                                        <td>Leite condensado, chocolate em pó, creme de leite, manteiga...</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha del" title="Excluir"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                    <!-- Registro 2: Bolo de Chocolate com botão Excluir -->
                                    <tr class="linha-selecionavel-rece" data-id="102" style="display: table-row !important;">
                                        <td>102</td>
                                        <td><strong>Bolo de Chocolate</strong></td>
                                        <td>Farinha de trigo, açúcar, chocolate em pó, ovos, leite...</td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha del" title="Excluir"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <!-- Fecha o wrapper-tabela-receitas-scroll -->

                        <!-- Rodapé limpo contendo apenas a contagem total de itens -->
                        <div class="rodape-paginacao-receitas">
                            <span class="info-contagem-itens">Mostrando 1 a 2 de 2 receitas cadastradas</span>
                        </div>

                    </div> <!-- Fecha o card-tabela-receitas -->
                </div> <!-- Fecha a coluna-direita-listagem -->

            </div> <!-- Fecha o grid-receitas-container -->
        </main>
    </div> <!-- Fecha o container-dashboard -->

    <!-- Motor JavaScript nativo original do seu colega -->
    <script src="../js/receitas.js"></script>
</body>
</html>

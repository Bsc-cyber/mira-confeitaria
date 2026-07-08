<?php 
// SEGURANÇA: Valida se o usuário fez login puxando a regra da subpasta
require_once "logica_php/home.php"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Gestão de Pedidos</title>
    <!-- Inclusão das folhas de estilo unificadas a partir da subpasta -->
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <link rel="stylesheet" href="../css/pedidos.css">
</head>
<body>

    <!-- 💡 BANCO DE SUGESTÕES NATIVAS (DATALISTS) PARA O FILTRO ESTILO CHROME -->
    <datalist id="listaClientesSugestoes">
        <option value="Mariana Silva">
        <option value="Carlos Eduardo">
        <option value="Ana Beatriz">
        <option value="Juliana Ribeiro">
    </datalist>

    <datalist id="listaProdutosSugestoes">
        <option value="Bolo de Chocolate Premium" data-preco="85.00">
        <option value="Cheesecake de Frutas Vermelhas" data-preco="95.00">
        <option value="Torta de Limão Siciliano" data-preco="75.00">
    </datalist>

    <datalist id="listaSaboresSugestoes">
        <option value="Tradicional">
        <option value="Ninho com Morango">
        <option value="Nutella com Leite Ninho">
        <option value="Doce de Leite com Ameixa">
    </datalist>

    <datalist id="listaTamanhosSugestoes">
        <option value="Pequeno (1kg)">
        <option value="Médio (2kg)">
        <option value="Grande (3kg)">
    </datalist>

    <div class="container-dashboard">
        
        <!-- Injeção da barra lateral componentizada -->
        <?php require_once "barra_lateral.php"; ?>

        <!-- ÁREA PRINCIPAL DA GESTÃO DE PEDIDOS -->
        <main class="painel-conteudo-pedidos">
            
            <!-- Cabeçalho Principal da Tela -->
            <header class="topo-pedidos">
                <div class="titulo-pagina">
                    <div class="icone-titulo">
                        <svg class="svg-topo-ped" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <div>
                        <h1>Gestão de Pedidos</h1>
                        <p>Acompanhe e gerencie todos os pedidos da sua confeitaria</p>
                    </div>
                </div>
            </header>

            <!-- Bloco Grid Dividido em Duas Colunas (50% / 50%) -->
            <div class="grid-pedidos-container">
                
                <!-- COLUNA DA ESQUERDA: Montagem do Pedido Corrente -->
                <div class="coluna-esquerda-formulario">
                    
                    <!-- 1. Bloco: Dados do Pedido (Cliente com seta e autocompletar combinados!) -->
                    <div class="card-formulario-pedidos">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Dados do Pedido</h3>
                        <div class="linha-inputs-inline">
                            <div class="grupo-input-pedidos">
                                <label>Cliente</label>
                                <!-- Mantém a setinha nativa do select combinando com a digitação livre do Chrome -->
                                <input type="text" id="selectCliente" list="listaClientesSugestoes" placeholder="Selecione ou digite o cliente...">
                            </div>
                            <div class="grupo-input-pedidos">
                                <label>Data do Pedido</label>
                                <input type="date" id="dataPedido" value="2026-07-06">
                            </div>
                            <div class="grupo-input-pedidos">
                                <label>Entrega</label>
                                <input type="date" id="dataEntrega" value="2026-07-06">
                            </div>
                            <div class="grupo-input-pedidos">
                                <label>Status</label>
                                <select id="statusInicial">
                                    <option value="Pendente">Pendente</option>
                                    <option value="Em Produção">Em Produção</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- 2. Bloco: Seleção do Produto (SEM BOTÕES LUPA, SEM BOTÕES + E COM AUTOCOMPLETAR COMPLETO) -->
                    <div class="card-formulario-pedidos">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg> Produto</h3>
                        
                        <!-- Fileira do Produto: Limpa, portando largura total e autocompletar -->
                        <div class="linha-produto-acoes">
                            <div class="grupo-input-pedidos esticar">
                                <label>Produto</label>
                                <input type="text" id="selectProduto" list="listaProdutosSugestoes" placeholder="Selecione ou digite o produto...">
                            </div>
                        </div>

                        <!-- Fileira do Sabor: Limpa, portando largura total e autocompletar -->
                        <div class="linha-produto-acoes m-t-10">
                            <div class="grupo-input-pedidos esticar">
                                <label>Sabor</label>
                                <input type="text" id="selectSabor" list="listaSaboresSugestoes" placeholder="Selecione ou digite o sabor...">
                            </div>
                        </div>

                        <!-- Fileira do Tamanho: Limpa, portando largura total e autocompletar -->
                        <div class="linha-produto-acoes m-t-10">
                            <div class="grupo-input-pedidos esticar">
                                <label>Tamanho</label>
                                <input type="text" id="selectTamanho" list="listaTamanhosSugestoes" placeholder="Selecione ou digite o tamanho...">
                            </div>
                        </div>

                        <!-- Quantidade e Bloco de Exibição do Preço -->
                        <div class="linha-inputs-inline m-t-10">
                            <div class="grupo-input-pedidos">
                                <label>Quantidade</label>
                                <input type="number" id="inputQuantidade" min="1" value="1">
                            </div>
                            <div class="bloco-exibicao-preco">
                                <span class="label-preco">Preço</span>
                                <div class="caixa-preco-total" id="precoDisplay">R$ 0,00</div>
                            </div>
                        </div>

                        <!-- Observações do Item -->
                        <div class="grupo-input-pedidos m-t-10">
                            <label>Observações</label>
                            <textarea id="txtObservacoes" placeholder="Digite observações sobre o item..."></textarea>
                        </div>

                        <!-- Botão Adicionar ao Carrinho -->
                        <button type="button" class="btn-add-carrinho" id="btnAdicionarCarrinho">
                            <svg class="svg-btn-inline" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg> Adicionar ao Carrinho
                        </button>
                    </div>
                    <!-- 3. Bloco: Tabela Dinâmica do Pedido Atual -->
                    <div class="card-tabela-carrinho">
                        <div class="wrapper-tabela-scroll">
                            <table class="tabela-itens-pedido" id="tabelaCarrinho">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Sabor</th>
                                        <th>Tam.</th>
                                        <th>Qtd.</th>
                                        <th>Preço</th>
                                        <th style="width: 35px; text-align: center;"></th>
                                    </tr>
                                </thead>
                                <tbody id="corpoTabelaCarrinho">
                                    <!-- Os itens inseridos via JavaScript entrarão aqui dinamicamente -->
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="rodape-valores-esquerda">
                            <span class="total-pedido-txt">Total do pedido: <strong id="totalPedidoDisplay">R$ 0,00</strong></span>
                            <!-- BOTÕES CORRIGIDOS: Agora dividem o mesmo espaço de forma simétrica e destacada -->
                            <div class="botoes-acoes-esquerda-unificados">
                                <button type="button" class="btn-carrinho-base botao-limpar-lote" id="btnLimparCarrinho">🗑️ Limpar Carrinho</button>
                                <button type="button" class="btn-carrinho-base botao-gerar-lote" id="btnGerarPedido">⚙️ Gerar Pedido</button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- COLUNA DA DIREITA: Filtros Unificados em Linha e Área de Produção -->
                <div class="coluna-direita-producao">
                    
                    <!-- 1. Bloco: Indicadores e Filtros de Produção UNIFICADOS EM UMA FILEIRA -->
                    <div class="card-producao-filtros">
                        <div class="topo-indicadores-producao">
                            <div class="badge-ind prod">⚙️ Em Produção: <span id="countProd">0</span></div>
                            <div class="badge-ind pend">⏳ Pendentes: <span id="countPend">0</span></div>
                            <div class="badge-ind fin">✅ Finalizados: <span id="countFin">0</span></div>
                            <div class="badge-ind atr">🚨 Atrasados: <span id="countAtr">0</span></div>
                        </div>

                        <!-- NOVA FILEIRA HORIZONTAL INTEGRADORA DE QUATRO CAMPOS -->
                        <div class="fatura-filtros-linha-unica">
                            <div class="campo-filtros-unificados">
                                <input type="date" id="filtroData" value="2026-07-06">
                            </div>
                            <div class="campo-filtros-unificados">
                                <select id="filtroStatus">
                                    <option value="">Status</option>
                                    <option value="Pendente">Pendente</option>
                                    <option value="Em Produção">Em Produção</option>
                                </select>
                            </div>
                            <!-- Novo Campo de Pesquisa de Ordens solicitado por você -->
                            <div class="campo-filtros-unificados esticar-pesquisa">
                                <input type="text" id="pesquisaPedidoFila" placeholder="Pesquisar pedido...">
                            </div>
                            <button type="button" class="btn-filtrar-producao-novo">
                                <svg class="svg-btn-inline" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg> Filtrar
                            </button>
                        </div>
                    </div>

                    <!-- 2. Bloco: Área de Detalhes / Lista de Produção (SEM O BOTÃO DETALHES) -->
                    <div class="card-lista-producao-status">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Detalhes</h3>
                        
                        <div class="wrapper-producao-scroll" id="containerCardsProducao">
                            <div class="estado-vazio-producao" id="estadoVazioId">
                                <svg class="svg-vazio" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><circle cx="11" cy="13" r="3"/></svg>
                                <h4>Nenhum item adicionado</h4>
                                <p>Adicione produtos ao carrinho para visualizar os detalhes do pedido.</p>
                            </div>
                        </div>

                        <!-- Botão único mantido conforme solicitado por você -->
                        <div class="rodape-botoes-direita-unico">
                            <button type="button" class="btn-finalizar-pedido-completo-unico">✓ Finalizar Pedido</button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <!-- MODAL DE DETALHES DO PEDIDO (INICIALMENTE OCULTO) -->
    <div id="modalDetalhes" class="modal-oculto">
        <div class="modal-conteudo">
            <div class="modal-cabecalho">
                <h3 id="modalTitulo">Pedido #00</h3>
                <button type="button" id="btnFecharModal">❌</button>
            </div>
            <div class="modal-corpo">
                <p><strong>Cliente:</strong> <span id="modalCliente"></span></p>
                <hr style="margin: 10px 0; border: 0; border-top: 1px solid #e5e7eb;">
                <h4 style="font-size: 0.8rem; color: #4b5563; margin-bottom: 5px;">Itens do Pedido:</h4>
                <ul id="modalListaItens" class="lista-itens-modal">
                    </ul>
                <hr style="margin: 10px 0; border: 0; border-top: 1px solid #e5e7eb;">
                <p class="modal-total"><strong>Total:</strong> <strong id="modalTotal">R$ 0,00</strong></p>
                
                <div class="modal-status-box">
                    <label style="font-size: 0.75rem; font-weight: bold;">Status da Produção:</label>
                    <select id="modalSelectStatus">
                        <option value="Pendente">Pendente</option>
                        <option value="Em Produção">Em Produção</option>
                    </select>
                </div>
            </div>
            <div class="modal-rodape" style="display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="btn-cancelar-pedido" id="btnCancelarPedidoModal">🗑️ Cancelar Pedido</button>
                <button type="button" class="btn-salvar-status" id="btnSalvarStatusModal">💾 Salvar Alteração</button>
            </div>
        </div>
    </div>
    <!-- Inclusão do script JavaScript específico para a página de pedidos. Direciona para a pasta correta -->
    <script src="../js/pedidos.js"></script>
</body>
</html>

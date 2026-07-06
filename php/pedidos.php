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
    <!-- Inclusão das folhas de estilo voltando uma pasta para achar o diretório css/ -->
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <link rel="stylesheet" href="../css/pedidos.css">
</head>
<body>

    <div class="container-dashboard">
        
        <!-- Injeção da barra lateral localizada na mesma pasta corrente -->
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

            <!-- Bloco Geral Dividido em Duas Colunas Principais -->
            <div class="grid-pedidos-container">
                
                <!-- COLUNA DA ESQUERDA: Criação do Pedido e Adição de Produtos -->
                <div class="coluna-esquerda-formulario">
                    
                    <!-- 1. Bloco: Dados do Pedido -->
                    <div class="card-formulario-pedidos">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Dados do Pedido</h3>
                        <div class="linha-inputs-inline">
                            <div class="grupo-input-pedidos">
                                <label>Cliente</label>
                                <select id="selectCliente">
                                    <option value="">Selecione o cliente</option>
                                    <option value="Mariana Silva">Mariana Silva</option>
                                    <option value="Carlos Eduardo">Carlos Eduardo</option>
                                </select>
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
                    <!-- 2. Bloco: Seleção do Produto -->
                    <div class="card-formulario-pedidos">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg> Produto</h3>
                        <div class="linha-produto-acoes">
                            <div class="grupo-input-pedidos esticar">
                                <label>Produto</label>
                                <select id="selectProduto">
                                    <option value="">Selecione o produto</option>
                                    <option value="Bolo de Chocolate Premium" data-preco="85.00">Bolo de Chocolate Premium</option>
                                    <option value="Cheesecake de Frutas Vermelhas" data-preco="95.00">Cheesecake de Frutas Vermelhas</option>
                                    <option value="Torta de Limão Siciliano" data-preco="75.00">Torta de Limão Siciliano</option>
                                </select>
                            </div>
                            <button type="button" class="btn-pesquisa">
                                <svg class="svg-btn" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            </button>
                            <button type="button" class="btn-cadastro-rapido">
                                <svg class="svg-btn" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </button>
                        </div>

                        <div class="linha-produto-acoes m-t-10">
                            <div class="grupo-input-pedidos esticar">
                                <label>Sabor</label>
                                <select id="selectSabor">
                                    <option value="">Selecione o sabor</option>
                                    <option value="Tradicional">Tradicional</option>
                                    <option value="Ninho com Morango">Ninho com Morango</option>
                                </select>
                            </div>
                            <button type="button" class="btn-pesquisa">
                                <svg class="svg-btn" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            </button>
                            <button type="button" class="btn-cadastro-rapido">
                                <svg class="svg-btn" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </button>
                        </div>

                        <div class="linha-produto-acoes m-t-10">
                            <div class="grupo-input-pedidos esticar">
                                <label>Tamanho</label>
                                <select id="selectTamanho">
                                    <option value="">Selecione o tamanho</option>
                                    <option value="Pequeno (1kg)">Pequeno (1kg)</option>
                                    <option value="Médio (2kg)">Médio (2kg)</option>
                                    <option value="Grande (3kg)">Grande (3kg)</option>
                                </select>
                            </div>
                            <button type="button" class="btn-cadastro-rapido">
                                <svg class="svg-btn" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </button>
                        </div>

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

                        <div class="grupo-input-pedidos m-t-10">
                            <label>Observações</label>
                            <textarea id="txtObservacoes" placeholder="Digite observações sobre o item..."></textarea>
                        </div>

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
                                        <!-- Espaço para o botão de exclusão do item -->
                                        <th style="width: 30px;"></th> </tr>
                                    </tr>
                                </thead>
                                <tbody id="corpoTabelaCarrinho">
                                    <!-- Os itens inseridos via JavaScript entrarão aqui dinamicamente -->
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="rodape-valores-esquerda">
                            <span class="total-pedido-txt">Total do pedido: <strong id="totalPedidoDisplay">R$ 0,00</strong></span>
                            <div class="botoes-acoes-esquerda">
                                <button type="button" class="btn-aux-esquerda" disabled>📝 Editar Produto</button>
                                <button type="button" class="btn-aux-esquerda vermelho-btn" id="btnLimparCarrinho">🗑️ Limpar Carrinho</button>
                                <button type="button" class="btn-acao-principal-esquerda" id="btnGerarPedido">⚙️ Gerar Pedido</button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- COLUNA DA DIREITA: Filtros e Área de Produção -->
                <div class="coluna-direita-producao">
                    
                    <!-- 1. Bloco: Indicadores e Filtros de Produção -->
                    <div class="card-producao-filtros">
                        <div class="topo-indicadores-producao">
                            <div class="badge-ind prod">⚙️ Em Produção: <span id="countProd">0</span></div>
                            <div class="badge-ind pend">⏳ Pendentes: <span id="countPend">0</span></div>
                            <div class="badge-ind fin">✅ Finalizados: <span id="countFin">0</span></div>
                            <div class="badge-ind atr">🚨 Atrasados: <span id="countAtr">0</span></div>
                        </div>

                        <div class="linha-filtros-producao">
                            <input type="date" id="filtroData" value="2026-07-06">
                            <select id="filtroStatus">
                                <option value="">Selecione o status</option>
                                <option value="Pendente">Pendente</option>
                                <option value="Em Produção">Em Produção</option>
                            </select>
                            <button type="button" class="btn-filtrar-producao">
                                <svg class="svg-btn-inline" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg> Filtrar
                            </button>
                        </div>
                    </div>

                    <!-- 2. Bloco: Área de Detalhes / Lista de Produção -->
                    <div class="card-lista-producao-status">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Detalhes</h3>
                        
                        <div class="wrapper-producao-scroll" id="containerCardsProducao">
                            <div class="estado-vazio-producao" id="estadoVazioId">
                                <svg class="svg-vazio" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><circle cx="11" cy="13" r="3"/></svg>
                                <h4>Nenhum item adicionado</h4>
                                <p>Adicione produtos ao carrinho para visualizar os detalhes do pedido.</p>
                            </div>
                        </div>

                        <div class="rodape-botoes-direita">
                            <button type="button" class="btn-detalhes-final">Detalhes do Pedido</button>
                            <button type="button" class="btn-finalizar-pedido-completo">✓ Finalizar Pedido</button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <!-- Script de controle dinâmico -->
    <script src="../js/pedidos.js"></script>
</body>
</html>

<?php 
// SEGURANÇA: Valida se o usuário fez login puxando a regra de sessão da subpasta
require_once "logica_php/home.php"; 
?> 
<!DOCTYPE html> 
<html lang="pt-BR"> 
<head> 
    <!-- Define a codificação global de caracteres para evitar quebras em palavras acentuadas -->
    <meta charset="UTF-8"> 
    <!-- Ajusta a área de visualização para garantir a responsividade em telas mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>MIRA Confeitaria - Registro de Vendas</title> 
    <!-- Inclusão das folhas de estilo unificadas a partir da subpasta -->
    <link rel="stylesheet" href="../css/barra_lateral.css"> 
    <link rel="stylesheet" href="../css/vendas.css?v=3.0"> 
</head> 
<body> 

    <!-- 💡 BANCO DE SUGESTÕES NATIVAS (DATALISTS) PARA O FILTRO ESTILO CHROME -->
    <datalist id="listaClientesSugestoes"> 
        <option value="Consumidor Final"> 
        <option value="Mariana Silva"> 
        <option value="Carlos Eduardo"> 
    </datalist> 

    <datalist id="listaProdutosSugestoes"> 
        <option value="Bolo de Chocolate Premium" data-preco="85.00"> 
        <option value="Cheesecake de Frutas Vermelhas" data-preco="95.00"> 
        <option value="Torta de Limão Siciliano" data-preco="75.00"> 
    </datalist> 

    <!-- Estrutura principal do painel que engloba a barra lateral e a área útil de conteúdo -->
    <div class="container-dashboard"> 
        
        <!-- Injeção da barra lateral componentizada isolada em arquivo externo -->
        <?php require_once "barra_lateral.php"; ?> 

        <!-- ÁREA PRINCIPAL DO PONTO DE VENDA (PDV) -->
        <main class="painel-conteudo-vendas"> 
            
            <!-- Cabeçalho Principal da Tela com tamanho do ícone de carrinho domado e controlado -->
            <header class="topo-vendas"> 
                <div class="titulo-pagina"> 
                    <div class="icone-titulo"> 
                        <!-- 🔥 TRAVA DE SEGURANÇA: Largura e altura inseridas direto no código para nunca mais estourar -->
                        <svg class="svg-topo-ped" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#171d14" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </div> 
                    <div> 
                        <h1>Ponto de Venda</h1> 
                        <p>Registre saídas rápidas e faça o fechamento de caixas do dia</p> 
                    </div> 
                </div> 
            </header> 

            <!-- Bloco Grid Flexbox configurado via CSS externo para esticar verticalmente -->
            <div class="grid-vendas-container">
                <!-- COLUNA DA ESQUERDA: LISTAGEM DE ITENS E PESQUISA (Ocupa 70% da largura) -->
                <div class="coluna-esquerda-vendas">
                    <div class="card-vendas-box">
                        
                        <!-- Painel superior com o nome do cliente e a barra de busca alinhada -->
                        <div class="linha-topo-pdv">
                            <div class="titulo-secao-compras">
                                <small>CLIENTE: CONSUMIDOR FINAL</small>
                                <h3>
                                    <!-- Trava de segurança inserida direto no vetor para blindar o visual -->
                                    <svg class="svg-card-titulo" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#171d14" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                    Itens da Compra
                                </h3>
                            </div>
                            
                            <!-- Grupo de busca integrado com os botões simétricos de ação -->
                            <div class="grupo-busca-barra">
                                <datalist id="listaPedidosProntos"></datalist>
                                <input type="text" id="inputBuscaPedido" list="listaPedidosProntos" placeholder="Digite ou selecione o nome do cliente..." style="width: 400px;">
                                <button type="button" class="btn-adicionar-azul" id="btnCarregarPedido" title="Carregar Pedido">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#ffffff" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Estrutura da esteira de produtos configurada para ocupar o espaço elástico vertical -->
                        <div class="card-tabela-carrinho">
                            <div class="wrapper-tabela-scroll">
                                <table class="tabela-itens-venda">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Produto</th>
                                            <th>Qtd</th>
                                            <th>Vlr. Unitário</th>
                                            <th>Subtotal</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody id="corpoTabelaPdv">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- COLUNA DA DIREITA: RESUMO FINANCEIRO COMPLETO (Ocupa 30% da largura) -->
                <div class="coluna-direita-vendas">
                    <div class="card-vendas-box resumo-flex-box">
                        
                        <!-- Conteúdos superiores dos indicadores financeiros e descontos -->
                        <div class="conteudo-topo-resumo">
                            <h3 class="titulo-resumo-caixa">
                                <!-- Ícone de resumo blindado com tamanho fixo nativo em HTML -->
                                <svg class="svg-card-titulo" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#171d14" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                Resumo da Compra
                            </h3>

                            <div class="linha-resumo-valores">
                                <span>Total de Itens:</span>
                                <strong id="contagemItensDestaque">0</strong>
                            </div>

                            <div class="linha-resumo-valores">
                                <span>Subtotal:</span>
                                <span id="subtotalDestaque" class="valor-direita" style="font-weight: bold;">R$ 0,00</span>
                            </div>

                            <div class="grupo-input-vendas">
                                <label>Desconto (R$):</label>
                                <input type="number" id="descontoInput" value="0.00" step="0.01" min="0">
                            </div>

                            <!-- Caixa de destaque visual exibindo o valor líquido total em verde -->
                            <div class="caixa-totalizador-pdv">
                                <span class="label-total-verde">TOTAL:</span>
                                <span class="valor-total-grande" id="totalFinalDisplay">R$ 0,00</span>
                            </div>

                            <div class="grupo-input-vendas">
                                <label>Forma de Pagamento:</label>
                                <select id="selectPagamento">
                                    <option value="pix">PIX</option>
                                    <option value="dinheiro">Dinheiro</option>
                                    <option value="debito">Cartão de Débito</option>
                                    <option value="credito">Cartão de Crédito</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botão simétrico na cor oficial verde musgo escuro da MIRA confeitaria -->
                        <div class="rodape-botoes-direita-unico">
                            <button type="button" id="btnFinalizarVendaCompleto" class="btn-finalizar-venda-grafite" onclick="alert('O botão está vivo e recebendo o clique!'); abrirCupomDireto();">
                                <span>✓ Finalizar Venda</span>
                            </button>
                        </div>

                    </div>
                </div>

            </div> <!-- Fecha a tag .grid-vendas-container iniciada na Parte 1 -->
        </main> <!-- Fecha a tag .painel-conteudo-vendas iniciada na Parte 1 -->
    </div> <!-- Fecha a tag .container-dashboard iniciada na Parte 1 -->

    
    <!-- MODAL DE FECHAMENTO DE VENDA E CUPOM (RECIBO) -->
    <div id="modalDetalhes" class="modal-oculto"> 
        <div class="modal-conteudo cupom-container"> 
            
            <div class="modal-cabecalho no-print"> 
                <h3 id="modalTitulo">Venda Finalizada!</h3> 
                <button type="button" id="btnFecharModal">❌</button> 
            </div> 
            
            <!-- ÁREA DO CUPOM (É isso que sai na impressora térmica ou no PDF) -->
            <div class="modal-corpo cupom-impresso" id="cupomArea"> 
                <div class="cabecalho-cupom">
                    <h2>MIRA CONFEITARIA</h2>
                    <p>Recibo de Venda #<span id="cupomNumero">000</span></p>
                    <p>Data: <span id="cupomData">00/00/0000</span></p>
                    <hr class="divisor-cupom">
                    <p><strong>Cliente:</strong> <span id="cupomCliente">Consumidor Final</span></p>
                </div>
                
                <hr class="divisor-cupom">
                <table class="tabela-cupom">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Qtd</th>
                            <th style="width: 50%;">Descrição</th>
                            <th style="width: 20%;">V.Un</th>
                            <th style="width: 20%;">Total</th>
                        </tr>
                    </thead>
                    <tbody id="cupomListaItens">
                        <!-- Itens entram aqui -->
                    </tbody>
                </table>
                <hr class="divisor-cupom">
                
                <div class="totais-cupom">
                    <p>Subtotal: <span id="cupomSubtotal">R$ 0,00</span></p>
                    <p>Desconto: <span id="cupomDesconto">R$ 0,00</span></p>
                    <h3>TOTAL: <span id="cupomTotalFinal">R$ 0,00</span></h3>
                    <p style="margin-top: 5px;">Forma Pagto: <strong><span id="cupomPagamento">PIX</span></strong></p>
                </div>
                
                <div class="rodape-cupom">
                    <p>Obrigado pela preferência!</p>
                    <p>Volte Sempre 🧁</p>
                </div>
            </div> 

            <!-- BOTÕES DE AÇÃO DO CUPOM -->
            <div class="modal-rodape botoes-cupom no-print"> 
                <button type="button" id="btnImprimirCupom" class="btn-acao-modal bg-cinza">🖨️ Imprimir</button> 
                <button type="button" id="btnBaixarCupom" class="btn-acao-modal bg-azul">📥 Baixar</button> 
                <button type="button" id="btnZapCupom" class="btn-acao-modal bg-verde">📱 WhatsApp</button> 
            </div> 
        </div> 
    </div>

    <!-- Chamada isolada do arquivo JavaScript contendo a lógica dos cálculos do PDV -->
    <!-- <script src="../js/vendas.js"></script> -->
    <script src="../js/vendas.js?v=<?php echo time(); ?>"></script>
</body> 
</html>

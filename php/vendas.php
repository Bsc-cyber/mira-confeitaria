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
                                <input type="text" id="inputBuscaPdv" placeholder="Nome, CPF ou Telefone...">
                                
                                <button type="button" class="btn-pesquisa-lupa">
                                    <!-- Ícone da lupa travado nativamente para proteção visual -->
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#171d14" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                </button>
                                
                                <button type="button" class="btn-adicionar-azul">
                                    <!-- Símbolo de adição (+) blindado em tamanho fixo corporativo -->
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
                                        <!-- Estado inicial indicando carrinho vazio aguardando lançamentos de balcão -->
                                        <tr>
                                            <td colspan="6" class="linha-vazia-texto">
                                                Nenhum produto adicionado à compra até o momento.
                                            </td>
                                        </tr>
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
                            <button type="button" id="btnFinalizarVendaCompleto" class="btn-finalizar-venda-grafite">
                                <span>✓ Finalizar Venda</span>
                            </button>
                        </div>

                    </div>
                </div>

            </div> <!-- Fecha a tag .grid-vendas-container iniciada na Parte 1 -->
        </main> <!-- Fecha a tag .painel-conteudo-vendas iniciada na Parte 1 -->
    </div> <!-- Fecha a tag .container-dashboard iniciada na Parte 1 -->

    <!-- MODAL DE FECHAMENTO DE VENDA COMPLETO (INICIALMENTE OCULTO) -->
    <div id="modalDetalhes" class="modal-oculto"> 
        <div class="modal-conteudo"> 
            <div class="modal-cabecalho"> 
                <h3 id="modalTitulo">Venda Realizada #00</h3> 
                <button type="button" id="btnFecharModal">❌</button> 
            </div> 
            <div class="modal-corpo"> 
                <p><strong>Cliente:</strong> <span id="modalCliente">Consumidor Final</span></p> 
                <hr class="divisor-modal"> 
                <h4 class="subtitulo-itens-modal">Produtos do Cupom:</h4> 
                <ul id="modalListaItens" class="lista-itens-modal"></ul> 
                <hr class="divisor-modal"> 
                <p class="modal-total"><strong>Total Pago:</strong> <strong id="modalTotal" style="color: #166534;">R$ 0,00</strong></p> 
                <div class="modal-status-box">
                    <label>Status do Caixa:</label> 
                    <select id="modalSelectStatus" disabled> 
                        <option value="Concluida">Venda Concluída</option> 
                    </select> 
                </div> 
            </div> 
            <div class="modal-rodape"> 
                <button type="button" id="btnImprimirCupom" class="btn-imprimir-cupom-azul">🖨️ Imprimir Cupom</button> 
            </div> 
        </div> 
    </div> 

    <!-- Chamada isolada do arquivo JavaScript contendo a lógica dos cálculos do PDV -->
    <script src="../js/vendas.js"></script> 
</body> 
</html>

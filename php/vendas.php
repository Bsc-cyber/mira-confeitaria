<?php 
// SEGURANÇA MÁXIMA: Garante o login do usuário
require_once "logica_php/home.php"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Frente de Caixa (Vendas)</title>
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <!-- Versão 25.0 para forçar o Chrome a desenhar o novo campo de desconto imediatamente -->
    <link rel="stylesheet" href="../css/vendas.css?v=25.0">
</head>
<body>

    <datalist id="listaPedidosPendentes">
        <option value="Pedido #1 - Mariana Silva (R$ 85,00)">
        <option value="Pedido #2 - Carlos Eduardo (R$ 170,00)">
    </datalist>

    <div class="container-dashboard">
        
        <?php require_once "barra_lateral.php"; ?>

        <main class="painel-conteudo-vendas">
            
            <header class="topo-vendas">
                <div class="titulo-pagina-vendas">
                    <div class="icone-titulo-vend">
                        <svg class="svg-topo-vend" viewBox="0 0 24 24" width="18" height="18" stroke="#171d14" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div class="alinhamento-texto-topo">
                        <h1>Frente de Caixa (Vendas)</h1>
                        <p>Fature os pedidos da confeitaria e gerencie o histórico de entradas.</p>
                    </div>
                </div>
            </header>

            <div class="grid-vendas-container">
                
                <div class="coluna-esquerda-cadastro">
                    <div class="card-formulario-vendas">
                        <h3><svg class="svg-card-titulo" viewBox="0 0 24 24" width="14" height="14" stroke="#171d14" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12" y2="18"/><line x1="6" y1="6" x2="18" y2="6"/><line x1="6" y1="10" x2="18" y2="10"/><line x1="6" y1="14" x2="18" y2="14"/></svg> Registrar Recebimento</h3>
                        
                        <form id="formFrenteCaixa" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                            <div class="wrapper-inputs-scroll-cadastro">
                                
                                <div class="grupo-input-vendas">
                                    <label>Importar Pedido <span class="obrigatorio">*</span></label>
                                    <input type="text" id="importarPedido" list="listaPedidosPendentes" placeholder="Digite o número ou nome do cliente..." required>
                                </div>

                                <div class="grupo-input-vendas m-t-8">
                                    <label>Cliente</label>
                                    <input type="text" id="clienteVenda" placeholder="Nome do cliente carregado automaticamente" readonly>
                                </div>

                                <div class="grupo-input-vendas m-t-8">
                                    <label>Valor do Pedido</label>
                                    <div class="prefixo-moeda-wrapper">
                                        <span class="prefixo-rs">R$</span>
                                        <input type="text" id="valorBrutoVenda" placeholder="0,00" readonly>
                                    </div>
                                </div>

                                <!-- 🛒 CAMPO NOVO ADICIONADO: Desconto em reais posicionado perfeitamente -->
                                <div class="grupo-input-vendas m-t-8">
                                    <label>Desconto (Opcional)</label>
                                    <div class="prefixo-moeda-wrapper">
                                        <span class="prefixo-rs">R$</span>
                                        <input type="text" id="descontoVenda" placeholder="0,00">
                                    </div>
                                </div>

                                <div class="grupo-input-vendas m-t-8">
                                    <label>Forma de Pagamento <span class="obrigatorio">*</span></label>
                                    <select id="formaPagamento" required>
                                        <option value="">Selecione a forma...</option>
                                        <option value="Dinheiro">Dinheiro</option>
                                        <option value="Pix">Pix</option>
                                        <option value="Cartão de Crédito">Cartão de Crédito</option>
                                        <option value="Cartão de Débito">Cartão de Débito</option>
                                    </select>
                                </div>

                                <div class="grupo-input-vendas m-t-8">
                                    <label>Status do Pagamento <span class="obrigatorio">*</span></label>
                                    <select id="statusPagamento" required>
                                        <option value="Pago">🟢 Pago (Finalizado)</option>
                                        <option value="Aguardando">🔴 Aguardando Pagamento</option>
                                    </select>
                                </div>

                                <div class="grupo-input-vendas m-t-8">
                                    <label>Observações do Caixa</label>
                                    <textarea id="obsVenda" placeholder="Digite observações sobre o recebimento..."></textarea>
                                </div>

                                <div class="botoes-acoes-formulario-vend-triplo">
                                    <button type="submit" class="btn-vend-base salvar-btn">💾 Faturar</button>
                                    <button type="button" class="btn-vend-base limpar-btn" id="btnLimparVend">🧹 Limpar</button>
                                    <button type="button" class="btn-vend-base editar-btn" id="btnEditarVend">📝 Estornar</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <!-- COLUNA DA DIREITA: Histórico de Vendas Faturadas (O Caixa) -->
                <div class="coluna-direita-listagem">
                    <div class="card-tabela-vendas">
                        <div class="topo-tabela-acoes-prod">
                            <h3><svg class="svg-card-title-prod" viewBox="0 0 24 24" width="14" height="14" stroke="#171d14" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Histórico de Vendas (Faturamento)</h3>
                        </div>

                        <!-- Barra de pesquisa interna e recarregar embutidos dentro do card da tabela -->
                        <div class="linha-pesquisa-interna-vend">
                            <div class="wrapper-busca-tabela-vend">
                                <svg class="svg-busca-tabela-interna" viewBox="0 0 24 24" width="12" height="12"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" id="inputPesquisaGlobal" placeholder="Pesquisar histórico de vendas...">
                            </div>
                            <button type="button" class="btn-mini-tabela-topo" id="btnRecarregarVendas" title="Recarregar Caixa">
                                <svg class="svg-mini-topo" viewBox="0 0 24 24" width="12" height="12"><path d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                            </button>
                        </div>

                        <!-- Área de Rolagem Otimizada com Blindagem Inline Direta -->
                        <div class="wrapper-tabela-vendas-scroll">
                            <table class="tabela-dados-vendas" style="display: table !important; width: 100% !important; table-layout: fixed !important; border-collapse: collapse !important;">
                                <thead>
                                    <tr style="display: table-row !important;">
                                        <th style="width: 10%;">Cód</th>
                                        <th style="width: 30%;">Cliente</th>
                                        <th style="width: 15%;">Pagamento</th>
                                        <th style="width: 18%;">Valor Total</th>
                                        <th style="width: 17%;">Status</th>
                                        <th style="width: 10%; text-align: center;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="corpoTabelaVendas">
                                    <!-- Registro Simulando o Faturamento do Pedido #1 da Mariana -->
                                    <tr class="linha-selecionavel-vend" data-id="1" style="display: table-row !important;">
                                        <td>#1</td>
                                        <td><strong>Mariana Silva</strong></td>
                                        <td><span class="badge-pag b-pix">Pix</span></td>
                                        <td>R$ 85,00</td>
                                        <td><span class="badge-venda-status v-pago">Faturado</span></td>
                                        <td class="celula-acoes-tabela">
                                            <button type="button" class="btn-acao-linha edit" title="Ver Detalhes/Imprimir Cupom"><svg viewBox="0 0 24 24" width="12" height="12"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <!-- Fecha o wrapper-tabela-vendas-scroll -->

                        <!-- Rodapé limpo contendo a contagem total e fechamento bruto -->
                        <div class="rodape-paginacao-vendas">
                            <span class="info-contagem-itens">Mostrando 1 de 1 venda faturada hoje</span>
                        </div>

                    </div> <!-- Fecha o card-tabela-vendas -->
                </div> <!-- Fecha a coluna-direita-listagem -->

            </div> <!-- Fecha o grid-vendas-container -->
        </main>
    </div> <!-- Fecha o container-dashboard -->

    <!-- Vinculação do futuro motor JavaScript de Vendas -->
    <script src="../js/vendas.js"></script>
</body>
</html>

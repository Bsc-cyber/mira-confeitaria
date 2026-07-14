/* ==========================================================================
   MIRA CONFEITARIA - MOTOR DO PONTO DE VENDA (PDV)
   ========================================================================== */

document.addEventListener("DOMContentLoaded", function () {
    
    // Captura de Elementos da Interface
    const inputBuscaPedido = document.getElementById("inputBuscaPedido");
    const btnCarregar = document.getElementById("btnCarregarPedido");
    const corpoTabela = document.getElementById("corpoTabelaPdv");
    
    const labelCliente = document.querySelector(".titulo-secao-compras small");
    const labelTotalItens = document.getElementById("contagemItensDestaque");
    const labelSubtotal = document.getElementById("subtotalDestaque");
    const inputDesconto = document.getElementById("descontoInput");
    const labelTotalGeral = document.getElementById("totalFinalDisplay");
    
    const selectPagamento = document.getElementById("selectPagamento");
    const btnFinalizarVenda = document.getElementById("btnFinalizarVendaCompleto");
    const modal = document.getElementById("modalDetalhes");

    // Variáveis de Memória do Caixa
    let carrinho = [];
    let pedidosFinalizadosDisponiveis = []; // Armazena os pedidos prontos para faturamento
    let pedidoOrigemId = null; 
    let nomeClienteAtual = "CONSUMIDOR FINAL";

    // ==========================================================================
    // 1. CARREGAR E LISTAR OS PEDIDOS "FINALIZADOS" DIRETAMENTE NA TABELA
    // ==========================================================================
    function buscarPedidosAguardandoPagamento() {
        corpoTabela.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 50px;">Buscando pedidos finalizados na produção...</td></tr>';
        
        // Puxa todos os pedidos cadastrados no sistema
        fetch('buscar_todos_pedidos.php')
        .then(res => res.json())
        .then(retorno => {
            if (retorno.sucesso) {
                // Filtra para mostrar apenas os pedidos que a cozinha marcou como "Finalizado"
                pedidosFinalizadosDisponiveis = retorno.pedidos.filter(p => p.status === "Finalizado");
                
                // Popula o datalist de pesquisa para o topo (caso queira buscar por nome)
                const datalist = document.getElementById('listaPedidosProntos');
                if (datalist) {
                    datalist.innerHTML = '';
                    pedidosFinalizadosDisponiveis.forEach(ped => {
                        const opcao = document.createElement('option');
                        opcao.value = `${ped.cliente} (Pedido #${ped.id})`;
                        opcao.setAttribute('data-id-pedido', ped.id);
                        datalist.appendChild(opcao);
                    });
                }

                renderizarListaDeEspera();
            } else {
                corpoTabela.innerHTML = '<tr><td colspan="6" class="linha-vazia-texto">Erro ao carregar os dados do banco.</td></tr>';
            }
        })
        .catch(erro => {
            console.error("Erro ao listar pedidos:", erro);
            corpoTabela.innerHTML = '<tr><td colspan="6" class="linha-vazia-texto">Erro de conexão ao buscar os pedidos.</td></tr>';
        });
    }

    // Desenha a "tela inicial" do caixa com os pedidos prontos na tabela
    // Variáveis de Memória do Caixa (Adicione esta no topo do seu script se não tiver)
    let pedidosSelecionadosIds = []; // Guarda os IDs dos pedidos selecionados na fila

    // ==========================================================================
    // DESENHA A LISTA DE ESPERA COM SUPORTE A SELEÇÃO MÚLTIPLA
    // ==========================================================================
    function renderizarListaDeEspera() {
        corpoTabela.innerHTML = "";
        pedidosSelecionadosIds = []; // Reseta a seleção
        
        if (pedidosFinalizadosDisponiveis.length === 0) {
            corpoTabela.innerHTML = '<tr><td colspan="6" class="linha-vazia-texto" style="padding: 150px 15px !important;">🧁 Excelente! Todos os pedidos da cozinha já foram faturados e pagos.</td></tr>';
            atualizarFinanceiro(0, 0);
            return;
        }

        labelCliente.textContent = "AGUARDANDO PAGAMENTO NO CAIXA";

        pedidosFinalizadosDisponiveis.forEach(ped => {
            const dataEntFormatada = ped.data_entrega.split('-').reverse().join('/');
            const precoFormatado = parseFloat(ped.total).toFixed(2).replace('.', ',');

            const tr = document.createElement("tr");
            tr.style.cursor = "pointer";
            tr.className = "linha-pedido-fila";
            tr.setAttribute("data-id", ped.id);
            tr.setAttribute("data-cliente", ped.cliente);
            tr.title = "Clique para selecionar. Clique em 'Cobrar' para faturar os selecionados.";
            
            tr.innerHTML = `
                <td><strong>#${ped.id}</strong></td>
                <td><strong>👤 ${ped.cliente}</strong> <span style="font-size:0.7rem; color:#6b7280; margin-left: 10px;">📅 Entrega: ${dataEntFormatada}</span></td>
                <td>${ped.qtd_itens} item(ns)</td>
                <td>R$ ${precoFormatado}</td>
                <td><span style="background-color: #e6f7ed; color: #16a34a; padding: 2px 8px; border-radius: 12px; font-weight: bold; font-size: 0.65rem;">PRONTO</span></td>
                <td style="text-align:center;">
                    <button type="button" class="btn-abrir-pedido" data-id="${ped.id}" style="background:#172016; color:#fff; border:none; border-radius:4px; padding:4px 8px; font-size:10px; cursor:pointer; font-weight:bold;">💵 Cobrar</button>
                </td>
            `;

            // CLIQUE NA LINHA: Controla a Seleção Múltipla Inteligente
            tr.addEventListener("click", function (e) {
                // Se clicou direto no botão de cobrar, fatura imediatamente os selecionados
                if (e.target.classList.contains("btn-abrir-pedido")) {
                    e.stopPropagation();
                    
                    // Se não tiver nenhum selecionado, adiciona pelo menos este que foi clicado
                    if (pedidosSelecionadosIds.length === 0) {
                        pedidosSelecionadosIds.push(ped.id);
                    }
                    importarMultiplosPedidosNoCarrinho(pedidosSelecionadosIds);
                    return;
                }

                const clienteLinha = ped.cliente.trim().toLowerCase();

                // Validação de cliente: Se já tiver algum pedido selecionado, impede de selecionar outro cliente
                if (pedidosSelecionadosIds.length > 0) {
                    const primeiroSelecionado = pedidosFinalizadosDisponiveis.find(p => p.id == pedidosSelecionadosIds[0]);
                    if (primeiroSelecionado && primeiroSelecionado.cliente.trim().toLowerCase() !== clienteLinha) {
                        alert(`⚠️ Bloqueio de Segurança!\n\nVocê já selecionou um pedido de "${primeiroSelecionado.cliente}". Para faturar múltiplos pedidos juntos, eles precisam pertencer ao mesmo cliente.`);
                        return;
                    }
                }

                // Liga/Desliga a seleção
                tr.classList.toggle("pedido-selecionado");
                
                if (tr.classList.contains("pedido-selecionado")) {
                    pedidosSelecionadosIds.push(ped.id);
                } else {
                    pedidosSelecionadosIds = pedidosSelecionadosIds.filter(id => id != ped.id);
                }
            });

            corpoTabela.appendChild(tr);
        });

        atualizarFinanceiro(0, 0); // Mantém o caixa zerado até abrir
    }

    // ==========================================================================
    // 2. BUSCAR E UNIR OS ITENS DE MÚLTIPLOS PEDIDOS DO MESMO CLIENTE (LISTAGEM INDIVIDUAL)
    // ==========================================================================
    function importarMultiplosPedidosNoCarrinho(listaIds) {
        if (listaIds.length === 0) return;

        corpoTabela.innerHTML = `<tr><td colspan="6" style="text-align:center; padding: 50px;">Carregando e unificando ${listaIds.length} pedidos...</td></tr>`;

        const urlBusca = `buscar_detalhes_pedido.php`;
        const requisicoesCorretas = listaIds.map(id => 
            fetch(`${urlBusca}?id=${id}`).then(res => res.json())
        );

        Promise.all(requisicoesCorretas)
        .then(resultados => {
            carrinho = [];
            const idsOrigem = [];

            resultados.forEach(retorno => {
                if (retorno.sucesso) {
                    const pedido = retorno.dados;
                    nomeClienteAtual = pedido.cliente.toUpperCase();
                    idsOrigem.push(pedido.id);

                    pedido.itens.forEach(item => {
                        const qtd = parseInt(item.quantidade);
                        const precoTotal = parseFloat(item.preco_total);
                        const precoUnitario = precoTotal / qtd;

                        // AQUI ESTÁ A CORREÇÃO: Ele empurra o doce direto para o carrinho em uma linha nova, sem tentar somar ou agrupar com os outros!
                        carrinho.push({
                            codigo: item.id_item || pedido.id,
                            nome: item.produto,
                            qtd: qtd,
                            preco: precoUnitario,
                            subtotal: precoTotal
                        });
                    });
                }
            });

            // Une os IDs dos pedidos originais para o PHP dar baixa em todos de uma vez (Ex: "13, 14")
            pedidoOrigemId = idsOrigem.join(","); 
            labelCliente.textContent = `CLIENTE: ${nomeClienteAtual} (Faturando Pedidos: #${idsOrigem.join(' e #')})`;
            
            renderizarCarrinho();
        })
        .catch(err => {
            console.error(err);
            alert("Erro ao unificar detalhes dos pedidos no banco de dados.");
            buscarPedidosAguardandoPagamento();
        });
    }

    // ==========================================================================
    // 3. SEÇÃO DO CARRINHO (CHECKOUT)
    // ==========================================================================
    function renderizarCarrinho() {
        corpoTabela.innerHTML = "";
        
        let totalItens = 0;
        let subtotalCaixa = 0;

        carrinho.forEach((item, index) => {
            totalItens += item.qtd;
            subtotalCaixa += item.subtotal;

            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>#${item.codigo}</td>
                <td><strong>${item.nome}</strong></td>
                <td>${item.qtd}</td>
                <td>R$ ${item.preco.toFixed(2).replace('.', ',')}</td>
                <td><strong>R$ ${item.subtotal.toFixed(2).replace('.', ',')}</strong></td>
                <td style="text-align:center;">
                    <button type="button" class="btn-cancelar-checkout" style="background:none; border:none; cursor:pointer; font-size:12px;" title="Cancelar e voltar para a fila">↩️ Voltar</button>
                </td>
            `;

            // Se o usuário quiser desistir ou voltar para a lista sem finalizar, clica no botão voltar
            tr.querySelector(".btn-cancelar-checkout").addEventListener("click", (e) => {
                e.stopPropagation();
                carrinho = [];
                pedidoOrigemId = null;
                nomeClienteAtual = "CONSUMIDOR FINAL";
                buscarPedidosAguardandoPagamento();
            });

            corpoTabela.appendChild(tr);
        });

        atualizarFinanceiro(totalItens, subtotalCaixa);
    }

    let valorSubtotalReal = 0;
    
    function atualizarFinanceiro(totalItens = 0, subtotal = 0) {
        valorSubtotalReal = subtotal;
        labelTotalItens.textContent = totalItens;
        labelSubtotal.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
        recalcularTotalComDesconto();
    }

    function recalcularTotalComDesconto() {
        let desconto = parseFloat(inputDesconto.value) || 0;
        if (desconto > valorSubtotalReal) desconto = valorSubtotalReal; 
        
        const totalLiquido = valorSubtotalReal - desconto;
        labelTotalGeral.textContent = `R$ ${totalLiquido.toFixed(2).replace('.', ',')}`;
    }

    inputDesconto.addEventListener("input", recalcularTotalComDesconto);

    // ==========================================================================
    // 4. PESQUISA RÁPIDA NO CAMPO SUPERIOR
    // ==========================================================================
    if (btnCarregar) {
        btnCarregar.addEventListener("click", () => {
            const val = inputBuscaPedido.value.trim();
            if (!val) return;
            
            // Tenta achar o pedido no array pelo nome digitado
            const pedidoEncontrado = pedidosFinalizadosDisponiveis.find(p => p.cliente.toLowerCase().includes(val.toLowerCase()));
            if (pedidoEncontrado) {
                importarPedidoNoCarrinho(pedidoEncontrado.id);
            } else {
                alert("Nenhum pedido pronto encontrado para este cliente.");
            }
        });
    }

    // ==========================================================================
    // 5. FINALIZAÇÃO DA VENDA NO BANCO
    // ==========================================================================
    btnFinalizarVenda.addEventListener("click", function () {
        if (carrinho.length === 0) return alert("❌ Selecione e abra um pedido na lista abaixo primeiro!");

        const btnOriginal = this.innerHTML;
        this.innerHTML = "⏳ Processando...";
        this.disabled = true;

        let descontoNum = parseFloat(inputDesconto.value) || 0;
        let totalFinalNum = valorSubtotalReal - descontoNum;

        const pacoteVenda = {
            cliente: nomeClienteAtual,
            subtotal: valorSubtotalReal,
            desconto: descontoNum,
            total_liquido: totalFinalNum,
            forma_pagamento: selectPagamento.options[selectPagamento.selectedIndex].text,
            pedido_origem_id: pedidoOrigemId,
            itens: carrinho
        };

        fetch('../php/salvar_venda.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(pacoteVenda)
        })
        .then(res => res.json())
        .then(retorno => {
            if (retorno.sucesso) {
                // Prepara os dados de Data e Hora
                const dataHoje = new Date();
                const dataFormatada = dataHoje.toLocaleString('pt-BR');

                // 1. PREENCHE O CUPOM FÍSICO (HTML)
                document.getElementById("cupomNumero").textContent = retorno.id_venda;
                document.getElementById("cupomData").textContent = dataFormatada;
                document.getElementById("cupomCliente").textContent = nomeClienteAtual;
                
                const tbody = document.getElementById("cupomListaItens");
                tbody.innerHTML = "";
                
                // 2. PREPARA O TEXTO DO WHATSAPP AO MESMO TEMPO
                let textoZap = `*MIRA CONFEITARIA*\nRecibo #${retorno.id_venda}\nData: ${dataFormatada}\nCliente: ${nomeClienteAtual}\n\n*ITENS:*\n`;

                carrinho.forEach(item => {
                    const vlrUn = item.preco.toFixed(2).replace('.', ',');
                    const vlrTot = item.subtotal.toFixed(2).replace('.', ',');
                    
                    // Injeta no Cupom HTML
                    tbody.innerHTML += `
                        <tr>
                            <td>${item.qtd}x</td>
                            <td>${item.nome}</td>
                            <td>R$ ${vlrUn}</td>
                            <td>R$ ${vlrTot}</td>
                        </tr>
                    `;
                    // Injeta na mensagem de Texto (WhatsApp)
                    textoZap += `${item.qtd}x ${item.nome} (R$ ${vlrTot})\n`;
                });

                // Preenche Totais no Cupom HTML
                document.getElementById("cupomSubtotal").textContent = `R$ ${valorSubtotalReal.toFixed(2).replace('.', ',')}`;
                document.getElementById("cupomDesconto").textContent = `R$ ${descontoNum.toFixed(2).replace('.', ',')}`;
                document.getElementById("cupomTotalFinal").textContent = `R$ ${totalFinalNum.toFixed(2).replace('.', ',')}`;
                document.getElementById("cupomPagamento").textContent = selectPagamento.options[selectPagamento.selectedIndex].text;

                // Preenche Totais na Mensagem de WhatsApp
                textoZap += `\nSubtotal: R$ ${valorSubtotalReal.toFixed(2).replace('.', ',')}`;
                textoZap += `\nDesconto: R$ ${descontoNum.toFixed(2).replace('.', ',')}`;
                textoZap += `\n*TOTAL: R$ ${totalFinalNum.toFixed(2).replace('.', ',')}*`;
                textoZap += `\nPagamento: ${selectPagamento.options[selectPagamento.selectedIndex].text}`;
                textoZap += `\n\nObrigado pela preferência! 🧁`;

                // Guarda o texto do Zap na memória para o botão usar depois
                window.textoCupomGerado = textoZap;

                // Abre o Modal com o Recibo!
                modal.style.display = "flex"; 
                
                // Limpa o PDV por trás
                carrinho = [];
                pedidoOrigemId = null;
                nomeClienteAtual = "CONSUMIDOR FINAL";
                inputDesconto.value = "0.00";
                buscarPedidosAguardandoPagamento();
            } else {
                alert("❌ Erro ao fechar venda: " + retorno.erro);
            }
        })
        .catch(() => alert("❌ Erro de comunicação com o servidor."))
        .finally(() => {
            this.innerHTML = btnOriginal;
            this.disabled = false;
        });
    });

    // ==========================================================================
    // MÁGICA DOS 3 BOTÕES DO CUPOM FISCAL (IMPRIMIR, BAIXAR, WHATSAPP)
    // ==========================================================================
    
    // 1. Fechar o Modal
    document.getElementById("btnFecharModal").addEventListener("click", () => modal.style.display = "none");

    // 2. Botão Imprimir
    document.getElementById("btnImprimirCupom").addEventListener("click", () => {
        window.print(); // O CSS @media print que fizemos esconde o sistema e imprime só o cupom!
    });

    // 3. Botão Baixar (Gera um arquivo de texto com a nota)
    document.getElementById("btnBaixarCupom").addEventListener("click", () => {
        const numero = document.getElementById("cupomNumero").textContent;
        // Cria um "arquivo fantasma" contendo o texto gerado
        const blob = new Blob([window.textoCupomGerado], { type: "text/plain;charset=utf-8" });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = url;
        link.download = `Recibo_MiraConfeitaria_${numero}.txt`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // 4. Botão WhatsApp
    document.getElementById("btnZapCupom").addEventListener("click", () => {
        const numeroCliente = prompt("Digite o WhatsApp do cliente (com DDD)\n\nDeixe em branco e clique em 'OK' caso queira escolher o contato na sua lista do WhatsApp Web:");
        
        let linkWhatsApp = `https://api.whatsapp.com/send?text=${encodeURIComponent(window.textoCupomGerado)}`;
        
        if (numeroCliente) {
            // Remove parênteses e traços caso você tenha digitado
            const numeroLimpo = numeroCliente.replace(/\D/g, ''); 
            linkWhatsApp = `https://api.whatsapp.com/send?phone=55${numeroLimpo}&text=${encodeURIComponent(window.textoCupomGerado)}`;
        }
        
        window.open(linkWhatsApp, '_blank'); // Abre nova guia já com a mensagem pronta
    });

    // Inicializa a lista de pedidos pendentes
    buscarPedidosAguardandoPagamento();
});
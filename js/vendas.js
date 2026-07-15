/* ==========================================================================
   MIRA CONFEITARIA - MOTOR DO PONTO DE VENDA (FINALIZADOR DE PEDIDOS E CUPOM)
   ========================================================================== */

document.addEventListener("DOMContentLoaded", function () {
    
    // 1. CAPTURA DE ELEMENTOS
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
    let pedidosFinalizadosDisponiveis = []; 
    let pedidosSelecionadosIds = []; 
    let pedidoOrigemId = null; 
    let nomeClienteAtual = "CONSUMIDOR FINAL";
    let valorSubtotalReal = 0;

    // ==========================================================================
    // 2. LISTA DE ESPERA (PEDIDOS PRONTOS DA COZINHA)
    // ==========================================================================
    function buscarPedidosAguardandoPagamento() {
        corpoTabela.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 50px;">Buscando pedidos finalizados na produção...</td></tr>';
        
        fetch('buscar_todos_pedidos.php')
        .then(res => res.json())
        .then(retorno => {
            if (retorno.sucesso) {
                pedidosFinalizadosDisponiveis = retorno.pedidos.filter(p => p.status === "Finalizado");
                
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

    function renderizarListaDeEspera() {
        corpoTabela.innerHTML = "";
        pedidosSelecionadosIds = []; 
        
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

            tr.addEventListener("click", function (e) {
                if (e.target.classList.contains("btn-abrir-pedido")) {
                    e.stopPropagation();
                    if (pedidosSelecionadosIds.length === 0) {
                        pedidosSelecionadosIds.push(ped.id);
                    }
                    importarMultiplosPedidosNoCarrinho(pedidosSelecionadosIds);
                    return;
                }

                const clienteLinha = ped.cliente.trim().toLowerCase();
                if (pedidosSelecionadosIds.length > 0) {
                    const primeiroSelecionado = pedidosFinalizadosDisponiveis.find(p => p.id == pedidosSelecionadosIds[0]);
                    if (primeiroSelecionado && primeiroSelecionado.cliente.trim().toLowerCase() !== clienteLinha) {
                        alert(`⚠️ Bloqueio de Segurança!\n\nVocê já selecionou um pedido de "${primeiroSelecionado.cliente}". Selecione apenas pedidos do mesmo cliente.`);
                        return;
                    }
                }

                tr.classList.toggle("pedido-selecionado");
                if (tr.classList.contains("pedido-selecionado")) {
                    pedidosSelecionadosIds.push(ped.id);
                } else {
                    pedidosSelecionadosIds = pedidosSelecionadosIds.filter(id => id != ped.id);
                }
            });

            corpoTabela.appendChild(tr);
        });

        atualizarFinanceiro(0, 0); 
    }

    // ==========================================================================
    // 3. UNIFICAR E ABRIR PEDIDOS NO CARRINHO
    // ==========================================================================
    function importarMultiplosPedidosNoCarrinho(listaIds) {
        if (listaIds.length === 0) return;

        corpoTabela.innerHTML = `<tr><td colspan="6" style="text-align:center; padding: 50px;">Carregando e unificando ${listaIds.length} pedido(s)...</td></tr>`;

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
    // 4. RENDERIZAÇÃO DO CARRINHO (CHECKOUT) E FINANCEIRO
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

    if(inputDesconto) inputDesconto.addEventListener("input", recalcularTotalComDesconto);

    if (btnCarregar && inputBuscaPedido) {
        btnCarregar.addEventListener("click", () => {
            const val = inputBuscaPedido.value.trim();
            if (!val) return;
            const pedidoEncontrado = pedidosFinalizadosDisponiveis.find(p => p.cliente.toLowerCase().includes(val.toLowerCase()));
            if (pedidoEncontrado) {
                importarMultiplosPedidosNoCarrinho([pedidoEncontrado.id]);
            } else {
                alert("Nenhum pedido pronto encontrado para este cliente.");
            }
        });
        inputBuscaPedido.addEventListener("keypress", (e) => { if (e.key === "Enter") btnCarregar.click(); });
    }

    // ==========================================================================
    // 5. FINALIZAÇÃO DA VENDA NO BANCO E EMISSÃO DO CUPOM
    // ==========================================================================
    if (btnFinalizarVenda) {
        btnFinalizarVenda.addEventListener("click", function () {
            
            // PROTEÇÃO: Se a imagem R$ 0,00 estiver zerada, ele dá este aviso!
            if (carrinho.length === 0) return alert("❌ Selecione e abra um pedido na lista ao lado primeiro!");

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
                    const dataHoje = new Date();
                    const dataFormatada = dataHoje.toLocaleString('pt-BR');

                    // 1. PREENCHE O CUPOM FÍSICO
                    const elmCupomNum = document.getElementById("cupomNumero");
                    if(elmCupomNum) elmCupomNum.textContent = retorno.id_venda;
                    
                    const elmCupomData = document.getElementById("cupomData");
                    if(elmCupomData) elmCupomData.textContent = dataFormatada;
                    
                    const elmCupomCliente = document.getElementById("cupomCliente");
                    if(elmCupomCliente) elmCupomCliente.textContent = nomeClienteAtual;
                    
                    const tbody = document.getElementById("cupomListaItens");
                    if(tbody) {
                        tbody.innerHTML = "";
                        
                        // 2. PREPARA O TEXTO DO WHATSAPP
                        let textoZap = `*MIRA CONFEITARIA*\nRecibo #${retorno.id_venda}\nData: ${dataFormatada}\nCliente: ${nomeClienteAtual}\n\n*ITENS:*\n`;

                        carrinho.forEach(item => {
                            const vlrUn = item.preco.toFixed(2).replace('.', ',');
                            const vlrTot = item.subtotal.toFixed(2).replace('.', ',');
                            
                            tbody.innerHTML += `
                                <tr>
                                    <td>${item.qtd}x</td>
                                    <td>${item.nome}</td>
                                    <td>R$ ${vlrUn}</td>
                                    <td>R$ ${vlrTot}</td>
                                </tr>
                            `;
                            textoZap += `${item.qtd}x ${item.nome} (R$ ${vlrTot})\n`;
                        });

                        const elmSub = document.getElementById("cupomSubtotal");
                        if(elmSub) elmSub.textContent = `R$ ${valorSubtotalReal.toFixed(2).replace('.', ',')}`;
                        
                        const elmDesc = document.getElementById("cupomDesconto");
                        if(elmDesc) elmDesc.textContent = `R$ ${descontoNum.toFixed(2).replace('.', ',')}`;
                        
                        const elmTot = document.getElementById("cupomTotalFinal");
                        if(elmTot) elmTot.textContent = `R$ ${totalFinalNum.toFixed(2).replace('.', ',')}`;
                        
                        const elmPag = document.getElementById("cupomPagamento");
                        if(elmPag) elmPag.textContent = selectPagamento.options[selectPagamento.selectedIndex].text;

                        textoZap += `\nSubtotal: R$ ${valorSubtotalReal.toFixed(2).replace('.', ',')}`;
                        textoZap += `\nDesconto: R$ ${descontoNum.toFixed(2).replace('.', ',')}`;
                        textoZap += `\n*TOTAL: R$ ${totalFinalNum.toFixed(2).replace('.', ',')}*`;
                        textoZap += `\nPagamento: ${selectPagamento.options[selectPagamento.selectedIndex].text}`;
                        textoZap += `\n\nObrigado pela preferência! 🧁`;

                        window.textoCupomGerado = textoZap;
                    }

                    if (modal) modal.style.display = "flex"; 
                    
                    carrinho = [];
                    pedidoOrigemId = null;
                    nomeClienteAtual = "CONSUMIDOR FINAL";
                    inputDesconto.value = "0.00";
                    buscarPedidosAguardandoPagamento();
                } else {
                    alert("❌ Erro ao fechar venda: " + retorno.erro);
                }
            })
            .catch((e) => {
                console.error("Erro no Fetch:", e);
                alert("❌ Erro de comunicação com o servidor.");
            })
            .finally(() => {
                this.innerHTML = btnOriginal;
                this.disabled = false;
            });
        });
    }

    // ==========================================================================
    // 6. BOTÕES DO CUPOM FISCAL E FECHAR
    // ==========================================================================
    const btnFecharModal = document.getElementById("btnFecharModal");
    if (btnFecharModal) btnFecharModal.addEventListener("click", () => modal.style.display = "none");

    const btnImprimirCupom = document.getElementById("btnImprimirCupom");
    if (btnImprimirCupom) {
        btnImprimirCupom.addEventListener("click", () => window.print());
    }

    const btnBaixarCupom = document.getElementById("btnBaixarCupom");
    if (btnBaixarCupom) {
        btnBaixarCupom.addEventListener("click", () => {
            const numero = document.getElementById("cupomNumero").textContent;
            const blob = new Blob([window.textoCupomGerado], { type: "text/plain;charset=utf-8" });
            const url = URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = url;
            link.download = `Recibo_MiraConfeitaria_${numero}.txt`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }

    const btnZapCupom = document.getElementById("btnZapCupom");
    if (btnZapCupom) {
        btnZapCupom.addEventListener("click", () => {
            const numeroCliente = prompt("Digite o WhatsApp do cliente (com DDD)\n\nDeixe em branco e clique em 'OK' caso queira escolher o contato na sua lista do WhatsApp Web:");
            
            let linkWhatsApp = `https://api.whatsapp.com/send?text=${encodeURIComponent(window.textoCupomGerado)}`;
            
            if (numeroCliente) {
                const numeroLimpo = numeroCliente.replace(/\D/g, ''); 
                linkWhatsApp = `https://api.whatsapp.com/send?phone=55${numeroLimpo}&text=${encodeURIComponent(window.textoCupomGerado)}`;
            }
            
            window.open(linkWhatsApp, '_blank'); 
        });
    }

    // ==========================================================================
    // FORÇA BRUTA: FUNÇÃO DIRETA DE FINALIZAÇÃO E ABERTURA DO CUPOM
    // ==========================================================================
    window.abrirCupomDireto = function() {
        // 1. Verifica se tem itens
        if (carrinho.length === 0) {
            alert("⚠️ O CARRINHO ESTÁ VAZIO! Selecione um pedido na fila primeiro.");
            return;
        }

        const btn = document.getElementById("btnFinalizarVendaCompleto");
        const textoOriginal = btn.innerHTML;
        btn.innerHTML = "⏳ Emitindo Cupom...";
        btn.disabled = true;

        // 2. Coleta os valores da tela
        const inputDesconto = document.getElementById("descontoInput");
        const selectPagamento = document.getElementById("selectPagamento");
        const modalCupom = document.getElementById("modalDetalhes");

        let descontoNum = inputDesconto ? parseFloat(inputDesconto.value) || 0 : 0;
        let totalFinalNum = valorSubtotalReal - descontoNum;

        const pacoteVenda = {
            cliente: nomeClienteAtual,
            subtotal: valorSubtotalReal,
            desconto: descontoNum,
            total_liquido: totalFinalNum,
            forma_pagamento: selectPagamento ? selectPagamento.options[selectPagamento.selectedIndex].text : "Dinheiro",
            pedido_origem_id: pedidoOrigemId,
            itens: carrinho
        };

        // 3. Envia para o Banco de Dados
        fetch('../php/salvar_venda.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(pacoteVenda)
        })
        .then(res => res.text()) // Lê como texto para pescar qualquer erro escondido do PHP
        .then(textoResposta => {
            btn.innerHTML = textoOriginal;
            btn.disabled = false;

            try {
                const retorno = JSON.parse(textoResposta);
                
                if (retorno.sucesso) {
                    
                    // Verifica se o HTML do Modal existe na página
                    if (!modalCupom) {
                        alert("✅ Venda salva no banco! MAS o 'modalDetalhes' (desenho do cupom) não foi encontrado no HTML para ser aberto.");
                        return;
                    }

                    const dataHoje = new Date();
                    const dataFormatada = dataHoje.toLocaleString('pt-BR');

                    // Preenche os dados físicos do Cupom
                    document.getElementById("cupomNumero").textContent = retorno.id_venda;
                    document.getElementById("cupomData").textContent = dataFormatada;
                    document.getElementById("cupomCliente").textContent = nomeClienteAtual;
                    
                    const tbody = document.getElementById("cupomListaItens");
                    tbody.innerHTML = "";
                    
                    let textoZap = `*MIRA CONFEITARIA*\nRecibo #${retorno.id_venda}\nData: ${dataFormatada}\nCliente: ${nomeClienteAtual}\n\n*ITENS:*\n`;

                    carrinho.forEach(item => {
                        const vlrUn = item.preco.toFixed(2).replace('.', ',');
                        const vlrTot = item.subtotal.toFixed(2).replace('.', ',');
                        
                        tbody.innerHTML += `
                            <tr>
                                <td>${item.qtd}x</td>
                                <td>${item.nome}</td>
                                <td>R$ ${vlrUn}</td>
                                <td>R$ ${vlrTot}</td>
                            </tr>
                        `;
                        textoZap += `${item.qtd}x ${item.nome} (R$ ${vlrTot})\n`;
                    });

                    document.getElementById("cupomSubtotal").textContent = `R$ ${valorSubtotalReal.toFixed(2).replace('.', ',')}`;
                    document.getElementById("cupomDesconto").textContent = `R$ ${descontoNum.toFixed(2).replace('.', ',')}`;
                    document.getElementById("cupomTotalFinal").textContent = `R$ ${totalFinalNum.toFixed(2).replace('.', ',')}`;
                    document.getElementById("cupomPagamento").textContent = pacoteVenda.forma_pagamento;

                    textoZap += `\nSubtotal: R$ ${valorSubtotalReal.toFixed(2).replace('.', ',')}`;
                    textoZap += `\nDesconto: R$ ${descontoNum.toFixed(2).replace('.', ',')}`;
                    textoZap += `\n*TOTAL: R$ ${totalFinalNum.toFixed(2).replace('.', ',')}*`;
                    textoZap += `\nPagamento: ${pacoteVenda.forma_pagamento}`;
                    textoZap += `\n\nObrigado pela preferência! 🧁`;

                    window.textoCupomGerado = textoZap;

                    // A MÁGICA: Abre o Modal!
                    modalCupom.style.display = "flex"; 
                    
                    // Limpa a tela de vendas
                    carrinho = [];
                    pedidoOrigemId = null;
                    nomeClienteAtual = "CONSUMIDOR FINAL";
                    if(inputDesconto) inputDesconto.value = "0.00";
                    buscarPedidosAguardandoPagamento();

                } else {
                    alert("❌ Erro ao salvar no banco de dados:\n" + retorno.erro);
                }
            } catch (e) {
                alert("❌ ERRO CRÍTICO NO ARQUIVO PHP:\n\nO arquivo salvar_venda.php está com algum erro e não conseguiu processar a venda. Resposta do servidor:\n\n" + textoResposta.substring(0, 250));
            }
        })
        .catch(erro => {
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
            alert("❌ Falha de comunicação com a internet ou com o servidor PHP.");
        });
    };

    // Inicializa a lista assim que o PDV abre
    buscarPedidosAguardandoPagamento();
});
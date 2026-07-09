/* ==========================================================================
   MIRA CONFEITARIA - SISTEMA GESTOR DE PEDIDOS E CARRINHO (ATUALIZADO)
   ========================================================================== */

document.addEventListener("DOMContentLoaded", function () {
    
    // 1. CAPTURA DOS ELEMENTOS DA TELA
    const selectProduto = document.getElementById("selectProduto");
    const inputQuantidade = document.getElementById("inputQuantidade");
    const precoDisplay = document.getElementById("precoDisplay");
    const btnAdicionar = document.getElementById("btnAdicionarCarrinho");
    
    const corpoTabela = document.getElementById("corpoTabelaCarrinho");
    const totalDisplay = document.getElementById("totalPedidoDisplay");
    const btnLimpar = document.getElementById("btnLimparCarrinho");
    
    const btnGerar = document.getElementById("btnGerarPedido");
    const containerProducao = document.getElementById("containerCardsProducao");
    const estadoVazio = document.getElementById("estadoVazioId");
    
    const countProd = document.getElementById("countProd");
    const countPend = document.getElementById("countPend");
    
    // Variáveis Globais de Memória
    let itensCarrinho = [];
    let totalGeral = 0;

    // ==========================================================================
    // CARREGAMENTO AUTOMÁTICO E FILTRO DOS PEDIDOS
    // ==========================================================================
    function carregarPedidosDoBanco(filtroData = '', filtroStatus = '', filtroBusca = '') {
        // Limpa os cards antigos da tela antes de desenhar os novos filtrados
        document.querySelectorAll('.card-pedido-producao-ativo').forEach(card => card.remove());

        // Monta a rota conversando com o PHP passando os filtros
        const url = `buscar_todos_pedidos.php?data=${filtroData}&status=${filtroStatus}&busca=${filtroBusca}`;

        fetch(url)
        .then(res => res.json())
        .then(retorno => {
            if (retorno.sucesso) {
                
                // 👇 1. CRIA OS CONTADORES ZERADOS PARA CADA BUSCA 👇
                let contProducao = 0;
                let contPendente = 0;
                let contFinalizado = 0;
                let contAtrasado = 0;
                
                // Pega a data de hoje para calcular os atrasos
                const hoje = new Date();
                hoje.setHours(0, 0, 0, 0);

                if (retorno.pedidos.length > 0) {
                    // Esconde a div de "Vazio"
                    if (estadoVazio) { estadoVazio.style.display = "none"; }

                    retorno.pedidos.forEach(pedido => {
                        
                        // 👇 2. CONTA CADA STATUS ENQUANTO PARTE PARA O DESENHO 👇
                        if (pedido.status === "Em Produção") contProducao++;
                        if (pedido.status === "Pendente") contPendente++;
                        if (pedido.status === "Finalizado") contFinalizado++; 
                        
                        // Lógica inteligente de atraso: data passou e não está Finalizado
                        const dataEntrega = new Date(pedido.data_entrega + "T00:00:00");
                        if (dataEntrega < hoje && pedido.status !== "Finalizado") {
                            contAtrasado++;
                        }
                        // ---------------------------------------------------

                        const dataPedFormatada = pedido.data_pedido.split('-').reverse().join('/');
                        const dataEntFormatada = pedido.data_entrega.split('-').reverse().join('/');
                        
                        const novoCardProd = document.createElement("div");
                        novoCardProd.className = "card-pedido-producao-ativo"; 
                        const classeBadge = (pedido.status === "Pendente") ? "pendente" : "producao";
                        
                        novoCardProd.innerHTML = `
                            <div class="info-card-prod">
                                <h4><svg style="width:12px; height:12px; stroke:#171d14; fill:none; stroke-width:2; vertical-align:middle; margin-right:4px;" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Cliente: ${pedido.cliente}</h4>
                                <p style="margin-top: 4px;"><strong>📦 Pedido #${pedido.id}</strong> • 🎂 Lote: ${pedido.qtd_itens} doce(s)</p>
                                
                                <p style="margin-top: 2px; font-size: 0.8rem; color: #4b5563;">📅 Pedido: ${dataPedFormatada} | 🚚 Entrega: <strong style="color: #b91c1c;">${dataEntFormatada}</strong></p>
                                
                                <p style="margin-top: 2px;">💰 Líquido: R$ ${parseFloat(pedido.total).toFixed(2).replace('.', ',')}</p>
                                
                                <button type="button" class="btn-ver-detalhes" data-id="${pedido.id}">👁️ Ver Detalhes</button>
                            </div>
                            <span class="status-badge-dinamica ${classeBadge}" id="badge-pedido-${pedido.id}">${pedido.status}</span>
                        `;
                        containerProducao.appendChild(novoCardProd);
                    });
                } else {
                    // Se nenhum pedido bater com o filtro, mostra a tela vazia
                    if (estadoVazio) { estadoVazio.style.display = "flex"; } 
                }

                // 👇 3. ATUALIZA AS SUAS CAIXINHAS DO TOPO NA TELA COM OS SPERPANELS 👇
                const elProd = document.getElementById("countProd");
                const elPend = document.getElementById("countPend");
                const elFin  = document.getElementById("countFin");
                const elAtras= document.getElementById("countAtr");

                if(elProd) elProd.textContent = contProducao;
                if(elPend) elPend.textContent = contPendente;
                if(elFin)  elFin.textContent  = contFinalizado;
                if(elAtras) elAtras.textContent = contAtrasado;
            }
        })
        .catch(erro => console.error("Erro ao puxar pedidos:", erro));
}

    // Chama a função vazia ao abrir a tela (para carregar todos os pedidos)
    carregarPedidosDoBanco();

    // ESCUTA O CLIQUE DO BOTÃO DE FILTRAR
    const btnFiltrar = document.getElementById("btnFiltrar");
    if(btnFiltrar) {
        btnFiltrar.addEventListener("click", function() {
            // Pega os valores preenchidos na barrinha
            const dataVal = document.getElementById("filtroData").value;
            const statusVal = document.getElementById("filtroStatus").value;
            const buscaVal = document.getElementById("filtroBusca").value;
            
            // Refaz a busca aplicando as regras
            carregarPedidosDoBanco(dataVal, statusVal, buscaVal);
        });
    }
    // ==========================================================================
    
    /* ==========================================================================
       PARTE 1: CÁLCULO DE PREÇO EM TEMPO REAL (CORRIGIDO PARA DATALIST)
       ========================================================================== */
    function atualizarPrecoDisplay() {
        const valProduto = selectProduto.value;
        const datalist = document.getElementById("listaProdutosSugestoes");
        
        if (!datalist) return; // Evita erro se a lista não existir

        // Procura na datalist ignorando letras maiúsculas/minúsculas
        const opcaoSelecionada = Array.from(datalist.options).find(
            opt => opt.value.trim().toLowerCase() === valProduto.trim().toLowerCase()
        );
        
        const precoUnitario = opcaoSelecionada ? parseFloat(opcaoSelecionada.getAttribute("data-preco")) : 0;
        const qtd = parseInt(inputQuantidade.value) || 1;
        
        if (precoUnitario > 0) {
            const subtotal = precoUnitario * qtd;
            precoDisplay.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
        } else {
            precoDisplay.textContent = "R$ 0,00";
        }
    }
    
    // Dispara a função sempre que o usuário digitar uma letra ou mudar a quantidade
    selectProduto.addEventListener("input", atualizarPrecoDisplay);
    inputQuantidade.addEventListener("input", atualizarPrecoDisplay);

    /* ==========================================================================
       PARTE 2: INSERÇÃO E EXCLUSÃO NA TABELA DA ESQUERDA
       ========================================================================== */
    function atualizarTabelaCarrinho() {
        corpoTabela.innerHTML = ""; 
        totalGeral = 0; 

        itensCarrinho.forEach((item, index) => {
            const novaLinha = document.createElement("tr");
            novaLinha.innerHTML = `
                <td>${item.produtoNome}</td>
                <td>${item.sabor}</td>
                <td>${item.tamanho.split(' ')[0]}</td>
                <td>${item.qtd}</td>
                <td>R$ ${item.precoTotalItem.toFixed(2).replace('.', ',')}</td>
                <td style="text-align: center;">
                    <button type="button" class="btn-remover-item" data-index="${index}" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:12px; font-weight:bold;" title="Remover item">❌</button>
                </td>
            `;
            corpoTabela.appendChild(novaLinha);
            totalGeral += item.precoTotalItem;
        });

        totalDisplay.textContent = `R$ ${totalGeral.toFixed(2).replace('.', ',')}`;
    }

    // EVENTO: Clicar no botão verde "Adicionar ao Carrinho"
    btnAdicionar.addEventListener("click", function () {
        const produtoNome = selectProduto.value; 
        const sabor = document.getElementById("selectSabor").value; 
        const tamanho = document.getElementById("selectTamanho").value; 
        const qtd = parseInt(inputQuantidade.value) || 1; 
        const cliente = document.getElementById("selectCliente").value; 
        
        if (!cliente) { return alert("Por favor, selecione um Cliente primeiro!"); }
        if (!produtoNome || !sabor || !tamanho) { return alert("Preencha Produto, Sabor e Tamanho!"); }
        
        const datalist = document.getElementById("listaProdutosSugestoes");
        const opcaoSelecionada = Array.from(datalist.options).find(
            opt => opt.value.trim().toLowerCase() === produtoNome.trim().toLowerCase()
        );

        if (!opcaoSelecionada) {
            return alert("Produto não encontrado! Selecione uma opção válida da lista.");
        }
        
        const precoUnit = parseFloat(opcaoSelecionada.getAttribute("data-preco"));
        const precoTotalItem = precoUnit * qtd;
        
        itensCarrinho.push({ produtoNome: opcaoSelecionada.value, sabor, tamanho, qtd, precoTotalItem });
        atualizarTabelaCarrinho();
    });

    // EVENTO: Clicar no ❌ da tabela para excluir
    corpoTabela.addEventListener("click", function(evento) {
        if (evento.target.closest('.btn-remover-item')) {
            const index = evento.target.closest('.btn-remover-item').getAttribute('data-index');
            itensCarrinho.splice(index, 1);
            atualizarTabelaCarrinho();
        }
    });

    /* ==========================================================================
       PARTE 3: BOTÕES DE RODAPÉ E INTEGRAÇÃO COM BANCO DE DADOS (PHP)
       ========================================================================== */
    btnLimpar.addEventListener("click", function () {
        if(confirm("Tem certeza que deseja limpar todo o carrinho?")) {
            itensCarrinho = []; 
            atualizarTabelaCarrinho(); 
            selectProduto.value = ""; 
            precoDisplay.textContent = "R$ 0,00";
        }
    });

    // EVENTO: Despachar o pedido final para o servidor e gerar o card
    btnGerar.addEventListener("click", function () {
        const clienteName = document.getElementById("selectCliente").value;
        const statusPed = document.getElementById("statusInicial").value; 
        const dataPed = document.getElementById("dataPedido").value;
        const dataEnt = document.getElementById("dataEntrega").value;
        
        if (itensCarrinho.length === 0) { return alert("Adicione doces ao carrinho primeiro!"); }
        if (!clienteName) { return alert("Selecione um cliente no topo da página."); }

        // Troca o visual do botão para carregando
        const textoOriginalBotao = btnGerar.innerHTML;
        btnGerar.innerHTML = "⏳ Salvando...";
        btnGerar.disabled = true;
        
        // PACOTE DE DADOS PARA O BANCO
        const pacoteDados = {
            cliente: clienteName,
            data_pedido: dataPed,
            data_entrega: dataEnt,
            status: statusPed,
            total: totalGeral,
            itens: itensCarrinho
        };

        // ENVIO SILENCIOSO PARA O PHP (FETCH)
        fetch('../php/salvar_pedido.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },    
            body: JSON.stringify(pacoteDados)
        })

        .then(resposta => resposta.json())
        .then(retorno => {
            if (retorno.sucesso) {
                // Desenha a tela se salvou com sucesso
                if (estadoVazio) { estadoVazio.style.display = "none"; }
                
                // Cria o visual do card na tela
                const novoCardProd = document.createElement("div");
                novoCardProd.className = "card-pedido-producao-ativo"; 
                novoCardProd.setAttribute("data-id", pedido.id); // Guarda o ID na borda do card
                const classeBadge = (statusPed === "Pendente") ? "pendente" : "producao";
                
                // Pega as datas direto do pacote que foi enviado ao banco e converte
                const dataPedForm = pacoteDados.data_pedido.split('-').reverse().join('/');
                const dataEntForm = pacoteDados.data_entrega.split('-').reverse().join('/');

                novoCardProd.innerHTML = `
                    <div class="info-card-prod">
                        <h4><svg style="width:12px; height:12px; stroke:#171d14; fill:none; stroke-width:2; vertical-align:middle; margin-right:4px;" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Cliente: ${clienteName}</h4>
                        <p style="margin-top: 4px;"><strong>📦 Pedido #${retorno.id_pedido}</strong> • 🎂 Lote: ${itensCarrinho.length} doce(s)</p>
                        
                        <p style="margin-top: 2px; font-size: 0.8rem; color: #4b5563;">📅 Pedido: ${dataPedForm} | 🚚 Entrega: <strong style="color: #b91c1c;">${dataEntForm}</strong></p>

                        <p style="margin-top: 2px;">💰 Líquido: R$ ${totalGeral.toFixed(2).replace('.', ',')}</p>
                        
                        <button type="button" class="btn-ver-detalhes" data-id="${retorno.id_pedido}">👁️ Ver Detalhes</button>
                    </div>
                    <span class="status-badge-dinamica ${classeBadge}" id="badge-pedido-${retorno.id_pedido}">${statusPed}</span>
                `;
                // 👆 ------------------------------------------------ 👆
                
                containerProducao.appendChild(novoCardProd);
                
                if (statusPed === "Pendente") {
                    countPend.textContent = parseInt(countPend.textContent) + 1;
                } else if (statusPed === "Em Produção") {
                    countProd.textContent = parseInt(countProd.textContent) + 1;
                }
                
                // Limpeza final do carrinho esquerdo
                itensCarrinho = []; 
                atualizarTabelaCarrinho(); 
                document.getElementById("selectCliente").value = "";
                selectProduto.value = "";
                precoDisplay.textContent = "R$ 0,00";
                
                alert("Sucesso! Pedido salvo no banco e despachado.");
            } else {
                alert("Erro do Banco de Dados: " + retorno.erro);
            }
        })
        .catch(erro => {
            alert("Erro de comunicação com o arquivo PHP.");
            console.error(erro);
        })
    .finally(() => {
            btnGerar.innerHTML = textoOriginalBotao;
            btnGerar.disabled = false;
        });
    });

    /* ==========================================================================
       PARTE 4: LÓGICA DO MODAL DIRETAMENTE LIGADO AO BANCO DE DADOS
       ========================================================================== */
    const modal = document.getElementById("modalDetalhes");
    let idPedidoAtualModal = null; 

    // Escuta os cliques no botão "Ver Detalhes"
    containerProducao.addEventListener("click", function(evento) {
        if (evento.target.classList.contains("btn-ver-detalhes")) {
            const id = evento.target.getAttribute("data-id");
            idPedidoAtualModal = id;
            
            const btnClicado = evento.target;
            const textoOriginal = btnClicado.innerHTML;
            btnClicado.innerHTML = "⏳ Buscando..."; 
            
            // BUSCA OS DADOS REAIS NO BANCO
            fetch(`buscar_detalhes_pedido.php?id=${id}`)
            .then(res => res.json())
            .then(retorno => {
                btnClicado.innerHTML = textoOriginal; 
                
                if (retorno.sucesso) {
                    const pedido = retorno.dados;

                    document.getElementById("modalTitulo").textContent = `📦 Pedido #${pedido.id}`;
                    document.getElementById("modalCliente").textContent = pedido.cliente;
                    document.getElementById("modalTotal").textContent = `R$ ${parseFloat(pedido.total).toFixed(2).replace('.', ',')}`;
                    document.getElementById("modalSelectStatus").value = pedido.status;

                    const listaUl = document.getElementById("modalListaItens");
                    listaUl.innerHTML = "";
                    pedido.itens.forEach(item => {
                        listaUl.innerHTML += `<li><span>${item.quantidade}x ${item.produto}</span> <strong>R$ ${parseFloat(item.preco_total).toFixed(2).replace('.', ',')}</strong></li>`;
                    });

                    modal.className = "modal-ativo"; 
                } else {
                    alert("Erro do banco: " + retorno.erro);
                }
            })
            .catch(erro => {
                btnClicado.innerHTML = textoOriginal;
                alert("Erro de comunicação com o buscar_detalhes_pedido.php");
            });
        }
    });

    // Fechar o modal no 'X'
    document.getElementById("btnFecharModal").addEventListener("click", () => modal.className = "modal-oculto");

    // SALVAR O NOVO STATUS NO BANCO DE DADOS
    document.getElementById("btnSalvarStatusModal").addEventListener("click", function() {
        const novoStatus = document.getElementById("modalSelectStatus").value;
        const btnSalvar = this;

        btnSalvar.innerHTML = "⏳ Salvando...";
        btnSalvar.disabled = true;

        fetch('atualizar_status_pedido.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: idPedidoAtualModal, status: novoStatus })
        })
        .then(res => res.json())
        .then(retorno => {
            if (retorno.sucesso) {
                const badgeFora = document.getElementById(`badge-pedido-${idPedidoAtualModal}`);
                if(badgeFora) {
                    badgeFora.textContent = novoStatus;
                    badgeFora.className = `status-badge-dinamica ${novoStatus === "Pendente" ? "pendente" : "producao"}`;
                }
                modal.className = "modal-oculto"; 
            } else {
                alert("Erro ao atualizar: " + retorno.erro);
            }
        })
        .catch(erro => alert("Erro ao salvar status no PHP."))
        .finally(() => {
            btnSalvar.innerHTML = "💾 Salvar Alteração";
            btnSalvar.disabled = false;
        });
    });

    // AÇÃO DE CANCELAR O PEDIDO
    document.getElementById("btnCancelarPedidoModal").addEventListener("click", function() {
        // A caixinha de pergunta nativa do navegador
        const confirmacao = confirm(`Tem certeza que deseja CANCELAR e EXCLUIR o Pedido #${idPedidoAtualModal}? Essa ação não pode ser desfeita.`);
        
        if (confirmacao) {
            const btnCancelar = this;
            const textoOriginal = btnCancelar.innerHTML;
            btnCancelar.innerHTML = "⏳ Excluindo...";
            btnCancelar.disabled = true;

            // Manda o ID para o novo arquivo PHP apagar
            fetch(`excluir_pedido.php?id=${idPedidoAtualModal}`, { method: 'GET' })
            .then(res => res.json())
            .then(retorno => {
                if (retorno.sucesso) {
                    // Encontra o card lá fora pelo ID da badge e remove ele da tela
                    const cardParaRemover = document.getElementById(`badge-pedido-${idPedidoAtualModal}`).closest('.card-pedido-producao-ativo');
                    if (cardParaRemover) {
                        cardParaRemover.remove();
                    }
                    
                    // Fecha o modal
                    modal.className = "modal-oculto"; 
                } else {
                    alert("Erro ao excluir: " + retorno.erro);
                }
            })
            .catch(erro => alert("Erro de comunicação com o excluir_pedido.php"))
            .finally(() => {
                btnCancelar.innerHTML = textoOriginal;
                btnCancelar.disabled = false;
            });
        }
    });
    // ==========================================================================
    // SELEÇÃO MÚLTIPLA DE CARDS E FINALIZAÇÃO
    // ==========================================================================
    
    // 1. Marca/Desmarca o card ao clicar nele
    containerProducao.addEventListener("click", function(evento) {
        // Se a pessoa clicou no botão "Ver Detalhes", a gente não seleciona o card (ignora)
        if (evento.target.closest('.btn-ver-detalhes')) return;

        // Verifica se clicou em um card
        const cardClicado = evento.target.closest('.card-pedido-producao-ativo');
        if (cardClicado) {
            cardClicado.classList.toggle('card-selecionado'); // Pinta de verde ou tira o verde
        }
    });

    // 2. Ação do Botão Preto "Finalizar Pedido"
    const btnFinalizar = document.getElementById("btnFinalizarSelecionados");
    if (btnFinalizar) {
        btnFinalizar.addEventListener("click", function() {
            // Pega todos os cards que estão com a bordinha verde
            const cardsSelecionados = document.querySelectorAll('.card-selecionado');
            
            if (cardsSelecionados.length === 0) {
                alert("Por favor, selecione pelo menos um pedido clicando no card.");
                return;
            }

            // Pega o número (ID) de cada card selecionado e guarda numa lista
            const listaIds = Array.from(cardsSelecionados).map(card => card.querySelector('.btn-ver-detalhes').getAttribute('data-id'));

            // =================================================================
            // INTELIGÊNCIA DA MENSAGEM DE CONFIRMAÇÃO
            // =================================================================
            const qtdPedidos = listaIds.length;
            const listaFormatada = listaIds.map(id => `#${id}`).join(', '); // Fica assim: #17, #14, #13
            
            let mensagem = "";
            if (qtdPedidos === 1) {
                mensagem = `Você está prestes a FINALIZAR o Pedido ${listaFormatada}.\n\nTem certeza que deseja continuar?`;
            } else {
                mensagem = `Você está prestes a FINALIZAR ${qtdPedidos} pedidos de uma vez (${listaFormatada}).\n\nTem certeza que deseja continuar?`;
            }

            // Exibe a caixinha de confirmação na tela
            const confirmacao = confirm(mensagem);

            // Se o usuário clicar em "Cancelar", a função aborta aqui mesmo
            if (!confirmacao) {
                return;
            }
            // =================================================================

            // Se o usuário clicou em "OK", segue o baile para o banco de dados!
            const textoOriginal = btnFinalizar.innerHTML;
            btnFinalizar.innerHTML = "⏳ Finalizando...";
            btnFinalizar.disabled = true;

            // Envia os IDs para o PHP trabalhar
            fetch('finalizar_pedidos.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: listaIds })
            })
            .then(res => res.json())
            .then(retorno => {
                if (retorno.sucesso) {
                    carregarPedidosDoBanco();
                } else {
                    alert("Erro ao finalizar: " + retorno.erro);
                }
            })
            .catch(erro => alert("Erro na comunicação com finalizar_pedidos.php"))
            .finally(() => {
                btnFinalizar.innerHTML = textoOriginal;
                btnFinalizar.disabled = false;
            });
        });
    }
}); // <-- O FECHAMENTO DO ARQUIVO CONTINUA AQUI NO FINALZINHO!);
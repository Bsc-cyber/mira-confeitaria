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
    // PUXAR PRODUTOS DO BANCO PARA O AUTOCOMPLETAR
    // ==========================================================================
    function carregarProdutosParaSugestoes() {
        fetch('../php/buscar_produtos.php') 
        .then(res => res.json())
        .then(retorno => {
            if (retorno.sucesso) {
                const datalist = document.getElementById('listaProdutosSugestoes');
                datalist.innerHTML = ''; 
                
                retorno.produtos.forEach(prod => {
                    if (prod.status == 1) { 
                        const opcao = document.createElement('option');
                        
                        // MÁGICA 1: Cria um nome visual único para você diferenciar os repetidos!
                        let nomeVisivel = prod.nome;
                        if (prod.tamanho) nomeVisivel += ` - ${prod.tamanho}`;
                        if (prod.sabores) nomeVisivel += ` (${prod.sabores})`;
                        
                        opcao.value = nomeVisivel; // O que aparece na barrinha para você
                        opcao.setAttribute('data-nome-real', prod.nome); // O nome limpo escondido
                        opcao.setAttribute('data-preco', prod.preco);
                        opcao.setAttribute('data-tamanho', prod.tamanho || '');
                        opcao.setAttribute('data-sabor', prod.sabores || '');
                        
                        datalist.appendChild(opcao);
                    }
                });
            }
        })
        .catch(erro => console.error("Erro ao puxar produtos para sugestão:", erro));
    }
    
    // Dispara a busca assim que a tela abre
    carregarProdutosParaSugestoes();

    // ==========================================================================
    // AUTO-PREENCHER A DATA DO PEDIDO COM O DIA DE HOJE
    // ==========================================================================
    const campoDataPedido = document.getElementById("dataPedido");
    if (campoDataPedido) {
        const dataHoje = new Date();
        const ano = dataHoje.getFullYear();
        const mes = String(dataHoje.getMonth() + 1).padStart(2, '0'); // Garante o 0 na frente (ex: 07)
        const dia = String(dataHoje.getDate()).padStart(2, '0');
        
        campoDataPedido.value = `${ano}-${mes}-${dia}`; // Formato obrigatório do HTML: YYYY-MM-DD
    }

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

                        // Só desenha o card na tela se ele NÃO estiver Finalizado
                        if (pedido.status !== "Finalizado") {
                            
                            const dataPedFormatada = pedido.data_pedido.split('-').reverse().join('/');
                            const dataEntFormatada = pedido.data_entrega.split('-').reverse().join('/');
                            
                            const novoCardProd = document.createElement("div");
                            novoCardProd.className = "card-pedido-producao-ativo"; 
                            novoCardProd.setAttribute("data-id", pedido.id); // Mantém o ID para a seleção verde funcionar
                            
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
                        
                        } // <-- Fechamento do IF que esconde os finalizados
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
    /* ==========================================================================
       PARTE 1: CÁLCULO DE PREÇO EM TEMPO REAL E AUTO-PREENCHIMENTO
       ========================================================================== */
    function atualizarPrecoDisplay() {
        const valProduto = selectProduto.value;
        const valSabor = document.getElementById("selectSabor").value.trim().toLowerCase();
        const valTamanho = document.getElementById("selectTamanho").value.trim().toLowerCase();
        const datalist = document.getElementById("listaProdutosSugestoes");
        
        if (!datalist || !valProduto) {
            precoDisplay.textContent = "R$ 0,00";
            return;
        }

        const digitadoNome = valProduto.trim().toLowerCase();
        const arrayOpcoes = Array.from(datalist.options);

        // 1. Tenta achar a combinação PERFEITA (Nome + Sabor + Tamanho exatos)
        let opcaoSelecionada = arrayOpcoes.find(opt => {
            const nomeLongo = opt.value.trim().toLowerCase();
            const nomeCurto = opt.getAttribute("data-nome-real").trim().toLowerCase();
            const saborOpt = (opt.getAttribute("data-sabor") || "").trim().toLowerCase();
            const tamanhoOpt = (opt.getAttribute("data-tamanho") || "").trim().toLowerCase();
            
            const nomeBate = (nomeCurto === digitadoNome || nomeLongo === digitadoNome);
            return nomeBate && (saborOpt === valSabor) && (tamanhoOpt === valTamanho);
        });

        // 2. Se não achou a perfeita, acha só pelo Nome (para ajudar a preencher as caixas pra você)
        if (!opcaoSelecionada) {
            opcaoSelecionada = arrayOpcoes.find(opt => {
                const nomeLongo = opt.value.trim().toLowerCase();
                const nomeCurto = opt.getAttribute("data-nome-real").trim().toLowerCase();
                return (nomeCurto === digitadoNome || nomeLongo === digitadoNome);
            });
            
            // Auto-preenche as caixas de Sabor e Tamanho (apenas se elas estiverem vazias)
            if (opcaoSelecionada) {
                const inputSabor = document.getElementById("selectSabor");
                const inputTamanho = document.getElementById("selectTamanho");
                if (inputSabor.value === "") inputSabor.value = opcaoSelecionada.getAttribute("data-sabor");
                if (inputTamanho.value === "") inputTamanho.value = opcaoSelecionada.getAttribute("data-tamanho");
            }
        }

        // Calcula e exibe o preço se encontrou algo
        if (opcaoSelecionada) {
            const precoUnitario = parseFloat(opcaoSelecionada.getAttribute("data-preco")) || 0;
            const qtd = parseInt(inputQuantidade.value) || 1;
            const subtotal = precoUnitario * qtd;
            precoDisplay.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
        } else {
            precoDisplay.textContent = "R$ 0,00";
        }
    }
    
    // Dispara o cálculo sempre que você digitar EM QUALQUER UMA das caixas (Produto, Sabor, Tamanho ou Qtd)
    selectProduto.addEventListener("input", atualizarPrecoDisplay);
    document.getElementById("selectSabor").addEventListener("input", atualizarPrecoDisplay);
    document.getElementById("selectTamanho").addEventListener("input", atualizarPrecoDisplay);
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
        const valorDigitado = selectProduto.value; 
        const sabor = document.getElementById("selectSabor").value; 
        const tamanho = document.getElementById("selectTamanho").value; 
        const qtd = parseInt(inputQuantidade.value) || 1; 
        const cliente = document.getElementById("selectCliente").value; 
        
        if (!cliente) { return alert("Por favor, selecione um Cliente primeiro!"); }
        if (!valorDigitado || !sabor || !tamanho) { return alert("Preencha Produto, Sabor e Tamanho!"); }
        
        const datalist = document.getElementById("listaProdutosSugestoes");
        
        // VALIDAÇÃO RIGOROSA: Tem que existir no banco EXATAMENTE essa combinação de Nome, Sabor e Tamanho!
        const opcaoSelecionada = Array.from(datalist.options).find(opt => {
            const digitadoNome = valorDigitado.trim().toLowerCase();
            const nomeLongo = opt.value.trim().toLowerCase();
            const nomeCurto = opt.getAttribute("data-nome-real").trim().toLowerCase();
            
            const saborOpt = (opt.getAttribute("data-sabor") || "").trim().toLowerCase();
            const tamanhoOpt = (opt.getAttribute("data-tamanho") || "").trim().toLowerCase();
            
            const saborDig = sabor.trim().toLowerCase();
            const tamanhoDig = tamanho.trim().toLowerCase();
            
            const nomeBate = (nomeCurto === digitadoNome || nomeLongo === digitadoNome);
            const saborBate = (saborOpt === saborDig);
            const tamanhoBate = (tamanhoOpt === tamanhoDig);
            
            return nomeBate && saborBate && tamanhoBate; // Os TRÊS têm que ser verdadeiros
        });

        // BLOQUEIO: Se tentou inventar moda ou alterar para algo que não existe, o sistema trava!
        if (!opcaoSelecionada) {
            return alert("❌ Produto Não Validado!\n\nA combinação exata de Produto, Sabor e Tamanho informada não está cadastrada no seu banco de dados. Verifique a digitação!");
        }
        
        // Se passou na segurança, pega o preço exato cadastrado no banco e adiciona!
        const precoUnit = parseFloat(opcaoSelecionada.getAttribute("data-preco"));
        const precoTotalItem = precoUnit * qtd;
        const nomeLimpo = opcaoSelecionada.getAttribute("data-nome-real");
        
        itensCarrinho.push({ 
            produtoNome: nomeLimpo, 
            sabor: sabor, 
            tamanho: tamanho, 
            qtd: qtd, 
            precoTotalItem: precoTotalItem 
        });
        
        atualizarTabelaCarrinho();
        
        // Limpa o formulário pro próximo doce
        selectProduto.value = "";
        document.getElementById("selectSabor").value = "";
        document.getElementById("selectTamanho").value = "";
        inputQuantidade.value = "1";
        precoDisplay.textContent = "R$ 0,00";
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

        // ENVIO SILENCIOSO PARA O PHP (FETCH)
        fetch('salvar_pedido.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },    
            body: JSON.stringify(pacoteDados)
        })
        .then(resposta => resposta.json())
        .then(retorno => {
            if (retorno.sucesso) {
                // 1. A MÁGICA: Puxa todos os dados atualizados do banco (desenha o card e arruma contadores)
                carregarPedidosDoBanco();
                
                // 2. Limpeza final do carrinho esquerdo (MANTIDO)
                itensCarrinho = []; 
                atualizarTabelaCarrinho(); 
                document.getElementById("selectCliente").value = "";
                // Como não tenho certeza do ID exato do seu Produto/Preço, mantenha os seus:
                // selectProduto.value = "";
                // precoDisplay.textContent = "R$ 0,00";
                
                // 3. Avisa que deu certo!
                alert("Sucesso! Pedido #" + retorno.id_pedido + " salvo no banco.");

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
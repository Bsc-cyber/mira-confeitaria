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
                
                const novoCardProd = document.createElement("div");
                novoCardProd.className = "card-pedido-producao-ativo"; 
                const classeBadge = (statusPed === "Pendente") ? "pendente" : "producao";
                
                novoCardProd.innerHTML = `
                    <div class="info-card-prod">
                        <h4><svg style="width:12px; height:12px; stroke:#171d14; fill:none; stroke-width:2; vertical-align:middle; margin-right:4px;" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Cliente: ${clienteName}</h4>
                        <p style="margin-top: 4px;">🎂 Lote: ${itensCarrinho.length} doce(s) cadastrado(s)</p>
                        <p style="margin-top: 2px;">💰 Líquido: R$ ${totalGeral.toFixed(2).replace('.', ',')}</p>
                    </div>
                    <span class="status-badge-dinamica ${classeBadge}">${statusPed}</span>
                `;
                
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
});
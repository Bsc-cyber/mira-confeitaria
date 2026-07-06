/* ==========================================================================
   PARTE 1: CAPTURA DE COMPONENTES DO DOM E MAPEAMENTO DE EVENTOS DE PREÇO
   ========================================================================== */

// Aguarda o carregamento total de toda a estrutura de tags HTML (DOM) na janela da página
document.addEventListener("DOMContentLoaded", function () {
    
    // Captura as referências das caixas de seleção e inputs do formulário de doce
    const selectProduto = document.getElementById("selectProduto"); // Caixa de seleção do produto principal
    const inputQuantidade = document.getElementById("inputQuantidade"); // Campo numérico de quantidade do item
    const precoDisplay = document.getElementById("precoDisplay"); // Caixinha cinza que exibe o subtotal calculado
    const btnAdicionar = document.getElementById("btnAdicionarCarrinho"); // Botão verde largo de injetar na tabela
    
    // Captura as referências dos componentes da listagem do carrinho à esquerda
    const corpoTabela = document.getElementById("corpoTabelaCarrinho"); // Área interna da tabela onde entram as linhas
    const totalDisplay = document.getElementById("totalPedidoDisplay"); // Texto em negrito que mostra o total geral do lote
    const btnLimpar = document.getElementById("btnLimparCarrinho"); // Botão auxiliar de limpar/excluir itens
    
    // Captura as referências dos componentes da esteira de produção à direita
    const btnGerar = document.getElementById("btnGerarPedido"); // Botão preto de despachar o pedido para a produção
    const containerProducao = document.getElementById("containerCardsProducao"); // Bloco que empilha as ordens geradas
    const estadoVazio = document.getElementById("estadoVazioId"); // Prancheta cinza de aviso padrão "Nenhum item adicionado"
    
    // Captura as referências dos contadores numéricos coloridos presentes no topo da direita
    const countProd = document.getElementById("countProd"); // Número indicador de pedidos em produção ativa
    const countPend = document.getElementById("countPend"); // Número indicador de pedidos pendentes na fila
    
    // Declaração de variáveis globais em memória RAM simulando a sessão corrente do carrinho
    let itensCarrinho = []; // Array que vai armazenar os objetos de doces inseridos pelo confeiteiro
    let totalGeral = 0; // Acumulador numérico que calcula o valor líquido total do pedido
    
    // 1. FUNÇÃO TÉCNICA: Calcula e atualiza dinamicamente o preço do item em tempo real na tela
    function atualizarPrecoDisplay() {
        // Captura a tag <option> que está selecionada no momento dentro do seletor de produtos
        const opcaoSelecionada = selectProduto.options[selectProduto.selectedIndex];
        
        // Extrai o valor numérico escondido no atributo customizado 'data-preco' da tag selecionada
        const precoUnitario = opcaoSelecionada.getAttribute("data-preco");
        
        // Lê a quantidade atual informada pelo usuário, convertendo para número inteiro (padrão é 1)
        const qtd = parseInt(inputQuantidade.value) || 1;
        
        // Verifica de forma lógica se existe um produto válido selecionado portando preço
        if (precoUnitario) {
            // Executa a multiplicação matemática básica: preço unitário vezes a quantidade
            const subtotal = parseFloat(precoUnitario) * qtd;
            
            // Injeta o resultado formatado em padrão moeda brasileira (R$) com duas casas decimais
            precoDisplay.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
        } else {
            // Caso nenhum produto esteja marcado no seletor, redefine a caixinha cinza para zero
            precoDisplay.textContent = "R$ 0,00";
        }
    }
    
    // Vincula a função de cálculo para disparar automaticamente sempre que o usuário trocar de produto
    selectProduto.addEventListener("change", atualizarPrecoDisplay);
    
    // Vincula a função de cálculo para disparar instantaneamente sempre que o usuário digitar ou mudar a quantidade
    inputQuantidade.addEventListener("input", atualizarPrecoDisplay);
/* ==========================================================================
   PARTE 2: INSERÇÃO DINÂMICA E EXCLUSÃO DE ITENS NA TABELA (ESQUERDA)
   ========================================================================== */

    // FUNÇÃO NOVA: Limpa e desenha a tabela do zero baseado na memória
    function atualizarTabelaCarrinho() {
        corpoTabela.innerHTML = ""; // Limpa a tabela visual
        totalGeral = 0; // Zera o totalizador

        // Percorre os itens na memória e desenha a linha com um ID único (index)
        itensCarrinho.forEach((item, index) => {
            const novaLinha = document.createElement("tr");
            
            novaLinha.innerHTML = `
                <td>${item.produtoNome}</td>
                <td>${item.sabor}</td>
                <td>${item.tamanho.split(' ')[0]}</td>
                <td>${item.qtd}</td>
                <td>R$ ${item.precoTotalItem.toFixed(2).replace('.', ',')}</td>
                <td style="text-align: center;">
                    <button type="button" class="btn-remover-item" data-index="${index}" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:12px; font-weight:bold;" title="Remover item">
                        ❌
                    </button>
                </td>
            `;
            
            corpoTabela.appendChild(novaLinha);
            totalGeral += item.precoTotalItem;
        });

        // Atualiza o valor do rodapé
        totalDisplay.textContent = `R$ ${totalGeral.toFixed(2).replace('.', ',')}`;
    }

    // 2. EVENTO DE CLIQUE: Adicionar ao carrinho
    btnAdicionar.addEventListener("click", function () {
        const produtoNome = selectProduto.value; 
        const sabor = document.getElementById("selectSabor").value; 
        const tamanho = document.getElementById("selectTamanho").value; 
        const qtd = parseInt(inputQuantidade.value) || 1; 
        const cliente = document.getElementById("selectCliente").value; 
        
        if (!cliente) { 
            alert("Por favor, selecione um Cliente primeiro!"); 
            return; 
        }
        if (!produtoNome || !sabor || !tamanho) { 
            alert("Preencha Produto, Sabor e Tamanho!"); 
            return; 
        }
        
        const precoUnit = parseFloat(selectProduto.options[selectProduto.selectedIndex].getAttribute("data-preco"));
        const precoTotalItem = precoUnit * qtd;
        
        // Em vez de desenhar o HTML aqui, nós guardamos na memória [cite: 88]
        itensCarrinho.push({ produtoNome, sabor, tamanho, qtd, precoTotalItem });
        
        // E chamamos a função inteligente que desenha tudo e calcula
        atualizarTabelaCarrinho();
    });

    // 3. EVENTO DE CLIQUE NOVO: Excluir item específico da tabela
    corpoTabela.addEventListener("click", function(evento) {
        // Verifica se clicou exatamente no ícone de "X" vermelho
        if (evento.target.closest('.btn-remover-item')) {
            // Descobre o número da linha clicada
            const index = evento.target.closest('.btn-remover-item').getAttribute('data-index');
            
            // Remove 1 item da memória a partir daquela posição
            itensCarrinho.splice(index, 1);
            
            // Redesenha a tabela perfeitamente sem o item excluído!
            atualizarTabelaCarrinho();
        }
    });
/* ==========================================================================
   PARTE 3: GERAÇÃO DA ORDEM DE PRODUÇÃO E LIMPEZA DE FLUXO (DIREITA)
   ========================================================================== */

    // 3. EVENTO DE CLIQUE: Limpa todas as informações do carrinho corrente na esquerda
    btnLimpar.addEventListener("click", function () {
        itensCarrinho = []; // Esvazia o array de itens guardados em memória RAM
        totalGeral = 0; // Zera o acumulador de faturamento do lote
        corpoTabela.innerHTML = ""; // Apaga todas as linhas de doces de dentro da tabela HTML
        totalDisplay.textContent = "R$ 0,00"; // Redefine o mostrador de totalizador para zero
        precoDisplay.textContent = "R$ 0,00"; // Redefine o mostrador de subtotal para zero
        selectProduto.value = ""; // Volta a seleção do produto para o estado inicial neutro
    });
    
// 4. EVENTO DE CLIQUE: Despacha o pedido para o Banco de Dados e gera o card
    btnGerar.addEventListener("click", function () {
        // Captura os dados do formulário
        const clienteName = document.getElementById("selectCliente").value; 
        const statusPed = document.getElementById("statusInicial").value; 
        const dataPed = document.getElementById("dataPedido").value;
        const dataEnt = document.getElementById("dataEntrega").value;
        
        // VALIDAÇÃO: Impede a geração se a tabela estiver vazia
        if (itensCarrinho.length === 0) {
            alert("A tabela do pedido está vazia! Adicione doces ao carrinho primeiro.");
            return; 
        }

        // Muda o texto do botão para mostrar que está carregando
        const textoOriginalBotao = btnGerar.innerHTML;
        btnGerar.innerHTML = "⏳ Salvando...";
        btnGerar.disabled = true;
        
        // 1. PREPARA O PACOTE DE DADOS (JSON)
        const pacoteDados = {
            cliente: clienteName,
            data_pedido: dataPed,
            data_entrega: dataEnt,
            status: statusPed,
            total: totalGeral,
            itens: itensCarrinho // Manda a lista inteira de doces!
        };

        // 2. ENVIA PARA O PHP USANDO FETCH (O Carteiro)
        fetch('../php/salvar_pedido.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(pacoteDados)
        })
        .then(resposta => resposta.json()) // Lê a resposta do PHP
        .then(retorno => {
            
            // SE O PHP AVISAR QUE SALVOU COM SUCESSO:
            if (retorno.sucesso) {
                // Esconde a prancheta vazia
                if (estadoVazio) { estadoVazio.style.display = "none"; }
                
                // CRIA O CARD NA TELA (Seu código visual original)
                const novoCardProd = document.createElement("div");
                novoCardProd.className = "card-pedido-producao-ativo"; 
                const classeBadge = (statusPed === "Pendente") ? "pendente" : "producao";
                
                novoCardProd.innerHTML = `
                    <div class="info-card-prod">
                        <h4>
                            <svg style="width:12px; height:12px; stroke:#171d14; fill:none; stroke-width:2; vertical-align:middle; margin-right:4px;" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Cliente: ${clienteName}
                        </h4>
                        <p style="margin-top: 4px;">🎂 Lote: ${itensCarrinho.length} doce(s) cadastrado(s)</p>
                        <p style="margin-top: 2px;">💰 Líquido: R$ ${totalGeral.toFixed(2).replace('.', ',')}</p>
                    </div>
                    <span class="status-badge-dinamica ${classeBadge}">${statusPed}</span>
                `;
                
                containerProducao.appendChild(novoCardProd);
                
                // Atualiza contadores numéricos
                if (statusPed === "Pendente") {
                    countPend.textContent = parseInt(countPend.textContent) + 1;
                } else {
                    countProd.textContent = parseInt(countProd.textContent) + 1;
                }
                
                // Limpa o formulário esquerdo
                btnLimpar.click();
                
                // Avisa o usuário!
                alert("Sucesso! O pedido foi salvo no banco de dados e enviado para produção.");
            } else {
                // SE O PHP DEVOLVER ERRO (Ex: banco de dados fora do ar)
                alert("Erro ao salvar o pedido no banco de dados:\n" + retorno.erro);
            }
        })
        .catch(erro => {
            alert("Erro de comunicação com o servidor.");
            console.error(erro);
        })
        .finally(() => {
            // Devolve o botão ao estado normal
            btnGerar.innerHTML = textoOriginalBotao;
            btnGerar.disabled = false;
        });
    });
});
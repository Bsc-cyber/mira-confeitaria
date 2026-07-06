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
   PARTE 2: INSERÇÃO DINÂMICA DE ITENS NA TABELA DO CARRINHO (ESQUERDA)
   ========================================================================== */

    // 2. EVENTO DE CLIQUE: Escuta o botão de adicionar ao carrinho e injeta os dados na tabela
    btnAdicionar.addEventListener("click", function () {
        // Captura os textos e valores selecionados nos campos no momento exato do clique
        const produtoNome = selectProduto.value; // Nome do doce escolhido
        const sabor = document.getElementById("selectSabor").value; // Sabor selecionado
        const tamanho = document.getElementById("selectTamanho").value; // Tamanho selecionado
        const qtd = parseInt(inputQuantidade.value) || 1; // Quantidade informada
        const cliente = document.getElementById("selectCliente").value; // Nome do cliente do lote
        
        // REGRAS DE VALIDAÇÃO: Impede o envio se faltar preencher algum seletor obrigatório
        if (!cliente) { 
            alert("Por favor, selecione um Cliente primeiro antes de montar o carrinho!"); 
            return; // Interrompe a execução do código na hora
        }
        if (!produtoNome || !sabor || !tamanho) { 
            alert("Preencha todos os parâmetros do doce (Produto, Sabor e Tamanho)!"); 
            return; // Interrompe a execução do código na hora
        }
        
        // Extrai o preço unitário do produto selecionado para somar na tabela
        const precoUnit = parseFloat(selectProduto.options[selectProduto.selectedIndex].getAttribute("data-preco"));
        
        // Multiplica o valor unitário pela quantidade de doces informada
        const precoTotalItem = precoUnit * qtd;
        
        // Injeta os dados estruturados em forma de objeto dentro do vetor global na memória RAM
        itensCarrinho.push({ produtoNome, sabor, tamanho, qtd, precoTotalItem });
        
        // CRIAÇÃO DE COMPONENTE HTML: Cria uma linha física <tr> para embutir na tabela da esquerda
        const novaLinha = document.createElement("tr");
        
        // Preenche os dados organizados em colunas <td> milimetricamente alinhadas conforme a tabela
        novaLinha.innerHTML = `
            <td>${produtoNome}</td>
            <td>${sabor}</td>
            <td>${tamanho.split(' ')[0]}</td> <!-- Pega apenas a primeira palavra (Ex: Pequeno) -->
            <td>${qtd}</td>
            <td>R$ ${precoTotalItem.toFixed(2).replace('.', ',')}</td>
        `;
        
        // Adiciona a nova linha de fato no corpo da tabela visível na tela
        corpoTabela.appendChild(novaLinha);
        
        // Atualiza a variável acumuladora do total geral do pedido somando o novo item
        totalGeral += precoTotalItem;
        
        // Injeta o valor do acumulador formatado na moeda nacional no mostrador da base esquerda
        totalDisplay.textContent = `R$ ${totalGeral.toFixed(2).replace('.', ',')}`;
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
    
    // 4. EVENTO DE CLIQUE: Despacha o pedido atual gerando o card na esteira de produção à direita
    btnGerar.addEventListener("click", function () {
        // Captura as definições de cabeçalho do pedido no instante do disparo
        const clienteName = document.getElementById("selectCliente").value; // Nome do cliente informado
        const statusPed = document.getElementById("statusInicial").value; // Status de produção escolhido
        
        // VALIDAÇÃO: Impede a geração se o confeiteiro tentar clicar com a tabela vazia
        if (itensCarrinho.length === 0) {
            alert("A tabela do pedido está vazia! Adicione doces ao carrinho primeiro.");
            return; // Interrompe o envio na hora
        }
        
        // Esconde a prancheta de aviso cinza central de "Nenhum item adicionado" para abrir espaço
        if (estadoVazio) { 
            estadoVazio.style.display = "none"; 
        }
        
        // CRIAÇÃO DE COMPONENTE HTML: Desenha a caixinha estrutural (Card) da ordem de produção
        const novoCardProd = document.createElement("div");
        novoCardProd.className = "card-pedido-producao-ativo"; // Aplica o estilo de contorno e padding
        
        // Define de forma lógica a classe de cor da etiqueta da esteira baseado no status selecionado
        const classeBadge = (statusPed === "Pendente") ? "pendente" : "producao";
        
        // Injeta a estrutura de layout e os dados do lote em formato de texto e mini ícones SVG
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
        
        // Adiciona o card gerado na lista vertical de monitoramento da direita
        containerProducao.appendChild(novoCardProd);
        
        // ATUALIZAÇÃO DE INDICADORES: Incrementa o contador do topo baseado na escolha feita
        if (statusPed === "Pendente") {
            // Soma mais um no contador de pendentes na fila
            countPend.textContent = parseInt(countPend.textContent) + 1;
        } else {
            // Soma mais um no contador de ordens em produção ativa
            countProd.textContent = parseInt(countProd.textContent) + 1;
        }
        
        // Simula o clique automático no botão de limpar para resetar a esquerda para uma nova venda
        btnLimpar.click();
        
        // Dispara o alerta de sucesso na tela para controle operacional do confeiteiro
        alert("Sucesso! O lote de pedidos foi despachado para a esteira de produção.");
    });
});

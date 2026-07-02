// ==========================================
// CONFIGURAÇÕES GERAIS E BANCO DE DADOS LOCAL
// ==========================================
let carrinho = []; // Array temporário para guardar todos os doces que o cliente escolher
let produtoAtual = {}; // Guarda os dados do doce que está aberto na tela no momento
let precoBaseAtual = 0; // Armazena o preço original do doce selecionado
let precoAdicionalTamanho = 0; // Controla o acréscimo dinâmico baseado no tamanho escolhido

// ==========================================
// CONTROLE DE NAVEGAÇÃO (CARDÁPIO VS SOBRE NÓS)
// ==========================================

// Função que esconde o cardápio e exibe a página explicativa com animação suave
function abrirSobreNos() {
    document.getElementById('area-cardapio-completo').style.display = 'none';
    
    let sectionSobre = document.getElementById('section-sobre-nos');
    sectionSobre.style.display = 'flex';
    
    // Altera a classe ativa dos links do menu no topo fixo
    document.getElementById('menu-cardapio').classList.remove('active');
    document.getElementById('menu-sobre').classList.add('active');
}

// Função que traz o usuário de volta para a vitrine de doces
function mostrarCardapio() {
    document.getElementById('section-sobre-nos').style.display = 'none';
    
    let areaCardapio = document.getElementById('area-cardapio-completo');
    areaCardapio.style.display = 'block';
    
    document.getElementById('menu-sobre').classList.remove('active');
    document.getElementById('menu-cardapio').classList.add('active');
}

// ==========================================
// MOTOR DE FILTRAGEM INTELIGENTE POR CATEGORIAS
// ==========================================
function filtrarPorCategoria(categoriaAlvo, elementoClicado) {
    // Remove a classe 'active' de todas as bolinhas de categoria
    let itensCategoria = document.querySelectorAll('.category-item');
    itensCategoria.forEach(item => item.classList.remove('active'));
    
    // Adiciona a classe ativa apenas na bolinha que o usuário clicou
    elementoClicado.classList.add('active');
    
    // Captura todas as caixinhas de doces da página
    let cards = document.querySelectorAll('.product-card');
    
    cards.forEach(card => {
        // Pega a etiqueta 'data-category' que inserimos no HTML de cada doce
        let categoriaDoCard = card.getAttribute('data-category');
        
        // Aplica o efeito visual de entrada resetando a animação
        card.style.animation = 'none';
        card.offsetHeight; // Truque para forçar o navegador a reiniciar a animação
        card.style.animation = 'cardFadeIn 0.5s ease-out forwards';
        
        // Se for 'todos' ou bater com a categoria clicada, exibe. Senão, esconde.
        if (categoriaAlvo === 'todos' || categoriaDoCard === categoriaAlvo) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// ==========================================
// SISTEMA DE BARRA DE PESQUISA POR TEXTO
// ==========================================
function buscarProdutos() {
    // Pega o texto digitado na barra de pesquisa e transforma em letras minúsculas
    let termoBusca = document.getElementById('search-input').value.toLowerCase().trim();
    let cards = document.querySelectorAll('.product-card');
    
    cards.forEach(card => {
        // Captura o nome do doce dentro do h3 daquela caixinha específica
        let nomeDoce = card.querySelector('h3').innerText.toLowerCase();
        
        // Se o nome do doce contiver as letras digitadas, mantém visível
        if (nomeDoce.includes(termoBusca)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// ==========================================
// REORDENAÇÃO DINÂMICA POR MENOR OU MAIOR PREÇO
// ==========================================
function ordenarProdutos() {
    let criterio = document.getElementById('sort-select').value;
    let grid = document.getElementById('main-products-grid');
    let cards = Array.from(grid.querySelectorAll('.product-card'));
    
    // Organiza a lista de elementos baseada no valor numérico da etiqueta 'data-price'
    cards.sort((a, b) => {
        let precoA = parseFloat(a.getAttribute('data-price'));
        let precoB = parseFloat(b.getAttribute('data-price'));
        
        if (criterio === 'menor-preco') {
            return precoA - precoB; // Do menor para o maior
        } else if (criterio === 'maior-preco') {
            return precoB - precoA; // Do maior para o menor
        } else {
            return 0; // Mantém ordem original (mais populares)
        }
    });
    
    // Limpa a grade e reinjeta os cartões na nova ordem de preços organizada
    grid.innerHTML = "";
    cards.forEach(card => grid.appendChild(card));
}

// ==========================================
// CONTROLE INTERNO DO POP-UP (MODAL DE OPÇÕES)
// ==========================================
function abrirModal(nome, img, descricao, precoBase) {
    produtoAtual = { nome, img, descricao, precoBase };
    precoBaseAtual = precoBase;
    precoAdicionalTamanho = 0;
    
    document.getElementById('modal-title').innerText = nome;
    document.getElementById('modal-img').src = img;
    document.getElementById('modal-desc').innerText = descricao;
    document.getElementById('qty-input').value = 1;
    document.getElementById('modal-obs').value = "";
    
    // Marca a primeira opção de tamanho (Fatia Individual) como padrão automaticamente
    const tamanhos = document.getElementsByName('size');
    if(tamanhos.length > 0) {
        tamanhos[0].checked = true;
    }
    
    calcularTotalModal();
    document.getElementById('product-modal').style.display = 'flex';

    // 👇 NOVA LINHA ADICIONADA AQUI: Recolhe o carrinho ao abrir um novo doce 👇
    document.getElementById('sidebar-carrinho').classList.remove('active');
}


function fecharModal() {
    document.getElementById('product-modal').style.display = 'none';
}

function alterarQtd(valor) {
    let input = document.getElementById('qty-input');
    let qtd = parseInt(input.value) + valor;
    if (qtd >= 1) {
        input.value = qtd;
        calcularTotalModal();
    }
}

function atualizarPrecoModal(acrescimo) {
    precoAdicionalTamanho = acrescimo;
    calcularTotalModal();
}

function calcularTotalModal() {
    let qtd = parseInt(document.getElementById('qty-input').value);
    let totalItem = (precoBaseAtual + precoAdicionalTamanho) * qtd;
    document.getElementById('modal-total-price').innerText = `R$ ${totalItem.toFixed(2).replace('.', ',')}`;
}

    // ==========================================
    // GERENCIAMENTO DA LISTA DE COMPRAS (CARRINHO)
    // ==========================================

    function adicionarAoCarrinhoDoModal() {
        let qtd = parseInt(document.getElementById('qty-input').value);
        let tamanhoSelecionado = document.querySelector('input[name="size"]:checked').value;
        let observacaoText = document.getElementById('modal-obs').value.trim();
        let precoFinalUnitario = precoBaseAtual + precoAdicionalTamanho;

        carrinho.push({
            nome: produtoAtual.nome,
            tamanho: tamanhoSelecionado,
            obs: observacaoText ? observacaoText : "Nenhuma",
            qtd: qtd,
            precoTotal: precoFinalUnitario * qtd
        });

        fecharModal();
        atualizarInterfaceCarrinho();
        //toggleCarrinho(true); // Abre a barra lateral para o cliente ver o item entrando
    }

    function atualizarInterfaceCarrinho() {
        let container = document.getElementById('carrinho-itens');
        let badge = document.getElementById('cart-count-badge');
        let totalDisplay = document.getElementById('sidebar-total-value');
        let formContainer = document.getElementById('checkout-form-container');
        
        badge.innerText = carrinho.length;
        
        if (carrinho.length === 0) {
            container.innerHTML = '<p class="empty-msg">Nenhum item adicionado ainda.</p>';
            totalDisplay.innerText = "R$ 0,00";
            formContainer.style.display = 'none';
            return;
        }

        formContainer.style.display = 'block';
        container.innerHTML = "";
        let totalGeral = 0;

        // Adicionamos o 'index' aqui para rastrear a posição do item
        carrinho.forEach((item, index) => {
            totalGeral += item.precoTotal;
            container.innerHTML += `
                <div class="cart-item-row" style="margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #EFEBE4; position: relative;">
                    <strong style="padding-right: 25px; display: block;">${item.qtd}x ${item.nome}</strong>
                    <span style="color: #7A8A7C; font-size:11px;">Tamanho: ${item.tamanho}</span><br>
                    <span style="color: #BC8A5F; font-size:11px; display:block; font-style:italic;">Obs: ${item.obs}</span>
                    <span style="color: #2F3E33; font-weight:600; display:block; margin-top:2px;">R$ ${item.precoTotal.toFixed(2).replace('.', ',')}</span>
                    
                    <i class="ph ph-trash" onclick="removerDoCarrinho(${index}, event)" style="position: absolute; right: 0; top: 10px; cursor: pointer; font-size: 16px; color: #A2B1A6;" title="Remover pedido"></i>
                </div>
            `;
        });

        totalDisplay.innerText = `R$ ${totalGeral.toFixed(2).replace('.', ',')}`;
    }

    function toggleCarrinho(forçarAbrir = false) {
        let sidebar = document.getElementById('sidebar-carrinho');
        if (forçarAbrir) {
            sidebar.classList.add('active');
        } else {
            sidebar.classList.toggle('active');
        }
    }

    function toggleCamposEndereco() {
        let metodo = document.getElementById('delivery-method').value;
        let camposEndereco = document.getElementById('address-fields');
        if (metodo === 'retirada') {
            camposEndereco.style.display = 'none';
        } else {
            camposEndereco.style.display = 'block';
        }
    }

// ==========================================
// DISPARO DA COMANDA INTEGRADA PARA WHATSAPP
// ==========================================
function enviarPedidoWhatsApp() {
    if (carrinho.length === 0) {
        alert("Seu carrinho está vazio! Adicione algum doce antes de finalizar.");
        return;
    }

    let nomeCliente = document.getElementById('client-name').value.trim();
    let tipoEntrega = document.getElementById('delivery-method').value;
    let enderecoCliente = document.getElementById('client-address').value.trim();
    let bairroCliente = document.getElementById('client-bairro').value.trim();

    // VALIDAÇÕES OBRIGATÓRIAS
    if (!nomeCliente) {
        alert("Por favor, preencha o campo: Seu Nome Completo.");
        document.getElementById('client-name').focus();
        return;
    }

    if (tipoEntrega === 'entrega' && (!enderecoCliente || !bairroCliente)) {
        alert("Por favor, preencha o Endereço e o Bairro para realizarmos a entrega.");
        if(!enderecoCliente) document.getElementById('client-address').focus();
        else document.getElementById('client-bairro').focus();
        return;
    }

    // NÚMERO DE WHATSAPP DO CONFEITEIRO (Mude para o real do cliente com o 55 e o DDD)
    let numeroTelefone = "5531995365146"; // Exemplo: 55 + DDD + número
    
    let textoComanda = "🎂 *NOVO PEDIDO - MIRA CONFEITARIA*\n\n";
    textoComanda += `👤 *Cliente:* ${nomeCliente}\n`;
    
    if (tipoEntrega === 'retirada') {
        textoComanda += `📦 *Forma de Retirada:* Retirar na Confeitaria\n`;
    } else {
        textoComanda += `🛵 *Forma de Entrega:* Enviar em Domicílio\n`;
        textoComanda += `📍 *Endereço:* ${enderecoCliente}\n`;
        textoComanda += `🏘️ *Bairro:* ${bairroCliente}\n`;
    }
    
    textoComanda += `\n------------------------------------------\n`;
    textoComanda += `🛒 *ITENS DO PEDIDO:*\n\n`;

    let totalGeral = 0;
    
    carrinho.forEach(item => {
        textoComanda += `*${item.qtd}x ${item.nome}*\n`;
        textoComanda += `• Tamanho: ${item.tamanho}\n`;
        textoComanda += `• Observação: ${item.obs}\n`;
        textoComanda += `• Subtotal: R$ ${item.precoTotal.toFixed(2).replace('.', ',')}\n\n`;
        totalGeral += item.precoTotal;
    });

    textoComanda += `------------------------------------------\n`;
    textoComanda += `💰 *TOTAL GERAL: R$ ${totalGeral.toFixed(2).replace('.', ',')}*`;

    // Dispara a API oficial abrindo o chat estruturado
    let urlCompleta = `https://api.whatsapp.com/send?phone=${numeroTelefone}&text=${encodeURIComponent(textoComanda)}`;
    window.open(urlCompleta, '_blank');
}
// ==========================================
// ABRIR CHAT SIMPLES NO WHATSAPP (RODAPÉ)
// ==========================================
function falarNoWhatsApp() {
    // Insira aqui o mesmo número de WhatsApp da loja
    let numeroTelefone = "5531995365146"; // Exemplo: 55 + DDD + número
    
    // Você pode deixar uma mensagem padrão ou deixar vazio ("")
    let mensagemPadrao = "Olá! Gostaria de tirar uma dúvida com a Mira Confeitaria.";
    
    let urlCompleta = `https://wa.me/${numeroTelefone}?text=${encodeURIComponent(mensagemPadrao)}`;
    window.open(urlCompleta, '_blank');
}

// ==========================================
// FECHAR CARRINHO AO CLICAR FORA DELE
// ==========================================
document.addEventListener('click', function(event) {
    let sidebar = document.getElementById('sidebar-carrinho');
    let cartIcon = document.querySelector('.cart-icon');

    // Verifica se o carrinho está aberto e se o clique foi FORA da barra lateral e FORA do ícone
    if (sidebar.classList.contains('active') && 
        !sidebar.contains(event.target) && 
        !cartIcon.contains(event.target)) {
        
        // Remove a classe que mantém o carrinho aberto
        sidebar.classList.remove('active');
    }
});


// ==========================================
// FUNÇÃO PARA REMOVER ITEM DO CARRINHO
// ==========================================
function removerDoCarrinho(index, event) {
    // Segura o clique no ícone para não acionar o fechamento da aba lateral
    event.stopPropagation(); 
    
    // Remove 1 elemento do array a partir da posição (index)
    carrinho.splice(index, 1);
    
    // Atualiza a interface 
    atualizarInterfaceCarrinho();
}
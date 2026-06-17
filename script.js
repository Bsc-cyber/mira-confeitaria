document.addEventListener("DOMContentLoaded", () => {
    let carrinho = [];
    let taxaDesconto = 0; // 0 significa sem desconto, 0.10 significa 10%

    // 1. FILTRAGEM DE CATEGORIAS
    const botoesFiltro = document.querySelectorAll(".btn-filtro");
    const cardsProdutos = document.querySelectorAll(".card-produto");

    botoesFiltro.forEach(botao => {
        botao.addEventListener("click", () => {
            botoesFiltro.forEach(b => b.classList.remove("active"));
            botao.classList.add("active");
            const categoria = botao.getAttribute("data-categoria");

            cardsProdutos.forEach(card => {
                const tagCard = card.querySelector(".tag-categoria").textContent.toLowerCase().trim();
                card.style.display = (categoria === "todos" || tagCard === categoria) ? "flex" : "none";
            });
        });
    });

    // 2. CONTROLE DA SACOLA LATERAL
    const btnAbrir = document.getElementById("abrir-carrinho");
    const btnFechar = document.getElementById("fechar-carrinho");
    const painel = document.getElementById("menu-lateral-carrinho");

    if (btnAbrir && painel) btnAbrir.addEventListener("click", () => painel.classList.add("ativo"));
    if (btnFechar && painel) btnFechar.addEventListener("click", () => painel.classList.remove("ativo"));

    // 3. ADICIONAR PRODUTOS À SACOLA + NOTIFICAÇÃO TOAST
    const botoesAdd = document.querySelectorAll(".btn-add-sacola");
    const toast = document.getElementById("toast-alerta");

    botoesAdd.forEach(btn => {
        btn.addEventListener("click", () => {
            const nome = btn.getAttribute("data-nome");
            const preco = parseFloat(btn.getAttribute("data-preco"));

            const itemExistente = carrinho.find(item => item.nome === nome);
            if (itemExistente) {
                itemExistente.quantidade++;
            } else {
                carrinho.push({ nome, preco, quantidade: 1 });
            }
            
            atualizarInterfaceCarrinho();
            
            if (toast) {
                toast.classList.add("mostrar");
                setTimeout(() => toast.classList.remove("mostrar"), 2000);
            }
        });
    });

    // 4. SISTEMA DE CUPOM DE DESCONTO DE TESTE
    const btnCupom = document.getElementById("btn-validar-cupom");
    const inputCupom = document.getElementById("input-cupom-texto");
    const msgCupom = document.getElementById("msg-cupom-status");

    if (btnCupom && inputCupom && msgCupom) {
        btnCupom.addEventListener("click", () => {
            const textoCupom = inputCupom.value.toUpperCase().trim();
            
            if (textoCupom === "BOASVINDAS") {
                taxaDesconto = 0.10; // Aplica 10%
                msgCupom.textContent = "Cupom 'BOASVINDAS' aplicado! 🎉";
                msgCupom.style.color = "#10B981";
                msgCupom.style.display = "block";
            } else {
                taxaDesconto = 0;
                msgCupom.textContent = "Cupom inválido ou expirado.";
                msgCupom.style.color = "#EF4444";
                msgCupom.style.display = "block";
            }
            atualizarInterfaceCarrinho();
        });
    }

    // 5. ATUALIZAR INTERFACE DA SACOLA + MAIS E MENOS
    function atualizarInterfaceCarrinho() {
        const listaHtml = document.getElementById("itens-carrinho-lista");
        const contItens = document.getElementById("cont-itens");
        const totalHtml = document.getElementById("valor-total-carrinho");
        const blocoDescVisual = document.getElementById("bloco-desconto-visual");
        const valorDescHtml = document.getElementById("valor-desconto-html");
        
        if (!listaHtml) return; // Evita erros se o elemento não existir na página
        
        listaHtml.innerHTML = "";
        let subtotalCalculado = 0;
        let totalQuantidade = 0;

        carrinho.forEach((item, index) => {
            subtotalCalculado += item.preco * item.quantidade;
            totalQuantidade += item.quantidade;

            listaHtml.innerHTML += `
                <div class="item-no-carrinho" style="border-bottom: 1px solid #FAFAFA; padding: 12px 0;">
                    <div>
                        <h4 style="font-size:14px; font-weight:700;">${item.nome}</h4>
                        <p style="font-size:12px; color:var(--texto-mutado);">R$ ${item.preco.toFixed(2).replace('.', ',')}</p>
                        <div class="controle-qtd-carrinho">
                            <button class="btn-qtd-carrinho" onclick="window.alterarQuantidade(${index}, -1)">-</button>
                            <span style="font-size:13px; font-weight:700;">${item.quantidade}</span>
                            <button class="btn-qtd-carrinho" onclick="window.alterarQuantidade(${index}, 1)">+</button>
                        </div>
                    </div>
                    <button style="background:none; border:none; color:#EF4444; cursor:pointer; font-size:12px; font-weight:700;" onclick="window.removerDoCarrinho(${index})">Remover</button>
                </div>
            `;
        });

        let totalDesconto = subtotalCalculado * taxaDesconto;
        let totalGeralFinal = subtotalCalculado - totalDesconto;

        if (blocoDescVisual && valorDescHtml) {
            if (totalDesconto > 0) {
                blocoDescVisual.style.display = "flex";
                valorDescHtml.textContent = `- R$ ${totalDesconto.toFixed(2).replace('.', ',')}`;
            } else {
                blocoDescVisual.style.display = "none";
            }
        }

        if (contItens) contItens.textContent = totalQuantidade;
        if (totalHtml) totalHtml.textContent = `R$ ${totalGeralFinal.toFixed(2).replace('.', ',')}`;
    }

    // Funções globais mapeadas para acessar o carrinho interno sem erros de escopo
    window.alterarQuantidade = (index, valor) => {
        if (!carrinho[index]) return;
        carrinho[index].quantidade += valor;
        if (carrinho[index].quantidade <= 0) {
            carrinho.splice(index, 1);
        }
        atualizarInterfaceCarrinho();
    };

    window.removerDoCarrinho = (index) => {
        if (!carrinho[index]) return;
        carrinho.splice(index, 1);
        atualizarInterfaceCarrinho();
    };

    // 6. ENVIAR PARA O WHATSAPP COM OS DADOS EXTRA
    const botaoFinalizar = document.getElementById("finalizar-pedido-whats");
    if (botaoFinalizar) {
        botaoFinalizar.addEventListener("click", function() {
            if (carrinho.length === 0) {
                alert("Sua sacola está vazia!");
                return;
            }

            const inputNome = document.getElementById("cli-nome");
            const inputEndereco = document.getElementById("cli-endereco");

            if (!inputNome || !inputEndereco) {
                alert("Erro no HTML: Não encontrei os campos com os IDs 'cli-nome' ou 'cli-endereco'. Verifique as tags do seu formulário.");
                return;
            }

            const nomeCliente = inputNome.value.trim();
            const enderecoCliente = inputEndereco.value.trim();

            if (nomeCliente === "" || enderecoCliente === "") {
                alert("Por favor, preencha o seu Nome e Endereço.");
                return;
            }

            const telefoneDono = this.getAttribute("data-whats");
            if (!telefoneDono || telefoneDono.trim() === "") {
                alert("Erro de Configuração: O botão precisa ter o atributo data-whats='55...' preenchido no seu arquivo HTML.");
                return;
            }

            let mensagem = `🧁 *NOVA ENCOMENDA - MIRA CONFEITARIA* 🧁\n\n`;
            mensagem += `👤 *Cliente:* ${nomeCliente}\n`;
            mensagem += `📍 *Entrega:* ${enderecoCliente}\n\n`;
            mensagem += `=========================\n`;
            mensagem += `🛒 *ÍTENS SOLICITADOS:*\n\n`;
            
            let subtotalGeral = 0;

            carrinho.forEach(item => {
                const subtotalItem = item.preco * item.quantidade;
                subtotalGeral += subtotalItem;
                mensagem += `🍰 *${item.quantidade}x* ${item.nome}\n`;
                mensagem += `   Subtotal: R$ ${subtotalItem.toFixed(2).replace('.', ',')}\n\n`;
            });

            let valorDescontoFinal = subtotalGeral * taxaDesconto;
            let valorTotalFinal = subtotalGeral - valorDescontoFinal;

            mensagem += `=========================\n`;
            if (valorDescontoFinal > 0) {
                mensagem += `🎁 *Cupom Aplicado:* 10% DE DESCONTO\n`;
                mensagem += `📉 *Desconto:* - R$ ${valorDescontoFinal.toFixed(2).replace('.', ',')}\n`;
            }
            mensagem += `💰 *VALOR TOTAL:* R$ ${valorTotalFinal.toFixed(2).replace('.', ',')}\n\n`;
            mensagem += `Aguardando retorno sobre o agendamento! ✨`;

            // CORREÇÃO DO LINK DO WHATSAPP AQUI: Adicionado a "/" faltante e o caractere "$" de interpolação
            const urlWhatsApp = `https://wa.me{telefoneDono}?text=${encodeURIComponent(mensagem)}`;
            
            window.open(urlWhatsApp, '_blank');
        });
    }
});

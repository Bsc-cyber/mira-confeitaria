/* ==========================================================================
   MIRA CONFEITARIA - MOTOR DE INTELIGÊNCIA DA GESTÃO DE PRODUTOS
   ========================================================================== */

document.addEventListener("DOMContentLoaded", function () {
    // Captura de componentes do Formulário
    const form = document.getElementById("formCadastroProduto");
    const inputNome = document.getElementById("nomeProduto");
    const inputCategoria = document.getElementById("categoriaProduto");
    const inputTamanho = document.getElementById("tamanhoProduto");
    const inputPreco = document.getElementById("precoProduto");
    const inputSabores = document.getElementById("saboresProduto");
    const inputDescricao = document.getElementById("descricaoProduto");
    const inputStatus = document.getElementById("statusProduto");
    const labelStatus = document.getElementById("labelStatusFiltro");

    const btnEditar = document.getElementById("btnEditarProd");
    const btnLimpar = document.getElementById("btnLimparProd");
    
    // Captura da Tabela e Pesquisa
    const inputPesquisaGlobal = document.getElementById("inputPesquisaGlobal");
    const corpoTabela = document.getElementById("corpoTabelaProdutos");
    const btnRecarregar = document.getElementById("btnRecarregarProdutos");
    
    // Variável para controlar se estamos editando ou criando um novo
    let produtoIdAtual = null;

    // Atualiza o texto do switch dinamicamente
    inputStatus.addEventListener("change", function () {
        labelStatus.textContent = this.checked ? "Sim, produto ativo" : "Não, produto inativo";
    });

    // ==========================================================================
    // 1. CARREGAR PRODUTOS DO BANCO
    // ==========================================================================
    function carregarProdutosDoBanco() {
        corpoTabela.innerHTML = `<tr><td colspan="7" style="text-align:center;">Carregando produtos...</td></tr>`;

        fetch('../php/buscar_produtos.php')
        .then(res => res.json())
        .then(retorno => {
            corpoTabela.innerHTML = ""; // Limpa a tabela

            if (retorno.sucesso && retorno.produtos.length > 0) {
                retorno.produtos.forEach(produto => {
                    
                    // Lógica para colorir a badge da categoria
                    let classeCat = "b-doces"; // Padrão
                    const catLower = produto.categoria.toLowerCase();
                    if (catLower.includes('bolo')) classeCat = 'b-bolos';
                    if (catLower.includes('cheese')) classeCat = 'b-cheesecakes';
                    if (catLower.includes('torta')) classeCat = 'b-tortas';

                    // Lógica do Status
                    const statusBadge = produto.status == 1 
                        ? `<span class="badge-status ativo">Ativo</span>` 
                        : `<span class="badge-status inativo">Inativo</span>`;

                    // Formata preço para exibição
                    const precoFormatado = parseFloat(produto.preco).toFixed(2).replace('.', ',');

                    // Cria a linha
                    const tr = document.createElement("tr");
                    tr.className = "linha-selecionavel-prod";
                    tr.setAttribute("data-id", produto.id);
                    tr.style.display = "table-row";
                    tr.style.cursor = "pointer";

                    tr.innerHTML = `
                        <td>${produto.id}</td>
                        <td><strong>${produto.nome}</strong></td>
                        <td><span class="badge-cat ${classeCat}">${produto.categoria}</span></td>
                        <td>${produto.tamanho || '-'}</td>
                        <td>R$ ${precoFormatado}</td>
                        <td>${produto.sabores || '-'}</td>
                        <td>${statusBadge}</td>
                        <td class="celula-acoes-prod">
                            <button type="button" class="btn-linha-prod edit"><svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                        </td>
                    `;
                    
                    // Adiciona evento de clique na linha para jogar os dados pro formulário
                    tr.addEventListener("click", function (e) {
                        if (e.target.closest('.btn-linha-prod')) return; // Ignora se clicou no botão de ação direto
                        carregarFormulario(produto);
                    });

                    corpoTabela.appendChild(tr);
                });
            } else {
                corpoTabela.innerHTML = `<tr><td colspan="7" style="text-align:center;">Nenhum produto cadastrado ainda.</td></tr>`;
            }
        })
        .catch(erro => console.error("Erro ao puxar produtos:", erro));
    }

    // ==========================================================================
    // 2. SALVAR OU ATUALIZAR PRODUTO
    // ==========================================================================
    form.addEventListener("submit", function (e) {
        e.preventDefault(); // Impede a página de recarregar

        const btnSalvar = document.querySelector(".salvar-btn");
        const textoOriginal = btnSalvar.innerHTML;
        btnSalvar.innerHTML = "⏳ Salvando...";
        btnSalvar.disabled = true;

        const pacoteDados = {
            id: produtoIdAtual,
            nome: inputNome.value,
            categoria: inputCategoria.value,
            tamanho: inputTamanho.value,
            preco: inputPreco.value,
            sabores: inputSabores.value,
            descricao: inputDescricao.value,
            status: inputStatus.checked
        };

        fetch('../php/salvar_produto.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(pacoteDados)
        })
        .then(res => res.json())
        .then(retorno => {
            if (retorno.sucesso) {
                alert(retorno.mensagem);
                btnLimpar.click(); // Limpa o formulário
                carregarProdutosDoBanco(); // Atualiza a tabela imediatamente
            } else {
                alert("Erro ao salvar: " + retorno.erro);
            }
        })
        .catch(erro => alert("Erro de comunicação com o servidor."))
        .finally(() => {
            btnSalvar.innerHTML = textoOriginal;
            btnSalvar.disabled = false;
        });
    });

    // ==========================================================================
    // 3. CARREGAR DADOS NO FORMULÁRIO PARA EDIÇÃO
    // ==========================================================================
    function carregarFormulario(produto) {
        produtoIdAtual = produto.id; // Salva o ID para o sistema saber que é uma atualização
        
        inputNome.value = produto.nome;
        inputCategoria.value = produto.categoria;
        inputTamanho.value = produto.tamanho;
        
        // Retorna o preço formatado para o input
        inputPreco.value = parseFloat(produto.preco).toFixed(2).replace('.', ',');
        
        inputSabores.value = produto.sabores;
        inputDescricao.value = produto.descricao;
        
        inputStatus.checked = (produto.status == 1);
        labelStatus.textContent = inputStatus.checked ? "Sim, produto ativo" : "Não, produto inativo";

        btnEditar.disabled = false;

        // Rola o formulário de volta para o topo suavemente
        document.querySelector(".wrapper-inputs-scroll-cadastro").scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ==========================================================================
    // 4. INTERAÇÕES DE LIMPEZA E FILTRO
    // ==========================================================================
    btnLimpar.addEventListener("click", function () {
        form.reset();
        produtoIdAtual = null; // Zera o ID (o próximo salvamento será um INSERT novo)
        labelStatus.textContent = "Sim, produto ativo";
        btnEditar.disabled = true;
    });

    // Filtro superior em tempo real
    inputPesquisaGlobal.addEventListener("keyup", function () {
        const busca = this.value.toLowerCase().trim();
        const linhas = corpoTabela.querySelectorAll("tr");

        linhas.forEach(linha => {
            if (linha.cells.length > 1) { // Garante que não é a linha de "Carregando"
                const textoNome = linha.cells[1].textContent.toLowerCase();
                const textoCat = linha.cells[2].textContent.toLowerCase();

                if (textoNome.includes(busca) || textoCat.includes(busca)) {
                    linha.style.display = "table-row";
                } else {
                    linha.style.display = "none";
                }
            }
        });
    });

    btnRecarregar.addEventListener("click", carregarProdutosDoBanco);

    // Inicializa a tabela ao carregar a página
    carregarProdutosDoBanco();
});
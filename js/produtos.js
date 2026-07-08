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
    const btnExcluir = document.getElementById("btnExcluirProd");

    // Captura da Pesquisa Superior e Linhas da Tabela
    const inputPesquisaGlobal = document.getElementById("inputPesquisaGlobal");
    const corpoTabela = document.getElementById("corpoTabelaProdutos");

    // Array simulado em memória RAM contendo os 7 itens idênticos aos do mockup
    let produtosEmMemoria = [
        { id: 101, nome: "Bolo de Chocolate Premium", categoria: "Bolos", tamanho: "20 cm", preco: "89,90", sabores: "Chocolate", status: true },
        { id: 102, nome: "Cheesecake de Frutas Vermelhas", categoria: "Cheesecakes", tamanho: "18 cm", preco: "74,90", sabores: "Frutas Vermelhas", status: true },
        { id: 103, nome: "Torta de Limão Siciliano", categoria: "Tortas", tamanho: "22 cm", preco: "69,90", sabores: "Limão", status: true },
        { id: 104, nome: "Brigadeiro Gourmet", categoria: "Doces", tamanho: "15 un", preco: "45,00", sabores: "Chocolate", status: true },
        { id: 105, nome: "Macarons Sortidos", categoria: "Doces", tamanho: "10 un", preco: "55,00", sabores: "Variados", status: false },
        { id: 106, nome: "Bolo Red Velvet", categoria: "Bolos", tamanho: "20 cm", preco: "99,90", sabores: "Red Velvet", status: true },
        { id: 107, nome: "Brownie com Nozes", categoria: "Doces", tamanho: "12 un", preco: "42,00", sabores: "Chocolate, Nozes", status: true }
    ];

    // Atualiza o texto do switch dinamicamente ao clicar nele
    inputStatus.addEventListener("change", function () {
        labelStatus.textContent = this.checked ? "Sim, produto ativo" : "Não, produto inativo";
    });

    // Função para escutar cliques nas linhas manuais da tabela
    function vincularCliquesLinhas() {
        const linhas = corpoTabela.querySelectorAll(".linha-selecionavel-prod");
        linhas.forEach(linha => {
            linha.style.cursor = "pointer";
            linha.addEventListener("click", function (e) {
                // Impede disparar se o clique for direto nos mini botões de ação
                if (e.target.closest('.btn-linha-prod')) return;

                const idAlvo = this.getAttribute("data-id");
                const itemLocalizado = produtosEmMemoria.find(p => p.id == idAlvo);
                if (itemLocalizado) {
                    carregarFormulario(itemLocalizado);
                }
            });
        });
    }

    // Carrega os dados do item clicado de volta no formulário
    function carregarFormulario(dados) {
        inputNome.value = dados.nome;
        inputCategoria.value = dados.categoria;
        inputTamanho.value = dados.tamanho;
        inputPreco.value = dados.preco;
        inputSabores.value = dados.sabores;
        inputStatus.checked = dados.status;
        labelStatus.textContent = dados.status ? "Sim, produto ativo" : "Não, produto inativo";

        // Ativa botões administrativos auxiliares da esquerda
        btnEditar.disabled = false;
        btnExcluir.disabled = false;

        // Rola o formulário de volta estável para o topo
        document.querySelector(".wrapper-inputs-scroll-produtos").scrollTop = 0;
    }

    // Botão Limpar restaura o estado original neutro do formulário
    btnLimpar.addEventListener("click", function () {
        form.reset();
        labelStatus.textContent = "Sim, produto ativo";
        btnEditar.disabled = true;
        btnExcluir.disabled = true;
    });

    // Filtro superior por digitação em tempo real (Filtra por Nome ou Categoria)
    inputPesquisaGlobal.addEventListener("keyup", function () {
        const busca = this.value.toLowerCase().trim();
        const linhas = corpoTabela.querySelectorAll("tr");

        linhas.forEach(linha => {
            const textoNome = linha.cells[1].textContent.toLowerCase();
            const textoCat = linha.cells[2].textContent.toLowerCase();

            if (textoNome.includes(busca) || textoCat.includes(busca)) {
                linha.style.display = "";
            } else {
                linha.style.display = "none";
            }
        });
    });

    // Executa o vínculo inicial na carga do DOM
    vincularCliquesLinhas();
});

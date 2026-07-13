/* ==========================================================================
   MIRA CONFEITARIA - SISTEMA DE GESTÃO DE RECEITAS
   ========================================================================== */

document.addEventListener("DOMContentLoaded", function () {
    const formCadastro = document.getElementById("formCadastroReceita");
    const inputNome = document.getElementById("nomeReceita");
    const inputIngredientes = document.getElementById("ingredientesReceita");
    const inputPreparo = document.getElementById("preparoReceita");
    
    const corpoTabela = document.getElementById("corpoTabelaReceitas");
    const btnLimpar = document.getElementById("btnLimparRece"); 
    const inputPesquisaGlobal = document.getElementById("inputPesquisaGlobal");
    const btnRecarregar = document.getElementById("btnRecarregarReceitas");

    let idReceitaAtual = null; // Memória para saber se estamos a editar ou a criar

    // ==========================================================================
    // 1. CARREGAR RECEITAS DA BASE DE DADOS
    // ==========================================================================
    function carregarReceitasDaBase() {
        corpoTabela.innerHTML = `<tr><td colspan="4" style="text-align:center;">A carregar receitas...</td></tr>`;

        fetch('../php/buscar_receitas.php')
        .then(res => res.json())
        .then(retorno => {
            corpoTabela.innerHTML = ""; // Limpa a tabela atual

            if (retorno.sucesso && retorno.receitas.length > 0) {
                retorno.receitas.forEach(receita => {
                    const tr = document.createElement("tr");
                    tr.className = "linha-selecionavel-rece";
                    tr.style.display = "table-row";

                    // Trunca o texto dos ingredientes para a tabela não esticar demasiado
                    let ingrTruncado = receita.ingredientes;
                    if (ingrTruncado.length > 55) {
                        ingrTruncado = ingrTruncado.substring(0, 55) + '...';
                    }

                    tr.innerHTML = `
                        <td>${receita.id}</td>
                        <td><strong>${receita.nome}</strong></td>
                        <td title="${receita.ingredientes}">${ingrTruncado}</td>
                        <td class="celula-acoes-tabela">
                            <button type="button" class="btn-acao-linha edit" title="Editar"><svg style="width:12px;height:12px;stroke:#4b5563;stroke-width:2;fill:none;" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                            <button type="button" class="btn-acao-linha del" title="Eliminar"><svg style="width:12px;height:12px;stroke:#4b5563;stroke-width:2;fill:none;" viewBox="0 0 24 24"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                        </td>
                    `;

                    // Atribui funcionalidade ao botão Editar
                    tr.querySelector('.edit').addEventListener('click', () => carregarFormularioParaEdicao(receita));

                    // Atribui funcionalidade ao botão Eliminar
                    tr.querySelector('.del').addEventListener('click', () => eliminarReceita(receita.id, receita.nome));

                    corpoTabela.appendChild(tr);
                });
            } else {
                corpoTabela.innerHTML = `<tr><td colspan="4" style="text-align:center;">Nenhuma receita registada.</td></tr>`;
            }
        })
        .catch(erro => console.error("Erro ao puxar receitas da base de dados:", erro));
    }

    // ==========================================================================
    // 2. GUARDAR OU ATUALIZAR RECEITA
    // ==========================================================================
    formCadastro.addEventListener("submit", function (evento) {
        evento.preventDefault();

        const btnSalvar = document.querySelector(".salvar-btn");
        const textoOriginal = btnSalvar.innerHTML;
        btnSalvar.innerHTML = "⏳ A guardar...";
        btnSalvar.disabled = true;

        const pacoteDados = {
            id: idReceitaAtual,
            nome: inputNome.value,
            ingredientes: inputIngredientes.value,
            preparo: inputPreparo.value
        };

        fetch('../php/salvar_receita.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(pacoteDados)
        })
        .then(res => res.json())
        .then(retorno => {
            if (retorno.sucesso) {
                alert(retorno.mensagem);
                btnLimpar.click(); // Reseta o formulário
                carregarReceitasDaBase(); // Atualiza a tabela na hora
            } else {
                alert("Erro ao guardar: " + retorno.erro);
            }
        })
        .catch(erro => alert("Erro de comunicação com o servidor."))
        .finally(() => {
            btnSalvar.innerHTML = textoOriginal;
            btnSalvar.disabled = false;
        });
    });

    // ==========================================================================
    // 3. EDITAR RECEITA (Devolve os dados para as caixas de texto)
    // ==========================================================================
    function carregarFormularioParaEdicao(receita) {
        idReceitaAtual = receita.id; // Informa o sistema que será um UPDATE em vez de INSERT
        
        inputNome.value = receita.nome;
        inputIngredientes.value = receita.ingredientes;
        inputPreparo.value = receita.preparo;

        // Desliza suavemente o formulário de volta para o topo
        document.querySelector(".wrapper-inputs-scroll-cadastro").scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ==========================================================================
    // 4. ELIMINAR RECEITA
    // ==========================================================================
    function eliminarReceita(id, nome) {
        if (confirm(`Tem a certeza que deseja ELIMINAR a receita de "${nome}"?\n\nEsta ação não pode ser desfeita.`)) {
            
            fetch(`../php/excluir_receita.php?id=${id}`)
            .then(res => res.json())
            .then(retorno => {
                if (retorno.sucesso) {
                    carregarReceitasDaBase();
                } else {
                    alert("Erro ao eliminar a receita: " + retorno.erro);
                }
            })
            .catch(erro => alert("Erro na comunicação com o ficheiro PHP ao eliminar."));
        }
    }

    // ==========================================================================
    // 5. FERRAMENTAS (LIMPAR E PESQUISA)
    // ==========================================================================
    if (btnLimpar) {
        btnLimpar.addEventListener("click", function () {
            formCadastro.reset();
            idReceitaAtual = null; // Se limparmos, voltamos ao modo de criação
        });
    }

    // Filtro global em tempo real
    if (inputPesquisaGlobal) {
        inputPesquisaGlobal.addEventListener("keyup", function () {
            const busca = this.value.toLowerCase().trim();
            const linhas = corpoTabela.querySelectorAll("tr");

            linhas.forEach(linha => {
                if (linha.cells.length > 1) { // Evita afetar a linha de "A carregar..."
                    const textoNome = linha.cells[1].textContent.toLowerCase();
                    const textoIngredientes = linha.cells[2].textContent.toLowerCase();

                    if (textoNome.includes(busca) || textoIngredientes.includes(busca)) {
                        linha.style.display = "table-row";
                    } else {
                        linha.style.display = "none";
                    }
                }
            });
        });
    }

    // Ação do botão circular no topo da tabela
    if (btnRecarregar) {
        btnRecarregar.addEventListener("click", carregarReceitasDaBase);
    }

    // Inicia a tabela assim que o ecrã carrega
    carregarReceitasDaBase();
});
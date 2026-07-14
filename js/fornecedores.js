/* ==========================================================================
   MIRA CONFEITARIA - GESTÃO DE FORNECEDORES (CONECTADO AO BANCO)
   ========================================================================== */

document.addEventListener("DOMContentLoaded", function () {
    const formCadastro = document.getElementById("formCadastroFornecedor");
    const corpoTabela = document.getElementById("corpoTabelaFornecedores");
    const btnLimpar = document.getElementById("btnLimparForn");
    const inputPesquisaGlobal = document.getElementById("inputPesquisaGlobal");
    const btnRecarregar = document.getElementById("btnRecarregarFornecedores");

    let idFornecedorAtual = null;

    // ==========================================================================
    // 1. CARREGAR FORNECEDORES DO BANCO
    // ==========================================================================
    function carregarFornecedores() {
        corpoTabela.innerHTML = `<tr><td colspan="6" style="text-align:center;">Carregando fornecedores...</td></tr>`;

        fetch('../php/buscar_fornecedores.php')
        .then(res => res.json())
        .then(retorno => {
            corpoTabela.innerHTML = "";

            if (retorno.sucesso && retorno.fornecedores.length > 0) {
                retorno.fornecedores.forEach(forn => {
                    
                    // Lógica para colorir a badge corretamente
                    let classeBadge = "b-ingredientes";
                    if (forn.tipo === "Embalagens") classeBadge = "b-embalagens";
                    else if (forn.tipo === "Laticínios") classeBadge = "b-laticinios";
                    else if (forn.tipo === "Hortifrúti" || forn.tipo === "Hortifruti") classeBadge = "b-hortifruti";
                    else if (forn.tipo === "Aromas") classeBadge = "b-aromas";

                    const tr = document.createElement("tr");
                    tr.className = "linha-selecionavel-forn";
                    tr.innerHTML = `
                        <td>${forn.id}</td>
                        <td><strong>${forn.nome}</strong></td>
                        <td><span class="badge-tipo ${classeBadge}">${forn.tipo}</span></td>
                        <td>${forn.cnpj || "—"}</td>
                        <td>${forn.telefone || "—"}</td>
                        <td class="celula-acoes-tabela">
                            <button type="button" class="btn-acao-linha edit" title="Editar"><svg style="width:12px;height:12px;stroke:#4b5563;stroke-width:2;fill:none;" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                            <button type="button" class="btn-acao-linha del" title="Excluir"><svg style="width:12px;height:12px;stroke:#4b5563;stroke-width:2;fill:none;" viewBox="0 0 24 24"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                        </td>
                    `;

                    // Ações dos botões da linha
                    tr.querySelector('.edit').addEventListener('click', () => carregarFormularioEdicao(forn));
                    tr.querySelector('.del').addEventListener('click', () => excluirFornecedor(forn.id, forn.nome));

                    corpoTabela.appendChild(tr);
                });
            } else {
                corpoTabela.innerHTML = `<tr><td colspan="6" style="text-align:center;">Nenhum fornecedor cadastrado.</td></tr>`;
            }
        })
        .catch(erro => console.error("Erro ao puxar fornecedores:", erro));
    }

    // ==========================================================================
    // 2. SALVAR OU ATUALIZAR FORNECEDOR
    // ==========================================================================
    formCadastro.addEventListener("submit", function (evento) {
        evento.preventDefault();

        const btnSalvar = document.querySelector(".salvar-btn");
        const textoOriginal = btnSalvar.innerHTML;
        btnSalvar.innerHTML = "⏳ Salvando...";
        btnSalvar.disabled = true;

        const pacoteDados = {
            id: idFornecedorAtual,
            nome: document.getElementById("nomeFornecedor").value.trim(),
            tipo: document.getElementById("tipoFornecedor").value,
            cnpj: document.getElementById("cnpjFornecedor").value.trim(),
            telefone: document.getElementById("telFornecedor").value.trim(),
            email: document.getElementById("emailFornecedor").value.trim(),
            rua: document.getElementById("ruaFornecedor").value.trim(),
            numero: document.getElementById("numFornecedor").value.trim(),
            bairro: document.getElementById("bairroFornecedor").value.trim(),
            cidade: document.getElementById("cidadeFornecedor").value.trim(),
            complemento: document.getElementById("compFornecedor").value.trim(),
            info_adicional: document.getElementById("infoFornecedor").value.trim()
        };

        fetch('../php/salvar_fornecedor.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(pacoteDados)
        })
        .then(res => res.json())
        .then(retorno => {
            if (retorno.sucesso) {
                alert(retorno.mensagem);
                btnLimpar.click(); 
                carregarFornecedores(); 
            } else {
                alert("Erro: " + retorno.erro);
            }
        })
        .catch(erro => alert("Erro na comunicação com o servidor."))
        .finally(() => {
            btnSalvar.innerHTML = textoOriginal;
            btnSalvar.disabled = false;
        });
    });

    // ==========================================================================
    // 3. CARREGAR DADOS NO FORMULÁRIO (EDITAR) E EXCLUIR
    // ==========================================================================
    function carregarFormularioEdicao(forn) {
        idFornecedorAtual = forn.id;
        document.getElementById("nomeFornecedor").value = forn.nome;
        document.getElementById("tipoFornecedor").value = forn.tipo;
        document.getElementById("cnpjFornecedor").value = forn.cnpj;
        document.getElementById("telFornecedor").value = forn.telefone;
        document.getElementById("emailFornecedor").value = forn.email;
        document.getElementById("ruaFornecedor").value = forn.rua;
        document.getElementById("numFornecedor").value = forn.numero;
        document.getElementById("bairroFornecedor").value = forn.bairro;
        document.getElementById("cidadeFornecedor").value = forn.cidade;
        document.getElementById("compFornecedor").value = forn.complemento;
        document.getElementById("infoFornecedor").value = forn.info_adicional;

        document.querySelector(".wrapper-inputs-scroll-cadastro").scrollTo({ top: 0, behavior: 'smooth' });
    }

    function excluirFornecedor(id, nome) {
        if (confirm(`Tem certeza que deseja EXCLUIR o fornecedor "${nome}"?`)) {
            fetch(`../php/excluir_fornecedor.php?id=${id}`)
            .then(res => res.json())
            .then(retorno => {
                if (retorno.sucesso) carregarFornecedores();
                else alert("Erro ao excluir: " + retorno.erro);
            });
        }
    }

    // ==========================================================================
    // 4. FERRAMENTAS EXTRAS (Limpar e Pesquisar)
    // ==========================================================================
    btnLimpar.addEventListener("click", function () {
        formCadastro.reset();
        idFornecedorAtual = null; // Zera o ID para um novo cadastro
    });

    if (inputPesquisaGlobal) {
        inputPesquisaGlobal.addEventListener("keyup", function () {
            const busca = this.value.toLowerCase().trim();
            const linhas = corpoTabela.querySelectorAll("tr");
            linhas.forEach(linha => {
                if (linha.cells.length > 1) {
                    const textoLinha = linha.textContent.toLowerCase();
                    linha.style.display = textoLinha.includes(busca) ? "table-row" : "none";
                }
            });
        });
    }

    if (btnRecarregar) btnRecarregar.addEventListener("click", carregarFornecedores);

    carregarFornecedores(); // Inicia tudo!
});
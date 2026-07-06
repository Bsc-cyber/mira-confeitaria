// Aguarda o carregamento do DOM da tela de fornecedores
document.addEventListener("DOMContentLoaded", function () {
    const formCadastro = document.getElementById("formCadastroFornecedor");
    const nomeFornecedor = document.getElementById("nomeFornecedor");
    const tipoFornecedor = document.getElementById("tipoFornecedor");
    const cnpjFornecedor = document.getElementById("cnpjFornecedor");
    const telFornecedor = document.getElementById("telFornecedor");
    const corpoTabela = document.getElementById("corpoTabelaFornecedores");
    const btnLimpar = document.getElementById("btnLimparForn");
    
    let proximoId = 103; // Inicia os cadastros a partir do ID 103

    formCadastro.addEventListener("submit", function (evento) {
        evento.preventDefault(); // Impede o recarregamento automático da página

        const nome = nomeFornecedor.value.trim();
        const tipo = tipoFornecedor.value;
        const cnpj = cnpjFornecedor.value.trim() || "—";
        const tel = telFornecedor.value.trim() || "—";

        if (nome === "" || tipo === "") {
            alert("Por favor, preencha os campos obrigatórios!");
            return;
        }

        // Define a classe da badge correspondente baseada no tipo de fornecedor
        let classeBadge = "b-ingredientes";
        if (tipo === "Embalagens") classeBadge = "b-embalagens";
        else if (tipo === "Laticínios") classeBadge = "b-laticinios";
        else if (tipo === "Hortifruti") classeBadge = "b-hortifruti";
        else if (tipo === "Aromas") classeBadge = "b-aromas";

        // Cria uma nova linha física de tabela dinamicamente
        const novaLinha = document.createElement("tr");
        novaLinha.innerHTML = `
            <td>${proximoId}</td>
            <td><strong>${nome}</strong></td>
            <td><span class="badge-tipo ${classeBadge}">${tipo}</span></td>
            <td>${cnpj}</td>
            <td>${tel}</td>
            <td class="celula-acoes-tabela">
                <button type="button" class="btn-acao-linha edit"><svg style="width:12px;height:12px;stroke:#4b5563;stroke-width:2;fill:none;" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                <button type="button" class="btn-acao-linha del"><svg style="width:12px;height:12px;stroke:#4b5563;stroke-width:2;fill:none;" viewBox="0 0 24 24"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
            </td>
        `;

        corpoTabela.appendChild(novaLinha); // Injeta na tabela
        proximoId++; // Incrementa o gerador de IDs

        alert("Fornecedor cadastrado e integrado com sucesso!");
        btnLimpar.click(); // Reseta os campos
    });

    btnLimpar.addEventListener("click", function () {
        nomeFornecedor.value = "";
        tipoFornecedor.value = "";
        cnpjFornecedor.value = "";
        telFornecedor.value = "";
        document.getElementById("emailFornecedor").value = "";
        document.getElementById("ruaFornecedor").value = "";
        document.getElementById("numFornecedor").value = "";
        document.getElementById("bairroFornecedor").value = "";
        document.getElementById("cidadeFornecedor").value = "";
        document.getElementById("compFornecedor").value = "";
        document.getElementById("infoFornecedor").value = "";
    });
});

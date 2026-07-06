document.addEventListener("DOMContentLoaded", function () {
    const formCadastro = document.getElementById("formCadastroReceita");
    const nomeReceita = document.getElementById("nomeReceita");
    const ingredientesReceita = document.getElementById("ingredientesReceita");
    const preparoReceita = document.getElementById("preparoReceita");
    const corpoTabela = document.getElementById("corpoTabelaReceitas");
    const btnLimpar = document.getElementById("btnLimparForm");
    
    let proximoId = 103; // Inicia os cadastros simulados a partir do ID 103

    // Controla o envio do formulário de cadastro de novas receitas
    formCadastro.addEventListener("submit", function (evento) {
        evento.preventDefault(); // Impede o recarregamento automático do PHP

        const nome = nomeReceita.value.trim();
        const ingredientes = ingredientesReceita.value.trim();

        if (nome === "" || ingredientes === "") {
            alert("Por favor, preencha todos os campos obrigatórios!");
            return;
        }

        // Cria uma nova linha física de tabela dinamicamente
        const novaLinha = document.createElement("tr");
        novaLinha.innerHTML = `
            <td>${proximoId}</td>
            <td><strong>${nome}</strong></td>
            <td class="txt-truncado">${ingredientes.replace(/\n/g, ", ")}</td>
            <td class="celula-acoes-tabela">
                <button type="button" class="btn-acao-linha edit"><svg style="width:12px;height:12px;stroke:#4b5563;stroke-width:2;fill:none;" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                <button type="button" class="btn-acao-linha del"><svg style="width:12px;height:12px;stroke:#4b5563;stroke-width:2;fill:none;" viewBox="0 0 24 24"><polyline points="3 6 5 3 21 3 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
            </td>
        `;

        corpoTabela.appendChild(novaLinha); // Injeta a receita na tabela
        proximoId++; // Incrementa o gerador de identificadores

        // Dispara o alerta de sucesso e limpa os campos
        alert("Receita salva e integrada com sucesso!");
        btnLimpar.click();
    });

    // Controla o botão de limpar formulário
    btnLimpar.addEventListener("click", function () {
        nomeReceita.value = "";
        ingredientesReceita.value = "";
        preparoReceita.value = "";
    });
});

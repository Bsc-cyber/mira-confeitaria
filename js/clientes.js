/* ==========================================================================
   MIRA CONFEITARIA - OPERAÇÕES DO PAINEL DE CLIENTES
   ========================================================================== */

document.addEventListener("DOMContentLoaded", function () {
    
    // 1. CAPTURA DOS ELEMENTOS DA TELA
    const form = document.getElementById("formularioCliente");
    const btnLimpar = document.getElementById("btn-limpar");
    const btnExcluir = document.getElementById("btn-excluir");
    const inputAcao = document.getElementById("form_acao");
    const inputId = document.getElementById("cliente_id");

    // 2. AÇÃO DO BOTÃO "LIMPAR"
    if (btnLimpar) {
        btnLimpar.addEventListener("click", function () {
            form.reset(); // Limpa todos os campos digitados
            inputId.value = ""; // Zera o ID oculto (volta ao modo "Novo Cadastro")
            inputAcao.value = "salvar"; // Reseta a ação para salvar
        });
    }

    // 3. AÇÃO DO BOTÃO "EXCLUIR"
    if (btnExcluir) {
        btnExcluir.addEventListener("click", function () {
            if (inputId.value === "") {
                alert("⚠️ Selecione um cliente clicando no ícone do lápis na tabela antes de excluir!");
                return;
            }
            
            // Pede confirmação antes de apagar do banco
            if (confirm("Tem certeza que deseja EXCLUIR permanentemente este cliente?")) {
                inputAcao.value = "excluir"; // Muda a intenção do formulário para exclusão
                form.submit(); // Dispara o formulário para o PHP processar
            }
        });
    }

    // 4. PESQUISA RÁPIDA NA TABELA
    const inputPesquisa = document.querySelector(".wrapper-busca-tabela-clie input");
    if (inputPesquisa) {
        inputPesquisa.addEventListener("keyup", function () {
            const busca = this.value.toLowerCase().trim();
            const linhas = document.querySelectorAll(".tabela-dados-clientes tbody tr");
            
            linhas.forEach(linha => {
                // Ignora a linha de "Nenhum cliente cadastrado"
                if (linha.cells.length > 1) {
                    const textoLinha = linha.textContent.toLowerCase();
                    linha.style.display = textoLinha.includes(busca) ? "table-row" : "none";
                }
            });
        });
    }
});

// ==========================================================================
// FUNÇÃO PARA CARREGAR OS DADOS DO CLIENTE NO FORMULÁRIO
// ==========================================================================
// Esta função é chamada diretamente pelo atributo onclick no HTML (no botão do lápis)
window.carregarCliente = function(dados) {
    document.getElementById('cliente_id').value = dados.id;
    document.getElementById('nome').value = dados.nome;
    document.getElementById('telefone').value = dados.telefone || "";
    document.getElementById('cpf').value = dados.cpf || "";
    document.getElementById('data_nascimento').value = dados.data_nascimento || "";
    document.getElementById('cep').value = dados.cep || "";
    document.getElementById('rua').value = dados.rua || "";
    document.getElementById('numero').value = dados.numero || "";
    document.getElementById('bairro').value = dados.bairro || "";
    document.getElementById('complemento').value = dados.complemento || "";
    document.getElementById('cidade').value = dados.cidade || "";
    document.getElementById('email').value = dados.email || "";
    document.getElementById('observacoes').value = dados.observacoes || "";

    // Garante que ao salvar, ele fará um UPDATE e não um INSERT novo
    document.getElementById('form_acao').value = 'salvar';
    
    // Rola a tela para cima suavemente para o usuário ver o formulário preenchido
    document.querySelector('.wrapper-inputs-scroll-clientes').scrollTo({ top: 0, behavior: 'smooth' });
};
/* ==========================================================================
   MIRA CONFEITARIA - OPERAÇÕES DO PAINEL DE CLIENTES
   ========================================================================== */

document.addEventListener("DOMContentLoaded", function () {
    
    // 1. CAPTURA DOS ELEMENTOS DA TELA
    const form = document.getElementById("formularioCliente");
    const btnLimpar = document.getElementById("btn-limpar");
    const inputAcao = document.getElementById("form_acao");
    const inputId = document.getElementById("cliente_id");

    // 2. AÇÃO DO BOTÃO "LIMPAR" (Apenas Salvar e Limpar sobraram no form)
    if (btnLimpar) {
        btnLimpar.addEventListener("click", function () {
            form.reset(); 
            inputId.value = ""; 
            inputAcao.value = "salvar"; 
            
            // Remove a seleção visual de qualquer linha da tabela
            document.querySelectorAll('.linha-tabela-clie').forEach(linha => {
                linha.classList.remove('linha-ativa');
            });
        });
    }

    // 3. PESQUISA RÁPIDA NA TABELA
    const inputPesquisa = document.querySelector(".wrapper-busca-tabela-clie input");
    if (inputPesquisa) {
        inputPesquisa.addEventListener("keyup", function () {
            const busca = this.value.toLowerCase().trim();
            const linhas = document.querySelectorAll(".tabela-dados-clientes tbody tr");
            
            linhas.forEach(linha => {
                if (linha.cells.length > 1) {
                    const textoLinha = linha.textContent.toLowerCase();
                    linha.style.display = textoLinha.includes(busca) ? "table-row" : "none";
                }
            });
        });
    }
});

// ==========================================================================
// FUNÇÃO PARA CARREGAR OS DADOS NO FORMULÁRIO E MARCAR A LINHA
// ==========================================================================
window.carregarCliente = function(dados, linhaElemento) {
    document.querySelectorAll('.linha-tabela-clie').forEach(linha => {
        linha.classList.remove('linha-ativa');
    });
    
    if(linhaElemento) {
        linhaElemento.classList.add('linha-ativa');
    }

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

    document.getElementById('form_acao').value = 'salvar';
    
    document.querySelector('.wrapper-inputs-scroll-clientes').scrollTo({ top: 0, behavior: 'smooth' });
};

// ==========================================================================
// FUNÇÃO PARA EXCLUIR CLIENTE DIRETAMENTE DA TABELA (BOTÃO LIXEIRA)
// ==========================================================================
window.excluirClienteDireto = function(id) {
    if (confirm("Tem certeza que deseja EXCLUIR permanentemente este cliente do sistema?")) {
        document.getElementById('cliente_id').value = id;
        document.getElementById('form_acao').value = 'excluir';
        document.getElementById('formularioCliente').submit();
    }
};
/* ==========================================================================
   MIRA CONFEITARIA - LOGICA OPERACIONAL DE ALTERNÂNCIA DE ABAS
   ========================================================================== */

document.addEventListener("DOMContentLoaded", function () {
    // Captura de todos os botões do menu da esquerda e dos painéis da direita
    const botoesAbas = document.querySelectorAll(".btn-aba-item");
    const paineisSecoes = document.querySelectorAll(".painel-config-secao");

    botoesAbas.forEach(botao => {
        botao.addEventListener("click", function () {
            // Remove a classe 'ativa' de todos os botões da lista esquerda
            botoesAbas.forEach(b => b.classList.remove("ativa"));
            
            // Remove a visibilidade de todos os blocos de formulários da direita
            paineisSecoes.forEach(p => p.classList.remove("ativa-painel"));

            // Adiciona o estado selecionado ao botão que acabou de sofrer o clique
            this.classList.add("ativa");

            // Localiza qual o ID do alvo correspondente através do atributo data-alvo
            const idAlvo = this.getAttribute("data-alvo");
            const painelAlvo = document.getElementById(idAlvo);

            if (painelAlvo) {
                // Projeta na tela o formulário selecionado
                painelAlvo.classList.add("ativa-painel");
            }
        });
    });
});

// ==========================================================================
// MIRA CONFEITARIA - OPERAÇÕES DO PAINEL DE CLIENTES
// ==========================================================================

// 1. ANIMAÇÃO DE EXPANSÃO DA BARRA LATERAL (CORREÇÃO DA ABERTURA)
const sidebar = document.getElementById('sidebar');

if (sidebar) {
    // Quando o mouse passa por cima, adiciona a classe que abre a barra
    sidebar.addEventListener('mouseenter', () => {
        sidebar.classList.add('expanded');
    });
    
    // Quando o mouse sai de cima, remove a classe e encolhe a barra
    sidebar.addEventListener('mouseleave', () => {
        sidebar.classList.remove('expanded');
    });
}

// 2. INTELIGÊNCIA DE CLIQUES DOS BOTÕES DO FORMULÁRIO (FUNDO VERDE / CLIQUE BRANCO)
const botoesAcao = document.querySelectorAll('.form-actions-4 .btn');

botoesAcao.forEach(botao => {
    botao.addEventListener('click', function() {
        // Remove a cor branca ativa de todos os botões para resetar o estado
        botoesAcao.forEach(b => b.classList.remove('active-click'));
        
        // Adiciona a classe que deixa o fundo branco e borda dourada no botão que você clicou
        this.classList.add('active-click');
    });
});

// ==========================================================================
// MIRA CONFEITARIA - GESTÃO DO CARDÁPIO DE PRODUTOS
// ==========================================================================

// 1. Controle de Expansão Lateral da Barra de Menu
const sidebar = document.getElementById('sidebar');
if (sidebar) {
    sidebar.addEventListener('mouseenter', () => sidebar.classList.add('expanded'));
    sidebar.addEventListener('mouseleave', () => sidebar.classList.remove('expanded'));
}

// 2. Inteligência de Cliques: Deixa o botão clicado Branco e os outros Verdes
const botoesAcao = document.querySelectorAll('.form-actions-4 .btn');

botoesAcao.forEach(botao => {
    botao.addEventListener('click', function() {
        // Remove a cor branca ativa de todos os botões para resetar
        botoesAcao.forEach(b => b.classList.remove('active-click'));
        
        // Adiciona o fundo branco com contorno dourado no botão que recebeu o clique
        this.classList.add('active-click');
    });
});

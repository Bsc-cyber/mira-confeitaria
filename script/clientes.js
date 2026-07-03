// ==========================================================================
// MIRA CONFEITARIA - OPERAÇÕES DO PAINEL DE CLIENTES
// ==========================================================================

// 1. ANIMAÇÃO DE EXPANSÃO DA BARRA LATERAL (CORREÇÃO DA ABERTURA)
var sidebar = document.getElementById('sidebar');

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
var botoesAcao = document.querySelectorAll('.form-actions-4 .btn');

botoesAcao.forEach(botao => {
    botao.addEventListener('click', function() {
        // Remove a cor branca ativa de todos os botões para resetar o estado
        botoesAcao.forEach(b => b.classList.remove('active-click'));
        
        // Adiciona a classe que deixa o fundo branco e borda dourada no botão que você clicou
        this.classList.add('active-click');
    });
});

// Preenche o formulário ao clicar em uma linha da tabela
function carregarCliente(dados) {
    document.getElementById('cliente_id').value = dados.id;
    document.getElementById('nome').value = dados.nome;
    
    // O '|| ""' evita que apareça "null" caso o cliente não tenha telefone cadastrado, por exemplo
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

    // Garante que a ação principal é salvar (ou atualizar, já que o ID está preenchido)
    document.getElementById('form_acao').value = 'salvar';

    // Se você tiver botões de editar/excluir desabilitados, habilite-os aqui:
    // document.getElementById('btn-editar').disabled = false;
    // document.getElementById('btn-excluir').disabled = false;
}
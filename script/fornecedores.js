// ==========================================================================
// MIRA CONFEITARIA - OPERAÇÕES DE FORNECEDORES VIA JAVASCRIPT & AJAX (60/40)
// ==========================================================================

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById('sidebar');
    const inputBusca = document.getElementById('searchSupplierInput');
    const botoesAcao = document.querySelectorAll('.form-actions-4 .btn');
    
    // Variável global para armazenar o ID do fornecedor selecionado na tabela
    window.fornecedorSelecionadoId = null;

    // 1. ANIMAÇÃO DE EXPANSÃO SUAVE DA BARRA LATERAL (FLUTUANTE)
    if (sidebar) {
        sidebar.addEventListener('mouseenter', () => {
            sidebar.classList.add('expanded');
        });
        sidebar.addEventListener('mouseleave', () => {
            sidebar.classList.remove('expanded');
        });
    }

    // 2. INTELIGÊNCIA DE BUSCA INSTANTÂNEA EM TEMPO REAL (ESTILO CHROME)
    if (inputBusca) {
        inputBusca.addEventListener('input', () => {
            const termoBusca = inputBusca.value.toLowerCase();
            const linhasFornecedores = document.querySelectorAll('#suppliersTable tbody tr.forn-row');

            linhasFornecedores.forEach(linha => {
                const celulaNome = linha.querySelector('.item-name-cell');
                if (celulaNome) {
                    const textoNome = celulaNome.textContent.toLowerCase();
                    linha.style.display = textoNome.includes(termoBusca) ? "" : "none";
                }
            });
        });
    }

    // 3. DINÂMICA DE CLIQUE: Captura os dados do PDO e joga nas caixas de texto
    window.ativarCliquesTabelaFornecedores = function() {
        const linhasFornecedores = document.querySelectorAll('#suppliersTable tbody tr.forn-row');

        linhasFornecedores.forEach(linha => {
            linha.addEventListener('click', function() {
                // Destaca visualmente a linha selecionada na tabela
                linhasFornecedores.forEach(l => l.style.backgroundColor = "");
                this.style.backgroundColor = "rgba(209, 180, 140, 0.15)";

                // Grava o ID do item clicado na nossa variável de controle
                window.fornecedorSelecionadoId = this.getAttribute('data-id');

                // Alimenta cirurgicamente todos os inputs do formulário esquerdo
                document.getElementById('forn_nome').value = this.getAttribute('data-nome') || '';
                document.getElementById('forn_tipo').value = this.getAttribute('data-tipo') || '';
                document.getElementById('forn_cpf_cnpj').value = this.getAttribute('data-cpf_cnpj') || '';
                document.getElementById('forn_telefone').value = this.getAttribute('data-telefone') || '';
                document.getElementById('forn_email').value = this.getAttribute('data-email') || '';
                document.getElementById('forn_rua').value = this.getAttribute('data-rua') || '';
                document.getElementById('forn_numero').value = this.getAttribute('data-numero') || '';
                document.getElementById('forn_bairro').value = this.getAttribute('data-bairro') || '';
                document.getElementById('forn_complemento').value = this.getAttribute('data-complemento') || '';
                document.getElementById('forn_cidade_estado').value = this.getAttribute('data-cidade_estado') || '';
                document.getElementById('forn_notas').value = this.getAttribute('data-notas') || '';

                // Limpa focos visuais dos botões ao selecionar outra empresa
                botoesAcao.forEach(b => b.classList.remove('active-click'));
            });
        });
    };

    // Inicializa a escuta de cliques da listagem
    ativarCliquesTabelaFornecedores();
    // 4. FUNÇÃO AUXILIAR: LIMPAR FORMULÁRIO E RESETAR VARIÁVEIS
    function limparFormularioFornecedores() {
        document.getElementById('supplierForm').reset();
        window.fornecedorSelecionadoId = null;
        
        const linhasFornecedores = document.querySelectorAll('#suppliersTable tbody tr.forn-row');
        linhasFornecedores.forEach(l => l.style.backgroundColor = "");
        
        botoesAcao.forEach(b => b.classList.remove('active-click'));
    }

    // 5. PROCESSAMENTO DOS BOTÕES DE AÇÃO (SALVAR, EDITAR E EXCLUIR VIA PDO)
    botoesAcao.forEach(botao => {
        botao.addEventListener('click', function() {
            const acaoSolicitada = this.textContent.trim().toLowerCase();
            
            botoesAcao.forEach(b => b.classList.remove('active-click'));
            this.classList.add('active-click');

            if (acaoSolicitada === 'limpar') {
                limparFormularioFornecedores();
                return;
            }

            const dadosFormulario = new FormData();
            dadosFormulario.append('acao', acaoSolicitada);
            dadosFormulario.append('id', window.fornecedorSelecionadoId || '');
            dadosFormulario.append('nome', document.getElementById('forn_nome').value);
            dadosFormulario.append('tipo', document.getElementById('forn_tipo').value);
            dadosFormulario.append('cpf_cnpj', document.getElementById('forn_cpf_cnpj').value);
            dadosFormulario.append('telefone', document.getElementById('forn_telefone').value);
            dadosFormulario.append('email', document.getElementById('forn_email').value);
            dadosFormulario.append('rua', document.getElementById('forn_rua').value);
            dadosFormulario.append('numero', document.getElementById('forn_numero').value);
            dadosFormulario.append('bairro', document.getElementById('forn_bairro').value);
            dadosFormulario.append('complemento', document.getElementById('forn_complemento').value);
            dadosFormulario.append('cidade_estado', document.getElementById('forn_cidade_estado').value);
            dadosFormulario.append('notas', document.getElementById('forn_notas').value);

            // Validações de segurança antes de disparar a requisição
            if ((acaoSolicitada === 'editar' || acaoSolicitada === 'excluir') && !window.fornecedorSelecionadoId) {
                alert('Por favor, selecione primeiro um fornecedor na tabela da direita para efetuar esta ação.');
                this.classList.remove('active-click');
                return;
            }

            if (acaoSolicitada === 'salvar' && document.getElementById('forn_nome').value.trim() === "") {
                alert('O nome/razão social é obrigatório para realizar o cadastro.');
                this.classList.remove('active-click');
                return;
            }

            if (acaoSolicitada === 'excluir' && !confirm('Tem certeza que deseja remover este fornecedor permanentemente do sistema?')) {
                this.classList.remove('active-click');
                return;
            }

            // Dispara a requisição Fetch AJAX para a própria página fornecedores.php processar
            fetch('fornecedores.php', {
                method: 'POST',
                body: dadosFormulario
            })
            .then(resposta => resposta.text())
            .then(conteudoHTML => {
                const parserHTML = new DOMParser();
                const documentoFormatado = parserHTML.parseFromString(conteudoHTML, 'text/html');
                
                const novaTabelaCorpo = documentoFormatado.querySelector('#suppliersTable tbody');
                const tabelaAtualCorpo = document.querySelector('#suppliersTable tbody');

                if (novaTabelaCorpo && tabelaAtualCorpo) {
                    tabelaAtualCorpo.innerHTML = novaTabelaCorpo.innerHTML;
                    
                    // Reativa imediatamente as escutas de clique na tabela atualizada
                    window.ativarCliquesTabelaFornecedores();
                    
                    limparFormularioFornecedores();
                    alert('Operação de ' + acaoSolicitada + ' executada no banco com sucesso!');
                } else {
                    alert('Erro de comunicação: Não foi possível atualizar a tabela de fornecedores.');
                }
            })
            .catch(erroConexao => {
                console.error('Falha na requisição Fetch:', erroConexao);
                alert('Erro crítico ao tentar conectar e salvar as informações no servidor local.');
            });
        });
    });

    // Garante a ativação inicial dos cliques
    window.ativarCliquesTabelaFornecedores();
});

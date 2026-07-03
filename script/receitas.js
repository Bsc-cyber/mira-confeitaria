// ==========================================================================
// MIRA CONFEITARIA - GESTÃO DE RECEITAS VIA JAVASCRIPT & AJAX (60/40)
// ==========================================================================

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById('sidebar');
    const inputBusca = document.getElementById('searchRecipeInput');
    const botoesAcao = document.querySelectorAll('.form-actions-4 .btn');
    
    // Variável global para rastrear o ID da receita selecionada na tabela
    window.receitaSelecionadaId = null;

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
            const linhasReceitas = document.querySelectorAll('#recipesTable tbody tr.rec-row');

            linhasReceitas.forEach(linha => {
                const celulaNome = linha.querySelector('.item-name-cell');
                if (celulaNome) {
                    const textoNome = celulaNome.textContent.toLowerCase();
                    linha.style.display = textoNome.includes(termoBusca) ? "" : "none";
                }
            });
        });
    }

    // 3. DINÂMICA DE CLIQUE: Puxa os dados do MySQL e injeta nos campos de texto
    window.ativarCliquesTabelaReceitas = function() {
        const linhasReceitas = document.querySelectorAll('#recipesTable tbody tr.rec-row');

        linhasReceitas.forEach(linha => {
            linha.addEventListener('click', function() {
                // Destaca visualmente a linha selecionada na tabela
                linhasReceitas.forEach(l => l.style.backgroundColor = "");
                this.style.backgroundColor = "rgba(209, 180, 140, 0.15)";

                // Armazena o ID do item clicado na nossa variável de controle
                window.receitaSelecionadaId = this.getAttribute('data-id');

                // Alimenta os inputs e textareas detalhados na esquerda
                document.getElementById('rec_nome').value = this.getAttribute('data-nome') || '';
                document.getElementById('rec_tempo').value = this.getAttribute('data-tempo') || '';
                document.getElementById('rec_rendimento').value = this.getAttribute('data-rendimento') || '';
                document.getElementById('rec_ingredientes').value = this.getAttribute('data-ingredientes') || '';
                document.getElementById('rec_preparo').value = this.getAttribute('data-preparo') || '';

                // Limpa focos visuais dos botões ao selecionar outra receita
                botoesAcao.forEach(b => b.classList.remove('active-click'));
            });
        });
    };

    // Inicializa a escuta de cliques da listagem de receitas
    ativarCliquesTabelaReceitas();
    // 4. FUNÇÃO AUXILIAR: LIMPAR FORMULÁRIO E RESETAR VARIÁVEIS
    function limparFormularioReceitas() {
        document.getElementById('recipeForm').reset();
        window.receitaSelecionadaId = null;
        
        const linhasReceitas = document.querySelectorAll('#recipesTable tbody tr.rec-row');
        linhasReceitas.forEach(l => l.style.backgroundColor = "");
        
        botoesAcao.forEach(b => b.classList.remove('active-click'));
    }

    // 5. PROCESSAMENTO DOS BOTÕES DE AÇÃO (SALVAR, EDITAR E EXCLUIR VIA PDO)
    botoesAcao.forEach(botao => {
        botao.addEventListener('click', function() {
            const acaoSolicitada = this.textContent.trim().toLowerCase();
            
            botoesAcao.forEach(b => b.classList.remove('active-click'));
            this.classList.add('active-click');

            if (acaoSolicitada === 'limpar') {
                limparFormularioReceitas();
                return;
            }

            const dadosFormulario = new FormData();
            dadosFormulario.append('acao', acaoSolicitada);
            dadosFormulario.append('id', window.receitaSelecionadaId || '');
            dadosFormulario.append('nome', document.getElementById('rec_nome').value);
            dadosFormulario.append('tempo', document.getElementById('rec_tempo').value);
            dadosFormulario.append('rendimento', document.getElementById('rec_rendimento').value);
            dadosFormulario.append('ingredientes', document.getElementById('rec_ingredientes').value);
            dadosFormulario.append('preparo', document.getElementById('rec_preparo').value);

            // Validações de segurança antes de disparar a requisição
            if ((acaoSolicitada === 'editar' || acaoSolicitada === 'excluir') && !window.receitaSelecionadaId) {
                alert('Por favor, selecione primeiro uma receita na tabela da direita para efetuar esta ação.');
                this.classList.remove('active-click');
                return;
            }

            if (acaoSolicitada === 'salvar' && document.getElementById('rec_nome').value.trim() === "") {
                alert('O nome da receita é obrigatório para realizar o cadastro.');
                this.classList.remove('active-click');
                return;
            }

            if (acaoSolicitada === 'excluir' && !confirm('Tem certeza que deseja remover esta receita permanentemente do sistema?')) {
                this.classList.remove('active-click');
                return;
            }

            // Dispara a requisição Fetch AJAX para a própria página receitas.php processar
            fetch('receitas.php', {
                method: 'POST',
                body: dadosFormulario
            })
            .then(resposta => resposta.text())
            .then(conteudoHTML => {
                const parserHTML = new DOMParser();
                const documentoFormatado = parserHTML.parseFromString(conteudoHTML, 'text/html');
                
                const novaTabelaCorpo = documentoFormatado.querySelector('#recipesTable tbody');
                const tabelaAtualCorpo = document.querySelector('#recipesTable tbody');

                if (novaTabelaCorpo && tabelaAtualCorpo) {
                    tabelaAtualCorpo.innerHTML = novaTabelaCorpo.innerHTML;
                    
                    // Reativa imediatamente as escutas de clique na tabela atualizada
                    window.ativarCliquesTabelaReceitas();
                    
                    limparFormularioReceitas();
                    alert('Operação de ' + acaoSolicitada + ' executada no banco com sucesso!');
                } else {
                    alert('Erro de comunicação: Não foi possível atualizar a tabela de receitas.');
                }
            })
            .catch(erroConexao => {
                console.error('Falha na requisição Fetch:', erroConexao);
                alert('Erro crítico ao tentar conectar e salvar as informações no servidor local.');
            });
        });
    });

    // Garante a ativação inicial dos cliques
    window.ativarCliquesTabelaReceitas();
});

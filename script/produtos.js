// ==========================================================================
// MIRA CONFEITARIA - OPERAÇÕES DO BANCO DE DADOS E INTERAÇÕES (COMPLETO)
// ==========================================================================

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById('sidebar');
    const inputBusca = document.getElementById('searchProductInput');
    const botoesAcao = document.querySelectorAll('.form-actions-4 .btn');
    
    // Variável global para armazenar o ID do doce selecionado ao clicar na tabela
    window.produtoSelecionadoId = null;

    // 1. CONTROLE DE EXPANSÃO LATERAL DA BARRA DE MENU (CORRIGIDO)
    if (sidebar) {
        sidebar.addEventListener('mouseenter', () => {
            sidebar.classList.add('expanded');
        });
        sidebar.addEventListener('mouseleave', () => {
            sidebar.classList.remove('expanded');
        });
    }

    // 2. Inteligência de Busca Instantânea em Tempo Real (Estilo Chrome)
    if (inputBusca) {
        inputBusca.addEventListener('input', () => {
            const termoBusca = inputBusca.value.toLowerCase();
            const linhasProdutos = document.querySelectorAll('#productsTable tbody tr.prod-row');

            linhasProdutos.forEach(linha => {
                const celulaNome = linha.querySelector('.item-name-cell');
                if (celulaNome) {
                    const textoNome = celulaNome.textContent.toLowerCase();
                    linha.style.display = textoNome.includes(termoBusca) ? "" : "none";
                }
            });
        });
    }

    // 3. COMUNICAÇÃO DE CLIQUE: Captura os dados da tabela MySQL e joga no formulário
    window.ativarCliquesTabelaReal = function() {
        const linhasProdutos = document.querySelectorAll('#productsTable tbody tr.prod-row');

        linhasProdutos.forEach(linha => {
            linha.addEventListener('click', function() {
                // Remove destaque visual de linhas antigas e marca a linha selecionada
                linhasProdutos.forEach(l => l.style.backgroundColor = "");
                this.style.backgroundColor = "rgba(209, 180, 140, 0.15)";

                // Grava o ID do item clicado na nossa variável de controle
                window.produtoSelecionadoId = this.getAttribute('data-id');

                // Alimenta os campos do formulário esquerdo com os dados do banco
                document.getElementById('prod_nome').value = this.getAttribute('data-nome') || '';
                document.getElementById('prod_categoria').value = this.getAttribute('data-categoria') || '';
                document.getElementById('prod_tamanho').value = this.getAttribute('data-tamanho') || '';
                document.getElementById('prod_preco').value = this.getAttribute('data-preco') || '';
                document.getElementById('prod_ativo').value = this.getAttribute('data-ativo') || 'sim';
                document.getElementById('prod_sabores').value = this.getAttribute('data-sabores') || '';
                document.getElementById('prod_descricao').value = this.getAttribute('data-descricao') || '';

                // Reseta o destaque de foco dos botões ao selecionar um novo item
                botoesAcao.forEach(b => b.classList.remove('active-click'));
            });
        });
    };

    // 4. FUNÇÃO AUXILIAR: LIMPAR FORMULÁRIO E RESETAR ID SELECIONADO
    function limparFormularioProdutos() {
        document.getElementById('productForm').reset();
        window.produtoSelecionadoId = null;
        
        const linhasProdutos = document.querySelectorAll('#productsTable tbody tr.prod-row');
        linhasProdutos.forEach(l => l.style.backgroundColor = "");
        
        botoesAcao.forEach(b => b.classList.remove('active-click'));
    }

    // 5. INTELIGÊNCIA DOS BOTÕES DE AÇÃO: DISPAROS EM TEMPO REAL PARA O BANCO (PDO)
    botoesAcao.forEach(botao => {
        botao.addEventListener('click', function() {
            const acaoSolicitada = this.textContent.trim().toLowerCase();
            
            botoesAcao.forEach(b => b.classList.remove('active-click'));
            this.classList.add('active-click');

            if (acaoSolicitada === 'limpar') {
                limparFormularioProdutos();
                return;
            }

            const dadosFormulario = new FormData();
            dadosFormulario.append('acao', acaoSolicitada);
            dadosFormulario.append('id', window.produtoSelecionadoId || '');
            dadosFormulario.append('nome', document.getElementById('prod_nome').value);
            dadosFormulario.append('categoria', document.getElementById('prod_categoria').value);
            dadosFormulario.append('tamanho', document.getElementById('prod_tamanho').value);
            dadosFormulario.append('preco', document.getElementById('prod_preco').value);
            dadosFormulario.append('ativo', document.getElementById('prod_ativo').value);
            dadosFormulario.append('sabores', document.getElementById('prod_sabores').value);
            dadosFormulario.append('descricao', document.getElementById('prod_descricao').value);

            if ((acaoSolicitada === 'editar' || acaoSolicitada === 'excluir') && !window.produtoSelecionadoId) {
                alert('Por favor, selecione primeiro um produto na tabela da direita para efetuar esta ação.');
                this.classList.remove('active-click');
                return;
            }

            if (acaoSolicitada === 'salvar' && document.getElementById('prod_nome').value.trim() === "") {
                alert('O nome do produto é obrigatório para realizar o cadastro.');
                this.classList.remove('active-click');
                return;
            }

            if (acaoSolicitada === 'excluir' && !confirm('Tem certeza absoluta que deseja remover este produto permanentemente do cardápio?')) {
                this.classList.remove('active-click');
                return;
            }

            // Envia a requisição invisível via Fetch API para a própria página produtos.php
            fetch('produtos.php', {
                method: 'POST',
                body: dadosFormulario
            })
            .then(resposta => resposta.text())
            .then(conteudoHTML => {
                const parserHTML = new DOMParser();
                const documentoFormatado = parserHTML.parseFromString(conteudoHTML, 'text/html');
                
                const novaTabelaCorpo = documentoFormatado.querySelector('#productsTable tbody');
                const tabelaAtualCorpo = document.querySelector('#productsTable tbody');

                if (novaTabelaCorpo && tabelaAtualCorpo) {
                    tabelaAtualCorpo.innerHTML = novaTabelaCorpo.innerHTML;
                    
                    // Reativa imediatamente os gatilhos de clique na nova listagem renderizada
                    window.ativarCliquesTabelaReal();
                    
                    limparFormularioProdutos();
                    alert('Operação de ' + acaoSolicitada + ' processada e gravada no banco com sucesso!');
                } else {
                    alert('Erro de comunicação: Não foi possível atualizar a tabela de listagem.');
                }
            })
            .catch(erroConexao => {
                console.error('Falha na requisição Fetch:', erroConexao);
                alert('Erro crítico ao tentar conectar e salvar as informações no servidor local.');
            });
        });
    });

    // Inicializa a escuta de cliques da tabela na primeira carga
    window.ativarCliquesTabelaReal();
});

/* ==========================================================================
   MIRA CONFEITARIA - OPERAÇÃO COMPLETA DE MODAIS SEM SAIR DA PÁGINA (AJAX)
   ========================================================================== */

document.addEventListener("DOMContentLoaded", function () {
    
    // 1. MAPEAMENTO DOS INPUTS OPERACIONAIS DO FORMULÁRIO (COLUNA ESQUERDA)
    const campoId = document.getElementById("id_usuario");
    const campoNome = document.getElementById("nome_completo_input");
    const campoUsuario = document.getElementById("usuario_input");
    const campoSenha = document.getElementById("senha_input");
    const campoPermissao = document.getElementById("permissao_input");
    const botaoSalvar = document.getElementById("btnSalvarUsuarioForm");

    // 2. FUNÇÃO AUXILIAR PARA CONTROLAR OS EVENTOS DE CLIQUE NA TABELA (COLUNA DIREITA)
    function ligarEventosTabela() {
        const linhas = document.querySelectorAll("#listaUsuariosTabela tr");
        
        linhas.forEach(linha => {
            linha.onclick = function () {
                // Captura os atributos de dados injetados pelo PHP dinamicamente
                const id = this.getAttribute("data-id");
                const nome = this.getAttribute("data-nome");
                const usuario = this.getAttribute("data-usuario");
                const permissao = this.getAttribute("data-permissao");

                // Preenche os campos do formulário da esquerda de forma automática
                if (campoId) campoId.value = id;
                if (campoNome) campoNome.value = nome;
                if (campoUsuario) campoUsuario.value = usuario;
                if (campoSenha) campoSenha.value = ""; // Limpa a senha por segurança ao editar
                if (campoPermissao) campoPermissao.value = permissao;

                // Altera o texto e estilo do botão para indicar modo de atualização
                if (botaoSalvar) {
                    const spanElement = botaoSalvar.querySelector("span");
                    if (spanElement) spanElement.innerText = "Atualizar Usuário";
                    botaoSalvar.style.backgroundColor = "#1b2518"; // Verde oliva escuro de edição
                }
                
                console.log(`Modo edição ativado para o usuário ID: ${id}`);
            };
        });

        // 3. AÇÃO DO BOTÃO EXCLUIR REGISTRO VIA FETCH ASSÍNCRONO
        const botoesExcluir = document.querySelectorAll(".btn-excluir-linha");
        if (botoesExcluir.length > 0) {
            botoesExcluir.forEach(btn => {
                btn.onclick = function (e) {
                    e.stopPropagation(); // Impede o clique na linha de carregar o formulário junto
                    
                    const id = this.getAttribute("data-id");
                    const linhaPai = this.closest("tr");
                    const nomeUser = linhaPai ? linhaPai.getAttribute("data-nome") : "Usuário";

                    if (confirm(`Tem certeza que deseja remover permanentemente o usuário "${nomeUser}"?`)) {
                        fetch(`logica_php/deletar_usuario.php?id=${id}`)
                        .then(res => res.json())
                        .then(dados => {
                            alert(dados.mensagem);
                            if (dados.sucesso && linhaPai) {
                                linhaPai.remove(); // Remove a linha da tabela na hora sem atualizar a página
                            }
                        })
                        .catch(err => alert("Erro ao conectar com o servidor local do XAMPP."));
                    }
                };
            });
        }
    }

    // Inicializa a escuta de cliques da tabela ao carregar a página
    ligarEventosTabela();

    // 4. ENVIO DO FORMULÁRIO (SALVAR OU ATUALIZAR) VIA AJAX FETCH API (SEGUNDO PLANO)
    if (botaoSalvar) {
        botaoSalvar.onclick = function (e) {
            e.preventDefault();

            // Validação simples de segurança antes do envio
            if (!campoNome || !campoUsuario || !campoNome.value || !campoUsuario.value) {
                alert("Nome Completo e Usuário de Acesso são obrigatórios!");
                return;
            }

            const dadosForm = new FormData();
            if (campoId && campoId.value) dadosForm.append('id', campoId.value);
            dadosForm.append('nome', campoNome.value);
            dadosForm.append('email', campoUsuario.value);
            dadosForm.append('senha', campoSenha ? campoSenha.value : '');
            dadosForm.append('permissao', campoPermissao ? campoPermissao.value : 'balcao');

            // Dispara a requisição em AJAX de forma silenciosa por trás dos panos
            fetch('logica_php/salvar_usuario.php', {
                method: 'POST',
                body: dadosForm
            })
            .then(res => res.json())
            .then(dados => {
                alert(dados.mensagem); // Exibe a janelinha com a mensagem do PHP (Cadastrado/Atualizado)
                if (dados.sucesso) {
                    window.location.reload(); // Recarrega o painel de configurações para trazer a tabela atualizada
                }
            })
            .catch(err => alert("Erro ao enviar dados assíncronos para salvar no XAMPP."));
        };
    }
});

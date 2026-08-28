<?php 
// 1. REGRA DE SEGURANÇA: Valida se o usuário fez login antes de desenhar a página
require_once "logica_php/home.php"; 

// 2. CRIAÇÃO DAS GAVETAS COM VALORES PADRÃO (Essenciais para o HTML ler sem dar erro)
$nome_conf = "MIRA Confeitaria"; 
$cnpj_conf = "000.000.000/0001-00";
$telefone_conf = "(31) 99876-5432";
$email_conf = "contato@miraconfeitaria.com.br";
$endereco_conf = "Rua das Flores - Centro";
$cidade_estado_conf = "Belo Horizonte - MG";
$hora_abertura_conf = "08:00";
$hora_fechamento_conf = "18:00";

// 3. CONSULTA AO BANCO DE DADOS: Atualiza as gavetas se encontrar dados na tabela
try {
    $pdo_info = new PDO("mysql:host=localhost;dbname=mira_confeitaria;charset=utf8", "root", "");
    $pdo_info->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Busca o registro de configurações
    $stmt_info = $pdo_info->query("SELECT * FROM info_confeitaria LIMIT 1");
    $dados_conf = $stmt_info->fetch(PDO::FETCH_ASSOC);

    // Se achou dados reais salvos por você, joga nas gavetas
    if ($dados_conf) {
        $nome_conf = $dados_conf['nome_confeitaria'];
        $cnpj_conf = $dados_conf['cnpj'];
        $telefone_conf = $dados_conf['telefone'];
        $email_conf = $dados_conf['email'];
        $endereco_conf = $dados_conf['endereco'];
        $cidade_estado_conf = $dados_conf['cidade_estado'];
        
        if (!empty($dados_conf['hora_abertura'])) {
            $hora_abertura_conf = date("H:i", strtotime($dados_conf['hora_abertura']));
        }
        if (!empty($dados_conf['hora_fechamento'])) {
            $hora_fechamento_conf = date("H:i", strtotime($dados_conf['hora_fechamento']));
        }
    }
} catch (Exception $e) {
    // Se a tabela 'info_confeitaria' ainda não existir, o PHP ignora e mantém os padrões acima
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Configurações</title>
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <link rel="stylesheet" href="../css/configuracoes.css?v=1.4">
</head>
<body>

    <?php 
    // Injeta dinamicamente a barra lateral de navegação
    require_once "barra_lateral.php"; 
    ?>

    <main class="painel-conteudo-config">
        
        <!-- Formulário mestre apontando para a rota correta de salvamento -->
        <form id="formularioConfiguracoes" method="POST" action="logica_php/salvar_configuracoes.php" novalidate style="display: flex; flex-direction: column; height: 100%; width: 100%;">

            <!-- Cabeçalho Superior -->
            <header class="topo-config">
                <div class="titulo-pagina-config">
                    <div class="icone-titulo-conf">
                        <svg class="svg-topo-conf" xmlns="http://w3.org" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    </div>
                    <div class="alinhamento-texto-topo">
                        <h1>Configurações</h1>
                        <p>Gerencie as configurações do sistema da sua confeitaria.</p>
                    </div>
                </div>

                <button type="submit" class="btn-salvar-alteracoes">
                    <svg xmlns="http://w3.org" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Salvar alterações
                </button>
            </header>

            <div class="wrapper-scroll-configuracoes">
                
                <!-- 1. GRANDE CARD: INFORMAÇÕES DA CONFEITARIA -->
                <div class="card-config-bloco">
                    <h3 class="titulo-secao-config">
                        <svg class="svg-secao-config" xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Informações da Confeitaria
                    </h3>
                    <p class="subtitulo-secao-config">Atualize os dados principais da sua confeitaria.</p>

                    <div class="linha-campos-config-tripla">
                        <div class="grupo-campo-config">
                            <label>Nome da Confeitaria</label>
                            <input type="text" name="nome_confeitaria" id="nome_confeitaria" value="<?php echo htmlspecialchars($nome_conf); ?>" placeholder="Digite o nome comercial">
                        </div>
                        <div class="grupo-campo-config">
                            <label>CNPJ <span class="opcional-texto">(opcional)</span></label>
                            <input type="text" name="cnpj" id="cnpj" value="<?php echo htmlspecialchars($cnpj_conf); ?>" placeholder="00.000.000/0000-00">
                        </div>
                        <div class="grupo-campo-config">
                            <label>Telefone</label>
                            <input type="text" name="telefone_confeitaria" id="telefone_confeitaria" value="<?php echo htmlspecialchars($telefone_conf); ?>" placeholder="(00) 00000-0000">
                        </div>
                    </div>

                    <div class="linha-campos-config-tripla">
                        <div class="grupo-campo-config">
                            <label>E-mail</label>
                            <input type="email" name="email_confeitaria" id="email_confeitaria" value="<?php echo htmlspecialchars($email_conf); ?>" placeholder="exemplo@empresa.com">
                        </div>
                        <div class="grupo-campo-config">
                            <label>Endereço</label>
                            <input type="text" name="endereco_confeitaria" id="endereco_confeitaria" value="<?php echo htmlspecialchars($endereco_conf); ?>" placeholder="Rua, número, bairro">
                        </div>
                        <div class="grupo-campo-config">
                            <label>Cidade / Estado</label>
                            <input type="text" name="cidade_estado" id="cidade_estado" value="<?php echo htmlspecialchars($cidade_estado_conf); ?>" placeholder="Cidade - UF">
                        </div>
                    </div>

                    <div class="secao-horario-funcionamento">
                        <label class="label-horario-titulo">Horário de Funcionamento</label>
                        <div class="linha-selecao-horario">
                            <svg xmlns="http://w3.org" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>Segunda a Sábado</span>
                            <input type="time" name="hora_abertura" id="hora_abertura" value="<?php echo $hora_abertura_conf; ?>">
                            <span class="divisor-horas">às</span>
                            <input type="time" name="hora_fechamento" id="hora_fechamento" value="<?php echo $hora_fechamento_conf; ?>">
                        </div>
                    </div>
                </div>

                <!-- 2. GRID INFERIOR REORGANIZADO: FILEIRA DE 3 COLUNAS COM LINKS ANCORADOS -->
                <div class="linha-gerenciamento-tripla">
                    
                    <!-- Card de Gerenciamento de Usuários -->
                    <div class="card-mini-gerenciamento">
                        <div class="topo-mini-card">
                            <div class="icone-mini-card">
                                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div class="texto-mini-card">
                                <h4>Usuários</h4>
                                <p>Cadastre usuários, defina permissões e controle o acesso ao sistema.</p>
                            </div>
                        </div>
                        <a href="#modalUsuarios" class="btn-link-config">
                            <span>Gerenciar usuários</span>
                            <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>

                    <!-- Card de Banco de Dados -->
                    <div class="card-mini-gerenciamento">
                        <div class="topo-mini-card">
                            <div class="icone-mini-card">
                                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"/></svg>
                            </div>
                            <div class="texto-mini-card">
                                <h4>Banco de Dados</h4>
                                <p>Faça backup, restaure ou exporte os dados com segurança.</p>
                            </div>
                        </div>
                        <a href="logica_php/backup_banco.php" class="btn-link-config">
                            <span>Baixar banco de dados</span>
                            <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        </a>
                    </div>

                    <!-- Card de Sobre o Sistema -->
                    <div class="card-mini-gerenciamento">
                        <div class="topo-mini-card">
                            <div class="icone-mini-card">
                                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            </div>
                            <div class="texto-mini-card">
                                <h4>Sobre o Sistema</h4>
                                <p>Informações de versão atual do sistema e desenvolvedores.</p>
                            </div>
                        </div>
                        <a href="#modalSobre" class="btn-link-config">
                            <span>Ver informações</span>
                            <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>

                </div> <!-- Fecha a linha-gerenciamento-tripla -->

                <!-- Pequena barra de aviso sutil idêntica à do mockup original -->
                <footer class="barra-aviso-config-rodape">
                    <svg xmlns="http://w3.org" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span>As alterações serão aplicadas em todo o sistema após salvar.</span>
                </footer>

            </div> <!-- Fecha o wrapper-scroll-configuracoes -->
        </form> <!-- Fecha o formulário mestre -->
    </main> <!-- Fecha o main do painel-conteudo-config -->
    
        <!-- 1. MODAL DE USUÁRIOS COMPLETO: TRÊS NÍVEIS DE ACESSO -->
    <div id="modalUsuarios" class="modal-config-container">
        <div class="modal-config-conteudo" style="width: 850px; max-width: 95%;">
            <div class="modal-config-topo">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <h2>Gerenciar Usuários</h2>
                </div>
                <a href="#" class="btn-fechar-modal">&times;</a>
            </div>
            
            <div class="modal-config-corpo" style="display: flex; gap: 24px; align-items: stretch;">
                
                <!-- COLUNA DA ESQUERDA: FORMULÁRIO -->
                <div style="flex: 1; display: flex; flex-direction: column; gap: 12px; border-right: 1px solid #e5e7eb; padding-right: 24px;">
                    <h3 style="font-size: 0.82rem; color: #111827; font-weight: 700; margin: 0;">Salvar / Atualizar Dados</h3>
                    <p style="font-size: 0.7rem; color: #6b7280; margin: 0 0 4px 0;">Insira as credenciais do usuário para cadastrar ou modificar o acesso.</p>
                    
                    <input type="hidden" id="id_usuario" value="">

                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <label style="font-size: 0.68rem; color: #4b5563; font-weight: 600;">Nome Completo</label>
                        <input type="text" id="nome_completo_input" placeholder="Digite o nome" style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.75rem; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <label style="font-size: 0.68rem; color: #4b5563; font-weight: 600;">Usuário de Acesso (Login)</label>
                        <input type="text" id="usuario_input" placeholder="Ex: admin ou atendente01" style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.75rem; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <label style="font-size: 0.68rem; color: #4b5563; font-weight: 600;">Senha de Acesso</label>
                        <input type="password" id="senha_input" placeholder="Digite uma senha segura" style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.75rem; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <label style="font-size: 0.68rem; color: #4b5563; font-weight: 600;">Nível de Acesso</label>
                        <select id="permissao_input" style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.75rem; outline: none; background-color: white; color: #1f2937;">
                            <option value="colaborador">Colaborador</option>
                            <option value="proprietario">Proprietário</option>
                            <option value="administrador">Administrador</option>
                        </select>
                    </div>

                    <button type="button" id="btnSalvarUsuarioForm" style="background-color: #2a3626; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 6px;">
                        <svg xmlns="http://w3.org" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                        <span>Salvar Usuário</span>
                    </button>
                </div>

                <!-- COLUNA DA DIREITA: TABELA COM CORES DOS TRÊS NÍVEIS -->
                <div style="flex: 1.3; display: flex; flex-direction: column; gap: 12px;">
                    <h3 style="font-size: 0.82rem; color: #111827; font-weight: 700; margin: 0;">Usuários Cadastrados</h3>
                    <p style="font-size: 0.7rem; color: #6b7280; margin: 0 0 4px 0;">Clique sobre um registro para editar ou use a ação para remover.</p>
                    
                    <div style="border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; background: #ffffff;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.72rem;">
                            <thead>
                                <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; color: #4b5563; font-weight: 600;">
                                    <th style="padding: 8px 10px;">Nome / Usuário</th>
                                    <th style="padding: 8px 10px;">Nível</th>
                                    <th style="padding: 8px 10px; text-align: right;">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="listaUsuariosTabela" style="color: #1f2937;">
                                <?php
                                try {
                                    $conexao_lista = new PDO("mysql:host=localhost;dbname=mira_confeitaria;charset=utf8", "root", "");
                                    $conexao_lista->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    
                                    // Puxa a nova coluna 'nivel' do banco
                                    $stmt_lista = $conexao_lista->query("SELECT id, usuario, nome_completo, nivel FROM usuarios ORDER BY nome_completo ASC");
                                    
                                    while ($user = $stmt_lista->fetch(PDO::FETCH_ASSOC)) {
                                        $nivel = !empty($user['nivel']) ? strtolower($user['nivel']) : 'colaborador';
                                        
                                        // Configuração visual das tags coloridas baseadas no nível real do banco
                                        if ($nivel === 'administrador') {
                                            $label = 'Administrador'; $bg = '#fef3c7'; $txt = '#d97706'; // Amarelo
                                        } else if ($nivel === 'proprietario') {
                                            $label = 'Proprietário'; $bg = '#d1fae5'; $txt = '#065f46'; // Verde sutil
                                        } else {
                                            $label = 'Colaborador'; $bg = '#e0f2fe'; $txt = '#0369a1'; // Azul
                                        }
                                        ?>
                                        <tr style="border-bottom: 1px solid #f3f4f6; cursor: pointer;" 
                                            data-id="<?php echo $user['id']; ?>" 
                                            data-nome="<?php echo htmlspecialchars($user['nome_completo']); ?>" 
                                            data-usuario="<?php echo htmlspecialchars($user['usuario']); ?>"
                                            data-permissao="<?php echo $nivel; ?>">
                                            <td style="padding: 8px 10px;">
                                                <div style="font-weight: 600;"><?php echo htmlspecialchars($user['nome_completo']); ?></div>
                                                <div style="font-size: 0.65rem; color: #6b7280;"><?php echo htmlspecialchars($user['usuario']); ?></div>
                                            </td>
                                            <td style="padding: 8px 10px;">
                                                <span style="background-color: <?php echo $bg; ?>; color: <?php echo $txt; ?>; padding: 2px 5px; border-radius: 4px; font-size: 0.62rem; font-weight: 600;"><?php echo $label; ?></span>
                                            </td>
                                            <td style="padding: 8px 10px; text-align: right;">
                                                <button type="button" class="btn-excluir-linha" data-id="<?php echo $user['id']; ?>" style="background: none; border: none; color: #ef4444; font-size: 0.68rem; font-weight: 700; cursor: pointer; padding: 2px 4px;">
                                                    Excluir
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } catch (Exception $e) {
                                    echo "<tr><td colspan='3' style='padding: 10px; color: red;'>Erro ao listar banco: " . $e->getMessage() . "</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- 2. MODAL SOBRE O SISTEMA -->
    <div id="modalSobre" class="modal-config-container">
        <div class="modal-config-conteudo">
            <div class="modal-config-topo">
                <h2>Sobre o Sistema</h2>
                <a href="#" class="btn-fechar-modal">&times;</a>
            </div>
            <div class="modal-config-corpo">
                <p><strong>MIRA Confeitaria</strong> - Sistema de Gerenciamento Local</p>
                <p style="margin-top: 10px; font-size: 0.8rem; color: #4b5563;">
                    Versão: 1.0.0 Local<br>
                    Ambiente: XAMPP / VS Code
                </p>
            </div>
        </div>
    </div>

    <!-- Chamada padrão do JS isolado do sistema -->
    <script src="../js/configuracoes.js?v=1.2"></script>
</body>
</html>

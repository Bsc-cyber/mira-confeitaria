<?php 
// Valida se o usuário fez login puxando a regra de segurança da subpasta
require_once "logica_php/home.php"; 
?>
<!-- Define o tipo de documento como HTML5 para o navegador -->
<!DOCTYPE html>
<!-- Define o idioma padrão da página como Português do Brasil -->
<html lang="pt-BR">
<!-- Abertura do cabeçalho de metadados da página -->
<head>
    <!-- Define a codificação de caracteres UTF-8 para evitar erros de acentos -->
    <meta charset="UTF-8">
    <!-- Ajusta a janela de visualização para garantir a responsividade -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Define o título que aparece na aba do navegador -->
    <title>MIRA Confeitaria - Configurações</title>
    <!-- Importa a folha de estilo fixa da barra lateral esquerda do sistema -->
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <!-- Importa o CSS próprio e customizado de configurações com controle de cache -->
    <link rel="stylesheet" href="../css/configuracoes.css?v=1.0">
<!-- Fechamento do bloco de metadados do cabeçalho -->
</head>
<!-- Abertura do corpo visível da página -->
<body>

    <?php 
    // Injeta dinamicamente a barra lateral de navegação do sistema MIRA
    require_once "barra_lateral.php"; 
    ?>

    <!-- Abre o painel de conteúdo mestre exclusivo da tela de configurações -->
    <main class="painel-conteudo-config">
        
        <!-- Abertura do formulário mestre que engloba toda a página para salvar tudo de uma vez -->
        <form id="formularioConfiguracoes" method="POST" action="configuracoes.php" novalidate style="display: flex; flex-direction: column; height: 100%; width: 100%;">

            <!-- Abre o cabeçalho superior completo de configurações -->
            <header class="topo-config">
                <!-- Agrupamento interno do título e do ícone do topo esquerdo -->
                <div class="titulo-pagina-config">
                    <!-- Moldura branca para o ícone vetorial da engrenagem -->
                    <div class="icone-titulo-conf">
                        <!-- Ícone vetorial de engrenagem seguindo o padrão MIRA -->
                        <svg class="svg-topo-conf" xmlns="http://w3.org" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    </div>
                    <!-- Bloco de texto para alinhar os títulos do topo -->
                    <div class="alinhamento-texto-topo">
                        <h1>Configurações</h1>
                        <p>Gerencie as configurações do sistema da sua confeitaria.</p>
                    </div>
                </div>

                <!-- Botão de Ação Destacado no Canto Direito Superior -->
                <button type="submit" class="btn-salvar-alteracoes">
                    <!-- Ícone vetorial de disquete/salvar interno do botão -->
                    <svg xmlns="http://w3.org" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Salvar alterações
                </button>
            </header>

            <!-- Área móvel interna de scroll que vai guardar os blocos e travar o layout -->
            <div class="wrapper-scroll-configuracoes">
                <!-- 1. GRANDE CARD: INFORMAÇÕES DA CONFEITARIA -->
                <div class="card-config-bloco">
                    <!-- Título interno da seção com mini ícone vetorial de loja -->
                    <h3 class="titulo-secao-config">
                        <svg class="svg-secao-config" xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Informações da Confeitaria
                    </h3>
                    <p class="subtitulo-secao-config">Atualize os dados principais da sua confeitaria.</p>

                    <!-- Fileira com 3 campos: Nome, CNPJ e Telefone -->
                    <div class="linha-campos-config-tripla">
                        <div class="grupo-campo-config">
                            <label>Nome da Confeitaria</label>
                            <input type="text" name="nome_confeitaria" id="nome_confeitaria" value="MIRA Confeitaria" placeholder="Digite o nome comercial">
                        </div>
                        <div class="grupo-campo-config">
                            <label>CNPJ <span class="opcional-texto">(opcional)</span></label>
                            <input type="text" name="cnpj" id="cnpj" value="12.345.678/0001-90" placeholder="00.000.000/0000-00">
                        </div>
                        <div class="grupo-campo-config">
                            <label>Telefone</label>
                            <input type="text" name="telefone_confeitaria" id="telefone_confeitaria" value="(31) 99876-5432" placeholder="(00) 00000-0000">
                        </div>
                    </div>

                    <!-- Fileira com 3 campos: E-mail, Endereço e Cidade/Estado -->
                    <div class="linha-campos-config-tripla">
                        <div class="grupo-campo-config">
                            <label>E-mail</label>
                            <input type="email" name="email_confeitaria" id="email_confeitaria" value="contato@miraconfeitaria.com.br" placeholder="exemplo@empresa.com">
                        </div>
                        <div class="grupo-campo-config">
                            <label>Endereço</label>
                            <input type="text" name="endereco_confeitaria" id="endereco_confeitaria" value="Rua das Flores, 123 - Centro" placeholder="Rua, número, bairro">
                        </div>
                        <div class="grupo-campo-config">
                            <label>Cidade / Estado</label>
                            <input type="text" name="cidade_estado" id="cidade_estado" value="Belo Horizonte - MG" placeholder="Cidade - UF">
                        </div>
                    </div>

                    <!-- Bloco interno de Horário de Funcionamento -->
                    <div class="secao-horario-funcionamento">
                        <label class="label-horario-titulo">Horário de Funcionamento</label>
                        <div class="linha-selecao-horario">
                            <!-- Ícone sutil de relógio -->
                            <svg xmlns="http://w3.org" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>Segunda a Sábado</span>
                            <!-- Inputs de hora minificados lado a lado -->
                            <input type="time" name="hora_abertura" id="hora_abertura" value="08:00">
                            <span class="divisor-horas">às</span>
                            <input type="time" name="hora_fechamento" id="hora_fechamento" value="18:00">
                        </div>
                    </div>
                </div>

                <!-- 2. GRID INFERIOR SELECIONÁVEL (FILHOS MENORES QUE NÃO FORAM RISCADOS) -->

                <div class="linha-gerenciamento-dupla">
                    
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
                        <button type="button" class="btn-link-config" onclick="location.href='usuarios.php'">
                            <span>Gerenciar usuários</span>
                            <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </button>
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
                        <button type="button" class="btn-link-config" onclick="location.href='banco_dados.php'">
                            <span>Gerenciar banco de dados</span>
                            <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </button>
                    </div>

                </div>

                <!-- 3. GRID INFERIOR REORGANIZADO: FILEIRA DE BAIXO (2 COLUNAS) -->
                <div class="linha-gerenciamento-dupla">
                    
                    <!-- Card de Segurança (Movido para cá) -->
                    <div class="card-mini-gerenciamento">
                        <div class="topo-mini-card">
                            <div class="icone-mini-card">
                                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <div class="texto-mini-card">
                                <h4>Segurança</h4>
                                <p>Altere sua senha, autenticação 2FA e gerencie sessões ativas.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-link-config" onclick="location.href='seguranca.php'">
                            <span>Configurar segurança</span>
                            <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </button>
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
                        <button type="button" class="btn-link-config" onclick="location.href='sobre.php'">
                            <span>Ver informações</span>
                            <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </button>
                    </div>

                </div>
                <!-- 3. FILEIRA INFERIOR: CARD SOBRE O SISTEMA -->
                            <!-- Fecha a div do wrapper-scroll-configuracoes móvel de forma limpa -->
            </div>

            <!-- Pequena barra de aviso sutil idêntica à do mockup original -->
            <footer class="barra-aviso-config-rodape">
                <!-- Ícone sutil de informação vetorial -->
                <svg xmlns="http://w3.org" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>As alterações serão aplicadas em todo o sistema após salvar.</span>
            </footer>

        <!-- Fecha o formulário mestre -->
        </form>
    <!-- Fecha o main do painel-conteudo-config -->
    </main>

    <!-- Injeta de forma cronológica a chamada do script JavaScript isolado de configurações -->
    <script src="js/configuracoes.js"></script>
<!-- Fecha a tag de corpo do documento -->
</body>
<!-- Fecha o escopo da tag HTML raiz -->
</html>

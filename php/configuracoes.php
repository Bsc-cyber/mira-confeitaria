<?php 
// SEGURANÇA MÁXIMA: Valida se o usuário fez login puxando a regra da subpasta
require_once "logica_php/home.php"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Configurações</title>
    <!-- Inclusão das folhas de estilo unificadas de forma local -->
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <link rel="stylesheet" href="../css/configuracoes.css?v=1.0">
</head>
<body>

    <div class="container-dashboard">
        
        <!-- Injeção da barra lateral componentizada -->
        <?php require_once "barra_lateral.php"; ?>

        <!-- ÁREA PRINCIPAL DA GESTÃO DE CONFIGURAÇÕES -->
        <main class="painel-conteudo-config">
            
            <!-- Cabeçalho Superior Limpo no padrão visual do sistema MIRA -->
            <header class="topo-config">
                <div class="titulo-pagina-config">
                    <div class="icone-titulo-conf">
                        <svg class="svg-topo-conf" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    </div>
                    <div class="alinhamento-texto-topo">
                        <h1>Configurações</h1>
                        <p>Gerencie as preferências, dados da empresa e parâmetros do sistema.</p>
                    </div>
                </div>
            </header>

            <!-- Grid de Configurações: Dividido em Aba Lateral Interna e Conteúdo Flutuante -->
            <div class="grid-config-container">
                
                <!-- COLUNA DA ESQUERDA: Menu de Navegação das Abas de Configuração -->
                <div class="coluna-esquerda-abas">
                    <div class="card-menu-abas-internas">
                        <button type="button" class="btn-aba-item ativa" data-alvo="secao-empresa">
                            💾 Dados da Empresa
                        </button>
                        <button type="button" class="btn-aba-item" data-alvo="secao-parametros">
                            ⚙️ Parâmetros do Sistema
                        </button>
                        <button type="button" class="btn-aba-item" data-alvo="secao-seguranca">
                            🔒 Segurança & Backup
                        </button>
                    </div>
                </div>

                <!-- COLUNA DA DIREITA: Contêiner Rolável Portando os Formulários -->
                <div class="coluna-direita-paineis">
                    <form id="formConfiguracoesSistema" class="wrapper-paineis-scroll">
                        
                        <!-- ABA 1: DADOS DA EMPRESA -->
                        <div class="painel-config-secao ativa-painel" id="secao-empresa">
                            <div class="card-config-bloco">
                                <h3>🏢 Perfil da Confeitaria</h3>
                                
                                <div class="grupo-input-config">
                                    <label>Razão Social / Nome Fantasia <span class="obrigatorio">*</span></label>
                                    <input type="text" value="MIRA Confeitaria Gourmet LTDA" required>
                                </div>

                                <div class="linha-inputs-dupla-conf m-t-8">
                                    <div class="grupo-input-config">
                                        <label>CNPJ</label>
                                        <input type="text" value="12.345.678/0001-99">
                                    </div>
                                    <div class="grupo-input-config">
                                        <label>Inscrição Estadual</label>
                                        <input type="text" value="110.220.330.440">
                                    </div>
                                </div>

                                <div class="linha-inputs-dupla-conf m-t-8">
                                    <div class="grupo-input-config">
                                        <label>Telefone Comercial <span class="obrigatorio">*</span></label>
                                        <input type="text" value="(11) 98765-4321" required>
                                    </div>
                                    <div class="grupo-input-config">
                                        <label>E-mail de Contato <span class="obrigatorio">*</span></label>
                                        <input type="email" value="contato@miraconfeitaria.com.br" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ABA 2: PARÂMETROS DO SISTEMA (TAXAS E REGRAS DE CONFEITARIA) -->
                        <div class="painel-config-secao" id="secao-parametros">
                            <div class="card-config-bloco">
                                <h3>⚙️ Regras de Venda e Taxas</h3>
                                
                                <div class="linha-inputs-dupla-conf">
                                    <div class="grupo-input-config">
                                        <label>Taxa de Entrega Padrão (R$) <span class="obrigatorio">*</span></label>
                                        <input type="text" value="7,00" required>
                                    </div>
                                    <div class="grupo-input-config">
                                        <label>Desconto Máximo Permitido (%) <span class="obrigatorio">*</span></label>
                                        <input type="text" value="15,00" required>
                                    </div>
                                </div>

                                <div class="grupo-input-config m-t-8">
                                    <label>Mensagem Padrão no Cupom do Cliente</label>
                                    <input type="text" value="Obrigado por adoçar o seu dia com a MIRA Confeitaria! Volte sempre.">
                                </div>

                                <div class="grupo-input-config m-t-8">
                                    <label>Impressão Automática de Pedidos</label>
                                    <div class="alinhamento-switch-config">
                                        <label class="switch-container-conf">
                                            <input type="checkbox" checked>
                                            <span class="slider-switch-bola-conf"></span>
                                        </label>
                                        <span class="texto-switch-conf">Enviar via Wi-Fi direto para a impressora térmica da cozinha</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ABA 3: SEGURANÇA, CONTROLE DE ACESSO E BACKUPS -->
                        <div class="painel-config-secao" id="secao-seguranca">
                            <div class="card-config-bloco">
                                <h3>🔒 Proteção e Salvamento Automático</h3>
                                
                                <div class="grupo-input-config">
                                    <label>Tempo Limite de Sessão Inativa (Minutos) <span class="obrigatorio">*</span></label>
                                    <input type="number" value="30" required>
                                </div>

                                <div class="grupo-input-config m-t-8">
                                    <label>Backup Automático em Nuvem</label>
                                    <div class="alinhamento-switch-config">
                                        <label class="switch-container-conf">
                                            <input type="checkbox" checked>
                                            <span class="slider-switch-bola-conf"></span>
                                        </label>
                                        <span class="texto-switch-conf">Realizar cópia de segurança do banco local todos os dias às 23:59h</span>
                                    </div>
                                </div>

                                <div class="linha-botoes-seguranca-rapida m-t-8">
                                    <button type="button" class="btn-acao-seguranca" id="btnBackupAgora">💾 Fazer Backup Local Agora (SQL)</button>
                                    <button type="button" class="btn-acao-seguranca limpar-cache" id="btnLimparCacheSistema">🧹 Limpar Cache de Imagens</button>
                                </div>
                            </div>
                        </div>

                        <!-- CARD FIXO DE AÇÃO: Botão grande unificado militar de salvar tudo, igual Fornecedores -->
                        <div class="card-formulario-config-acoes">
                            <div class="botoes-acoes-formulario-conf">
                                <button type="submit" class="btn-conf-base salvar-btn">💾 Salvar Todas as Alterações</button>
                            </div>
                        </div>

                    </form> <!-- Fecha a wrapper-paineis-scroll -->
                </div> <!-- Fecha a coluna-direita-paineis -->

            </div> <!-- Fecha a grid-config-container -->
        </main>
    </div> <!-- Fecha a container-dashboard -->

    <!-- Vinculação do motor comportamental isolado das abas -->
    <script src="../js/configuracoes.js"></script>
</body>
</html>

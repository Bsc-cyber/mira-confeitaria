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
    <!-- Ajusta a janela de visualização para que o site seja responsivo -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Define o título que aparece na aba do navegador -->
    <title>MIRA Confeitaria - Cadastro de Clientes</title>
    <!-- Importa a folha de estilo fixa da barra lateral esquerda do sistema -->
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <!-- Importa o CSS próprio e customizado de clientes com controle de cache -->
    <link rel="stylesheet" href="../css/clientes.css?v=6.0">
<!-- Fechamento do bloco de metadados do cabeçalho -->
</head>
<!-- Abertura do corpo visível da página -->
<body>

    <?php 
    // Injeta dinamicamente a barra lateral de navegação do sistema MIRA
    require_once "barra_lateral.php"; 
    ?>

    <!-- Abre o painel de conteúdo mestre exclusivo da tela de clientes -->
    <main class="painel-conteudo-clientes">
        
        <!-- Abre o cabeçalho superior contendo os títulos e descrições de clientes -->
        <header class="topo-clientes">
            <!-- Abre o agrupamento interno do título e do ícone do topo -->
            <div class="titulo-pagina-clientes">
                <!-- Moldura branca para o ícone vetorial da página de clientes -->
                <div class="icone-titulo-clie">
                    <!-- Ícone vetorial do usuário/cliente usando o padrão do sistema -->
                    <svg class="svg-topo-clie" xmlns="http://w3.org" viewBox="0 0 24 24">
                        <!-- Desenha a base dos ombros do ícone de cliente -->
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <!-- Desenha o círculo que representa a cabeça do cliente -->
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                <!-- Fecha a caixinha branca do ícone -->
                </div>
                <!-- Abre o bloco de texto para alinhar os títulos do topo -->
                <div class="alinhamento-texto-topo">
                    <!-- Renderiza o título principal da tela própria de clientes -->
                    <h1>Gestão de Clientes</h1>
                    <!-- Renderiza o pequeno texto explicativo cinza sobre contatos -->
                    <p>Cadastre novos clientes ou gerencie sua base de contatos.</p>
                <!-- Fecha o bloco de alinhamento de texto do topo -->
                </div>
            <!-- Fecha o agrupamento de título e ícone -->
            </div>
        <!-- Fecha a fileira do cabeçalho superior -->
        </header>

        <!-- Abre a grande seção de grade que reparte a tela ao meio em duas colunas de clientes (50%/50%) -->
        <section class="grid-clientes-container">
            <!-- Abre a metade esquerda do grid destinada ao formulário de cadastro de clientes -->
            <div class="coluna-esquerda-cadastro">
                <!-- Abre o cartão branco flutuante com as classes próprias de clientes -->
                <div class="card-formulario-clientes">
                    <!-- Título interno do cartão em letras maiúsculas próprio de clientes -->
                    <h3>
                        <!-- Ícone vetorial sutil de cadastro inserido ao lado esquerdo do texto -->
                        <svg class="svg-card-titulo" xmlns="http://w3.org" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        CADASTRO DE CLIENTE
                    <!-- Fecha a tag de título do cartão -->
                    </h3>
                    
                    <!-- Abertura do formulário que envia os dados via método POST -->
                    <form id="formularioCliente" method="POST" action="clientes.php" novalidate style="display: flex; flex-direction: column; height: 100%;">
                        <!-- Campo oculto para armazenar o ID do cliente selecionado no banco -->
                        <input type="hidden" name="id" id="cliente_id" value="">
                        <!-- Campo oculto que indica ao PHP qual ação executar no banco de dados -->
                        <input type="hidden" name="acao" id="form_acao" value="salvar">

                        <!-- Abre a área com rolagem vertical própria e exclusiva para os clientes -->
                        <div class="wrapper-inputs-scroll-clientes">
                            
                            <!-- Abre o bloco do campo de Nome do cliente -->
                            <div class="grupo-input-clientes">
                                <!-- Rótulo superior do campo indicando que ele é de preenchimento obrigatório -->
                                <label>Nome<span class="obrigatorio">*</span></label>
                                <!-- Caixa de texto para digitação do nome completo do cliente -->
                                <input type="text" name="nome" id="nome" placeholder="Digite o nome completo do cliente">
                            <!-- Fecha o bloco do campo de Nome -->
                            </div>

                            <!-- Abre o bloco do campo de Telefone do cliente -->
                            <div class="grupo-input-clientes">
                                <!-- Rótulo superior do campo indicando obrigatoriedade -->
                                <label>Telefone<span class="obrigatorio">*</span></label>
                                <!-- Caixa de texto configurada para a máscara de telefone -->
                                <input type="text" name="telefone" id="telefone" placeholder="Digite o telefone (00) 00000-0000">
                            <!-- Fecha o bloco do campo de Telefone -->
                            </div>

                            <!-- Abre o bloco do campo de CPF do cliente -->
                            <div class="grupo-input-clientes">
                                <!-- Rótulo superior informativo do campo de CPF -->
                                <label>CPF</label>
                                <!-- Caixa de texto configurada para a digitação e máscara de CPF -->
                                <input type="text" name="cpf" id="cpf" placeholder="Digite o CPF 000.000.000-00">
                            <!-- Fecha o bloco do campo de CPF -->
                            </div>

                            <!-- Abre o bloco do campo de Data de Nascimento -->
                            <div class="grupo-input-clientes">
                                <!-- Rótulo superior informativo da data de nascimento -->
                                <label>Data de Nascimento</label>
                                <!-- Campo nativo de calendário para escolha rápida de datas -->
                                <input type="date" name="data_nascimento" id="data_nascimento">
                            <!-- Fecha o bloco do campo de Data de Nascimento -->
                            </div>
                            <!-- Abre o bloco do campo de CEP residencial -->
                            <div class="grupo-input-clientes">
                                <!-- Rótulo superior para identificação do CEP postal -->
                                <label>CEP</label>
                                <!-- Caixa de texto configurada para a máscara de CEP de 8 dígitos -->
                                <input type="text" name="cep" id="cep" placeholder="Digite o CEP 00000-000">
                            <!-- Fecha o bloco do campo de CEP -->
                            </div>

                            <!-- Abre o bloco do campo do Logradouro/Rua -->
                            <div class="grupo-input-clientes">
                                <!-- Rótulo superior indicando a rua ou logradouro do endereço -->
                                <label>Rua</label>
                                <!-- Caixa de texto para o preenchimento automático ou manual da rua -->
                                <input type="text" name="rua" id="rua" placeholder="Logradouro">
                            <!-- Fecha o bloco do campo de Rua -->
                            </div>

                            <!-- Abre o bloco do campo numérico da residência -->
                            <div class="grupo-input-clientes">
                                <!-- Rótulo superior indicando o número do imóvel do cliente -->
                                <label>Nº</label>
                                <!-- Caixa de texto para preenchimento do número residencial -->
                                <input type="text" name="numero" id="numero" placeholder="Número">
                            <!-- Fecha o bloco do campo de Número -->
                            </div>

                            <!-- Abre o bloco do campo do Bairro residencial -->
                            <div class="grupo-input-clientes">
                                <!-- Rótulo superior indicando o bairro onde o cliente reside -->
                                <label>Bairro</label>
                                <!-- Caixa de texto para preenchimento do nome do bairro -->
                                <input type="text" name="bairro" id="bairro" placeholder="Bairro">
                            <!-- Fecha o bloco do campo de Bairro -->
                            </div>

                            <!-- Abre o bloco do campo de Complemento do endereço -->
                            <div class="grupo-input-clientes">
                                <!-- Rótulo descritivo para informações adicionais de entrega -->
                                <label>Complemento</label>
                                <!-- Caixa de texto para preenchimento de apartamento, bloco ou pontos -->
                                <input type="text" name="complemento" id="complemento" placeholder="Apt, Bloco, etc.">
                            <!-- Fecha o bloco do campo de Complemento -->
                            </div>

                            <!-- Abre o bloco do campo da Cidade do cliente -->
                            <div class="grupo-input-clientes">
                                <!-- Rótulo descritivo para identificação do município do endereço -->
                                <label>Cidade</label>
                                <!-- Caixa de texto para preenchimento do nome da cidade -->
                                <input type="text" name="cidade" id="cidade" placeholder="Cidade">
                            <!-- Fecha o bloco do campo de Cidade -->
                            </div>

                            <!-- Abre o bloco do campo de correio eletrônico -->
                            <div class="grupo-input-clientes">
                                <!-- Rótulo superior informativo do endereço de correio eletrônico -->
                                <label>E-mail</label>
                                <!-- Caixa de texto do tipo email para validação automática de formato -->
                                <input type="email" name="email" id="email" placeholder="Digite o e-mail (ex: exemplo@email.com)">
                            <!-- Fecha o bloco do campo de E-mail -->
                            </div>

                            <!-- Abre o bloco de texto longo para anotações gerais -->
                            <div class="grupo-input-clientes">
                                <!-- Rótulo descritivo de observações ou restrições do cadastro -->
                                <label>Observações</label>
                                <!-- Caixa expandida para anotação de restrições alimentares e notas gerais -->
                                <textarea name="observacoes" id="observacoes" placeholder="Restrições alimentares, notas gerais..."></textarea>
                            <!-- Fecha o bloco do campo de Observações -->
                            </div>

                            <!-- INSERIDO DENTRO DO SCROLL: Fileira própria de 4 botões de clientes -->
                            <div class="botoes-acoes-formulario-clie-quadruplo">
                                <!-- Botão principal para salvar os registros do cliente -->
                                <button type="submit" id="btn-salvar" class="btn-clie-base salvar-clie-btn">Salvar</button>
                                <!-- Botão para limpar os campos da tela de clientes -->
                                <button type="button" id="btn-limpar" class="btn-clie-base limpar-clie-btn">Limpar</button>
                                <!-- Botão escuro para carregar dados para edição -->
                                <button type="button" id="btn-editar" class="btn-clie-base limpar-clie-btn">Editar</button>
                                <!-- Botão vermelho destacado para exclusão definitiva do cliente -->
                                <button type="button" id="btn-excluir" class="btn-clie-base excluir-clie-btn">Excluir</button>
                            <!-- Fecha a fileira de botões inferiores -->
                            </div>

                        <!-- Fecha a div de rolagem vertical isolada do formulário de clientes -->
                        </div>
                    <!-- Fecha o elemento principal do formulário de cadastro -->
                    </form>
                <!-- Fecha o cartão branco de cadastro esquerdo -->
                </div>
            <!-- Fecha a metade esquerda do grid principal -->
            </div>
            <!-- Abre a metade direita do grid destinada à listagem de clientes cadastrados -->
            <div class="coluna-direita-listagem">
                <!-- Abre o cartão branco flutuante da listagem própria de clientes -->
                <div class="card-tabela-clientes">
                    
                    <!-- Abre a fileira interna que une a busca de contatos e o botão de atualização -->
                    <div class="linha-pesquisa-interna-clie">
                        <!-- Abre o envoltório estrutural cinza da barra de busca de clientes -->
                        <div class="wrapper-busca-tabela-clie">
                            <!-- Ícone vetorial de lupa inserido dentro do campo de busca de clientes -->
                            <svg class="svg-busca-tabela-interna" xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <!-- Input textual invisível e sem bordas para digitação e filtro dinâmico de clientes -->
                            <input type="text" placeholder="Pesquisar cliente...">
                        <!-- Fecha o envoltório estrutural da barra de busca -->
                        </div>
                        
                        <!-- Botão quadrado minificado para recarregar ou atualizar a listagem via PHP -->
                        <button class="btn-mini-tabela-clie-topo">
                            <!-- Ícone vetorial cíclico que representa a ação de atualização de registros -->
                            <svg class="svg-mini-topo" xmlns="http://w3.org" viewBox="0 0 24 24" fill="none"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                        <!-- Fecha o botão minificado de topo -->
                        </button>
                    <!-- Fecha a fileira interna de pesquisa superior -->
                    </div>

                    <!-- Abre a área de scroll com bordas nítidas própria e exclusiva para a listagem de clientes -->
                    <div class="wrapper-tabela-clientes-scroll">
                        <!-- Abertura da tabela de dados própria de clientes com layout de colunas fixado -->
                        <table class="tabela-dados-clientes">
                            <!-- Abertura do cabeçalho de títulos da tabela de clientes -->
                            <thead>
                                <!-- Abertura da linha de títulos do cabeçalho -->
                                <tr>
                                    <!-- Coluna de tamanho fixado em 50px para exibição do ID numérico -->
                                    <th style="width: 50px;">ID</th>
                                    <!-- Coluna fluída para renderização do nome completo do cliente -->
                                    <th>Nome do Cliente</th>
                                    <!-- Coluna de tamanho fixado para exibição do telefone formatado -->
                                    <th style="width: 110px;">Telefone</th>
                                    <!-- Coluna de tamanho fixado para exibição do CPF cadastrado -->
                                    <th style="width: 100px;">CPF</th>
                                    <!-- Coluna final minificada reservada para o botão redondo de edição -->
                                    <th style="width: 50px; text-align: center;">Ações</th>
                                <!-- Fecha a linha de títulos do cabeçalho -->
                                </tr>
                            <!-- Fecha o cabeçalho de títulos da tabela -->
                            </thead>
                            <!-- Abertura do corpo dinâmico de dados da tabela de clientes -->
                            <tbody>
                                <?php 
                                // Valida se o array dinâmico vindo do banco de dados está sem registros
                                if (empty($clientes)): 
                                ?>
                                <!-- Linha única de marcação informativa criada para tabelas vazias -->
                                <tr>
                                    <!-- Célula esticada por todas as colunas centralizando o aviso cinza de busca -->
                                    <td colspan="5" style="text-align: center; color: #6b7280; padding: 30px 0;">
                                        Nenhum cliente cadastrado ou encontrado na busca.
                                    </td>
                                </tr>
                                <?php 
                                // Caso o banco retorne registros, entra no laço de repetição do PHP
                                else: 
                                ?>
                                <?php 
                                // Varre o array criando uma linha HTML exclusiva para cada cliente mapeado
                                foreach ($clientes as $cliente): 
                                ?>
                                <!-- Criação da linha de dados comum a todos os clientes listados -->
                                <tr>
                                    <!-- Renderiza o código de identificação ID numérico com escape de segurança -->
                                    <td><?= htmlspecialchars($cliente['id']) ?></td>
                                    <!-- Renderiza o nome completo em negrito nítido de alto contraste comercial -->
                                    <td style="font-weight: 600; color: #111827;"><?= htmlspecialchars($cliente['nome']) ?></td>
                                    <!-- Renderiza a string contendo o telefone formatado do cliente -->
                                    <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                                    <!-- Renderiza a numeração do documento de CPF do cliente -->
                                    <td><?= htmlspecialchars($cliente['cpf']) ?></td>
                                    <!-- Célula centralizada reservada para acomodar o botão de clique -->
                                    <td>
                                        <!-- Alinhamento flexível centralizado para os botões redondos de linha -->
                                        <div class="celula-acoes-clie">
                                            <!-- Botão redondo disparador do carregamento de dados do cliente ativo -->
                                            <button type="button" class="btn-linha-clie edit" onclick='carregarCliente(<?= json_encode($cliente) ?>)'>
                                                <!-- Mini ícone vetorial sutil de prancheta/lápis próprio do sistema MIRA -->
                                                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/></svg>
                                            <!-- Fecha o botão circular de edição da linha de clientes -->
                                            </button>
                                        <!-- Fecha o alinhamento flexível da célula de ações -->
                                        </div>
                                    </td>
                                <!-- Fecha a linha de dados do cliente corrente -->
                                </tr>
                                <?php 
                                // Encerra o laço de repetição de varredura do PHP
                                endforeach; 
                                ?>
                                <?php 
                                // Encerra a condicional de validação de dados estruturada do PHP
                                endif; 
                                ?>
                            <!-- Fecha o corpo dinâmico de dados da tabela de clientes -->
                            </tbody>
                        <!-- Fecha o elemento principal da tabela de dados -->
                        </table>
                    <!-- Fecha a área de scroll blindada com linha externa escurecida de clientes -->
                    </div>

                    <!-- Abre o rodapé simplificado contendo as informações numéricas de contagem -->
                    <div class="rodape-paginacao-clientes">
                        <!-- Renderiza o pequeno texto indicando a volumetria exata do banco de clientes -->
                        <span class="info-contagem-clie">Mostrando 1 a <?= count($clientes ?? []) ?> de <?= count($clientes ?? []) ?> clientes</span>
                    <!-- Fecha o rodapé simplificado da listagem -->
                    </div>

                <!-- Fecha o cartão branco de listagem direito de clientes -->
                </div>
            <!-- Fecha a metade direita do grid principal de clientes -->
            </div>

        <!-- Fecha a grande seção de grade que divide a tela ao meio -->
        </section>
    <!-- Fecha o painel de conteúdo mestre exclusivo de clientes -->
    </main>

    <!-- Injeta de forma cronológica a chamada do script JavaScript isolado da página -->
    <script src="js/clientes.js"></script>
<!-- Fecha a tag de corpo do documento -->
</body>
<!-- Fecha o escopo da tag HTML raiz -->
</html>

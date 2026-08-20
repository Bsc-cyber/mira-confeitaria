<?php 
// 1. Valida se o usuário fez login puxando a regra de segurança
require_once "logica_php/home.php"; 

// 2. Conexão com o banco de dados
require_once "conexao.php"; 

// ==========================================================================
// LÓGICA DE SALVAR, ATUALIZAR E EXCLUIR (POST)
// ==========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? 'salvar';
    $id = $_POST['id'] ?? null;
    
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? null;
    $cpf = $_POST['cpf'] ?? null;
    $data_nascimento = !empty($_POST['data_nascimento']) ? $_POST['data_nascimento'] : null;
    $cep = $_POST['cep'] ?? null;
    $rua = $_POST['rua'] ?? null;
    $numero = $_POST['numero'] ?? null;
    $bairro = $_POST['bairro'] ?? null;
    $complemento = $_POST['complemento'] ?? null;
    $cidade = $_POST['cidade'] ?? null;
    $email = $_POST['email'] ?? null;
    $observacoes = $_POST['observacoes'] ?? null;

    try {
        if ($acao === 'salvar') {
            if (empty($id)) {
                $sql = "INSERT INTO clientes (nome, telefone, cpf, data_nascimento, cep, rua, numero, bairro, complemento, cidade, email, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conexao->prepare($sql);
                $stmt->execute([$nome, $telefone, $cpf, $data_nascimento, $cep, $rua, $numero, $bairro, $complemento, $cidade, $email, $observacoes]);
            } else {
                $sql = "UPDATE clientes SET nome=?, telefone=?, cpf=?, data_nascimento=?, cep=?, rua=?, numero=?, bairro=?, complemento=?, cidade=?, email=?, observacoes=? WHERE id=?";
                $stmt = $conexao->prepare($sql);
                $stmt->execute([$nome, $telefone, $cpf, $data_nascimento, $cep, $rua, $numero, $bairro, $complemento, $cidade, $email, $observacoes, $id]);
            }
        } elseif ($acao === 'excluir' && !empty($id)) {
            $sql = "DELETE FROM clientes WHERE id=?";
            $stmt = $conexao->prepare($sql);
            $stmt->execute([$id]);
        }
        
        header("Location: clientes.php");
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao processar cliente: " . $e->getMessage() . "');</script>";
    }
}

// ==========================================================================
// LÓGICA DE LISTAGEM PARA A TABELA (GET)
// ==========================================================================
$clientes = [];
try {
    $stmt = $conexao->query("SELECT * FROM clientes ORDER BY id DESC");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>alert('Erro ao buscar clientes: " . $e->getMessage() . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Cadastro de Clientes</title>
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <link rel="stylesheet" href="../css/clientes.css?v=9.0">
</head>
<body>

    <?php require_once "barra_lateral.php"; ?>

    <main class="painel-conteudo-clientes">
        <header class="topo-clientes">
            <div class="titulo-pagina-clientes">
                <div class="icone-titulo-clie">
                    <svg class="svg-topo-clie" xmlns="http://w3.org" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div class="alinhamento-texto-topo">
                    <h1>Gestão de Clientes</h1>
                    <p>Cadastre novos clientes ou gerencie sua base de contatos.</p>
                </div>
            </div>
        </header>

        <section class="grid-clientes-container">
            <!-- COLUNA ESQUERDA: FORMULÁRIO -->
            <div class="coluna-esquerda-cadastro">
                <div class="card-formulario-clientes">
                    <h3>
                        <svg class="svg-card-titulo" xmlns="http://w3.org" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        CADASTRO DE CLIENTE
                    </h3>
                    
                    <form id="formularioCliente" method="POST" action="clientes.php" novalidate style="display: flex; flex-direction: column; height: 100%;">
                        <input type="hidden" name="id" id="cliente_id" value="">
                        <input type="hidden" name="acao" id="form_acao" value="salvar">

                        <div class="wrapper-inputs-scroll-clientes">
                            <div class="grupo-input-clientes">
                                <label>Nome<span class="obrigatorio">*</span></label>
                                <input type="text" name="nome" id="nome" placeholder="Digite o nome completo do cliente" required>
                            </div>

                            <div class="grupo-input-clientes">
                                <label>Telefone<span class="obrigatorio">*</span></label>
                                <input type="text" name="telefone" id="telefone" placeholder="Digite o telefone (00) 00000-0000">
                            </div>

                            <div class="grupo-input-clientes">
                                <label>CPF</label>
                                <input type="text" name="cpf" id="cpf" placeholder="Digite o CPF 000.000.000-00">
                            </div>

                            <div class="grupo-input-clientes">
                                <label>Data de Nascimento</label>
                                <input type="date" name="data_nascimento" id="data_nascimento">
                            </div>

                            <div class="grupo-input-clientes">
                                <label>CEP</label>
                                <input type="text" name="cep" id="cep" placeholder="Digite o CEP 00000-000">
                            </div>

                            <div class="grupo-input-clientes">
                                <label>Rua</label>
                                <input type="text" name="rua" id="rua" placeholder="Logradouro">
                            </div>

                            <div class="grupo-input-clientes">
                                <label>Nº</label>
                                <input type="text" name="numero" id="numero" placeholder="Número">
                            </div>

                            <div class="grupo-input-clientes">
                                <label>Bairro</label>
                                <input type="text" name="bairro" id="bairro" placeholder="Bairro">
                            </div>

                            <div class="grupo-input-clientes">
                                <label>Complemento</label>
                                <input type="text" name="complemento" id="complemento" placeholder="Apt, Bloco, etc.">
                            </div>

                            <div class="grupo-input-clientes">
                                <label>Cidade</label>
                                <input type="text" name="cidade" id="cidade" placeholder="Cidade">
                            </div>

                            <div class="grupo-input-clientes">
                                <label>E-mail</label>
                                <input type="email" name="email" id="email" placeholder="Digite o e-mail (ex: exemplo@email.com)">
                            </div>

                            <div class="grupo-input-clientes">
                                <label>Observações</label>
                                <textarea name="observacoes" id="observacoes" placeholder="Restrições alimentares, notas gerais..."></textarea>
                            </div>

                            <!-- FILEIRA DE BOTÕES DUPLOS (Apenas Salvar e Limpar) -->
                            <div class="botoes-acoes-formulario-clie-duplo">
                                <button type="submit" id="btn-salvar" class="btn-clie-base salvar-clie-btn">Salvar</button>
                                <button type="button" id="btn-limpar" class="btn-clie-base limpar-clie-btn">Limpar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- COLUNA DIREITA: LISTAGEM -->
            <div class="coluna-direita-listagem">
                <div class="card-tabela-clientes">
                    <div class="linha-pesquisa-interna-clie">
                        <div class="wrapper-busca-tabela-clie">
                            <svg class="svg-busca-tabela-interna" xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" placeholder="Pesquisar cliente...">
                        </div>
                        <button class="btn-mini-tabela-clie-topo">
                            <svg class="svg-mini-topo" xmlns="http://w3.org" viewBox="0 0 24 24" fill="none"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                        </button>
                    </div>

                    <div class="wrapper-tabela-clientes-scroll">
                        <table class="tabela-dados-clientes">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">ID</th>
                                    <th>Nome do Cliente</th>
                                    <th style="width: 110px;">Telefone</th>
                                    <th style="width: 110px;">CPF</th>
                                    <!-- Restaurada a coluna de ações -->
                                    <th style="width: 80px; text-align: center;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($clientes)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #6b7280; padding: 30px 0;">
                                        Nenhum cliente cadastrado ou encontrado na busca.
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($clientes as $cliente): ?>
                                <!-- LINHA CLICÁVEL -->
                                <tr class="linha-tabela-clie" style="cursor: pointer;" onclick='carregarCliente(<?= json_encode($cliente) ?>, this)'>
                                    <td><?= htmlspecialchars($cliente['id']) ?></td>
                                    <td style="font-weight: 600; color: #111827;"><?= htmlspecialchars($cliente['nome']) ?></td>
                                    <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                                    <td><?= htmlspecialchars($cliente['cpf']) ?></td>
                                    <td>
                                        <div class="celula-acoes-clie" style="gap: 8px;">
                                            <!-- BOTÃO DE EDITAR -->
                                            <button type="button" class="btn-linha-clie edit" onclick='carregarCliente(<?= json_encode($cliente) ?>, this.closest("tr")); event.stopPropagation();' title="Editar Cliente">
                                                <svg xmlns="http://w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/></svg>
                                            </button>
                                            
                                            <!-- BOTÃO DE EXCLUIR RÁPIDO -->
                                            <button type="button" class="btn-linha-clie delete" onclick='excluirClienteDireto(<?= $cliente['id'] ?>); event.stopPropagation();' title="Excluir Cliente">
                                                <svg xmlns="http://w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="rodape-paginacao-clientes">
                        <span class="info-contagem-clie">Mostrando 1 a <?= count($clientes ?? []) ?> de <?= count($clientes ?? []) ?> clientes</span>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <script src="../js/clientes.js?v=<?php echo time(); ?>"></script>
</body>
</html>
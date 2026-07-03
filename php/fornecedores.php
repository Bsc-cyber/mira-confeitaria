<?php
// ==========================================================================
// MIRA CONFEITARIA - GESTÃO DE FORNECEDORES COMPLETA EM PDO
// ==========================================================================

$page = 'fornecedores';

// Puxa as conexões e componentes que estão na mesma pasta php/
include 'conexao.php';

// Verifica se chegou alguma requisição AJAX enviada pelo JavaScript via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : '';
    $cpf_cnpj = isset($_POST['cpf_cnpj']) ? trim($_POST['cpf_cnpj']) : '';
    $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $rua = isset($_POST['rua']) ? trim($_POST['rua']) : '';
    $numero = isset($_POST['numero']) ? trim($_POST['numero']) : '';
    $bairro = isset($_POST['bairro']) ? trim($_POST['bairro']) : '';
    $complemento = isset($_POST['complemento']) ? trim($_POST['complemento']) : '';
    $cidade_estado = isset($_POST['cidade_estado']) ? trim($_POST['cidade_estado']) : '';
    $notas = isset($_POST['notas']) ? trim($_POST['notas']) : '';

    try {
        // --- 1. OPERAÇÃO: SALVAR / CADASTRAR NOVO FORNECEDOR ---
        if ($acao === 'salvar' && !empty($nome)) {
            $sql = "INSERT INTO fornecedores (nome_razao, tipo_fornecedor, cpf_cnpj, telefone, email, rua, numero, bairro, complemento, cidade_estado, notas) 
                    VALUES (:nome, :tipo, :cpf_cnpj, :telefone, :email, :rua, :numero, :bairro, :complemento, :cidade_estado, :notas)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome, ':tipo' => $tipo, ':cpf_cnpj' => $cpf_cnpj, ':telefone' => $telefone,
                ':email' => $email, ':rua' => $rua, ':numero' => $numero, ':bairro' => $bairro,
                ':complemento' => $complemento, ':cidade_estado' => $cidade_estado, ':notas' => $notas
            ]);
        }

        // --- 2. OPERAÇÃO: EDITAR / ATUALIZAR CADASTRO EXISTENTE ---
        if ($acao === 'editar' && $id > 0 && !empty($nome)) {
            $sql = "UPDATE fornecedores SET nome_razao = :nome, tipo_fornecedor = :tipo, cpf_cnpj = :cpf_cnpj, telefone = :telefone, 
                    email = :email, rua = :rua, numero = :numero, bairro = :bairro, complemento = :complemento, cidade_estado = :cidade_estado, notas = :notas 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome, ':tipo' => $tipo, ':cpf_cnpj' => $cpf_cnpj, ':telefone' => $telefone,
                ':email' => $email, ':rua' => $rua, ':numero' => $numero, ':bairro' => $bairro,
                ':complemento' => $complemento, ':cidade_estado' => $cidade_estado, ':notas' => $notas, ':id' => $id
            ]);
        }

        // --- 3. OPERAÇÃO: EXCLUIR FORNECEDOR DO SISTEMA ---
        if ($acao === 'excluir' && $id > 0) {
            $sql = "DELETE FROM fornecedores WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
        }
    } catch (PDOException $e) {
        // Trata ou silencia em segundo plano para proteger a apresentação
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Fornecedores</title>
    <!-- ../ sai da pasta php/ e busca a folha de estilo de fornecedores -->
    <link rel="stylesheet" href="../css/fornecedores.css?v=1.0">
    <link rel="stylesheet" href="../css/responsivo.css">
</head>
<body>

    <!-- Puxa o menu centralizado profissional em PHP -->
    <?php include 'sidebar.php'; ?>

    <!-- Área de Conteúdo Principal -->
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <h1>Cadastro de Fornecedores</h1>
                <p class="subtitle">Gerencie os contatos de laticínios, embalagens e insumos da confeitaria.</p>
            </div>
        </header>

        <!-- Grade de alinhamento em formato Flexbox Líquido (60/40) -->
        <div class="suppliers-grid-layout-unified">
            <!-- COLUNA ESQUERDA: Formulário Amplo de Cadastro (60%) -->
            <div class="form-card">
                <div style="margin-bottom: 12px;">
                    <h3 class="card-section-title">Dados do Fornecedor</h3>
                    <p style="font-size: 11px; color: var(--text-muted);">Insira ou modifique as informações de contato e endereço da empresa.</p>
                </div>
                
                <form id="supplierForm" class="custom-form" novalidate>
                    <div class="form-row full-width">
                        <label>Nome / Razão Social:</label>
                        <input type="text" id="forn_nome" placeholder="Nome do fornecedor ou empresa">
                    </div>

                    <div class="form-row-grid">
                        <div class="form-row">
                            <label>Tipo de Fornecedor:</label>
                            <select id="forn_tipo" class="premium-select">
                                <option value="" disabled selected>Selecione o tipo</option>
                                <option value="Laticínios">Laticínios e Insumos Frios</option>
                                <option value="Embalagens">Embalagens e Caixas</option>
                                <option value="Confeitaria">Ingredientes de Confeitaria</option>
                                <option value="Utensílios">Equipamentos e Utensílios</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>CPF / CNPJ:</label>
                            <input type="text" id="forn_cpf_cnpj" placeholder="00.000.000/0001-00">
                        </div>
                    </div>

                    <div class="form-row-grid">
                        <div class="form-row">
                            <label>Telefone:</label>
                            <input type="text" id="forn_telefone" placeholder="(00) 00000-0000">
                        </div>
                        <div class="form-row">
                            <label>E-mail:</label>
                            <input type="text" id="forn_email" placeholder="fornecedor@empresa.com">
                        </div>
                    </div>

                    <div class="form-row-grid" style="grid-template-columns: 3fr 1fr;">
                        <div class="form-row">
                            <label>Rua / Logradouro:</label>
                            <input type="text" id="forn_rua" placeholder="Logradouro">
                        </div>
                        <div class="form-row">
                            <label>Nº:</label>
                            <input type="text" id="forn_numero" placeholder="Número">
                        </div>
                    </div>

                    <div class="form-row-grid">
                        <div class="form-row">
                            <label>Bairro:</label>
                            <input type="text" id="forn_bairro" placeholder="Bairro">
                        </div>
                        <div class="form-row">
                            <label>Complemento:</label>
                            <input type="text" id="forn_complemento" placeholder="Apt, Bloco, Galpão...">
                        </div>
                    </div>

                    <div class="form-row full-width">
                        <label>Cidade / Estado:</label>
                        <input type="text" id="forn_cidade_estado" placeholder="Cidade / UF">
                    </div>

                    <div class="form-row full-width">
                        <label>Notas de Compra / Informações Extras:</label>
                        <textarea id="forn_notas" placeholder="Chave Pix, prazos de entrega, acordos comerciais..."></textarea>
                    </div>

                    <!-- Botões com texto puro, sem emojis ou ícones -->
                    <div class="form-actions-4">
                        <button type="button" class="btn">Salvar</button>
                        <button type="button" class="btn">Editar</button>
                        <button type="button" class="btn">Limpar</button>
                        <button type="button" class="btn">Excluir</button>
                    </div>
                </form>
            </div>

            <!-- COLUNA DIREITA: Tabela de Busca Rápida Dinâmica (40%) -->
            <div class="list-card">
                <div style="margin-bottom: 12px;">
                    <h3 class="card-section-title">Fornecedores Cadastrados</h3>
                    <p style="font-size: 11px; color: var(--text-muted);">Clique em uma linha para editar ou digite para filtrar instantaneamente.</p>
                </div>

                <div class="list-header" style="display: block; margin-bottom: 20px;">
                    <div class="search-box-client" style="width: 100%;">
                        <input type="text" id="searchSupplierInput" placeholder="Pesquisar fornecedor por nome...">
                        <svg xmlns="http://w3.org" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="products-table" id="suppliersTable">
                        <thead>
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>Nome do Fornecedor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                // LOOP REAL EM PDO: Varre as empresas cadastradas no MySQL
                                $sql = "SELECT * FROM fornecedores ORDER BY id DESC";
                                $stmt = $pdo->query($sql);
                                $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if (count($fornecedores) > 0) {
                                    foreach ($fornecedores as $linha) {
                                        // Armazena todos os metadados em propriedades oculta 'data-' para o JavaScript ler
                                        echo "<tr class='forn-row' 
                                                  data-id='".htmlspecialchars($linha['id'])."' 
                                                  data-nome='".htmlspecialchars($linha['nome_razao'])."' 
                                                  data-tipo='".htmlspecialchars($linha['tipo_fornecedor'])."' 
                                                  data-cpf_cnpj='".htmlspecialchars($linha['cpf_cnpj'])."' 
                                                  data-telefone='".htmlspecialchars($linha['telefone'])."' 
                                                  data-email='".htmlspecialchars($linha['email'])."' 
                                                  data-rua='".htmlspecialchars($linha['rua'])."' 
                                                  data-numero='".htmlspecialchars($linha['numero'])."' 
                                                  data-bairro='".htmlspecialchars($linha['bairro'])."' 
                                                  data-complemento='".htmlspecialchars($linha['complemento'])."' 
                                                  data-cidade_estado='".htmlspecialchars($linha['cidade_estado'])."' 
                                                  data-notas='".htmlspecialchars($linha['notas'])."'>";
                                        echo "<td>#" . str_pad($linha['id'], 3, '0', STR_PAD_LEFT) . "</td>";
                                        echo "<td class='item-name-cell'>" . htmlspecialchars($linha['nome_razao']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo '<tr><td colspan="2" style="text-align: center; color: var(--text-muted); padding: 40px 0; font-style: italic;">Nenhum fornecedor cadastrado na base.</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="2" style="text-align: center; color: #C94A4A; padding: 40px 0;">Erro ao carregar tabela: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div> <!-- FIM DA GRADE -->
    </main>

    <!-- ../js/ sai de php/ e busca o script separado na raiz -->
    <script src="../js/fornecedores.js"></script>
</body>
</html>

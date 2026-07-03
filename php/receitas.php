<?php
// ==========================================================================
// MIRA CONFEITARIA - GESTÃO DE RECEITAS COMPLETA EM PDO
// ==========================================================================

$page = 'receitas';

// Puxa a conexão PDO que está na mesma pasta php/
include 'conexao.php';

// Verifica se chegou alguma requisição AJAX enviada pelo JavaScript via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $tempo = isset($_POST['tempo']) ? trim($_POST['tempo']) : '';
    $rendimento = isset($_POST['rendimento']) ? trim($_POST['rendimento']) : '';
    $ingredientes = isset($_POST['ingredientes']) ? trim($_POST['ingredientes']) : '';
    $preparo = isset($_POST['preparo']) ? trim($_POST['preparo']) : '';

    try {
        // --- 1. OPERAÇÃO: SALVAR / CADASTRAR NOVA RECEITA ---
        if ($acao === 'salvar' && !empty($nome)) {
            $sql = "INSERT INTO receitas (nome, tempo_preparo, rendimento, ingredientes, modo_preparo) VALUES (:nome, :tempo, :rendimento, :ingredientes, :preparo)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':tempo' => $tempo,
                ':rendimento' => $rendimento,
                ':ingredientes' => $ingredientes,
                ':preparo' => $preparo
            ]);
        }

        // --- 2. OPERAÇÃO: EDITAR / ATUALIZAR RECEITA EXISTENTE ---
        if ($acao === 'editar' && $id > 0 && !empty($nome)) {
            $sql = "UPDATE receitas SET nome = :nome, tempo_preparo = :tempo, rendimento = :rendimento, ingredientes = :ingredientes, modo_preparo = :preparo WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':tempo' => $tempo,
                ':rendimento' => $rendimento,
                ':ingredientes' => $ingredientes,
                ':preparo' => $preparo,
                ':id' => $id
            ]);
        }

        // --- 3. OPERAÇÃO: EXCLUIR RECEITA PERMANENTEMENTE ---
        if ($acao === 'excluir' && $id > 0) {
            $sql = "DELETE FROM receitas WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
        }
    } catch (PDOException $e) {
        // Silencia erros em segundo plano para proteger a apresentação
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Receitas</title>
    <!-- O ../ faz o arquivo sair da pasta php/ e achar a pasta css/ na raiz do projeto -->
    <link rel="stylesheet" href="../css/receitas.css?v=1.0">
    <link rel="stylesheet" href="../css/responsivo.css">
</head>
<body>

    <!-- Menu Lateral Embutido na Mesma Pasta para evitar falhas -->
    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <span class="avatar-mc">MC</span>
            <h2>MIRA confeitaria</h2>
        </div>
        
        <nav class="menu">
            <a href="home.php">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="clientes.php">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Clientes</span>
            </a>
            <a href="produtos.php">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                <span>Produtos</span>
            </a>
            <a href="pedidos.php">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                <span>Pedidos</span>
            </a>
            <a href="vendas.php">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <span>Vendas</span>
            </a>
            <a href="#" class="active">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                <span>Receitas</span>
            </a>
            <a href="fornecedores.php">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2" ry="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                <span>Fornecedores</span>
            </a>
            <a href="financeiro.php">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span>Controle Financeiro</span>
            </a>
            <a href="configuracoes.php">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span>Configurações</span>
            </a>
            <a href="../login.html">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span>Sair</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <p class="brand-phrase">Doces feitos para transformar<br>momentos em memórias.</p>
            <div class="user-profile">
                <span class="avatar-lc">LC</span>
                <div class="user-info">
                    <h4>Lucas</h4>
                    <small>Proprietário</small>
                </div>
            </div>
        </div>
    </aside>

    <!-- Área de Conteúdo Principal -->
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <h1>Fichas Técnicas de Receitas</h1>
                <p class="subtitle">Cadastre e gerencie o modo de preparo e os custos de suas produções.</p>
            </div>
        </header>

        <!-- Grade de alinhamento horizontal lado a lado (60/40) -->
        <div class="recipes-grid-layout-unified">
            <!-- COLUNA ESQUERDA: Ficha Técnica Detalhada (60%) -->
            <div class="form-card">
                <div style="margin-bottom: 12px;">
                    <h3 class="card-section-title">Ficha Técnica</h3>
                    <p style="font-size: 11px; color: var(--text-muted);">Insira os ingredientes e etapas detalhadas da receita.</p>
                </div>
                
                <form id="recipeForm" class="custom-form" novalidate>
                    <div class="form-row full-width">
                        <label>Nome da Receita:</label>
                        <input type="text" id="rec_nome" placeholder="Ex: Massa Amanteigada de Baunilha, Brigadeiro de Pistache...">
                    </div>

                    <div class="form-row-grid">
                        <div class="form-row">
                            <label>Tempo de Preparo:</label>
                            <input type="text" id="rec_tempo" placeholder="Ex: 45 min, 1h 20min...">
                        </div>
                        <div class="form-row">
                            <label>Rendimento Base:</label>
                            <input type="text" id="rec_rendimento" placeholder="Ex: 20 porções, 2 aros de 15cm...">
                        </div>
                    </div>

                    <div class="form-row full-width">
                        <label>Ingredientes e Quantidades:</label>
                        <textarea id="rec_ingredientes" style="height: 110px;" placeholder="Ex:&#10;- 200g de farinha de trigo&#10;- 4 ovos inteiros..."></textarea>
                    </div>

                    <div class="form-row full-width">
                        <label>Modo de Preparo / Passos:</label>
                        <textarea id="rec_preparo" style="height: 130px;" placeholder="Descreva o passo a passo completo da produção..."></textarea>
                    </div>

                    <div class="form-actions-4">
                        <button type="button" class="btn">Salvar</button>
                        <button type="button" class="btn">Editar</button>
                        <button type="button" class="btn">Limpar</button>
                        <button type="button" class="btn">Excluir</button>
                    </div>
                </form>
            </div>

            <!-- COLUNA DIREITA: Tabela de Busca Rápida (40%) -->
            <div class="list-card">
                <div style="margin-bottom: 12px;">
                    <h3 class="card-section-title">Receitas Cadastradas</h3>
                    <p style="font-size: 11px; color: var(--text-muted);">Clique em uma linha para carregar ou digite para filtrar na hora.</p>
                </div>

                <div class="list-header" style="display: block; margin-bottom: 20px;">
                    <div class="search-box-client" style="width: 100%;">
                        <input type="text" id="searchRecipeInput" placeholder="Pesquisar receita por nome...">
                        <svg xmlns="http://w3.org" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="products-table" id="recipesTable">
                        <thead>
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>Nome da Receita</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                // Puxa todas as receitas cadastradas na base local via PDO
                                $sql = "SELECT * FROM receitas ORDER BY id DESC";
                                $stmt = $pdo->query($sql);
                                $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if (count($receitas) > 0) {
                                    foreach ($receitas as $linha) {
                                        echo "<tr class='rec-row' 
                                                  data-id='".htmlspecialchars($linha['id'])."' 
                                                  data-nome='".htmlspecialchars($linha['nome'])."' 
                                                  data-tempo='".htmlspecialchars($linha['tempo_preparo'])."' 
                                                  data-rendimento='".htmlspecialchars($linha['rendimento'])."' 
                                                  data-ingredientes='".htmlspecialchars($linha['ingredientes'])."' 
                                                  data-preparo='".htmlspecialchars($linha['modo_preparo'])."'>";
                                        echo "<td>#" . str_pad($linha['id'], 3, '0', STR_PAD_LEFT) . "</td>";
                                        echo "<td class='item-name-cell'>" . htmlspecialchars($linha['nome']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo '<tr><td colspan="2" style="text-align: center; color: var(--text-muted); padding: 40px 0; font-style: italic;">Nenhuma receita salva no banco local.</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="2" style="text-align: center; color: #C94A4A; padding: 40px 0;">Erro ao ler tabela: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- Script externo unificado de receitas -->
    <script src="../js/receitas.js"></script>
</body>
</html>

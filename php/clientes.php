<?php
// 1. Chama a conexão com o banco
require_once 'conexao.php';

// 2. LÓGICA DE SALVAR, EDITAR E EXCLUIR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? 'salvar';
    $id = $_POST['id'] ?? '';
    
    // EXCLUIR
    if ($acao === 'excluir' && !empty($id)) {
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: clientes.php");
        exit;
    } 
    
    // SALVAR (NOVO) OU EDITAR
    if ($acao === 'salvar') {
        $dados = [
            $_POST['nome'], $_POST['telefone'], $_POST['cpf'], $_POST['data_nascimento'],
            $_POST['cep'], $_POST['rua'], $_POST['numero'], $_POST['bairro'],
            $_POST['complemento'], $_POST['cidade'], $_POST['email'], $_POST['observacoes']
        ];

        if (empty($id)) {
            // Se não tem ID, é um cliente NOVO (INSERT)
            $sql = "INSERT INTO clientes (nome, telefone, cpf, data_nascimento, cep, rua, numero, bairro, complemento, cidade, email, observacoes) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($dados);
        } else {
            // Se tem ID, está atualizando um cliente existente (UPDATE)
            $sql = "UPDATE clientes SET nome=?, telefone=?, cpf=?, data_nascimento=?, cep=?, rua=?, numero=?, bairro=?, complemento=?, cidade=?, email=?, observacoes=? WHERE id=?";
            $dados[] = $id; // Adiciona o ID no final da lista de dados para a cláusula WHERE
            $stmt = $pdo->prepare($sql);
            $stmt->execute($dados);
        }
        // Recarrega a página para limpar o formulário e atualizar a tabela
        header("Location: clientes.php");
        exit;
    }
}

// 3. BUSCAR OS CLIENTES PARA A TABELA (Ordem alfabética)
$stmt = $pdo->query("SELECT * FROM clientes ORDER BY nome ASC");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Cadastro de Clientes</title>
    <link rel="stylesheet" href="../css/clientes.css?v=3.0">
</head>
<body>

    <!-- Barra Lateral Inteligente (Padrão do Site) -->
    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <span class="avatar-mc">MC</span>
            <h2>MIRA confeitaria</h2>
        </div>
        
        <nav class="menu">
            <a href="home.html">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="#" class="active">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Clientes</span>
            </a>
            <a href="produtos.html">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                <span>Produtos</span>
            </a>
            <a href="pedidos.html">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                <span>Pedidos</span>
            </a>
            <a href="vendas.html">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <span>Vendas</span>
            </a>
            <a href="receitas.html">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                <span>Receitas</span>
            </a>
            <a href="fornecedores.html">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2" ry="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                <span>Fornecedores</span>
            </a>
            <a href="financeiro.html">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span>Controle Financeiro</span>
            </a>
            <a href="#">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                <span>Relatórios</span>
            </a>
            
            <a href="configuracoes.html" class="nav-configuracoes">
                <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span>Configurações</span>
            </a>

            <a href="login.html">
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
                <h1>Gestão de Clientes</h1>
                <p class="subtitle">Cadastre novos clientes ou gerencie sua base de contatos.</p>
            </div>
        </header>

        <!-- Grade Principal Baseada no Modelo de Pedidos (Meio a Meio) -->
        <section class="clients-grid-container">
            <!-- COLUNA ESQUERDA: Formulário com os 4 Botões Verdes -->
            <div class="form-card">
                <h3 class="card-section-title">Dados do Cliente</h3>
                <!--<form id="clientForm" class="custom-form" novalidate> -->
                <form id="clientForm" method="POST" action="clientes.php" class="custom-form" novalidate>
                    
                    <!-- CAMPOS OCULTOS (Obrigatórios para o PHP saber o que fazer) -->
                    <input type="hidden" name="id" id="cliente_id" value="">
                    <input type="hidden" name="acao" id="form_acao" value="salvar">

                    <!-- O campo Nome e o resto continuam normais daqui para baixo -->

                    <div class="form-row full-width">
                        <label>Nome:</label>
                        <input type="text" name="nome" placeholder="Nome completo do cliente">
                    </div>

                    <div class="form-row-grid">
                        <div class="form-row">
                            <label>Telefone:</label>
                            <input type="text" name="telefone" placeholder="(00) 00000-0000">
                        </div>
                        <div class="form-row">
                            <label>CPF:</label>
                            <input type="text" name="cpf" placeholder="000.000.000-00">
                        </div>
                    </div>

                    <div class="form-row-grid">
                        <div class="form-row">
                            <label>Data Nascimento:</label>
                            <input type="date" name="data_nascimento">
                        </div>
                        <div class="form-row">
                            <label>CEP:</label>
                            <input type="text" name="cep" placeholder="00000-000">
                        </div>
                    </div>

                    <div class="form-row-grid target-address">
                        <div class="form-row long-input">
                            <label>Rua:</label>
                            <input type="text" name="rua" placeholder="Logradouro">
                        </div>
                        <div class="form-row short-input">
                            <label>Nº:</label>
                            <input type="text" name="numero" placeholder="Número">
                        </div>
                    </div>

                    <div class="form-row-grid">
                        <div class="form-row">
                            <label>Bairro:</label>
                            <input type="text" name="bairro" placeholder="Bairro">
                        </div>
                        <div class="form-row">
                            <label>Complemento:</label>
                            <input type="text" name="complemento" placeholder="Apt, Bloco, etc.">
                        </div>
                    </div>

                    <div class="form-row-grid">
                        <div class="form-row">
                            <label>Cidade:</label>
                            <input type="text" name="cidade" placeholder="Cidade">
                        </div>
                        <div class="form-row">
                            <label>E-mail:</label>
                            <input type="email" name="email" placeholder="exemplo@email.com">
                        </div>
                    </div>

                    <div class="form-row full-width">
                        <label>Observações:</label>
                        <textarea name="observacoes" placeholder="Restrições alimentares, preferências de entrega, notas gerais..."></textarea>
                    </div>

                    <!-- Linha com os 4 Botões Clássicos Padronizados -->
                    <div class="form-actions-4">
                        <button type="submit" class="btn">Salvar</button>
                        <button type="button" class="btn">Editar</button>
                        <button type="button" class="btn">Limpar</button>
                        <button type="button" class="btn">Excluir</button>
                    </div>
                </form>
            </div>

            <!-- COLUNA DIREITA: Barra de Pesquisa e Tabela ID/NOME -->
            <div class="list-card">
                <div class="list-header">
                    <div class="search-box-client">
                        <input type="text" placeholder="Pesquisar cliente...">
                        <svg xmlns="http://w3.org" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                </div>

                <!-- Tabela de Referência Otimizada com ID e NOME -->
                <div class="table-wrapper">
                    <table class="clients-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Nome do Cliente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Pronto para receber as linhas dinâmicas do banco de dados -->
                            <tr class="empty-row-placeholder">
                                <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                                    Nenhum cliente cadastrado ou encontrado na busca.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </main>

    <!-- Script de animação do menu lateral -->
    <script>
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.addEventListener('mouseenter', () => sidebar.classList.add('expanded'));
            sidebar.addEventListener('mouseleave', () => sidebar.classList.remove('expanded'));
        }
    </script>
   
    <!-- CHAMADA PADRÃO DO ARQUIVO DE SCRIPT SEPARADO -->
    <script src="js/clientes.js"></script>
</body>
</html>

<?php 
// SEGURANÇA: Valida se o usuário fez login puxando a regra da subpasta
require_once "logica_php/home.php"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Confeitaria - Cadastro de Clientes</title>
    <link rel="stylesheet" href="../css/barra_lateral.css">
    <link rel="stylesheet" href="../css/clientes.css?v=3.0">
</head>
<body>

    <<!-- Injeção da barra lateral localizada na mesma pasta corrente -->
    <?php require_once "barra_lateral.php"; ?>

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
                
                <form id="clientForm" method="POST" action="clientes.php" class="custom-form" novalidate>
                    
                    <!-- CAMPOS OCULTOS (Obrigatórios para o PHP saber o que fazer) -->
                    <input type="hidden" name="id" id="cliente_id" value="">
                    <input type="hidden" name="acao" id="form_acao" value="salvar">

                    <div class="form-row full-width">
                        <label>Nome:</label>
                        <input type="text" name="nome" id="nome" placeholder="Nome completo do cliente">
                    </div>

                    <div class="form-row-grid">
                        <div class="form-row">
                            <label>Telefone:</label>
                            <input type="text" name="telefone" id="telefone" placeholder="(00) 00000-0000">
                        </div>
                        <div class="form-row">
                            <label>CPF:</label>
                            <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00">
                        </div>
                    </div>

                    <div class="form-row-grid">
                        <div class="form-row">
                            <label>Data Nascimento:</label>
                            <input type="date" name="data_nascimento" id="data_nascimento">
                        </div>
                        <div class="form-row">
                            <label>CEP:</label>
                            <input type="text" name="cep" id="cep" placeholder="00000-000">
                        </div>
                    </div>

                    <div class="form-row-grid target-address">
                        <div class="form-row long-input">
                            <label>Rua:</label>
                            <input type="text" name="rua" id="rua" placeholder="Logradouro">
                        </div>
                        <div class="form-row short-input">
                            <label>Nº:</label>
                            <input type="text" name="numero" id="numero" placeholder="Número">
                        </div>
                    </div>

                    <div class="form-row-grid">
                        <div class="form-row">
                            <label>Bairro:</label>
                            <input type="text" name="bairro" id="bairro" placeholder="Bairro">
                        </div>
                        <div class="form-row">
                            <label>Complemento:</label>
                            <input type="text" name="complemento" id="complemento" placeholder="Apt, Bloco, etc.">
                        </div>
                    </div>

                    <div class="form-row-grid">
                        <div class="form-row">
                            <label>Cidade:</label>
                            <input type="text" name="cidade" id="cidade" placeholder="Cidade">
                        </div>
                        <div class="form-row">
                            <label>E-mail:</label>
                            <input type="email" name="email" id="email" placeholder="exemplo@email.com">
                        </div>
                    </div>

                    <div class="form-row full-width">
                        <label>Observações:</label>
                        <textarea name="observacoes" id="observacoes" placeholder="Restrições alimentares, preferências de entrega, notas gerais..."></textarea>
                    </div>

                    <!-- Linha com os 4 Botões Clássicos Padronizados -->
                    <div class="form-actions-4">
                        <button type="submit" id="btn-salvar" class="btn">Salvar</button>
                        <button type="button" id="btn-editar" class="btn">Editar</button>
                        <button type="button" id="btn-limpar" class="btn">Limpar</button>
                        <button type="button" id="btn-excluir" class="btn">Excluir</button>
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
                            <?php if (empty($clientes)): ?>
                                <!-- Mostra isso se o banco de dados estiver vazio -->
                                <tr class="empty-row-placeholder">
                                    <td colspan="2" style="text-align: center; color: #7A8A7C; padding: 40px 0;">
                                        Nenhum cliente cadastrado ou encontrado na busca.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <!-- Se tiver clientes no banco, cria uma linha para cada um -->
                                <?php foreach ($clientes as $cliente): ?>
                                    <!-- A função carregarCliente pega os dados e joga no formulário da esquerda ao clicar -->
                                    <tr onclick='carregarCliente(<?= json_encode($cliente) ?>)' style="cursor: pointer;">
                                        <td><?= htmlspecialchars($cliente['id']) ?></td>
                                        <td><?= htmlspecialchars($cliente['nome']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </main>

    <!-- CHAMADA PADRÃO DO ARQUIVO DE SCRIPT SEPARADO -->
    <script src="js/clientes.js"></script>
</body>
</html>

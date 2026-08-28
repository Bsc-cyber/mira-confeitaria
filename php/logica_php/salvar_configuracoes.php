<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Configurações de conexão com o banco do XAMPP
$host = "localhost";
$usuario_db = "root";
$senha_db = "";
$banco = "mira_confeitaria";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario_db, $senha_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Captura os dados digitados nas caixinhas da tela
    $nome = $_POST['nome_confeitaria'] ?? '';
    $cnpj = $_POST['cnpj'] ?? '';
    $telefone = $_POST['telefone_confeitaria'] ?? '';
    $email = $_POST['email_confeitaria'] ?? '';
    $endereco = $_POST['endereco_confeitaria'] ?? '';
    $cidade_estado = $_POST['cidade_estado'] ?? '';
    $hora_abertura = $_POST['hora_abertura'] ?? '';
    $hora_fechamento = $_POST['hora_fechamento'] ?? '';

    // Validação básica: Nome da loja não pode ser vazio
    if (empty($nome)) {
        header("Location: ../configuracoes.php?status=erro&mensagem=O+nome+da+confeitaria+e+obrigatorio");
        exit;
    }

    // 3. Verifica se já existe alguma linha salva na tabela info_confeitaria
    $stmt = $pdo->query("SELECT id FROM info_confeitaria LIMIT 1");
    $config_existente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($config_existente) {
        // Se já existe um registro, ATUALIZA ele
        $id = $config_existente['id'];
        $sql = "UPDATE info_confeitaria SET nome_confeitaria = ?, cnpj = ?, telefone = ?, email = ?, endereco = ?, cidade_estado = ?, hora_abertura = ?, hora_fechamento = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $cnpj, $telefone, $email, $endereco, $cidade_estado, $hora_abertura, $hora_fechamento, $id]);
    } else {
        // Se a tabela estiver vazia, INSERE o primeiro registro do sistema
        $sql = "INSERT INTO info_confeitaria (nome_confeitaria, cnpj, telefone, email, endereco, cidade_estado, hora_abertura, hora_fechamento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $cnpj, $telefone, $email, $endereco, $cidade_estado, $hora_abertura, $hora_fechamento]);
    }

    // 4. Redireciona de volta para a página de configurações de forma limpa
    header("Location: ../configuracoes.php?status=sucesso");
    exit;

} catch (Exception $e) {
    // Se houver erro (ex: tabela não criada), mostra o aviso na URL para facilitar o ajuste
    header("Location: ../configuracoes.php?status=erro&mensagem=" . urlencode($e->getMessage()));
    exit;
}

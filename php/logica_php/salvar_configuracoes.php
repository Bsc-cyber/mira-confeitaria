<?php
// Configurações de conexão com o banco do XAMPP
$host = "localhost";
$usuario_db = "root";
$senha_db = "";
$banco = "mira_confeitaria";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario_db, $senha_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Captura os dados enviados pelo formulário principal
    $nome = $_POST['nome_confeitaria'] ?? '';
    $cnpj = $_POST['cnpj'] ?? '';
    $telefone = $_POST['telefone_confeitaria'] ?? '';
    $email = $_POST['email_confeitaria'] ?? '';
    $endereco = $_POST['endereco_confeitaria'] ?? '';
    $cidade_estado = $_POST['cidade_estado'] ?? '';
    $hora_abertura = $_POST['hora_abertura'] ?? '';
    $hora_fechamento = $_POST['hora_fechamento'] ?? '';

    // Verifica se os campos obrigatórios estão preenchidos
    if (empty($nome) || empty($email)) {
        header("Location: ../configuracoes.php?status=erro&mensagem=Nome+e+Email+sao+obrigatorios");
        exit;
    }

    // Procura se já existe alguma configuração salva (geralmente é apenas 1 linha na tabela)
    $stmt = $pdo->query("SELECT id FROM info_confeitaria LIMIT 1");
    $config_existente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($config_existente) {
        // Se já existe, ATUALIZA os dados
        $id = $config_existente['id'];
        $sql = "UPDATE info_confeitaria SET nome_confeitaria = ?, cnpj = ?, telefone = ?, email = ?, endereco = ?, cidade_estado = ?, hora_abertura = ?, hora_fechamento = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $cnpj, $telefone, $email, $endereco, $cidade_estado, $hora_abertura, $hora_fechamento, $id]);
    } else {
        // Se a tabela estiver vazia, INSERE o primeiro registro
        $sql = "INSERT INTO info_confeitaria (nome_confeitaria, cnpj, telefone, email, endereco, cidade_estado, hora_abertura, hora_fechamento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $cnpj, $telefone, $email, $endereco, $cidade_estado, $hora_abertura, $hora_fechamento]);
    }

    // Redireciona de volta para a página de configurações com mensagem de sucesso
    header("Location: ../configuracoes.php?status=sucesso");
    exit;

} catch (Exception $e) {
    echo "Erro ao salvar as configurações: " . $e->getMessage();
}

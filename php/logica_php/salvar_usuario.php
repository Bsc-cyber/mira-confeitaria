<?php
// Garante que o navegador entenda que a resposta deste arquivo é um JSON oficial
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$usuario_db = "root";
$senha_db = "";
$banco = "mira_confeitaria";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario_db, $senha_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Captura os dados enviados via JavaScript FormData
    $id = $_POST['id'] ?? null; 
    $nome_completo = $_POST['nome'] ?? '';
    $usuario_login = $_POST['email'] ?? ''; 
    $senha_acesso = $_POST['senha'] ?? '';

    if (empty($nome_completo) || empty($usuario_login)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Nome e Usuário são obrigatórios.']);
        exit;
    }

    if ($id) {
        // MODO ATUALIZAÇÃO
        if (!empty($senha_acesso)) {
            $stmt = $pdo->prepare("UPDATE usuarios SET usuario = ?, senha = ?, nome_completo = ? WHERE id = ?");
            $stmt->execute([$usuario_login, $senha_acesso, $nome_completo, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE usuarios SET usuario = ?, nome_completo = ? WHERE id = ?");
            $stmt->execute([$usuario_login, $nome_completo, $id]);
        }
        echo json_encode(['sucesso' => true, 'mensagem' => 'Usuário atualizado com sucesso!']);
        exit;
    } else {
        // MODO NOVO CADASTRO
        if (empty($senha_acesso)) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'A senha é obrigatória para novos cadastros.']);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, senha, nome_completo) VALUES (?, ?, ?)");
        $stmt->execute([$usuario_login, $senha_acesso, $nome_completo]);
        
        echo json_encode(['sucesso' => true, 'mensagem' => 'Usuário cadastrado com sucesso!']);
        exit;
    }

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no banco: ' . $e->getMessage()]);
    exit;
}

<?php
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

    // Captura o ID do usuário enviado pela URL pelo JavaScript
    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido para exclusão.']);
        exit;
    }

    // Executa o comando DELETE na tabela correta 'usuario'
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);


    echo json_encode(['sucesso' => true, 'mensagem' => 'Usuário removido com sucesso!']);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao deletar: ' . $e->getMessage()]);
}

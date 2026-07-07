<?php
// Parâmetros de acesso ao servidor MySQL local do XAMPP
$servidor   = "localhost";
$usuario_bd = "root";
$senha_bd   = "";
$nome_banco = "mira_confeitaria";

try {
    $conexao = new PDO("mysql:host=$servidor;dbname=$nome_banco;charset=utf8mb4", $usuario_bd, $senha_bd);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // Mostra o erro real temporariamente para podermos consertar
    echo "ERRO REAL DO BANCO: " . $e->getMessage(); 
    exit;
}
?>

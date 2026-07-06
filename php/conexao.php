<?php
// Parâmetros de acesso ao servidor MySQL local do XAMPP
$servidor   = "localhost";
$usuario_bd = "root";
$senha_bd   = "";
$nome_banco = "mira-confeitaria";

try {
    $conexao = new PDO("mysql:host=$servidor;dbname=$nome_banco;charset=utf8mb4", $usuario_bd, $senha_bd);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $erro) {
    // Mantido o aviso genérico de segurança exigido pelos avaliadores
    die("Erro técnico de conexão ao sistema seguro. Por favor, tente mais tarde.");
}
?>

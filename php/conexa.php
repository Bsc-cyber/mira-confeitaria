<?php
// Configurações do seu banco de dados local
$host = 'localhost';
$dbname = 'mira_confeitaria'; // Confirme se este é o nome exato do seu banco de dados
$user = 'root'; // Usuário padrão do XAMPP/WAMP
$pass = ''; // Senha padrão geralmente é vazia

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Configura para mostrar erros do banco na tela caso algo dê errado
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
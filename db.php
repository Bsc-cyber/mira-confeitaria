<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "mira_confeitaria";

// Conexão inicial para checar/criar banco
$conn = @new mysqli($host, $usuario, $senha);

if ($conn->connect_error) {
    die("<div style='padding:20px;background:#ffdddd;color:#b10000;font-family:sans-serif;'>
            <strong>Erro de Conexão:</strong> Não foi possível conectar ao MySQL. Certifique-se de que o 
            <strong>MySQL</strong> está com status <strong>Start (Verde)</strong> no Painel do XAMPP!
         </div>");
}

$conn->query("CREATE DATABASE IF NOT EXISTS $banco");
$conn->select_db($banco);

// Criação da tabela de fornecedores
$sql_table = "CREATE TABLE IF NOT EXISTS fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    tipo VARCHAR(50),
    cpf_cnpj VARCHAR(20),
    telefone VARCHAR(20),
    email VARCHAR(100),
    rua VARCHAR(100),
    numero VARCHAR(10),
    bairro VARCHAR(50),
    cidade VARCHAR(50),
    complemento VARCHAR(100),
    informacoes TEXT
)";
$conn->query($sql_table);
?>

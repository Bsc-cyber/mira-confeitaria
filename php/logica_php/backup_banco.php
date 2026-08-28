<?php
// Configurações do seu banco de dados local no XAMPP
$host = "localhost";
$usuario = "root"; // Usuário padrão do XAMPP
$senha = "";       // Senha padrão do XAMPP é vazia
$banco = "mira_confeitaria"; // SUBSTITUA pelo nome exato do seu banco de dados se for diferente

// Nome do arquivo que o usuário vai baixar
$nome_arquivo = "backup_mira_" . date("d-m-r_H-i-s") . ".sql";

// Define os cabeçalhos do navegador para forçar o download direto do arquivo
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $nome_arquivo . '"');
header('Pragma: no-cache');
header('Expires: 0');

try {
    // Conecta ao banco de dados via PDO
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Puxa todas as tabelas do banco
    $tabelas = [];
    $query = $pdo->query("SHOW TABLES");
    while ($linha = $query->fetch(PDO::FETCH_NUM)) {
        $tabelas[] = $linha[0];
    }
    
    // Monta o conteúdo estrutural do arquivo .sql
    echo "-- Backup MIRA Confeitaria\n";
    echo "-- Gerado em: " . date("d/m/Y H:i:s") . "\n\n";
    
    foreach ($tabelas as $tabela) {
        // Puxa a estrutura de criação da tabela (CREATE TABLE)
        $estrutura_query = $pdo->query("SHOW CREATE TABLE `$tabela`");
        $estrutura = $estrutura_query->fetch(PDO::FETCH_ASSOC);
        echo $estrutura['Create Table'] . ";\n\n";
        
        // Puxa todos os dados salvos nela (INSERT INTO)
        $dados_query = $pdo->query("SELECT * FROM `$tabela`");
        while ($dados = $dados_query->fetch(PDO::FETCH_ASSOC)) {
            $chaves = array_keys($dados);
            $valores = array_values($dados);
            
            // Trata as aspas para não quebrar o SQL
            $valores_tratados = array_map(function($val) use ($pdo) {
                if ($val === null) return 'NULL';
                return $pdo->quote($val);
            }, $valores);
            
            echo "INSERT INTO `$tabela` (`" . implode("`, `", $chaves) . "`) VALUES (" . implode(", ", $valores_tratados) . ");\n";
        }
        echo "\n\n";
    }
    exit;

} catch (Exception $e) {
    // Se der erro (ex: banco de dados com nome errado), mostra na tela
    header_remove(); 
    echo "Erro ao gerar cópia de segurança: " . $e->getMessage();
}

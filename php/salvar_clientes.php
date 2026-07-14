<?php
// 1. Chama a conexão com o banco
require_once 'conexao.php';

// 2. LÓGICA DE SALVAR, EDITAR E EXCLUIR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? 'salvar';
    $id = $_POST['id'] ?? '';
    
    // EXCLUIR
    if ($acao === 'excluir' && !empty($id)) {
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: clientes.php");
        exit;
    } 
    
    // SALVAR (NOVO) OU EDITAR
    if ($acao === 'salvar') {
        $dados = [
            $_POST['nome'], $_POST['telefone'], $_POST['cpf'], $_POST['data_nascimento'],
            $_POST['cep'], $_POST['rua'], $_POST['numero'], $_POST['bairro'],
            $_POST['complemento'], $_POST['cidade'], $_POST['email'], $_POST['observacoes']
        ];

        if (empty($id)) {
            // Se não tem ID, é um cliente NOVO (INSERT)
            $sql = "INSERT INTO clientes (nome, telefone, cpf, data_nascimento, cep, rua, numero, bairro, complemento, cidade, email, observacoes) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($dados);
        } else {
            // Se tem ID, está atualizando um cliente existente (UPDATE)
            $sql = "UPDATE clientes SET nome=?, telefone=?, cpf=?, data_nascimento=?, cep=?, rua=?, numero=?, bairro=?, complemento=?, cidade=?, email=?, observacoes=? WHERE id=?";
            $dados[] = $id; // Adiciona o ID no final da lista de dados para a cláusula WHERE
            $stmt = $pdo->prepare($sql);
            $stmt->execute($dados);
        }
        // Recarrega a página para limpar o formulário e atualizar a tabela
        header("Location: clientes.php");
        exit;
    }
}

// 3. BUSCAR OS CLIENTES PARA A TABELA (Ordem alfabética)
$stmt = $pdo->query("SELECT * FROM clientes ORDER BY nome ASC");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
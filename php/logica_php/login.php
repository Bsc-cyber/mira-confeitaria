<?php
// Inicia a sessão global para gerenciar o login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// GERAÇÃO DO TOKEN CSRF PARA SEGURANÇA DO SEU FORMULÁRIO
if (empty($_SESSION['token_csrf'])) {
    $_SESSION['token_csrf'] = bin2hex(random_bytes(32));
}

$mensagem_erro = "";

// INTERCEPTA O ENVIO DO FORMULÁRIO POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Valida o token de segurança para evitar ataques CSRF
    $token_post = $_POST['token_csrf'] ?? '';
    if ($token_post !== $_SESSION['token_csrf']) {
        $mensagem_erro = "Falha de segurança: Token inválido.";
    } else {
        
        // Captura e limpa os inputs digitados pelo usuário
        $usuario_input = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
        $senha_input = isset($_POST['senha']) ? trim($_POST['senha']) : '';

        if (empty($usuario_input) || empty($senha_input)) {
            $mensagem_erro = "Por favor, preencha todos os campos.";
        } else {
            
            // Configurações de acesso ao MySQL local
            $host = "localhost";
            $usuario_db = "root";
            $senha_db = "";
            $banco = "mira_confeitaria";

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario_db, $senha_db);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Busca o usuário na tabela do seu XAMPP ('usuarios')
                $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
                $stmt->execute([$usuario_input]);
                $dados_usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                // Compara a senha digitada diretamente com o texto puro salvo no banco
                if ($dados_usuario && $senha_input === $dados_usuario['senha']) {
                    
                    // CONFIGURA A SESSÃO COMPLETA ATUALIZADA COM O NÍVEL DO BANCO
                    $_SESSION['autenticado'] = true;
                    $_SESSION['id_usuario'] = $dados_usuario['id'];
                    $_SESSION['nome_usuario'] = $dados_usuario['nome_completo'];
                    
                    // MODIFICADO: Salva o nível real cadastrado (Administrador, Proprietário, Colaborador)
                    $_SESSION['nivel_usuario'] = !empty($dados_usuario['nivel']) ? $dados_usuario['nivel'] : 'colaborador';

                    // Redireciona para o painel de configurações na subpasta php/
                    header("Location: php/configuracoes.php");
                    exit;
                } else {
                    $mensagem_erro = "Usuário ou senha incorretos.";
                }

            } catch (Exception $e) {
                $mensagem_erro = "Erro de conexão local: " . $e->getMessage();
            }
        }
    }
}

<?php
// 1. CONFIGURAÇÕES DE SEGURANÇA: Configura propriedades de sessão a nível de servidor antes de iniciar
ini_set('session.cookie_httponly', 1); // Bloqueia o acesso aos cookies por scripts JS (evita roubo de sessão)
ini_set('session.cookie_secure', 1);   // Força o uso de conexões seguras HTTPS em produção

// 2. INICIALIZAÇÃO DA SESSÃO: Ativa o rastreamento seguro no servidor
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// REMOVIDO O REQUIRE DA CONEXAO DAQUI PARA NÃO DAR MAIS ERRO DE BANCO DE DADOS!

// 3. PROTEÇÃO CSRF: Cria um token criptográfico único para o formulário se ele não existir
if (empty($_SESSION['token_csrf'])) {
    $_SESSION['token_csrf'] = bin2hex(random_bytes(32)); 
}

// 4. PROCESSAMENTO DO FORMULÁRIO DE LOGIN
$mensagem_erro = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação estrita do Token CSRF para garantir que a requisição veio do seu próprio site
    if (!isset($_POST['token_csrf']) || $_POST['token_csrf'] !== $_SESSION['token_csrf']) {
        die("Erro de validação de segurança: Requisição inválida (CSRF).");
    }

    // Tratamento básico de entradas do usuário para mitigar injeções de scripts XSS
    $usuario_enviado = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_SPECIAL_CHARS);
    $senha_enviada = $_POST['senha'];

    // Credenciais provisórias solicitadas
    $usuario_unico = "admin";
    $senha_unica = "123";

    // Validação lógica do acesso
    if ($usuario_enviado === $usuario_unico && $senha_enviada === $senha_unica) {
        session_regenerate_id(true); // Regenera o ID para evitar fixação de sessão
        
        // Define as variáveis de sessão essenciais
        $_SESSION['usuario_logado'] = "Chef Confeiteiro Mira";
        $_SESSION['autenticado'] = true;
        
        // Redireciona o confeiteiro para a tela interna protegida
        header("Location: home.php");
        exit;
    } else {
        // Mensagem de erro genérica por boas práticas de segurança
        $mensagem_erro = "Usuário ou senha incorretos!";
    }
}
?>

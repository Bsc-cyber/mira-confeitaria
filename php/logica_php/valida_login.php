<?php
// Inicia a sessão global para que o home.php consiga ler as permissões depois
session_start();

$host = "localhost";
$usuario_db = "root";
$senha_db = "";
$banco = "mira_confeitaria";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario_db, $senha_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Captura os dados que vieram do formulário HTML de login
    $usuario_input = $_POST['usuario'] ?? trim('');
    $senha_input = $_POST['senha'] ?? trim('');

    if (empty($usuario_input) || empty($senha_input)) {
        header("Location: ../login.php?erro=campos_vazios");
        exit;
    }

    // Busca o usuário na tabela 'usuarios' batendo com a sua coluna 'usuario'
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
    $stmt->execute([$usuario_input]);
    $dados_usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Como as senhas estão salvas em texto puro (ex: '123'), fazemos a comparação direta
    if ($dados_usuario && $senha_input === $dados_usuario['senha']) {
        
        // CONFIGURA AS CHAVES DE SESSÃO EXATAS QUE O SEU HOME.PHP EXIGE PARA NÃO EXPULSAR O USUÁRIO
        $_SESSION['autenticado'] = true; 
        $_SESSION['id_usuario'] = $dados_usuario['id'];
        $_SESSION['nome_usuario'] = $dados_usuario['nome_completo'];

        // Redireciona com sucesso direto para a sua página de configurações
        header("Location: ../configuracoes.php");
        exit;
    } else {
        // Se o usuário não existir ou a senha estiver errada, volta para a tela de login com erro
        header("Location: ../login.php?erro=autenticacao");
        exit;
    }

} catch (Exception $e) {
    echo "Erro interno no servidor de login: " . $e->getMessage();
}

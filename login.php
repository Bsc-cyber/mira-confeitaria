<?php 
// Busca a inteligência do login dentro da estrutura modular de subpastas
require_once "php/logica_php/login.php"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIRA Login</title>
    <!-- VINCULAÇÃO DO CSS -->
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <!-- MÁSCARAS DE CURVA SUAVE (SVG) -->
    <svg width="0" height="0" style="position: absolute;">
        <defs>
            <clipPath id="curvaBranca" clipPathUnits="objectBoundingBox">
                <path d="M 1,0 L 0.18,0 C 0.08,0.25 0.22,0.70 0.02,1 L 1,1 Z" />
            </clipPath>
            <clipPath id="curvaDourada" clipPathUnits="objectBoundingBox">
                <path d="M 1,0 L 0.16,0 C 0.06,0.25 0.20,0.70 0,1 L 1,1 Z" />
            </clipPath>
        </defs>
    </svg>

    <div class="container-login">
        <div class="painel-esquerdo"></div>

        <div class="painel-direito">
            <div class="bloco-formulario">
                <h2>Bem-vindo de volta!</h2>
                <p class="subtitulo">Faça login para continuar acessando o sistema.</p>

                <!-- Exibe dinamicamente as mensagens de erro capturadas pelo PHP interno -->
                <?php if (!empty($mensagem_erro)): ?>
                    <div class="alerta-erro"><?php echo $mensagem_erro; ?></div>
                <?php endif; ?>

                <form action="login.php" method="POST" id="formLogin">
                    <input type="hidden" name="token_csrf" value="<?php echo $_SESSION['token_csrf']; ?>">

                    <div class="grupo-campo">
                        <label for="usuario">Usuário</label>
                        <input type="text" id="usuario" name="usuario" placeholder="Digite seu usuário" required>
                    </div>

                    <div class="grupo-campo">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                        <a href="#" class="link-esqueceu">Esqueceu sua senha?</a>
                    </div>

                    <button type="submit" class="btn-entrar">Entrar</button>
                </form>
            </div>

            <footer class="rodape-direitos">
                <span>© 2026 MIRA Sistemas. Todos os direitos reservados.</span>
            </footer>
        </div>
    </div>

    <!-- VINCULAÇÃO DO SCRIPT -->
    <script src="js/login.js"></script>
</body>
</html>

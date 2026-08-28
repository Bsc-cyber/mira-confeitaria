<?php
session_start();
session_unset();
session_destroy(); // Limpa todas as variáveis de sessão com segurança

// Volta dois níveis (../_../) para encontrar o login.php na raiz mestre do projeto
header("Location: ../../login.php"); 
exit;

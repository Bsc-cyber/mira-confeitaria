<?php
session_start();
session_unset();
session_destroy(); // Limpa todas as variáveis de sessão

// Volta um nível para encontrar o login.php na pasta raiz (mira-confeitaria/)
header("Location: ../login.php"); 
exit;

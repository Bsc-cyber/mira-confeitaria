<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Expulsa invasores que tentam entrar direto pela URL sem logar
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Limpa a sessão
session_unset();
session_destroy();

// Redireciona para login
header('Location: /cupom-amigo/src/views/auth/login.php');
exit;
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /src/views/auth/recuperar-senha.php');
    exit;
}

$_SESSION['recuperar_error'] = 'Este recurso está temporariamente desativado.';
header('Location: /src/views/auth/recuperar-senha.php');
exit;

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../models/Associado.php';
require_once __DIR__ . '/../models/Comercio.php';
require_once __DIR__ . '/../helpers/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /src/views/auth/login.php');
    exit;
}

$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

$usuario_clean = preg_replace('/\D/', '', $usuario);

if (empty($usuario_clean) || empty($senha)) {
    $_SESSION['login_error'] = 'Preencha usuário e senha.';
    header('Location: /src/views/auth/login.php');
    exit;
}

$len = strlen($usuario_clean);
if ($len === 11) {
    if (!validateCPF($usuario_clean)) {
        $_SESSION['login_error'] = 'CPF inválido. Verifique os dígitos informados.';
        header('Location: /src/views/auth/login.php');
        exit;
    }
    $type = 'associado';
} elseif ($len === 14) {
    if (!validateCNPJ($usuario_clean)) {
        $_SESSION['login_error'] = 'CNPJ inválido. Verifique os dígitos informados.';
        header('Location: /src/views/auth/login.php');
        exit;
    }
    $type = 'comercio';
} else {
    $_SESSION['login_error'] = 'CPF deve ter 11 dígitos e CNPJ deve ter 14 dígitos.';
    header('Location: /src/views/auth/login.php');
    exit;
}

try {
    if ($type === 'associado') {
        $associado = new Associado($pdo);
        $user = $associado->findByCpf($usuario_clean);
    } else {
        $comercio = new Comercio($pdo);
        $user = $comercio->findByCnpj($usuario_clean);
    }
} catch (PDOException $e) {
    $_SESSION['login_error'] = $e->getMessage();
    header('Location: /src/views/auth/login.php');
    exit;
}

if (!$user) {
    $_SESSION['login_error'] = 'Usuário não encontrado.';
    header('Location: /src/views/auth/login.php');
    exit;
}

$stored = $user['senha'];
$verified = false;

if (function_exists('password_verify') && password_verify($senha, $stored)) {
    $verified = true;
} else {
    if ($senha === $stored) {
        $verified = true;
        $_SESSION['password_migration_needed'] = true;
    }
}

if ($verified) {
    $_SESSION['user_type'] = $type;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['nome'] ?? null;
    $_SESSION['user_email'] = $user['email'] ?? null;

    header('Location: /index.php');
    exit;
} else {
    $_SESSION['login_error'] = 'Senha incorreta.';
    header('Location: /src/views/auth/login.php');
    exit;
}

?>

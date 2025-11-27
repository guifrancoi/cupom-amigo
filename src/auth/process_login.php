<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /cupom-amigo/src/views/auth/login.php');
    exit;
}

$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

// remove tudo que não for dígito
$usuario_clean = preg_replace('/\D/', '', $usuario);

if (empty($usuario_clean) || empty($senha)) {
    $_SESSION['login_error'] = 'Preencha usuário e senha.';
    header('Location: /cupom-amigo/src/views/auth/login.php');
    exit;
}

$len = strlen($usuario_clean);
if ($len === 11) {
    $type = 'associado';
    $sql = 'SELECT cpf_associado AS id, senha_associado AS senha, nome_associado AS nome FROM ASSOCIADO WHERE cpf_associado = :id LIMIT 1';
} elseif ($len === 14) {
    $type = 'comercio';
    $sql = 'SELECT cnpj_comercio AS id, senha_comercio AS senha, nome_fantasia_comercio AS nome FROM COMERCIO WHERE cnpj_comercio = :id LIMIT 1';
} else {
    $_SESSION['login_error'] = 'CPF ou CNPJ inválido. Use apenas números.';
    header('Location: /cupom-amigo/src/views/auth/login.php');
    exit;
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $usuario_clean]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    $_SESSION['login_error'] = 'Erro ao acessar o banco de dados.';
    header('Location: /cupom-amigo/src/views/auth/login.php');
    exit;
}

if (!$user) {
    $_SESSION['login_error'] = 'Usuário não encontrado.';
    header('Location: /cupom-amigo/src/views/auth/login.php');
    exit;
}

$stored = $user['senha'];
$verified = false;

// Tenta verificar senha com password_verify (recomendado)
if (function_exists('password_verify') && password_verify($senha, $stored)) {
    $verified = true;
} else {
    // Fallback temporário: comparação em texto plano (apenas enquanto migrar dados)
    if ($senha === $stored) {
        $verified = true;
        $_SESSION['password_migration_needed'] = true;
    }
}

if ($verified) {
    $_SESSION['user_type'] = $type;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['nome'] ?? null;

    // Redireciona para área principal
    header('Location: /cupom-amigo/public/index.php');
    exit;
} else {
    $_SESSION['login_error'] = 'Senha incorreta.';
    header('Location: /cupom-amigo/src/views/auth/login.php');
    exit;
}

?>

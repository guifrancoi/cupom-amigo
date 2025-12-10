<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAuthenticated(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAssociado(): bool {
    return isAuthenticated() && ($_SESSION['user_type'] ?? '') === 'associado';
}

function isComercio(): bool {
    return isAuthenticated() && ($_SESSION['user_type'] ?? '') === 'comercio';
}

function getUserId(): ?string {
    return $_SESSION['user_id'] ?? null;
}

function getUserType(): ?string {
    return $_SESSION['user_type'] ?? null;
}

function getUserName(): ?string {
    return $_SESSION['user_name'] ?? null;
}

function requireAuth(string $redirect = ''): void {
    if (!isAuthenticated()) {
        if (!empty($redirect)) {
            $_SESSION['redirect_after_login'] = $redirect;
        }
        header('Location: /src/views/auth/login.php');
        exit;
    }
}

function requireAssociado(): void {
    requireAuth();
    if (!isAssociado()) {
        $_SESSION['auth_error'] = 'Acesso restrito a associados.';
        header('Location: /src/views/auth/login.php');
        exit;
    }
}

function requireComercio(): void {
    requireAuth();
    if (!isComercio()) {
        $_SESSION['auth_error'] = 'Acesso restrito a comerciantes.';
        header('Location: /src/views/auth/login.php');
        exit;
    }
}

function redirectIfAuthenticated(): void {
    if (isAuthenticated()) {
        if (isAssociado()) {
            header('Location: /src/controllers/AssociadoController.php');
        } else {
            header('Location: /src/controllers/CupomController.php');
        }
        exit;
    }
}

function logout(): void {
    $_SESSION = [];
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
}

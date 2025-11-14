<?php
session_start();

// Mensagens flash (setadas pelo handler)
$error = $_SESSION['login_error'] ?? null;
$message = $_SESSION['login_message'] ?? null;
$pw_migration = $_SESSION['password_migration_needed'] ?? null;
unset($_SESSION['login_error'], $_SESSION['login_message'], $_SESSION['password_migration_needed']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;max-width:700px;margin:24px;}label{display:block;margin:8px 0}</style>
</head>
<body>
    <h1>Login</h1>
    <?php if (!empty($message)): ?><p style="color:green"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <?php if (!empty($error)): ?><p style="color:red"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <?php if (!empty($pw_migration)): ?><p style="color:orange">Senha armazenada em texto plano — por favor altere sua senha.</p><?php endif; ?>

    <form method="post" action="/cupom-amigo/src/auth/process_login.php">
        <label>CPF ou CNPJ (apenas números):
            <input type="text" name="usuario" inputmode="numeric" required placeholder="Ex: 01234567890 ou 01234567000189">
        </label>
        <label>Senha:
            <input type="password" name="senha" required>
        </label>
        <button type="submit">Entrar</button>
    </form>
    <p><a href="/cupom-amigo/src/views/auth/cadastrar.php">Cadastrar usuário</a></p>
</body>
</html>

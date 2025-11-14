<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;}</style>
</head>
<body>
    <h1>Cadastrar Usuário</h1>
    <?php if (!empty($message)): ?><p style="color:green"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <?php if (!empty($error)): ?><p style="color:red"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="post" action="">
        <label>Usuário: <input type="text" name="username" required></label><br>
        <label>Senha: <input type="password" name="password" required></label><br>
        <button type="submit">Cadastrar</button>
    </form>
    <p><a href="login.php">Voltar ao login</a></p>
</body>
</html>

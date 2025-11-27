<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = $_SESSION['reservar_error'] ?? null;
$message = $_SESSION['reservar_message'] ?? null;
unset($_SESSION['reservar_error'], $_SESSION['reservar_message']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cupons Disponíveis - Associado</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .navbar { margin-bottom: 20px; }
        .navbar a { padding: 10px 15px; margin-right: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .navbar a:hover { background: #0056b3; }
        .cupom-item { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; background: #f9f9f9; }
        .cupom-item h3 { margin: 0 0 10px 0; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="/cupom-amigo/public/index.php">Home</a>
        <a href="/cupom-amigo/src/auth/logout.php">Sair</a>
    </div>

    <h1>Cupons Disponíveis</h1>

    <?php if (!empty($message)): ?><div style="color:green"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div style="color:red"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <?php if (empty($cupons)): ?>
        <p>Nenhum cupom ativo disponível.</p>
    <?php else: ?>
        <?php foreach ($cupons as $cupom): ?>
            <div class="cupom-item">
                <h3><?= htmlspecialchars($cupom['dsc_cupom'] ?? 'N/A') ?></h3>
                <p><strong>Cupom:</strong> <?= htmlspecialchars($cupom['num_cupom']) ?></p>
                <p><strong>Desconto:</strong> <?= htmlspecialchars($cupom['vlr_desconto']) ?>%</p>
                <p><strong>Validade:</strong> <?= htmlspecialchars($cupom['dta_inicio']) ?> até <?= htmlspecialchars($cupom['dta_fim']) ?></p>
                <form method="post" action="/cupom-amigo/src/controllers/AssociadoController.php" style="margin-top:10px;">
                    <input type="hidden" name="action" value="reservar">
                    <input type="hidden" name="num_cupom" value="<?= htmlspecialchars($cupom['num_cupom']) ?>">
                    <button type="submit">Reservar Cupom</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

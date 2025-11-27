<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Cupom.php';

// Validar autenticação (apenas comerciante)
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'comercio') {
    header('Location: /cupom-amigo/src/views/auth/login.php');
    exit;
}

$error = $_SESSION['registrar_error'] ?? null;
$message = $_SESSION['registrar_message'] ?? null;
unset($_SESSION['registrar_error'], $_SESSION['registrar_message']);

$cupomModel = new Cupom(Database::getConnection());
$cnpjUser = $_SESSION['user_id'] ?? null;
$cuponsAtivos = $cupomModel->listarAtivos($cnpjUser);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registrar Uso de Cupom</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; max-width: 800px; }
        .navbar { margin-bottom: 20px; }
        .navbar a { padding: 10px 15px; margin-right: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .navbar a:hover { background: #0056b3; }
        .cupom-list { margin-top: 20px; }
        .cupom-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            background: #f9f9f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .cupom-info h3 { margin: 0 0 5px 0; }
        .cupom-info p { margin: 3px 0; font-size: 14px; color: #555; }
        .cupom-actions button {
            padding: 8px 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .cupom-actions button:hover { background: #218838; }
        .error { color: #d32f2f; margin-bottom: 15px; }
        .message { color: #388e3c; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="/cupom-amigo/src/views/cupons/listar.php">Voltar</a>
    </div>

    <h1>Registrar Uso de Cupom</h1>
    <p>Selecione um cupom ativo e confirme o uso apresentado pelo associado.</p>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="cupom-list">
        <?php if (empty($cuponsAtivos)): ?>
            <p>Nenhum cupom ativo disponível.</p>
        <?php else: ?>
            <?php foreach ($cuponsAtivos as $cupom): ?>
                <div class="cupom-item">
                    <div class="cupom-info">
                        <h3><?= htmlspecialchars($cupom['dsc_cupom'] ?? 'N/A') ?></h3>
                        <p><strong>Cupom:</strong> <?= htmlspecialchars($cupom['num_cupom']) ?></p>
                        <p><strong>Desconto:</strong> <?= htmlspecialchars($cupom['vlr_desconto']) ?>%</p>
                        <p><strong>Válido até:</strong> <?= htmlspecialchars($cupom['dta_fim']) ?></p>
                    </div>
                    <div class="cupom-actions">
                        <form method="post" action="/cupom-amigo/src/controllers/CupomController.php" style="margin: 0;">
                            <input type="hidden" name="action" value="registrarUso">
                            <input type="hidden" name="num_cupom" value="<?= htmlspecialchars($cupom['num_cupom']) ?>">
                            <label>CPF do Associado: <input type="text" name="cpf_associado" placeholder="Digite CPF (apenas números)" required></label>
                            <button type="submit" onclick="return confirm('Confirmar uso deste cupom?')">Confirmar Uso</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>

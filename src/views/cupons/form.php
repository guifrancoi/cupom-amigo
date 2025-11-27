<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';

// Validar autenticação (apenas comerciante)
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'comercio') {
    header('Location: /cupom-amigo/src/views/auth/login.php');
    exit;
}

$error = $_SESSION['form_error'] ?? null;
$message = $_SESSION['form_message'] ?? null;
unset($_SESSION['form_error'], $_SESSION['form_message']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Cupom</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; max-width: 600px; }
        .navbar { margin-bottom: 20px; }
        .navbar a { padding: 10px 15px; margin-right: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .navbar a:hover { background: #0056b3; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #007bff; }
        .form-actions { margin-top: 20px; }
        .form-actions button { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px; }
        .form-actions button:hover { background: #218838; }
        .form-actions a { padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; display: inline-block; }
        .form-actions a:hover { background: #5a6268; }
        .error { color: #d32f2f; margin-bottom: 15px; }
        .message { color: #388e3c; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="/cupom-amigo/src/views/cupons/listar.php">Voltar</a>
    </div>

    <h1>Criar Novo Cupom</h1>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" action="/cupom-amigo/src/controllers/CupomController.php">
        <input type="hidden" name="action" value="criar">

        <div class="form-group">
            <label for="dsc_cupom">Título da Promoção:</label>
            <input type="text" id="dsc_cupom" name="dsc_cupom" required placeholder="Ex: Desconto em Alimentos">
        </div>

        <div class="form-group">
            <label for="dta_inicio">Data de Início:</label>
            <input type="date" id="dta_inicio" name="dta_inicio" required>
        </div>

        <div class="form-group">
            <label for="dta_fim">Data de Término:</label>
            <input type="date" id="dta_fim" name="dta_fim" required>
        </div>

        <div class="form-group">
            <label for="vlr_desconto">Percentual de Desconto (%):</label>
            <input type="number" id="vlr_desconto" name="vlr_desconto" step="0.01" min="0" max="100" required placeholder="Ex: 10.50">
        </div>

        <div class="form-group">
            <label for="qtd_cupom">Quantidade de Cupons:</label>
            <input type="number" id="qtd_cupom" name="qtd_cupom" min="1" required placeholder="Ex: 50">
        </div>

        <div class="form-actions">
            <button type="submit">Criar Cupom</button>
            <a href="/cupom-amigo/src/views/cupons/listar.php">Cancelar</a>
        </div>
    </form>
</body>
</html>

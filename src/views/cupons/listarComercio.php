<?php
// Variáveis $cupons e $filter devem ser providas pelo controller.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciamento de Cupons</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .navbar { margin-bottom: 20px; }
        .navbar a, .navbar button { 
            padding: 10px 15px; 
            margin-right: 10px; 
            background: #007bff; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            text-decoration: none;
        }
        .navbar a:hover, .navbar button:hover { background: #0056b3; }
        .navbar a.active { background: #0056b3; }
        .filter-tabs { margin-bottom: 20px; }
        .filter-tabs button {
            padding: 8px 12px;
            margin-right: 5px;
            background: #ccc;
            border: 1px solid #999;
            cursor: pointer;
            border-radius: 4px;
        }
        .filter-tabs button.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        .cupom-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            background: #f9f9f9;
        }
        .cupom-item h3 { margin: 0 0 10px 0; }
        .cupom-info { font-size: 14px; color: #555; }
        .cupom-actions { margin-top: 10px; }
        .cupom-actions a, .cupom-actions button {
            padding: 6px 12px;
            margin-right: 5px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .cupom-actions a:hover, .cupom-actions button:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="/cupom-amigo/public/index.php">Home</a>
        <a href="/cupom-amigo/src/views/cupons/form.php">+ Criar Cupom</a>
        <a href="/cupom-amigo/src/views/cupons/registrar.php">Registrar Uso</a>
        <a href="/cupom-amigo/src/auth/logout.php">Sair</a>
    </div>

    <h1>Gerenciamento de Cupons</h1>

    <div class="filter-tabs">
        <button class="<?= $filter === 'ativos' ? 'active' : '' ?>" onclick="window.location.href='?filter=ativos'">Cupons Ativos</button>
        <button class="<?= $filter === 'utilizados' ? 'active' : '' ?>" onclick="window.location.href='?filter=utilizados'">Cupons Utilizados</button>
        <button class="<?= $filter === 'vencidos' ? 'active' : '' ?>" onclick="window.location.href='?filter=vencidos'">Cupons Vencidos/Não Usados</button>
    </div>

    <?php if (empty($cupons)): ?>
        <p>Nenhum cupom encontrado nesta categoria.</p>
    <?php else: ?>
        <?php foreach ($cupons as $cupom): ?>
            <div class="cupom-item">
                <h3><?= htmlspecialchars($cupom['dsc_cupom'] ?? 'N/A') ?></h3>
                <div class="cupom-info">
                    <p><strong>Número:</strong> <?= htmlspecialchars($cupom['num_cupom'] ?? '') ?></p>
                    <p><strong>Desconto:</strong> <?= htmlspecialchars($cupom['vlr_desconto'] ?? '0') ?>%</p>
                    <p><strong>Quantidade:</strong> <?= htmlspecialchars($cupom['qtd_cupom'] ?? '0') ?> cupons</p>
                    <p><strong>Validade:</strong> <?= htmlspecialchars($cupom['dta_inicio'] ?? '') ?> até <?= htmlspecialchars($cupom['dta_fim'] ?? '') ?></p>
                </div>
                <?php if ($filter === 'ativos'): ?>
                    <div class="cupom-actions">
                        <a href="/cupom-amigo/src/views/cupons/registrar.php?cupom=<?= urlencode($cupom['num_cupom']) ?>">Registrar Uso</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
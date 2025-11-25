<h2>Cupons Disponíveis</h2>
<ul>
<?php foreach ($cupons as $cupom): ?>
    <li><?= htmlspecialchars($cupom['num_cupom'] ?? '') ?> - <?= htmlspecialchars($cupom['dsc_cupom'] ?? '') ?> (Validade: <?= htmlspecialchars($cupom['dta_inicio'] ?? '') ?> até <?= htmlspecialchars($cupom['dta_fim'] ?? '') ?>)</li>
<?php endforeach; ?>
</ul>

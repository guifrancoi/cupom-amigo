<h2>Cupons Disponíveis</h2>
<ul>
<?php foreach ($cupons as $cupom): ?>
    <li><?= htmlspecialchars($cupom['titulo']) ?> - <?= htmlspecialchars($cupom['descricao']) ?></li>
<?php endforeach; ?>
</ul>

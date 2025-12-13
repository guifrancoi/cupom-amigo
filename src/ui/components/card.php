<?php

function startCard($title = null, $description = null, $class = '') {
    ?>
    <div class="card <?= htmlspecialchars($class) ?>">
        <?php if ($title): ?>
        <header>
            <h2><?= htmlspecialchars($title) ?></h2>
            <?php if ($description): ?>
            <p><?= htmlspecialchars($description) ?></p>
            <?php endif; ?>
        </header>
        <?php endif; ?>
        <section>
    <?php
}

function endCard($footerContent = null) {
    ?>
        </section>
        <?php if ($footerContent): ?>
        <footer>
            <?= $footerContent ?>
        </footer>
        <?php endif; ?>
    </div>
    <?php
}

function renderCard($title, $content, $description = null, $footer = null, $class = '') {
    ob_start();
    ?>
    <div class="card <?= htmlspecialchars($class) ?>">
        <?php if ($title): ?>
        <header>
            <h2><?= htmlspecialchars($title) ?></h2>
            <?php if ($description): ?>
            <p><?= htmlspecialchars($description) ?></p>
            <?php endif; ?>
        </header>
        <?php endif; ?>
        <section>
            <?= $content ?>
        </section>
        <?php if ($footer): ?>
        <footer>
            <?= $footer ?>
        </footer>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
?>

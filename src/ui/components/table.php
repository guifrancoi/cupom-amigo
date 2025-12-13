<?php

function startTable($headers = [], $class = '') {
    ob_start();
    ?>
    <div class="rounded-md border overflow-x-auto">
        <table class="table <?= htmlspecialchars($class) ?>">
            <?php if (!empty($headers)): ?>
            <thead>
                <tr>
                    <?php foreach ($headers as $header): ?>
                        <th><?= htmlspecialchars($header) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <?php endif; ?>
            <tbody>
    <?php
    echo ob_get_clean();
}

function endTable($footerContent = null) {
    ob_start();
    ?>
            </tbody>
            <?php if ($footerContent): ?>
            <tfoot>
                <tr>
                    <td colspan="100%"><?= $footerContent ?></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
    <?php
    echo ob_get_clean();
}

function renderTableRow($cells, $escape = true) {
    ob_start();
    ?>
    <tr>
        <?php foreach ($cells as $cell): ?>
            <td><?= $escape ? htmlspecialchars($cell) : $cell ?></td>
        <?php endforeach; ?>
    </tr>
    <?php
    echo ob_get_clean();
}

function renderEmptyState($message = 'Nenhum item encontrado.', $icon = null) {
    $defaultIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" y1="22" x2="12" y2="12"/></svg>';
    
    ob_start();
    ?>
    <div class="flex flex-col items-center justify-center py-12 text-center">
        <?= $icon ?? $defaultIcon ?>
        <p class="mt-4 text-muted-foreground"><?= htmlspecialchars($message) ?></p>
    </div>
    <?php
    return ob_get_clean();
}
?>

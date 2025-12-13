<?php

function renderDialog($id, $title, $description, $buttons = [], $type = 'confirm') {
    if (empty($buttons)) {
        if ($type === 'alert') {
            $buttons = [
                ['label' => 'Entendi', 'class' => 'btn', 'action' => "document.getElementById('$id').close()"]
            ];
        } else {
            $buttons = [
                ['label' => 'Cancelar', 'class' => 'btn-outline', 'action' => "document.getElementById('$id').close()"],
                ['label' => 'Confirmar', 'class' => 'btn', 'action' => "document.getElementById('$id').close()"]
            ];
        }
    }
    
    ob_start();
    ?>
    <dialog id="<?= htmlspecialchars($id) ?>" class="dialog" aria-labelledby="<?= htmlspecialchars($id) ?>-title" aria-describedby="<?= htmlspecialchars($id) ?>-description">
        <div>
            <header>
                <h2 id="<?= htmlspecialchars($id) ?>-title"><?= htmlspecialchars($title) ?></h2>
                <p id="<?= htmlspecialchars($id) ?>-description"><?= $description ?></p>
            </header>

            <footer>
                <?php foreach ($buttons as $button): ?>
                    <button 
                        class="<?= htmlspecialchars($button['class'] ?? 'btn') ?>" 
                        onclick="<?= htmlspecialchars($button['action'] ?? "document.getElementById('$id').close()") ?>">
                        <?= htmlspecialchars($button['label']) ?>
                    </button>
                <?php endforeach; ?>
            </footer>
        </div>
    </dialog>
    <?php
    return ob_get_clean();
}

function renderConfirmDialog($id, $title, $description, $formId, $confirmLabel = 'Confirmar', $cancelLabel = 'Cancelar') {
    $buttons = [
        ['label' => $cancelLabel, 'class' => 'btn-outline', 'action' => "document.getElementById('$id').close()"],
        ['label' => $confirmLabel, 'class' => 'btn', 'action' => "document.getElementById('$formId').submit()"]
    ];
    
    return renderDialog($id, $title, $description, $buttons, 'confirm');
}

function renderAlertDialog($id, $title, $description, $okLabel = 'Entendi') {
    $buttons = [
        ['label' => $okLabel, 'class' => 'btn', 'action' => "document.getElementById('$id').close()"]
    ];
    
    return renderDialog($id, $title, $description, $buttons, 'alert');
}

function openDialog($dialogId) {
    return "document.getElementById('" . htmlspecialchars($dialogId) . "').showModal()";
}

function closeDialog($dialogId) {
    return "document.getElementById('" . htmlspecialchars($dialogId) . "').close()";
}
?>

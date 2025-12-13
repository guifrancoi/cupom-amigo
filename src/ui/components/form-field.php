<?php

function renderInput($name, $label, $type = 'text', $options = []) {
    $id = $options['id'] ?? $name;
    $placeholder = $options['placeholder'] ?? '';
    $required = $options['required'] ?? false;
    $value = $options['value'] ?? '';
    $hint = $options['hint'] ?? null;
    $error = $options['error'] ?? null;
    $disabled = $options['disabled'] ?? false;
    $attrs = $options['attrs'] ?? [];
    
    $inputClass = 'input';
    if ($error) {
        $inputClass .= ' aria-invalid';
    }
    
    $attrString = '';
    foreach ($attrs as $key => $val) {
        $attrString .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($val) . '"';
    }
    
    ob_start();
    ?>
    <div class="grid gap-2">
        <label for="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($label) ?></label>
        <input 
            type="<?= htmlspecialchars($type) ?>" 
            id="<?= htmlspecialchars($id) ?>" 
            name="<?= htmlspecialchars($name) ?>"
            class="<?= $inputClass ?>"
            <?php if ($placeholder): ?>placeholder="<?= htmlspecialchars($placeholder) ?>"<?php endif; ?>
            <?php if ($required): ?>required<?php endif; ?>
            <?php if ($disabled): ?>disabled<?php endif; ?>
            <?php if ($value !== ''): ?>value="<?= htmlspecialchars($value) ?>"<?php endif; ?>
            <?php if ($error): ?>aria-invalid="true"<?php endif; ?>
            <?= $attrString ?>
        >
        <?php if ($hint && !$error): ?>
            <p class="text-sm text-muted-foreground"><?= htmlspecialchars($hint) ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="text-sm text-destructive"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

function renderSelect($name, $label, $options_list, $options = []) {
    $id = $options['id'] ?? $name;
    $required = $options['required'] ?? false;
    $selected = $options['selected'] ?? '';
    $placeholder = $options['placeholder'] ?? '-- Selecione --';
    $hint = $options['hint'] ?? null;
    $error = $options['error'] ?? null;
    
    ob_start();
    ?>
    <div class="grid gap-2">
        <label for="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($label) ?></label>
        <select 
            id="<?= htmlspecialchars($id) ?>" 
            name="<?= htmlspecialchars($name) ?>"
            class="select"
            <?php if ($required): ?>required<?php endif; ?>
            <?php if ($error): ?>aria-invalid="true"<?php endif; ?>
        >
            <option value=""><?= htmlspecialchars($placeholder) ?></option>
            <?php foreach ($options_list as $value => $text): ?>
                <option value="<?= htmlspecialchars($value) ?>" <?= $selected == $value ? 'selected' : '' ?>>
                    <?= htmlspecialchars($text) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if ($hint && !$error): ?>
            <p class="text-sm text-muted-foreground"><?= htmlspecialchars($hint) ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="text-sm text-destructive"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

function renderRadioGroup($name, $label, $options_list, $options = []) {
    $selected = $options['selected'] ?? '';
    $required = $options['required'] ?? false;
    
    ob_start();
    ?>
    <div class="grid gap-3">
        <label><?= htmlspecialchars($label) ?></label>
        <div class="flex flex-wrap gap-4">
            <?php foreach ($options_list as $value => $text): ?>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input 
                        type="radio" 
                        name="<?= htmlspecialchars($name) ?>" 
                        value="<?= htmlspecialchars($value) ?>"
                        class="radio"
                        <?= $selected == $value ? 'checked' : '' ?>
                        <?php if ($required): ?>required<?php endif; ?>
                    >
                    <span><?= htmlspecialchars($text) ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>

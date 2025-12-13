<?php

function renderButton($text, $type = 'button', $variant = 'primary', $size = 'default', $attrs = [], $icon = null) {
    $classes = ['btn'];
    
    if ($variant !== 'primary') {
    if ($variant !== 'primary') {
        $classes = ["btn-{$variant}"];
    }
    

    if ($size === 'sm') {
        $classes[0] = str_replace('btn', 'btn-sm', $classes[0]);
    } elseif ($size === 'lg') {
        $classes[0] = str_replace('btn', 'btn-lg', $classes[0]);
    }
    
    if (!empty($attrs['class'])) {
        $classes[] = $attrs['class'];
        unset($attrs['class']);
    }
    
    $attrString = '';
    foreach ($attrs as $key => $value) {
        $attrString .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
    }
    
    ob_start();
    ?>
    <button type="<?= htmlspecialchars($type) ?>" class="<?= implode(' ', $classes) ?>"<?= $attrString ?>>
        <?php if ($icon): ?>
            <?= $icon ?>
        <?php endif; ?>
        <?= htmlspecialchars($text) ?>
    </button>
    <?php
    return ob_get_clean();
}

function renderButtonLink($text, $href, $variant = 'primary', $size = 'default', $attrs = [], $icon = null) {
    $classes = ['btn'];
    
    if ($variant !== 'primary') {
        $classes = ["btn-{$variant}"];
    }
    
    if ($size === 'sm') {
        $classes[0] = str_replace('btn', 'btn-sm', $classes[0]);
    } elseif ($size === 'lg') {
        $classes[0] = str_replace('btn', 'btn-lg', $classes[0]);
    }
    
    if (!empty($attrs['class'])) {
        $classes[] = $attrs['class'];
        unset($attrs['class']);
    }
    
    $attrString = '';
    foreach ($attrs as $key => $value) {
        $attrString .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
    }
    
    ob_start();
    ?>
    <a href="<?= htmlspecialchars($href) ?>" class="<?= implode(' ', $classes) ?>"<?= $attrString ?>>
        <?php if ($icon): ?>
            <?= $icon ?>
        <?php endif; ?>
        <?= htmlspecialchars($text) ?>
    </a>
    <?php
    return ob_get_clean();
}

function iconPlus() {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>';
}

function iconLogout() {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>';
}

function iconCheck() {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
}
?>

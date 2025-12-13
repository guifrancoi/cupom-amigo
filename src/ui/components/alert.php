<?php

function renderAlert($type, $title, $message = null, $dismissible = false) {
    $class = $type === 'error' || $type === 'destructive' ? 'alert-destructive' : 'alert';
    $textColor = $type === 'success' ? 'text-green-600' : '';
    
    $icons = [
        'success' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>',
        'error' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
        'warning' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        'info' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
        'destructive' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>'
    ];
    
    $icon = $icons[$type] ?? $icons['info'];
    
    ob_start();
    ?>
    <div class="<?= $class ?> mb-4 -mt-2 <?= $textColor ?>">
        <?= $icon ?>
        <h2><?= htmlspecialchars($title) ?></h2>
        <?php if ($message): ?>
            <section><?= htmlspecialchars($message) ?></section>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

function showSessionAlerts() {
    $output = '';
    
    if (!empty($_SESSION['success_message'])) {
        $output .= renderAlert('success', $_SESSION['success_message']);
        unset($_SESSION['success_message']);
    }
    
    if (!empty($_SESSION['error_message'])) {
        $output .= renderAlert('error', $_SESSION['error_message']);
        unset($_SESSION['error_message']);
    }
    
    if (!empty($_SESSION['warning_message'])) {
        $output .= renderAlert('warning', $_SESSION['warning_message']);
        unset($_SESSION['warning_message']);
    }
    
    if (!empty($_SESSION['info_message'])) {
        $output .= renderAlert('info', $_SESSION['info_message']);
        unset($_SESSION['info_message']);
    }
    
    return $output;
}
?>

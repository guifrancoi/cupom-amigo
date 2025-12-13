<?php

function renderBadge($text, $variant = 'primary') {
    $class = 'badge';
    
    switch ($variant) {
        case 'secondary':
            $class = 'badge-secondary';
            break;
        case 'destructive':
            $class = 'badge-destructive';
            break;
        case 'outline':
            $class = 'badge-outline';
            break;
        case 'success':
            $class = 'badge-secondary bg-green-500 text-white dark:bg-green-600';
            break;
        case 'warning':
            $class = 'badge-secondary bg-yellow-500 text-white dark:bg-yellow-600';
            break;
        case 'scheduled':
            $class = 'badge-secondary bg-blue-500 text-white dark:bg-blue-600';
            break;
    }
    
    return '<span class="' . $class . '">' . htmlspecialchars($text) . '</span>';
}

function renderCupomStatusBadge($status) {
    switch ($status) {
        case 'ativo':
        case 'A':
            return renderBadge('Ativo', 'success');
        case 'reservado':
        case 'R':
            return renderBadge('Reservado', 'primary');
        case 'utilizado':
        case 'U':
            return renderBadge('Utilizado', 'secondary');
        case 'vencido':
        case 'V':
            return renderBadge('Vencido', 'destructive');
        default:
            return renderBadge($status, 'outline');
    }
}
?>

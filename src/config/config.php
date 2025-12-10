<?php

define('ENVIRONMENT', 'environment');

if (ENVIRONMENT === 'production') {
    define('BASE_URL', '/');
    define('PROJECT_ROOT', '/htdocs');
} else {
    define('BASE_URL', '/');
    define('PROJECT_ROOT', 'C:/xampp/htdocs');
}

date_default_timezone_set('America/Sao_Paulo');

function getAppBasePath() {
    $scriptPath = $_SERVER['SCRIPT_NAME'];
    $scriptDir = dirname($scriptPath);
    
    $relativePath = str_replace(rtrim(BASE_URL, '/'), '', $scriptDir);
    
    if (empty($relativePath) || $relativePath === '/') {
        return BASE_URL;
    }
    
    $depth = substr_count(trim($relativePath, '/'), '/') + 1;
    
    return str_repeat('../', $depth);
}

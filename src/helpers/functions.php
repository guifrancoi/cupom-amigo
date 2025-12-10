<?php

function formatCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);
    if (strlen($cpf) !== 11) return $cpf;
    return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
}

function formatCNPJ($cnpj) {
    $cnpj = preg_replace('/\D/', '', $cnpj);
    if (strlen($cnpj) !== 14) return $cnpj;
    return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
}

function validateCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);
    
    if (strlen($cpf) !== 11) return false;
    
    if (preg_match('/^(\d)\1{10}$/', $cpf)) return false;
    
    for ($t = 9; $t < 11; $t++) {
        $sum = 0;
        for ($i = 0; $i < $t; $i++) {
            $sum += $cpf[$i] * (($t + 1) - $i);
        }
        $digit = ((10 * $sum) % 11) % 10;
        if ($cpf[$t] != $digit) return false;
    }
    
    return true;
}

function validateCNPJ($cnpj) {
    $cnpj = preg_replace('/\D/', '', $cnpj);
    
    if (strlen($cnpj) !== 14) return false;
    
    if (preg_match('/^(\d)\1{13}$/', $cnpj)) return false;
    
    $sum = 0;
    $sum = 0;
    $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    for ($i = 0; $i < 12; $i++) {
        $sum += $cnpj[$i] * $weights[$i];
    }
    $digit1 = ($sum % 11 < 2) ? 0 : 11 - ($sum % 11);
    if ($cnpj[12] != $digit1) return false;
    
    $sum = 0;
    $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    for ($i = 0; $i < 13; $i++) {
        $sum += $cnpj[$i] * $weights[$i];
    }
    $digit2 = ($sum % 11 < 2) ? 0 : 11 - ($sum % 11);
    if ($cnpj[13] != $digit2) return false;
    
    return true;
}

function formatDate($date, $format = 'd/m/Y') {
    if (empty($date)) return '';
    $dt = new DateTime($date);
    return $dt->format($format);
}

function formatCurrency($value) {
    return 'R$ ' . number_format($value, 2, ',', '.');
}

function formatPercent($value) {
    return number_format($value, 0) . '%';
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function onlyNumbers($string) {
    return preg_replace('/\D/', '', $string);
}

function setSuccess($message) {
    $_SESSION['success_message'] = $message;
}

function setError($message) {
    $_SESSION['error_message'] = $message;
}

function setWarning($message) {
    $_SESSION['warning_message'] = $message;
}

function setInfo($message) {
    $_SESSION['info_message'] = $message;
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function post($key, $default = '') {
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

function get($key, $default = '') {
    return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
}
?>

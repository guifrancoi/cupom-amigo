<?php
require_once __DIR__ . 'database.php';

try {
    $pdo = Database::getConnection();
} catch (PDOException $e) {
    echo "Conexão falhou: " . $e->getMessage();
}
?>
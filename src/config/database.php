<?php
class Database {
    public static function getConnection(): PDO {
        // Desenvolvimento Local (XAMPP)
        $host = '127.0.0.1';
        $dbname = 'cupom_amigo';
        $user = 'root';
        $pass = '';

        $dsn = "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new PDO($dsn, $user, $pass, $options);
    }
}

?>
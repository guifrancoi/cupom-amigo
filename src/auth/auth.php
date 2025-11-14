<?php
session_start();

function require_auth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../src/views/auth/login.php');
        exit;
    }
}

?>
<?php
require_once __DIR__ . '/../models/Cupom.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/auth.php';
class CupomController {
    public function index() {
        require_auth();
        $cupomModel = new Cupom(Database::getConnection());
        $cupons = $cupomModel->listarTodos();
        include __DIR__ . '/../views/cupons/listar.php';
    }

    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // valida e salva
        } else {
            include __DIR__ . '/../views/cupons/form.php';
        }
    }
}
?>
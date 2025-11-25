<?php
require_once __DIR__ . '/../src/auth/auth.php';
require_auth();

require_once __DIR__ . '/../src/controllers/CupomController.php';

$controller = new CupomController();
$controller->index();

?>

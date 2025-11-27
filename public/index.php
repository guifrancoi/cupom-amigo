<?php
require_once __DIR__ . '/../src/auth/auth.php';
require_auth();

// route by user_type
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'associado') {
	require_once __DIR__ . '/../src/controllers/AssociadoController.php';
} else {
	require_once __DIR__ . '/../src/controllers/CupomController.php';
}

?>

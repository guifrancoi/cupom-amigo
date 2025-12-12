<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cupom.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php?filter=ativos');
    exit;
}

$numCupom = isset($_POST['num_cupom']) ? trim($_POST['num_cupom']) : '';
$cpfAssociado = isset($_POST['cpf_associado']) ? trim($_POST['cpf_associado']) : '';

$cpfAssociado = preg_replace('/[^0-9]/', '', $cpfAssociado);

if (empty($numCupom)) {
    $_SESSION['registrar_error'] = 'Cupom não informado.';
    header('Location: /index.php?filter=ativos');
    exit;
}

if (empty($cpfAssociado)) {
    $_SESSION['registrar_error'] = 'CPF do associado não informado.';
    header('Location: /index.php?filter=ativos');
    exit;
}

try {
    $db = Database::getConnection();
    

    $stmt = $db->prepare("SELECT cpf_associado FROM ASSOCIADO WHERE cpf_associado = :cpf");
    $stmt->execute([':cpf' => $cpfAssociado]);
    $associado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$associado) {
        $_SESSION['registrar_error'] = 'CPF não cadastrado no sistema. O associado precisa estar cadastrado para usar o cupom.';
        header('Location: /index.php?filter=ativos');
        exit;
    }
    
    $cupomModel = new Cupom($db);
    $cupomModel->confirmarUso($numCupom, $cpfAssociado);
    
    $_SESSION['registrar_message'] = 'Uso do cupom registrado com sucesso!';
    header('Location: /index.php?filter=ativos');
    exit;
} catch (Exception $e) {
    $_SESSION['registrar_error'] = 'Erro ao registrar uso: ' . $e->getMessage();
    header('Location: /index.php?filter=ativos');
    exit;
}

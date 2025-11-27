<?php
require_once __DIR__ . '/../models/Cupom.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/auth.php';

// Validar autenticação
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'associado') {
    header('Location: /cupom-amigo/src/views/auth/login.php');
    exit;
}

class AssociadoController {
    private $cupomModel;
    private $userCpf;

    public function __construct() {
        $this->cupomModel = new Cupom(Database::getConnection());
        $this->userCpf = $_SESSION['user_id']; // CPF do associado logado
    }

    public function index() {
        require_auth();
        // list active coupons for browsing/reservation
        $cupons = $this->cupomModel->listarAtivos();
        include __DIR__ . '/../views/associados/listarAssociado.php';
    }

    public function reservar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cupom-amigo/public/index.php');
            exit;
        }

        $numCupom = isset($_POST['num_cupom']) ? trim($_POST['num_cupom']) : '';
        if (empty($numCupom)) {
            $_SESSION['reservar_error'] = 'Cupom não informado.';
            header('Location: /cupom-amigo/public/index.php');
            exit;
        }

        try {
            // Inserir registro em CUPOM_ASSOCIADO com status R (reservado), sem dta_uso
            $pdo = Database::getConnection();
            $sql = 'INSERT INTO CUPOM_ASSOCIADO (cpf_associado, num_cupom, dta_reserva, status_cupom_associado) VALUES (:cpf, :num, :reserva, :status)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':cpf' => $this->userCpf,
                ':num' => $numCupom,
                ':reserva' => date('Y-m-d'),
                ':status' => 'R'
            ]);
            $_SESSION['reservar_message'] = 'Cupom reservado com sucesso!';
            header('Location: /cupom-amigo/public/index.php');
            exit;
        } catch (Exception $e) {
            $_SESSION['reservar_error'] = 'Erro ao reservar cupom: ' . $e->getMessage();
            header('Location: /cupom-amigo/public/index.php');
            exit;
        }
    }
}

// Roteador simples
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $controller = new AssociadoController();
    switch ($action) {
        case 'reservar':
            $controller->reservar();
            break;
        default:
            header('Location: /cupom-amigo/public/index.php');
            exit;
    }
} else {
    $controller = new AssociadoController();
    $controller->index();
}

?>

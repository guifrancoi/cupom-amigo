<?php
require_once __DIR__ . '/../models/Cupom.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/auth.php';

// Validar autenticação
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'comercio') {
    header('Location: /cupom-amigo/src/views/auth/login.php');
    exit;
}

class CupomController {
    private $cupomModel;
    private $userCnpj;

    public function __construct() {
        $this->cupomModel = new Cupom(Database::getConnection());
        $this->userCnpj = $_SESSION['user_id']; // CNPJ do comerciante logado
    }

    public function index() {
        require_auth();
        $filter = $_GET['filter'] ?? 'ativos';
        switch ($filter) {
            case 'utilizados':
                $cupons = $this->cupomModel->listarUtilizados($this->userCnpj);
                break;
            case 'vencidos':
                $cupons = $this->cupomModel->listarVencidos($this->userCnpj);
                break;
            case 'ativos':
            default:
                $cupons = $this->cupomModel->listarAtivos($this->userCnpj);
                break;
        }
        include __DIR__ . '/../views/cupons/listarComercio.php';
    }

    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cupom-amigo/src/views/cupons/form.php');
            exit;
        }

        $dsc = isset($_POST['dsc_cupom']) ? trim($_POST['dsc_cupom']) : '';
        $inicio = isset($_POST['dta_inicio']) ? trim($_POST['dta_inicio']) : '';
        $fim = isset($_POST['dta_fim']) ? trim($_POST['dta_fim']) : '';
        $desconto = isset($_POST['vlr_desconto']) ? (float)$_POST['vlr_desconto'] : 0;
        $qtd = isset($_POST['qtd_cupom']) ? (int)$_POST['qtd_cupom'] : 0;

        // Validação básica
        if (empty($dsc) || empty($inicio) || empty($fim) || $desconto <= 0 || $qtd <= 0) {
            $_SESSION['form_error'] = 'Todos os campos são obrigatórios e o desconto/quantidade devem ser maiores que 0.';
            header('Location: /cupom-amigo/src/views/cupons/form.php');
            exit;
        }

        if (strtotime($fim) <= strtotime($inicio)) {
            $_SESSION['form_error'] = 'A data de término deve ser posterior à data de início.';
            header('Location: /cupom-amigo/src/views/cupons/form.php');
            exit;
        }

        try {
            $this->cupomModel->create([
                'dsc_cupom' => $dsc,
                'dta_inicio' => $inicio,
                'dta_fim' => $fim,
                'vlr_desconto' => $desconto,
                'qtd_cupom' => $qtd,
                'cnpj_comercio' => $this->userCnpj
            ]);
            $_SESSION['form_message'] = 'Cupom criado com sucesso!';
            header('Location: /cupom-amigo/src/views/cupons/listarComercio.php?filter=ativos');
            exit;
        } catch (Exception $e) {
            $_SESSION['form_error'] = 'Erro ao criar cupom: ' . $e->getMessage();
            header('Location: /cupom-amigo/src/views/cupons/form.php');
            exit;
        }
    }

    public function registrarUso() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cupom-amigo/src/views/cupons/registrar.php');
            exit;
        }

        $numCupom = isset($_POST['num_cupom']) ? trim($_POST['num_cupom']) : '';

        if (empty($numCupom)) {
            $_SESSION['registrar_error'] = 'Cupom não informado.';
            header('Location: /cupom-amigo/src/views/cupons/registrar.php');
            exit;
        }

        try {
            // TODO: Implementar busca pelo CPF do associado via QR code ou entrada manual
            // Por enquanto, para testar, você pode passar o CPF como parâmetro POST
            $cpfAssociado = isset($_POST['cpf_associado']) ? trim($_POST['cpf_associado']) : '';
            if (empty($cpfAssociado)) {
                $_SESSION['registrar_error'] = 'CPF do associado não informado.';
                header('Location: /cupom-amigo/src/views/cupons/registrar.php');
                exit;
            }

            $this->cupomModel->confirmarUso($numCupom, $cpfAssociado);
            $_SESSION['registrar_message'] = 'Uso do cupom registrado com sucesso!';
            header('Location: /cupom-amigo/src/views/cupons/registrar.php');
            exit;
        } catch (Exception $e) {
            $_SESSION['registrar_error'] = 'Erro ao registrar uso: ' . $e->getMessage();
            header('Location: /cupom-amigo/src/views/cupons/registrar.php');
            exit;
        }
    }
}

// Roteador simples baseado em POST action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $controller = new CupomController();

    switch ($action) {
        case 'criar':
            $controller->criar();
            break;
        case 'registrarUso':
            $controller->registrarUso();
            break;
        default:
            header('Location: /cupom-amigo/src/views/cupons/listar.php');
            exit;
    }
} else {
    $controller = new CupomController();
    $controller->index();
}

?>

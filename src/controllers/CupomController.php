<?php
require_once __DIR__ . '/../models/Cupom.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/auth.php';

requireComercio();

class CupomController {
    private $cupomModel;
    private $userCnpj;

    public function __construct() {
        $this->cupomModel = new Cupom(Database::getConnection());
        $this->userCnpj = $_SESSION['user_id'];
    }

    public function index() {
        require_auth();
        $filter = $_GET['filter'] ?? 'ativos';
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        
        switch ($filter) {
            case 'utilizados':
                $cupons = $this->cupomModel->listarUtilizados($this->userCnpj, $search, $perPage, $offset);
                $totalCupons = $this->cupomModel->contarUtilizados($this->userCnpj, $search);
                break;
            case 'vencidos':
                $cupons = $this->cupomModel->listarVencidos($this->userCnpj, $search, $perPage, $offset);
                $totalCupons = $this->cupomModel->contarVencidos($this->userCnpj, $search);
                break;
            case 'futuros':
                $cupons = $this->cupomModel->listarFuturos($this->userCnpj, $search, $perPage, $offset);
                $totalCupons = $this->cupomModel->contarFuturos($this->userCnpj, $search);
                break;
            case 'ativos':
            default:
                $cupons = $this->cupomModel->listarAtivos($this->userCnpj, $search, $perPage, $offset);
                $totalCupons = $this->cupomModel->contarAtivos($this->userCnpj, $search);
                break;
        }
        
        $totalPages = ceil($totalCupons / $perPage);
        
        $pagination = [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'per_page' => $perPage,
            'total_items' => $totalCupons,
            'has_prev' => $page > 1,
            'has_next' => $page < $totalPages
        ];
        
        include __DIR__ . '/../views/cupons/listarComercio.php';
    }

    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /src/views/cupons/form.php');
            exit;
        }

        $dsc = isset($_POST['dsc_cupom']) ? trim($_POST['dsc_cupom']) : '';
        $inicio = isset($_POST['dta_inicio']) ? trim($_POST['dta_inicio']) : '';
        $fim = isset($_POST['dta_fim']) ? trim($_POST['dta_fim']) : '';
        $desconto = isset($_POST['vlr_desconto']) ? (float)$_POST['vlr_desconto'] : 0;
        $qtd = isset($_POST['qtd_cupom']) ? (int)$_POST['qtd_cupom'] : 0;

        if (empty($dsc) || empty($inicio) || empty($fim) || $desconto <= 0 || $qtd <= 0) {
            $_SESSION['form_error'] = 'Todos os campos são obrigatórios e o desconto/quantidade devem ser maiores que 0.';
            header('Location: /src/views/cupons/form.php');
            exit;
        }

        if (strtotime($fim) < strtotime($inicio)) {
            $_SESSION['form_error'] = 'A data de término não pode ser anterior à data de início.';
            header('Location: /src/views/cupons/form.php');
            exit;
        }

        try {
            $cuponsGerados = $this->cupomModel->create([
                'dsc_cupom' => $dsc,
                'dta_inicio' => $inicio,
                'dta_fim' => $fim,
                'vlr_desconto' => $desconto,
                'qtd_cupom' => $qtd,
                'cnpj_comercio' => $this->userCnpj
            ]);
            
            if ($cuponsGerados === 1) {
                $_SESSION['form_message'] = 'Cupom criado com sucesso!';
            } else {
                $_SESSION['form_message'] = $cuponsGerados . ' cupons criados com sucesso!';
            }
            header('Location: /index.php?filter=ativos');
            exit;
        } catch (Exception $e) {
            $_SESSION['form_error'] = 'Erro ao criar cupom: ' . $e->getMessage();
            header('Location: /src/views/cupons/form.php');
            exit;
        }
    }


}

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
            header('Location: /src/views/cupons/listar.php');
            exit;
    }
} else {
    $controller = new CupomController();
    $controller->index();
}

?>

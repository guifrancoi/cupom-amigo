<?php
require_once __DIR__ . '/../models/Cupom.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/auth.php';

requireAssociado();

class AssociadoController {
    private $cupomModel;
    private $userCpf;

    public function __construct() {
        $this->cupomModel = new Cupom(Database::getConnection());
        $this->userCpf = $_SESSION['user_id'];
    }

    public function index() {
        $categorias = $this->cupomModel->listarCategorias();
        
        $categoriaFiltro = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;
        
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        
        $cupons = $this->cupomModel->listarAtivosPorCategoria($categoriaFiltro, $perPage, $offset);
        $totalCupons = $this->cupomModel->contarAtivosPorCategoria($categoriaFiltro);
        
        $totalPages = ceil($totalCupons / $perPage);
        $totalPages = ceil($totalCupons / $perPage);
        $pagination = [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalCupons,
            'perPage' => $perPage,
            'hasNext' => $page < $totalPages,
            'hasPrev' => $page > 1
        ];
        
        include __DIR__ . '/../views/associados/listarAssociado.php';
    }

    public function meusCupons() {
        $filter = $_GET['filter'] ?? 'ativos';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        
        try {
            switch ($filter) {
                case 'utilizados':
                    $cupons = $this->cupomModel->listarCuponsAssociadoUtilizados($this->userCpf, $perPage, $offset);
                    $totalCupons = $this->cupomModel->contarCuponsAssociadoUtilizados($this->userCpf);
                    break;
                    
                case 'vencidos':
                    $cupons = $this->cupomModel->listarCuponsAssociadoVencidos($this->userCpf, $perPage, $offset);
                    $totalCupons = $this->cupomModel->contarCuponsAssociadoVencidos($this->userCpf);
                    break;
                    
                case 'ativos':
                default:
                    $cupons = $this->cupomModel->listarCuponsAssociadoAtivos($this->userCpf, $perPage, $offset);
                    $totalCupons = $this->cupomModel->contarCuponsAssociadoAtivos($this->userCpf);
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
            
            $error = null;
        } catch (Exception $e) {
            $cupons = [];
            $error = 'Erro ao carregar cupons: ' . $e->getMessage();
            $pagination = null;
        }
        
        $title = 'Meus Cupons';
        $useSidebar = true;
        $currentPage = 'meus-cupons';
        
        include __DIR__ . '/../views/associados/meusCupons.php';
    }

    public function reservar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php');
            exit;
        }

        $numCupom = isset($_POST['num_cupom']) ? trim($_POST['num_cupom']) : '';
        if (empty($numCupom)) {
            $_SESSION['reservar_error'] = 'Cupom não informado.';
            header('Location: /index.php');
            exit;
        }

        try {
            $pdo = Database::getConnection();
            $hoje = date('Y-m-d');
            
            $sqlCheck = 'SELECT c.num_cupom, c.dta_fim 
                         FROM CUPOM c 
                         WHERE c.num_cupom = :num 
                         AND c.dta_inicio <= :hoje1 
                         AND c.dta_fim >= :hoje2';
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([
                ':num' => $numCupom, 
                ':hoje1' => $hoje,
                ':hoje2' => $hoje
            ]);
            $cupom = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if (!$cupom) {
                $_SESSION['reservar_error'] = 'Cupom inválido ou expirado.';
                header('Location: /index.php');
                exit;
            }
            
            $sqlReservado = 'SELECT COUNT(*) FROM CUPOM_ASSOCIADO WHERE num_cupom = :num';
            $stmtReservado = $pdo->prepare($sqlReservado);
            $stmtReservado->execute([':num' => $numCupom]);
            $jaReservado = $stmtReservado->fetchColumn() > 0;
            
            if ($jaReservado) {
                $_SESSION['reservar_error'] = 'Este cupom já foi reservado por outro usuário.';
                header('Location: /index.php');
                exit;
            }
            
            $sql = 'INSERT INTO CUPOM_ASSOCIADO (cpf_associado, num_cupom, dta_reserva, status_cupom_associado) VALUES (:cpf, :num, :reserva, :status)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':cpf' => $this->userCpf,
                ':num' => $numCupom,
                ':reserva' => date('Y-m-d'),
                ':status' => 'R'
            ]);
            $_SESSION['reservar_message'] = 'Cupom reservado com sucesso!';
            header('Location: /index.php');
            exit;
        } catch (Exception $e) {
            $_SESSION['reservar_error'] = 'Erro ao reservar cupom: ' . $e->getMessage();
            header('Location: /index.php');
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $controller = new AssociadoController();
    switch ($action) {
        case 'reservar':
            $controller->reservar();
            break;
        default:
            header('Location: /index.php');
            exit;
    }
} else {
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $controller = new AssociadoController();
    switch ($action) {
        case 'meusCupons':
            $controller->meusCupons();
            break;
        default:
            $controller->index();
            break;
    }
}

?>

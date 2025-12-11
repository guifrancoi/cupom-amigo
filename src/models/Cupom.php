<?php
class Cupom {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    private function getCurrentDate(): string {
        return date('Y-m-d');
    }

    public function listarPorComerciante($idComerciante) {
        $stmt = $this->conn->prepare("SELECT * FROM CUPOM WHERE cnpj_comercio = ?");
        $stmt->execute([$idComerciante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTodos() {
        $stmt = $this->conn->prepare("SELECT * FROM CUPOM");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarAtivos(string $cnpj = null, string $search = '', int $limit = null, int $offset = 0): array {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM CUPOM_ASSOCIADO ca 
                 WHERE ca.num_cupom = c.num_cupom AND ca.status_cupom_associado = 'U') as cupons_usados
                FROM CUPOM c
                WHERE c.dta_inicio <= :hoje1 AND c.dta_fim >= :hoje2";
        $params = ['hoje1' => $hoje, 'hoje2' => $hoje];
        
        if ($cnpj) {
            $sql .= " AND c.cnpj_comercio = :cnpj";
            $params['cnpj'] = $cnpj;
        }
        
        if (!empty($search)) {
            $sql .= " AND c.num_cupom LIKE :search";
            $params['search'] = '%' . $search . '%';
        }
        
        $sql .= " HAVING cupons_usados < c.qtd_cupom";
        $sql .= " ORDER BY c.dta_inicio DESC, c.dsc_cupom ASC";
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($sql);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function listarFuturos(string $cnpj = null, string $search = '', int $limit = null, int $offset = 0): array {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT * FROM CUPOM 
                WHERE dta_inicio > :hoje";
        $params = ['hoje' => $hoje];
        
        if ($cnpj) {
            $sql .= " AND cnpj_comercio = :cnpj";
            $params['cnpj'] = $cnpj;
        }
        
        if (!empty($search)) {
            $sql .= " AND num_cupom LIKE :search";
            $params['search'] = '%' . $search . '%';
        }
        
        $sql .= " ORDER BY dta_inicio ASC, dsc_cupom ASC";
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($sql);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function contarFuturos(string $cnpj = null, string $search = ''): int {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT COUNT(*) as total FROM CUPOM 
                WHERE dta_inicio > :hoje";
        $params = ['hoje' => $hoje];
        
        if ($cnpj) {
            $sql .= " AND cnpj_comercio = :cnpj";
            $params['cnpj'] = $cnpj;
        }
        
        if (!empty($search)) {
            $sql .= " AND num_cupom LIKE :search";
            $params['search'] = '%' . $search . '%';
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarAtivos(string $cnpj = null, string $search = ''): int {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT COUNT(*) FROM CUPOM 
                WHERE dta_inicio <= :hoje1 AND dta_fim >= :hoje2";
        $params = ['hoje1' => $hoje, 'hoje2' => $hoje];
        
        if ($cnpj) {
            $sql .= " AND cnpj_comercio = :cnpj";
            $params['cnpj'] = $cnpj;
        }
        
        if (!empty($search)) {
            $sql .= " AND num_cupom LIKE :search";
            $params['search'] = '%' . $search . '%';
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function listarVencidos(string $cnpj = null, string $search = '', int $limit = null, int $offset = 0): array {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT c.*,
                       ca.dta_uso_associado,
                       ca.status_cupom_associado,
                       a.nome_associado,
                       a.cpf_associado
                FROM CUPOM c
                LEFT JOIN CUPOM_ASSOCIADO ca ON c.num_cupom = ca.num_cupom
                LEFT JOIN ASSOCIADO a ON ca.cpf_associado = a.cpf_associado
                WHERE c.dta_fim < :hoje";
        $params = ['hoje' => $hoje];
        
        if ($cnpj) {
            $sql .= " AND c.cnpj_comercio = :cnpj";
            $params['cnpj'] = $cnpj;
        }
        
        if (!empty($search)) {
            $sql .= " AND c.num_cupom LIKE :search";
            $params['search'] = '%' . $search . '%';
        }
        
        $sql .= " ORDER BY c.dta_inicio DESC, c.dsc_cupom ASC";
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($sql);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function contarVencidos(string $cnpj = null, string $search = ''): int {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT COUNT(*) FROM CUPOM c
                WHERE c.dta_fim < :hoje
                AND c.num_cupom NOT IN (
                    SELECT num_cupom FROM CUPOM_ASSOCIADO WHERE dta_uso_associado IS NOT NULL
                )";
        $params = ['hoje' => $hoje];
        
        if ($cnpj) {
            $sql .= " AND c.cnpj_comercio = :cnpj";
            $params['cnpj'] = $cnpj;
        }
        
        if (!empty($search)) {
            $sql .= " AND c.num_cupom LIKE :search";
            $params['search'] = '%' . $search . '%';
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function listarUtilizados(string $cnpj = null, string $search = '', int $limit = null, int $offset = 0): array {
        $sql = "SELECT DISTINCT c.*, ca.dta_uso_associado, a.nome_associado, a.cpf_associado 
                FROM CUPOM c
                INNER JOIN CUPOM_ASSOCIADO ca ON c.num_cupom = ca.num_cupom
                LEFT JOIN ASSOCIADO a ON ca.cpf_associado = a.cpf_associado
                WHERE ca.dta_uso_associado IS NOT NULL";
        $params = [];
        
        if ($cnpj) {
            $sql .= " AND c.cnpj_comercio = :cnpj";
            $params['cnpj'] = $cnpj;
        }
        
        if (!empty($search)) {
            $sql .= " AND c.num_cupom LIKE :search";
            $params['search'] = '%' . $search . '%';
        }
        
        $sql .= " ORDER BY ca.dta_uso_associado DESC, c.dsc_cupom ASC";
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($sql);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function contarUtilizados(string $cnpj = null, string $search = ''): int {
        $sql = "SELECT COUNT(DISTINCT c.num_cupom) FROM CUPOM c
                INNER JOIN CUPOM_ASSOCIADO ca ON c.num_cupom = ca.num_cupom
                WHERE ca.dta_uso_associado IS NOT NULL";
        $params = [];
        
        if ($cnpj) {
            $sql .= " AND c.cnpj_comercio = :cnpj";
            $params['cnpj'] = $cnpj;
        }
        
        if (!empty($search)) {
            $sql .= " AND c.num_cupom LIKE :search";
            $params['search'] = '%' . $search . '%';
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    private function gerarNumCupom(): string {
        return strtoupper(substr(hash('sha256', uniqid(mt_rand(), true) . microtime(true)), 0, 12));
    }

    public function create(array $data): int {
        if (empty($data['dsc_cupom']) || empty($data['dta_inicio']) || empty($data['dta_fim']) || 
            !isset($data['vlr_desconto']) || !isset($data['qtd_cupom']) || empty($data['cnpj_comercio'])) {
            throw new InvalidArgumentException('Dados obrigatórios faltando: dsc_cupom, dta_inicio, dta_fim, vlr_desconto, qtd_cupom, cnpj_comercio');
        }

        $quantidade = max(1, intval($data['qtd_cupom']));
        $cuponsGerados = 0;
        
        $sql = "INSERT INTO CUPOM (num_cupom, dsc_cupom, dta_inicio, dta_fim, vlr_desconto, qtd_cupom, cnpj_comercio) 
                VALUES (:num, :desc, :inicio, :fim, :desc_val, :qtd, :cnpj)";
        $stmt = $this->conn->prepare($sql);
        
        for ($i = 0; $i < $quantidade; $i++) {
            $num_cupom = $this->gerarNumCupom();
            
            $tentativas = 0;
            while ($this->cupomExiste($num_cupom) && $tentativas < 10) {
                $num_cupom = $this->gerarNumCupom();
                $tentativas++;
            }
            
            $result = $stmt->execute([
                ':num' => $num_cupom,
                ':desc' => $data['dsc_cupom'],
                ':inicio' => $data['dta_inicio'],
                ':fim' => $data['dta_fim'],
                ':desc_val' => $data['vlr_desconto'],
                ':qtd' => 1,
                ':cnpj' => $data['cnpj_comercio']
            ]);
            
            if ($result) {
                $cuponsGerados++;
            }
            
            usleep(1000);
        }
        
        return $cuponsGerados;
    }

    private function cupomExiste(string $numCupom): bool {
        $sql = "SELECT COUNT(*) FROM CUPOM WHERE num_cupom = :num";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':num' => $numCupom]);
        return $stmt->fetchColumn() > 0;
    }

    public function confirmarUso(string $numCupom, string $cpfAssociado): bool {
        $hoje = date('Y-m-d');
        

        $sql = "SELECT num_cupom, qtd_cupom, dta_inicio, dta_fim 
                FROM CUPOM 
                WHERE num_cupom = :cupom 
                AND dta_inicio <= :dataInicio 
                AND dta_fim >= :dataFim";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':cupom' => $numCupom, ':dataInicio' => $hoje, ':dataFim' => $hoje]);
        $cupom = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cupom) {
            throw new Exception('Cupom não encontrado ou fora do período de validade.');
        }


        $sql = "SELECT id_cupom_associado, status_cupom_associado FROM CUPOM_ASSOCIADO 
                WHERE num_cupom = :cupom AND cpf_associado = :cpf 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':cupom' => $numCupom, ':cpf' => $cpfAssociado]);
        $cupomAssociado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cupomAssociado) {
            throw new Exception('Este cupom não foi reservado por este associado. O associado deve reservar o cupom antes de utilizá-lo.');
        }

        if ($cupomAssociado['status_cupom_associado'] === 'U') {
            throw new Exception('Este cupom já foi utilizado por este associado.');
        }

        if ($cupomAssociado['status_cupom_associado'] !== 'R') {
            throw new Exception('Este cupom não está em estado válido para uso.');
        }


        $sql = "UPDATE CUPOM_ASSOCIADO 
                SET dta_uso_associado = :hoje, status_cupom_associado = 'U'
                WHERE id_cupom_associado = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':hoje' => $hoje,
            ':id' => $cupomAssociado['id_cupom_associado']
        ]);
    }

    public function listarCategorias(): array {
        $sql = "SELECT id_categoria, nome_categoria FROM CATEGORIA ORDER BY nome_categoria ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarCuponsAssociadoAtivos(string $cpf, int $limit = null, int $offset = 0): array {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT c.*, ca.dta_reserva, ca.dta_uso_associado, ca.status_cupom_associado,
                       com.nome_fantasia_comercio
                FROM CUPOM_ASSOCIADO ca
                INNER JOIN CUPOM c ON ca.num_cupom = c.num_cupom
                LEFT JOIN COMERCIO com ON c.cnpj_comercio = com.cnpj_comercio
                WHERE ca.cpf_associado = :cpf 
                AND ca.status_cupom_associado = 'R'
                AND c.dta_fim >= :hoje
                ORDER BY c.dta_inicio DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':hoje', $hoje);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarCuponsAssociadoAtivos(string $cpf): int {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT COUNT(*) FROM CUPOM_ASSOCIADO ca
                INNER JOIN CUPOM c ON ca.num_cupom = c.num_cupom
                WHERE ca.cpf_associado = :cpf 
                AND ca.status_cupom_associado = 'R'
                AND c.dta_fim >= :hoje";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':cpf' => $cpf, ':hoje' => $hoje]);
        return (int) $stmt->fetchColumn();
    }

    public function listarCuponsAssociadoUtilizados(string $cpf, int $limit = null, int $offset = 0): array {
        $sql = "SELECT c.*, ca.dta_reserva, ca.dta_uso_associado, ca.status_cupom_associado,
                       com.nome_fantasia_comercio
                FROM CUPOM_ASSOCIADO ca
                INNER JOIN CUPOM c ON ca.num_cupom = c.num_cupom
                LEFT JOIN COMERCIO com ON c.cnpj_comercio = com.cnpj_comercio
                WHERE ca.cpf_associado = :cpf 
                AND ca.status_cupom_associado = 'U'
                ORDER BY ca.dta_uso_associado DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':cpf', $cpf);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarCuponsAssociadoUtilizados(string $cpf): int {
        $sql = "SELECT COUNT(*) FROM CUPOM_ASSOCIADO ca
                WHERE ca.cpf_associado = :cpf 
                AND ca.status_cupom_associado = 'U'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':cpf' => $cpf]);
        return (int) $stmt->fetchColumn();
    }

    public function listarCuponsAssociadoVencidos(string $cpf, int $limit = null, int $offset = 0): array {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT c.*, ca.dta_reserva, ca.dta_uso_associado, ca.status_cupom_associado,
                       com.nome_fantasia_comercio
                FROM CUPOM_ASSOCIADO ca
                INNER JOIN CUPOM c ON ca.num_cupom = c.num_cupom
                LEFT JOIN COMERCIO com ON c.cnpj_comercio = com.cnpj_comercio
                WHERE ca.cpf_associado = :cpf 
                AND ca.status_cupom_associado = 'R'
                AND c.dta_fim < :hoje
                ORDER BY c.dta_inicio DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':hoje', $hoje);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarCuponsAssociadoVencidos(string $cpf): int {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT COUNT(*) FROM CUPOM_ASSOCIADO ca
                INNER JOIN CUPOM c ON ca.num_cupom = c.num_cupom
                WHERE ca.cpf_associado = :cpf 
                AND ca.status_cupom_associado = 'R'
                AND c.dta_fim < :hoje";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':cpf' => $cpf, ':hoje' => $hoje]);
        return (int) $stmt->fetchColumn();
    }

    public function listarAtivosPorCategoria(?int $idCategoria = null, int $limit = 12, int $offset = 0): array {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT c.*, co.nome_fantasia_comercio, co.id_categoria, cat.nome_categoria
                FROM CUPOM c
                INNER JOIN COMERCIO co ON c.cnpj_comercio = co.cnpj_comercio
                LEFT JOIN CATEGORIA cat ON co.id_categoria = cat.id_categoria
                WHERE c.dta_inicio <= :hoje1 AND c.dta_fim >= :hoje2
                AND c.num_cupom NOT IN (
                    SELECT num_cupom FROM CUPOM_ASSOCIADO
                )";
        
        $params = ['hoje1' => $hoje, 'hoje2' => $hoje];
        
        if ($idCategoria !== null && $idCategoria > 0) {
            $sql .= " AND co.id_categoria = :categoria";
            $params['categoria'] = $idCategoria;
        }
        
        $sql .= " ORDER BY c.dta_inicio DESC, c.dsc_cupom ASC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function contarAtivosPorCategoria(?int $idCategoria = null): int {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT COUNT(*) as total
                FROM CUPOM c
                INNER JOIN COMERCIO co ON c.cnpj_comercio = co.cnpj_comercio
                WHERE c.dta_inicio <= :hoje1 AND c.dta_fim >= :hoje2
                AND c.num_cupom NOT IN (
                    SELECT num_cupom FROM CUPOM_ASSOCIADO
                )";
        
        $params = ['hoje1' => $hoje, 'hoje2' => $hoje];
        
        if ($idCategoria !== null && $idCategoria > 0) {
            $sql .= " AND co.id_categoria = :categoria";
            $params['categoria'] = $idCategoria;
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
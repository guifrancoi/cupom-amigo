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
        // The foreign key to Comercio is cnpj_comercio
        $stmt = $this->conn->prepare("SELECT * FROM CUPOM WHERE cnpj_comercio = ?");
        $stmt->execute([$idComerciante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTodos() {
        $stmt = $this->conn->prepare("SELECT * FROM CUPOM");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cupons ativos: dta_inicio <= hoje E dta_fim >= hoje
     */
    public function listarAtivos(string $cnpj = null): array {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT * FROM CUPOM 
                WHERE dta_inicio <= :hoje1 AND dta_fim >= :hoje2";
        if ($cnpj) {
            $sql .= " AND cnpj_comercio = :cnpj";
        }
        $sql .= " ORDER BY dta_inicio DESC, dsc_cupom ASC";
        $stmt = $this->conn->prepare($sql);
        $params = ['hoje1' => $hoje, 'hoje2' => $hoje];
        if ($cnpj) {
            $params['cnpj'] = $cnpj;
        }
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cupons vencidos e não utilizados: dta_fim < hoje E sem registro em CUPOM_ASSOCIADO
     */
    public function listarVencidos(string $cnpj = null): array {
        $hoje = $this->getCurrentDate();
        $sql = "SELECT c.* FROM CUPOM c
                WHERE c.dta_fim < :hoje
                AND c.num_cupom NOT IN (
                    SELECT num_cupom FROM CUPOM_ASSOCIADO WHERE dta_uso_associado IS NOT NULL
                )
                ORDER BY c.dta_inicio DESC, c.dsc_cupom ASC";
        if ($cnpj) {
            $sql = str_replace('ORDER BY', 'AND c.cnpj_comercio = :cnpj ORDER BY', $sql);
        }
        $stmt = $this->conn->prepare($sql);
        $params = [ 'hoje' => $hoje ];
        if ($cnpj) { $params['cnpj'] = $cnpj; }
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cupons utilizados: têm registro em CUPOM_ASSOCIADO com dta_uso_associado preenchida
     */
    public function listarUtilizados(string $cnpj = null): array {
        $sql = "SELECT DISTINCT c.* FROM CUPOM c
                INNER JOIN CUPOM_ASSOCIADO ca ON c.num_cupom = ca.num_cupom
                WHERE ca.dta_uso_associado IS NOT NULL
                ORDER BY c.dta_inicio DESC, c.dsc_cupom ASC";
        if ($cnpj) {
            $sql = str_replace('ORDER BY', 'AND c.cnpj_comercio = :cnpj ORDER BY', $sql);
        }
        $stmt = $this->conn->prepare($sql);
        if ($cnpj) {
            $stmt->execute(['cnpj' => $cnpj]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gera um número de cupom único (CHAR(12))
     */
    private function gerarNumCupom(): string {
        return strtoupper(substr(hash('sha256', uniqid() . time()), 0, 12));
    }

    /**
     * Cria novo cupom
     */
    public function create(array $data): bool {
        if (empty($data['dsc_cupom']) || empty($data['dta_inicio']) || empty($data['dta_fim']) || 
            !isset($data['vlr_desconto']) || !isset($data['qtd_cupom']) || empty($data['cnpj_comercio'])) {
            throw new InvalidArgumentException('Dados obrigatórios faltando: dsc_cupom, dta_inicio, dta_fim, vlr_desconto, qtd_cupom, cnpj_comercio');
        }

        // Gera número único
        $num_cupom = $this->gerarNumCupom();

        $sql = "INSERT INTO CUPOM (num_cupom, dsc_cupom, dta_inicio, dta_fim, vlr_desconto, qtd_cupom, cnpj_comercio) 
                VALUES (:num, :desc, :inicio, :fim, :desc_val, :qtd, :cnpj)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':num' => $num_cupom,
            ':desc' => $data['dsc_cupom'],
            ':inicio' => $data['dta_inicio'],
            ':fim' => $data['dta_fim'],
            ':desc_val' => $data['vlr_desconto'],
            ':qtd' => $data['qtd_cupom'],
            ':cnpj' => $data['cnpj_comercio']
        ]);
    }

    /**
     * Registra o uso de um cupom por um associado
     * Atualiza a data de uso na tabela CUPOM_ASSOCIADO
     */
    public function confirmarUso(string $numCupom, string $cpfAssociado): bool {
        // Busca um registro de cupom para o associado (status R = reservado)
        $sql = "SELECT id_cupom_associado FROM CUPOM_ASSOCIADO 
                WHERE num_cupom = :cupom AND cpf_associado = :cpf 
                AND status_cupom_associado = 'R' 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':cupom' => $numCupom, ':cpf' => $cpfAssociado]);
        $cupomAssoc = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cupomAssoc) {
            throw new Exception('Cupom não encontrado ou já foi utilizado para este associado.');
        }

        // Atualiza com a data de uso e altera status para U (usado)
        $sql = "UPDATE CUPOM_ASSOCIADO 
                SET dta_uso_associado = :hoje, status_cupom_associado = 'U'
                WHERE id_cupom_associado = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':hoje' => date('Y-m-d'),
            ':id' => $cupomAssoc['id_cupom_associado']
        ]);
    }
}
?>
<?php
require_once __DIR__ . '/../config/database.php';

class Associado {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function exists(string $cpf): bool {
        $sql = 'SELECT 1 FROM ASSOCIADO WHERE cpf_associado = :cpf LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cpf' => $cpf]);
        return (bool) $stmt->fetch();
    }

    public function create(array $data): bool {
        if (empty($data['cpf_associado']) || empty($data['senha_associado'])) {
            throw new InvalidArgumentException('CPF e senha são obrigatórios para Associado.');
        }

        $columns = [];
        $placeholders = [];
        $params = [];
        foreach ($data as $col => $value) {
            $columns[] = $col;
            $placeholders[] = ':' . $col;
            $params[':' . $col] = $value;
        }

        $sql = 'INSERT INTO ASSOCIADO (' . implode(',', $columns) . ') VALUES (' . implode(',', $placeholders) . ')';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}

?>

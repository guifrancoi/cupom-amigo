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

    public function findByCpf(string $cpf): ?array {
        $sql = 'SELECT cpf_associado AS id, senha_associado AS senha, nome_associado AS nome, email_associado AS email 
                FROM ASSOCIADO WHERE cpf_associado = :cpf LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cpf' => $cpf]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findByEmail(string $email): ?array {
        $sql = 'SELECT cpf_associado AS id, email_associado AS email, nome_associado AS nome 
                FROM ASSOCIADO WHERE LOWER(email_associado) = :email LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => strtolower($email)]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function updatePassword(string $cpf, string $senhaHash): bool {
        $sql = 'UPDATE ASSOCIADO SET senha_associado = :senha WHERE cpf_associado = :cpf';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':senha' => $senhaHash, ':cpf' => $cpf]);
    }
}

?>

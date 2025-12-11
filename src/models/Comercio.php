<?php
require_once __DIR__ . '/../config/database.php';

class Comercio {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function exists(string $cnpj): bool {
        $sql = 'SELECT 1 FROM COMERCIO WHERE cnpj_comercio = :cnpj LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cnpj' => $cnpj]);
        return (bool) $stmt->fetch();
    }

    public function create(array $data): bool {
        if (empty($data['cnpj_comercio']) || empty($data['senha_comercio']) || empty($data['raz_social_comercio'])) {
            throw new InvalidArgumentException('CNPJ, razão social e senha são obrigatórios para Comércio.');
        }

        $columns = [];
        $placeholders = [];
        $params = [];
        foreach ($data as $col => $value) {
            $columns[] = $col;
            $placeholders[] = ':' . $col;
            $params[':' . $col] = $value;
        }

        $sql = 'INSERT INTO COMERCIO (' . implode(',', $columns) . ') VALUES (' . implode(',', $placeholders) . ')';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function findByCnpj(string $cnpj): ?array {
        $sql = 'SELECT cnpj_comercio AS id, senha_comercio AS senha, nome_fantasia_comercio AS nome, 
                raz_social_comercio, email_comercio AS email, id_categoria, endereco_comercio 
                FROM COMERCIO WHERE cnpj_comercio = :cnpj LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cnpj' => $cnpj]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findByEmail(string $email): ?array {
        $sql = 'SELECT cnpj_comercio AS id, email_comercio AS email, nome_fantasia_comercio AS nome 
                FROM COMERCIO WHERE LOWER(email_comercio) = :email LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => strtolower($email)]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function updatePassword(string $cnpj, string $senhaHash): bool {
        $sql = 'UPDATE COMERCIO SET senha_comercio = :senha WHERE cnpj_comercio = :cnpj';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':senha' => $senhaHash, ':cnpj' => $cnpj]);
    }
}

?>

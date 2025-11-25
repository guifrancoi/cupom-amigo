<?php
class Cupom {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
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
}
?>
<?php
class Cupom {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listarPorComerciante($idComerciante) {
        $stmt = $this->conn->prepare("SELECT * FROM cupons WHERE id_comerciante = ?");
        $stmt->execute([$idComerciante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTodos() {
        $query = "SELECT * FROM cupons";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
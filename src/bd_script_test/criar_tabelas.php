<?php
/**
 * Script: criar_tabelas.php
 * Objetivo: Criar as tabelas do sistema de cupons se não existirem.
 */

require_once __DIR__ . '/../config/database.php';

try {

    $conn = Database::getConnection();


    $sql = "
    CREATE TABLE IF NOT EXISTS CATEGORIA (
        id_categoria INT AUTO_INCREMENT PRIMARY KEY,
        nome_categoria VARCHAR(25) NOT NULL
    ) ENGINE=InnoDB;
    ";
    $conn->exec($sql);


    $sql = "
    CREATE TABLE IF NOT EXISTS COMERCIO (
        cnpj_comercio VARCHAR(14) PRIMARY KEY,
        raz_social_comercio VARCHAR(30) NOT NULL,
        nome_fantasia_comercio VARCHAR(30),
        endereco_comercio VARCHAR(50),
        cidade_comercio VARCHAR(30),
        estado_comercio CHAR(2),
        cep_comercio VARCHAR(10),
        email_comercio VARCHAR(50),
        senha_comercio VARCHAR(255),
        id_categoria INT,
        FOREIGN KEY (id_categoria) REFERENCES CATEGORIA(id_categoria)
            ON UPDATE CASCADE ON DELETE SET NULL
    ) ENGINE=InnoDB;
    ";
    $conn->exec($sql);


    $sql = "
    CREATE TABLE IF NOT EXISTS ASSOCIADO (
        cpf_associado VARCHAR(11) PRIMARY KEY,
        dtn_nascimento DATE,
        nome_associado VARCHAR(30),
        sexo_associado CHAR(1),
        endereco_associado VARCHAR(50),
        cidade_associado VARCHAR(30),
        estado_associado CHAR(2),
        cep_associado VARCHAR(10),
        email_associado VARCHAR(50),
        senha_associado VARCHAR(255)
    ) ENGINE=InnoDB;
    ";
    $conn->exec($sql);


    $sql = "
    CREATE TABLE IF NOT EXISTS CUPOM (
        num_cupom CHAR(12) PRIMARY KEY,
        dsc_cupom VARCHAR(25),
        dta_inicio DATE,
        dta_fim DATE,
        vlr_desconto DECIMAL(5,2),
        qtd_cupom INT,
        cnpj_comercio VARCHAR(14),
        FOREIGN KEY (cnpj_comercio) REFERENCES COMERCIO(cnpj_comercio)
            ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE=InnoDB;
    ";
    $conn->exec($sql);


    $sql = "
    CREATE TABLE IF NOT EXISTS CUPOM_ASSOCIADO (
        id_cupom_associado INT AUTO_INCREMENT PRIMARY KEY,
        cpf_associado VARCHAR(11),
        num_cupom CHAR(12),
        dta_reserva DATE,
        dta_uso_associado DATE,
        status_cupom_associado CHAR(1),
        FOREIGN KEY (cpf_associado) REFERENCES ASSOCIADO(cpf_associado)
            ON UPDATE CASCADE ON DELETE CASCADE,
        FOREIGN KEY (num_cupom) REFERENCES CUPOM(num_cupom)
            ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE=InnoDB;
    ";
    $conn->exec($sql);

    echo "<p>Tabelas verificadas/criadas com sucesso!</p>";

} catch (PDOException $e) {
    echo "<p>Erro: " . $e->getMessage() . "</p>";
}
?>

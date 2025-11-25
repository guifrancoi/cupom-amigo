<?php
session_start();

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../models/Associado.php';
require_once __DIR__ . '/../models/Comercio.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /cupom-amigo/src/views/auth/cadastrar.php');
    exit;
}

$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

// mantém somente dígitos
$usuario_clean = preg_replace('/\D/', '', $usuario);

if (empty($usuario_clean) || empty($senha)) {
    $_SESSION['register_error'] = 'Preencha usuário e senha.';
    header('Location: /cupom-amigo/src/views/auth/cadastrar.php');
    exit;
}

$len = strlen($usuario_clean);
try {
    if ($len === 11) {
        // Associado
        $assoc = new Associado($pdo);
        if ($assoc->exists($usuario_clean)) {
            $_SESSION['register_error'] = 'CPF já cadastrado.';
            header('Location: /cupom-amigo/src/views/auth/cadastrar.php');
            exit;
        }

        $data = [
            'cpf_associado' => $usuario_clean,
            'senha_associado' => password_hash($senha, PASSWORD_DEFAULT),
            'dtn_nascimento' => $_POST['dtn_nascimento'] ?? null,
            'nome_associado' => $_POST['nome_associado'] ?? null,
            'sexo_associado' => $_POST['sexo_associado'] ?? null,
            'endereco_associado' => $_POST['endereco_associado'] ?? null,
            'cidade_associado' => $_POST['cidade_associado'] ?? null,
            'estado_associado' => $_POST['estado_associado'] ?? null,
            'cep_associado' => $_POST['cep_associado'] ?? null,
            'email_associado' => $_POST['email_associado'] ?? null,
        ];

        $assoc->create($data);
        $_SESSION['register_message'] = 'Associado cadastrado com sucesso! Faça login.';
        header('Location: /cupom-amigo/src/views/auth/login.php');
        exit;

    } elseif ($len === 14) {
        // Comércio
        $com = new Comercio($pdo);
        if ($com->exists($usuario_clean)) {
            $_SESSION['register_error'] = 'CNPJ já cadastrado.';
            header('Location: /cupom-amigo/src/views/auth/cadastrar.php');
            exit;
        }

        // Razão social required by schema (NOT NULL)
        $razao = $_POST['raz_social_comercio'] ?? null;
        if (empty($razao)) {
            $_SESSION['register_error'] = 'Razão social é obrigatória para cadastro de comércio.';
            header('Location: /cupom-amigo/src/views/auth/cadastrar.php');
            exit;
        }

        $data = [
            'cnpj_comercio' => $usuario_clean,
            'senha_comercio' => password_hash($senha, PASSWORD_DEFAULT),
            'raz_social_comercio' => $razao,
            'nome_fantasia_comercio' => $_POST['nome_fantasia_comercio'] ?? null,
            'endereco_comercio' => $_POST['endereco_comercio'] ?? null,
            'cidade_comercio' => $_POST['cidade_comercio'] ?? null,
            'estado_comercio' => $_POST['estado_comercio'] ?? null,
            'cep_comercio' => $_POST['cep_comercio'] ?? null,
            'email_comercio' => $_POST['email_comercio'] ?? null,
            'id_categoria' => !empty($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : null,
        ];

        $com->create($data);
        $_SESSION['register_message'] = 'Comércio cadastrado com sucesso! Faça login.';
        header('Location: /cupom-amigo/src/views/auth/login.php');
        exit;

    } else {
        $_SESSION['register_error'] = 'CPF ou CNPJ inválido. Preencha apenas com números.';
        header('Location: /cupom-amigo/src/views/auth/cadastrar.php');
        exit;
    }
} catch (PDOException $e) {
    // TODO, pode-se logar a mensagem. Aqui retornamos genérico para o usuário.
    $_SESSION['register_error'] = 'Erro ao acessar o banco de dados: ' . $e->getMessage();
    header('Location: /cupom-amigo/src/views/auth/cadastrar.php');
    exit;
}

?>

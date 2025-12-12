<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../models/Associado.php';
require_once __DIR__ . '/../models/Comercio.php';
require_once __DIR__ . '/../helpers/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /src/views/auth/cadastrar.php');
    exit;
}

$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
$confirmarSenha = isset($_POST['confirmar_senha']) ? $_POST['confirmar_senha'] : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'associado';

$usuario_clean = preg_replace('/\D/', '', $usuario);

if (empty($usuario_clean) || empty($senha)) {
    $_SESSION['register_error'] = 'Preencha usuário e senha.';
    header('Location: /src/views/auth/cadastrar.php');
    exit;
}


if ($senha !== $confirmarSenha) {
    $_SESSION['register_error'] = 'As senhas não coincidem.';
    header('Location: /src/views/auth/cadastrar.php');
    exit;
}


if (strlen($senha) < 6) {
    $_SESSION['register_error'] = 'A senha deve ter pelo menos 6 caracteres.';
    header('Location: /src/views/auth/cadastrar.php');
    exit;
}

$len = strlen($usuario_clean);


if ($len === 11) {
    if (!validateCPF($usuario_clean)) {
        $_SESSION['register_error'] = 'CPF inválido. Verifique os dígitos informados.';
        header('Location: /src/views/auth/cadastrar.php');
        exit;
    }
} elseif ($len === 14) {
    if (!validateCNPJ($usuario_clean)) {
        $_SESSION['register_error'] = 'CNPJ inválido. Verifique os dígitos informados.';
        header('Location: /src/views/auth/cadastrar.php');
        exit;
    }
} else {
    $_SESSION['register_error'] = 'CPF deve ter 11 dígitos e CNPJ deve ter 14 dígitos.';
    header('Location: /src/views/auth/cadastrar.php');
    exit;
}

try {
    if ($len === 11 || $tipo === 'associado') {
        $assoc = new Associado($pdo);
        if ($assoc->exists($usuario_clean)) {
            $_SESSION['register_error'] = 'CPF já cadastrado.';
            header('Location: /src/views/auth/cadastrar.php');
            exit;
        }


        $email = $_POST['email_associado'] ?? '';
        if (!empty($email) && !validateEmail($email)) {
            $_SESSION['register_error'] = 'E-mail inválido.';
            header('Location: /src/views/auth/cadastrar.php');
            exit;
        }

        $data = [
            'cpf_associado' => $usuario_clean,
            'senha_associado' => password_hash($senha, PASSWORD_DEFAULT),
            'dtn_nascimento' => !empty($_POST['dtn_nascimento']) ? $_POST['dtn_nascimento'] : null,
            'nome_associado' => !empty($_POST['nome_associado']) ? trim($_POST['nome_associado']) : null,
            'sexo_associado' => !empty($_POST['sexo_associado']) ? $_POST['sexo_associado'] : null,
            'endereco_associado' => !empty($_POST['endereco_associado']) ? trim($_POST['endereco_associado']) : null,
            'cidade_associado' => !empty($_POST['cidade_associado']) ? trim($_POST['cidade_associado']) : null,
            'estado_associado' => !empty($_POST['estado_associado']) ? strtoupper(trim($_POST['estado_associado'])) : null,
            'cep_associado' => !empty($_POST['cep_associado']) ? onlyNumbers($_POST['cep_associado']) : null,
            'email_associado' => !empty($email) ? strtolower(trim($email)) : null,
        ];

        $assoc->create($data);
        
        $_SESSION['login_message'] = 'Associado cadastrado com sucesso! Faça login.';
        header('Location: /src/views/auth/login.php');
        exit;

    } elseif ($len === 14 || $tipo === 'comercio') {
        $com = new Comercio($pdo);
        if ($com->exists($usuario_clean)) {
            $_SESSION['register_error'] = 'CNPJ já cadastrado.';
            header('Location: /src/views/auth/cadastrar.php');
            exit;
        }


        $razao = $_POST['raz_social_comercio'] ?? null;
        if (empty($razao)) {
            $_SESSION['register_error'] = 'Razão social é obrigatória para cadastro de comércio.';
            header('Location: /src/views/auth/cadastrar.php');
            exit;
        }


        $email = $_POST['email_comercio'] ?? '';
        if (!empty($email) && !validateEmail($email)) {
            $_SESSION['register_error'] = 'E-mail inválido.';
            header('Location: /src/views/auth/cadastrar.php');
            exit;
        }


        $id_categoria = !empty($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : null;
        if ($id_categoria !== null) {
            $stmtCat = $pdo->prepare('SELECT 1 FROM CATEGORIA WHERE id_categoria = :id LIMIT 1');
            $stmtCat->execute([':id' => $id_categoria]);
            if (!$stmtCat->fetch()) {
                $id_categoria = null;
            }
        }

        $data = [
            'cnpj_comercio' => $usuario_clean,
            'senha_comercio' => password_hash($senha, PASSWORD_DEFAULT),
            'raz_social_comercio' => trim($razao),
            'nome_fantasia_comercio' => !empty($_POST['nome_fantasia_comercio']) ? trim($_POST['nome_fantasia_comercio']) : null,
            'endereco_comercio' => !empty($_POST['endereco_comercio']) ? trim($_POST['endereco_comercio']) : null,
            'cidade_comercio' => !empty($_POST['cidade_comercio']) ? trim($_POST['cidade_comercio']) : null,
            'estado_comercio' => !empty($_POST['estado_comercio']) ? strtoupper(trim($_POST['estado_comercio'])) : null,
            'cep_comercio' => !empty($_POST['cep_comercio']) ? onlyNumbers($_POST['cep_comercio']) : null,
            'email_comercio' => !empty($email) ? strtolower(trim($email)) : null,
            'id_categoria' => $id_categoria,
        ];

        $com->create($data);
        
        $_SESSION['login_message'] = 'Comércio cadastrado com sucesso! Faça login.';
        header('Location: /src/views/auth/login.php');
        exit;

    } else {
        $_SESSION['register_error'] = 'CPF ou CNPJ inválido. Preencha apenas com números.';
        header('Location: /src/views/auth/cadastrar.php');
        exit;
    }
} catch (Exception $e) {
    $_SESSION['register_error'] = 'Erro ao acessar o banco de dados: ' . $e->getMessage();
    header('Location: /src/views/auth/cadastrar.php');
    exit;
}

?>

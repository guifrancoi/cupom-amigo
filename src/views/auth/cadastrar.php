<?php
session_start();

$error = $_SESSION['register_error'] ?? null;
$message = $_SESSION['register_message'] ?? null;
unset($_SESSION['register_error'], $_SESSION['register_message']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;}</style>
</head>
<body>
    <h1>Cadastrar Usuário</h1>
    <?php if (!empty($message)): ?><p style="color:green"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <?php if (!empty($error)): ?><p style="color:red"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <p>Informe CPF (11 dígitos) para Associado ou CNPJ (14 dígitos) para Comércio. Preencha os campos do tipo correspondente.</p>
    <form method="post" action="/cupom-amigo/src/auth/process_cadastro.php">
        <label>CPF/CNPJ (apenas números):
            <input type="text" name="usuario" id="usuario" inputmode="numeric" required placeholder="CPF: 01234567890 ou CNPJ: 01234567000189">
        </label>
        <label>Senha: <input type="password" name="senha" required></label><br>
        <hr>
        <h3>Dados do Associado (preencher se for CPF)</h3>
        <label>Nome: <input type="text" name="nome_associado" id="nome_associado"></label><br>
        <label>Data de Nascimento: <input type="date" name="dtn_nascimento"></label><br>
        <label>Sexo: <select name="sexo_associado"><option value="">--</option><option value="M">M</option><option value="F">F</option></select></label><br>
        <label>Endereço: <input type="text" name="endereco_associado"></label><br>
        <label>Cidade: <input type="text" name="cidade_associado"></label><br>
        <label>Estado: <input type="text" name="estado_associado" maxlength="2" size="2"></label><br>
        <label>CEP: <input type="text" name="cep_associado"></label><br>
        <label>E-mail: <input type="email" name="email_associado"></label>
        <hr>
        <h3>Dados do Comércio (preencher se for CNPJ)</h3>
        <label>Razão Social: <input type="text" name="raz_social_comercio" id="razao_social"></label><br>
        <label>Nome Fantasia: <input type="text" name="nome_fantasia_comercio"></label><br>
        <label>Endereço: <input type="text" name="endereco_comercio"></label><br>
        <label>Cidade: <input type="text" name="cidade_comercio"></label><br>
        <label>Estado: <input type="text" name="estado_comercio" maxlength="2" size="2"></label><br>
        <label>CEP: <input type="text" name="cep_comercio"></label><br>
        <label>E-mail: <input type="email" name="email_comercio"></label>
        <label>Categoria (ID): <input type="number" name="id_categoria" min="0"></label><br>
        <button type="submit">Cadastrar</button>
    </form>
    <p><a href="login.php">Voltar ao login</a></p>
</body>
</html>

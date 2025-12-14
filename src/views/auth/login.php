<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!empty($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}


require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../ui/components/alert.php';


$error = $_SESSION['login_error'] ?? null;
$message = $_SESSION['login_message'] ?? null;
$pw_migration = $_SESSION['password_migration_needed'] ?? null;
unset($_SESSION['login_error'], $_SESSION['login_message'], $_SESSION['password_migration_needed']);


$hideLayout = true;

$title = 'Login';
?>
<?php require_once __DIR__ . '/../../ui/layout/head.php'; ?>

    
    <main class="main-content flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            
            
            <div class="text-center mb-8">
                <a href="/index.php" class="inline-flex items-center gap-2 text-2xl font-bold text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" style="margin-top: 8px" viewBox="0 0 24 24" fill="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12.79 21 3 11.21v2c0 .53.21 1.04.59 1.41l7.79 7.79c.78.78 2.05.78 2.83 0l6.21-6.21c.78-.78.78-2.05 0-2.83z"></path><path d="M11.38 17.41c.78.78 2.05.78 2.83 0l6.21-6.21c.78-.78.78-2.05 0-2.83L12.63.58C12.25.21 11.74 0 11.21 0H5C3.9 0 3 .9 3 2v6.21c0 .53.21 1.04.59 1.41zM7.25 3c.69 0 1.25.56 1.25 1.25S7.94 5.5 7.25 5.5 6 4.94 6 4.25 6.56 3 7.25 3"></path>
                    </svg>
                    Cupom Amigo
                </a>
            </div>
            
            
            <div class="card">
                <header>
                    <h2>Entrar na sua conta</h2>
                    <p>Digite suas credenciais para acessar o sistema</p>
                </header>
                
                <section>
                    
                    <?php if (!empty($message)): ?>
                        <?= renderAlert('success', $message) ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <?= renderAlert('error', $error) ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($pw_migration)): ?>
                        <?= renderAlert('warning', 'Atenção', 'Senha armazenada em texto plano — por favor altere sua senha.') ?>
                    <?php endif; ?>
                    
                    
                    <form method="post" action="/src/auth/process_login.php" class="form grid gap-6">
                        
                        <div class="grid gap-2">
                            <label for="usuario">CPF ou CNPJ</label>
                            <input 
                                type="text" 
                                id="usuario" 
                                name="usuario" 
                                class="input"
                                inputmode="numeric"
                                required 
                                placeholder="Digite apenas números"
                                pattern="[0-9]{11,14}"
                                title="Digite 11 dígitos para CPF ou 14 para CNPJ"
                            >
                        </div>
                        
                        <div class="grid gap-2">
                            <div class="flex justify-between items-center">
                                <label for="senha">Senha</label>
                                <a href="/src/views/auth/recuperar-senha.php" class="text-sm text-primary hover:underline">
                                    Esqueceu a senha?
                                </a>
                            </div>
                            <input 
                                type="password" 
                                id="senha" 
                                name="senha" 
                                class="input"
                                required
                                placeholder="Digite sua senha"
                            >
                        </div>
                        
                        <button type="submit" class="btn w-full">
                            Entrar
                        </button>
                        
                    </form>
                </section>
                
                <footer class="text-center">
                    <p class="text-sm text-muted-foreground">
                        Não tem uma conta? 
                        <a href="/src/views/auth/cadastrar.php" class="text-primary hover:underline font-medium">
                            Cadastre-se
                        </a>
                    </p>
                </footer>
            </div>
            
        </div>
    </main>
</body>
</html>

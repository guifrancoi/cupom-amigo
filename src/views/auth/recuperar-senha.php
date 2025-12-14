<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../ui/components/alert.php';

$error = $_SESSION['recuperar_error'] ?? null;
$message = $_SESSION['recuperar_success'] ?? null;
unset($_SESSION['recuperar_error'], $_SESSION['recuperar_success']);

$hideLayout = true;
$title = 'Recuperar Senha';
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
                    <h2>Recuperar Senha</h2>
                    <p>Digite seu e-mail para recuperar sua senha</p>
                </header>
                
                <section>
                    
                    <?php if (!empty($message)): ?>
                        <?= renderAlert('success', $message) ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <?= renderAlert('warning', 'Recurso Desativado', $error) ?>
                    <?php endif; ?>
                    
                    
                    <form method="post" action="/src/auth/process_recuperar_senha.php" class="form grid gap-6">
                        
                        <div class="grid gap-2">
                            <label for="email">E-mail</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="input"
                                required 
                                placeholder="seu@email.com"
                            >
                        </div>
                        
                        <button type="submit" class="btn w-full">
                            Enviar Link de Recuperação
                        </button>
                        
                    </form>
                </section>
                
                <footer class="text-center">
                    <p class="text-sm text-muted-foreground">
                        Lembrou sua senha? 
                        <a href="/src/views/auth/login.php" class="text-primary hover:underline font-medium">
                            Fazer login
                        </a>
                    </p>
                </footer>
            </div>
            
        </div>
    </main>
</body>
</html>

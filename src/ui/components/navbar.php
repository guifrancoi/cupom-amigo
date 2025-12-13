<?php

require_once __DIR__ . '/theme-switcher.php';

function renderNavbar() {
    $userType = $_SESSION['user_type'] ?? null;
    $userId = $_SESSION['user_id'] ?? null;
    $isLoggedIn = !empty($userId);
    
    ob_start();
    ?>
    <header class="border-b bg-background sticky top-0 z-50">
        <div class="container">
            <nav class="flex h-16 items-center justify-between">
                
                <a href="/index.php" class="logo flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.5 12H16c-.7 2-2 3-4 3s-3.3-1-4-3H2.5"/>
                        <path d="M5.5 5.1L2 12v6c0 1.1.9 2 2 2h16a2 2 0 002-2v-6l-3.5-6.9A2 2 0 0016.8 4H7.2a2 2 0 00-1.7 1.1z"/>
                    </svg>
                    <span>Cupom Amigo</span>
                </a>
                
                <?php if ($isLoggedIn): ?>
                    <div class="hidden md:flex items-center gap-6">
                        <?php if ($userType === 'comercio'): ?>
                            <a href="/index.php" class="text-sm font-medium hover:text-primary">
                                Meus Cupons
                            </a>
                            <a href="/src/views/cupons/form.php" class="text-sm font-medium hover:text-primary">
                                Criar Cupom
                            </a>

                        <?php else: ?>
                            <a href="/index.php" class="text-sm font-medium hover:text-primary">
                                Cupons Disponíveis
                            </a>
                            <a href="/src/controllers/AssociadoController.php?action=meusCupons" class="text-sm font-medium hover:text-primary">
                                Meus Cupons
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <?= renderThemeSwitcher('sm') ?>
                        <span class="text-sm text-muted-foreground hidden sm:inline">
                            <?= $userType === 'comercio' ? 'CNPJ' : 'CPF' ?>: <?= htmlspecialchars($userId) ?>
                        </span>
                        <a href="/src/auth/logout.php" class="btn-outline btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Sair
                        </a>
                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-2">
                        <?= renderThemeSwitcher('sm') ?>
                        <a href="/src/views/auth/login.php" class="btn-ghost btn-sm">
                            Entrar
                        </a>
                        <a href="/src/views/auth/cadastrar.php" class="btn btn-sm">
                            Cadastrar
                        </a>
                    </div>
                <?php endif; ?>
                
                
                <button type="button" class="md:hidden btn-ghost btn-icon" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" y1="12" x2="20" y2="12"/>
                        <line x1="4" y1="6" x2="20" y2="6"/>
                        <line x1="4" y1="18" x2="20" y2="18"/>
                    </svg>
                </button>
            </nav>
            
            
            <?php if ($isLoggedIn): ?>
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t pt-4">
                <div class="flex flex-col gap-2">
                    <?php if ($userType === 'comercio'): ?>
                        <a href="/index.php" class="btn-ghost justify-start">Meus Cupons</a>
                        <a href="/src/views/cupons/form.php" class="btn-ghost justify-start">Criar Cupom</a>

                    <?php else: ?>
                        <a href="/index.php" class="btn-ghost justify-start">Cupons Disponíveis</a>
                        <a href="/src/controllers/AssociadoController.php?action=meusCupons" class="btn-ghost justify-start">Meus Cupons</a>
                    <?php endif; ?>
                    <hr class="my-2">
                    <a href="/src/auth/logout.php" class="btn-destructive justify-start">Sair</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </header>
    <?php
    return ob_get_clean();
}
?>

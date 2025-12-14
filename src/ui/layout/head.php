<?php

require_once __DIR__ . '/../../config/config.php';

$pageTitle = $pageTitle ?? 'Cupom Amigo';
$pageTitle = $pageTitle ?? 'Cupom Amigo';
$user_type = $_SESSION['user_type'] ?? 'guest';
$pageDescription = $pageDescription ?? 'Sistema de cupons de desconto para associados e comerciantes';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$basePath = getAppBasePath();

$isAuthPage = isset($hideLayout) && $hideLayout === true;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <title><?= htmlspecialchars($pageTitle) ?> | Cupom Amigo</title>
    
    <script>
      (() => {
        try {
          const stored = localStorage.getItem('themeMode');
          if (stored === 'dark') {
            document.documentElement.classList.add('dark');
          } else if (stored === 'light') {
            document.documentElement.classList.remove('dark');
          }
        } catch (_) {}

        const apply = dark => {
          document.documentElement.classList.toggle('dark', dark);
          try { localStorage.setItem('themeMode', dark ? 'dark' : 'light'); } catch (_) {}
        };

        document.addEventListener('basecoat:theme', (event) => {
          const mode = event.detail?.mode;
          apply(mode === 'dark' ? true
                : mode === 'light' ? false
                : !document.documentElement.classList.contains('dark'));
        });
      })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/basecoat-css@0.3.6/dist/basecoat.cdn.min.css">
    <script src="https://cdn.jsdelivr.net/npm/basecoat-css@0.3.6/dist/js/all.min.js" defer></script>
</head>
<body>
    
    <?php if (!$isAuthPage): ?>
    
    <aside class="sidebar" data-side="left" aria-hidden="false">
        <nav aria-label="Sidebar navigation">
            <header>
                <a href="/" class="btn-ghost p-2 h-12 w-full justify-start">
                    <div class="bg-[#df1240] text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-lg">
                        <svg focusable="false" style="fill: #fff" aria-hidden="true" viewBox="0 0 24 24" tabindex="-1" title="DiscountOutlined"><path d="M12.79 21 3 11.21v2c0 .53.21 1.04.59 1.41l7.79 7.79c.78.78 2.05.78 2.83 0l6.21-6.21c.78-.78.78-2.05 0-2.83z"></path><path d="M11.38 17.41c.39.39.9.59 1.41.59s1.02-.2 1.41-.59l6.21-6.21c.78-.78.78-2.05 0-2.83L12.62.58C12.25.21 11.74 0 11.21 0H5C3.9 0 3 .9 3 2v6.21c0 .53.21 1.04.59 1.41zM5 2h6.21L19 9.79 12.79 16 5 8.21z"></path><circle cx="7.25" cy="4.25" r="1.25"></circle></svg>
                    </div>
                    <div class="grid flex-1 text-left text-sm leading-tight">
                        <span class="truncate font-medium">Cupom amigo</span>
                        <span class="truncate text-xs">v1.0.0</span>
                    </div>
                </a>
            </header>
            
            <section class="scrollbar">
                <!-- <div role="group" aria-labelledby="group-label-principal">
                    <h3 id="group-label-principal">Principal</h3>
                    <ul>
                        <li>
                            <a href="<?= $basePath ?>index.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                    <polyline points="9,22 9,12 15,12 15,22"/>
                                </svg>
                                <span>Início</span>
                            </a>
                        </li>
                    </ul>
                </div> -->
                
                <?php if ($user_type === 'comercio'): ?>
                <div role="group" aria-labelledby="group-label-cupons">
                    <h3 id="group-label-cupons">Cupons</h3>
                    <ul>
                        <li>
                            <a href="<?= $basePath ?>index.php?filter=ativos">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21.5 12H16c-.7 2-2 3-4 3s-3.3-1-4-3H2.5"/>
                                    <path d="M5.5 5.1L2 12v6c0 1.1.9 2 2 2h16a2 2 0 002-2v-6l-3.5-6.9A2 2 0 0016.8 4H7.2a2 2 0 00-1.7 1.1z"/>
                                </svg>
                                <span>Meus Cupons</span>
                            </a>
                        </li>
                        
                        <li>
                            <a href="<?= $basePath ?>src/views/cupons/form.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                <span>Criar Cupom</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <?php if ($user_type === 'associado'): ?>
                <div role="group" aria-labelledby="group-label-associados">
                    <h3 id="group-label-associados">Cupons</h3>
                    <ul>
                        <li>
                            <a href="<?= $basePath ?>index.php?filter=ativos">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21.5 12H16c-.7 2-2 3-4 3s-3.3-1-4-3H2.5"/>
                                    <path d="M5.5 5.1L2 12v6c0 1.1.9 2 2 2h16a2 2 0 002-2v-6l-3.5-6.9A2 2 0 0016.8 4H7.2a2 2 0 00-1.7 1.1z"/>
                                </svg>
                                <span>Cupons Disponíveis</span>
                            </a>
                        </li>
                        
                        <li>
                            <a href="<?= $basePath ?>src/controllers/AssociadoController.php?action=meusCupons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                                </svg>
                                </svg>
                                <span>Meus Cupons</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </section>

            <footer>
                <?php 
                $userName = $_SESSION['user_name'] ?? 'Usuário';
                $userEmail = $_SESSION['user_email'] ?? 'usuario@example.com';
                $userAvatar = $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=df1240&color=fff';
                ?>
                <div id="user-dropdown" class="dropdown-menu">
                    <button id="user-dropdown-trigger" type="button" aria-haspopup="menu" aria-controls="user-dropdown-menu" aria-expanded="false" class="btn-ghost p-2 h-12 w-full flex items-center justify-start" data-keep-mobile-sidebar-open="">
                        <img src="<?= htmlspecialchars($userAvatar) ?>" alt="<?= htmlspecialchars($userName) ?>" class="rounded-lg shrink-0 size-8">
                        <div class="grid flex-1 text-left text-sm leading-tight">
                            <span class="truncate font-medium"><?= htmlspecialchars($userName) ?></span>
                            <span class="truncate text-xs text-muted-foreground"><?= htmlspecialchars($userEmail) ?></span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"></path><path d="m7 9 5-5 5 5"></path></svg>
                    </button>
                    <div id="user-dropdown-popover" data-popover aria-hidden="true" data-side="top" class="w-full">
                        <div role="menu" id="user-dropdown-menu" aria-labelledby="user-dropdown-trigger">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <img alt="<?= htmlspecialchars($userName) ?>" src="<?= htmlspecialchars($userAvatar) ?>" class="size-8 shrink-0 rounded-full">
                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-medium"><?= htmlspecialchars($userName) ?></span>
                                    <span class="text-muted-foreground truncate text-xs"><?= htmlspecialchars($userEmail) ?></span>
                                </div>
                            </div>
                            <hr role="separator">
                            <!-- <a href="<?= $basePath ?>src/controllers/AssociadoController.php?action=meusCupons" role="menuitem">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="7.5 4.21 12 6.81 16.5 4.21"/><polyline points="7.5 19.79 7.5 14.6 3 12"/><polyline points="21 12 16.5 14.6 16.5 19.79"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                Meus Cupons
                            </a> -->
                            <!-- <hr role="separator">
                            <a href="#" role="menuitem">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                Minha Conta
                            </a>
                            <a href="#" role="menuitem">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.268 21a2 2 0 0 0 3.464 0"></path><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path></svg>
                                Notificações
                            </a> -->
                            <!-- <hr role="separator"> -->
                            <a href="<?= $basePath ?>src/auth/logout.php" role="menuitem">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" x2="9" y1="12" y2="12"></line></svg>
                                Sair
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </nav>
    </aside>

    
    <main>
        
        <header class="bg-[var(--background)] sticky inset-x-0 top-0 isolate flex shrink-0 items-center gap-2 border-b z-10 h-14 gap-2 px-4 border-bottom sticky-top">
            <button type="button" onclick="document.dispatchEvent(new CustomEvent('basecoat:sidebar'))" aria-label="Toggle sidebar" data-tooltip="Toggle sidebar" data-side="bottom" data-align="start" class="btn-sm-icon-ghost size-7 -ml-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M9 3v18"></path></svg>
            </button>

            <div class="w-[calc(100%-116px)]"></div>
            
            <button type="button" aria-label="Toggle dark mode" data-tooltip="Toggle dark mode" data-side="bottom" onclick="document.dispatchEvent(new CustomEvent('basecoat:theme'))" class="btn-icon-outline size-8">
                <span class="hidden dark:block"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m6.34 17.66-1.41 1.41"></path><path d="m19.07 4.93-1.41 1.41"></path></svg></span>
                <span class="block dark:hidden"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path></svg></span>
            </button>
        </header>

        
        <div class="<?= $isAuthPage ? 'container-fluid' : 'container-fluid p-6 pb-0' ?>">
    <?php else: ?>
    
    <main class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full">
        
            
            <?php if (isset($_SESSION['message'])): ?>
                <?php 
                $messageType = $_SESSION['message_type'] ?? 'info';
                $message = $_SESSION['message'];
                
                $icon = '';
                switch($messageType) {
                    case 'success':
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><path d="m9 12 2 2 4-4" /></svg>';
                        break;
                    case 'error':
                    case 'danger':
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><line x1="15" y1="9" x2="9" y2="15" /><line x1="9" y1="9" x2="15" y2="15" /></svg>';
                        break;
                    case 'warning':
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" /><path d="M12 9v4" /><path d="m12 17 .01 0" /></svg>';
                        break;
                    default:
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><path d="M12 16v-4" /><path d="m12 8 .01 0" /></svg>';
                }
                
                $alertClass = match($messageType) {
                    'success' => 'alert-success',
                    'error', 'danger' => 'alert-danger',
                    'warning' => 'alert-warning',
                    default => 'alert-info'
                };
                ?>
                
                <div class="alert <?= $alertClass ?> mb-4" role="alert" id="session-alert">
                    <?= $icon ?>
                    <section><?= htmlspecialchars($message) ?></section>
                </div>
                
                <?php 
                unset($_SESSION['message'], $_SESSION['message_type']); 
                ?>
            <?php endif; ?>
    <?php endif; ?>

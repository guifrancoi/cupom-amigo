<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../ui/components/alert.php';
require_once __DIR__ . '/../../ui/components/badge.php';
require_once __DIR__ . '/../../ui/components/tabs.php';
require_once __DIR__ . '/../../ui/components/table.php';
require_once __DIR__ . '/../../ui/components/empty.php';
require_once __DIR__ . '/../../ui/components/dialog.php';

$filter = $filter ?? 'ativos';
$title = 'Gerenciamento de Cupons';
$useSidebar = true;
$currentPage = $filter === 'ativos' ? 'ativos' : ($filter === 'utilizados' ? 'utilizados' : 'vencidos');
?>
<?php require_once __DIR__ . '/../../ui/layout/head.php'; ?>

        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold">Gerenciamento de Cupons</h1>
                <p class="text-muted-foreground">Gerencie todos os seus cupons de desconto</p>
            </div>
            <div class="flex gap-2">
                <a href="/src/views/cupons/form.php" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Criar Cupom
                </a>
            </div>
        </div>
        
        
        <?php if (isset($_SESSION['form_message'])): ?>
            <div class="mt-6">
                <?= renderAlert('success', $_SESSION['form_message']) ?>
            </div>
            <?php unset($_SESSION['form_message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['form_error'])): ?>
            <div class="mt-6">
                <?= renderAlert('error', $_SESSION['form_error']) ?>
            </div>
            <?php unset($_SESSION['form_error']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['registrar_message'])): ?>
            <div class="mt-6">
                <?= renderAlert('success', $_SESSION['registrar_message']) ?>
            </div>
            <?php unset($_SESSION['registrar_message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['registrar_error'])): ?>
            <div class="mt-6">
                <?= renderAlert('error', $_SESSION['registrar_error']) ?>
            </div>
            <?php unset($_SESSION['registrar_error']); ?>
        <?php endif; ?>

        
        <?php
        $iconeCupom = '<svg class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium MuiSvgIcon-root MuiSvgIcon-fontSizeMedium svg-icon css-5zsjn4" focusable="false" aria-hidden="true" viewBox="0 0 24 24" tabindex="-1" title="DiscountOutlined"><path d="M12.79 21 3 11.21v2c0 .53.21 1.04.59 1.41l7.79 7.79c.78.78 2.05.78 2.83 0l6.21-6.21c.78-.78.78-2.05 0-2.83z"></path><path d="M11.38 17.41c.39.39.9.59 1.41.59s1.02-.2 1.41-.59l6.21-6.21c.78-.78.78-2.05 0-2.83L12.62.58C12.25.21 11.74 0 11.21 0H5C3.9 0 3 .9 3 2v6.21c0 .53.21 1.04.59 1.41zM5 2h6.21L19 9.79 12.79 16 5 8.21z"></path><circle cx="7.25" cy="4.25" r="1.25"></circle></svg>';
        $tabs = [
            'ativos' => [
                'label' => 'Cupons Ativos',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'
            ],
            'futuros' => [
                'label' => 'Cupons Futuros',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>'
            ],
            'utilizados' => [
                'label' => 'Cupons Utilizados',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>'
            ],
            'vencidos' => [
                'label' => 'Cupons Vencidos',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>'
            ]
        ];
        echo renderTabNav($tabs, $filter, '/index.php', 'filter');
        ?>
        
        
        <div class="mt-6">
            <form method="get" action="/index.php" class="flex gap-2">
                <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        class="input w-full" 
                        placeholder="Buscar por código do cupom..."
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    >
                </div>
                <button type="submit" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    Buscar
                </button>
                <?php if (!empty($_GET['search'])): ?>
                    <a href="/index.php?filter=<?= htmlspecialchars($filter) ?>" class="btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <path d="M18 6 6 18"/>
                            <path d="m6 6 12 12"/>
                        </svg>
                        Limpar
                    </a>
                <?php endif; ?>
            </form>
        </div>
        
        
        <div class="mt-6">
            <?php if (empty($cupons)): ?>
                <?php
                $cupomIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 12H16c-.7 2-2 3-4 3s-3.3-1-4-3H2.5"/><path d="M5.5 5.1L2 12v6c0 1.1.9 2 2 2h16a2 2 0 002-2v-6l-3.5-6.9A2 2 0 0016.8 4H7.2a2 2 0 00-1.7 1.1z"/></svg>';
                
                $emptyMessages = [
                    'ativos' => [
                        'title' => 'Nenhum Cupom Ativo',
                        'description' => 'Você não possui cupons ativos no momento. Crie novos cupons para começar a oferecer descontos aos associados.',
                        'actions' => [
                            ['label' => 'Criar Novo Cupom', 'href' => '/src/views/cupons/form.php']
                        ]
                    ],
                    'futuros' => [
                        'title' => 'Nenhum Cupom Agendado',
                        'description' => 'Você não possui cupons agendados para o futuro. Crie cupons com data de início futura para planejamento de campanhas.',
                        'actions' => [
                            ['label' => 'Criar Novo Cupom', 'href' => '/src/views/cupons/form.php']
                        ]
                    ],
                    'utilizados' => [
                        'title' => 'Nenhum Cupom Utilizado',
                        'description' => 'Ainda não há cupons utilizados. Quando os associados usarem seus cupons, eles aparecerão aqui.',
                        'actions' => [
                            ['label' => 'Ver Cupons Ativos', 'href' => '/index.php?filter=ativos']
                        ]
                    ],
                    'vencidos' => [
                        'title' => 'Nenhum Cupom Vencido',
                        'description' => 'Você não possui cupons vencidos. Mantenha seus cupons atualizados para melhor engajamento!',
                        'actions' => [
                            ['label' => 'Ver Cupons Ativos', 'href' => '/index.php?filter=ativos']
                        ]
                    ]
                ];
                
                $message = $emptyMessages[$filter] ?? [
                    'title' => 'Nenhum Cupom Encontrado',
                    'description' => 'Não há cupons nesta categoria no momento.',
                    'actions' => []
                ];
                
                echo renderEmpty(
                    $message['title'],
                    $message['description'],
                    $cupomIcon,
                    $message['actions']
                );
                ?>
            <?php else: ?>
                
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($cupons as $cupom): ?>
                        <div class="card">
                            <header>
                                <div class="flex justify-between items-start">
                                    <h2 class="text-lg"><?= htmlspecialchars($cupom['dsc_cupom'] ?? 'N/A') ?></h2>
                                    <?php
                                    if ($filter === 'ativos') {
                                        echo renderBadge('Ativo', 'success');
                                    } elseif ($filter === 'futuros') {
                                        echo renderBadge('Agendado', 'scheduled');
                                    } elseif ($filter === 'utilizados') {
                                        echo renderBadge('Utilizado', 'secondary');
                                    } else {
                                        echo renderBadge('Vencido', 'destructive');
                                    }
                                    ?>
                                </div>
                            </header>
                            <section>
                                <div class="grid gap-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Código:</span>
                                        <span class="font-mono font-medium"><?= htmlspecialchars($cupom['num_cupom'] ?? '') ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Desconto:</span>
                                        <span class="font-bold text-primary"><?= htmlspecialchars($cupom['vlr_desconto'] ?? '0') ?>%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Validade:</span>
                                        <span><?= formatDate($cupom['dta_inicio'] ?? '') ?> - <?= formatDate($cupom['dta_fim'] ?? '') ?></span>
                                    </div>
                                </div>
                                <?php if (($filter === 'utilizados' || $filter === 'vencidos') && !empty($cupom['nome_associado'])): ?>
                                <div class="mt-3 pt-3 border-t border-border">
                                    <div class="flex items-center gap-3">
                                        <div class="size-10 shrink-0 rounded-full bg-[var(--muted)] flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                                <circle cx="12" cy="7" r="4"/>
                                            </svg>
                                        </div>
                                        <div class="grid flex-1 text-left text-sm leading-tight">
                                            <span class="truncate font-medium"><?= htmlspecialchars($cupom['nome_associado']) ?></span>
                                            <?php if (!empty($cupom['dta_uso_associado'])): ?>
                                            <span class="text-muted-foreground truncate text-xs">Usado em <?= formatDate($cupom['dta_uso_associado'] ?? '') ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </section>
                            <?php if ($filter === 'ativos'): ?>
                            <footer>
                                <button 
                                    onclick="openRegistrarDialog(
                                        '<?= htmlspecialchars($cupom['num_cupom']) ?>', 
                                        '<?= htmlspecialchars($cupom['dsc_cupom']) ?>',
                                        '<?= htmlspecialchars($cupom['vlr_desconto']) ?>',
                                        '<?= htmlspecialchars($cupom['qtd_cupom']) ?>',
                                        '<?= formatDate($cupom['dta_inicio']) ?>',
                                        '<?= formatDate($cupom['dta_fim']) ?>'
                                    )" 
                                    class="btn-sm-outline w-full">
                                    Registrar Uso
                                </button>
                            </footer>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                
                <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="mt-6 flex justify-center items-center gap-2">
                        <?php if ($pagination['has_prev']): ?>
                            <a href="?filter=<?= urlencode($filter) ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>&page=<?= $pagination['current_page'] - 1 ?>" class="btn-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                                Anterior
                            </a>
                        <?php endif; ?>
                        
                        <div class="flex gap-1">
                            <?php 
                            $startPage = max(1, $pagination['current_page'] - 2);
                            $endPage = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            
                            if ($startPage > 1): ?>
                                <a href="?filter=<?= urlencode($filter) ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>&page=1" class="btn-outline px-3 py-1">1</a>
                                <?php if ($startPage > 2): ?>
                                    <span class="px-2 py-1">...</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <a href="?filter=<?= urlencode($filter) ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>&page=<?= $i ?>" 
                                   class="<?= $i === $pagination['current_page'] ? 'btn' : 'btn-outline' ?> px-3 py-1">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($endPage < $pagination['total_pages']): ?>
                                <?php if ($endPage < $pagination['total_pages'] - 1): ?>
                                    <span class="px-2 py-1">...</span>
                                <?php endif; ?>
                                <a href="?filter=<?= urlencode($filter) ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>&page=<?= $pagination['total_pages'] ?>" class="btn-outline px-3 py-1"><?= $pagination['total_pages'] ?></a>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($pagination['has_next']): ?>
                            <a href="?filter=<?= urlencode($filter) ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>&page=<?= $pagination['current_page'] + 1 ?>" class="btn-outline">
                                Próxima
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        
        <dialog id="registrar-dialog" class="dialog" aria-labelledby="registrar-title">
            <div>
                <header>
                    <h2 id="registrar-title">Registrar Uso de Cupom</h2>
                    <p id="cupom-nome" class="text-muted-foreground font-medium"></p>
                </header>

                <form method="post" action="/src/auth/process_use_cupom.php" id="form-registrar" class="form">
                    <input type="hidden" name="num_cupom" id="num_cupom">
                    
                    <div class="grid gap-4 py-4">
                        
                        <div class="card -mt-4 mb-2" style="background: var(--muted); border: 1px solid var(--border);">
                            <section>
                                <h3 class="text-sm font-semibold mb-3">Detalhes do Cupom</h3>
                                <div class="grid gap-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Código:</span>
                                        <span class="font-mono font-medium" id="dialog-codigo"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Desconto:</span>
                                        <span class="font-bold text-primary" id="dialog-desconto"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Validade:</span>
                                        <span id="dialog-validade" class="text-sm"></span>
                                    </div>
                                </div>
                            </section>
                        </div>

                        
                        <div class="grid gap-2">
                            <label for="cpf_associado">CPF do Associado</label>
                            <input 
                                type="text" 
                                id="cpf_associado" 
                                name="cpf_associado" 
                                class="input"
                                required
                                placeholder="000.000.000-00"
                                maxlength="14"
                            >
                        </div>
                    </div>

                    <footer>
                        <button type="button" class="btn-outline" onclick="document.getElementById('registrar-dialog').close()">
                            Cancelar
                        </button>
                        <button type="submit" class="btn">
                            Confirmar Uso
                        </button>
                    </footer>
                </form>
            </div>
        </dialog>

        <script>
            function openRegistrarDialog(numCupom, dscCupom, desconto, quantidade, dataInicio, dataFim) {

                document.getElementById('num_cupom').value = numCupom;
                document.getElementById('cupom-nome').textContent = dscCupom;
                

                document.getElementById('dialog-codigo').textContent = numCupom;
                document.getElementById('dialog-desconto').textContent = desconto + '%';
                document.getElementById('dialog-validade').textContent = dataInicio + ' - ' + dataFim;
                

                document.getElementById('cpf_associado').value = '';
                

                document.getElementById('registrar-dialog').showModal();
            }


            document.getElementById('cpf_associado').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                    e.target.value = value;
                }
            });
        </script>

<?php require_once __DIR__ . '/../../ui/layout/footer.php'; ?>
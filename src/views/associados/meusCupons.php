<?php
/**
 * Meus Cupons - Tela para associado consultar cupons reservados (RF007)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../ui/components/alert.php';
require_once __DIR__ . '/../../ui/components/badge.php';
require_once __DIR__ . '/../../ui/components/tabs.php';
require_once __DIR__ . '/../../ui/components/empty.php';


$hoje = date('Y-m-d');



?>
<?php require_once __DIR__ . '/../../ui/layout/head.php'; ?>


<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold">Meus Cupons</h1>
                <p class="text-muted-foreground">Acompanhe seus cupons reservados</p>
            </div>
            <a href="/index.php" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Buscar Cupons
            </a>
        </div>
        
        
        <?php
        $tabs = [
            'ativos' => [
                'label' => 'Cupons Ativos',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'
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
        echo renderTabNav($tabs, $filter, '/src/controllers/AssociadoController.php?action=meusCupons', 'filter');
        ?>
        
        
        <?php if (!empty($error)): ?>
            <div class="mt-4">
                <?= renderAlert('error', $error) ?>
            </div>
        <?php endif; ?>
        
        
        <div class="mt-6">
            <?php if (empty($cupons)): ?>
                <?php

                $cupomIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 12H16c-.7 2-2 3-4 3s-3.3-1-4-3H2.5"/><path d="M5.5 5.1L2 12v6c0 1.1.9 2 2 2h16a2 2 0 002-2v-6l-3.5-6.9A2 2 0 0016.8 4H7.2a2 2 0 00-1.7 1.1z"/></svg>';
                

                $emptyMessages = [
                    'ativos' => [
                        'title' => 'Nenhum Cupom Ativo',
                        'description' => 'Você não possui cupons ativos no momento. Explore cupons disponíveis para reservar.'
                    ],
                    'utilizados' => [
                        'title' => 'Nenhum Cupom Utilizado',
                        'description' => 'Você ainda não utilizou nenhum cupom. Quando usar seus cupons, eles aparecerão aqui.'
                    ],
                    'vencidos' => [
                        'title' => 'Nenhum Cupom Vencido',
                        'description' => 'Você não possui cupons vencidos. Continue aproveitando seus cupons antes que expirem!'
                    ]
                ];
                
                $message = $emptyMessages[$filter] ?? [
                    'title' => 'Nenhum Cupom Encontrado',
                    'description' => 'Não há cupons nesta categoria no momento.'
                ];
                
                $actions = [
                    ['label' => 'Buscar Cupons', 'href' => '/index.php']
                ];
                
                echo renderEmpty(
                    $message['title'],
                    $message['description'],
                    $cupomIcon,
                    $actions
                );
                ?>
            <?php else: ?>
                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <?php foreach ($cupons as $cupom): ?>
                        <div class="card">
                            <header>
                                <div class="flex justify-between items-start gap-2">
                                    <div>
                                        <h2 class="text-lg"><?= htmlspecialchars($cupom['dsc_cupom'] ?? 'N/A') ?></h2>
                                        <?php if (!empty($cupom['nome_fantasia_comercio'])): ?>
                                            <p class="text-sm text-muted-foreground"><?= htmlspecialchars($cupom['nome_fantasia_comercio']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                    if ($cupom['status_cupom_associado'] === 'U') {
                                        echo renderBadge('Utilizado', 'secondary');
                                    } elseif (strtotime($cupom['dta_fim']) < strtotime($hoje)) {
                                        echo renderBadge('Vencido', 'destructive');
                                    } else {
                                        echo renderBadge('Ativo', 'success');
                                    }
                                    ?>
                                </div>
                            </header>
                            <section>
                                
                                <div class="text-center py-2 bg-primary/10 rounded-lg mb-2">
                                    <span class="text-xl font-bold text-primary"><?= htmlspecialchars($cupom['vlr_desconto']) ?>%</span>
                                    <p class="text-xs text-muted-foreground">de desconto</p>
                                </div>
                                
                                <div class="grid gap-1.5 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Código:</span>
                                        <span class="font-mono text-xs"><?= htmlspecialchars($cupom['num_cupom']) ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Válido até:</span>
                                        <span class="text-xs"><?= formatDate($cupom['dta_fim']) ?></span>
                                    </div>
                                    <?php if ($cupom['status_cupom_associado'] === 'U' && !empty($cupom['dta_uso_associado'])): ?>
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Usado em:</span>
                                            <span class="text-green-600 font-medium text-xs"><?= formatDate($cupom['dta_uso_associado']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </section>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                
                <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="mt-6 flex justify-center items-center gap-2">
                        <?php if ($pagination['has_prev']): ?>
                            <a href="/src/controllers/AssociadoController.php?action=meusCupons&filter=<?= urlencode($filter) ?>&page=<?= $pagination['current_page'] - 1 ?>" class="btn-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                                Anterior
                            </a>
                        <?php endif; ?>
                        
                        <div class="flex gap-1">
                            <?php 
                            $startPage = max(1, $pagination['current_page'] - 2);
                            $endPage = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            
                            if ($startPage > 1): ?>
                                <a href="/src/controllers/AssociadoController.php?action=meusCupons&filter=<?= urlencode($filter) ?>&page=1" class="btn-outline px-3 py-1">1</a>
                                <?php if ($startPage > 2): ?>
                                    <span class="px-2 py-1">...</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <a href="/src/controllers/AssociadoController.php?action=meusCupons&filter=<?= urlencode($filter) ?>&page=<?= $i ?>" 
                                   class="<?= $i === $pagination['current_page'] ? 'btn' : 'btn-outline' ?> px-3 py-1">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($endPage < $pagination['total_pages']): ?>
                                <?php if ($endPage < $pagination['total_pages'] - 1): ?>
                                    <span class="px-2 py-1">...</span>
                                <?php endif; ?>
                                <a href="/src/controllers/AssociadoController.php?action=meusCupons&filter=<?= urlencode($filter) ?>&page=<?= $pagination['total_pages'] ?>" class="btn-outline px-3 py-1"><?= $pagination['total_pages'] ?></a>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($pagination['has_next']): ?>
                            <a href="/src/controllers/AssociadoController.php?action=meusCupons&filter=<?= urlencode($filter) ?>&page=<?= $pagination['current_page'] + 1 ?>" class="btn-outline">
                                Próxima
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

<?php require_once __DIR__ . '/../../ui/layout/footer.php'; ?>

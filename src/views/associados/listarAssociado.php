<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../ui/components/alert.php';
require_once __DIR__ . '/../../ui/components/badge.php';
require_once __DIR__ . '/../../ui/components/table.php';
require_once __DIR__ . '/../../ui/components/empty.php';
require_once __DIR__ . '/../../ui/components/dialog.php';

$error = $_SESSION['reservar_error'] ?? null;
$message = $_SESSION['reservar_message'] ?? null;
unset($_SESSION['reservar_error'], $_SESSION['reservar_message']);

$title = 'Cupons Disponíveis';
$currentPage = 'cupons';


$categoriaFiltro = $_GET['categoria'] ?? '';
?>
<?php require_once __DIR__ . '/../../ui/layout/head.php'; ?>

        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold">Cupons Disponíveis</h1>
                <p class="text-muted-foreground">Reserve cupons de desconto dos comerciantes parceiros</p>
            </div>
            <a href="/src/controllers/AssociadoController.php?action=meusCupons" class="btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M21.5 12H16c-.7 2-2 3-4 3s-3.3-1-4-3H2.5"/><path d="M5.5 5.1L2 12v6c0 1.1.9 2 2 2h16a2 2 0 002-2v-6l-3.5-6.9A2 2 0 0016.8 4H7.2a2 2 0 00-1.7 1.1z"/></svg>
                Meus Cupons
            </a>
        </div>
        
        
        <div class="mb-6">
            <?php if (!empty($message)): ?>
                <?= renderAlert('success', $message) ?>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <?= renderAlert('error', $error) ?>
            <?php endif; ?>
        </div>
        
        
        <form method="get" class="flex flex-col sm:flex-row gap-4 items-end mb-6">
                    <div>
                        <label for="categoria" class="text-sm font-medium">Filtrar por categoria</label>
                        <div class="flex gap-2 mt-2">
                        <select id="categoria" name="categoria" class="select">
                            <option value="">Todas as categorias</option>
                            <?php if (!empty($categorias)): ?>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat['id_categoria']) ?>" 
                                        <?= ($categoriaFiltro == $cat['id_categoria']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['nome_categoria']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>

                        <button type="submit" class="btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                            Filtrar
                        </button>
                        <?php if (!empty($categoriaFiltro)): ?>
                            <a href="?" class="btn-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                Limpar
                            </a>
                        <?php endif; ?>
                         </div>
                    </div>
                </form>
        
        
        <?php if (empty($cupons)): ?>
            <?php
            $cupomIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 12H16c-.7 2-2 3-4 3s-3.3-1-4-3H2.5"/><path d="M5.5 5.1L2 12v6c0 1.1.9 2 2 2h16a2 2 0 002-2v-6l-3.5-6.9A2 2 0 0016.8 4H7.2a2 2 0 00-1.7 1.1z"/></svg>';
            
            $description = !empty($categoriaFiltro) 
                ? 'Não há cupons disponíveis nesta categoria no momento. Experimente outras categorias ou volte mais tarde.' 
                : 'Não há cupons disponíveis no momento. Volte mais tarde para ver novas ofertas.';
            
            $actions = [];
            if (!empty($categoriaFiltro)) {
                $actions[] = ['label' => 'Ver Todos os Cupons', 'href' => '?'];
            }
            
            echo renderEmpty(
                'Nenhum Cupom Disponível',
                $description,
                $cupomIcon,
                $actions
            );
            ?>
        <?php else: ?>
            <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <?php foreach ($cupons as $cupom): ?>
                    <div class="card">
                        <header>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="text-lg"><?= htmlspecialchars($cupom['dsc_cupom'] ?? 'N/A') ?></h2>
                                    <p class="text-sm text-muted-foreground"><?= htmlspecialchars($cupom['nome_fantasia_comercio'] ?? 'Comércio') ?></p>
                                </div>
                                <?= renderBadge($cupom['nome_categoria'] ?? 'Geral', 'secondary') ?>
                            </div>
                        </header>
                        <section>
                            <div class="grid gap-2">
                                
                                <div class="text-center py-2 bg-primary/10 rounded-lg">
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
                                </div>
                            </div>
                        </section>
                        <footer>
                            <form method="post" action="/src/controllers/AssociadoController.php" class="w-full" id="form-reservar-<?= htmlspecialchars($cupom['num_cupom']) ?>">
                                <input type="hidden" name="action" value="reservar">
                                <input type="hidden" name="num_cupom" value="<?= htmlspecialchars($cupom['num_cupom']) ?>">
                                <button type="button" class="btn w-full" onclick="<?= openDialog('dialog-reservar-' . htmlspecialchars($cupom['num_cupom'])) ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                                    Reservar Cupom
                                </button>
                            </form>
                            
                            
                            <dialog id="dialog-reservar-<?= htmlspecialchars($cupom['num_cupom']) ?>" class="dialog" aria-labelledby="dialog-reservar-title-<?= htmlspecialchars($cupom['num_cupom']) ?>">
                                <div>
                                    <header>
                                        <h2 id="dialog-reservar-title-<?= htmlspecialchars($cupom['num_cupom']) ?>">Confirmar Reserva</h2>
                                        <p class="text-muted-foreground font-medium"><?= htmlspecialchars($cupom['dsc_cupom']) ?></p>
                                    </header>

                                    <div class="grid gap-4 py-4">
                                        
                                        <div class="card -mt-4 mb-2" style="background: var(--muted); border: 1px solid var(--border);">
                                            <section>
                                                <h3 class="text-sm font-semibold mb-3">Detalhes do Cupom</h3>
                                                <div class="grid gap-2 text-sm">
                                                    <div class="flex justify-between">
                                                        <span class="text-muted-foreground">Comércio:</span>
                                                        <span class="font-medium"><?= htmlspecialchars($cupom['nome_fantasia_comercio'] ?? 'N/A') ?></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-muted-foreground">Código:</span>
                                                        <span class="font-mono font-medium"><?= htmlspecialchars($cupom['num_cupom']) ?></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-muted-foreground">Desconto:</span>
                                                        <span class="font-bold text-primary"><?= htmlspecialchars($cupom['vlr_desconto']) ?>%</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-muted-foreground">Validade:</span>
                                                        <span class="text-sm"><?= formatDate($cupom['dta_inicio']) ?> até <?= formatDate($cupom['dta_fim']) ?></span>
                                                    </div>
                                                    <?php if (!empty($cupom['nome_categoria'])): ?>
                                                    <div class="flex justify-between">
                                                        <span class="text-muted-foreground">Categoria:</span>
                                                        <span class="text-sm"><?= htmlspecialchars($cupom['nome_categoria']) ?></span>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </section>
                                        </div>

                                        <p class="text-sm text-muted-foreground">
                                            Após a reserva, o cupom será exclusivo para você e poderá ser utilizado no comércio.
                                        </p>
                                    </div>

                                    <footer>
                                        <button type="button" class="btn-outline" onclick="document.getElementById('dialog-reservar-<?= htmlspecialchars($cupom['num_cupom']) ?>').close()">
                                            Cancelar
                                        </button>
                                        <button type="button" class="btn" onclick="document.getElementById('form-reservar-<?= htmlspecialchars($cupom['num_cupom']) ?>').submit()">
                                            Confirmar Reserva
                                        </button>
                                    </footer>
                                </div>
                            </dialog>
                        </footer>
                    </div>
                <?php endforeach; ?>
            </div>
            
            
            <?php if ($pagination['totalPages'] > 1): ?>
                <div class="mt-6 flex justify-center items-center gap-2">
                    <?php if ($pagination['hasPrev']): ?>
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?categoria=<?= $categoriaFiltro ?? '' ?>&page=<?= $pagination['currentPage'] - 1 ?>" class="btn-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                            Anterior
                        </a>
                    <?php endif; ?>
                    
                    <div class="flex gap-1">
                        <?php 
                        $startPage = max(1, $pagination['currentPage'] - 2);
                        $endPage = min($pagination['totalPages'], $pagination['currentPage'] + 2);
                        
                        if ($startPage > 1): ?>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?categoria=<?= $categoriaFiltro ?? '' ?>&page=1" class="btn-outline px-3 py-1">1</a>
                            <?php if ($startPage > 2): ?>
                                <span class="px-2 py-1">...</span>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?categoria=<?= $categoriaFiltro ?? '' ?>&page=<?= $i ?>" 
                               class="<?= $i === $pagination['currentPage'] ? 'btn' : 'btn-outline' ?> px-3 py-1">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($endPage < $pagination['totalPages']): ?>
                            <?php if ($endPage < $pagination['totalPages'] - 1): ?>
                                <span class="px-2 py-1">...</span>
                            <?php endif; ?>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?categoria=<?= $categoriaFiltro ?? '' ?>&page=<?= $pagination['totalPages'] ?>" class="btn-outline px-3 py-1"><?= $pagination['totalPages'] ?></a>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($pagination['hasNext']): ?>
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?categoria=<?= $categoriaFiltro ?? '' ?>&page=<?= $pagination['currentPage'] + 1 ?>" class="btn-outline">
                            Próxima
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

<?php require_once __DIR__ . '/../../ui/layout/footer.php'; ?>

<?php

function renderEmpty(
    string $title = 'No Data Yet',
    string $description = 'There is no data to display at the moment.',
    string $icon = '',
    array $actions = [],
    string $learnMoreUrl = ''
): string {
    $defaultIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10.5 8 13l2 2.5" /><path d="m14 10.5 2 2.5-2 2.5" /><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2z" /></svg>';
    $defaultIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10.5 8 13l2 2.5" /><path d="m14 10.5 2 2.5-2 2.5" /><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2z" /></svg>';
    $iconSvg = $icon ?: $defaultIcon;
    
    ob_start();
    ?>
    <div class="flex min-w-0 flex-1 flex-col items-center justify-center gap-6 rounded-lg border border-dashed p-6 text-center text-balance md:p-12">
        <header class="flex max-w-sm flex-col items-center gap-2 text-center">
            <div class="mb-2 [&_svg]:pointer-events-none [&_svg]:shrink-0 dark:bg-[var(--color-muted)] text-foreground flex size-10 shrink-0 items-center justify-center rounded-lg [&_svg:not([class*='size-'])]:size-6">
                <?= $iconSvg ?>
            </div>
            <h3 class="text-lg font-medium tracking-tight"><?= htmlspecialchars($title) ?></h3>
            <p class="text-muted-foreground [&>a:hover]:text-primary text-sm/relaxed [&>a]:underline [&>a]:underline-offset-4">
                <?= htmlspecialchars($description) ?>
            </p>
        </header>
        
        <?php if (!empty($actions)): ?>
        <section class="flex w-full max-w-sm min-w-0 flex-col items-center gap-4 text-sm text-balance">
            <div class="flex gap-2 flex-wrap justify-center">
                <?php foreach ($actions as $index => $action): ?>
                    <?php 
                    $variant = $action['variant'] ?? ($index === 0 ? '' : 'outline');
                    $btnClass = $variant === 'outline' ? 'btn-outline' : 'btn';
                    ?>
                    <a href="<?= htmlspecialchars($action['href']) ?>" class="<?= $btnClass ?>">
                        <?= htmlspecialchars($action['label']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <?php if (!empty($learnMoreUrl)): ?>
        <a href="<?= htmlspecialchars($learnMoreUrl) ?>" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive underline-offset-4 hover:underline h-8 rounded-md gap-1.5 px-3 has-[>svg]:px-2.5 text-muted-foreground">
            Saiba mais
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 7h10v10" /><path d="M7 17 17 7" /></svg>
        </a>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

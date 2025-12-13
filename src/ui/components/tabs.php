<?php

function renderTabNav($tabs, $activeTab, $baseUrl, $paramName = 'filter', $class = '') {
    ob_start();
    ?>
    <div class="tabs <?= htmlspecialchars($class) ?>">
        <nav role="tablist" aria-orientation="horizontal">
            <?php foreach ($tabs as $id => $tab): ?>
                <?php 
                    $label = is_array($tab) ? $tab['label'] : $tab;
                    $icon = is_array($tab) && isset($tab['icon']) ? $tab['icon'] : '';
                    $separator = strpos($baseUrl, '?') !== false ? '&' : '?';
                ?>
                <a 
                    href="<?= htmlspecialchars($baseUrl) ?><?= $separator ?><?= htmlspecialchars($paramName) ?>=<?= htmlspecialchars($id) ?>"
                    role="tab"
                    aria-selected="<?= $activeTab === $id ? 'true' : 'false' ?>"
                    tabindex="0"
                >
                    <?php if ($icon): ?>
                        <?= $icon ?>
                        <span><?= htmlspecialchars($label) ?></span>
                    <?php else: ?>
                        <?= htmlspecialchars($label) ?>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
    <?php
    return ob_get_clean();
}

function renderTabsWithPanels($id, $tabs, $class = '') {
    $firstKey = array_key_first($tabs);
    
    ob_start();
    ?>
    <div class="tabs <?= htmlspecialchars($class) ?>" id="<?= htmlspecialchars($id) ?>" data-tabs-initialized="true">
        <nav role="tablist" aria-orientation="horizontal">
            <?php $isFirst = true; foreach ($tabs as $tabId => $tab): ?>
                <button 
                    type="button" 
                    role="tab" 
                    id="<?= htmlspecialchars($id) ?>-tab-<?= htmlspecialchars($tabId) ?>"
                    aria-controls="<?= htmlspecialchars($id) ?>-panel-<?= htmlspecialchars($tabId) ?>"
                    aria-selected="<?= $isFirst ? 'true' : 'false' ?>"
                    tabindex="0"    
                >
                    <?php if (isset($tab['icon'])): ?>
                        <?= $tab['icon'] ?>
                        <span><?= htmlspecialchars($tab['label']) ?></span>
                    <?php else: ?>
                        <?= htmlspecialchars($tab['label']) ?>
                    <?php endif; ?>
                </button>
            <?php $isFirst = false; endforeach; ?>
        </nav>
        
        <?php $isFirst = true; foreach ($tabs as $tabId => $tab): ?>
            <div 
                role="tabpanel" 
                id="<?= htmlspecialchars($id) ?>-panel-<?= htmlspecialchars($tabId) ?>"
                aria-labelledby="<?= htmlspecialchars($id) ?>-tab-<?= htmlspecialchars($tabId) ?>"
                tabindex="-1"
                <?= $isFirst ? '' : 'hidden' ?>
            >
                <?= $tab['content'] ?>
            </div>
        <?php $isFirst = false; endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
?>

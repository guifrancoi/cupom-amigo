
    
    <footer class="border-t py-6 mt-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-center items-center gap-8">
                <p class="text-sm text-muted-foreground">
                    &copy; <?= date('Y') ?> Cupom Amigo - Associação de Moradores e Comerciantes
                </p>
            </div>
        </div>
    </footer>
    
    <?php 
    require_once __DIR__ . '/../components/theme-switcher.php';
    if (function_exists('renderThemeScript')) {
        echo renderThemeScript();
    }
    ?>
</body>
</html>

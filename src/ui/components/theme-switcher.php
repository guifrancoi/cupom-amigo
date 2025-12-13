<?php

function renderThemeSwitcher(string $size = 'md'): string {
    $sizeClasses = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6'
    ];
    $iconSize = $sizeClasses[$size] ?? $sizeClasses['md'];
    
    return <<<HTML
    <button 
        type="button"
        id="theme-toggle"
        class="btn-ghost rounded-full p-2"
        aria-label="Alternar tema"
        title="Alternar tema claro/escuro"
    >
        
        <svg 
            id="theme-icon-light" 
            xmlns="http://www.w3.org/2000/svg" 
            class="{$iconSize} hidden" 
            viewBox="0 0 24 24" 
            fill="none" 
            stroke="currentColor" 
            stroke-width="2" 
            stroke-linecap="round" 
            stroke-linejoin="round"
        >
            <circle cx="12" cy="12" r="4"/>
            <path d="M12 2v2"/>
            <path d="M12 20v2"/>
            <path d="m4.93 4.93 1.41 1.41"/>
            <path d="m17.66 17.66 1.41 1.41"/>
            <path d="M2 12h2"/>
            <path d="M20 12h2"/>
            <path d="m6.34 17.66-1.41 1.41"/>
            <path d="m19.07 4.93-1.41 1.41"/>
        </svg>
        
        
        <svg 
            id="theme-icon-dark" 
            xmlns="http://www.w3.org/2000/svg" 
            class="{$iconSize}" 
            viewBox="0 0 24 24" 
            fill="none" 
            stroke="currentColor" 
            stroke-width="2" 
            stroke-linecap="round" 
            stroke-linejoin="round"
        >
            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
        </svg>
    </button>
    HTML;
}

function renderThemeScript(): string {
    return <<<'SCRIPT'
    <script>
    (function() {
        const toggle = document.getElementById('theme-toggle');
        const iconLight = document.getElementById('theme-icon-light');
        const iconDark = document.getElementById('theme-icon-dark');
        const html = document.documentElement;
        
        function updateIcons(isDark) {
            if (iconLight && iconDark) {
                iconLight.classList.toggle('hidden', !isDark);
                iconDark.classList.toggle('hidden', isDark);
            }
        }
        
        function setTheme(theme) {
            if (theme === 'dark') {
                html.classList.add('dark');
                localStorage.setItem('themeMode', 'dark');
                updateIcons(true);
            } else {
                html.classList.remove('dark');
                localStorage.setItem('themeMode', 'light');
                updateIcons(false);
            }
        }
        
        function getPreferredTheme() {
            const saved = localStorage.getItem('themeMode');
            return saved || 'light';
        }
        
        const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
        updateIcons(currentTheme === 'dark');
        
        if (toggle) {
            toggle.addEventListener('click', function() {
                const isDark = html.classList.contains('dark');
                setTheme(isDark ? 'light' : 'dark');
            });
        }
        
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            if (!localStorage.getItem('themeMode')) {
                setTheme(e.matches ? 'dark' : 'light');
            }
        });
    })();
    </script>
    SCRIPT;
}

// Theme Helper - Shared theme functionality
(function(){
    // Apply theme early
    function initTheme(){
        try{
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        }catch(_e){}
    }
    
    // Apply theme
    function applyTheme(theme){
        const t = (theme === 'dark') ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', t);
        try{ localStorage.setItem('theme', t); }catch(_e){}
        updateThemeToggle(t);
    }
    
    // Update theme toggle button
    function updateThemeToggle(theme){
        const icon = document.getElementById('theme-icon');
        const text = document.getElementById('theme-text');
        if (icon && text){
            if (theme === 'dark'){
                icon.textContent = '☀️';
                text.textContent = 'Light';
            } else {
                icon.textContent = '🌙';
                text.textContent = 'Dark';
            }
        }
    }
    
    // Toggle theme
    function toggleTheme(){
        const current = document.documentElement.getAttribute('data-theme') || 'light';
        const next = current === 'dark' ? 'light' : 'dark';
        applyTheme(next);
    }
    
    // Initialize on load
    if (document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', function(){
            initTheme();
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                updateThemeToggle(savedTheme);
            }catch(_e){}
        });
    } else {
        initTheme();
        try{
            const savedTheme = localStorage.getItem('theme') || 'light';
            updateThemeToggle(savedTheme);
        }catch(_e){}
    }
    
    // Expose toggleTheme globally
    window.toggleTheme = toggleTheme;
})();


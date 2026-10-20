{{--
    Flash-prevention: runs synchronously before browser paint in <head>.
    Evaluates saved localStorage theme or prefers-color-scheme media query,
    setting .dark on <html> immediately to avoid flash of wrong theme (FOUC).
--}}
<meta name="color-scheme" content="light dark">
<script>
(function () {
    try {
        var stored = localStorage.getItem('theme');
        var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        var isDark = stored ? (stored === 'dark') : prefersDark;

        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        document.documentElement.classList.add('js');
    } catch (e) {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
    }

    /* Expose toggle globally so any button can call it */
    window.toggleTheme = function () {
        var isDarkNow = document.documentElement.classList.toggle('dark');
        try {
            localStorage.setItem('theme', isDarkNow ? 'dark' : 'light');
        } catch (e) {}
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: isDarkNow ? 'dark' : 'light' } }));
    };

    /* Listen for OS theme changes if user has not stored an explicit preference */
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            try {
                if (!localStorage.getItem('theme')) {
                    if (e.matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            } catch (err) {}
        });
    }
})();
</script>

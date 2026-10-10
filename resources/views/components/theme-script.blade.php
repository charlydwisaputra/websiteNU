{{--
    Flash-prevention: runs before paint.
    Sets .dark on <html> and color-scheme on <meta> immediately
    so there is no flash of wrong theme on load.
--}}
<meta name="color-scheme" content="light dark">
<script>
(function () {
    var stored = localStorage.getItem('theme');
    var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    var isDark = stored === 'dark' || (!stored && prefersDark);

    if (isDark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    /* Expose toggle globally so any button can call it */
    window.toggleTheme = function () {
        var nowDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', nowDark ? 'dark' : 'light');
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: nowDark ? 'dark' : 'light' } }));
    };
})();
</script>

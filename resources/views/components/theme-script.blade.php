{{--
    Flash-prevention: runs synchronously before browser paint in <head>.
    Default theme is LIGHT. Dark is used only when the visitor explicitly
    chose it via the toggle button (saved in localStorage as 'dark').
--}}
<meta name="color-scheme" content="light">
<script>
(function () {
    document.documentElement.classList.add('js');

    var meta = document.querySelector('meta[name="color-scheme"]');

    function applyScheme(isDark) {
        if (meta) meta.setAttribute('content', isDark ? 'dark' : 'light');
    }

    var stored = null;
    try {
        stored = localStorage.getItem('theme');
    } catch (e) {}

    var isDark = stored === 'dark';

    if (isDark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    applyScheme(isDark);

    /* Expose toggle globally so any button can call it */
    window.toggleTheme = function () {
        var isDarkNow = document.documentElement.classList.toggle('dark');
        try {
            localStorage.setItem('theme', isDarkNow ? 'dark' : 'light');
        } catch (e) {}
        applyScheme(isDarkNow);
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: isDarkNow ? 'dark' : 'light' } }));
    };
})();
</script>

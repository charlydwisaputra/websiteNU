@php
    $isHome   = request()->routeIs('home');
    $homeUrl  = route('home');

    $links = [
        [
            'href'    => $isHome ? '#hero-section' : $homeUrl,
            'label'   => 'Beranda',
            'active'  => false,
            'section' => $isHome ? 'hero-section' : null,
        ],
        [
            'href'    => $isHome ? '#ekosistem' : $homeUrl . '#ekosistem',
            'label'   => 'Organisasi',
            'active'  => false,
            'section' => $isHome ? 'ekosistem' : null,
        ],
        [
            'href'    => route('anggota'),
            'label'   => 'Anggota',
            'active'  => request()->routeIs('anggota'),
            'section' => null,
        ],
        [
            'href'    => route('galeri'),
            'label'   => 'Dokumentasi',
            'active'  => request()->routeIs('galeri'),
            'section' => null,
        ],
        [
            'href'    => route('berita.index'),
            'label'   => 'Berita',
            'active'  => request()->routeIs('berita*'),
            'section' => null,
        ],
    ];
@endphp

<style>
    /* Premium OS-like Floating Glass Navbar */
    .glass-navbar-container {
        /* Light Mode: More transparent for a glossy feel */
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: 
            0 4px 24px -4px rgba(0, 0, 0, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.5);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .dark .glass-navbar-container {
        /* Dark Mode: Deep Black Iris with higher transparency */
        background: rgba(8, 8, 19, 0.35);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 
            0 8px 32px -4px rgba(0, 0, 0, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.08);
    }

    /* Scrolled state: slightly more opaque for readability but still very glossy */
    header.is-scrolled .glass-navbar-container {
        background: rgba(255, 255, 255, 0.65);
        box-shadow: 
            0 12px 40px -8px rgba(0, 0, 0, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.6);
    }
    .dark header.is-scrolled .glass-navbar-container {
        background: rgba(8, 8, 19, 0.6);
        box-shadow: 
            0 12px 40px -8px rgba(0, 0, 0, 0.6),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    /* Navigation Links */
    .nav-link {
        position: relative;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
        padding: 0.45rem 1.1rem;
        border-radius: 9999px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transform: scale(1);
    }
    .dark .nav-link {
        color: var(--text-secondary);
    }
    
    .nav-link:hover {
        color: var(--text-primary);
        background: rgba(0, 0, 0, 0.04);
        transform: scale(1.02);
    }
    .dark .nav-link:hover {
        color: var(--text-primary);
        background: rgba(255, 255, 255, 0.06);
    }

    /* Active Navigation: Selected Pill Feel */
    .nav-link.is-active, 
    .nav-link[data-active="true"] {
        color: var(--accent) !important;
        background: var(--bg-surface) !important;
        font-weight: 600 !important;
        box-shadow: 
            0 2px 10px -2px var(--accent-subtle),
            inset 0 0 0 1px rgba(0, 140, 90, 0.1);
        transform: scale(1.02);
    }
    .dark .nav-link.is-active, 
    .dark .nav-link[data-active="true"] {
        background: rgba(255, 255, 255, 0.08) !important;
        box-shadow: 
            0 2px 12px -2px rgba(0, 179, 115, 0.2),
            inset 0 0 0 1px rgba(0, 179, 115, 0.15);
    }

    /* Dashboard Button */
    .btn-dashboard {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 1rem 0.35rem 0.35rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        background: var(--bg-surface);
        border: 1px solid var(--border-medium);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
    }
    .btn-dashboard:hover {
        border-color: var(--accent);
        box-shadow: 0 4px 12px -2px var(--accent-subtle);
        transform: scale(1.02);
    }
    .dark .btn-dashboard {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
    }
    .dark .btn-dashboard:hover {
        border-color: var(--accent);
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 12px -2px rgba(0, 179, 115, 0.2);
    }
    .btn-dashboard-icon {
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        color: white;
        background: var(--accent);
        transition: transform 0.3s ease;
    }
    .btn-dashboard:hover .btn-dashboard-icon {
        transform: scale(1.1);
    }
    
    /* OS-like Theme Toggle */
    .theme-toggle-btn {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .theme-toggle-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 9999px;
        background: var(--text-primary);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .theme-toggle-btn:hover::before {
        opacity: 0.06;
    }
    .dark .theme-toggle-btn:hover::before {
        opacity: 0.1;
    }
    .theme-toggle-btn svg {
        position: relative;
        z-index: 1;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .theme-toggle-btn:hover svg {
        transform: rotate(15deg) scale(1.1);
        color: var(--text-primary);
    }
    
    /* Logo Interaction */
    .logo-container {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .logo-container:hover {
        transform: scale(1.03);
    }
    .logo-icon-wrap {
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 2px 8px -2px var(--accent-subtle);
    }
    .logo-container:hover .logo-icon-wrap {
        transform: rotate(-5deg) scale(1.05);
        box-shadow: 0 4px 12px -2px rgba(0, 140, 90, 0.3);
    }
    .dark .logo-container:hover .logo-icon-wrap {
        box-shadow: 0 4px 12px -2px rgba(0, 179, 115, 0.4);
    }
</style>

<header id="glass-navbar" class="fixed left-0 right-0 z-50 top-4 px-4 transition-all duration-300 ease-out">

    <div class="glass-navbar-container relative mx-auto w-full max-w-[1100px] h-16 rounded-full
                flex items-center justify-between px-4 sm:px-6">

        {{-- LEFT: Logo --}}
        <a href="{{ $homeUrl }}" class="flex items-center gap-3 shrink-0 logo-container" aria-label="Beranda NU Cikembulan">
            <div class="logo-icon-wrap w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-sm"
                 style="background:var(--accent)">
                NU
            </div>
            <div class="hidden sm:flex flex-col leading-none">
                <span class="font-serif font-bold text-[15px]" style="color:var(--text-primary)">NU Cikembulan</span>
                <span class="text-[10px] font-medium uppercase tracking-widest" style="color:var(--text-muted)">Ranting</span>
            </div>
        </a>

        {{-- CENTER: Nav --}}
        <nav class="hidden lg:flex items-center gap-1">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}"
                   class="nav-link{{ $link['active'] ? ' is-active' : '' }}"
                   @if($link['section']) data-section="{{ $link['section'] }}" data-active="false" @endif>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- RIGHT: Controls --}}
        <div class="hidden lg:flex items-center gap-2">

            {{-- Theme toggle --}}
            <button type="button" onclick="toggleTheme()" aria-label="Ganti tema" class="theme-toggle-btn">
                <svg class="icon-sun w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg class="icon-moon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            @if(Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-dashboard">
                        <span class="btn-dashboard-icon">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                        Dashboard
                    </a>
                @else
                    <div class="relative group">
                        <button class="w-9 h-9 rounded-full flex items-center justify-center transition-all"
                                style="color:var(--text-muted)"
                                onmouseover="this.style.background='rgba(0,0,0,0.05)'"
                                onmouseout="this.style.background=''">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-44 rounded-2xl py-2 opacity-0 invisible
                                    group-hover:opacity-100 group-hover:visible transition-all"
                             style="background:var(--bg-surface);border:1px solid var(--border-light);box-shadow:var(--shadow-md)">
                            <a href="{{ route('login') }}"
                               class="block px-4 py-2.5 text-sm font-medium transition-colors"
                               style="color:var(--text-secondary)"
                               onmouseover="this.style.background='var(--bg-surface-elevated)';this.style.color='var(--text-primary)'"
                               onmouseout="this.style.background='';this.style.color='var(--text-secondary)'">
                                Masuk Admin
                            </a>
                        </div>
                    </div>
                @endauth
            @endif
        </div>

        {{-- MOBILE: Controls --}}
        <div class="lg:hidden flex items-center gap-2">
            <button type="button" onclick="toggleTheme()" aria-label="Ganti tema" class="theme-toggle-btn">
                <svg class="icon-sun w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg class="icon-moon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            <button id="mobile-menu-btn" type="button" aria-label="Buka menu" aria-expanded="false"
                    class="w-9 h-9 rounded-full flex items-center justify-center transition-all"
                    style="color:var(--text-primary)"
                    onmouseover="this.style.background='var(--bg-surface-elevated)'"
                    onmouseout="this.style.background=''">
                <svg id="icon-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- MOBILE DRAWER --}}
    <div id="mobile-menu"
         class="mobile-sheet hidden absolute top-[76px] left-4 right-4 rounded-2xl p-2 shadow-lg"
         style="background:var(--bg-surface);border:1px solid var(--border-light)">
        <ul class="flex flex-col gap-0.5">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link['href'] }}"
                       class="mobile-nav-link block px-4 py-3 rounded-xl text-sm font-medium transition-all
                              {{ $link['active'] ? 'is-active' : '' }}"
                       @if($link['section']) data-section="{{ $link['section'] }}" data-active="false" @endif
                       style="{{ $link['active'] ? 'color:var(--accent);background:var(--accent-subtle);font-weight:600' : 'color:var(--text-secondary)' }}"
                       onmouseover="{{ $link['active'] ? '' : "this.style.background='var(--bg-surface-elevated)';this.style.color='var(--text-primary)'" }}"
                       onmouseout="{{ $link['active'] ? '' : "this.style.background='';this.style.color='var(--text-secondary)'" }}">
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach

            <li><div class="h-px my-1" style="background:var(--border-light)"></div></li>

            @auth
                <li>
                    <a href="{{ url('/dashboard') }}"
                       class="block px-4 py-3 rounded-xl text-sm font-medium transition-all"
                       style="color:var(--text-secondary)"
                       onmouseover="this.style.background='var(--bg-surface-elevated)';this.style.color='var(--text-primary)'"
                       onmouseout="this.style.background='';this.style.color='var(--text-secondary)'">
                        Dashboard — {{ Auth::user()->name }}
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}"
                       class="block px-4 py-3 rounded-xl text-sm font-medium transition-all"
                       style="color:var(--text-secondary)"
                       onmouseover="this.style.background='var(--bg-surface-elevated)';this.style.color='var(--text-primary)'"
                       onmouseout="this.style.background='';this.style.color='var(--text-secondary)'">
                        Masuk Admin
                    </a>
                </li>
            @endauth
        </ul>
    </div>
</header>

<script>
    // Scroll behavior for glass navbar
    window.addEventListener('scroll', function() {
        const header = document.getElementById('glass-navbar');
        if (window.scrollY > 20) {
            header.classList.add('is-scrolled');
        } else {
            header.classList.remove('is-scrolled');
        }
    });
</script>

{{-- ── SCROLL-SPY: only on home page ── --}}
@if($isHome)
<script>
(function () {
    var sectionIds = ['hero-section', 'ekosistem'];
    var visible    = {};

    function setActive(id) {
        document.querySelectorAll('.nav-link[data-section]').forEach(function (el) {
            var match = el.dataset.section === id;
            if (match) {
                el.dataset.active = 'true';
            } else {
                el.dataset.active = 'false';
            }
        });
        document.querySelectorAll('.mobile-nav-link[data-section]').forEach(function (el) {
            var match = el.dataset.section === id;
            if (match) {
                el.style.color      = 'var(--accent)';
                el.style.background = 'var(--accent-subtle)';
                el.style.fontWeight = '600';
            } else {
                el.style.color      = 'var(--text-secondary)';
                el.style.background = '';
                el.style.fontWeight = '500';
            }
        });
    }

    sectionIds.forEach(function (id) {
        var el = document.getElementById(id);
        if (!el) return;
        new IntersectionObserver(function (entries) {
            entries.forEach(function (e) { visible[id] = e.isIntersecting; });
            
            // Find which one is visible (using strict center-screen margin)
            var active = sectionIds.find(function (s) { return visible[s]; });
            
            // Force hero-section if at the very top of page
            if (window.scrollY < 100) active = 'hero-section';
            
            if (active) setActive(active);
        }, { rootMargin: '-40% 0px -40% 0px', threshold: 0 }).observe(el);
    });

    // Re-check on scroll just in case we hit the absolute top
    window.addEventListener('scroll', function() {
        if (window.scrollY < 100) setActive('hero-section');
    });

    // Check hash on load
    if (window.location.hash) {
        var hashId = window.location.hash.substring(1);
        if (sectionIds.includes(hashId)) setActive(hashId);
    } else {
        setActive('hero-section');
    }
})();
</script>
@endif


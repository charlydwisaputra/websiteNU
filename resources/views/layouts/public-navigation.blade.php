@php
    $isHome   = request()->routeIs('home');

    $links = [
        [
            'href'    => route('home'),
            'label'   => 'Beranda',
            'active'  => request()->routeIs('home'),
        ],
        [
            'href'    => route('tentang'),
            'label'   => 'Tentang',
            'active'  => request()->routeIs('tentang'),
        ],
        [
            'href'    => route('ekosistem'),
            'label'   => 'Ekosistem',
            'active'  => request()->routeIs('ekosistem'),
        ],
        [
            'href'    => route('berita.index'),
            'label'   => 'Warta',
            'active'  => request()->routeIs('berita*'),
        ],
        [
            'href'    => route('galeri'),
            'label'   => 'Dokumentasi',
            'active'  => request()->routeIs('galeri'),
        ],
    ];
@endphp

<style>
    /* CSS Variables based on state */
    #glass-navbar {
        /* Default: Scrolled State (Light Mode) */
        --nav-bg: rgba(255, 255, 255, 0.75);
        --nav-border: rgba(0, 0, 0, 0.08);
        --nav-text: #1f2937;
        --nav-text-hover: #111827;
        --nav-active-bg: #d1fae5;
        --nav-active-text: #065f46;
        --nav-btn-bg: rgba(0, 0, 0, 0.05);
        --nav-btn-text: #1f2937;
        --nav-mobile-bg: rgba(255, 255, 255, 0.95);
        --nav-mobile-border: rgba(0, 0, 0, 0.1);
        --nav-blur: 16px;
        --nav-saturate: 1.4;
        --nav-highlight: inset 0 1px 0 rgba(255,255,255,0.1);
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }
    
    .dark #glass-navbar {
        /* Default: Scrolled State (Dark Mode) */
        --nav-bg: rgba(7, 19, 15, 0.65);
        --nav-border: rgba(255, 255, 255, 0.1);
        --nav-text: #e5e7eb;
        --nav-text-hover: #ffffff;
        --nav-active-bg: rgba(16, 185, 129, 0.2);
        --nav-active-text: #34d399;
        --nav-btn-bg: rgba(255, 255, 255, 0.1);
        --nav-btn-text: #ffffff;
        --nav-mobile-bg: rgba(5, 10, 7, 0.95);
        --nav-mobile-border: rgba(255, 255, 255, 0.1);
        --nav-highlight: inset 0 1px 0 rgba(255,255,255,0.05);
    }

    #glass-navbar[data-nav-state="over-hero"] {
        /* Over-Hero State */
        --nav-bg: rgba(30, 40, 35, 0.5); /* Slightly brighter tint */
        --nav-border: rgba(255, 255, 255, 0.18);
        --nav-text: rgba(255, 255, 255, 0.85);
        --nav-text-hover: #ffffff;
        --nav-active-bg: rgba(255, 255, 255, 0.2);
        --nav-active-text: #ffffff;
        --nav-btn-bg: rgba(255, 255, 255, 0.15);
        --nav-btn-text: #ffffff;
        --nav-blur: 10px; /* Toggle to 0px if jitter occurs */
        --nav-saturate: 1.2;
        --nav-highlight: inset 0 1px 0 rgba(255,255,255,0.12);
    }

    .navbar--hidden,
    #glass-navbar.navbar--hidden {
        transform: translateY(calc(-100% - 2rem));
    }

    @media (min-width: 1024px) {
        #glass-navbar.navbar--hidden {
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        #glass-navbar {
            transition: none !important;
        }
    }

    /* Core Styles */
    .nav-container {
        background-color: var(--nav-bg);
        border: 1px solid var(--nav-border);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), var(--nav-highlight);
        transition: background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease, box-shadow 0.25s ease, backdrop-filter 0.25s ease;
    }
    
    @supports (backdrop-filter: blur(16px)) {
        .nav-container {
            backdrop-filter: blur(var(--nav-blur)) saturate(var(--nav-saturate));
            -webkit-backdrop-filter: blur(var(--nav-blur)) saturate(var(--nav-saturate));
        }
    }
    @supports not (backdrop-filter: blur(16px)) {
        #glass-navbar { --nav-bg: #ffffff; }
        .dark #glass-navbar { --nav-bg: #050a07; }
        #glass-navbar[data-nav-state="over-hero"] { --nav-bg: #1e2823; }
    }

    /* Nav Links */
    .nav-link {
        color: var(--nav-text);
        transition: color 0.25s ease, background-color 0.25s ease;
    }
    .nav-link:hover, .nav-link:focus-visible {
        color: var(--nav-text-hover);
        outline: none;
    }
    .nav-link:focus-visible {
        box-shadow: 0 0 0 2px var(--nav-active-bg);
    }
    .nav-link.active {
        background-color: var(--nav-active-bg);
        color: var(--nav-active-text);
        font-weight: 500;
    }

    /* Buttons */
    .nav-icon-btn {
        color: var(--nav-text);
        background-color: transparent;
        border: 1px solid var(--nav-border);
        transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease;
    }
    .nav-icon-btn:hover, .nav-icon-btn:focus-visible {
        background-color: var(--nav-btn-bg);
        color: var(--nav-text-hover);
        outline: none;
    }
    .nav-icon-btn:focus-visible {
        box-shadow: 0 0 0 2px var(--nav-active-bg);
    }
    
    .nav-primary-btn {
        background-color: var(--nav-btn-bg);
        color: var(--nav-btn-text);
        transition: transform 0.1s ease, background-color 0.25s ease;
    }
    .nav-primary-btn:hover {
        opacity: 0.9;
    }
    .nav-primary-btn:active {
        transform: scale(0.96);
    }

    /* Mobile Menu */
    
    .nav-mobile-panel {
        background-color: var(--nav-mobile-bg);
        border: 1px solid var(--nav-mobile-border);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
        /* display: none handled by .hidden */
    }
    .nav-mobile-panel.menu-open {
        animation: fade-in-up 0.25s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @supports (backdrop-filter: blur(16px)) {
        .nav-mobile-panel {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px) saturate(1.5);
        }
        .dark .nav-mobile-panel {
            background-color: rgba(5, 10, 7, 0.85);
        }
    }

</style>

<header id="glass-navbar" data-nav-state="scrolled" class="fixed top-5 inset-x-0 z-50 px-4 md:px-6 w-full max-w-7xl mx-auto flex justify-center">
    
    <nav aria-label="Utama" class="nav-container w-full rounded-full flex items-center justify-between px-4 py-2 min-h-[56px] lg:px-6">
        
        <!-- LOGO -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 rounded-lg p-1 group" aria-label="Beranda">
            <div class="relative w-9 h-9 flex items-center justify-center">
                <img src="{{ asset('images/logonu.png') }}" alt="" class="w-9 h-9 object-contain relative z-10" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                <div class="absolute inset-0 bg-emerald-700 text-white rounded-full items-center justify-center text-[10px] font-bold font-serif shadow-inner hidden" style="display:none;">NU</div>
            </div>
            <div class="flex flex-col justify-center">
                <span class="font-serif text-[1.1rem] font-bold tracking-wide leading-none nav-link !bg-transparent !p-0 group-hover:text-emerald-500 transition-colors">NU Kalisari</span>
                <span class="hidden sm:block text-[0.6rem] uppercase tracking-[0.15em] opacity-80 leading-none mt-1 nav-link !bg-transparent !p-0">Ranting</span>
            </div>
        </a>

        <!-- DESKTOP LINKS -->
        <ul class="hidden lg:flex items-center gap-1 mx-auto">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link['href'] }}" 
                       class="nav-link px-4 py-2 rounded-full text-sm font-medium {{ $link['active'] ? 'active' : '' }}"
                       @if($link['active']) aria-current="page" @endif>
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- RIGHT SIDE: THEME & LOGIN/DASHBOARD & MOBILE TOGGLE -->
        <div class="flex items-center gap-2 shrink-0">
            <!-- Theme Toggle -->
            <button id="theme-toggle" type="button" aria-label="Ganti Tema" class="nav-icon-btn w-11 h-11 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            <!-- Dashboard/Login -->
            @auth
                <a href="{{ url('/dashboard') }}" class="nav-primary-btn hidden md:inline-flex px-4 py-2 rounded-full text-sm font-medium">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-primary-btn hidden md:inline-flex px-4 py-2 rounded-full text-sm font-medium">
                    Masuk
                </a>
            @endauth

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" type="button" aria-label="Buka menu" aria-expanded="false" class="nav-icon-btn w-11 h-11 rounded-full flex lg:hidden items-center justify-center">
                <svg id="icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </nav>

    <!-- MOBILE MENU PANEL -->
    <div id="mobile-menu" class="nav-mobile-panel hidden absolute top-[calc(100%+8px)] left-4 right-4 rounded-2xl p-2 lg:hidden ">
        <ul class="flex flex-col gap-1">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link['href'] }}" 
                       class="block px-4 py-3 min-h-[44px] rounded-xl text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors {{ $link['active'] ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : '' }}"
                       @if($link['active']) aria-current="page" @endif>
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
            
            <li><div class="h-px my-2 bg-slate-200 dark:bg-slate-700/50"></div></li>

            @auth
                <li>
                    <a href="{{ url('/dashboard') }}" class="block px-4 py-3 min-h-[44px] rounded-xl text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        Dashboard — {{ Auth::user()->name }}
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}" class="block px-4 py-3 min-h-[44px] rounded-xl text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        Masuk Admin
                    </a>
                </li>
            @endauth
        </ul>
    </div>
</header>



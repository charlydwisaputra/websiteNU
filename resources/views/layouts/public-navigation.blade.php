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
        [
            'href'    => route('anggota'),
            'label'   => 'Anggota',
            'active'  => request()->routeIs('anggota*'),
        ],
    ];
@endphp

<style>
    /* CSS Variables based on state */
    #glass-navbar {
        /* Default: Scrolled State (Light Mode) - 94% solid + blur */
        --nav-bg: rgba(255, 255, 255, 0.94);
        --nav-border: rgba(15, 23, 42, 0.08);
        --nav-text: #0f172a;
        --nav-text-hover: #047857;
        --nav-active-bg: #d1fae5;
        --nav-active-text: #047857;
        --nav-btn-bg: rgba(15, 23, 42, 0.05);
        --nav-btn-text: #0f172a;
        --nav-btn-primary-bg: #047857;
        --nav-btn-primary-text: #ffffff;
        --nav-mobile-bg: rgba(255, 255, 255, 0.98);
        --nav-mobile-border: rgba(15, 23, 42, 0.1);
        --nav-blur: 16px;
        --nav-saturate: 1.5;
        --nav-highlight: inset 0 1px 0 rgba(255,255,255,0.8);
        --nav-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }
    
    .dark #glass-navbar {
        /* Default: Scrolled State (Dark Mode) - 94% solid + blur */
        --nav-bg: rgba(5, 10, 7, 0.94);
        --nav-border: rgba(255, 255, 255, 0.12);
        --nav-text: #f8fafc;
        --nav-text-hover: #34d399;
        --nav-active-bg: rgba(16, 185, 129, 0.2);
        --nav-active-text: #34d399;
        --nav-btn-bg: rgba(255, 255, 255, 0.1);
        --nav-btn-text: #ffffff;
        --nav-btn-primary-bg: #059669;
        --nav-btn-primary-text: #ffffff;
        --nav-mobile-bg: rgba(5, 10, 7, 0.98);
        --nav-mobile-border: rgba(255, 255, 255, 0.12);
        --nav-highlight: inset 0 1px 0 rgba(255,255,255,0.06);
        --nav-shadow: 0 12px 28px -5px rgba(0, 0, 0, 0.6), 0 4px 8px -2px rgba(0, 0, 0, 0.4);
    }

    #glass-navbar[data-nav-state="over-hero"] {
        /* Over-Hero State (High-translucency dark glass over video/photo) */
        --nav-bg: rgba(15, 23, 19, 0.52);
        --nav-border: rgba(255, 255, 255, 0.2);
        --nav-text: rgba(255, 255, 255, 0.95);
        --nav-text-hover: #ffffff;
        --nav-active-bg: rgba(255, 255, 255, 0.22);
        --nav-active-text: #ffffff;
        --nav-btn-bg: rgba(255, 255, 255, 0.15);
        --nav-btn-text: #ffffff;
        --nav-btn-primary-bg: rgba(255, 255, 255, 0.25);
        --nav-btn-primary-text: #ffffff;
        --nav-mobile-bg: rgba(255, 255, 255, 0.98);
        --nav-mobile-border: rgba(15, 23, 42, 0.1);
        --nav-blur: 12px;
        --nav-saturate: 1.2;
        --nav-highlight: inset 0 1px 0 rgba(255,255,255,0.15);
        --nav-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }

    .dark #glass-navbar[data-nav-state="over-hero"] {
        --nav-mobile-bg: rgba(5, 10, 7, 0.98);
        --nav-mobile-border: rgba(255, 255, 255, 0.12);
    }

    /* Mobile: setelah digulir > 24px, latar navbar menjadi hampir solid (94%) dengan backdrop-blur */
    @media (max-width: 1023px) {
        #glass-navbar[data-scrolled-past="true"] {
            --nav-bg: rgba(255, 255, 255, 0.94);
            --nav-border: rgba(15, 23, 42, 0.1);
            --nav-text: #0f172a;
            --nav-text-hover: #047857;
            --nav-active-bg: #d1fae5;
            --nav-active-text: #047857;
            --nav-btn-bg: rgba(15, 23, 42, 0.05);
            --nav-btn-text: #0f172a;
            --nav-blur: 16px;
            --nav-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        }
        .dark #glass-navbar[data-scrolled-past="true"] {
            --nav-bg: rgba(5, 10, 7, 0.94);
            --nav-border: rgba(255, 255, 255, 0.12);
            --nav-text: #f8fafc;
            --nav-text-hover: #34d399;
            --nav-active-bg: rgba(16, 185, 129, 0.2);
            --nav-active-text: #34d399;
            --nav-btn-bg: rgba(255, 255, 255, 0.1);
            --nav-btn-text: #ffffff;
            --nav-blur: 16px;
            --nav-shadow: 0 12px 28px -5px rgba(0, 0, 0, 0.6), 0 4px 8px -2px rgba(0, 0, 0, 0.4);
        }
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
        box-shadow: var(--nav-shadow), var(--nav-highlight);
        transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                    border-color 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                    color 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                    box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                    backdrop-filter 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        font-weight: 600;
    }

    /* Buttons */
    .nav-icon-btn {
        color: var(--nav-text);
        background-color: var(--nav-btn-bg);
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
        background-color: var(--nav-btn-primary-bg);
        color: var(--nav-btn-primary-text);
        border: 1px solid var(--nav-border);
        transition: transform 0.1s ease, background-color 0.25s ease, opacity 0.2s ease;
    }
    .nav-primary-btn:hover {
        opacity: 0.92;
    }
    .nav-primary-btn:active {
        transform: scale(0.96);
    }

    /* Mobile Menu Panel — SOLID, tidak ada teks hero yang tembus */
    .nav-mobile-panel {
        background-color: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.07);
        box-shadow: 0 20px 40px -8px rgba(0, 0, 0, 0.28), 0 4px 12px -2px rgba(0, 0, 0, 0.10);
    }
    .dark .nav-mobile-panel {
        background-color: #050A07;
        border-color: rgba(255, 255, 255, 0.09);
    }
    .nav-mobile-panel.menu-open {
        animation: fade-in-up 0.22s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Backdrop overlay — mulai tepat di bawah navbar agar header tidak meredup */
    #mobile-menu-backdrop {
        display: none;
        position: fixed;
        /* top diset via JS menjadi tinggi navbar aktual */
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 49;  /* di bawah header (z-50) dan panel (z-51), di atas konten halaman */
        background: transparent;
        transition: background 0.22s ease;
        pointer-events: none;
    }
    #mobile-menu-backdrop.active {
        display: block;
        background: rgba(0, 0, 0, 0.45);
        pointer-events: auto;
    }

    /* Masuk/Dashboard CTA at bottom of panel */
    .nav-menu-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 48px;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.9375rem;
        font-weight: 600;
        text-align: center;
        transition: background-color 0.2s ease, opacity 0.15s ease;
        cursor: pointer;
    }
    .nav-menu-cta-outline {
        background: transparent;
        border: 2px solid #047857;
        color: #047857;
    }
    .dark .nav-menu-cta-outline {
        border-color: #34d399;
        color: #34d399;
    }
    .nav-menu-cta-outline:hover {
        background: rgba(4, 120, 87, 0.08);
    }
    .dark .nav-menu-cta-outline:hover {
        background: rgba(52, 211, 153, 0.1);
    }
</style>

<header id="glass-navbar" data-nav-state="{{ $isHome ? 'over-hero' : 'scrolled' }}" class="fixed top-5 inset-x-0 z-50 px-4 md:px-6 w-full max-w-7xl mx-auto flex justify-center">
    
    <nav aria-label="Utama" class="nav-container w-full rounded-full flex items-center justify-between px-4 py-2 min-h-[56px] lg:px-6">
        
        <!-- LOGO -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 rounded-lg p-1 group min-h-[44px]" aria-label="Beranda NU Kalisari">
            <div class="relative w-9 h-9 rounded-full bg-white/95 dark:bg-white/90 p-1 flex items-center justify-center shrink-0 shadow-xs border border-emerald-500/20">
                <img src="{{ asset('images/brand/logo-nu.png') }}" alt="Logo Nahdlatul Ulama Kalisari" width="28" height="28" class="w-7 h-7 object-contain relative z-10" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                <div class="absolute inset-0 bg-emerald-700 text-white rounded-full items-center justify-center text-[10px] font-bold font-serif shadow-inner hidden" style="display:none;">NU</div>
            </div>
            <div class="flex flex-col justify-center">
                <span class="font-serif text-[1.1rem] font-bold tracking-wide leading-none transition-colors group-hover:text-emerald-500" style="color: var(--nav-text)">NU Kalisari</span>
                <span class="hidden sm:block text-[0.6rem] uppercase tracking-[0.15em] opacity-80 leading-none mt-1 transition-colors" style="color: var(--nav-text)">Ranting</span>
            </div>
        </a>

        <!-- DESKTOP LINKS -->
        <ul class="hidden lg:flex items-center gap-1 mx-auto">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link['href'] }}" 
                       class="nav-link px-3.5 py-2 min-h-[40px] inline-flex items-center rounded-full text-sm font-medium {{ $link['active'] ? 'active' : '' }}"
                       @if($link['active']) aria-current="page" @endif>
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- RIGHT SIDE: THEME & LOGIN/DASHBOARD & MOBILE TOGGLE -->
        <div class="flex items-center gap-2 shrink-0">
            <!-- Theme Toggle Button (44px min area) -->
            <button id="theme-toggle" type="button" aria-label="Ganti Tema" class="nav-icon-btn w-11 h-11 min-w-[44px] min-h-[44px] rounded-full flex items-center justify-center cursor-pointer">
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            <!-- Dashboard/Login -->
            @auth
                <a href="{{ url('/dashboard') }}" class="nav-primary-btn hidden md:inline-flex px-4 py-2 min-h-[44px] items-center rounded-full text-sm font-semibold shadow-sm">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-primary-btn hidden md:inline-flex px-4 py-2 min-h-[44px] items-center rounded-full text-sm font-semibold shadow-sm">
                    Masuk
                </a>
            @endauth

            <!-- Mobile Menu Button (44px min area) -->
            <button id="mobile-menu-btn" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu" class="nav-icon-btn w-11 h-11 min-w-[44px] min-h-[44px] rounded-full flex lg:hidden items-center justify-center cursor-pointer">
                <svg id="icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </nav>

    <!-- BACKDROP OVERLAY (closes menu on tap) -->
    <div id="mobile-menu-backdrop" aria-hidden="true"></div>

    <!-- MOBILE MENU PANEL -->
    <div id="mobile-menu" class="nav-mobile-panel hidden absolute top-[calc(100%+8px)] left-4 right-4 max-h-[calc(100dvh-100px)] overflow-y-auto rounded-3xl p-3 sm:p-4 lg:hidden" style="z-index: 51;">

        {{-- Panel header: label only, no close button --}}
        <div class="px-3 pt-1 pb-2 mb-1 border-b border-black/5 dark:border-white/5">
            <span class="text-[10px] uppercase font-bold tracking-[0.18em] text-slate-400 dark:text-slate-500" aria-hidden="true">Menu Navigasi</span>
        </div>

        {{-- Navigation links --}}
        <ul class="flex flex-col gap-0.5">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link['href'] }}"
                       class="flex items-center justify-between px-4 py-3 min-h-[44px] rounded-xl text-base font-medium transition-colors {{ $link['active'] ? 'bg-emerald-600 text-white font-semibold shadow-sm' : 'text-slate-800 dark:text-slate-100 hover:bg-black/5 dark:hover:bg-white/5' }}"
                       @if($link['active']) aria-current="page" @endif>
                        <span>{{ $link['label'] }}</span>
                        @if($link['active'])
                            {{-- Titik penanda aktif: selalu putih — bg-white paksa di kedua mode --}}
                            <span style="width:8px;height:8px;border-radius:9999px;background:#ffffff;display:inline-block;flex-shrink:0" aria-hidden="true"></span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Divider --}}
        <div class="h-px my-2 bg-black/8 dark:bg-white/8"></div>

        {{-- CTA: Masuk / Dashboard — tombol penuh di dasar panel --}}
        @auth
            <a href="{{ url('/dashboard') }}"
               class="nav-menu-cta {{ request()->is('dashboard*') ? 'bg-emerald-600 text-white' : 'nav-menu-cta-outline' }}">
                Dashboard
                <span class="ml-2 text-sm font-normal opacity-70">{{ Auth::user()->name }}</span>
            </a>
        @else
            <a href="{{ route('login') }}"
               class="nav-menu-cta nav-menu-cta-outline"
               @if(request()->routeIs('login')) aria-current="page" @endif>
                Masuk
            </a>
        @endauth
    </div>
</header>

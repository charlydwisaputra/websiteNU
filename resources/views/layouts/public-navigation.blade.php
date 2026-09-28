@php
    $isHome          = request()->routeIs('home');
    $homeUrl         = route('home');
    $homeSectionUrl  = $isHome ? '#hero-section'   : $homeUrl;
    $orgSectionUrl   = $isHome ? '#produk-section' : $homeUrl . '#produk-section';

    $links = [
        ['href' => $homeSectionUrl,          'label' => 'Beranda',    'active' => $isHome && !request()->routeIs('anggota','galeri') && !request()->routeIs('berita*'), 'smooth' => $isHome],
        ['href' => $orgSectionUrl,            'label' => 'Organisasi', 'active' => false,  'smooth' => $isHome],
        ['href' => route('anggota'),          'label' => 'Anggota',   'active' => request()->routeIs('anggota'),  'smooth' => false],
        ['href' => route('galeri'),           'label' => 'Dokumentasi','active' => request()->routeIs('galeri'),   'smooth' => false],
        ['href' => route('berita.index'),     'label' => 'Berita',    'active' => request()->routeIs('berita*'), 'smooth' => false],
    ];
@endphp

{{-- ============================================================
     FLOATING GLASS NAVBAR — Digdaya-Inspired + iPhone Glass
     Single top navbar · No bottom dock · Glassmorphism premium
     ============================================================ --}}
<header id="glass-navbar"
        class="fixed left-0 right-0 z-50 
               top-3 sm:top-4 md:top-6 
               px-3 sm:px-4 md:px-6
               transition-all duration-300 ease-out">
    
    {{-- — Glass Container — --}}
    <div class="glass-navbar-container relative mx-auto w-full max-w-[1200px]
                h-[54px] sm:h-[60px] md:h-[68px] rounded-full 
                flex items-center justify-between 
                px-3 sm:px-5 md:px-6
                transition-all duration-300">


        {{-- — Content Wrapper — --}}
        <div class="relative z-10 flex w-full items-center justify-between">
            
            {{-- — LEFT: Logo Brand — --}}
            <a href="{{ $homeUrl }}" class="flex items-center gap-2 sm:gap-2.5 shrink-0 group">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-b from-[#087A4B] to-[#065C39]
                            flex items-center justify-center text-white 
                            font-black text-xs sm:text-sm md:text-base
                            shadow-sm
                            transition-transform duration-300 group-hover:scale-[1.03]">
                    NU
                </div>
                <span class="font-bold text-sm sm:text-[15px] md:text-[17px] tracking-tight leading-none flex flex-col justify-center" style="color: var(--color-text-primary);">
                    NU CIKEMBULAN
                </span>
            </a>

            {{-- — CENTER: Navigation Menu (Dock Style) — --}}
            <nav class="hidden lg:flex items-center gap-1 rounded-full p-1" style="background: var(--nav-dock-bg); border: 1px solid var(--nav-dock-border);">
                @foreach ($links as $link)
                    @php
                        $hash = '';
                        if (str_contains($link['href'], '#')) {
                            $hash = '#' . explode('#', $link['href'])[1];
                        }
                    @endphp
                    <a href="{{ $link['href'] }}"
                       data-nav-link="{{ $hash }}"
                       class="nav-link-item relative px-4 py-1.5 text-[14px] rounded-full font-medium 
                              transition-all duration-300 ease-out
                              {{ $link['active']
                                  ? 'active-nav-link'
                                  : '' }}"
                       style="{{ $link['active'] 
                           ? 'background:var(--nav-active-bg);box-shadow:var(--nav-active-shadow);color:var(--color-text-primary);' 
                           : 'color:var(--color-text-muted);' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- — RIGHT: Theme Toggle + Login/Dashboard — --}}
            <div class="hidden lg:flex items-center gap-2.5">
                {{-- Theme Toggle --}}
                <button id="theme-toggle" type="button" class="theme-toggle-btn" aria-label="Toggle dark mode">
                    <svg class="icon-sun" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 2.22a1 1 0 011.415 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zm-3.78 4.22a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM10 18a1 1 0 01-1-1v-1a1 1 0 112 0v1a1 1 0 01-1 1zm-4.22-3.78a1 1 0 01-1.414 0l-.707-.707a1 1 0 111.414-1.414l.707.707a1 1 0 010 1.414zM4 10a1 1 0 01-1-1H2a1 1 0 110 2h1a1 1 0 011-1zm1.78-4.22a1 1 0 010-1.414l.707-.707a1 1 0 111.414 1.414l-.707.707a1 1 0 01-1.414 0zM10 7a3 3 0 100 6 3 3 0 000-6z"></path></svg>
                    <svg class="icon-moon" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                </button>

                @if (Illuminate\Support\Facades\Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="relative bg-gray-900 text-white text-[13px] font-medium 
                                  px-5 py-2 rounded-full 
                                  shadow-[0_4px_12px_rgba(0,0,0,0.1)] 
                                  transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_6px_16px_rgba(0,0,0,0.15)]
                                  flex items-center gap-2">
                            <span class="relative z-10 w-5 h-5 rounded-full bg-white/20 
                                         flex items-center justify-center text-[10px] font-bold leading-none">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <div class="relative" id="user-dropdown-wrapper">
                            <button id="user-dropdown-btn" class="flex items-center justify-center w-9 h-9 rounded-full transition focus:outline-none" style="background: var(--nav-dock-bg); color: var(--color-text-secondary);">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                            </button>
                            <div id="user-dropdown-menu" class="hidden absolute right-0 mt-2 w-64 rounded-xl shadow-lg py-3 z-50" style="background: var(--color-surface-solid); border: 1px solid var(--color-border);">
                                <p class="px-4 text-sm mb-2" style="color: var(--color-text-secondary);">Apakah anda ingin login sebagai admin?</p>
                                <div class="px-4">
                                    <a href="{{ route('login') }}" class="block w-full text-center text-sm font-medium py-2 rounded-lg transition" style="background: var(--color-accent); color: #fff;">Masuk sebagai Admin</a>
                                </div>
                            </div>
                        </div>
                    @endauth
                @endif
            </div>

            {{-- — Hamburger Mobile — --}}
            <div class="lg:hidden flex items-center gap-2">
                {{-- Mobile Theme Toggle --}}
                <button id="theme-toggle-mobile" type="button" class="theme-toggle-btn" style="width:36px;height:36px;" aria-label="Toggle dark mode">
                    <svg class="icon-sun" fill="currentColor" viewBox="0 0 20 20" style="width:16px;height:16px;"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 2.22a1 1 0 011.415 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zm-3.78 4.22a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM10 18a1 1 0 01-1-1v-1a1 1 0 112 0v1a1 1 0 01-1 1zm-4.22-3.78a1 1 0 01-1.414 0l-.707-.707a1 1 0 111.414-1.414l.707.707a1 1 0 010 1.414zM4 10a1 1 0 01-1-1H2a1 1 0 110 2h1a1 1 0 011-1zm1.78-4.22a1 1 0 010-1.414l.707-.707a1 1 0 111.414 1.414l-.707.707a1 1 0 01-1.414 0zM10 7a3 3 0 100 6 3 3 0 000-6z"></path></svg>
                    <svg class="icon-moon" fill="currentColor" viewBox="0 0 20 20" style="width:16px;height:16px;"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                </button>

                <button id="mobile-menu-btn"
                        type="button"
                        aria-label="Buka menu"
                        aria-expanded="false"
                        class="flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-full 
                               hover:bg-black/5 
                               transition-all duration-200 z-20 focus:outline-none"
                        style="color: var(--color-text-secondary);">
                    <svg id="icon-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- — Mobile Menu Dropdown — --}}
    <div id="mobile-menu"
         class="lg:hidden hidden absolute top-[68px] sm:top-[76px] left-3 sm:left-4 right-3 sm:right-4 overflow-hidden transition-all duration-300
                backdrop-blur-2xl backdrop-saturate-150
                rounded-[1.5rem]"
         style="background: var(--mobile-menu-bg); border: 1px solid var(--glass-border); box-shadow: 0 12px 40px rgba(0,0,0,0.12);">
        <ul class="px-3 py-3 flex flex-col gap-1">
            @foreach ($links as $link)
            <li>
                <a href="{{ $link['href'] }}"
                   class="block px-4 py-2.5 rounded-2xl text-[14px] font-medium transition-all duration-150"
                   style="{{ $link['active']
                       ? 'background:var(--btn-dark-bg);color:var(--btn-dark-text);'
                       : 'color:var(--color-text-secondary);' }}">
                    {{ $link['label'] }}
                </a>
            </li>
            @endforeach

            <li class="pt-2 mt-1" style="border-top: 1px solid var(--color-border);">
                @if (Illuminate\Support\Facades\Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="block px-4 py-3 rounded-2xl text-[14px] font-medium transition text-center"
                           style="color: var(--color-text-primary); border: 1px solid var(--color-border);">
                            Dashboard — {{ Auth::user()->name }}
                        </a>
                    @else
                        <div class="px-4 py-3 rounded-2xl" style="border: 1px solid var(--color-border);">
                            <p class="text-sm mb-2 text-center" style="color: var(--color-text-secondary);">Apakah anda ingin login sebagai admin?</p>
                            <a href="{{ route('login') }}"
                               class="block w-full px-4 py-2 rounded-xl text-[14px] font-medium text-white transition text-center shadow-md"
                               style="background: var(--color-accent);">
                                Masuk sebagai admin
                            </a>
                        </div>
                    @endauth
                @endif
            </li>
        </ul>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle mobile menu
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('icon-open');
        const iconClose = document.getElementById('icon-close');
        
        if (btn && menu) {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = menu.classList.contains('hidden');
                if (isHidden) {
                    menu.classList.remove('hidden');
                    menu.classList.add('menu-open');
                    btn.setAttribute('aria-expanded', 'true');
                    iconOpen?.classList.add('hidden');
                    iconClose?.classList.remove('hidden');
                } else {
                    menu.classList.add('hidden');
                    menu.classList.remove('menu-open');
                    btn.setAttribute('aria-expanded', 'false');
                    iconOpen?.classList.remove('hidden');
                    iconClose?.classList.add('hidden');
                }
            });

            // Close when clicking links
            menu.querySelectorAll('a').forEach(a => {
                a.addEventListener('click', () => {
                    menu.classList.add('hidden');
                    menu.classList.remove('menu-open');
                    btn.setAttribute('aria-expanded', 'false');
                    iconOpen?.classList.remove('hidden');
                    iconClose?.classList.add('hidden');
                });
            });

            // Close when clicking outside
            document.addEventListener('click', (e) => {
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                    menu.classList.remove('menu-open');
                    btn.setAttribute('aria-expanded', 'false');
                    iconOpen?.classList.remove('hidden');
                    iconClose?.classList.add('hidden');
                }
            });
        }

        const navLinks = document.querySelectorAll('.nav-link-item');

        function setActive(hash) {
            if (!hash) return;
            navLinks.forEach(link => {
                const lHash = link.getAttribute('data-nav-link');
                if (lHash === hash) {
                    link.classList.add('active-nav-link');
                    link.style.background = 'var(--nav-active-bg)';
                    link.style.boxShadow = 'var(--nav-active-shadow)';
                    link.style.color = 'var(--color-text-primary)';
                } else if (lHash && lHash.startsWith('#')) {
                    link.classList.remove('active-nav-link');
                    link.style.background = '';
                    link.style.boxShadow = '';
                    link.style.color = 'var(--color-text-muted)';
                }
            });
        }

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const hash = this.getAttribute('data-nav-link');
                if (hash && document.querySelector(hash)) {
                    setActive(hash);
                }
            });
        });

        // User Dropdown Logic
        const userDropdownBtn = document.getElementById('user-dropdown-btn');
        const userDropdownMenu = document.getElementById('user-dropdown-menu');
        if (userDropdownBtn && userDropdownMenu) {
            userDropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdownMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', (e) => {
                if (!userDropdownBtn.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                    userDropdownMenu.classList.add('hidden');
                }
            });
        }

        // Theme Toggle Logic
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        document.getElementById('theme-toggle')?.addEventListener('click', toggleTheme);
        document.getElementById('theme-toggle-mobile')?.addEventListener('click', toggleTheme);
    });
</script>
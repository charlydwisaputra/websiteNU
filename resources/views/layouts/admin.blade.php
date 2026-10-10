<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Workspace — NU Kalisari</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .rail-tooltip {
            visibility: hidden; opacity: 0; transform: translateX(8px);
            transition: all 200ms ease;
        }
        .rail-item:hover .rail-tooltip {
            visibility: visible; opacity: 1; transform: translateX(0);
        }
    </style>
    @include('components.theme-script')
</head>

<body class="font-sans antialiased min-h-screen overflow-x-hidden"
      style="background:var(--bg-base);color:var(--text-primary)"
      x-data="{ mobileMenuOpen: false }">

    {{-- ─── DESKTOP RAIL (left side) ─── --}}
    <aside class="hidden md:flex flex-col items-center fixed left-5 top-5 bottom-5 w-[72px] z-40 glass-panel rounded-[24px] py-5">

        {{-- Logo --}}
        <a href="{{ route('home') }}"
           class="w-11 h-11 rounded-2xl flex items-center justify-center text-white font-bold text-sm shadow-sm mb-6 transition-all hover:scale-105"
           style="background:var(--accent)">NU</a>

        {{-- Nav links --}}
        <nav class="flex-1 flex flex-col items-center gap-1 w-full px-3">
            @php
            $railLinks = [
                ['route'=>'admin.dashboard', 'match'=>'admin.dashboard', 'label'=>'Dashboard',   'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route'=>'admin.berita.index',  'match'=>'admin.berita.*',  'label'=>'Berita',   'icon'=>'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                ['route'=>'admin.anggota.index', 'match'=>'admin.anggota.*', 'label'=>'Anggota', 'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['route'=>'admin.galeri.index',  'match'=>'admin.galeri.*',  'label'=>'Galeri',  'icon'=>'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ];
            @endphp

            @foreach($railLinks as $i => $rl)
                @if($i === 1 && auth()->user()?->role === 'admin')
                <div class="w-6 h-px my-1" style="background:var(--border-light)"></div>
                @endif
                @if($i === 0 || auth()->user()?->role === 'admin')
                <a href="{{ route($rl['route']) }}"
                   class="rail-item relative group w-full flex items-center justify-center p-3 rounded-2xl transition-all"
                   style="{{ request()->routeIs($rl['match'])
                       ? 'background:var(--accent-subtle);color:var(--accent)'
                       : 'color:var(--text-muted)' }}"
                   onmouseover="{{ request()->routeIs($rl['match']) ? '' : "this.style.background='var(--bg-surface-elevated)';this.style.color='var(--text-primary)'" }}"
                   onmouseout="{{ request()->routeIs($rl['match']) ? '' : "this.style.background='';this.style.color='var(--text-muted)'" }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $rl['icon'] }}"/>
                    </svg>
                    <div class="rail-tooltip absolute left-full ml-3 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap z-50 text-white"
                         style="background:var(--black-iris);box-shadow:var(--shadow-md)">
                        {{ $rl['label'] }}
                    </div>
                </a>
                @endif
            @endforeach
        </nav>

        {{-- Bottom: settings + logout --}}
        <div class="flex flex-col items-center gap-1 px-3 w-full">
            <a href="#profile-settings"
               onclick="document.getElementById('profile-settings')?.scrollIntoView({behavior:'smooth'})"
               class="rail-item relative group w-full flex items-center justify-center p-3 rounded-2xl transition-all"
               style="color:var(--text-muted)"
               onmouseover="this.style.background='var(--bg-surface-elevated)';this.style.color='var(--text-primary)'"
               onmouseout="this.style.background='';this.style.color='var(--text-muted)'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <div class="rail-tooltip absolute left-full ml-3 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap z-50 text-white"
                     style="background:var(--black-iris)">Pengaturan</div>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                        class="rail-item relative group w-full flex items-center justify-center p-3 rounded-2xl transition-all"
                        style="color:#f87171"
                        onmouseover="this.style.background='rgba(239,68,68,0.08)'"
                        onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <div class="rail-tooltip absolute left-full ml-3 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap z-50 text-white"
                         style="background:#dc2626">Logout</div>
                </button>
            </form>
        </div>
    </aside>

    {{-- ─── MOBILE SIDEBAR ─── --}}
    <div x-show="mobileMenuOpen" class="md:hidden fixed inset-0 z-50 flex" x-cloak>
        <div x-show="mobileMenuOpen" x-transition.opacity
             class="fixed inset-0 backdrop-blur-sm" style="background:rgba(0,0,0,0.4)"
             @click="mobileMenuOpen=false"></div>
        <aside x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
               class="relative flex-1 flex flex-col max-w-[280px] w-full px-5 py-8 shadow-2xl"
               style="background:var(--bg-surface);border-right:1px solid var(--border-light)">
            <div class="flex items-center justify-between mb-8">
                <span class="font-serif font-bold text-lg" style="color:var(--text-primary)">NU Kalisari</span>
                <button @click="mobileMenuOpen=false"
                        class="p-2 rounded-full transition-all" style="color:var(--text-muted)"
                        onmouseover="this.style.background='var(--bg-surface-elevated)'"
                        onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <nav class="flex flex-col gap-1 flex-grow">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
                   style="{{ request()->routeIs('admin.dashboard') ? 'background:var(--accent-subtle);color:var(--accent)' : 'color:var(--text-secondary)' }}">
                    Dashboard
                </a>
                @if(auth()->user()?->role === 'admin')
                <div class="px-4 pt-4 pb-1 text-[10px] font-bold uppercase tracking-widest" style="color:var(--text-muted)">
                    Data Master
                </div>
                <a href="{{ route('admin.berita.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
                   style="{{ request()->routeIs('admin.berita.*') ? 'background:var(--accent-subtle);color:var(--accent)' : 'color:var(--text-secondary)' }}">
                    Data Berita
                </a>
                <a href="{{ route('admin.anggota.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
                   style="{{ request()->routeIs('admin.anggota.*') ? 'background:var(--accent-subtle);color:var(--accent)' : 'color:var(--text-secondary)' }}">
                    Data Anggota
                </a>
                <a href="{{ route('admin.galeri.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
                   style="{{ request()->routeIs('admin.galeri.*') ? 'background:var(--accent-subtle);color:var(--accent)' : 'color:var(--text-secondary)' }}">
                    Data Galeri
                </a>
                @endif
            </nav>
            <div class="pt-5" style="border-top:1px solid var(--border-light)">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold transition-all"
                            style="color:#ef4444;background:rgba(239,68,68,0.07)"
                            onmouseover="this.style.background='rgba(239,68,68,0.12)'"
                            onmouseout="this.style.background='rgba(239,68,68,0.07)'">
                        Logout
                    </button>
                </form>
            </div>
        </aside>
    </div>

    {{-- ─── MAIN CONTENT ─── --}}
    <div class="md:ml-[100px] min-h-screen flex flex-col transition-all">

        {{-- Top Bar --}}
        <header class="h-[68px] mt-5 mx-4 md:mx-6 glass-panel rounded-2xl px-5 flex items-center justify-between sticky top-5 z-30">
            <div class="flex items-center gap-3">
                {{-- Hamburger --}}
                <button @click="mobileMenuOpen=true"
                        class="md:hidden p-2 rounded-xl transition-all"
                        style="color:var(--text-secondary);background:var(--bg-surface-elevated)"
                        onmouseover="this.style.color='var(--text-primary)'"
                        onmouseout="this.style.color='var(--text-secondary)'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <p class="font-serif font-bold text-base leading-none" style="color:var(--text-primary)">Workspace</p>
                    <p class="text-[10px] font-semibold uppercase tracking-widest mt-0.5" style="color:var(--text-muted)">NU Kalisari Digital</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{-- Theme toggle --}}
                <button type="button" onclick="toggleTheme()" aria-label="Ganti tema"
                        class="w-9 h-9 rounded-full flex items-center justify-center transition-all cursor-pointer border"
                        style="color:var(--text-muted);border-color:var(--border-light);background:var(--bg-surface)"
                        onmouseover="this.style.color='var(--accent)'"
                        onmouseout="this.style.color='var(--text-muted)'">
                    <svg class="icon-sun w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg class="icon-moon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                {{-- View site --}}
                <a href="{{ route('home') }}"
                   class="hidden sm:flex w-9 h-9 rounded-full items-center justify-center transition-all border"
                   style="color:var(--text-muted);border-color:var(--border-light);background:var(--bg-surface)"
                   onmouseover="this.style.color='var(--accent)'"
                   onmouseout="this.style.color='var(--text-muted)'"
                   title="Lihat Website">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>

                <div class="w-px h-5 hidden sm:block mx-1" style="background:var(--border-light)"></div>

                {{-- User pill --}}
                <a href="#profile-settings"
                   onclick="document.getElementById('profile-settings')?.scrollIntoView({behavior:'smooth'})"
                   class="flex items-center gap-2 pl-1.5 pr-3 py-1.5 rounded-full border transition-all"
                   style="background:var(--bg-surface);border-color:var(--border-light)"
                   onmouseover="this.style.borderColor='var(--accent)'"
                   onmouseout="this.style.borderColor='var(--border-light)'">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold overflow-hidden"
                         style="background:var(--accent)">
                        @if(auth()->user()?->profilAnggota?->photo)
                            <img src="{{ asset('storage/'.auth()->user()->profilAnggota->photo) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <span class="text-xs font-semibold hidden sm:block" style="color:var(--text-primary)">
                        {{ explode(' ', auth()->user()->name ?? 'User')[0] }}
                    </span>
                </a>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 px-4 md:px-6 py-8">
            @yield('content')
        </main>
    </div>
</body>
</html>

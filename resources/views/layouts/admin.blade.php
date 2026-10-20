<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
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
        @media (max-width: 767px) {
            .admin-main-content {
                padding-bottom: calc(5.25rem + env(safe-area-inset-bottom, 0px)) !important;
            }
        }
    </style>
    @include('components.theme-script')
</head>

<body class="font-sans antialiased min-h-[100dvh] overflow-x-hidden"
      style="background:var(--bg-base);color:var(--text-primary)"
      x-data="{ moreSheetOpen: false }"
      @keydown.window.escape="moreSheetOpen = false">

    @php
        $navMenu = \App\Helpers\AdminNavHelper::getMenu();
        $navFooter = \App\Helpers\AdminNavHelper::getFooter();
        $bottomBar = \App\Helpers\AdminNavHelper::getBottomBar();
    @endphp

    {{-- ─── DESKTOP RAIL (left side) ─── --}}
    <aside class="hidden md:flex flex-col items-center fixed left-5 top-5 bottom-5 w-[72px] z-40 glass-panel rounded-[24px] py-5 shadow-sm"
           aria-label="Sidebar Navigasi Desktop">

        {{-- Logo --}}
        <a href="{{ route('home') }}"
           class="w-11 h-11 rounded-2xl flex items-center justify-center p-1.5 shadow-sm mb-4 transition-all hover:scale-105"
           style="background:var(--bg-card); border: 1px solid var(--border-light);"
           title="Ke Beranda Website NU Kalisari"
           aria-label="Ke Beranda Website NU Kalisari">
            <img src="{{ asset('images/brand/logo-nu.png') }}" alt="Logo NU" width="32" height="32" class="w-8 h-8 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
            <div class="w-full h-full rounded-xl items-center justify-center text-white font-bold text-xs hidden" style="background:var(--accent); display:none;">NU</div>
        </a>

        {{-- Nav links --}}
        <nav class="flex-1 flex flex-col items-center gap-1 w-full px-3 overflow-y-auto overflow-x-hidden scrollbar-none"
             aria-label="Menu Utama Admin">
            @foreach($navMenu as $i => $rl)
                @if($i === 1 && auth()->user()?->role === 'admin')
                <div class="w-6 h-px my-1.5 shrink-0" style="background:var(--border-light)"></div>
                @endif
                @if($i === 0 || auth()->user()?->role === 'admin')
                @php
                    $url = \App\Helpers\AdminNavHelper::resolveUrl($rl);
                    $isActive = \App\Helpers\AdminNavHelper::isActive($rl);
                @endphp
                <a href="{{ $url }}"
                   class="rail-item relative group w-full flex items-center justify-center p-3 rounded-2xl transition-all min-h-[44px] min-w-[44px]"
                   style="{{ $isActive
                       ? 'background:var(--accent-subtle);color:var(--accent)'
                       : 'color:var(--text-muted)' }}"
                   title="{{ $rl['title'] ?? $rl['label'] }}"
                   aria-label="{{ $rl['aria_label'] ?? $rl['label'] }}"
                   onmouseover="{{ $isActive ? '' : "this.style.background='var(--bg-surface-elevated)';this.style.color='var(--text-primary)'" }}"
                   onmouseout="{{ $isActive ? '' : "this.style.background='';this.style.color='var(--text-muted)'" }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $rl['icon'] }}"/>
                    </svg>
                    <div class="rail-tooltip absolute left-full ml-3 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap z-50 text-white pointer-events-none"
                         style="background:var(--black-iris);box-shadow:var(--shadow-md)">
                        {{ $rl['label'] }}
                    </div>
                </a>
                @endif
            @endforeach
        </nav>

        {{-- Bottom Desktop Rail: Profil Pengguna & Logout --}}
        <div class="flex flex-col items-center gap-1.5 px-3 w-full pt-3 mt-auto" style="border-top:1px solid var(--border-light)">
            @foreach($navFooter as $fl)
                @php
                    $isLogout = ($fl['key'] ?? '') === 'keluar';
                    $fUrl = \App\Helpers\AdminNavHelper::resolveUrl($fl);
                    $fActive = \App\Helpers\AdminNavHelper::isActive($fl);
                @endphp
                @if($isLogout)
                <form method="POST" action="{{ $fUrl }}" class="w-full">
                    @csrf
                    <button type="submit"
                            class="rail-item relative group w-full flex items-center justify-center p-3 rounded-2xl transition-all min-h-[44px] min-w-[44px]"
                            style="color:#f87171"
                            title="{{ $fl['title'] ?? 'Logout' }}"
                            aria-label="{{ $fl['aria_label'] ?? 'Logout dari Sesi Admin' }}"
                            onmouseover="this.style.background='rgba(239,68,68,0.08)'"
                            onmouseout="this.style.background=''">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $fl['icon'] }}"/>
                        </svg>
                        <div class="rail-tooltip absolute left-full ml-3 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap z-50 text-white pointer-events-none"
                             style="background:#dc2626">
                            {{ $fl['label'] }}
                        </div>
                    </button>
                </form>
                @else
                <a href="{{ $fUrl }}"
                   class="rail-item relative group w-full flex items-center justify-center p-3 rounded-2xl transition-all min-h-[44px] min-w-[44px]"
                   style="{{ $fActive ? 'background:var(--accent-subtle);color:var(--accent)' : 'color:var(--text-muted)' }}"
                   title="{{ $fl['title'] ?? 'Profil Pengguna' }}"
                   aria-label="{{ $fl['aria_label'] ?? 'Buka Profil Pengguna' }}"
                   onmouseover="{{ $fActive ? '' : "this.style.background='var(--bg-surface-elevated)';this.style.color='var(--text-primary)'" }}"
                   onmouseout="{{ $fActive ? '' : "this.style.background='';this.style.color='var(--text-muted)'" }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $fl['icon'] }}"/>
                    </svg>
                    <div class="rail-tooltip absolute left-full ml-3 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap z-50 text-white pointer-events-none"
                         style="background:var(--black-iris)">
                        {{ $fl['label'] }}
                    </div>
                </a>
                @endif
            @endforeach
        </div>
    </aside>

    {{-- ─── MOBILE BOTTOM SHEET (Menu Lainnya) ─── --}}
    <div x-show="moreSheetOpen"
         class="md:hidden fixed inset-0 z-50 flex flex-col justify-end"
         x-cloak
         role="dialog"
         aria-modal="true"
         aria-label="Menu Navigasi Lainnya">

        {{-- Backdrop / Overlay Gelap (Tutup saat disentuh) --}}
        <div x-show="moreSheetOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 backdrop-blur-sm"
             style="background:rgba(0,0,0,0.6)"
             @click="moreSheetOpen = false"
             aria-hidden="true"></div>

        {{-- Sheet Container (Naik dari Bawah) --}}
        <div x-show="moreSheetOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="relative w-full max-h-[85dvh] rounded-t-[28px] shadow-2xl flex flex-col overflow-hidden border-t"
             style="background:var(--bg-surface);border-color:var(--border-light)">

            {{-- Handle Bar & Header Sheet --}}
            <div class="px-5 pt-4 pb-3 border-b flex items-center justify-between shrink-0"
                 style="border-color:var(--border-light)">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl flex items-center justify-center text-white font-bold text-xs shadow-xs"
                          style="background:var(--accent)">NU</span>
                    <div>
                        <h3 class="font-serif font-bold text-base leading-tight" style="color:var(--text-primary)">Menu Lainnya</h3>
                        <p class="text-[10px] font-medium" style="color:var(--text-muted)">Akses modul & pengaturan lengkap</p>
                    </div>
                </div>
                <button @click="moreSheetOpen = false"
                        type="button"
                        class="w-9 h-9 rounded-full flex items-center justify-center transition-all min-h-[44px] min-w-[44px]"
                        style="color:var(--text-muted);background:var(--bg-surface-elevated)"
                        title="Tutup Menu"
                        aria-label="Tutup Menu Lainnya">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Sheet Menu Items Scrollable (Agenda, Badan Otonom, Pengurus Inti, Pengaturan, Profil) --}}
            <nav class="flex-1 overflow-y-auto p-4 space-y-1.5 overscroll-contain"
                 aria-label="Menu Tambahan Admin">
                <div class="px-2 pb-1 text-[10px] font-bold uppercase tracking-wider" style="color:var(--text-muted)">
                    Modul Tambahan
                </div>
                @foreach(\App\Helpers\AdminNavHelper::getSheetItems() as $sItem)
                    @php
                        $sUrl = \App\Helpers\AdminNavHelper::resolveUrl($sItem);
                        $sActive = \App\Helpers\AdminNavHelper::isActive($sItem);
                    @endphp
                    <a href="{{ $sUrl }}"
                       @click="moreSheetOpen = false"
                       class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold transition-all min-h-[44px]"
                       style="{{ $sActive
                           ? 'background:var(--accent-subtle);color:var(--accent)'
                           : 'color:var(--text-primary);background:var(--bg-surface-elevated)' }}"
                       title="{{ $sItem['title'] ?? $sItem['label'] }}"
                       aria-label="{{ $sItem['aria_label'] ?? $sItem['label'] }}">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                             style="{{ $sActive
                                 ? 'background:var(--accent);color:#ffffff'
                                 : 'background:var(--bg-surface);color:var(--text-muted);border:1px solid var(--border-light)' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sItem['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="truncate">{{ $sItem['label'] }}</span>
                        @if($sActive)
                        <span class="ml-auto w-2 h-2 rounded-full" style="background:var(--accent)"></span>
                        @endif
                    </a>
                @endforeach
                {{-- Navigasi Website Publik --}}
                <div class="pt-2 px-2 pb-1 text-[10px] font-bold uppercase tracking-wider" style="color:var(--text-muted)">
                    Website Publik
                </div>
                <a href="{{ route('home') }}"
                   class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold transition-all min-h-[44px]"
                   style="color:var(--text-primary);background:var(--bg-surface-elevated)"
                   title="Lihat Website"
                   aria-label="Lihat Website">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                         style="background:var(--bg-surface);color:var(--text-muted);border:1px solid var(--border-light)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </div>
                    <span class="truncate">Lihat Website</span>
                    <svg class="w-4 h-4 ml-auto" style="color:var(--text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </nav>

            {{-- Sheet Footer: Keluar (Merah / destruktif dipisah di bawah) --}}
            @php
                $logoutItem = \App\Helpers\AdminNavHelper::getLogoutItem();
            @endphp
            @if($logoutItem)
            <div class="px-4 py-3 border-t shrink-0"
                 style="background:var(--bg-surface);border-color:var(--border-light);padding-bottom:max(1rem,env(safe-area-inset-bottom))">
                <form method="POST" action="{{ \App\Helpers\AdminNavHelper::resolveUrl($logoutItem) }}">
                    @csrf
                    <button type="submit"
                            @click="moreSheetOpen = false"
                            class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-semibold transition-all min-h-[44px] touch-manipulation"
                            style="color:#ef4444;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25)"
                            title="Keluar dari Akun"
                            aria-label="Keluar dari Akun"
                            onmouseover="this.style.background='rgba(239,68,68,0.15)'"
                            onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $logoutItem['icon'] }}"/>
                        </svg>
                        <span>{{ $logoutItem['label'] }}</span>
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    {{-- ─── MAIN CONTENT ─── --}}
    <div class="md:ml-[100px] min-h-[100dvh] flex flex-col transition-all">

        {{-- Top Bar (Header HP: Judul tautan ke Dashboard, Dark mode toggle, Lihat Website, Avatar) --}}
        <header class="h-[52px] md:h-[64px] mt-2 md:mt-5 mx-3 md:mx-6 glass-panel rounded-xl md:rounded-2xl px-3 md:px-5 flex items-center justify-between sticky top-2 md:top-5 z-30 shadow-xs">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-2.5 min-h-[44px] py-1 group transition-opacity hover:opacity-90"
               title="Ke Dashboard Workspace"
               aria-label="Ke Dashboard Workspace">
                <div>
                    <p class="font-serif font-bold text-sm md:text-lg leading-tight group-hover:text-[var(--accent)] transition-colors" style="color:var(--text-primary)">Workspace</p>
                    <p class="text-[9px] md:text-[10px] font-semibold uppercase tracking-wider mt-0.5" style="color:var(--text-muted)">NU Kalisari Digital</p>
                </div>
            </a>

            <div class="flex items-center gap-1.5 sm:gap-2">
                {{-- Theme toggle --}}
                <button type="button"
                        onclick="toggleTheme()"
                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center transition-all cursor-pointer border min-h-[44px] min-w-[44px]"
                        style="color:var(--text-muted);border-color:var(--border-light);background:var(--bg-surface)"
                        title="Ganti Mode Gelap / Terang"
                        aria-label="Ganti Mode Tampilan (Gelap/Terang)"
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

                {{-- View site (Mobile & Desktop) --}}
                <a href="{{ route('home') }}"
                   class="flex w-9 h-9 sm:w-10 sm:h-10 rounded-full items-center justify-center transition-all border min-h-[44px] min-w-[44px]"
                   style="color:var(--text-muted);border-color:var(--border-light);background:var(--bg-surface)"
                   title="Lihat Website"
                   aria-label="Lihat Website"
                   onmouseover="this.style.color='var(--accent)'"
                   onmouseout="this.style.color='var(--text-muted)'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>

                <div class="w-px h-5 hidden sm:block mx-1" style="background:var(--border-light)"></div>

                {{-- User pill --}}
                <a href="{{ Route::has('admin.profil') ? route('admin.profil') : (Route::has('profile.edit') ? route('profile.edit') : '#profile-settings') }}"
                   class="flex items-center gap-2 pl-1.5 pr-2.5 sm:pr-3 py-1 sm:py-1.5 rounded-full border transition-all min-h-[44px]"
                   style="background:var(--bg-surface);border-color:var(--border-light)"
                   title="Profil Akun: {{ auth()->user()?->name ?? 'User' }}"
                   aria-label="Buka Pengaturan Profil Pengguna"
                   onmouseover="this.style.borderColor='var(--accent)'"
                   onmouseout="this.style.borderColor='var(--border-light)'">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold overflow-hidden shrink-0"
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

        {{-- Content dengan padding-bottom aman di HP agar konten tidak tertutup dock --}}
        <main class="admin-main-content flex-1 px-4 md:px-6 py-4 md:py-8">
            @yield('content')
        </main>
    </div>

    {{-- ─── MOBILE BOTTOM NAVIGATION BAR (Thumb-friendly untuk HP 360-430px) ─── --}}
    @unless(View::hasSection('hide_bottom_nav') || !empty($hideBottomNav))
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 border-t flex items-center justify-around px-1 py-1"
         style="background:var(--bg-overlay);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-color:var(--border-light);padding-bottom:max(0.5rem,env(safe-area-inset-bottom));box-shadow:var(--shadow-md)"
         aria-label="Navigasi Bawah Ponsel">
        @foreach($bottomBar as $bItem)
            @if(($bItem['action'] ?? '') === 'open-sheet' || ($bItem['key'] ?? '') === 'lainnya')
            @php
                $isMoreActive = !empty($bItem['is_active']) || \App\Helpers\AdminNavHelper::isSheetActive();
            @endphp
            <button @click="moreSheetOpen = true"
                    type="button"
                    class="flex-1 flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition-all min-h-[48px] min-w-[44px] touch-manipulation relative"
                    style="{{ $isMoreActive
                        ? 'color:var(--accent);background:var(--accent-subtle)'
                        : 'color:var(--text-muted)' }}"
                    title="{{ $bItem['title'] ?? $bItem['label'] }}"
                    aria-label="{{ $bItem['aria_label'] ?? $bItem['label'] }}{{ $isMoreActive ? ' (Aktif)' : '' }}"
                    @if($isMoreActive) aria-current="page" @endif>
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $bItem['icon'] }}"/>
                </svg>
                <span class="text-[10px] {{ $isMoreActive ? 'font-bold' : 'font-medium' }} tracking-tight mt-0.5 leading-none">{{ $bItem['label'] }}</span>
                @if($isMoreActive)
                <span class="w-1.5 h-1.5 rounded-full mt-0.5" style="background:var(--accent)"></span>
                @endif
            </button>
            @else
            @php
                $bUrl = \App\Helpers\AdminNavHelper::resolveUrl($bItem);
                $bActive = \App\Helpers\AdminNavHelper::isActive($bItem);
            @endphp
            <a href="{{ $bUrl }}"
               class="flex-1 flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition-all min-h-[48px] min-w-[44px] touch-manipulation relative"
               style="{{ $bActive
                   ? 'color:var(--accent);background:var(--accent-subtle)'
                   : 'color:var(--text-muted)' }}"
               title="{{ $bItem['title'] ?? $bItem['label'] }}"
               aria-label="{{ $bItem['aria_label'] ?? $bItem['label'] }}{{ $bActive ? ' (Halaman aktif)' : '' }}"
               @if($bActive) aria-current="page" @endif>
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $bItem['icon'] }}"/>
                </svg>
                <span class="text-[10px] {{ $bActive ? 'font-bold' : 'font-medium' }} tracking-tight mt-0.5 leading-none">{{ $bItem['label'] }}</span>
                @if($bActive)
                <span class="w-1.5 h-1.5 rounded-full mt-0.5" style="background:var(--accent)"></span>
                @endif
            </a>
            @endif
        @endforeach
    </nav>
    @endunless
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Workspace - NU CIKEMBULAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <style>
        [x-cloak] { display: none !important; }
        .rail-tooltip {
            visibility: hidden;
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .rail-item:hover .rail-tooltip {
            visibility: visible;
            opacity: 1;
            transform: translateX(0);
        }
    </style>
    @include('components.theme-script')
</head>

<body class="font-sans antialiased text-slate-800 min-h-screen relative overflow-x-hidden" x-data="{ mobileMenuOpen: false }">
    <!-- Ambient Background -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-5%] w-[40%] h-[40%] bg-emerald-400/10 blur-[100px] rounded-full"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[50%] h-[50%] bg-emerald-500/5 blur-[120px] rounded-full"></div>
    </div>

    <!-- FLOATING NAVIGATION RAIL (Desktop) -->
    <aside class="hidden md:flex flex-col items-center fixed left-6 top-6 bottom-6 w-20 glass-panel rounded-[28px] z-40 py-6">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="w-12 h-12 bg-gradient-to-br from-[#087A4B] to-emerald-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-emerald-500/20 mb-8 hover:scale-105 transition-transform">
            NU
        </a>

        <!-- Links -->
        <nav class="flex-1 flex flex-col items-center gap-3 w-full">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="rail-item relative group p-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-white shadow-sm text-[#087A4B]' : 'text-slate-400 hover:text-slate-700 hover:bg-white/50' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <div class="rail-tooltip absolute left-full ml-4 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-slate-900/90 backdrop-blur-md text-white text-xs font-bold rounded-xl whitespace-nowrap z-50">Dashboard</div>
            </a>

            @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="w-8 h-px bg-slate-200/50 my-1"></div>
            <!-- Berita -->
            <a href="{{ route('admin.berita.index') }}" class="rail-item relative group p-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.berita.*') ? 'bg-white shadow-sm text-[#087A4B]' : 'text-slate-400 hover:text-slate-700 hover:bg-white/50' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <div class="rail-tooltip absolute left-full ml-4 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-slate-900/90 backdrop-blur-md text-white text-xs font-bold rounded-xl whitespace-nowrap z-50">Data Berita</div>
            </a>
            <!-- Anggota -->
            <a href="{{ route('admin.anggota.index') }}" class="rail-item relative group p-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.anggota.*') ? 'bg-white shadow-sm text-[#087A4B]' : 'text-slate-400 hover:text-slate-700 hover:bg-white/50' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <div class="rail-tooltip absolute left-full ml-4 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-slate-900/90 backdrop-blur-md text-white text-xs font-bold rounded-xl whitespace-nowrap z-50">Data Anggota</div>
            </a>
            <!-- Galeri -->
            <a href="{{ route('admin.galeri.index') }}" class="rail-item relative group p-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.galeri.*') ? 'bg-white shadow-sm text-[#087A4B]' : 'text-slate-400 hover:text-slate-700 hover:bg-white/50' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <div class="rail-tooltip absolute left-full ml-4 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-slate-900/90 backdrop-blur-md text-white text-xs font-bold rounded-xl whitespace-nowrap z-50">Data Galeri</div>
            </a>
            @endif
        </nav>

        <!-- Pengaturan / Profile -->
        <a href="#profile-settings" onclick="document.getElementById('profile-settings').scrollIntoView({behavior: 'smooth'})" class="rail-item relative group p-3 rounded-2xl transition-all duration-300 text-slate-400 hover:text-slate-700 hover:bg-white/50 mb-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <div class="rail-tooltip absolute left-full ml-4 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-slate-900/90 backdrop-blur-md text-white text-xs font-bold rounded-xl whitespace-nowrap z-50">Pengaturan</div>
        </a>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="w-full flex justify-center">
            @csrf
            <button type="submit" class="rail-item relative group p-3 rounded-2xl transition-all duration-300 text-red-400 hover:text-red-600 hover:bg-red-50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <div class="rail-tooltip absolute left-full ml-4 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-xl whitespace-nowrap z-50">Logout</div>
            </button>
        </form>
    </aside>

    <!-- MOBILE SIDEBAR (Slide-in) -->
    <div x-show="mobileMenuOpen" class="md:hidden fixed inset-0 z-50 flex" x-cloak>
        <div x-show="mobileMenuOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>
        <aside x-show="mobileMenuOpen" x-transition.transform.translate.x.-100 class="relative flex-1 flex flex-col max-w-[280px] w-full bg-white/90 backdrop-blur-3xl border-r border-white shadow-2xl px-6 py-8">
            <div class="flex items-center justify-between mb-8">
                <span class="font-black text-xl text-slate-900 tracking-tight">NU CIKEMBULAN</span>
                <button @click="mobileMenuOpen = false" class="p-2 bg-slate-100/50 hover:bg-slate-200 text-slate-500 rounded-full transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <nav class="flex flex-col gap-2 flex-grow">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-[#087A4B]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">Dashboard</a>
                
                @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="px-4 pt-4 pb-2 text-[10px] font-black uppercase tracking-widest text-slate-400">Data Master</div>
                <a href="{{ route('admin.berita.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-sm transition {{ request()->routeIs('admin.berita.*') ? 'bg-emerald-50 text-[#087A4B]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">Data Berita</a>
                <a href="{{ route('admin.anggota.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-sm transition {{ request()->routeIs('admin.anggota.*') ? 'bg-emerald-50 text-[#087A4B]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">Data Anggota</a>
                <a href="{{ route('admin.galeri.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-sm transition {{ request()->routeIs('admin.galeri.*') ? 'bg-emerald-50 text-[#087A4B]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">Data Galeri</a>
                @endif
            </nav>
            <div class="pt-6 border-t border-slate-200/50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl font-bold text-sm text-red-500 bg-red-50 hover:bg-red-100 transition">Logout</button>
                </form>
            </div>
        </aside>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="md:ml-[116px] min-h-screen flex flex-col relative z-10 transition-all duration-300">
        
        <!-- TOP BAR -->
        <header class="h-[72px] mt-6 mx-4 md:mx-8 lg:mx-10 glass-panel rounded-[24px] px-5 md:px-6 flex items-center justify-between sticky top-6 z-30">
            <div class="flex items-center gap-3 md:gap-4">
                <!-- Hamburger Mobile -->
                <button @click="mobileMenuOpen = true" class="md:hidden p-2 rounded-xl bg-white/50 text-slate-700 hover:bg-white shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="flex flex-col">
                    <span class="font-black text-slate-900 text-[15px] md:text-[17px] tracking-tight leading-none">Workspace</span>
                    <span class="text-[9px] md:text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">NU Cikembulan Digital</span>
                </div>
            </div>

            <!-- Search Command -->
            <div class="hidden lg:flex flex-1 max-w-sm xl:max-w-md mx-8">
                <div class="relative w-full">
                    <input type="text" placeholder="Search everywhere..." class="w-full bg-white/50 border border-white/80 focus:bg-white focus:ring-2 focus:ring-[#087A4B]/20 focus:border-[#087A4B] rounded-full pl-11 pr-4 py-2.5 text-xs font-bold text-slate-700 transition-all shadow-[0_2px_10px_rgba(0,0,0,0.02)] outline-none placeholder-slate-400">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Right Profile & Actions -->
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="hidden sm:flex w-9 h-9 md:w-10 md:h-10 rounded-full bg-white/60 items-center justify-center text-slate-400 hover:text-[#087A4B] hover:bg-white shadow-sm border border-white transition group" title="Lihat Website">
                    <svg class="w-4 h-4 md:w-4.5 md:h-4.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                </a>
                
                <div class="w-px h-6 bg-slate-200/60 hidden sm:block mx-1"></div>

                <a href="#profile-settings" class="flex items-center gap-2.5 pl-1.5 pr-3 md:pr-4 py-1.5 rounded-full bg-white/70 hover:bg-white border border-white shadow-sm hover:shadow transition group">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gradient-to-tr from-[#087A4B] to-emerald-400 text-white font-bold text-xs flex items-center justify-center shadow-inner overflow-hidden shrink-0">
                        @if(auth()->user() && auth()->user()->profilAnggota && auth()->user()->profilAnggota->photo)
                            <img src="{{ asset('storage/' . auth()->user()->profilAnggota->photo) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <span class="text-xs font-black text-slate-700 hidden sm:block group-hover:text-slate-900 transition-colors">{{ explode(' ', auth()->user()->name ?? 'User')[0] }}</span>
                    <svg class="w-3.5 h-3.5 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </a>
            </div>
        </header>

        <!-- CONTENT AREA -->
        <main class="flex-1 px-4 md:px-8 lg:px-10 py-8 lg:py-10">
            @yield('content')
        </main>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Kegiatan - NU CIKEMBULAN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @include('components.theme-script')
</head>

<body class="font-sans antialiased text-gray-900 bg-[#FAFCFB] flex flex-col min-h-screen relative overflow-x-hidden selection:bg-emerald-100 selection:text-[#087A4B]">

    <!-- Ambient Glow Background -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[400px] bg-gradient-to-b from-emerald-500/10 via-emerald-500/5 to-transparent blur-3xl pointer-events-none z-0"></div>

    @include('layouts.public-navigation')

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-20 flex-grow w-full relative z-10"
         x-data="{
            search: '',
            category: 'Semua',
            categories: ['Semua', 'Muslimat', 'Fatayat', 'GP Ansor', 'IPNU', 'IPPNU', 'PMII'],
            items: {{ \Illuminate\Support\Js::from($galeris->map(function($g) {
                return [
                    'id' => $g->id,
                    'title' => $g->title ?? 'Dokumentasi Kegiatan',
                    'category' => $g->category ?: 'Umum',
                    'photo' => asset('storage/' . $g->photo),
                    'date' => $g->created_at ? $g->created_at->format('d M Y') : ''
                ];
            })) }},
            get filteredItems() {
                let res = this.items;
                if (this.category !== 'Semua') {
                    res = res.filter(i => i.category === this.category);
                }
                if (this.search.trim() !== '') {
                    let q = this.search.toLowerCase();
                    res = res.filter(i => (i.title && i.title.toLowerCase().includes(q)) || (i.category && i.category.toLowerCase().includes(q)));
                }
                return res;
            }
         }"
    >
        <!-- 1. HEADER SECTION (HERO CARD PUTIH) -->
        <div class="relative overflow-hidden rounded-[1.5rem] bg-white/70 backdrop-blur-3xl border border-gray-200/60 shadow-sm px-5 py-6 md:px-8 md:py-6">
            <div class="absolute -right-20 -top-20 w-48 h-48 bg-emerald-100/40 blur-3xl rounded-full pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-[#087A4B] font-bold text-[10px] uppercase tracking-wider mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> DOKUMENTASI
                    </span>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Galeri Kegiatan</h1>
                    <p class="mt-1.5 max-w-lg text-sm text-gray-500 leading-relaxed">Momen penting, dokumentasi visual, dan arsip kegiatan organisasi NU Cikembulan.</p>
                </div>
                
                @auth
                    @if(auth()->user()->role === 'admin')
                    <div class="shrink-0">
                        <a href="{{ route('admin.galeri.create') }}" class="inline-flex items-center gap-1.5 bg-[#087A4B] hover:bg-[#065C39] text-white font-bold text-xs px-4 py-2.5 rounded-full shadow-sm shadow-emerald-500/20 transition-all whitespace-nowrap active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            <span>Tambah Galeri</span>
                        </a>
                    </div>
                    @endif
                @endauth
            </div>

            <!-- 2. FILTER BAR & SEARCH INPUT -->
            <div class="mt-5 pt-4 border-t border-gray-200/60 flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
                <!-- Filter Kategori di Sebelah Kiri -->
                <div class="flex overflow-x-auto hide-scrollbar gap-2 pb-1 md:pb-0 w-full md:w-auto">
                    <template x-for="cat in categories" :key="cat">
                        <button type="button"
                            @click="category = cat"
                            :class="category === cat ? 'bg-[#087A4B] text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                            class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-colors shadow-xs"
                            x-text="cat"></button>
                    </template>
                </div>

                <!-- Search Input di Sebelah Kanan -->
                <div class="relative w-full md:w-64 shrink-0">
                    <input type="text" x-model="search" placeholder="Cari galeri..."
                        class="w-full bg-white border border-gray-200 text-gray-900 text-xs font-medium rounded-full focus:ring-1 focus:ring-[#087A4B] focus:border-[#087A4B] block pl-9 pr-4 py-2.5 shadow-xs transition-all outline-none">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. GRID FOTO GALERI -->
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" x-show="filteredItems.length > 0" x-cloak>
            <template x-for="galeri in filteredItems" :key="galeri.id">
                <button type="button" 
                        :data-lightbox="galeri.photo" 
                        :data-title="galeri.title" 
                        class="group block w-full rounded-[1.5rem] overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-200/70 aspect-square bg-slate-900 relative text-left hover:-translate-y-1.5 hover:shadow-[0_12px_32px_rgba(0,0,0,0.1)] transition-all duration-300">
                    <img :src="galeri.photo" :alt="galeri.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    
                    <!-- Kategori Badge -->
                    <div class="absolute top-3.5 left-3.5">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur-md text-gray-900 font-bold text-[10px] rounded-full shadow-sm border border-white/60 uppercase tracking-wider" x-text="galeri.category"></span>
                    </div>

                    <!-- Hover View Overlay -->
                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span class="px-4 py-2 rounded-full bg-white/90 backdrop-blur-md text-gray-900 text-xs font-bold shadow-md flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#087A4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Lihat Foto
                        </span>
                    </div>

                    <!-- Title Bottom Gradient -->
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/85 via-black/40 to-transparent p-4 pt-10 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <p class="text-xs font-bold truncate leading-tight" x-text="galeri.title"></p>
                    </div>
                </button>
            </template>
        </div>

        <!-- 4. REDESAIN AREA EMPTY STATE (SOLID BORDER, CLEAN SLATE, MODERN VECTOR) -->
        <div class="mt-8 text-center py-20 px-6 bg-slate-50/60 rounded-[1.75rem] border border-slate-200/80 shadow-sm dark:bg-[#121614]/65 dark:border-white/10 dark:shadow-[0_16px_40px_rgba(0,0,0,0.16)] dark:backdrop-blur-[20px] dark:backdrop-saturate-150" x-show="filteredItems.length === 0" x-cloak>
            <div class="max-w-md mx-auto flex flex-col items-center">
                <!-- Modern Vector Album/Camera Icon with Emerald Accent -->
                <div class="relative w-20 h-20 mb-5 flex items-center justify-center">
                    <div class="absolute inset-0 bg-emerald-500/10 rounded-2xl rotate-6"></div>
                    <div class="relative w-16 h-16 rounded-2xl bg-white dark:bg-white/5 dark:border-white/10 border border-emerald-100 shadow-sm flex items-center justify-center text-[#087A4B]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-[#087A4B]"></span>
                        </span>
                    </div>
                </div>

                <!-- Hirarki Teks Tegas -->
                <h3 class="text-lg font-bold text-gray-900 dark:text-white/92 mb-1.5">Belum Ada Dokumentasi</h3>
                <p class="text-sm text-gray-500 dark:text-white/62 max-w-sm leading-relaxed mb-6">Foto kegiatan untuk kategori atau kata kunci ini belum diunggah atau tidak ditemukan.</p>
                
                <button type="button" 
                        @click="search = ''; category = 'Semua'" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white rounded-full text-xs font-bold transition-all shadow-sm active:scale-95 dark:bg-white/8 dark:hover:bg-white/12 dark:border dark:border-white/10 dark:text-white/90 dark:shadow-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Reset Filter</span>
                </button>
            </div>
        </div>

    </div>

    @include('layouts.public-footer')

</body>

</html>
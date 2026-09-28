<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Berita Terkini - NU CIKEMBULAN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @include('components.theme-script')
</head>
<body class="font-sans antialiased text-gray-900 bg-[#FAFCFB] flex flex-col min-h-screen relative overflow-x-hidden">

    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[400px] bg-gradient-to-b from-emerald-500/10 via-emerald-500/5 to-transparent blur-3xl pointer-events-none z-0"></div>

    @include('layouts.public-navigation')

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-20 flex-grow w-full relative z-10"
         x-data="{
            search: '',
            category: 'Semua',
            page: 1,
            perPage: 9,
            categories: ['Semua', 'Kegiatan', 'Pengumuman', 'Organisasi', 'Sosial'],
            selectedId: null,
            items: {{ \Illuminate\Support\Js::from($beritas->map(function($b) {
                return [
                    'id' => $b->id,
                    'title' => $b->title,
                    'excerpt_main' => \Illuminate\Support\Str::limit(strip_tags($b->content), 160),
                    'excerpt' => \Illuminate\Support\Str::limit(strip_tags($b->content), 90),
                    'image' => $b->image ? asset('storage/'.$b->image) : null,
                    'date' => $b->created_at->format('d M Y'),
                    'url' => route('berita.show', $b->id),
                    'category' => $b->category ?: 'Kegiatan'
                ];
            })) }},
            get filteredItems() {
                let res = this.items;
                if (this.category !== 'Semua') {
                    res = res.filter(i => i.category === this.category);
                }
                if (this.search.trim() !== '') {
                    let q = this.search.toLowerCase();
                    res = res.filter(i => i.title.toLowerCase().includes(q) || i.excerpt.toLowerCase().includes(q));
                }
                return res;
            },
            get activeHero() {
                if (this.filteredItems.length === 0) return null;
                if (this.selectedId) {
                    let found = this.filteredItems.find(i => i.id === this.selectedId);
                    if (found) return found;
                }
                return this.filteredItems[0];
            },
            get otherFeatured() {
                let hero = this.activeHero;
                if (!hero) return [];
                return this.filteredItems.filter(i => i.id !== hero.id).slice(0, 4);
            },
            selectHero(id) {
                this.selectedId = id;
            },
            get featuredItems() {
                return this.filteredItems.slice(0, 4);
            },
            get listItems() {
                let start = 4 + (this.page - 1) * this.perPage;
                return this.filteredItems.slice(start, start + this.perPage);
            },
            get totalPages() {
                return Math.max(1, Math.ceil(Math.max(0, this.filteredItems.length - 4) / this.perPage));
            },
            get pages() {
                let maxPages = this.totalPages;
                return Array.from({length: maxPages}, (_, i) => i + 1);
            },
            goToPage(p) {
                if (p >= 1 && p <= this.totalPages) {
                    this.page = p;
                    window.scrollTo({ top: document.getElementById('berita-terbaru').offsetTop - 100, behavior: 'smooth' });
                }
            }
         }"
    >
        
        <!-- 1. HEADER RINGKAS -->
        <div class="relative overflow-hidden rounded-[1.5rem] bg-white/70 backdrop-blur-3xl border border-gray-200/60 shadow-sm px-5 py-6 md:px-8 md:py-6">
            <div class="absolute -right-20 -top-20 w-48 h-48 bg-emerald-100/40 blur-3xl rounded-full pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 font-bold text-[10px] uppercase tracking-wider mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Warta & Informasi
                    </span>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Berita Terkini</h1>
                    <p class="mt-1.5 max-w-lg text-sm text-gray-500 leading-relaxed">Ikuti kabar terbaru, kegiatan, dan informasi resmi NU Cikembulan.</p>
                </div>
                
                @auth
                    @if(auth()->user()->role === 'admin')
                    <div class="shrink-0 hidden md:block">
                        <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center gap-1.5 bg-[#087A4B] hover:bg-[#065C39] text-white font-bold text-xs px-4 py-2.5 rounded-full shadow-sm transition-all whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Berita
                        </a>
                    </div>
                    @endif
                @endauth
            </div>
            
            <!-- 2. NEWS TOOLBAR (Dalam Header Container) -->
            <div class="mt-5 pt-4 border-t border-gray-200/60 flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
                <div class="flex overflow-x-auto hide-scrollbar gap-2 pb-1 md:pb-0 w-full md:w-auto">
                    <template x-for="cat in categories" :key="cat">
                        <button @click="category = cat; page = 1"
                            :class="category === cat ? 'bg-[#087A4B] text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                            class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-colors shadow-sm"
                            x-text="cat"></button>
                    </template>
                </div>
                <div class="relative w-full md:w-64 shrink-0">
                    <input type="text" x-model="search" @input="page = 1" placeholder="Cari berita..."
                        class="w-full bg-white border border-gray-200 text-gray-900 text-xs font-medium rounded-full focus:ring-1 focus:ring-[#087A4B] focus:border-[#087A4B] block pl-9 pr-4 py-2.5 shadow-sm transition-all outline-none">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. FEATURED SPOTLIGHT NEWS (INTERAKTIF) -->
        <div class="mt-8" x-show="activeHero" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Main Spotlight Hero (Kiri / Berita yang sedang tersorot) -->
                <div class="lg:col-span-8 flex flex-col">
                    <div class="bg-white/80 backdrop-blur-xl rounded-[1.5rem] border border-gray-200/70 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition-all duration-300">
                        <!-- Frame Gambar Berita Tersorot -->
                        <div class="relative h-[300px] sm:h-[380px] w-full overflow-hidden bg-slate-900">
                            <template x-if="activeHero?.image">
                                <img :src="activeHero.image" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105" alt="Thumbnail">
                            </template>
                            <template x-if="!activeHero?.image">
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-[#087A4B] to-emerald-800 text-white p-6 text-center">
                                    <svg class="w-16 h-16 opacity-30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 2v4a2 2 0 002 2h4M9 13h6M9 17h3"></path></svg>
                                    <span class="text-sm font-bold opacity-75">NU CIKEMBULAN DIGITAL</span>
                                </div>
                            </template>
                            
                            <!-- Overlay tipis & Tag Kategori -->
                            <div class="absolute top-4 left-4 flex items-center gap-2">
                                <span class="bg-[#087A4B] text-white text-xs font-black px-3.5 py-1.5 rounded-full uppercase tracking-wider shadow-md" x-text="activeHero?.category"></span>
                                <span class="bg-black/40 backdrop-blur-md text-white text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1.5 border border-white/20">
                                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span x-text="activeHero?.date"></span>
                                </span>
                            </div>
                        </div>

                        <!-- Keterangan & Detail Berita yang Sedang Tersorot (Di Bawah Foto) -->
                        <div class="p-6 md:p-8 flex flex-col justify-between flex-grow bg-transparent dark:bg-[#121614]/70 dark:backdrop-blur-xl dark:backdrop-saturate-150 transition-colors duration-300">
                            <div>
                                <h2 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white/90 leading-tight mb-3 hover:text-[#087A4B] transition-colors">
                                    <a :href="activeHero?.url" x-text="activeHero?.title"></a>
                                </h2>
                                <p class="text-gray-600 dark:text-white/60 text-sm md:text-base leading-relaxed mb-6" x-text="activeHero?.excerpt_main"></p>
                            </div>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-white/10">
                                <div class="flex items-center gap-2 text-xs font-bold text-gray-400 dark:text-[#087A4B] uppercase tracking-wider">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Sedang Ditampilkan</span>
                                </div>
                                <a :href="activeHero?.url" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#087A4B] hover:bg-[#065C39] text-white text-xs font-bold rounded-full shadow-sm shadow-emerald-500/20 transition-all hover:gap-3">
                                    <span>Baca Berita Lengkap</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Daftar Berita Samping (Klik untuk menyorot berita) -->
                <div class="lg:col-span-4 flex flex-col gap-4" x-show="otherFeatured.length > 0">
                    <div class="flex items-center justify-between pb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-4 bg-[#087A4B] rounded-full"></div>
                            <h3 class="text-base font-black text-gray-900 tracking-tight">Pilihan Berita Lainnya</h3>
                        </div>
                        <span class="text-[11px] font-bold text-gray-400">Klik untuk melihat</span>
                    </div>
                    
                    <div class="flex flex-col gap-3">
                        <template x-for="item in otherFeatured" :key="item.id">
                            <button type="button" 
                                    @click="selectHero(item.id)" 
                                    class="group text-left flex items-center gap-3.5 bg-white/70 backdrop-blur-xl border border-gray-200/60 p-3.5 rounded-[1.25rem] hover:bg-white hover:border-emerald-300 hover:shadow-md transition-all duration-200 cursor-pointer w-full focus:outline-none focus:ring-2 focus:ring-[#087A4B]/30"
                                    :class="activeHero?.id === item.id ? 'ring-2 ring-[#087A4B] bg-emerald-50/40 border-emerald-300' : ''">
                                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden shrink-0 relative bg-gray-100 shadow-sm">
                                    <template x-if="item.image">
                                        <img :src="item.image" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </template>
                                    <template x-if="!item.image">
                                        <div class="w-full h-full flex items-center justify-center bg-emerald-700 text-white font-black text-[10px]">NU</div>
                                    </template>
                                </div>
                                <div class="flex flex-col justify-center flex-grow pr-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[#087A4B] text-[9px] font-bold uppercase tracking-wider bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100" x-text="item.category"></span>
                                        <span class="text-gray-400 text-[10px] font-medium" x-text="item.date"></span>
                                    </div>
                                    <h4 class="text-gray-900 font-bold text-xs sm:text-[13px] leading-snug line-clamp-2 group-hover:text-[#087A4B] transition-colors" x-text="item.title"></h4>
                                    <span class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        Sorot berita ini &uarr;
                                    </span>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. BERITA TERBARU (GRID) -->
        <div class="mt-14" id="berita-terbaru" x-show="listItems.length > 0" x-cloak>
            <div class="flex items-center gap-3 mb-6">
                <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">Berita Lainnya</h2>
                <div class="h-px bg-gray-200 flex-grow"></div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="item in listItems" :key="item.id">
                    <a :href="item.url" class="group bg-white/80 backdrop-blur-xl rounded-[1.25rem] border border-gray-200/60 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden">
                        <div class="relative aspect-[16/9] overflow-hidden bg-gray-100">
                            <img :src="item.image || 'https://via.placeholder.com/600x400?text=No+Image'" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-2 py-1 rounded border border-white/50 shadow-sm flex items-center gap-1.5">
                                 <span class="w-1.5 h-1.5 rounded-full bg-[#087A4B]"></span>
                                 <span class="text-[9px] font-bold text-gray-900 uppercase tracking-wider" x-text="item.category"></span>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <span class="text-gray-400 text-[11px] font-medium mb-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span x-text="item.date"></span>
                            </span>
                            <h3 class="text-base font-bold text-gray-900 leading-snug mb-2.5 group-hover:text-[#087A4B] transition-colors line-clamp-2" x-text="item.title"></h3>
                            <p class="text-gray-500 text-[13px] leading-relaxed line-clamp-2 mb-4 flex-grow" x-text="item.excerpt"></p>
                        </div>
                    </a>
                </template>
            </div>

            <!-- 5. PAGINATION -->
            <div class="mt-10 flex justify-center" x-show="totalPages > 1">
                <nav class="flex items-center gap-1 bg-white/90 backdrop-blur-xl p-1.5 rounded-full border border-gray-200 shadow-sm">
                    <button @click="goToPage(page - 1)" :disabled="page === 1" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 disabled:opacity-40 disabled:hover:bg-transparent transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    
                    <div class="flex items-center gap-0.5">
                        <template x-for="p in pages" :key="p">
                            <button @click="goToPage(p)" 
                                :class="page === p ? 'bg-[#087A4B] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'" 
                                class="w-8 h-8 flex items-center justify-center rounded-full font-bold text-xs transition-colors">
                                <span x-text="p"></span>
                            </button>
                        </template>
                    </div>

                    <button @click="goToPage(page + 1)" :disabled="page === totalPages" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 disabled:opacity-40 disabled:hover:bg-transparent transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </nav>
            </div>
        </div>

        <!-- 6. EMPTY STATE -->
        <div class="mt-12 text-center py-16 bg-white/60 backdrop-blur-xl rounded-[1.5rem] border border-dashed border-gray-300 shadow-sm" x-show="filteredItems.length === 0" x-cloak>
            <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-4 shadow-inner border border-gray-100">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 2v4a2 2 0 002 2h4M9 13h6M9 17h3"></path></svg>
            </div>
            <h3 class="text-lg font-black text-gray-900 mb-2">Berita tidak ditemukan</h3>
            <p class="text-gray-500 text-sm max-w-sm mx-auto leading-relaxed">Pencarian atau kategori yang Anda pilih tidak menemukan hasil.</p>
            <button @click="search = ''; category = 'Semua'" class="mt-5 px-6 py-2.5 bg-gray-900 text-white rounded-full text-xs font-bold hover:bg-gray-800 transition-colors shadow-sm">Reset Filter</button>
        </div>
    </div>

    @include('layouts.public-footer')

</body>
</html>
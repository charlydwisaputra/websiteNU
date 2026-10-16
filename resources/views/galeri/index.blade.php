<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dokumentasi — {{ config('organisasi.nama') }}</title>
    <x-seo-meta
        :title="'Dokumentasi — ' . config('organisasi.nama')"
        :description="'Galeri dokumentasi kegiatan dan momen ' . config('organisasi.nama') . '.'"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.theme-script')

    <style>
        .masonry-grid { columns: 1; column-gap: 1.5rem; }
        @media (min-width: 640px)  { .masonry-grid { columns: 2; } }
        @media (min-width: 768px)  { .masonry-grid { columns: 3; } }
        @media (min-width: 1024px) { .masonry-grid { columns: 4; } }
        .masonry-item { break-inside: avoid; margin-bottom: 1.5rem; display: block; }
        @keyframes lbIn { from { opacity:0; transform:scale(0.94); } to { opacity:1; transform:scale(1); } }
        .lb-enter { animation: lbIn 0.28s cubic-bezier(0.16,1,0.3,1) forwards; }
    </style>
</head>

<body class="font-sans antialiased flex flex-col min-h-screen overflow-x-hidden"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    @php
        $galeriItems = $galeris->map(function($g) {
            return [
                'id'       => $g->id,
                'title'    => $g->title ?? 'Dokumentasi Kegiatan',
                'category' => $g->category ?: 'Umum',
                'photo'    => asset('storage/' . ($g->image ?? $g->photo)),
                'date'     => optional($g->created_at)->isoFormat('D MMM Y') ?? '',
            ];
        })->values()->toArray();

        $logoMap = collect(config('organisasi.banom'))->mapWithKeys(function($b) {
            return [$b['singkatan'] => asset('storage/logo-organisasi/' . $b['logo'])];
        })->toArray();
    @endphp

    <main class="flex-grow pt-32 pb-24"
          x-data="galApp({{ Illuminate\Support\Js::from($galeriItems) }}, {{ Illuminate\Support\Js::from($logoMap) }})"
          x-init="init()">

        <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- HEADER -->
            <header class="mb-12 pb-10 border-b border-black/5 dark:border-white/5 text-left">
                <h1 class="font-serif text-[clamp(2.5rem,6vw,5rem)] font-medium tracking-tight mb-4" style="color:var(--text-primary)">
                    Dokumentasi
                </h1>
                <p class="text-lg md:text-xl leading-relaxed max-w-2xl font-light" style="color:var(--text-secondary)">
                    Arsip visual dari langkah, karya, dan pengabdian seluruh elemen NU Kalisari.
                </p>
            </header>

            @if($galeris->isEmpty())
                <div class="py-12 px-6 rounded-2xl bg-black/5 dark:bg-white/5 text-center border border-black/5 dark:border-white/5">
                    <p class="text-base" style="color:var(--text-secondary)">Belum ada dokumentasi visual yang dipublikasikan.</p>
                </div>
            @else
                <!-- FILTERS & SEARCH -->
                <div class="mb-14 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <!-- Category chips -->
                    <div class="flex gap-2 overflow-x-auto scrollbar-none whitespace-nowrap flex-nowrap w-full md:w-auto pb-1">
                        <template x-for="c in categories" :key="c">
                            <button @click="setCategory(c)"
                                    class="px-4 py-2 min-h-[44px] rounded-full text-sm font-medium transition-colors border flex items-center gap-2 shrink-0"
                                    :class="activeCategory === c ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-transparent border-black/10 dark:border-white/10 hover:border-emerald-500/30'"
                                    :style="activeCategory !== c ? 'color:var(--text-secondary)' : ''">
                                <template x-if="logos[c]">
                                    <img :src="logos[c]" class="w-4 h-4 object-contain" :class="activeCategory === c ? 'brightness-0 invert' : 'dark:brightness-200 dark:contrast-100'" alt="">
                                </template>
                                <span x-text="c"></span>
                            </button>
                        </template>
                    </div>
                    <!-- Search -->
                    <div class="relative w-full md:w-64 shrink-0">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2" style="color:var(--text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" x-model="search" placeholder="Cari foto..."
                               class="w-full pl-9 pr-4 py-2 text-sm bg-black/5 dark:bg-white/5 border border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all"
                               style="color:var(--text-primary)">
                    </div>
                </div>

                <!-- RESULT COUNT -->
                <div class="mb-8" x-show="search || activeCategory !== 'Semua'" x-cloak>
                    <p class="text-sm" style="color:var(--text-muted)">
                        Menampilkan <span x-text="filtered.length" class="font-medium" style="color:var(--text-primary)"></span> hasil.
                    </p>
                </div>

                <!-- GRID -->
                <div class="masonry-grid" x-show="filtered.length > 0">
                    <template x-for="(item, idx) in visibleItems" :key="item.id">
                        <a href="javascript:void(0)" @click.prevent="openLb(idx)"
                           class="masonry-item group relative overflow-hidden card-hover rounded-xl bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/5">
                            <img :src="item.photo" :alt="item.title" class="w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-5">
                                <span class="text-[10px] font-medium uppercase tracking-widest text-emerald-400 mb-1" x-text="item.category"></span>
                                <h3 class="text-white font-serif text-lg font-medium leading-tight line-clamp-2" x-text="item.title"></h3>
                            </div>
                        </a>
                    </template>
                </div>

                <!-- EMPTY STATE SEARCH -->
                <div class="py-16 text-center" x-show="filtered.length === 0" x-cloak>
                    <h3 class="font-serif text-2xl font-medium mb-3" style="color:var(--text-primary)">Tidak ada hasil</h3>
                    <p class="text-base mb-6" style="color:var(--text-secondary)">Tidak ada foto yang cocok dengan pencarian atau filter.</p>
                    <button @click="search = ''; activeCategory = 'Semua'" class="px-6 py-2 btn-active-scale rounded-xl text-sm font-medium transition-colors bg-black/5 dark:bg-white/5 hover:bg-black/10 dark:hover:bg-white/10" style="color:var(--text-primary)">
                        Reset Filter
                    </button>
                </div>

                <!-- LOAD MORE -->
                <div class="mt-12 text-center" x-show="visibleItems.length < filtered.length" x-cloak>
                    <button @click="page++" class="px-8 py-3 btn-active-scale rounded-xl text-sm font-medium transition-colors bg-black/5 dark:bg-white/5 hover:bg-black/10 dark:hover:bg-white/10" style="color:var(--text-primary)">
                        Tampilkan Lebih Banyak
                    </button>
                </div>
            @endif

        </div>
    </main>

    <!-- LIGHTBOX -->
    <div id="nu-lightbox" class="fixed inset-0 z-50 bg-black/95 backdrop-blur-sm hidden items-center justify-center p-4">
        
        <!-- Controls -->
        <button id="lb-close" class="absolute top-4 right-4 sm:top-6 sm:right-6 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors z-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <button id="lb-prev" class="absolute left-2 sm:left-6 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors z-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button id="lb-next" class="absolute right-2 sm:right-6 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors z-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Content -->
        <div class="relative max-w-5xl w-full h-full flex flex-col items-center justify-center pointer-events-none">
            <div id="lb-img-wrap" class="relative max-h-[80vh] w-auto max-w-full rounded-lg overflow-hidden pointer-events-auto" style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                <img id="lb-img" src="" alt="" class="max-h-[80vh] w-auto max-w-full object-contain bg-black">
            </div>
            
            <div class="mt-6 text-center max-w-2xl w-full pointer-events-auto px-4">
                <div class="flex items-center justify-center gap-2 mb-2 text-xs uppercase tracking-widest font-semibold text-white/50">
                    <span id="lb-cat" class="text-emerald-400"></span>
                    <span>·</span>
                    <span id="lb-date"></span>
                    <span>·</span>
                    <span id="lb-counter"></span>
                </div>
                <h2 id="lb-title" class="font-serif text-2xl font-medium text-white mb-1 leading-tight"></h2>
            </div>
        </div>
    </div>

    @include('layouts.public-footer')

    <script>
    function galApp(data, logos) {
        return {
            allItems: data,
            logos: logos,
            categories: ['Semua', ...new Set(data.map(i => i.category))],
            activeCategory: 'Semua',
            search: '',
            perPage: 12,
            page: 1,

            get filtered() {
                let r = this.allItems;
                if (this.activeCategory !== 'Semua') r = r.filter(i => i.category === this.activeCategory);
                if (this.search.trim()) {
                    const q = this.search.toLowerCase();
                    r = r.filter(i => i.title.toLowerCase().includes(q) || i.category.toLowerCase().includes(q));
                }
                return r;
            },

            get visibleItems() {
                return this.filtered.slice(0, this.page * this.perPage);
            },

            setCategory(cat) { this.activeCategory = cat; this.page = 1; },

            openLb(idx) {
                window._lbItems = this.visibleItems;
                window._lbOpen(idx);
            },

            init() {
                this.$watch('search', () => { this.page = 1; });
            }
        };
    }
    </script>

    <script>
    (function () {
        const lb      = document.getElementById('nu-lightbox');
        const imgEl   = document.getElementById('lb-img');
        const titleEl = document.getElementById('lb-title');
        const catEl   = document.getElementById('lb-cat');
        const dateEl  = document.getElementById('lb-date');
        const counter = document.getElementById('lb-counter');
        const wrap    = document.getElementById('lb-img-wrap');
        let cur = 0;

        function show(idx) {
            const items = window._lbItems || [];
            cur = Math.max(0, Math.min(idx, items.length - 1));
            const it = items[cur];
            imgEl.src           = it.photo;
            imgEl.alt           = it.title;
            titleEl.textContent = it.title;
            catEl.textContent   = it.category;
            dateEl.textContent  = it.date;
            counter.textContent = (cur + 1) + ' / ' + items.length;
            document.getElementById('lb-prev').style.opacity = cur === 0 ? '0.3' : '1';
            document.getElementById('lb-next').style.opacity = cur >= items.length - 1 ? '0.3' : '1';
            wrap.classList.remove('lb-enter');
            void wrap.offsetWidth;
            wrap.classList.add('lb-enter');
        }

        window._lbOpen = function(idx) {
            lb.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            show(idx);
        };

        function closeLb() {
            lb.style.display = 'none';
            document.body.style.overflow = '';
        }

        if(document.getElementById('lb-close')) document.getElementById('lb-close').addEventListener('click', closeLb);
        if(document.getElementById('lb-prev')) document.getElementById('lb-prev').addEventListener('click', function() { if (cur > 0) show(cur - 1); });
        if(document.getElementById('lb-next')) document.getElementById('lb-next').addEventListener('click', function() {
            var n = (window._lbItems || []).length;
            if (cur < n - 1) show(cur + 1);
        });
        if(lb) lb.addEventListener('click', function(e) { if (e.target === lb) closeLb(); });
        document.addEventListener('keydown', function(e) {
            if (!lb || lb.style.display !== 'flex') return;
            if (e.key === 'Escape') closeLb();
            if (e.key === 'ArrowLeft'  && cur > 0)                                    show(cur - 1);
            if (e.key === 'ArrowRight' && cur < (window._lbItems || []).length - 1)   show(cur + 1);
        });
    })();
    </script>

</body>
</html>

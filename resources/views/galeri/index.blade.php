<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dokumentasi — NU Kalisari</title>
    <meta name="description" content="Galeri dokumentasi kegiatan dan momen Ranting Nahdlatul Ulama Kalisari.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.theme-script')

    @verbatim
    <style>
        /* Masonry columns */
        .masonry-grid { columns: 1; column-gap: 1rem; }
        @media (min-width: 640px)  { .masonry-grid { columns: 2; } }
        @media (min-width: 768px)  { .masonry-grid { columns: 3; } }
        @media (min-width: 1024px) { .masonry-grid { columns: 4; } }
        .masonry-item { break-inside: avoid; margin-bottom: 1rem; display: block; }

        /* Lightbox animation */
        @keyframes lbIn { from { opacity:0; transform:scale(0.94); } to { opacity:1; transform:scale(1); } }
        .lb-enter { animation: lbIn 0.28s cubic-bezier(0.16,1,0.3,1) forwards; }

        /* Gallery hover */
        .g-wrap { overflow: hidden; }
        .g-wrap img { transition: transform 0.45s cubic-bezier(0.16,1,0.3,1); width: 100%; display: block; }
        .g-item:hover .g-wrap img { transform: scale(1.07); }

        /* Overlay */
        .g-overlay {
            position: absolute; inset: 0; opacity: 0;
            transition: opacity 0.3s ease;
            background: linear-gradient(to top, rgba(8,8,19,0.9) 0%, rgba(8,8,19,0.35) 50%, transparent 100%);
            display: flex; align-items: flex-end; padding: 1.1rem;
        }
        .g-item:hover .g-overlay { opacity: 1; }
        .g-caption { transform: translateY(8px); transition: transform 0.35s cubic-bezier(0.16,1,0.3,1); }
        .g-item:hover .g-caption { transform: translateY(0); }

        /* Pill + search + buttons */
        .cat-pill { transition: all 0.22s cubic-bezier(0.16,1,0.3,1); }
        .g-search:focus { border-color: var(--accent) !important; box-shadow: 0 0 0 3px var(--accent-subtle) !important; }
        .btn-more { transition: all 0.3s cubic-bezier(0.16,1,0.3,1); }
        .btn-more:hover  { transform: translateY(-2px); box-shadow: 0 8px 24px -4px rgba(0,140,90,0.18); }
        .btn-more:active { transform: translateY(0); }
        .lb-btn { transition: background 0.2s ease; }
        .lb-btn:hover { background: rgba(255,255,255,0.18) !important; }
    </style>
    @endverbatim
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
                'photo'    => asset('storage/' . $g->photo),
                'date'     => optional($g->created_at)->format('d M Y') ?? '',
            ];
        })->values()->toArray();
    @endphp

    <main class="flex-grow pt-28 pb-32"
          x-data="galApp({{ Illuminate\Support\Js::from($galeriItems) }})"
          x-init="init()">

        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="inline-block text-xs font-bold uppercase tracking-[0.22em] mb-4" style="color:var(--accent)">Galeri Dokumentasi</span>
                <h1 class="font-serif text-5xl md:text-6xl font-bold tracking-tight mb-4 leading-tight" style="color:var(--text-primary)">
                    Merekam Setiap <em class="not-italic" style="color:var(--accent)">Momen</em>
                </h1>
                <p class="text-base md:text-lg leading-relaxed" style="color:var(--text-secondary)">
                    Kegiatan, perjalanan, dan kenangan bersama yang terdokumentasi dalam satu ruang visual.
                </p>
            </div>

            {{-- SEARCH --}}
            <div class="max-w-xl mx-auto mb-8">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none" style="color:var(--text-muted)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model.debounce.200ms="search"
                           placeholder="Cari momen atau kategori..."
                           class="g-search block w-full pl-14 pr-12 py-3.5 rounded-full text-sm font-medium outline-none transition-all"
                           style="background:var(--bg-surface);border:1.5px solid var(--border-medium);color:var(--text-primary);box-shadow:0 2px 12px rgba(0,0,0,0.05)">
                    <button x-show="search" @click="search=''" x-cloak
                            class="absolute inset-y-0 right-0 flex items-center pr-5" style="color:var(--text-muted)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- CATEGORY PILLS --}}
            <div class="flex overflow-x-auto scrollbar-none gap-2 pb-2 mb-10 justify-start md:justify-center">
                <template x-for="cat in categories" :key="cat">
                    <button type="button" @click="setCategory(cat)"
                            class="cat-pill px-4 py-2 rounded-full text-sm font-semibold border whitespace-nowrap"
                            :style="activeCategory === cat
                                ? 'background:var(--accent);color:#fff;border-color:var(--accent)'
                                : 'background:var(--bg-surface);color:var(--text-secondary);border-color:var(--border-medium)'"
                            x-text="cat">
                    </button>
                </template>
            </div>

            {{-- STATS --}}
            <div class="flex items-center justify-between mb-6 px-1">
                <p class="text-sm" style="color:var(--text-muted)">
                    Menampilkan <span class="font-semibold" style="color:var(--text-primary)" x-text="visibleItems.length"></span>
                    dari <span x-text="filtered.length"></span> dokumentasi
                </p>
                <button x-show="activeCategory !== 'Semua' || search" x-cloak
                        @click="search=''; setCategory('Semua')"
                        class="flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium transition-all"
                        style="background:var(--bg-surface);border:1px solid var(--border-medium);color:var(--text-secondary)">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </button>
            </div>

            {{-- MASONRY GRID --}}
            <div x-show="visibleItems.length > 0" x-cloak>
                <div class="masonry-grid">
                    <template x-for="(item, idx) in visibleItems" :key="item.id + '-' + idx">
                        <div class="masonry-item">
                            <button type="button" @click="openLb(idx)"
                                    class="g-item relative w-full rounded-2xl overflow-hidden cursor-zoom-in block"
                                    style="background:var(--bg-surface-elevated)">
                                <div class="g-wrap">
                                    <img :src="item.photo" :alt="item.title" loading="lazy"
                                         class="w-full object-cover"
                                         :style="idx % 5 === 0 ? 'aspect-ratio:3/4' : idx % 3 === 0 ? 'aspect-ratio:16/9' : 'aspect-ratio:4/3'"
                                         @@error="this.src='https://picsum.photos/seed/' + item.id + '/600/450'">
                                </div>
                                <div class="g-overlay rounded-2xl">
                                    <div class="g-caption w-full">
                                        <span class="inline-block px-2 py-0.5 mb-2 rounded-md text-[10px] font-bold uppercase tracking-widest text-white"
                                              style="background:var(--accent)" x-text="item.category"></span>
                                        <h3 class="text-white font-serif font-bold text-sm leading-snug line-clamp-2" x-text="item.title"></h3>
                                        <p class="mt-1.5 flex items-center gap-1 text-xs" style="color:rgba(255,255,255,0.55)">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span x-text="item.date"></span>
                                        </p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- EMPTY STATE --}}
            <div class="flex flex-col items-center justify-center py-24 text-center"
                 x-show="filtered.length === 0" x-cloak>
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-6"
                     style="background:var(--bg-surface);border:1px solid var(--border-light);color:var(--text-muted)">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-serif text-2xl font-bold mb-2" style="color:var(--text-primary)">Tidak ada hasil</h3>
                <p class="text-sm max-w-xs mb-6" style="color:var(--text-secondary)">Coba ubah kata kunci atau pilih kategori lain.</p>
                <button @click="search=''; setCategory('Semua')"
                        class="btn-more px-6 py-2.5 rounded-full text-sm font-semibold text-white"
                        style="background:var(--accent)">
                    Tampilkan Semua
                </button>
            </div>

            {{-- LOAD MORE --}}
            <div class="flex flex-col items-center gap-3 mt-14"
                 x-show="filtered.length > visibleItems.length" x-cloak>
                <p class="text-xs" style="color:var(--text-muted)">
                    <span x-text="visibleItems.length"></span> dari <span x-text="filtered.length"></span> foto
                </p>
                <button @click="page++"
                        class="btn-more group flex items-center gap-2.5 px-8 py-3.5 rounded-full text-sm font-semibold"
                        style="background:var(--bg-surface);border:1.5px solid var(--border-medium);color:var(--text-primary)">
                    <svg class="w-4 h-4 transition-transform duration-500 group-hover:rotate-180"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Muat Lebih Banyak
                </button>
            </div>

            {{-- ALL LOADED --}}
            <div class="flex items-center justify-center gap-4 mt-14"
                 x-show="filtered.length > 0 && filtered.length <= visibleItems.length" x-cloak>
                <div class="h-px flex-1 max-w-32" style="background:var(--border-light)"></div>
                <span class="text-xs" style="color:var(--text-muted)">Semua dokumentasi ditampilkan</span>
                <div class="h-px flex-1 max-w-32" style="background:var(--border-light)"></div>
            </div>

        </div>
    </main>

    {{-- LIGHTBOX --}}
    <div id="nu-lightbox"
         class="fixed inset-0 z-[200] hidden items-center justify-center"
         style="background:rgba(4,4,14,0.9);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px)">

        <button id="lb-close" aria-label="Tutup"
                class="lb-btn absolute top-5 right-5 z-10 w-11 h-11 rounded-full flex items-center justify-center"
                style="background:rgba(255,255,255,0.09);border:1px solid rgba(255,255,255,0.14);color:#fff">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div id="lb-counter"
             class="absolute top-5 left-1/2 -translate-x-1/2 text-sm font-medium px-4 py-1.5 rounded-full select-none"
             style="background:rgba(255,255,255,0.09);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.75)">
            1 / 1
        </div>

        <button id="lb-prev" aria-label="Sebelumnya"
                class="lb-btn absolute left-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full flex items-center justify-center"
                style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);color:#fff">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <button id="lb-next" aria-label="Berikutnya"
                class="lb-btn absolute right-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full flex items-center justify-center"
                style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);color:#fff">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <div class="flex flex-col items-center gap-5 w-full max-w-5xl px-16">
            <div id="lb-img-wrap" class="lb-enter w-full">
                <img id="lb-img" src="" alt=""
                     class="w-full max-h-[72vh] object-contain rounded-2xl"
                     style="box-shadow:0 32px 80px rgba(0,0,0,0.65)">
            </div>
            <div class="text-center">
                <span id="lb-cat"
                      class="inline-block px-3 py-1 mb-2 rounded-full text-xs font-bold uppercase tracking-widest text-white"
                      style="background:var(--accent)"></span>
                <h2 id="lb-title" class="font-serif text-xl font-bold text-white mb-1"></h2>
                <p id="lb-date" class="text-sm" style="color:rgba(255,255,255,0.45)"></p>
            </div>
        </div>
    </div>

    @include('layouts.public-footer')

    <script>
    function galApp(data) {
        return {
            allItems: data,
            categories: ['Semua','Muslimat','Fatayat','GP Ansor','IPNU','IPPNU','PMII'],
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

        document.getElementById('lb-close').addEventListener('click', closeLb);
        document.getElementById('lb-prev').addEventListener('click', function() { if (cur > 0) show(cur - 1); });
        document.getElementById('lb-next').addEventListener('click', function() {
            var n = (window._lbItems || []).length;
            if (cur < n - 1) show(cur + 1);
        });
        lb.addEventListener('click', function(e) { if (e.target === lb) closeLb(); });
        document.addEventListener('keydown', function(e) {
            if (lb.style.display !== 'flex') return;
            if (e.key === 'Escape') closeLb();
            if (e.key === 'ArrowLeft'  && cur > 0)                                    show(cur - 1);
            if (e.key === 'ArrowRight' && cur < (window._lbItems || []).length - 1)   show(cur + 1);
        });
    })();
    </script>

</body>
</html>

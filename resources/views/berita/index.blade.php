<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Warta — NU Cikembulan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.theme-script')
</head>
<body class="font-sans antialiased flex flex-col min-h-screen overflow-x-hidden"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-24 flex-grow w-full"
         x-data="{
            search: '',
            category: 'Semua',
            page: 1,
            perPage: 9,
            categories: ['Semua','Kegiatan','Pengumuman','Organisasi','Sosial'],
            items: {{ \Illuminate\Support\Js::from($beritas->map(fn($b) => [
                'id'       => $b->id,
                'title'    => $b->title,
                'excerpt'  => \Illuminate\Support\Str::limit(strip_tags($b->content), 120),
                'full_ex'  => \Illuminate\Support\Str::limit(strip_tags($b->content), 220),
                'image'    => $b->image ? asset('storage/'.$b->image) : null,
                'date'     => $b->created_at->format('d M Y'),
                'url'      => route('berita.show', $b->id),
                'category' => $b->category ?: 'Kegiatan',
            ])) }},
            get filtered() {
                let r = this.items;
                if (this.category !== 'Semua') r = r.filter(i => i.category === this.category);
                if (this.search.trim()) {
                    const q = this.search.toLowerCase();
                    r = r.filter(i => i.title.toLowerCase().includes(q) || i.excerpt.toLowerCase().includes(q));
                }
                return r;
            },
            get hero()  { return this.filtered[0] ?? null; },
            get side()  { return this.filtered.slice(1,4); },
            get rest()  {
                const start = 4 + (this.page-1)*this.perPage;
                return this.filtered.slice(start, start+this.perPage);
            },
            get totalPages() { return Math.max(1, Math.ceil(Math.max(0, this.filtered.length-4)/this.perPage)); },
            goPage(p) {
                if (p<1||p>this.totalPages) return;
                this.page=p;
                window.scrollTo({top:document.getElementById('more-section').offsetTop-120, behavior:'smooth'});
            }
         }">

        {{-- PAGE HEADER --}}
        <header class="mb-12 pb-10" style="border-bottom:1px solid var(--border-light)">
            <p class="text-label mb-3" style="color:var(--accent)">WARTA</p>
            <h1 class="font-serif text-5xl md:text-7xl font-bold tracking-tight mb-4" style="color:var(--text-primary)">
                Warta NU Cikembulan.
            </h1>
            <p class="text-lg md:text-xl leading-relaxed max-w-2xl" style="color:var(--text-secondary)">
                Berita, kegiatan, pengumuman, dan informasi terbaru dari lingkungan NU Cikembulan.
            </p>
        </header>

        {{-- FILTERS --}}
        <div class="mb-14 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            {{-- Category tabs --}}
            <div class="flex overflow-x-auto scrollbar-none gap-0 pb-1" style="border-bottom:1px solid var(--border-light)">
                <template x-for="cat in categories" :key="cat">
                    <button @click="category=cat;page=1"
                            :style="category===cat
                                ? 'color:var(--text-primary);border-bottom:2px solid var(--text-primary);margin-bottom:-1px'
                                : 'color:var(--text-muted);border-bottom:2px solid transparent;margin-bottom:-1px'"
                            class="px-3 py-2.5 text-xs font-bold uppercase tracking-widest whitespace-nowrap transition-all"
                            x-text="cat">
                    </button>
                </template>
            </div>

            {{-- Search --}}
            <div class="relative w-full md:w-72 shrink-0 group">
                <input type="text" x-model="search" @input="page=1" placeholder="Cari warta..."
                       class="w-full bg-transparent py-2 pr-4 text-sm font-medium transition-all outline-none"
                       style="border-bottom:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderBottomColor='var(--accent)'"
                       onblur="this.style.borderBottomColor='var(--border-medium)'"
                       ::placeholder-style="'color:var(--text-muted)'">
                <svg class="absolute right-0 top-1/2 -translate-y-1/2 w-4 h-4 transition-colors"
                     :style="search ? 'color:var(--accent)' : 'color:var(--text-muted)'"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        {{-- HERO + SIDEBAR --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-20" x-show="hero" x-cloak>

            {{-- Featured hero --}}
            <a :href="hero?.url" class="lg:col-span-8 group flex flex-col">
                <div class="w-full aspect-[16/10] rounded-2xl overflow-hidden mb-6 img-zoom-wrap"
                     style="background:var(--bg-surface-elevated);border:1px solid var(--border-light)">
                    <template x-if="hero?.image">
                        <img :src="hero.image" class="w-full h-full object-cover" alt="">
                    </template>
                    <template x-if="!hero?.image">
                        <div class="w-full h-full flex items-center justify-center font-serif text-2xl font-bold opacity-30"
                             style="color:var(--text-muted)">WARTA NU</div>
                    </template>
                </div>
                <div class="flex items-center gap-3 mb-3 text-xs font-bold uppercase tracking-widest">
                    <span x-text="hero?.category" style="color:var(--accent)"></span>
                    <span style="color:var(--border-medium)">|</span>
                    <span x-text="hero?.date" style="color:var(--text-muted)"></span>
                </div>
                <h2 class="font-serif text-4xl md:text-5xl font-bold leading-tight mb-4 transition-colors"
                    :style="'color:var(--text-primary)'"
                    x-text="hero?.title"
                    @mouseover="$el.style.color='var(--accent)'"
                    @mouseout="$el.style.color='var(--text-primary)'"></h2>
                <p class="text-base leading-relaxed line-clamp-3" style="color:var(--text-secondary)" x-text="hero?.full_ex"></p>
            </a>

            {{-- Side highlights --}}
            <div class="lg:col-span-4 flex flex-col">
                <div class="mb-5 pb-2" style="border-bottom:2px solid var(--text-primary)">
                    <h3 class="text-xs font-bold uppercase tracking-widest" style="color:var(--text-primary)">Sorotan</h3>
                </div>
                <div class="flex flex-col gap-6">
                    <template x-for="item in side" :key="item.id">
                        <a :href="item.url" class="group grid grid-cols-3 gap-4 items-start">
                            <div class="col-span-1 aspect-square rounded-xl overflow-hidden img-zoom-wrap"
                                 style="background:var(--bg-surface-elevated)">
                                <template x-if="item.image">
                                    <img :src="item.image" class="w-full h-full object-cover" alt="">
                                </template>
                            </div>
                            <div class="col-span-2 flex flex-col">
                                <span class="text-[10px] font-bold uppercase tracking-widest mb-1"
                                      style="color:var(--accent)" x-text="item.category"></span>
                                <h4 class="font-serif text-base font-bold leading-snug line-clamp-2 mb-1.5 transition-colors"
                                    style="color:var(--text-primary)"
                                    x-text="item.title"
                                    @mouseover="$el.style.color='var(--accent)'"
                                    @mouseout="$el.style.color='var(--text-primary)'"></h4>
                                <span class="text-xs font-medium" style="color:var(--text-muted)" x-text="item.date"></span>
                            </div>
                        </a>
                    </template>
                </div>
            </div>
        </div>

        {{-- MORE ARTICLES --}}
        <div id="more-section" class="scroll-mt-32 pt-12" style="border-top:1px solid var(--border-light)"
             x-show="rest.length > 0" x-cloak>
            <div class="mb-8 pb-2 inline-block" style="border-bottom:2px solid var(--text-primary)">
                <h2 class="text-xs font-bold uppercase tracking-widest" style="color:var(--text-primary)">Berita Lainnya</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14">
                <template x-for="item in rest" :key="item.id">
                    <a :href="item.url" class="group flex flex-col">
                        <div class="aspect-[4/3] rounded-xl overflow-hidden mb-4 img-zoom-wrap"
                             style="background:var(--bg-surface-elevated);border:1px solid var(--border-light)">
                            <template x-if="item.image">
                                <img :src="item.image" class="w-full h-full object-cover" alt="">
                            </template>
                        </div>
                        <div class="flex items-center gap-2 mb-2 text-[10px] font-bold uppercase tracking-widest">
                            <span x-text="item.category" style="color:var(--accent)"></span>
                            <span style="color:var(--border-medium)">·</span>
                            <span x-text="item.date" style="color:var(--text-muted)"></span>
                        </div>
                        <h3 class="font-serif text-xl font-bold leading-snug line-clamp-2 mb-2 transition-colors"
                            style="color:var(--text-primary)"
                            x-text="item.title"
                            @mouseover="$el.style.color='var(--accent)'"
                            @mouseout="$el.style.color='var(--text-primary)'"></h3>
                        <p class="text-sm leading-relaxed line-clamp-2" style="color:var(--text-secondary)" x-text="item.excerpt"></p>
                    </a>
                </template>
            </div>

            {{-- Pagination --}}
            <div class="mt-16 flex justify-center pt-10" style="border-top:1px solid var(--border-light)"
                 x-show="totalPages > 1">
                <div class="flex items-center gap-2">
                    <button @click="goPage(page-1)" :disabled="page===1"
                            class="w-10 h-10 flex items-center justify-center rounded-full border transition-all disabled:opacity-40"
                            style="border-color:var(--border-medium);color:var(--text-primary)"
                            onmouseover="this.style.background='var(--bg-surface)'"
                            onmouseout="this.style.background=''">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <template x-for="p in Array.from({length:totalPages},(_,i)=>i+1)" :key="p">
                        <button @click="goPage(p)"
                                :style="page===p
                                    ? 'background:var(--text-primary);color:var(--bg-base);border-color:var(--text-primary);font-weight:700'
                                    : 'border-color:transparent;color:var(--text-secondary)'"
                                class="w-10 h-10 flex items-center justify-center rounded-full border text-sm transition-all"
                                x-text="p"></button>
                    </template>
                    <button @click="goPage(page+1)" :disabled="page===totalPages"
                            class="w-10 h-10 flex items-center justify-center rounded-full border transition-all disabled:opacity-40"
                            style="border-color:var(--border-medium);color:var(--text-primary)"
                            onmouseover="this.style.background='var(--bg-surface)'"
                            onmouseout="this.style.background=''">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- EMPTY --}}
        <div class="mt-20 text-center py-24" style="border-top:1px solid var(--border-light)"
             x-show="filtered.length===0" x-cloak>
            <h3 class="font-serif text-3xl font-bold mb-3" style="color:var(--text-primary)">Warta tidak ditemukan</h3>
            <p class="text-base max-w-md mx-auto" style="color:var(--text-secondary)">
                Tidak ada berita yang cocok dengan filter yang dipilih.
            </p>
        </div>

    </div>

    @include('layouts.public-footer')
</body>
</html>

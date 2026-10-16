<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Warta — {{ config('organisasi.nama') }}</title>
    <x-seo-meta
        :title="'Warta — ' . config('organisasi.nama')"
        :description="'Warta, kegiatan, dan informasi terbaru dari lingkungan NU Kalisari.'"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.theme-script')
</head>
<body class="font-sans antialiased flex flex-col min-h-screen overflow-x-hidden"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-24 flex-grow w-full">
        <!-- PAGE HEADER -->
        <header class="mb-12 pb-10 border-b border-black/5 dark:border-white/5">
            <h1 class="font-serif text-[clamp(2.5rem,6vw,5rem)] font-medium tracking-tight mb-4" style="color:var(--text-primary)">
                Warta
            </h1>
            <p class="text-lg md:text-xl leading-relaxed max-w-2xl font-light" style="color:var(--text-secondary)">
                Warta, kegiatan, dan informasi terbaru dari lingkungan NU Kalisari.
            </p>
        </header>

        @if($beritas->isEmpty())
            <div class="py-12 px-6 rounded-2xl bg-black/5 dark:bg-white/5 text-center border border-black/5 dark:border-white/5">
                <p class="text-base" style="color:var(--text-secondary)">Belum ada warta yang dipublikasikan.</p>
            </div>
        @else
        <div x-data="{
                search: '',
                category: 'Semua',
                limit: 10,
                categories: ['Semua','Kegiatan','Pengumuman','Organisasi','Sosial'],
                items: {{ \Illuminate\Support\Js::from($beritas->map(fn($b) => [
                    'id'       => $b->id,
                    'title'    => $b->title,
                    'excerpt'  => \Illuminate\Support\Str::limit(strip_tags($b->content), 120),
                    'full_ex'  => \Illuminate\Support\Str::limit(strip_tags($b->content), 220),
                    'image'    => $b->image ? asset('storage/'.$b->image) : null,
                    'date'     => \Carbon\Carbon::parse($b->created_at)->isoFormat('D MMM Y'),
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
                get hero()  { return this.filtered.length > 0 ? this.filtered[0] : null; },
                get side()  { return this.filtered.slice(1, 4); },
                get rest()  { return this.filtered.slice(4, this.limit); },
                get hasMore() { return this.limit < this.filtered.length; },
                loadMore() { this.limit += 10; }
             }">

            <!-- FILTERS -->
            <div class="mb-14 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <!-- Category chips -->
                <div class="flex gap-2 overflow-x-auto scrollbar-none whitespace-nowrap flex-nowrap w-full md:w-auto pb-1">
                    <template x-for="c in categories" :key="c">
                        <button @click="category = c; limit = 10"
                                class="px-4 py-2 min-h-[44px] rounded-full text-sm font-medium transition-colors border shrink-0"
                                :class="category === c ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-transparent border-black/10 dark:border-white/10 hover:border-emerald-500/30'"
                                :style="category !== c ? 'color:var(--text-secondary)' : ''"
                                x-text="c"></button>
                    </template>
                </div>
                <!-- Search -->
                <div class="relative w-full md:w-64 shrink-0">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2" style="color:var(--text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="search" @input="limit = 10" placeholder="Cari warta..."
                           class="w-full pl-9 pr-4 py-2 text-sm bg-black/5 dark:bg-white/5 border border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all"
                           style="color:var(--text-primary)">
                </div>
            </div>

            <!-- RESULT COUNT -->
            <div class="mb-8" x-show="search || category !== 'Semua'" x-cloak>
                <p class="text-sm" style="color:var(--text-muted)">
                    Menampilkan <span x-text="filtered.length" class="font-medium" style="color:var(--text-primary)"></span> hasil.
                </p>
            </div>

            <div x-show="filtered.length > 0">
                <!-- TOP GRID (1 HERO + 3 SIDE) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-16">
                    <!-- HERO -->
                    <div class="lg:col-span-8 flex flex-col group" x-show="hero">
                        <a :href="hero?.url" class="block w-full aspect-video rounded-2xl overflow-hidden mb-5 bg-emerald-50 dark:bg-emerald-900/10 border border-black/5 dark:border-white/5">
                            <template x-if="hero?.image">
                                <img :src="hero.image" :alt="hero.title" class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-105" loading="lazy">
                            </template>
                            <template x-if="!hero?.image">
                                <div class="w-full h-full flex items-center justify-center text-emerald-600/30 font-serif text-4xl font-medium" x-text="hero?.title.substring(0,2)"></div>
                            </template>
                        </a>
                        <div class="flex items-center gap-2 mb-3 text-[10px] font-medium uppercase tracking-widest" style="color:var(--text-muted)">
                            <span x-text="hero?.category"></span>
                            <span>·</span>
                            <span x-text="hero?.date"></span>
                        </div>
                        <h2 class="font-serif text-3xl md:text-4xl font-medium leading-tight mb-3 group-hover:text-emerald-600 transition-colors" style="color:var(--text-primary)">
                            <a :href="hero?.url" x-text="hero?.title"></a>
                        </h2>
                        <p class="text-base font-normal leading-relaxed line-clamp-3" style="color:var(--text-secondary)" x-text="hero?.full_ex"></p>
                    </div>
                    
                    <!-- SIDE -->
                    <div class="lg:col-span-4 flex flex-col gap-4" x-show="side.length > 0">
                        <template x-for="item in side" :key="item.id">
                            <a :href="item.url" class="flex flex-row lg:flex-col gap-4 lg:gap-3 group flex-1 min-h-0">
                                <div class="w-1/3 lg:w-full aspect-[4/3] lg:aspect-[16/9] shrink-0 rounded-xl overflow-hidden bg-emerald-50 dark:bg-emerald-900/10 border border-black/5 dark:border-white/5 relative">
                                    <template x-if="item.image">
                                        <img :src="item.image" :alt="item.title" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                    </template>
                                    <template x-if="!item.image">
                                        <div class="w-full h-full flex items-center justify-center text-emerald-600/30 font-serif text-xl font-medium" x-text="item.title.substring(0,2)"></div>
                                    </template>
                                    <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wide bg-black/60 text-white backdrop-blur-sm lg:hidden" x-text="item.category"></span>
                                </div>
                                <div class="flex flex-col justify-center py-1">
                                    <span class="text-[10px] font-medium uppercase tracking-widest mb-1" style="color:var(--text-muted)" x-text="item.date"></span>
                                    <h3 class="font-serif text-xl font-semibold leading-tight group-hover:text-emerald-600 transition-colors line-clamp-2" style="color:var(--text-primary)" x-text="item.title"></h3>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>

                <!-- MORE LIST -->
                <div id="more-section" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" x-show="rest.length > 0">
                    <template x-for="item in rest" :key="item.id">
                        <a :href="item.url" class="group block p-4 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/5 hover:border-emerald-500/30 transition-colors card-hover">
                            <div class="w-full aspect-video rounded-xl overflow-hidden mb-4 bg-emerald-50 dark:bg-emerald-900/10">
                                <template x-if="item.image">
                                    <img :src="item.image" :alt="item.title" class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-105" loading="lazy">
                                </template>
                                <template x-if="!item.image">
                                    <div class="w-full h-full flex items-center justify-center text-emerald-600/30 font-serif text-2xl font-medium" x-text="item.title.substring(0,2)"></div>
                                </template>
                            </div>
                            <div class="flex items-center gap-2 mb-2 text-[10px] font-medium uppercase tracking-widest" style="color:var(--text-muted)">
                                <span x-text="item.category"></span>
                                <span>·</span>
                                <span x-text="item.date"></span>
                            </div>
                            <h3 class="font-serif text-xl font-semibold leading-snug line-clamp-2 mb-2 group-hover:text-emerald-600 transition-colors" style="color:var(--text-primary)" x-text="item.title"></h3>
                            <p class="text-sm font-normal leading-relaxed line-clamp-2" style="color:var(--text-secondary)" x-text="item.excerpt"></p>
                        </a>
                    </template>
                </div>

                <!-- LOAD MORE -->
                <div class="text-center pt-8" x-show="hasMore">
                    <button @click="loadMore" class="px-6 py-2.5 btn-active-scale rounded-xl text-sm font-medium transition-colors bg-black/5 dark:bg-white/5 hover:bg-black/10 dark:hover:bg-white/10" style="color:var(--text-primary)">
                        Muat lebih banyak
                    </button>
                </div>
            </div>

            <!-- EMPTY SEARCH -->
            <div class="py-16 text-center" x-show="filtered.length === 0" x-cloak>
                <h3 class="font-serif text-2xl font-medium mb-3" style="color:var(--text-primary)">Tidak ada hasil</h3>
                <p class="text-base text-center mb-6" style="color:var(--text-secondary)">Tidak ada warta yang cocok dengan pencarian atau filter.</p>
                <button @click="search = ''; category = 'Semua'" class="px-6 py-2 btn-active-scale rounded-xl text-sm font-medium transition-colors bg-black/5 dark:bg-white/5 hover:bg-black/10 dark:hover:bg-white/10" style="color:var(--text-primary)">
                    Reset Filter
                </button>
            </div>
            
        </div>
        @endif
    </div>

    @include('layouts.public-footer')
</body>
</html>

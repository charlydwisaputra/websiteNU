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
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.theme-script')
</head>
<body class="font-sans antialiased flex flex-col min-h-screen"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    <main class="flex-grow pt-[116px] md:pt-[132px] pb-16 md:pb-20 w-full"
          x-data="{
             search: '',
             category: 'Semua',
             limit: 9,
             rawItems: {{ \Illuminate\Support\Js::from($beritas->map(fn($b) => [
                 'id'           => $b->id,
                 'title'        => $b->title,
                 'excerpt'      => trim(preg_replace('/\s+/', ' ', strip_tags($b->content))),
                 'image'        => $b->image ? asset('storage/'.$b->image) : null,
                 'date'         => \Carbon\Carbon::parse($b->created_at)->isoFormat('D MMM Y'),
                 'url'          => route('berita.show', $b->id),
                 'category'     => $b->category ?: 'Kegiatan',
                 'reading_time' => $b->reading_time,
             ])) }},
             get categories() {
                 const cats = Array.from(new Set(this.rawItems.map(i => i.category).filter(Boolean)));
                 const preferred = {{ \Illuminate\Support\Js::from(\App\Models\Berita::CATEGORIES) }};
                 const ordered = ['Semua', ...preferred.filter(c => cats.includes(c))];
                 cats.forEach(c => { if (!ordered.includes(c)) ordered.push(c); });
                 return ordered;
             },
             get filtered() {
                 let r = this.rawItems;
                 if (this.category !== 'Semua') r = r.filter(i => i.category === this.category);
                 if (this.search && this.search.trim()) {
                     const q = this.search.toLowerCase().trim();
                     r = r.filter(i => 
                         (i.title && i.title.toLowerCase().includes(q)) || 
                         (i.excerpt && i.excerpt.toLowerCase().includes(q)) || 
                         (i.category && i.category.toLowerCase().includes(q))
                     );
                 }
                 return r;
             },
             get hero()  { return this.filtered.length > 0 ? this.filtered[0] : null; },
             get side()  { return this.filtered.slice(1, 5); },
             get rest()  { return this.filtered.slice(5, this.limit); },
             get hasMore() { return this.limit < this.filtered.length; },
             loadMore() { this.limit += 6; },
             categoryBadgeClass(cat) {
                 switch (cat) {
                     case 'Pengumuman': return 'text-amber-700 dark:text-amber-300 bg-amber-500/10 border-amber-500/25';
                     case 'Organisasi': return 'text-sky-700 dark:text-sky-300 bg-sky-500/10 border-sky-500/25';
                     case 'Sosial':     return 'text-rose-700 dark:text-rose-300 bg-rose-500/10 border-rose-500/25';
                     default:           return 'text-emerald-700 dark:text-emerald-300 bg-emerald-500/10 border-emerald-500/25';
                 }
             }
          }">

        <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 w-full" style="overflow-x: clip">

        <!-- PAGE HEADER -->
        <x-page-header
            eyebrow="BERITA & PENGUMUMAN"
            title="Warta"
            subtitle="Warta, kegiatan, dan informasi terbaru dari lingkungan NU Kalisari."
            :count="$beritas->count()"
            unit="warta"
            count-expression="filtered.length"
        />

        @if($beritas->isEmpty())
            <div class="py-16 sm:py-20 px-6 rounded-3xl bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/10 text-center max-w-lg mx-auto shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 mx-auto flex items-center justify-center mb-4 border border-emerald-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8"/></svg>
                </div>
                <h3 class="font-serif text-2xl font-medium mb-2" style="color:var(--text-primary)">Belum ada warta yang dipublikasikan.</h3>
                <p class="text-sm sm:text-base leading-relaxed text-slate-600 dark:text-slate-400">
                    Kabar dan pengumuman kegiatan NU Kalisari akan segera ditampilkan di sini.
                </p>
            </div>
        @else

            <!-- FILTERS & PENCARIAN -->
            <div class="mb-8 md:mb-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 md:gap-6">
                <!-- Chip filter kategori dinamis dengan scroll-snap dan right fade di mobile -->
                <div class="relative w-full md:w-auto overflow-hidden">
                    <div id="filter-chips-container"
                         class="flex gap-2 overflow-x-auto scrollbar-none whitespace-nowrap flex-nowrap w-full md:w-auto pb-1.5 pt-0.5 px-0.5 snap-x snap-mandatory pr-12 md:pr-0"
                         style="scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;">
                        <template x-for="c in categories" :key="c">
                            <button @click="category = c; limit = 9; $nextTick(() => $el.scrollIntoView({behavior: 'smooth', inline: 'center', block: 'nearest'}))"
                                    class="snap-start px-4 py-2 min-h-[44px] rounded-full text-xs sm:text-sm font-medium transition-all border shrink-0 cursor-pointer active:scale-95"
                                    :class="category === c 
                                        ? 'bg-emerald-600 border-emerald-600 text-white font-semibold shadow-sm shadow-emerald-950/20' 
                                        : 'bg-white dark:bg-[#070D09] border-black/10 dark:border-white/10 hover:border-emerald-500/40 text-slate-700 dark:text-slate-300'"
                                    x-text="c"></button>
                        </template>
                    </div>
                    <!-- Fade mask tepi kanan pada mobile -->
                    <div class="pointer-events-none absolute right-0 top-0 bottom-1.5 w-12 md:hidden"
                         style="background: linear-gradient(to left, var(--bg-base) 45%, transparent);"></div>
                </div>

                <!-- Kolom Pencarian dengan debounce 200ms dan tombol reset × -->
                <div class="relative w-full md:w-72 shrink-0">
                    <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model.debounce.200ms="search" @input="limit = 9" placeholder="Cari warta..."
                           class="w-full pl-10 pr-9 py-2.5 min-h-[44px] text-sm bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 transition-all text-slate-900 dark:text-slate-100 placeholder:text-slate-400">
                    <button type="button" 
                            x-show="search.length > 0" 
                            @click="search = ''; limit = 9" 
                            x-cloak
                            aria-label="Hapus pencarian"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 flex items-center justify-center rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-black/5 dark:hover:bg-white/10 cursor-pointer transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- HASIL PENCARIAN & FILTER -->
            <div x-show="filtered.length > 0">
                <!-- TOP GRID (HERO + DAFTAR SAMPING DENGAN TINGGI SEIMBANG) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 md:mb-12 items-stretch" x-show="hero">
                    <!-- HERO CARD (UNGGULAN BESAR: MELEBAR PENUH BILA DAFTAR KANAN KOSONG) -->
                    <a :href="hero?.url" 
                       :class="side.length === 0 ? 'lg:col-span-12 max-w-3xl mx-auto w-full' : 'lg:col-span-7'"
                       class="p-5 sm:p-6 rounded-2xl bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/10 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition-all duration-300 hover:-translate-y-1 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#070D09] group flex flex-col justify-between h-full">
                        <div>
                            {{-- Wrapper rasio 16:9 tetap dengan object-cover & placeholder gradien sesuai kategori --}}
                            <div class="w-full aspect-video rounded-xl sm:rounded-2xl overflow-hidden mb-4 bg-emerald-950/20 border border-black/5 dark:border-white/5 relative">
                                <template x-if="hero?.image">
                                    <img :src="hero.image" :alt="hero.title || 'Warta NU Kalisari'" x-on:error="hero.image = null" class="w-full h-full object-cover object-center transition-transform duration-500 ease-out group-hover:scale-105 motion-reduce:group-hover:scale-100 motion-reduce:transition-none" loading="lazy">
                                </template>
                                <template x-if="!hero?.image">
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-[#064e3b] via-[#04382a] to-[#022118] text-emerald-300/80 select-none relative overflow-hidden">
                                        <div class="absolute inset-0 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:16px_16px] opacity-15"></div>
                                        <div class="relative z-10 flex flex-col items-center justify-center">
                                            <template x-if="hero?.category === 'Kegiatan'">
                                                <svg class="w-12 h-12 mb-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </template>
                                            <template x-if="hero?.category === 'Pengumuman'">
                                                <svg class="w-12 h-12 mb-2 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                            </template>
                                            <template x-if="hero?.category === 'Organisasi'">
                                                <svg class="w-12 h-12 mb-2 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </template>
                                            <template x-if="hero?.category === 'Sosial'">
                                                <svg class="w-12 h-12 mb-2 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                            </template>
                                            <template x-if="!['Kegiatan','Pengumuman','Organisasi','Sosial'].includes(hero?.category)">
                                                <svg class="w-12 h-12 mb-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8"/></svg>
                                            </template>
                                            <span class="text-xs font-semibold tracking-wider uppercase text-emerald-200/90" x-text="hero?.category"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Meta: Kategori (aksen warna), Tanggal, Waktu Baca --}}
                            <div class="flex items-center flex-wrap gap-2 mb-2.5 text-[12px] font-semibold uppercase tracking-wider">
                                <span class="px-2.5 py-0.5 rounded-full border text-[11px]" :class="categoryBadgeClass(hero?.category)" x-text="hero?.category"></span>
                                <span class="text-black/30 dark:text-white/30">·</span>
                                <span class="text-slate-500 dark:text-slate-400 font-medium" x-text="hero?.date"></span>
                                <span class="text-black/30 dark:text-white/30">·</span>
                                <span class="text-slate-500 dark:text-slate-400 font-medium lowercase" x-text="hero?.reading_time"></span>
                            </div>

                            <h2 class="font-serif text-2xl sm:text-3xl font-bold leading-snug mb-3 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-2" style="color:var(--text-primary)" x-text="hero?.title"></h2>
                            <p class="text-sm sm:text-base leading-relaxed text-slate-600 dark:text-slate-300 line-clamp-2 sm:line-clamp-3" x-text="hero?.excerpt"></p>
                        </div>
                        <div class="mt-5 pt-3 border-t border-black/5 dark:border-white/5 flex items-center gap-1.5 text-sm font-semibold text-emerald-700 dark:text-emerald-400 group-hover:underline">
                            <span>Baca selengkapnya</span>
                            <span aria-hidden="true" class="transition-transform duration-200 group-hover:translate-x-1">&rarr;</span>
                        </div>
                    </a>
                    
                    <!-- DAFTAR KANAN: DITATA DENGAN TINGGI SEIMBANG DENGAN HERO -->
                    <div class="lg:col-span-5 flex flex-col justify-between gap-3 sm:gap-4 h-full" x-show="side.length > 0">
                        <template x-for="item in side" :key="item.id">
                            <a :href="item.url" 
                               class="flex-1 p-3 sm:p-4 rounded-2xl bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/10 shadow-sm hover:shadow-md hover:border-emerald-500/40 transition-all duration-300 hover:-translate-y-[2px] cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#070D09] group flex items-center gap-3 sm:gap-4">
                                {{-- Thumbnail bersih rasio tetap tanpa teks menumpuk --}}
                                <div class="w-[88px] h-[72px] sm:w-[116px] sm:h-[84px] aspect-[4/3] sm:aspect-video shrink-0 rounded-xl overflow-hidden bg-emerald-950/20 border border-black/5 dark:border-white/5 relative">
                                    <template x-if="item.image">
                                        <img :src="item.image" :alt="item.title || 'Warta NU Kalisari'" x-on:error="item.image = null" class="w-full h-full object-cover object-center transition-transform duration-300 ease-out group-hover:scale-105" loading="lazy">
                                    </template>
                                    <template x-if="!item.image">
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#064e3b] via-[#04382a] to-[#022118] text-emerald-300/80 select-none">
                                            <template x-if="item.category === 'Kegiatan'">
                                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </template>
                                            <template x-if="item.category === 'Pengumuman'">
                                                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                            </template>
                                            <template x-if="item.category === 'Organisasi'">
                                                <svg class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </template>
                                            <template x-if="item.category === 'Sosial'">
                                                <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                            </template>
                                            <template x-if="!['Kegiatan','Pengumuman','Organisasi','Sosial'].includes(item.category)">
                                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8"/></svg>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                                {{-- Text di samping thumbnail --}}
                                <div class="flex flex-col justify-center flex-1 min-w-0 py-0.5">
                                    <div class="flex items-center gap-1.5 mb-1 text-[11px] font-semibold uppercase tracking-wider whitespace-nowrap overflow-hidden">
                                        <span class="px-2 py-0.2 rounded text-[10px]" :class="categoryBadgeClass(item.category)" x-text="item.category"></span>
                                        <span class="text-black/30 dark:text-white/30 shrink-0">·</span>
                                        <span class="text-slate-500 dark:text-slate-400 font-medium truncate" x-text="item.date"></span>
                                    </div>
                                    <h3 class="font-serif text-[14px] sm:text-[15px] font-semibold leading-snug group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-2" style="color:var(--text-primary)" x-text="item.title"></h3>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>

                <!-- SECTION: WARTA LAINNYA (GRID 3 KOLOM DESKTOP, 1 KOLOM MOBILE, ARTIKEL SETELAH 5 TERATAS) -->
                <div id="more-section" class="mb-8 md:mb-12" x-show="rest.length > 0">
                    <div class="mb-6 flex items-center justify-between border-b border-black/5 dark:border-white/5 pb-3">
                        <h2 class="font-serif text-2xl font-bold" style="color:var(--text-primary)">Warta Lainnya</h2>
                        <span class="text-xs text-slate-500 dark:text-slate-400">Arsip warta dan kegiatan</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                        <template x-for="item in rest" :key="item.id">
                            <a :href="item.url" 
                               class="group p-4 sm:p-5 rounded-2xl bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/10 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition-all duration-300 hover:-translate-y-1 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#070D09] flex flex-col justify-between h-full">
                                <div>
                                    {{-- Thumbnail 16:9 aspect-video --}}
                                    <div class="w-full aspect-video rounded-xl overflow-hidden mb-3.5 bg-emerald-950/20 border border-black/5 dark:border-white/5 relative">
                                        <template x-if="item.image">
                                            <img :src="item.image" :alt="item.title || 'Warta NU Kalisari'" x-on:error="item.image = null" class="w-full h-full object-cover object-center transition-transform duration-500 ease-out group-hover:scale-105" loading="lazy">
                                        </template>
                                        <template x-if="!item.image">
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-[#064e3b] via-[#04382a] to-[#022118] text-emerald-300/80 select-none">
                                                <template x-if="item.category === 'Kegiatan'">
                                                    <svg class="w-8 h-8 opacity-80 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </template>
                                                <template x-if="item.category === 'Pengumuman'">
                                                    <svg class="w-8 h-8 opacity-80 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                                </template>
                                                <template x-if="item.category === 'Organisasi'">
                                                    <svg class="w-8 h-8 opacity-80 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                </template>
                                                <template x-if="item.category === 'Sosial'">
                                                    <svg class="w-8 h-8 opacity-80 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                                </template>
                                                <template x-if="!['Kegiatan','Pengumuman','Organisasi','Sosial'].includes(item.category)">
                                                    <svg class="w-8 h-8 opacity-80 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8"/></svg>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    {{-- Info Meta --}}
                                    <div class="flex items-center flex-wrap gap-1.5 mb-2 text-[11px] sm:text-[12px] font-semibold uppercase tracking-wider">
                                        <span class="px-2 py-0.5 rounded text-[10px]" :class="categoryBadgeClass(item.category)" x-text="item.category"></span>
                                        <span class="text-black/30 dark:text-white/30">·</span>
                                        <span class="text-slate-500 dark:text-slate-400 font-medium" x-text="item.date"></span>
                                        <span class="text-black/30 dark:text-white/30">·</span>
                                        <span class="text-slate-500 dark:text-slate-400 font-medium lowercase" x-text="item.reading_time"></span>
                                    </div>
                                    <h3 class="font-serif text-lg sm:text-xl font-bold leading-snug line-clamp-2 mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" style="color:var(--text-primary)" x-text="item.title"></h3>
                                    <p class="text-sm font-normal leading-relaxed text-slate-600 dark:text-slate-300 line-clamp-2" x-text="item.excerpt"></p>
                                </div>

                                <div class="mt-4 pt-3 border-t border-black/5 dark:border-white/5 flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-emerald-700 dark:text-emerald-400 group-hover:underline">
                                    <span>Baca selengkapnya</span>
                                    <span aria-hidden="true" class="transition-transform duration-200 group-hover:translate-x-1">&rarr;</span>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>

                <!-- TOMBOL MUAT LEBIH BANYAK & COUNTER (OUTLINE HIJAU) -->
                <div class="mt-10 md:mt-14 pt-8 border-t border-black/5 dark:border-white/5 flex flex-col items-center justify-center gap-3 text-center w-full" x-show="filtered.length > 0">
                    <div x-show="hasMore">
                        <button @click="loadMore()" 
                                class="px-8 py-2.5 min-h-[44px] inline-flex items-center justify-center gap-2 btn-active-scale rounded-xl text-sm font-semibold transition-colors border-2 border-emerald-600 text-emerald-700 dark:border-emerald-500/80 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 dark:hover:text-white active:scale-95 cursor-pointer">
                            <span>Muat lebih banyak</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium whitespace-nowrap">
                        Menampilkan <span x-text="Math.min(filtered.length, limit)" class="font-bold text-slate-700 dark:text-slate-200"></span> dari <span x-text="filtered.length" class="font-bold text-slate-700 dark:text-slate-200"></span> warta
                    </p>
                </div>
            </div>

            <!-- EMPTY STATE KETIKA FILTER / PENCARIAN KOSONG -->
            <div class="py-16 sm:py-20 px-6 text-center max-w-lg mx-auto bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/10 rounded-3xl shadow-sm" x-show="filtered.length === 0" x-cloak>
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 mx-auto flex items-center justify-center mb-4 border border-emerald-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8"/></svg>
                </div>
                <h3 class="font-serif text-2xl font-bold mb-2" style="color:var(--text-primary)">Belum ada warta untuk kategori ini</h3>
                <p class="text-sm sm:text-base mb-6 leading-relaxed text-slate-600 dark:text-slate-400">
                    Coba gunakan kata kunci pencarian lain atau pilih kategori warta yang berbeda.
                </p>
                <button @click="search = ''; category = 'Semua'; limit = 9" 
                        class="px-6 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-all inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm active:scale-95 cursor-pointer">
                    Lihat semua warta
                </button>
            </div>
        @endif

        <!-- SECTION: CTA KIRIM WARTA (DENGAN LATAR KARTU ELEGAN) -->
        <section class="mt-14 sm:mt-20 relative overflow-hidden rounded-2xl sm:rounded-3xl p-6 sm:p-10 md:p-12 border border-black/10 dark:border-white/10 bg-white dark:bg-[#070D09] shadow-sm text-left">
            <!-- Ambient Glow -->
            <div class="pointer-events-none absolute -right-16 -bottom-16 w-72 h-72 rounded-full bg-emerald-500/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-16 -top-16 w-56 h-56 rounded-full bg-emerald-600/5 blur-2xl"></div>

            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20 mb-4">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8"/></svg>
                    <span>Kirim Kabar & Liputan</span>
                </div>
                <h2 class="font-serif text-2xl sm:text-3xl md:text-4xl font-medium mb-3 leading-tight" style="color: var(--text-primary)">
                    Ada kabar untuk dibagikan?
                </h2>
                <p class="text-sm sm:text-base leading-relaxed text-slate-600 dark:text-slate-300 mb-6 font-normal">
                    Kirim informasi kegiatan dan warta ranting ke redaksi agar dimuat di Warta NU Kalisari.
                </p>
                @if(config('site.whatsapp'))
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="https://wa.me/{{ config('site.whatsapp') }}?text={{ urlencode('Assalamu\'alaikum, saya ingin mengirimkan informasi kegiatan untuk warta NU Kalisari.') }}" target="_blank" rel="noopener noreferrer" 
                       class="w-full sm:w-auto inline-flex btn-active-scale items-center justify-center gap-2.5 px-6 py-3 min-h-[44px] rounded-xl text-sm font-semibold transition-all bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm shadow-emerald-950/20 active:scale-95 cursor-pointer">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <span>Hubungi via WhatsApp</span>
                    </a>
                </div>
                @endif
            </div>
        </section>

        </div>
    </main>

    @include('layouts.public-footer')
</body>
</html>

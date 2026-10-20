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
        @keyframes lbIn { 
            from { opacity: 0; transform: scale(0.96); } 
            to { opacity: 1; transform: scale(1); } 
        }
        .lb-enter { animation: lbIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>

<body class="font-sans antialiased flex flex-col min-h-screen"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    @php
        $galeriItems = $galeris->map(function($g) {
            $photoUrl = $g->photo ?: $g->image;
            if ($photoUrl && !str_starts_with($photoUrl, 'http')) {
                $photoUrl = asset('storage/' . ltrim($photoUrl, '/'));
            } elseif (!$photoUrl) {
                $photoUrl = asset('storage/galeri/placeholder.svg');
            }

            return [
                'id'          => $g->id,
                'title'       => $g->title ?? 'Dokumentasi Kegiatan',
                'category'    => $g->category ?: 'Umum',
                'photo'       => $photoUrl,
                'description' => $g->description ?? null,
                'date'        => optional($g->created_at)->isoFormat('D MMM Y') ?? '',
            ];
        })->values()->toArray();

        $logoMap = collect(config('organisasi.banom'))->mapWithKeys(function($b) {
            return [$b['singkatan'] => asset('storage/logo-organisasi/' . $b['logo'])];
        })->toArray();
    @endphp

    <main class="flex-grow pt-[116px] md:pt-[132px] pb-16 md:pb-20 w-full"
          x-data="galApp({{ Illuminate\Support\Js::from($galeriItems) }}, {{ Illuminate\Support\Js::from($logoMap) }}, '{{ request('category', 'Semua') }}')"
          x-init="init()">

        <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 w-full" style="overflow-x: clip">

            <!-- HEADER -->
            <x-page-header
                eyebrow="ARSIP VISUAL"
                title="Dokumentasi"
                subtitle="Arsip visual langkah, karya, dan pengabdian seluruh elemen NU Kalisari."
                :count="count($galeriItems)"
                unit="foto"
                count-expression="filtered.length"
            />

            @if($galeris->isEmpty())
                <!-- EMPTY STATE (Data total dari DB kosong) -->
                <div class="py-16 sm:py-20 px-6 rounded-3xl bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/10 text-center max-w-lg mx-auto shadow-sm">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 mx-auto flex items-center justify-center mb-4 border border-emerald-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-2xl font-medium mb-2" style="color:var(--text-primary)">Belum ada foto dokumentasi</h3>
                    <p class="text-sm sm:text-base leading-relaxed text-slate-600 dark:text-slate-400">
                        Arsip dokumentasi kegiatan NU Kalisari akan segera ditampilkan di sini.
                    </p>
                </div>
            @else
                <!-- FILTERS & PENCARIAN -->
                <div class="mb-8 md:mb-10 flex flex-col xl:flex-row items-start xl:items-center justify-between gap-4 xl:gap-6 w-full">
                    <!-- Chip filter kategori dinamis dari DB: scroll horizontal snap tanpa scrollbar tampak -->
                    <div class="relative w-full xl:w-auto overflow-hidden">
                        <div class="flex gap-2 overflow-x-auto scrollbar-none whitespace-nowrap flex-nowrap w-full xl:w-auto pb-1.5 pt-0.5 px-0.5 snap-x snap-mandatory pr-10 xl:pr-0"
                             style="scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;">
                            <template x-for="c in categories" :key="c">
                                <button @click="setCategory(c)"
                                        class="snap-start px-4 py-2 min-h-[44px] rounded-full text-xs sm:text-sm font-medium transition-all border shrink-0 cursor-pointer active:scale-95 flex items-center select-none"
                                        :class="activeCategory === c 
                                            ? 'bg-emerald-600 border-emerald-600 text-white font-semibold shadow-sm shadow-emerald-900/20' 
                                            : 'bg-white dark:bg-[#070D09] border-black/10 dark:border-white/10 hover:border-emerald-500/40 text-slate-700 dark:text-slate-300'"
                                        :aria-pressed="activeCategory === c">
                                    <span x-text="c"></span>
                                </button>
                            </template>
                        </div>
                        <!-- Fade mask tepi kanan pada mobile/tablet -->
                        <div class="pointer-events-none absolute right-0 top-0 bottom-1.5 w-12 xl:hidden"
                             style="background: linear-gradient(to left, var(--bg-base) 45%, var(--bg-base-transparent));"></div>
                    </div>

                    <!-- Kolom Pencarian -->
                    <div class="relative w-full xl:w-72 shrink-0">
                        <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" x-model.debounce.200ms="search" @input="page = 1" placeholder="Cari foto..."
                               class="w-full pl-10 pr-9 py-2.5 min-h-[44px] text-sm bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 transition-all text-slate-900 dark:text-slate-100 placeholder:text-slate-400">
                        <button type="button" 
                                x-show="search.length > 0" 
                                @click="search = ''; page = 1" 
                                x-cloak
                                aria-label="Hapus pencarian"
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 flex items-center justify-center rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-black/5 dark:hover:bg-white/10 cursor-pointer transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <!-- GRID FOTO: Dinamis dan rapi untuk data sedikit (1-3 foto) maupun banyak (30+ foto) -->
                <div :class="{
                        'max-w-xl mx-auto': filtered.length === 1,
                        'grid grid-cols-1 sm:grid-cols-2 max-w-3xl mx-auto gap-4 sm:gap-6': filtered.length === 2,
                        'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6': filtered.length === 3,
                        'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6': filtered.length >= 4
                     }"
                     class="w-full transition-all duration-300"
                     x-show="filtered.length > 0">
                    <template x-for="(item, idx) in visibleItems" :key="item.id">
                        <div :class="{
                                'sm:col-span-2 lg:col-span-2 lg:row-span-2': idx === 0 && filtered.length >= 4,
                                'col-span-1': idx !== 0 || filtered.length < 4
                             }"
                             class="w-full">
                            <a href="javascript:void(0)" 
                               @click.prevent="openLb(idx, $event.currentTarget)"
                               x-data="{ imgLoaded: false }"
                               :class="{
                                   'aspect-[4/3] sm:aspect-[16/9] lg:aspect-auto h-full min-h-[260px] sm:min-h-[340px] lg:min-h-[460px]': idx === 0 && filtered.length >= 4,
                                   'aspect-[4/3]': idx !== 0 || filtered.length < 4
                               }"
                               class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#063323] via-[#031d14] to-[#010e09] border border-black/10 dark:border-white/10 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition-all duration-300 hover:-translate-y-1 motion-reduce:hover:translate-y-0 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#070D09] w-full block">
                                
                                <!-- Placeholder shimmer / skeleton loading saat memuat: gradien hijau tua dengan ikon kamera -->
                                <div x-show="!imgLoaded" 
                                     class="absolute inset-0 bg-gradient-to-br from-[#064e3b] via-[#032d21] to-[#011a12] animate-pulse flex items-center justify-center z-0">
                                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                                        <svg class="w-7 h-7 text-emerald-400/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Foto asli atau placeholder -->
                                <img :src="item.photo" 
                                     :alt="item.title || 'Dokumentasi NU Kalisari'" 
                                     @load="imgLoaded = true"
                                     x-on:error="imgLoaded = true"
                                     class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105 motion-reduce:group-hover:scale-100 motion-reduce:transition-none"
                                     :class="imgLoaded ? 'opacity-100' : 'opacity-0'"
                                     loading="lazy">

                                <!-- Badge kategori di pojok kiri atas -->
                                <div class="absolute top-3 left-3 z-20 pointer-events-none">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold tracking-wider uppercase bg-black/65 backdrop-blur-md text-emerald-300 border border-emerald-500/30 shadow-sm" x-text="item.category"></span>
                                </div>

                                <!-- Ikon perbesar muncul di pojok kanan atas saat hover -->
                                <div class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 backdrop-blur-md text-white flex items-center justify-center border border-white/20 opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-90 group-hover:scale-100 z-20 pointer-events-none shadow-md">
                                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                    </svg>
                                </div>

                                <!-- Caption overlay: gradien gelap di bawah untuk teks, tanggal dan judul di bawah -->
                                <div class="absolute inset-0 flex flex-col justify-end p-4 sm:p-5 text-left pointer-events-none z-10"
                                     style="background: linear-gradient(to top, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.45) 45%, rgba(0,0,0,0) 100%);">
                                    <div class="flex items-center gap-1.5 text-xs text-white/80 mb-1" x-show="item.date">
                                        <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span x-text="item.date" class="font-normal"></span>
                                    </div>
                                    <h3 class="text-white font-serif text-lg sm:text-xl font-medium leading-snug line-clamp-2 transition-transform duration-300 group-hover:-translate-y-0.5" 
                                        :class="{ 'sm:text-2xl lg:text-3xl': idx === 0 && filtered.length >= 4 }"
                                        x-text="item.title"></h3>
                                </div>
                            </a>
                        </div>
                    </template>
                </div>

                <!-- EMPTY STATE KETIKA FILTER / PENCARIAN KOSONG -->
                <div class="py-16 sm:py-20 px-6 rounded-3xl bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/10 text-center max-w-lg mx-auto shadow-sm" x-show="filtered.length === 0" x-cloak>
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 mx-auto flex items-center justify-center mb-4 border border-emerald-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-2xl font-medium mb-2" style="color:var(--text-primary)">Belum ada foto untuk kategori ini</h3>
                    <p class="text-sm sm:text-base mb-6 leading-relaxed text-slate-600 dark:text-slate-400">
                        Coba gunakan kata kunci pencarian lain atau pilih kategori dokumentasi yang berbeda.
                    </p>
                    <button @click="search = ''; activeCategory = 'Semua'; page = 1" 
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-full text-sm font-semibold transition-all bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm active:scale-95 cursor-pointer">
                        <span>Lihat semua foto</span>
                    </button>
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
                        Menampilkan <span x-text="visibleItems.length" class="font-bold text-slate-700 dark:text-slate-200"></span> dari <span x-text="filtered.length" class="font-bold text-slate-700 dark:text-slate-200"></span> foto
                    </p>
                </div>
            @endif

            <!-- SECTION: CTA KIRIM DOKUMENTASI (KARTU ELEGAN DENGAN LATAR TERPADU) -->
            <section class="mt-14 sm:mt-20 relative overflow-hidden rounded-2xl sm:rounded-3xl p-6 sm:p-10 md:p-12 border border-black/10 dark:border-white/10 bg-white dark:bg-[#070D09] shadow-sm text-left">
                <!-- Aksen glow halus di sudut -->
                <div class="pointer-events-none absolute -right-16 -bottom-16 w-72 h-72 rounded-full bg-emerald-500/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -left-16 -top-16 w-56 h-56 rounded-full bg-emerald-600/5 blur-2xl"></div>

                <div class="relative z-10 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20 mb-4">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Kontribusi Arsip</span>
                    </div>
                    <h2 class="font-serif text-2xl sm:text-3xl md:text-4xl font-medium mb-3 leading-tight" style="color: var(--text-primary)">
                        Punya dokumentasi kegiatan?
                    </h2>
                    <p class="text-sm sm:text-base leading-relaxed text-slate-600 dark:text-slate-300 mb-6 font-normal">
                        Kirim foto kegiatan ke pengurus agar masuk ke arsip dokumentasi resmi NU Kalisari.
                    </p>
                    @if(config('site.whatsapp'))
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="https://wa.me/{{ config('site.whatsapp') }}?text={{ urlencode('Assalamu\'alaikum, saya ingin mengirimkan foto dokumentasi kegiatan untuk arsip NU Kalisari.') }}" target="_blank" rel="noopener noreferrer" 
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

    <!-- LIGHTBOX FULLSCREEN: foto, judul, tanggal, kategori, dan deskripsi (bila ada) -->
    <div id="nu-lightbox" 
         class="fixed inset-0 z-50 bg-black/92 backdrop-blur-md flex flex-col justify-between items-center p-3 sm:p-6 select-none opacity-0 pointer-events-none transition-opacity duration-200 ease-out"
         role="dialog" 
         aria-modal="true" 
         aria-label="Penampil Foto Dokumentasi"
         aria-hidden="true"
         tabindex="-1">
        
        <!-- Top Controls Bar: penghitung foto dan tombol tutup (×) kanan atas -->
        <div id="lb-counter-wrap" class="w-full flex items-center justify-between z-50 max-w-5xl">
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm text-xs font-medium text-white/90 border border-white/10">
                <span id="lb-counter" class="font-bold text-emerald-400"></span>
                <span class="text-white/40">·</span>
                <span id="lb-top-cat" class="uppercase tracking-wider text-[11px] text-slate-300 font-semibold"></span>
            </div>
            
            <button id="lb-close" 
                    type="button"
                    aria-label="Tutup penampil foto" 
                    class="w-11 h-11 min-w-[44px] min-h-[44px] flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 active:scale-95 text-white transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-400 border border-white/10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Center Stage: Foto object-contain maks 76vh & Navigasi Panah Kiri/Kanan di Sisi -->
        <div class="relative w-full max-w-5xl flex-grow flex items-center justify-center my-2 sm:my-3 overflow-hidden pointer-events-none">
            <!-- Tombol Kiri (Desktop / Tablet) -->
            <button id="lb-prev" 
                    type="button"
                    aria-label="Foto sebelumnya" 
                    class="pointer-events-auto absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 w-11 h-11 min-w-[44px] min-h-[44px] flex items-center justify-center rounded-full bg-black/50 hover:bg-black/75 active:scale-95 text-white transition-all z-50 cursor-pointer shadow-xl backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-emerald-400 border border-white/15">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Image Wrap -->
            <div id="lb-img-wrap" class="pointer-events-auto relative max-h-[72vh] sm:max-h-[76vh] max-w-full flex items-center justify-center rounded-xl overflow-hidden shadow-2xl">
                <img id="lb-img" 
                     src="" 
                     alt="" 
                     class="max-h-[72vh] sm:max-h-[76vh] w-auto max-w-full object-contain rounded-xl select-none"
                     draggable="false">
            </div>

            <!-- Tombol Kanan (Desktop / Tablet) -->
            <button id="lb-next" 
                    type="button"
                    aria-label="Foto selanjutnya" 
                    class="pointer-events-auto absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 w-11 h-11 min-w-[44px] min-h-[44px] flex items-center justify-center rounded-full bg-black/50 hover:bg-black/75 active:scale-95 text-white transition-all z-50 cursor-pointer shadow-xl backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-emerald-400 border border-white/15">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <!-- Caption di Bagian Bawah Lightbox: kategori · tanggal, judul serif, dan deskripsi (bila ada) -->
        <div class="w-full max-w-3xl text-center z-50 pb-1 pointer-events-auto">
            <div class="flex items-center justify-center gap-2 mb-1 text-xs uppercase tracking-wider font-semibold text-white/80">
                <span id="lb-cat" class="text-emerald-400"></span>
                <span class="text-white/40">·</span>
                <span id="lb-date" class="text-slate-300 font-normal"></span>
            </div>
            <h2 id="lb-title" class="font-serif text-lg sm:text-2xl font-medium text-white leading-snug line-clamp-2 px-4"></h2>
            <!-- Deskripsi tambahan bila ada -->
            <p id="lb-desc" class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto mt-1.5 px-4 leading-relaxed line-clamp-3 font-light" style="display:none;"></p>
            <p class="text-[11px] text-white/40 mt-1.5 md:hidden select-none">Geser layar ke kiri atau kanan untuk berpindah foto</p>
        </div>
    </div>

    @include('layouts.public-footer')

    <script>
    function galApp(data, logos, initialCat) {
        // Ekstrak kategori unik dari data database
        const dbCats = Array.from(new Set(data.map(i => (i.category || '').trim()).filter(Boolean)));
        const defaultOrder = ['Semua', 'Muslimat', 'Fatayat', 'GP Ansor', 'IPNU', 'IPPNU', 'PMII', 'Umum'];
        
        // Urutkan kategori berdasarkan urutan banom umum yang ada di DB, lalu tambahkan sisa kategori lain dari DB
        const uniqueCats = ['Semua'];
        defaultOrder.forEach(c => {
            if (c !== 'Semua' && dbCats.includes(c) && !uniqueCats.includes(c)) {
                uniqueCats.push(c);
            }
        });
        dbCats.forEach(c => {
            if (!uniqueCats.includes(c)) {
                uniqueCats.push(c);
            }
        });

        return {
            allItems: data,
            logos: logos,
            categories: uniqueCats,
            activeCategory: uniqueCats.includes(initialCat) ? initialCat : 'Semua',
            search: '',
            perPage: 12,
            page: 1,

            get filtered() {
                let r = this.allItems;
                if (this.activeCategory !== 'Semua') {
                    r = r.filter(i => (i.category || 'Umum') === this.activeCategory);
                }
                if (this.search && this.search.trim()) {
                    const q = this.search.trim().toLowerCase();
                    r = r.filter(i => 
                        (i.title && i.title.toLowerCase().includes(q)) || 
                        (i.category && i.category.toLowerCase().includes(q)) ||
                        (i.description && i.description.toLowerCase().includes(q))
                    );
                }
                return r;
            },

            get visibleItems() {
                return this.filtered.slice(0, this.page * this.perPage);
            },

            get hasMore() {
                return this.visibleItems.length < this.filtered.length;
            },

            loadMore() {
                this.page++;
            },

            setCategory(cat) {
                this.activeCategory = cat;
                this.page = 1;
            },

            openLb(idx, triggerEl) {
                window._lbItems = this.filtered;
                window._lbOpen(idx, triggerEl);
            },

            init() {
                this.$watch('search', () => { this.page = 1; });
            }
        };
    }
    </script>

    <script>
    (function () {
        const lb        = document.getElementById('nu-lightbox');
        const imgEl     = document.getElementById('lb-img');
        const titleEl   = document.getElementById('lb-title');
        const descEl    = document.getElementById('lb-desc');
        const catEl     = document.getElementById('lb-cat');
        const topCatEl  = document.getElementById('lb-top-cat');
        const dateEl    = document.getElementById('lb-date');
        const counterEl = document.getElementById('lb-counter');
        const wrapEl    = document.getElementById('lb-img-wrap');
        const prevBtn   = document.getElementById('lb-prev');
        const nextBtn   = document.getElementById('lb-next');
        const closeBtn  = document.getElementById('lb-close');
        let cur = 0;
        let lastActiveElement = null;
        let isOpen = false;
        let isHistoryPushed = false;

        function show(idx) {
            const items = window._lbItems || [];
            if (!items.length) return;
            cur = Math.max(0, Math.min(idx, items.length - 1));
            const it = items[cur];

            imgEl.src             = it.photo;
            imgEl.alt             = it.title || 'Dokumentasi NU Kalisari';
            titleEl.textContent   = it.title || '';
            catEl.textContent     = it.category || '';
            if (topCatEl) topCatEl.textContent = it.category || '';
            dateEl.textContent    = it.date || '';
            counterEl.textContent = (cur + 1) + ' / ' + items.length;

            if (descEl) {
                if (it.description && it.description.trim()) {
                    descEl.textContent = it.description.trim();
                    descEl.style.display = 'block';
                } else {
                    descEl.textContent = '';
                    descEl.style.display = 'none';
                }
            }

            if (prevBtn) {
                prevBtn.style.opacity = cur === 0 ? '0.25' : '1';
                prevBtn.style.pointerEvents = cur === 0 ? 'none' : 'auto';
                prevBtn.setAttribute('aria-disabled', cur === 0 ? 'true' : 'false');
            }
            if (nextBtn) {
                nextBtn.style.opacity = cur >= items.length - 1 ? '0.25' : '1';
                nextBtn.style.pointerEvents = cur >= items.length - 1 ? 'none' : 'auto';
                nextBtn.setAttribute('aria-disabled', cur >= items.length - 1 ? 'true' : 'false');
            }

            if (wrapEl) {
                wrapEl.classList.remove('lb-enter');
                void wrapEl.offsetWidth;
                wrapEl.classList.add('lb-enter');
            }

            // Preload foto sebelum dan sesudahnya
            if (cur > 0 && items[cur - 1] && items[cur - 1].photo) {
                const prevImg = new Image();
                prevImg.src = items[cur - 1].photo;
            }
            if (cur < items.length - 1 && items[cur + 1] && items[cur + 1].photo) {
                const nextImg = new Image();
                nextImg.src = items[cur + 1].photo;
            }
        }

        window._lbOpen = function(idx, triggerEl) {
            if (!lb) return;
            lastActiveElement = triggerEl || document.activeElement;
            isOpen = true;
            lb.classList.remove('opacity-0', 'pointer-events-none');
            lb.classList.add('opacity-100', 'pointer-events-auto');
            lb.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

            // Push history state agar tombol Back di browser/HP menutup lightbox bukan meninggalkan halaman
            try {
                if (!isHistoryPushed) {
                    history.pushState({ nuLightbox: true }, '');
                    isHistoryPushed = true;
                }
            } catch (e) {}

            show(idx);

            setTimeout(() => {
                if (closeBtn) closeBtn.focus();
            }, 50);
        };

        function closeLb(fromHistory = false) {
            if (!lb || !isOpen) return;
            isOpen = false;
            lb.classList.remove('opacity-100', 'pointer-events-auto');
            lb.classList.add('opacity-0', 'pointer-events-none');
            lb.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';

            // Jika ditutup via tombol/Esc/klik latar, dan history sempat di-push, kembalikan history
            if (!fromHistory && isHistoryPushed) {
                isHistoryPushed = false;
                try {
                    history.back();
                } catch (e) {}
            } else {
                isHistoryPushed = false;
            }

            // Kembalikan fokus ke elemen kartu pemicu
            if (lastActiveElement && typeof lastActiveElement.focus === 'function') {
                lastActiveElement.focus();
            }
        }

        // Tangani tombol Back browser / HP (Android / iOS gesture)
        window.addEventListener('popstate', function (e) {
            if (isOpen) {
                closeLb(true);
            }
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                closeLb(false);
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (cur > 0) show(cur - 1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const n = (window._lbItems || []).length;
                if (cur < n - 1) show(cur + 1);
            });
        }

        // Klik latar menutup modal (kecuali jika klik tepat pada kontrol / foto)
        if (lb) {
            lb.addEventListener('click', function(e) {
                const isControl = e.target.closest('#lb-img-wrap') || 
                                  e.target.closest('#lb-prev') || 
                                  e.target.closest('#lb-next') || 
                                  e.target.closest('#lb-close') ||
                                  e.target.closest('#lb-counter-wrap');
                if (!isControl) {
                    closeLb(false);
                }
            });
        }

        // Keyboard navigasi: Esc tutup, ← → pindah foto, dan Focus trap
        document.addEventListener('keydown', function(e) {
            if (!lb || !isOpen) return;
            const items = window._lbItems || [];
            if (e.key === 'Escape') {
                e.preventDefault();
                closeLb(false);
                return;
            }
            if (e.key === 'ArrowLeft' && cur > 0) {
                e.preventDefault();
                show(cur - 1);
                return;
            }
            if (e.key === 'ArrowRight' && cur < items.length - 1) {
                e.preventDefault();
                show(cur + 1);
                return;
            }
            if (e.key === 'Tab') {
                const focusable = [closeBtn, prevBtn, nextBtn].filter(el => el && el.style.pointerEvents !== 'none' && el.offsetParent !== null);
                if (focusable.length === 0) return;
                const first = focusable[0];
                const last = focusable[focusable.length - 1];
                if (e.shiftKey) {
                    if (document.activeElement === first) {
                        e.preventDefault();
                        last.focus();
                    }
                } else {
                    if (document.activeElement === last) {
                        e.preventDefault();
                        first.focus();
                    }
                }
            }
        });

        // Touch swipe navigasi pada mobile (swipe kiri = next, swipe kanan = prev)
        let touchStartX = 0;
        let touchStartY = 0;

        lb.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
            }
        }, { passive: true });

        lb.addEventListener('touchend', function(e) {
            if (e.changedTouches.length === 1) {
                const touchEndX = e.changedTouches[0].clientX;
                const touchEndY = e.changedTouches[0].clientY;
                const diffX = touchEndX - touchStartX;
                const diffY = touchEndY - touchStartY;

                // Threshold geser horizontal minimal 40px dan lebih dominan dibanding vertikal
                if (Math.abs(diffX) > 40 && Math.abs(diffX) > Math.abs(diffY)) {
                    const items = window._lbItems || [];
                    if (diffX < 0) {
                        // Geser ke kiri -> Foto berikutnya
                        if (cur < items.length - 1) show(cur + 1);
                    } else {
                        // Geser ke kanan -> Foto sebelumnya
                        if (cur > 0) show(cur - 1);
                    }
                }
            }
        }, { passive: true });
    })();
    </script>

</body>
</html>

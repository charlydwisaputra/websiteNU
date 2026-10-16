<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('organisasi.nama') }} — Beranda</title>
    <x-seo-meta
        :title="config('organisasi.nama') . ' — Beranda'"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.theme-script')

    

    <style>
        /* SIMPLE GRADIENT FADE ZONE */
        .hero-fade-zone {
            height: clamp(48px, 5vw, 96px);
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            pointer-events: none;
            z-index: 2;
            background: linear-gradient(to bottom, var(--bg-base-transparent), var(--bg-base));
        }
        
        /* Warm tint for light mode to prevent muddy gray */
        .blur-gradient-tint {
            position: absolute;
            inset: 0;
            background: #eef8f4;
            mask-image: linear-gradient(to bottom, 
                rgba(0,0,0,0) 0%, 
                rgba(0,0,0,0.2) 30%, 
                rgba(0,0,0,0.4) 50%, 
                rgba(0,0,0,0.2) 70%, 
                rgba(0,0,0,0) 90%
            );
            -webkit-mask-image: linear-gradient(to bottom, 
                rgba(0,0,0,0) 0%, 
                rgba(0,0,0,0.2) 30%, 
                rgba(0,0,0,0.4) 50%, 
                rgba(0,0,0,0.2) 70%, 
                rgba(0,0,0,0) 90%
            );
        }
        .dark .blur-gradient-tint {
            display: none;
        }

        /* Fallbacks & Performance */
        @media (max-width: 767px), (prefers-reduced-motion: reduce) {
            .blur-layer {
                display: none !important;
            }
        }
        @supports not (backdrop-filter: blur(1px)) {
            .blur-layer {
                display: none !important;
            }
        }
        
        /* OVERSCROLL FIX UNTUK HERO */
        html, body {
            overscroll-behavior-y: none;
            background-color: #000 !important; /* Warna gelap matching hero */
        }
        main {
            background-color: var(--bg-base);
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body class="selection:bg-emerald-500/30 selection:text-emerald-900 dark:selection:text-emerald-100">

    @include("layouts.public-navigation")

    <main>
        <!-- HERO -->
        <section id="hero-section" class="relative w-full min-h-[70svh] md:min-h-[75svh] flex items-center justify-start" style="background: var(--bg-surface)">
            
            <!-- MEDIA LAYER -->
            <div class="absolute inset-0 z-0 overflow-hidden">
                <video id="heroVideo" autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover object-[center_35%] md:object-[center_30%]" poster="https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1920&auto=format&fit=crop">
                    <source src="{{ asset('videos/village_sample.mp4') }}" type="video/mp4">
                </video>
                <div class="absolute top-0 inset-x-0 h-[35vh] bg-gradient-to-b from-black/60 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
            </div>
            
            <!-- FADE ZONE -->
            <div class="hero-fade-zone"></div>


            <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-start pt-32 pb-[clamp(48px,5vw,96px)] text-white">
                <p class="text-xs uppercase reveal-up active tracking-[0.2em] font-medium mb-3 text-emerald-400">
                    Ekosistem Digital
                </p>
                <h1 class="font-serif text-[clamp(2.75rem,10vw,7rem)] font-medium leading-[1.1] mb-5 max-w-4xl reveal-up delay-100">
                    KALISARI
                </h1>
                <p class="text-[clamp(1.125rem,2vw,1.25rem)] reveal-up delay-200 font-light opacity-90 max-w-[46ch] leading-relaxed mb-8">
                    Tumbuh Bersama. Bergerak Bersama.<br>{{ config('organisasi.deskripsi') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-3 reveal-up delay-300">
                    <a href="#warta" class="w-full sm:w-auto inline-flex btn-active-scale items-center justify-center px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-medium transition-colors shadow-lg shadow-emerald-900/20">
                        Jelajahi Warta
                    </a>
                    <a href="#agenda" class="w-full sm:w-auto inline-flex btn-active-scale items-center justify-center px-8 py-3.5 rounded-xl text-sm font-medium transition-colors border border-white/30 hover:border-white/60 text-white hover:bg-white/10">
                        Lihat Agenda
                    </a>
                </div>
            </div>
        </section>

        {{-- JADWAL SALAT & HIJRIYAH --}}
        <x-prayer-widget :prayer="$prayer" />

        <!-- PENGANTAR -->
        <section class="py-16 md:py-24 border-b border-black/5 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl reveal-up">
                    <h2 class="font-serif text-[clamp(2rem,5vw,3.5rem)] font-medium leading-tight mb-8" style="color: var(--text-primary)">
                        Membangun ruang yang memberdayakan masyarakat melalui nilai Ahlussunnah wal Jamaah
                    </h2>
                    <p class="text-base md:text-lg opacity-80 leading-relaxed mb-8 max-w-2xl" style="color: var(--text-secondary)">
                        Pengurus Ranting NU Kalisari mengoordinasikan seluruh potensi umat di tingkat ranting. Kami merekam jejak langkah dan menyatukan visi Nahdlatul Ulama untuk masyarakat yang mandiri dan berkeadaban.
                    </p>
                    <div class="flex flex-wrap gap-2 text-[14px] font-medium">
                        @foreach(config('organisasi.lokasi') as $lokasi)
                            <span class="px-3 py-1.5 bg-slate-200 dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-lg border border-slate-300 dark:border-slate-700 shadow-sm text-[13px] md:text-[14px] font-medium">{{ $lokasi }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- EKOSISTEM -->
        <section class="py-16 md:py-24 bg-slate-50/50 dark:bg-slate-900/20 border-b border-black/5 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12 reveal-up">
                    <h2 class="font-serif text-[clamp(2.5rem,5vw,4rem)] font-medium leading-tight" style="color: var(--text-primary)">Ekosistem</h2>
                </div>

                <div class="-mx-4 px-4 sm:mx-0 sm:px-0 flex md:grid md:grid-cols-2 lg:grid-cols-3 gap-6 overflow-x-auto md:overflow-visible snap-x snap-mandatory overscroll-x-contain scrollbar-none pb-4 md:pb-0 mb-12 reveal-up" style="scroll-snap-type: x mandatory; overscroll-behavior-x: contain;">
                    @foreach(array_slice(config('organisasi.banom'), 0, 3) as $banom)
                    <div class="flex-[0_0_80%] md:flex-initial w-[80%] md:w-auto shrink-0 snap-start p-6 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 hover:border-emerald-500/30 transition-colors card-hover" style="scroll-snap-align: start;">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center mb-6 text-emerald-600 dark:text-emerald-400 font-medium text-xl overflow-hidden">
                            @if(isset($banom['logo']))
                                <img src="{{ asset('storage/logo-organisasi/' . $banom['logo']) }}" alt="{{ $banom['nama'] }}" class="w-full h-full object-contain p-1.5 dark:brightness-200 dark:contrast-100" loading="lazy">
                            @else
                                {{ substr($banom['nama'], 0, 1) }}
                            @endif
                        </div>
                        <h3 class="font-serif text-2xl font-medium mb-3" style="color: var(--text-primary)">{{ $banom['nama'] }}</h3>
                        <p class="text-sm leading-relaxed" style="color: var(--text-secondary)">{{ $banom['deskripsi'] }}</p>
                        
                        @php
                            $koordinator = $banom['koordinator'] ?? $banom['coordinator'] ?? null;
                            $hasKoordinator = !empty($koordinator) && !str_contains($koordinator, 'ISI:');
                        @endphp
                        @if($hasKoordinator)
                        <div class="mt-4 pt-4 border-t border-black/5 dark:border-white/5 text-xs" style="color: var(--text-muted)">
                            Koordinator: {{ $koordinator }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                
                <div>
                    <a href="{{ route('ekosistem') }}" class="text-sm font-medium transition-colors hover:text-emerald-600 dark:hover:text-emerald-400" style="color: var(--text-primary)">
                        <span class="link-underline">Lihat Seluruh Ekosistem &rarr;</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- AGENDA TERDEKAT -->
        <section id="agenda" class="py-16 md:py-24 border-b border-black/5 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12 reveal-up">
                    <h2 class="font-serif text-[clamp(2.5rem,5vw,4rem)] font-medium leading-tight" style="color: var(--text-primary)">Agenda Terdekat</h2>
                </div>

                @if($agendas->isEmpty())
                    <div class="py-12 px-6 rounded-2xl bg-black/5 dark:bg-white/5 text-center border border-black/5 dark:border-white/5">
                        <p class="text-base" style="color: var(--text-secondary)">Belum ada agenda terjadwal.</p>
                    </div>
                @else
                    <div class="-mx-4 px-4 sm:mx-0 sm:px-0 flex md:grid md:grid-cols-3 gap-6 overflow-x-auto md:overflow-visible snap-x snap-mandatory overscroll-x-contain scrollbar-none pb-4 md:pb-0 reveal-up" style="scroll-snap-type: x mandatory; overscroll-behavior-x: contain;">
                        @foreach($agendas as $agenda)
                        <div class="group flex-[0_0_80%] md:flex-initial w-[80%] md:w-auto shrink-0 snap-start p-6 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 hover:border-emerald-500/30 transition-colors card-hover flex flex-col justify-between" style="scroll-snap-align: start;">
                            <div>
                                <div class="w-14 h-14 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-500/20 flex flex-col items-center justify-center mb-6 shrink-0 text-emerald-600 dark:text-emerald-400">
                                    <span class="text-xl font-bold font-serif leading-none">{{ $agenda->tanggal_mulai->translatedFormat('d') }}</span>
                                    <span class="text-[11px] uppercase tracking-wider font-semibold mt-0.5 leading-none">{{ $agenda->tanggal_mulai->translatedFormat('M') }}</span>
                                </div>
                                <h3 class="font-serif text-2xl font-medium mb-3 line-clamp-2" style="color: var(--text-primary)">{{ $agenda->judul }}</h3>
                                @if($agenda->deskripsi)
                                    <p class="text-sm leading-relaxed line-clamp-2 mb-4" style="color: var(--text-secondary)">{{ $agenda->deskripsi }}</p>
                                @endif
                            </div>
                            <div class="pt-4 border-t border-black/5 dark:border-white/5 flex items-center gap-2 text-xs" style="color: var(--text-muted)">
                                <svg class="w-4 h-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="line-clamp-1">{{ $agenda->lokasi }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <!-- WARTA & KEGIATAN -->
        <section id="warta" class="py-16 md:py-24 border-b border-black/5 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12 reveal-up">
                    <h2 class="font-serif text-[clamp(2.5rem,5vw,4rem)] font-medium leading-tight" style="color: var(--text-primary)">Warta Terbaru</h2>
                </div>

                @if($beritas->isEmpty())
                    <div class="py-12 px-6 rounded-2xl bg-black/5 dark:bg-white/5 text-center border border-black/5 dark:border-white/5">
                        <p class="text-base" style="color: var(--text-secondary)">Belum ada warta yang dipublikasikan.</p>
                    </div>
                @else
                    @php $featured = $beritas->first(); @endphp

                    <!-- HERO -->
                    <div class="mb-12 reveal-up">
                        <a href="{{ route('berita.show', $featured->id) }}" class="group block md:grid md:grid-cols-2 gap-6 lg:gap-10 items-center">
                            <div class="w-full aspect-video overflow-hidden rounded-2xl mb-5 md:mb-0 bg-emerald-50 dark:bg-emerald-900/10 border border-black/5 dark:border-white/5">
                                @if($featured->image)
                                    <img src="{{ asset('storage/' . $featured->image) }}" alt="{{ $featured->title }}" class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-105" loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-emerald-600/30 text-4xl font-serif font-medium">
                                        {{ substr($featured->title, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col justify-center">
                                <div class="flex items-center gap-2 mb-3 text-[10px] font-medium uppercase tracking-widest" style="color: var(--text-muted)">
                                    <span>{{ $featured->category ?? 'Warta' }}</span>
                                    <span>·</span>
                                    <span>@tanggal($featured->created_at)</span>
                                </div>
                                <h3 class="font-serif text-3xl lg:text-4xl font-medium mb-4 leading-tight group-hover:text-emerald-600 transition-colors line-clamp-3" style="color: var(--text-primary)">
                                    {{ $featured->title }}
                                </h3>
                                <p class="text-base leading-relaxed line-clamp-3" style="color: var(--text-secondary)">
                                    {{ Str::limit(strip_tags($featured->content), 220) }}
                                </p>
                            </div>
                        </a>
                    </div>

                    <!-- COMPACT LIST -->
                    @if($beritas->count() > 1)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12 reveal-up" style="transition-delay: 100ms">
                        @foreach($beritas->skip(1) as $berita)
                        <a href="{{ route('berita.show', $berita->id) }}" class="flex items-center gap-4 group p-3 rounded-2xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors border border-transparent hover:border-black/5 dark:hover:border-white/5">
                            <div class="w-28 md:w-32 aspect-video shrink-0 rounded-xl overflow-hidden bg-emerald-50 dark:bg-emerald-900/10 border border-black/5 dark:border-white/5">
                                @if($berita->image)
                                    <img src="{{ asset('storage/' . $berita->image) }}" alt="{{ $berita->title }}" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-emerald-600/30 text-xl font-serif font-medium">
                                        {{ substr($berita->title, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col py-1 justify-center flex-grow">
                                <span class="text-[10px] font-medium uppercase tracking-widest mb-1.5" style="color: var(--text-muted)">
                                    @tanggal($berita->created_at)
                                </span>
                                <h4 class="font-serif text-base md:text-lg font-semibold leading-snug group-hover:text-emerald-600 transition-colors line-clamp-2" style="color: var(--text-primary)">
                                    {{ $berita->title }}
                                </h4>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                @endif

                <div>
                    <a href="{{ route('berita.index') }}" class="text-sm font-medium transition-colors hover:text-emerald-600 dark:hover:text-emerald-400" style="color: var(--text-primary)">
                        <span class="link-underline">Lihat Semua Warta &rarr;</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- DOKUMENTASI -->
        <section class="py-16 md:py-24 bg-slate-50/50 dark:bg-slate-900/20 border-b border-black/5 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12 reveal-up">
                    <h2 class="font-serif text-[clamp(2.5rem,5vw,4rem)] font-medium leading-tight" style="color: var(--text-primary)">Dokumentasi</h2>
                </div>

                @if($galeris->isEmpty())
                    <div class="py-12 px-6 rounded-2xl bg-black/5 dark:bg-white/5 text-center border border-black/5 dark:border-white/5">
                        <p class="text-base" style="color: var(--text-secondary)">Belum ada dokumentasi visual.</p>
                    </div>
                @else
                    <div class="-mx-4 px-4 sm:mx-0 sm:px-0 flex md:grid gap-3 md:gap-4 overflow-x-auto md:overflow-visible snap-x snap-mandatory overscroll-x-contain scrollbar-none pb-4 md:pb-0 mb-12 reveal-up dok-grid" style="scroll-snap-type: x mandatory; overscroll-behavior-x: contain;">
                        @foreach($galeris->take(8) as $galeri)
                        <a href="{{ route('galeri') }}" class="group flex-[0_0_75%] md:flex-initial w-[75%] md:w-auto shrink-0 snap-start aspect-[4/3] md:aspect-square card-hover overflow-hidden rounded-xl bg-emerald-50 dark:bg-emerald-900/10 border border-black/5 dark:border-white/5 relative" style="scroll-snap-align: start;">
                            @if($galeri->image || $galeri->photo)
                                <img src="{{ asset('storage/' . ($galeri->image ?? $galeri->photo)) }}" alt="{{ $galeri->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-emerald-600/30 text-2xl font-serif font-medium">
                                    {{ substr($galeri->title, 0, 2) }}
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                <span class="text-white text-sm font-medium line-clamp-1">{{ $galeri->title }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @endif
                
                <div>
                    <a href="{{ route('galeri') }}" class="text-sm font-medium transition-colors hover:text-emerald-600 dark:hover:text-emerald-400" style="color: var(--text-primary)">
                        <span class="link-underline">Buka Galeri Penuh &rarr;</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- PENGURUS -->
        <section id="pengurus" class="py-16 md:py-24 border-b border-black/5 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12 reveal-up">
                    <h2 class="font-serif text-[clamp(2.5rem,5vw,4rem)] font-medium leading-tight" style="color: var(--text-primary)">Pengurus</h2>
                </div>

                @if($penguruses->isEmpty())
                    <div class="py-12 px-6 rounded-2xl bg-black/5 dark:bg-white/5 text-center border border-black/5 dark:border-white/5 mb-12">
                        <p class="text-base" style="color: var(--text-secondary)">Belum ada data pengurus yang ditampilkan.</p>
                    </div>
                @else
                    <div class="-mx-4 px-4 sm:mx-0 sm:px-0 flex md:grid md:grid-cols-4 gap-4 md:gap-6 overflow-x-auto md:overflow-visible snap-x snap-mandatory overscroll-x-contain scrollbar-none pb-4 md:pb-0 mb-12 reveal-up" style="scroll-snap-type: x mandatory; overscroll-behavior-x: contain;">
                        @foreach($penguruses as $pengurus)
                        <div class="group flex-[0_0_40%] md:flex-initial w-[40%] md:w-auto shrink-0 snap-start p-5 sm:p-6 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 hover:border-emerald-500/30 transition-colors card-hover flex flex-col items-center text-center" style="scroll-snap-align: start;">
                            {{-- Foto bulat / Inisial --}}
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full mb-4 overflow-hidden shrink-0 flex items-center justify-center bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-500/20">
                                @if($pengurus->photo)
                                    <img src="{{ asset('storage/' . $pengurus->photo) }}" alt="{{ $pengurus->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                @else
                                    <span class="text-lg sm:text-xl font-bold font-serif text-emerald-700 dark:text-emerald-400">
                                        {{ strtoupper(substr($pengurus->name, 0, 2)) }}
                                    </span>
                                @endif
                            </div>

                            {{-- Nama --}}
                            <h3 class="font-serif text-base sm:text-lg font-semibold line-clamp-1 mb-1 transition-colors group-hover:text-emerald-600 dark:group-hover:text-emerald-400" style="color: var(--text-primary)">
                                {{ $pengurus->name }}
                            </h3>

                            {{-- Jabatan --}}
                            <p class="text-xs sm:text-sm font-medium line-clamp-1" style="color: var(--text-muted)">
                                {{ $pengurus->position ?: 'Pengurus' }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                @endif

                <div>
                    <a href="{{ route('anggota') }}" class="text-sm font-medium transition-colors hover:text-emerald-600 dark:hover:text-emerald-400" style="color: var(--text-primary)">
                        <span class="link-underline">Lihat Semua Anggota &rarr;</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- CTA PENUTUP -->
        <section class="py-20 reveal-up md:py-32 px-4 sm:px-6 lg:px-8 text-left max-w-7xl mx-auto">
            <h2 class="font-serif text-[clamp(2.5rem,5vw,4.5rem)] font-medium mb-6 leading-tight" style="color: var(--text-primary)">Hadir dan beri makna</h2>
            <p class="text-base md:text-lg mb-10 max-w-xl leading-relaxed" style="color: var(--text-secondary)">
                Ingin bertanya, berkolaborasi, atau bergabung? Hubungi pengurus kami.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="mailto:{{ config('organisasi.email') }}" class="inline-flex btn-active-scale items-center justify-center gap-2 px-6 py-3 min-h-[44px] rounded-xl text-sm font-medium transition-colors bg-emerald-600 hover:bg-emerald-700 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Hubungi via Email
                </a>
                @if(config('site.whatsapp'))
                <a href="https://wa.me/{{ config('site.whatsapp') }}" target="_blank" rel="noopener noreferrer" class="inline-flex btn-active-scale items-center justify-center gap-2 px-6 py-3 min-h-[44px] rounded-xl text-sm font-medium transition-colors bg-green-600 hover:bg-green-700 text-white">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Hubungi via WhatsApp
                </a>
                @endif
            </div>
        </section>
    </main>

    @include("layouts.public-footer")

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const video = document.getElementById('heroVideo');
            if (!video) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        video.play().catch(() => {});
                    } else {
                        video.pause();
                    }
                });
            }, {
                rootMargin: '50px',
                threshold: 0
            });

            observer.observe(video);
        });
    </script>
</body>
</html>

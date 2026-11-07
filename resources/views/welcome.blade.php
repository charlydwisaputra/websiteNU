<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('components.theme-script')
    <title>{{ config('organisasi.nama') }} — Beranda</title>
    <x-seo-meta
        :title="config('organisasi.nama') . ' — Beranda'"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* HERO-TO-CONTENT FADE ZONE (Pendek 80-120px dengan multi-stop: transparent, 50%, 85%, warna latar) */
        .hero-fade-zone {
            height: clamp(80px, 8vw, 110px);
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            pointer-events: none;
            z-index: 2;
            background: linear-gradient(
                to bottom,
                transparent 0%,
                color-mix(in srgb, var(--bg-base) 15%, transparent) 50%,
                color-mix(in srgb, var(--bg-base) 65%, transparent) 85%,
                var(--bg-base) 100%
            );
        }

        /* HERO OVERLAY (Max opacity ~0.46-0.52 di area teks, memudar transparan di area lain & bawah tombol) */
        .hero-overlay {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(
                180deg,
                rgba(0, 0, 0, 0.46) 0%,
                rgba(0, 0, 0, 0.52) 28%,
                rgba(0, 0, 0, 0.38) 58%,
                rgba(0, 0, 0, 0.10) 80%,
                transparent 100%
            );
        }
        @media (min-width: 640px) {
            .hero-overlay {
                background: linear-gradient(
                    90deg,
                    rgba(0, 0, 0, 0.52) 0%,
                    rgba(0, 0, 0, 0.42) 35%,
                    rgba(0, 0, 0, 0.18) 65%,
                    transparent 100%
                ),
                linear-gradient(
                    180deg,
                    rgba(0, 0, 0, 0.32) 0%,
                    transparent 25%
                );
            }
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
        
        /* OVERSCROLL PROTECTION — do NOT set overflow-x:hidden or max-width on html/body,
           those combinations break vertical scroll in all browsers */
        html {
            overscroll-behavior-y: none;
        }
        body {
            overscroll-behavior-y: none;
            background-color: var(--bg-base);
        }
        /* Scroll lock: ONLY applied via JS when mobile menu is open.
           Uses a single class on <html> only — released on menu close,
           link click, Escape, resize >= 1024px, and pageshow (bfcache). */
        html.menu-open {
            overflow: hidden;
        }
        .dark body {
            background-color: #050A07;
        }
        main {
            background-color: var(--bg-base);
            position: relative;
            z-index: 1;
            /* overflow-x: clip is safe: does not create a scroll container,
               so it cannot block vertical scroll unlike overflow-x: hidden */
            overflow-x: clip;
        }
    </style>
</head>
<body class="selection:bg-emerald-500/30 selection:text-emerald-900 dark:selection:text-emerald-100">
    @php
        $imgFallback = asset('images/flag-nu.jpg');
        $wartaFallback = asset('images/warta-placeholder.svg');
    @endphp

    @include("layouts.public-navigation")

    <main class="w-full" x-data="{
        activeLightbox: null,
        openLightbox(item) {
            this.activeLightbox = item;
            document.documentElement.classList.toggle('overflow-hidden', true);
        },
        closeLightbox() {
            this.activeLightbox = null;
            document.documentElement.classList.toggle('overflow-hidden', false);
        }
    }">
        <!-- HERO -->
        <section id="hero-section" class="relative w-full min-h-[85svh] max-h-[900px] flex items-center justify-start overflow-hidden" style="background: var(--bg-surface)">
            
            <!-- MEDIA LAYER -->
            <div class="absolute inset-0 z-0 overflow-hidden bg-gradient-to-br from-[#063323] via-[#031d14] to-[#010e09]">
                @if(!empty($heroMedia['has_video']))
                <video id="heroVideo" 
                       autoplay 
                       muted 
                       loop 
                       playsinline 
                       preload="metadata" 
                       @if(!empty($heroMedia['poster_url'])) poster="{{ $heroMedia['poster_url'] }}" @endif
                       class="absolute inset-0 w-full h-full object-cover object-[center_38%] md:object-[center_32%]">
                    
                    {{-- Sumber Video Mobile (untuk layar kecil <= 767px bila tersedia) --}}
                    @if(!empty($heroMedia['video_mobile_url']))
                    <source src="{{ $heroMedia['video_mobile_url'] }}" type="{{ $heroMedia['video_mobile_type'] }}" media="(max-width: 767px)">
                    @endif

                    {{-- Sumber Video WebM Desktop (bila tersedia) --}}
                    @if(!empty($heroMedia['video_desktop_webm_url']))
                    <source src="{{ $heroMedia['video_desktop_webm_url'] }}" type="video/webm">
                    @endif

                    {{-- Sumber Video Desktop Utama (MP4 / WebM) --}}
                    @if(!empty($heroMedia['video_desktop_url']))
                    <source src="{{ $heroMedia['video_desktop_url'] }}" type="{{ $heroMedia['video_desktop_type'] }}">
                    @endif
                </video>
                @elseif(!empty($heroMedia['poster_url']))
                <!-- Poster cadangan jika video tidak tersedia -->
                <img src="{{ $heroMedia['poster_url'] }}" 
                     alt="Latar Belakang Lanskap Kalisari" 
                     class="absolute inset-0 w-full h-full object-cover object-[center_38%] md:object-[center_32%]">
                @endif

                <!-- Hero overlay: terkontrol max ~0.46-0.52 di area teks, transparan di area lain -->
                <div class="hero-overlay"></div>
            </div>
            
            <!-- FADE ZONE -->
            <div class="hero-fade-zone"></div>

            <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-start pt-24 sm:pt-28 md:pt-28 lg:pt-32 pb-8 sm:pb-10 md:pb-10 lg:pb-14 text-white">
                <p class="text-xs uppercase reveal-up active tracking-[0.2em] font-semibold mb-3 text-emerald-400 drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]">
                    Ekosistem Digital
                </p>
                <h1 class="font-serif text-[clamp(2.75rem,10vw,7rem)] font-medium leading-[1.1] mb-5 max-w-4xl reveal-up delay-100 drop-shadow-[0_2px_4px_rgba(0,0,0,0.7)]">
                    KALISARI
                </h1>
                <p class="text-[clamp(1.125rem,2vw,1.25rem)] reveal-up delay-200 font-normal sm:font-light text-white max-w-[46ch] leading-relaxed mb-8 drop-shadow-[0_1px_3px_rgba(0,0,0,0.8)] [text-shadow:_0_1px_4px_rgba(0,0,0,0.65)]">
                    Tumbuh Bersama. Bergerak Bersama.<br>{{ config('organisasi.deskripsi') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-3 reveal-up delay-300 w-full sm:w-auto">
                    <a href="#warta" class="w-full sm:w-auto inline-flex btn-active-scale items-center justify-center px-8 py-3.5 min-h-[44px] bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-medium transition-colors shadow-lg shadow-black/25">
                        Jelajahi Warta
                    </a>
                    {{-- Tombol sekunder dengan backdrop-blur dan border lebih jelas --}}
                    @if($agendas->isNotEmpty())
                        <a href="#agenda" class="w-full sm:w-auto inline-flex btn-active-scale items-center justify-center px-8 py-3.5 min-h-[44px] rounded-xl text-sm font-medium transition-all bg-black/35 hover:bg-black/50 sm:bg-white/15 sm:hover:bg-white/25 backdrop-blur-md border border-white/60 hover:border-white/90 text-white shadow-md shadow-black/20">
                            Lihat Agenda
                        </a>
                    @endif
                </div>
            </div>
        </section>

        {{-- JADWAL SALAT & HIJRIYAH --}}
        <x-prayer-widget :prayer="$prayer" />

        <!-- PENGANTAR (DENGAN 3 KARTU STATISTIK DI KANAN) -->
        <section class="py-10 md:py-20 border-b border-black/5 dark:border-white/5 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-12 items-center reveal-up">
                    <div class="lg:col-span-7">
                        <h2 class="font-serif text-[26px] sm:text-3xl md:text-[clamp(2.25rem,5vw,3.5rem)] font-medium leading-[1.2] md:leading-tight mb-3 md:mb-6" style="color: var(--text-primary)">
                            Membangun ruang yang memberdayakan masyarakat melalui nilai Ahlussunnah wal Jamaah
                        </h2>
                        <p class="text-sm sm:text-base md:text-lg opacity-90 leading-relaxed mb-4 md:mb-6 max-w-2xl" style="color: var(--text-secondary)">
                            Pengurus Ranting NU Kalisari mengoordinasikan seluruh potensi umat di tingkat ranting. Kami merekam jejak langkah dan menyatukan visi Nahdlatul Ulama untuk masyarakat yang mandiri dan berkeadaban.
                        </p>
                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                            @foreach(config('organisasi.lokasi') as $lokasi)
                                <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 py-0.5 sm:px-2.5 sm:py-1 md:px-3 md:py-1.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 rounded-lg border border-emerald-200/80 dark:border-emerald-800/60 shadow-sm text-xs md:text-[14px] font-medium shrink-0 md:shrink">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $lokasi }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Elemen pendamping di kanan: 3 kartu statistik (grid 3 kolom ringkas di HP, angka besar ~28-30px, label singkat satu baris, kartu sama tinggi) --}}
                    <div class="lg:col-span-5 grid grid-cols-3 lg:grid-cols-1 gap-2 sm:gap-3.5 lg:gap-4 mt-2 lg:mt-0 items-stretch">
                        {{-- Kartu 1: Anggota --}}
                        <div class="h-full p-2.5 sm:p-4 lg:p-5 rounded-xl sm:rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 shadow-sm flex flex-col items-center justify-center text-center lg:flex-row lg:items-center lg:justify-start lg:text-left gap-1 sm:gap-1.5 lg:gap-4 transition-all hover:border-emerald-500/30 card-hover">
                            <div class="hidden lg:flex w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="font-serif text-[28px] sm:text-[32px] lg:text-3xl font-bold text-emerald-700 dark:text-emerald-400 leading-none mb-1 sm:mb-1.5">
                                    {{ $stats['anggota_display'] ?? $stats['anggota'] ?? $stats[0]['value'] ?? 0 }}
                                </div>
                                <div class="text-xs lg:text-sm font-medium leading-tight" style="color: var(--text-secondary)">
                                    <span class="lg:hidden">Anggota</span>
                                    <span class="hidden lg:inline">Anggota & Warga Aktif</span>
                                </div>
                            </div>
                        </div>

                        {{-- Kartu 2: Kegiatan Rutin --}}
                        <div class="h-full p-2.5 sm:p-4 lg:p-5 rounded-xl sm:rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 shadow-sm flex flex-col items-center justify-center text-center lg:flex-row lg:items-center lg:justify-start lg:text-left gap-1 sm:gap-1.5 lg:gap-4 transition-all hover:border-emerald-500/30 card-hover">
                            <div class="hidden lg:flex w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="font-serif text-[28px] sm:text-[32px] lg:text-3xl font-bold text-emerald-700 dark:text-emerald-400 leading-none mb-1 sm:mb-1.5">
                                    {{ $stats['kegiatan_display'] ?? $stats['kegiatan'] ?? $stats[2]['value'] ?? 0 }}
                                </div>
                                <div class="text-xs lg:text-sm font-medium leading-tight" style="color: var(--text-secondary)">
                                    <span class="lg:hidden">Kegiatan</span>
                                    <span class="hidden lg:inline">Kegiatan Rutin / Bulan</span>
                                </div>
                            </div>
                        </div>

                        {{-- Kartu 3: Badan Otonom --}}
                        <div class="h-full p-2.5 sm:p-4 lg:p-5 rounded-xl sm:rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 shadow-sm flex flex-col items-center justify-center text-center lg:flex-row lg:items-center lg:justify-start lg:text-left gap-1 sm:gap-1.5 lg:gap-4 transition-all hover:border-emerald-500/30 card-hover">
                            <div class="hidden lg:flex w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="font-serif text-[28px] sm:text-[32px] lg:text-3xl font-bold text-emerald-700 dark:text-emerald-400 leading-none mb-1 sm:mb-1.5">
                                    {{ $stats['banom_display'] ?? $stats['banom'] ?? $stats[1]['value'] ?? 0 }}
                                </div>
                                <div class="text-xs lg:text-sm font-medium leading-tight" style="color: var(--text-secondary)">
                                    <span class="lg:hidden">Badan Otonom</span>
                                    <span class="hidden lg:inline">Badan Otonom & Lembaga</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- EKOSISTEM (MENAMPILKAN SEMUA 6 BADAN OTONOM) -->
        <section class="py-10 md:py-20 bg-[var(--bg-section-alt)] dark:bg-white/[0.025] border-b border-black/5 dark:border-white/5 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-3 md:mb-8 reveal-up">
                    <h2 class="font-serif text-3xl md:text-[clamp(2.5rem,5vw,4rem)] font-medium leading-tight" style="color: var(--text-primary)">Ekosistem</h2>
                </div>

                <div class="lg:max-w-5xl rounded-2xl overflow-hidden bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 divide-y divide-black/5 dark:divide-white/10 mb-6 md:mb-8 reveal-up">
                    @foreach(config('organisasi.banom') as $banom)
                    @php
                        $banomLogoUrl = \App\Models\Banom::resolveLogoUrl($banom['logo'] ?? null, $banom['singkatan'] ?? $banom['nama'] ?? null);
                        $koordinator = $banom['koordinator'] ?? $banom['coordinator'] ?? null;
                        $hasKoordinator = !empty($koordinator) && !str_contains($koordinator, 'ISI:');
                    @endphp
                    <a href="{{ route('ekosistem') }}" class="group flex items-start md:items-center gap-4 md:gap-6 px-4 py-3 md:px-6 md:py-5 hover:bg-emerald-50/60 dark:hover:bg-white/[0.03] transition-colors">
                        {{-- Nomor Urut Desktop --}}
                        <span class="hidden md:block w-8 shrink-0 font-serif text-lg text-emerald-700/50 dark:text-emerald-400/50 tabular-nums">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                        {{-- Logo Banom --}}
                        <div class="w-12 h-12 md:w-16 md:h-16 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-medium text-lg md:text-2xl overflow-hidden shrink-0">
                            @if(!empty($banomLogoUrl))
                                <img src="{{ $banomLogoUrl }}" alt="Logo {{ $banom['nama'] }}" width="64" height="64" class="w-full h-full object-contain p-1.5 dark:brightness-200 dark:contrast-100" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/brand/logo-nu.png') }}';">
                            @else
                                {{ substr($banom['nama'], 0, 1) }}
                            @endif
                        </div>

                        {{-- Tengah (flex-1 min-w-0) + Koordinator --}}
                        <div class="flex-1 min-w-0 md:flex md:items-center md:justify-between md:gap-6">
                            <div class="min-w-0">
                                <h3 class="font-serif text-xl md:text-2xl font-medium leading-snug transition-colors group-hover:text-emerald-700 dark:group-hover:text-emerald-400" style="color: var(--text-primary)">
                                    {{ $banom['nama'] }}
                                </h3>
                                <p class="hidden md:block text-sm leading-relaxed mt-1 line-clamp-2" style="color: var(--text-secondary)">
                                    {{ $banom['deskripsi'] }}
                                </p>
                            </div>
                            <div class="mt-0.5 md:mt-0 md:w-56 md:text-right shrink-0 text-xs md:text-sm font-medium {{ $hasKoordinator ? 'text-slate-600 dark:text-slate-400' : 'text-slate-500 dark:text-slate-400/80 italic' }}">
                                @if($hasKoordinator)
                                    <span class="hidden md:block text-xs font-normal" style="color: var(--text-muted)">Koordinator</span>
                                    <span class="md:hidden">Koordinator: </span>{{ $koordinator }}
                                @else
                                    Belum ada koordinator
                                @endif
                            </div>
                        </div>

                        {{-- Panah Desktop --}}
                        <span class="hidden md:block text-emerald-600 dark:text-emerald-400 text-lg transition-transform duration-200 group-hover:translate-x-1 shrink-0" aria-hidden="true">&rarr;</span>
                    </a>
                    @endforeach
                </div>
                
                <div>
                    <a href="{{ route('ekosistem') }}" class="inline-flex items-center min-h-[44px] py-2 text-sm font-medium transition-colors text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
                        <span class="link-underline">Lihat Seluruh Ekosistem &rarr;</span>
                    </a>
                </div>
            </div>
        </section>

        @if($agendas->isNotEmpty())
        <!-- AGENDA TERDEKAT -->
        <section id="agenda" class="py-10 md:py-20 border-b border-black/5 dark:border-white/5 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-3 md:mb-8 reveal-up">
                    <h2 class="font-serif text-3xl md:text-[clamp(2.5rem,5vw,4rem)] font-medium leading-tight" style="color: var(--text-primary)">
                        Agenda Terdekat
                    </h2>
                </div>

                <div class="rounded-2xl overflow-hidden bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 divide-y divide-black/5 dark:divide-white/10 mb-0 reveal-up">
                    @foreach($agendas as $agenda)
                    <div class="flex items-start md:items-center gap-4 md:gap-6 px-4 py-4 md:px-6 md:py-5">
                        {{-- Tanggal Kiri --}}
                        <div class="w-14 md:w-16 shrink-0 text-center">
                            <span class="font-serif text-2xl md:text-3xl font-medium leading-none text-emerald-700 dark:text-emerald-400 block">{{ $agenda->tanggal_mulai->translatedFormat('d') }}</span>
                            <span class="text-xs uppercase tracking-wider font-semibold block mt-1" style="color: var(--text-muted)">{{ $agenda->tanggal_mulai->translatedFormat('M') }}</span>
                        </div>

                        {{-- Konten Tengah --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-serif text-lg md:text-xl font-medium leading-snug line-clamp-2" style="color: var(--text-primary)">
                                {{ $agenda->judul }}
                            </h3>
                            <div class="flex items-center gap-2 text-sm mt-1" style="color: var(--text-secondary)">
                                <span class="shrink-0">{{ $agenda->tanggal_mulai->translatedFormat('l, H:i') . ' WIB' }}</span>
                                <span class="shrink-0">&middot;</span>
                                <span class="inline-flex items-center gap-1 min-w-0">
                                    <svg class="w-4 h-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="line-clamp-1">{{ $agenda->lokasi }}</span>
                                </span>
                            </div>
                            @if($agenda->deskripsi)
                                <p class="hidden md:block text-sm line-clamp-2 mt-1.5" style="color: var(--text-muted)">
                                    {{ $agenda->deskripsi }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- WARTA & KEGIATAN -->
        <section id="warta" class="py-10 md:py-20 border-b border-black/5 dark:border-white/5 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-3 md:mb-8 reveal-up">
                    <h2 class="font-serif text-3xl md:text-[clamp(2.5rem,5vw,4rem)] font-medium leading-tight" style="color: var(--text-primary)">Warta Terbaru</h2>
                </div>

                @if($beritas->isEmpty())
                    <div class="py-10 px-6 rounded-2xl bg-black/5 dark:bg-white/5 text-center border border-black/5 dark:border-white/5">
                        <p class="text-base" style="color: var(--text-secondary)">Belum ada warta yang dipublikasikan.</p>
                    </div>
                @else
                    @php $featured = $beritas->first(); @endphp

                    <!-- HERO FEATURED -->
                    <div class="mb-6 md:mb-8 reveal-up">
                        <a href="{{ route('berita.show', $featured->id) }}" class="group block md:grid md:grid-cols-2 gap-6 lg:gap-10 items-center p-3 sm:p-4 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 hover:border-emerald-500/30 transition-all card-hover">
                            <div class="w-full aspect-video overflow-hidden rounded-xl mb-3.5 md:mb-0 bg-emerald-50 dark:bg-emerald-900/10 border border-black/5 dark:border-white/5">
                                <img src="{{ $featured->image ? asset('storage/' . $featured->image) : $wartaFallback }}" alt="{{ $featured->title }}" width="640" height="360" class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-105" loading="lazy" onerror="this.onerror=null; this.src='{{ $wartaFallback }}';">
                            </div>
                            <div class="flex flex-col justify-center">
                                <div class="flex items-center gap-2 mb-2 md:mb-3 text-xs font-medium uppercase tracking-widest" style="color: var(--text-muted)">
                                    <span>{{ $featured->category ?? 'Warta' }}</span>
                                    <span>·</span>
                                    <span>@tanggal($featured->created_at)</span>
                                </div>
                                <h3 class="font-serif text-2xl md:text-3xl lg:text-4xl font-medium mb-2.5 md:mb-4 leading-tight group-hover:text-emerald-600 transition-colors line-clamp-2 md:line-clamp-3" style="color: var(--text-primary)">
                                    {{ $featured->title }}
                                </h3>
                                <p class="text-sm md:text-base leading-relaxed line-clamp-2 md:line-clamp-3" style="color: var(--text-secondary)">
                                    {{ Str::limit(strip_tags($featured->content), 220) }}
                                </p>
                            </div>
                        </a>
                    </div>

                    <!-- COMPACT LIST: 3 BERITA DALAM 3 KOLOM DESKTOP, SAMA TINGGI, THUMBNAIL DIPERBESAR (96-112px, LINE-CLAMP-2) -->
                    @if($beritas->count() > 1)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5 sm:gap-3 md:gap-5 mb-6 md:mb-10 reveal-up" style="transition-delay: 100ms">
                        @foreach($beritas->skip(1)->take(3)->values() as $berita)
                        <a href="{{ route('berita.show', $berita->id) }}" class="h-full flex items-center gap-3 sm:gap-3.5 md:gap-4 p-2.5 sm:p-3 md:p-3.5 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 hover:border-emerald-500/30 transition-all card-hover group">
                            {{-- Thumbnail: mobile 96x72 px, desktop 112x112 px (sekitar 96-112px) --}}
                            <div class="w-[96px] h-[72px] sm:w-[104px] sm:h-[78px] md:w-28 md:h-28 shrink-0 rounded-xl overflow-hidden bg-emerald-50 dark:bg-emerald-900/10 border border-black/5 dark:border-white/5">
                                <img src="{{ $berita->image ? asset('storage/' . $berita->image) : $wartaFallback }}" alt="{{ $berita->title }}" width="112" height="112" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105" loading="lazy" onerror="this.onerror=null; this.src='{{ $wartaFallback }}';">
                            </div>
                            <div class="flex flex-col py-0.5 justify-center flex-grow min-w-0">
                                <span class="text-xs font-medium uppercase tracking-widest mb-1" style="color: var(--text-muted)">
                                    @tanggal($berita->created_at)
                                </span>
                                <h4 class="font-serif text-sm sm:text-base md:text-base font-semibold leading-snug group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-2" style="color: var(--text-primary)">
                                    {{ $berita->title }}
                                </h4>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                @endif

                <div>
                    <a href="{{ route('berita.index') }}" class="inline-flex items-center min-h-[44px] py-2 text-sm font-medium transition-colors text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
                        <span class="link-underline">Lihat Semua Warta &rarr;</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- DOKUMENTASI (GRID 3 KOLOM DESKTOP, 2x2 MOBILE, RASIO 4:3, CAPTION OVERLAY & LIGHTBOX) -->
        <section class="py-10 md:py-20 bg-[var(--bg-section-alt)] dark:bg-white/[0.025] border-b border-black/5 dark:border-white/5 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-3 md:mb-8 reveal-up">
                    <h2 class="font-serif text-3xl md:text-[clamp(2.5rem,5vw,4rem)] font-medium leading-tight" style="color: var(--text-primary)">Dokumentasi</h2>
                </div>

                @if($galeris->isEmpty())
                    <div class="py-10 px-6 rounded-2xl bg-black/5 dark:bg-white/5 text-center border border-black/5 dark:border-white/5">
                        <p class="text-base" style="color: var(--text-secondary)">Belum ada dokumentasi visual.</p>
                    </div>
                @else
                    {{-- Grid: mobile 2 kolom (2x2 = 4 foto), desktop 3 kolom (1 baris = 3 foto terbaru), rasio 4:3 --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-5 mb-6 md:mb-10 reveal-up">
                        @foreach($galeris->take(6) as $index => $galeri)
                        @php
                            $hasRealPhoto = !empty($galeri->image) || !empty($galeri->photo);
                            $galeriSrc = $hasRealPhoto 
                                ? asset('storage/' . ($galeri->image ?? $galeri->photo)) 
                                : asset('storage/galeri/placeholder.svg');
                            $tanggalFormatted = $galeri->created_at ? $galeri->created_at->translatedFormat('d F Y') : '';
                        @endphp
                        <div class="group aspect-[4/3] relative rounded-2xl overflow-hidden bg-emerald-50 dark:bg-emerald-900/10 border border-black/5 dark:border-white/5 cursor-pointer card-hover {{ $index >= 4 ? 'hidden' : ($index == 3 ? 'block md:hidden' : '') }}"
                             @click="openLightbox(@js(['src' => $galeriSrc, 'title' => $galeri->title, 'date' => $tanggalFormatted]))">
                            <img src="{{ $galeriSrc }}" alt="{{ $galeri->title }}" width="600" height="450" class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105" loading="lazy" onerror="this.onerror=null; this.src='{{ $imgFallback }}';">
                            
                            {{-- Caption Overlay (Judul + Tanggal): tidak ada teks 'Dokumentasi NU Kalisari' di atas foto asli --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/35 to-transparent flex flex-col justify-end p-3 sm:p-4 text-white">
                                <span class="font-serif text-xs sm:text-base font-semibold leading-snug line-clamp-1 drop-shadow-sm">{{ $galeri->title }}</span>
                                @if($tanggalFormatted)
                                    <span class="text-xs text-white/80 font-normal mt-0.5 drop-shadow-sm">{{ $tanggalFormatted }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
                
                <div>
                    <a href="{{ route('galeri') }}" class="inline-flex items-center min-h-[44px] py-2 text-sm font-medium transition-colors text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
                        <span class="link-underline">Buka Galeri Penuh &rarr;</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- PENGURUS (3 KOLOM 3+3 DESKTOP, 2 KOLOM MOBILE, KARTU BISA DIKLIK) -->
        <section id="pengurus" class="py-10 md:py-16 border-b border-black/5 dark:border-white/5 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-3 md:mb-8 reveal-up">
                    <h2 class="font-serif text-3xl md:text-[clamp(2.5rem,5vw,4rem)] font-medium leading-tight" style="color: var(--text-primary)">Pengurus</h2>
                </div>

                @if($penguruses->isEmpty())
                    <div class="py-10 px-6 rounded-2xl bg-black/5 dark:bg-white/5 text-center border border-black/5 dark:border-white/5 mb-6">
                        <p class="text-base" style="color: var(--text-secondary)">Belum ada data pengurus yang ditampilkan.</p>
                    </div>
                @else
                    @php
                        $semua = $penguruses->take(6)->values();
                        $pimpinan = $semua->filter(fn($p) => in_array(strtolower(trim($p->position)), ['rais syuriyah','ketua tanfidziyah']))->values();
                        if ($pimpinan->count() < 2) { $pimpinan = $semua->take(2)->values(); }
                        $lainnya = $semua->reject(fn($p) => $pimpinan->contains('id', $p->id))->values();
                    @endphp

                    {{-- Pimpinan (2 Kartu Unggulan) --}}
                    <div class="grid grid-cols-2 gap-3 md:gap-6 mb-3 md:mb-6 reveal-up">
                        @foreach($pimpinan as $pengurus)
                        <a href="{{ route('tentang') }}#pengurus" class="group p-5 md:p-8 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 hover:border-emerald-500/30 flex flex-col items-center text-center transition-all card-hover">
                            <div class="w-20 h-20 md:w-28 md:h-28 rounded-full overflow-hidden shrink-0 flex items-center justify-center bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-500/20 dark:border-emerald-500/30">
                                @if($pengurus->photo)
                                    <img src="{{ asset('storage/' . $pengurus->photo) }}" alt="{{ $pengurus->name }}" width="112" height="112" class="w-full h-full rounded-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                @else
                                    <span class="text-2xl md:text-4xl font-bold font-serif text-emerald-700 dark:text-emerald-200">
                                        @inisial($pengurus->name)
                                    </span>
                                @endif
                            </div>
                            <h3 class="font-serif text-base md:text-2xl font-semibold mt-3 md:mt-5 break-words transition-colors group-hover:text-emerald-600 dark:group-hover:text-emerald-400" style="color: var(--text-primary)">
                                {{ $pengurus->name }}
                            </h3>
                            <span class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                {{ $pengurus->position }}
                            </span>
                        </a>
                        @endforeach
                    </div>

                    {{-- Pengurus Lainnya (Daftar List Panel) --}}
                    @if($lainnya->isNotEmpty())
                    <div class="rounded-2xl overflow-hidden bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 divide-y divide-black/5 dark:divide-white/10 mb-6 md:mb-10 reveal-up">
                        @foreach($lainnya as $pengurus)
                        <a href="{{ route('tentang') }}#pengurus" class="group flex items-center gap-4 px-4 py-3 md:px-6 md:py-4 hover:bg-emerald-50/60 dark:hover:bg-white/[0.03] transition-colors">
                            <div class="w-11 h-11 md:w-12 md:h-12 rounded-full overflow-hidden shrink-0 flex items-center justify-center bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-500/20 dark:border-emerald-500/30">
                                @if($pengurus->photo)
                                    <img src="{{ asset('storage/' . $pengurus->photo) }}" alt="{{ $pengurus->name }}" width="48" height="48" class="w-full h-full rounded-full object-cover" loading="lazy">
                                @else
                                    <span class="text-sm md:text-base font-bold font-serif text-emerald-700 dark:text-emerald-200">
                                        @inisial($pengurus->name)
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0 md:flex md:items-center md:justify-between md:gap-4">
                                <h4 class="font-serif text-base md:text-lg font-semibold truncate transition-colors group-hover:text-emerald-600 dark:group-hover:text-emerald-400" style="color: var(--text-primary)">
                                    {{ $pengurus->name }}
                                </h4>
                                <p class="text-xs md:text-sm font-medium mt-0.5 md:mt-0 md:ml-auto md:text-right shrink-0" style="color: var(--text-muted)">
                                    {{ $pengurus->position }}
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                @endif

                <div>
                    <a href="{{ route('tentang') }}#pengurus" class="inline-flex items-center min-h-[44px] py-2 text-sm font-medium transition-colors text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
                        <span class="link-underline">Lihat Semua Anggota &rarr;</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- CTA PENUTUP -->
        <section class="motif reveal-up overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16 text-left">
                <h2 class="font-serif text-3xl sm:text-4xl md:text-[clamp(2.5rem,5vw,4.5rem)] font-medium mb-2 md:mb-5 leading-tight" style="color: var(--text-primary)">Hadir dan beri makna</h2>
                <p class="text-sm sm:text-base md:text-lg mb-5 md:mb-8 max-w-xl leading-relaxed" style="color: var(--text-secondary)">
                    Ingin bertanya, berkolaborasi, atau bergabung? Hubungi pengurus kami.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    @if(config('site.whatsapp'))
                    <a href="https://wa.me/{{ config('site.whatsapp') }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto inline-flex btn-active-scale items-center justify-center gap-2 px-6 py-3 min-h-[44px] rounded-xl text-sm font-semibold transition-colors bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Hubungi via WhatsApp
                    </a>
                    @endif
                    <a href="mailto:{{ config('organisasi.email') }}" class="w-full sm:w-auto inline-flex btn-active-scale items-center justify-center gap-2 px-6 py-3 min-h-[44px] rounded-xl text-sm font-semibold transition-colors border-2 border-emerald-600 text-emerald-700 hover:bg-emerald-50 dark:border-emerald-500/80 dark:text-emerald-400 dark:hover:bg-emerald-950/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Hubungi via Email
                    </a>
                </div>
            </div>
        </section>

        <!-- LIGHTBOX MODAL (DOKUMENTASI) -->
        <template x-teleport="body">
            <div x-show="activeLightbox" 
                 role="dialog"
                 aria-modal="true"
                 aria-label="Pratinjau dokumentasi"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @keydown.escape.window="closeLightbox()"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/90 backdrop-blur-md"
                 style="display: none;"
                 @click="closeLightbox()">
                <div class="relative max-w-4xl w-full flex flex-col items-center" @click.stop>
                    <button type="button" @click="closeLightbox()" class="absolute -top-12 right-0 text-white/80 hover:text-white p-2 rounded-full focus:outline-none transition-colors" aria-label="Tutup lightbox">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <div class="overflow-hidden rounded-2xl shadow-2xl bg-black/60 border border-white/10 w-full flex flex-col">
                        <div class="max-h-[75vh] flex items-center justify-center bg-black/50 overflow-hidden">
                            <img :src="activeLightbox?.src" :alt="activeLightbox?.title" class="max-h-[75vh] w-auto max-w-full object-contain mx-auto">
                        </div>
                        <div class="p-4 bg-slate-950/95 border-t border-white/10 text-white flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <h4 class="font-serif text-lg font-medium text-white truncate" x-text="activeLightbox?.title"></h4>
                                <p class="text-xs text-white/70 mt-0.5" x-text="activeLightbox?.date"></p>
                            </div>
                            <button type="button" @click="closeLightbox()" class="text-xs uppercase tracking-wider px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-colors shrink-0 font-medium">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </main>

    @include("layouts.public-footer")

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const video = document.getElementById('heroVideo');
            if (!video) return;

            // Hormati preferensi pengurangan gerakan atau koneksi hemat data
            const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const isSaveData = Boolean(navigator.connection && navigator.connection.saveData);

            if (prefersReducedMotion || isSaveData) {
                video.pause();
                video.removeAttribute('autoplay');
                video.preload = 'none';
                while (video.firstChild) {
                    video.removeChild(video.firstChild);
                }
                video.load();
                return;
            }

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

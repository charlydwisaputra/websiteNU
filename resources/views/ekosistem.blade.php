<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ekosistem — {{ config('organisasi.nama') }}</title>
    <x-seo-meta
        :title="'Ekosistem — ' . config('organisasi.nama')"
        :description="'Badan otonom dan lembaga yang menghidupkan masyarakat di lingkungan Nahdlatul Ulama Kalisari.'"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.theme-script')
</head>
<body class="font-sans antialiased flex flex-col min-h-screen"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    <!-- 1. BANNER HALAMAN: Foto latar pedesaan Kalisari dengan overlay gradasi hijau NU (selaras dengan halaman Tentang) -->
    <header id="hero-section" class="relative w-full h-[36svh] min-h-[290px] max-h-[380px] md:h-[45svh] md:min-h-[380px] md:max-h-[480px] flex items-center justify-start overflow-hidden pt-16 md:pt-20">
        <!-- Media latar: Foto desa atau gradasi hijau cadangan jika file tidak ditemukan -->
        <div class="absolute inset-0 z-0 overflow-hidden bg-gradient-to-br from-[#063323] via-[#031d14] to-[#010e09]">
            @if($hasBannerImage ?? true)
            <picture>
                <source 
                    type="image/webp"
                    srcset="{{ asset($bannerWebp768 ?? 'images/banner-1-768.webp') }} 768w, {{ asset($bannerWebp1280 ?? 'images/banner-1-1280.webp') }} 1280w, {{ asset($bannerWebp1920 ?? 'images/banner-1-1920.webp') }} 1920w"
                    sizes="(max-width: 768px) 100vw, (max-width: 1280px) 100vw, 1920px">
                <source 
                    type="image/jpeg"
                    srcset="{{ asset($bannerJpg1920 ?? 'images/banner-1-1920.jpg') }} 1920w"
                    sizes="100vw">
                <img 
                    src="{{ asset($bannerJpg1920 ?? 'images/banner-1-1920.jpg') }}" 
                    alt="Pemandangan udara bentang alam dan pedesaan Kalisari" 
                    width="1920" 
                    height="1280" 
                    fetchpriority="high"
                    class="absolute inset-0 w-full h-full object-cover object-[center_60%] select-none pointer-events-none">
            </picture>
            @endif

            <div class="absolute inset-0 bg-[#063a28]/40 mix-blend-multiply pointer-events-none"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/55 to-black/30 dark:from-[#050A07]/80 dark:via-[#050A07]/45 dark:to-black/20 pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,rgba(5,150,105,0.22),transparent_70%)] pointer-events-none"></div>
        </div>

        <!-- Transisi ke konten: memudar ke warna latar halaman -->
        <div class="absolute bottom-0 inset-x-0 h-12 sm:h-14 pointer-events-none" style="background: linear-gradient(to top, var(--bg-base) 0%, transparent 100%)"></div>

        <!-- Teks banner -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 backdrop-blur-sm mb-3">
                <span>BADAN OTONOM &amp; LEMBAGA</span>
            </div>
            <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-medium tracking-tight mb-3 text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.6)]">
                Ekosistem Organisasi
            </h1>
            <p class="text-sm sm:text-base md:text-lg text-emerald-50/90 font-light max-w-2xl leading-relaxed drop-shadow-[0_1px_2px_rgba(0,0,0,0.7)]">
                Badan otonom dan lembaga yang bersama-sama menghidupkan dan memberdayakan masyarakat di lingkungan Nahdlatul Ulama Kalisari.
            </p>
        </div>
    </header>

    <main class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 pt-10 sm:pt-14 pb-16 md:pb-20 flex-grow w-full">
        
        <!-- 2. HUBUNGAN BADAN OTONOM DENGAN NU RANTING & 3 KARTU STATISTIK GAYA BERANDA -->
        <section class="mb-10 sm:mb-14">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                <!-- Paragraf Hubungan -->
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider mb-3" style="background:var(--accent-subtle);color:var(--accent)">
                        <span>Kemitraan &amp; Pengabdian</span>
                    </div>
                    <h2 class="font-serif text-2xl sm:text-3xl font-medium mb-3 leading-snug" style="color:var(--text-primary)">
                        Sinergi Penggerak Warga di Tingkat Ranting
                    </h2>
                    <p class="text-sm sm:text-base leading-relaxed font-light text-slate-600 dark:text-slate-300">
                        Badan otonom (Banom) merupakan pilar strategis di bawah naungan Pengurus Ranting Nahdlatul Ulama Kalisari yang bergerak sesuai segmen usia, profesi, dan kemasyarakatan. Bersinergi dalam satu komando khidmah Ahlussunnah wal Jamaah, setiap badan otonom menjadi ujung tombak pengkaderan, pemberdayaan sosial, serta pembinaan warga Nahdliyin di Kalisari.
                    </p>
                </div>

                <!-- 3 Kartu Statistik Gaya Beranda -->
                <div class="lg:col-span-5 grid grid-cols-3 lg:grid-cols-1 gap-2 sm:gap-3.5 lg:gap-4 items-stretch">
                    {{-- Kartu 1: Banom --}}
                    <div class="h-full p-2.5 sm:p-4 lg:p-5 rounded-xl sm:rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 shadow-sm flex flex-col items-center justify-center text-center lg:flex-row lg:items-center lg:justify-start lg:text-left gap-1 sm:gap-1.5 lg:gap-4 transition-all hover:border-emerald-500/30">
                        <div class="hidden lg:flex w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div class="min-w-0">
                            <div class="font-serif text-[26px] sm:text-[30px] lg:text-3xl font-bold text-emerald-700 dark:text-emerald-400 leading-none mb-1 sm:mb-1.5">
                                {{ $stats['banom_display'] ?? $stats['banom'] ?? count($banoms) }}
                            </div>
                            <div class="text-[11px] sm:text-xs lg:text-sm font-medium leading-tight whitespace-nowrap" style="color: var(--text-secondary)">
                                <span class="lg:hidden">Banom</span>
                                <span class="hidden lg:inline">Badan Otonom &amp; Lembaga</span>
                            </div>
                        </div>
                    </div>

                    {{-- Kartu 2: Anggota --}}
                    <div class="h-full p-2.5 sm:p-4 lg:p-5 rounded-xl sm:rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 shadow-sm flex flex-col items-center justify-center text-center lg:flex-row lg:items-center lg:justify-start lg:text-left gap-1 sm:gap-1.5 lg:gap-4 transition-all hover:border-emerald-500/30">
                        <div class="hidden lg:flex w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <div class="font-serif text-[26px] sm:text-[30px] lg:text-3xl font-bold text-emerald-700 dark:text-emerald-400 leading-none mb-1 sm:mb-1.5">
                                {{ $stats['anggota_display'] ?? $stats['anggota'] ?? 0 }}
                            </div>
                            <div class="text-[11px] sm:text-xs lg:text-sm font-medium leading-tight whitespace-nowrap" style="color: var(--text-secondary)">
                                <span class="lg:hidden">Anggota</span>
                                <span class="hidden lg:inline">Anggota &amp; Warga Aktif</span>
                            </div>
                        </div>
                    </div>

                    {{-- Kartu 3: Kegiatan Rutin --}}
                    <div class="h-full p-2.5 sm:p-4 lg:p-5 rounded-xl sm:rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 shadow-sm flex flex-col items-center justify-center text-center lg:flex-row lg:items-center lg:justify-start lg:text-left gap-1 sm:gap-1.5 lg:gap-4 transition-all hover:border-emerald-500/30">
                        <div class="hidden lg:flex w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <div class="font-serif text-[26px] sm:text-[30px] lg:text-3xl font-bold text-emerald-700 dark:text-emerald-400 leading-none mb-1 sm:mb-1.5">
                                {{ $stats['kegiatan_display'] ?? $stats['kegiatan'] ?? 0 }}
                            </div>
                            <div class="text-[11px] sm:text-xs lg:text-sm font-medium leading-tight whitespace-nowrap" style="color: var(--text-secondary)">
                                <span class="lg:hidden">Kegiatan</span>
                                <span class="hidden lg:inline">Kegiatan Rutin / Bulan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. FILTER CHIPS & GRID SECTION -->
        <div x-data="{
            activeFilter: 'semua',
            matches(group) {
                if (this.activeFilter === 'semua') return true;
                return this.activeFilter === group;
            }
        }" class="w-full">
            
            <!-- Chip Filter: Semua | Perempuan (Muslimat, Fatayat, IPPNU) | Pemuda (GP Ansor, IPNU) | Mahasiswa (PMII) -->
            <div class="relative w-full mb-8">
                <div class="flex gap-2 overflow-x-auto scrollbar-none pb-1 justify-start md:justify-center snap-x snap-mandatory pr-10 md:pr-0">
                    <button type="button" @click="activeFilter = 'semua'"
                            class="snap-start shrink-0 px-4 sm:px-5 py-2.5 min-h-[44px] flex items-center rounded-full text-xs sm:text-sm font-semibold transition-all border cursor-pointer select-none"
                            :class="activeFilter === 'semua'
                                ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm shadow-emerald-950/20'
                                : 'bg-white dark:bg-[#070D09] text-slate-800 dark:text-slate-100 border-black/10 dark:border-white/10 hover:border-emerald-500/50'">
                        Semua
                    </button>
                    <button type="button" @click="activeFilter = 'perempuan'"
                            class="snap-start shrink-0 px-4 sm:px-5 py-2.5 min-h-[44px] flex items-center rounded-full text-xs sm:text-sm font-semibold transition-all border cursor-pointer select-none"
                            :class="activeFilter === 'perempuan'
                                ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm shadow-emerald-950/20'
                                : 'bg-white dark:bg-[#070D09] text-slate-800 dark:text-slate-100 border-black/10 dark:border-white/10 hover:border-emerald-500/50'">
                        Perempuan <span class="hidden sm:inline text-xs ml-1 font-normal transition-colors" :class="activeFilter === 'perempuan' ? 'text-emerald-100' : 'text-slate-600 dark:text-slate-200'">(Muslimat, Fatayat, IPPNU)</span>
                    </button>
                    <button type="button" @click="activeFilter = 'pemuda'"
                            class="snap-start shrink-0 px-4 sm:px-5 py-2.5 min-h-[44px] flex items-center rounded-full text-xs sm:text-sm font-semibold transition-all border cursor-pointer select-none"
                            :class="activeFilter === 'pemuda'
                                ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm shadow-emerald-950/20'
                                : 'bg-white dark:bg-[#070D09] text-slate-800 dark:text-slate-100 border-black/10 dark:border-white/10 hover:border-emerald-500/50'">
                        Pemuda <span class="hidden sm:inline text-xs ml-1 font-normal transition-colors" :class="activeFilter === 'pemuda' ? 'text-emerald-100' : 'text-slate-600 dark:text-slate-200'">(GP Ansor, IPNU)</span>
                    </button>
                    <button type="button" @click="activeFilter = 'mahasiswa'"
                            class="snap-start shrink-0 px-4 sm:px-5 py-2.5 min-h-[44px] flex items-center rounded-full text-xs sm:text-sm font-semibold transition-all border cursor-pointer select-none"
                            :class="activeFilter === 'mahasiswa'
                                ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm shadow-emerald-950/20'
                                : 'bg-white dark:bg-[#070D09] text-slate-800 dark:text-slate-100 border-black/10 dark:border-white/10 hover:border-emerald-500/50'">
                        Mahasiswa <span class="hidden sm:inline text-xs ml-1 font-normal transition-colors" :class="activeFilter === 'mahasiswa' ? 'text-emerald-100' : 'text-slate-600 dark:text-slate-200'">(PMII)</span>
                    </button>
                </div>
                <!-- Fade mask for horizontal scroll on mobile -->
                <div class="pointer-events-none absolute right-0 top-0 bottom-1 w-10 bg-gradient-to-l from-[var(--bg-base)] to-transparent md:hidden"></div>
            </div>

            <!-- CONTENT GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8 items-stretch">
                @foreach($banoms as $org)
                @php
                    $koordinator = $org['koordinator'] ?? $org['coordinator'] ?? null;
                    $hasKoordinator = !empty($koordinator) && !str_contains($koordinator, 'ISI:');
                    $jadwal = $org['jadwal_rutin'] ?? $org['jadwal'] ?? null;
                    $hasJadwal = !empty($jadwal) && !str_contains($jadwal, 'ISI:');
                    $kategoriGroup = $org['kategori_group'] ?? 'semua';
                    $kategoriSasaran = $org['kategori_sasaran'] ?? $org['singkatan'];
                    $anggotaCount = $org['anggota_count'] ?? 0;
                    $waKoordinator = $org['koordinator_whatsapp'] ?? null;
                @endphp
                <div x-show="matches('{{ $kategoriGroup }}')"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="group p-4 sm:p-5 md:p-6 pb-3.5 sm:pb-4 md:pb-4 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 hover:border-emerald-500/50 dark:hover:border-emerald-500/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-950/10 dark:hover:shadow-black/50 flex flex-col justify-between h-full focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                    
                    <!-- BAGIAN ATAS: Logo, Nama, Kategori Sasaran, Anggota, Deskripsi -->
                    <div class="flex-grow flex flex-col">
                        <div class="flex items-start gap-3 sm:gap-4 mb-2.5 sm:mb-3">
                            <!-- Kotak ikon logo: ukuran konsisten 56x56 / 64x64 dengan placeholder -->
                            <div class="w-14 h-14 sm:w-16 sm:h-16 aspect-square shrink-0 rounded-xl bg-emerald-50/80 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-800/30 flex items-center justify-center p-2.5 overflow-hidden">
                                @php
                                    $logoSrc = $org['logo_url'] ?? \App\Models\Banom::resolveLogoUrl($org['logo'] ?? null, $org['singkatan'] ?? $org['nama'] ?? null);
                                @endphp
                                @if($logoSrc)
                                    <img src="{{ $logoSrc }}" alt="" width="48" height="48" decoding="async" class="w-full h-full max-w-[48px] max-h-[48px] sm:max-w-[52px] sm:max-h-[52px] object-contain dark:brightness-200 dark:contrast-100 select-none" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/brand/logo-nu.png') }}';">
                                @else
                                    <span class="text-emerald-600 dark:text-emerald-400 font-serif text-2xl font-medium select-none">{{ substr($org['nama'], 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-serif text-xl sm:text-2xl font-medium leading-snug group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" style="color:var(--text-primary)">
                                    {{ $org['nama'] }}
                                </h3>
                                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                    <!-- Kategori Sasaran (pengganti singkatan yang mengulang nama) -->
                                    <span class="inline-flex items-center text-[10px] sm:text-[11px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-900/25 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/40">
                                        {{ $kategoriSasaran }}
                                    </span>
                                    <!-- Jumlah Anggota dari Database (sembunyikan jika <= 1) -->
                                    @if($anggotaCount > 1)
                                    <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] font-medium px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/40">
                                        <svg class="w-2.5 h-2.5 text-slate-500 dark:text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                                        {{ $anggotaCount }} Anggota
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Deskripsi: diberi jarak 12-16px (mb-3 sm:mb-4) sebelum batas garis Koordinator -->
                        <p class="text-xs sm:text-sm font-normal leading-relaxed text-slate-600 dark:text-slate-300 mb-3 sm:mb-4">
                            {{ $org['deskripsi'] }}
                        </p>
                    </div>

                    <!-- BAGIAN BAWAH: Koordinator, WhatsApp Koordinator (jika ada), Tombol Lihat Anggota -->
                    <div class="mt-auto pt-3 sm:pt-3.5 border-t border-black/5 dark:border-white/5 flex flex-col gap-2.5 sm:gap-3">
                        @if($hasKoordinator || $hasJadwal)
                        <div class="grid grid-cols-2 gap-3 items-center">
                            @if($hasKoordinator)
                            <div>
                                <p class="text-[10px] sm:text-xs uppercase tracking-widest font-semibold text-slate-400 dark:text-slate-500 mb-0.5">Koordinator</p>
                                <p class="text-xs sm:text-sm font-medium truncate" style="color:var(--text-primary)">{{ $koordinator }}</p>
                            </div>
                            @endif

                            @if(!empty($waKoordinator))
                            <div class="text-right">
                                <a href="https://wa.me/{{ $waKoordinator }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    <span>WhatsApp</span>
                                </a>
                            </div>
                            @elseif($hasJadwal)
                            <div>
                                <p class="text-[10px] sm:text-xs uppercase tracking-widest font-semibold text-slate-400 dark:text-slate-500 mb-0.5">Jadwal Rutin</p>
                                <p class="text-xs sm:text-sm font-medium" style="color:var(--text-primary)">{{ $jadwal }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Tombol LIHAT ANGGOTA (Bentuk tombol seragam di desktop dan mobile, target sentuh min. 44px) -->
                        <a href="{{ route('anggota', ['badan' => $org['slug']]) }}"
                           class="w-full min-h-[44px] py-2.5 px-4 rounded-xl text-center text-xs font-semibold uppercase tracking-wider bg-black/5 dark:bg-white/5 text-slate-800 dark:text-slate-200 hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 dark:hover:text-white transition-all flex items-center justify-center gap-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500"
                           aria-label="Lihat anggota {{ $org['nama'] }}">
                            <span>Lihat Anggota</span>
                            <svg class="w-3.5 h-3.5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 4. SECTION: BAGAIMANA CARA BERGABUNG? (3 langkah + tombol WhatsApp, gaya CTA Hadir dan beri makna) -->
        <section class="mt-14 sm:mt-20 pt-10 sm:pt-14 border-t border-black/5 dark:border-white/5 text-left">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider mb-3" style="background:var(--accent-subtle);color:var(--accent)">
                <span>Langkah Keanggotaan</span>
            </div>
            <h2 class="font-serif text-2xl sm:text-3xl font-medium mb-3 sm:mb-4 leading-snug" style="color: var(--text-primary)">
                Bagaimana cara bergabung?
            </h2>
            <p class="text-sm sm:text-base md:text-lg mb-8 sm:mb-10 max-w-2xl leading-relaxed text-slate-600 dark:text-slate-400 font-light">
                Bergabung dalam keluarga besar Nahdlatul Ulama Kalisari sangat mudah. Pilih badan otonom yang sesuai, hubungi koordinator, dan ikuti kegiatan berkala bersama kami.
            </p>

            <!-- 3 Langkah Bergabung -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-8 sm:mb-10">
                <!-- Langkah 1 -->
                <div class="p-5 sm:p-6 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 flex flex-col justify-between">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold text-sm flex items-center justify-center mb-4 border border-emerald-200/60 dark:border-emerald-800/40">
                            01
                        </div>
                        <h3 class="font-serif text-lg font-bold mb-2" style="color:var(--text-primary)">Pilih Badan Otonom</h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                            Tentukan banom sesuai usia dan minat Anda: Muslimat, Fatayat, GP Ansor, IPNU, IPPNU, atau PMII.
                        </p>
                    </div>
                </div>

                <!-- Langkah 2 -->
                <div class="p-5 sm:p-6 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 flex flex-col justify-between">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold text-sm flex items-center justify-center mb-4 border border-emerald-200/60 dark:border-emerald-800/40">
                            02
                        </div>
                        <h3 class="font-serif text-lg font-bold mb-2" style="color:var(--text-primary)">Hubungi Pengurus</h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                            Konsultasikan niat bergabung melalui narahubung resmi WhatsApp pengurus atau koordinator banom setempat.
                        </p>
                    </div>
                </div>

                <!-- Langkah 3 -->
                <div class="p-5 sm:p-6 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 flex flex-col justify-between">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold text-sm flex items-center justify-center mb-4 border border-emerald-200/60 dark:border-emerald-800/40">
                            03
                        </div>
                        <h3 class="font-serif text-lg font-bold mb-2" style="color:var(--text-primary)">Ikuti Kaderisasi</h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                            Ikuti masa orientasi dan kegiatan rutin untuk mendapatkan nomor dan kartu keanggotaan resmi.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi WhatsApp (gaya sama dengan CTA Hadir dan beri makna) -->
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                @if(config('site.whatsapp'))
                <a href="https://wa.me/{{ config('site.whatsapp') }}?text={{ urlencode('Assalamu\'alaikum, saya ingin bertanya mengenai pendaftaran anggota di banom NU Kalisari.') }}" target="_blank" rel="noopener noreferrer" 
                   class="w-full sm:w-auto inline-flex btn-active-scale items-center justify-center gap-2 px-6 py-3 min-h-[44px] rounded-xl text-sm font-semibold transition-colors bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm shadow-emerald-950/10">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    <span>Hubungi Pengurus via WhatsApp</span>
                </a>
                @endif
                <a href="{{ route('anggota') }}" 
                   class="w-full sm:w-auto inline-flex btn-active-scale items-center justify-center gap-2 px-6 py-3 min-h-[44px] rounded-xl text-sm font-semibold transition-colors border-2 border-emerald-600 text-emerald-700 hover:bg-emerald-50 dark:border-emerald-500/80 dark:text-emerald-400 dark:hover:bg-emerald-950/40">
                    <span>Lihat Seluruh Anggota</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </section>

    </main>

    @include('layouts.public-footer')
</body>
</html>

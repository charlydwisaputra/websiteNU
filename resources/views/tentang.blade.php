<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang — {{ config('organisasi.nama') }}</title>
    <x-seo-meta
        :title="'Tentang — ' . config('organisasi.nama')"
        :description="'Pengurus Ranting Nahdlatul Ulama Kalisari — sejarah, visi misi, susunan pengurus, dan kegiatan rutin.'"
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

    {{-- Variabel banner ($hasBannerImage, $bannerWebp1920, dll.) dikirim dari FrontController::tentang() --}}

    <!-- 1. BANNER HALAMAN: Foto udara lahan dan desa Kalisari dengan perlakuan warna hijau NU & overlay gradasi -->
    <header id="hero-section" class="relative w-full min-h-[320px] md:min-h-[420px] flex items-end justify-start overflow-hidden">
        <!-- Media latar: Foto udara desa atau gradasi hijau cadangan jika file tidak ditemukan -->
        <div class="absolute inset-0 z-0 overflow-hidden bg-gradient-to-br from-[#063323] via-[#031d14] to-[#010e09]">
            @if($hasBannerImage)
            <picture>
                <!-- Format WebP: 768w, 1280w, 1920w -->
                <source 
                    type="image/webp"
                    srcset="{{ asset($bannerWebp768) }} 768w, {{ asset($bannerWebp1280) }} 1280w, {{ asset($bannerWebp1920) }} 1920w"
                    sizes="(max-width: 768px) 100vw, (max-width: 1280px) 100vw, 1920px">
                <!-- Format Fallback JPEG: 1920w -->
                <source 
                    type="image/jpeg"
                    srcset="{{ asset($bannerJpg1920) }} 1920w"
                    sizes="100vw">
                <!-- Elemen gambar utama -->
                <img 
                    src="{{ asset($bannerJpg1920) }}" 
                    alt="Pemandangan udara bentang alam dan pedesaan Kalisari" 
                    width="1920" 
                    height="1280" 
                    fetchpriority="high"
                    class="absolute inset-0 w-full h-full object-cover object-[center_60%] select-none pointer-events-none">
            </picture>
            @endif

            <!-- Overlay & Perlakuan Warna:
                 1. Tint hijau tua (emerald/forest) dengan mix-blend-multiply memberikan sentuhan hijau khas NU yang menyatu dengan foto desa.
                 2. Gradasi vertikal halus memastikan keterbacaan teks (kontras > 4.5:1) pada mode light maupun dark (overlay dark mode dibuat tidak terlalu pekat agar lanskap foto terlihat sampai dekat dasar).
                 3. Pijar aksen radial lembut di sudut kiri atas untuk memperkaya nuansa visual seirama dengan Beranda.
            -->
            <div class="absolute inset-0 bg-[#063a28]/40 mix-blend-multiply pointer-events-none"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/55 to-black/30 dark:from-[#050A07]/80 dark:via-[#050A07]/45 dark:to-black/20 pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,rgba(5,150,105,0.22),transparent_70%)] pointer-events-none"></div>
        </div>

        <!-- Transisi ke konten yang mulus: memudar sempurna ke warna latar halaman -->
        <div class="absolute bottom-0 inset-x-0 h-20 sm:h-24 pointer-events-none" style="background: linear-gradient(to top, var(--bg-base) 0%, color-mix(in srgb, var(--bg-base) 70%, transparent) 55%, transparent 100%)"></div>

        <!-- Teks banner -->
        <div class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-white pt-28 md:pt-36 pb-24 sm:pb-28">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-black/40 text-emerald-200 border border-emerald-300/40 backdrop-blur-sm mb-3">
                <span>Profil & Khidmah</span>
            </div>
            <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-medium tracking-tight mb-3 text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.6)]">
                Tentang Kami
            </h1>
            <p class="text-sm sm:text-base md:text-lg text-emerald-50/90 font-normal max-w-2xl leading-relaxed drop-shadow-[0_1px_2px_rgba(0,0,0,0.7)]">
                {{ $tentangDeskripsi }}
            </p>
        </div>
    </header>

    <!-- CONTENT WRAPPER -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14 flex-grow w-full space-y-12 md:space-y-16" style="overflow-x: clip">
        
        <!-- 2. SEJARAH SINGKAT -->
        <section id="sejarah" class="scroll-mt-24">
                    <div class="flex items-center gap-2 mb-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                        <span>Kilasan Perjalanan</span>
                    </div>
                    <h2 class="font-serif text-3xl md:text-4xl font-medium mb-4 sm:mb-5" style="color:var(--text-primary)">
                        Sejarah Singkat
                    </h2>


                    {{-- Variabel foto sejarah ($sejarahPhotoType, $galeriPhoto, $realCaption) dikirim dari FrontController::tentang() --}}

                    <!-- Layout: Mobile foto di bawah paragraf pertama, Desktop foto di kolom kanan sebelah teks mengikuti tinggi teks -->
                    <div class="grid grid-cols-1 {{ $sejarahPhotoType ? 'lg:grid-cols-12 lg:gap-8 items-stretch' : '' }}">
                        
                        <!-- Teks Sejarah -->
                        <div class="{{ $sejarahPhotoType ? 'lg:col-span-7' : 'w-full' }} space-y-4 text-sm sm:text-base leading-relaxed text-slate-700 dark:text-slate-300 font-normal">
                            <p>{{ $sejarahParagraphs[0] ?? '' }}</p>
                            
                            @if($sejarahPhotoType)
                            <!-- FOTO KHUSUS MOBILE: Selebar penuh kontainer, rasio 16:10, tinggi maks 220px, di bawah paragraf pertama -->
                            <div class="block lg:hidden my-4 w-full">
                                <div class="w-full aspect-[16/10] max-h-[220px] rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-sm relative group bg-slate-100 dark:bg-slate-900">
                                    @if($sejarahPhotoType === 'galeri')
                                        <img src="{{ asset('storage/' . $galeriPhoto) }}" 
                                             alt="{{ $realCaption ?: 'Dokumentasi Kegiatan Pengurus Ranting Nahdlatul Ulama Kalisari' }}" 
                                             class="w-full h-full object-cover" 
                                             loading="lazy">
                                    @else
                                        <picture>
                                            <source srcset="{{ asset('images/sejarah-768.webp') }}" type="image/webp">
                                            <img src="{{ asset('images/sejarah.jpg') }}" 
                                                 alt="Permukiman Warga Desa Kalisari" 
                                                 class="w-full h-full object-cover" 
                                                 loading="lazy">
                                        </picture>
                                    @endif
                                </div>
                                @if($sejarahPhotoType === 'galeri' && !empty($realCaption))
                                <div class="mt-1.5 px-1">
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium line-clamp-2">
                                        {{ $realCaption }}
                                    </p>
                                </div>
                                @endif
                            </div>
                            @endif

                            <blockquote class="my-3 pl-4 border-l-4 border-emerald-600 dark:border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/25 py-2.5 px-4 rounded-r-xl font-serif text-base sm:text-lg text-emerald-950 dark:text-emerald-100 font-medium italic">
                                &#8220;{{ $kutipanSejarah }}&#8221;
                            </blockquote>

                            @foreach(array_slice($sejarahParagraphs, 1) as $paragraph)
                            <p>{{ $paragraph }}</p>
                            @endforeach
                        </div>

                        @if($sejarahPhotoType)
                        <!-- FOTO KHUSUS DESKTOP: Kolom kanan sebelah teks, sudut membulat, mengikuti tinggi isi teks -->
                        <div class="hidden lg:flex lg:col-span-5 flex-col self-stretch">
                            <div class="w-full h-full min-h-[300px] rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-sm relative group bg-slate-100 dark:bg-slate-900">
                                @if($sejarahPhotoType === 'galeri')
                                    <img src="{{ asset('storage/' . $galeriPhoto) }}" 
                                         alt="{{ $realCaption ?: 'Dokumentasi Kegiatan Pengurus Ranting Nahdlatul Ulama Kalisari' }}" 
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                         loading="lazy">
                                    @if(!empty($realCaption))
                                    <div class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                                        <p class="text-xs text-white/90 font-medium line-clamp-2">
                                            {{ $realCaption }}
                                        </p>
                                    </div>
                                    @endif
                                @else
                                    <picture class="w-full h-full">
                                        <source srcset="{{ asset('images/sejarah-1200.webp') }} 1200w, {{ asset('images/sejarah-768.webp') }} 768w" sizes="(min-width: 1024px) 40vw, 100vw" type="image/webp">
                                        <img src="{{ asset('images/sejarah.jpg') }}" 
                                             alt="Permukiman Warga Desa Kalisari" 
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                             loading="lazy">
                                    </picture>
                                @endif
                            </div>
                        </div>
                        @endif

                    </div>
                </section>

                <!-- 3. VISI & MISI: Desktop grid 12 kolom (Visi 5, Misi 7) -->
                <section id="visi-misi" class="scroll-mt-24">
                    <h2 class="font-serif text-3xl md:text-4xl font-medium mb-4 sm:mb-6" style="color:var(--text-primary)">
                        Visi & Misi
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                        <!-- Kartu Visi: Hijau pekat dengan latar samar flag-nu.jpg (lg:col-span-5, tinggi mengikuti isi) -->
                        <div class="lg:col-span-5 relative overflow-hidden flex flex-col p-6 sm:p-7 rounded-2xl bg-[#063323] text-white shadow-md shadow-emerald-950/15 border border-emerald-700/50 group">
                            
                            @if(file_exists(public_path('images/flag-nu.jpg')))
                            <!-- Latar samar flag-nu.jpg di sisi kanan atas (overlay 85-90%, lambang tidak tertumpuk teks) -->
                            <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none select-none">
                                <img src="{{ asset('images/flag-nu.jpg') }}" 
                                     alt="" 
                                     class="absolute -right-6 -top-6 w-3/4 h-3/4 object-contain object-right-top opacity-15 mix-blend-luminosity filter blur-[0.5px] transition-transform duration-700 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-br from-[#063323]/95 via-[#063323]/90 to-[#063323]/85"></div>
                            </div>
                            @endif

                            <!-- Elemen dekoratif kutip besar samar -->
                            <div class="absolute -right-2 -bottom-2 text-white/[0.06] pointer-events-none select-none z-0">
                                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                            </div>

                            <div class="relative z-10">
                                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-300">Pandangan Ke Depan</span>
                                <h3 class="font-serif text-xl font-medium text-emerald-200/90 mt-1 mb-4">Visi</h3>
                                <blockquote class="font-serif text-2xl lg:text-3xl font-medium leading-snug text-white">
                                    &ldquo;{{ $visi }}&rdquo;
                                </blockquote>
                            </div>
                        </div>

                        <!-- Kartu Misi: Daftar bernomor dengan lencana angka bulat (lg:col-span-7) -->
                        <div class="lg:col-span-7 p-6 sm:p-7 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 shadow-sm flex flex-col">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Langkah Strategis</span>
                                <h3 class="font-serif text-2xl font-medium mt-1 mb-4" style="color:var(--text-primary)">Misi</h3>
                                
                                <ol class="space-y-3.5">
                                    @foreach($misi as $idx => $butirMisi)
                                    <li class="flex items-start gap-3">
                                        <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 font-semibold text-xs flex items-center justify-center shrink-0 mt-0.5 border border-emerald-200 dark:border-emerald-800/40">{{ $idx + 1 }}</span>
                                        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-normal">
                                            {{ $butirMisi }}
                                        </p>
                                    </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 4. SUSUNAN PENGURUS: Dikelompokkan Syuriyah & Tanfidziyah, Foto 1:1, Inisial cadangan, Urut urutan -->
                <section id="pengurus" class="scroll-mt-24">
                    <h2 class="font-serif text-3xl md:text-4xl font-medium mb-1" style="color:var(--text-primary)">
                        Susunan Pengurus
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-normal mb-6">
                        Masa khidmah Pengurus Ranting Nahdlatul Ulama Kalisari.
                    </p>

                    <!-- Kelompok Syuriyah -->
                    @if(!empty($syuriyahList))
                    <div class="mb-7">
                        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1 sm:gap-2 mb-3 pb-2 border-b border-black/5 dark:border-white/5">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-600 dark:bg-emerald-400 shrink-0"></span>
                                <h3 class="font-serif text-xl sm:text-2xl font-medium" style="color:var(--text-primary)">
                                    Syuriyah
                                </h3>
                            </div>
                            <span class="text-sm text-slate-600 dark:text-slate-300 font-normal pl-4 sm:pl-0">
                                Bimbingan & Pengawasan Spiritual
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            @foreach($syuriyahList as $p)
                            <div class="flex items-center gap-3.5 sm:gap-4 p-3 sm:p-3.5 rounded-xl sm:rounded-2xl {{ $loop->first ? 'bg-emerald-50/70 dark:bg-emerald-950/30 border-emerald-200/70 dark:border-emerald-800/50' : 'bg-white dark:bg-[#070D09] border-black/5 dark:border-white/10' }} border hover:border-emerald-500/40 dark:hover:border-emerald-500/40 transition-colors group">
                                
                                <!-- Foto Profil Bulat 1:1 atau Inisial 2 Huruf (Warna terang kontras > 4.5:1 di dark mode) -->
                                <div class="{{ $loop->first ? 'w-14 h-14' : 'w-11 h-11 sm:w-12 sm:h-12' }} rounded-full overflow-hidden shrink-0 bg-emerald-50 dark:bg-[#031d13] border border-emerald-200/70 dark:border-emerald-800/80 flex items-center justify-center relative select-none aspect-square">
                                    @if(!empty($p['photo']))
                                    <img src="{{ asset('storage/' . $p['photo']) }}" 
                                         alt="{{ $p['nama'] }}" 
                                         class="w-full h-full object-cover rounded-full"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    @endif
                                    <span class="{{ !empty($p['photo']) ? 'hidden' : 'flex' }} w-full h-full items-center justify-center text-emerald-800 dark:text-emerald-300 font-serif {{ $loop->first ? 'text-lg' : 'text-sm sm:text-base' }} font-bold">
                                        @inisial($p['nama'])
                                    </span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="{{ $loop->first ? 'font-serif text-lg font-semibold' : 'text-sm sm:text-base font-medium' }} leading-snug group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate" style="color:var(--text-primary)">
                                        {{ $p['nama'] }}
                                    </p>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">{{ $p['jabatan'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Kelompok Tanfidziyah -->
                    @if(!empty($tanfidziyahList))
                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1 sm:gap-2 mb-3 pb-2 border-b border-black/5 dark:border-white/5">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-600 dark:bg-emerald-400 shrink-0"></span>
                                <h3 class="font-serif text-xl sm:text-2xl font-medium" style="color:var(--text-primary)">
                                    Tanfidziyah
                                </h3>
                            </div>
                            <span class="text-sm text-slate-600 dark:text-slate-300 font-normal pl-4 sm:pl-0">
                                Pelaksana Program Kerja & Organisasi
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            @foreach($tanfidziyahList as $p)
                            <div class="flex items-center gap-3.5 sm:gap-4 p-3 sm:p-3.5 rounded-xl sm:rounded-2xl {{ $loop->first ? 'bg-emerald-50/70 dark:bg-emerald-950/30 border-emerald-200/70 dark:border-emerald-800/50' : 'bg-white dark:bg-[#070D09] border-black/5 dark:border-white/10' }} border hover:border-emerald-500/40 dark:hover:border-emerald-500/40 transition-colors group">
                                
                                <!-- Foto Profil Bulat 1:1 atau Inisial 2 Huruf (Warna terang kontras > 4.5:1 di dark mode) -->
                                <div class="{{ $loop->first ? 'w-14 h-14' : 'w-11 h-11 sm:w-12 sm:h-12' }} rounded-full overflow-hidden shrink-0 bg-emerald-50 dark:bg-[#031d13] border border-emerald-200/70 dark:border-emerald-800/80 flex items-center justify-center relative select-none aspect-square">
                                    @if(!empty($p['photo']))
                                    <img src="{{ asset('storage/' . $p['photo']) }}" 
                                         alt="{{ $p['nama'] }}" 
                                         class="w-full h-full object-cover rounded-full"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    @endif
                                    <span class="{{ !empty($p['photo']) ? 'hidden' : 'flex' }} w-full h-full items-center justify-center text-emerald-800 dark:text-emerald-300 font-serif {{ $loop->first ? 'text-lg' : 'text-sm sm:text-base' }} font-bold">
                                        @inisial($p['nama'])
                                    </span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="{{ $loop->first ? 'font-serif text-lg font-semibold' : 'text-sm sm:text-base font-medium' }} leading-snug group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate" style="color:var(--text-primary)">
                                        {{ $p['nama'] }}
                                    </p>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">{{ $p['jabatan'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Tombol Lihat Semua Anggota -->
                    <div class="mt-5 flex justify-start">
                        <a href="{{ route('anggota') }}" 
                           class="inline-flex btn-active-scale items-center justify-center gap-2 min-h-[44px] px-6 py-2.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/30 rounded-xl text-sm font-semibold transition-all hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 dark:hover:text-white hover:border-emerald-600 shadow-sm shadow-emerald-950/5 active:scale-95 group">
                            <span>Lihat semua anggota</span>
                            <span class="transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
                        </a>
                    </div>
                </section>

                <!-- 5. KEGIATAN RUTIN: 3 Kartu Desktop, Ditumpuk Mobile -->
                <section id="kegiatan-rutin" class="scroll-mt-24">
                    <h2 class="font-serif text-3xl md:text-4xl font-medium mb-1" style="color:var(--text-primary)">
                        Kegiatan Rutin
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-normal mb-5">
                        Aktivitas pembinaan dan pertemuan rutin warga Nahdliyin Kalisari.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                        @foreach($kegiatanRutin as $k)
                        <div class="p-5 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 shadow-sm flex flex-col justify-between hover:border-emerald-500/30 transition-all duration-300 group">
                            <div>
                                <!-- Header kartu: Ikon & Chip Hari/Jadwal -->
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-800/30 shrink-0">
                                        @if(($k['icon'] ?? '') === 'calendar')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @elseif(($k['icon'] ?? '') === 'clock')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        @endif
                                    </div>
                                    
                                    <!-- Chip Hari / Jadwal -->
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 text-right">
                                        {{ $k['jadwal'] }}
                                    </span>
                                </div>

                                <h3 class="font-serif text-lg font-medium group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors leading-snug mb-2" style="color:var(--text-primary)">
                                    {{ $k['nama'] }}
                                </h3>

                                <!-- Lokasi / Peserta -->
                                <div class="space-y-1 text-xs text-slate-600 dark:text-slate-400 font-normal">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        <span class="truncate">{{ $k['lokasi'] ?? ($k['detail'] ?? '-') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        <span class="truncate">{{ $k['peserta'] ?? 'Warga Nahdliyin' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

        @if(!empty($banoms) && count($banoms) > 0)
        <!-- Deretan Logo Banom yang Menaut ke Ekosistem (Area Sentuh Min 44px) -->
        <section class="pt-8 sm:pt-10 border-t border-black/5 dark:border-white/5">
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ekosistem:</span>
                <div class="flex items-center gap-2.5 flex-wrap">
                    @foreach($banoms as $b)
                    <a href="{{ route('ekosistem') }}" 
                       class="w-11 h-11 min-w-[44px] min-h-[44px] p-2 rounded-xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 hover:border-emerald-500/50 hover:scale-105 active:scale-95 transition-all flex items-center justify-center shadow-xs" 
                       title="{{ $b['nama'] }} ({{ $b['singkatan'] }}) — Buka Ekosistem"
                       aria-label="{{ $b['nama'] }}">
                        <img src="{{ $b['logo_url'] }}" alt="Logo {{ $b['nama'] }} ({{ $b['singkatan'] }})" width="28" height="28" class="w-full h-full object-contain pointer-events-none" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/brand/logo-nu.png') }}';">
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        @php
            $lat = config('organisasi.peta.lat', config('organisasi.lokasi_lat', -7.4218));
            $lng = config('organisasi.peta.lng', config('organisasi.lokasi_lng', 109.1608));
            $hasCoordinates = !empty($lat) && !empty($lng) && is_numeric($lat) && is_numeric($lng);

            $mapLink = $hasCoordinates 
                ? "https://www.google.com/maps?q={$lat},{$lng}"
                : "https://maps.google.com/?q=" . urlencode(config('organisasi.alamat'));
        @endphp

        <!-- 6. PENUTUP: KONTAK & LOKASI + HADIR DAN BERI MAKNA -->
        <section id="kontak" class="mt-12 sm:mt-16 pt-10 sm:pt-14 border-t border-black/5 dark:border-white/5 scroll-mt-24 text-left">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- KIRI (lg:col-span-5) -->
                <div class="lg:col-span-5 space-y-6">
                    <div>
                        <div class="inline-flex items-center gap-2 mb-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                            <span>Sekretariat & Silaturahmi</span>
                        </div>
                        <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-medium mb-3 leading-tight" style="color: var(--text-primary)">
                            Hadir dan beri makna
                        </h2>
                        <p class="text-sm sm:text-base leading-relaxed text-slate-600 dark:text-slate-400 font-normal">
                            Ingin bertanya, berkolaborasi, atau bergabung? Hubungi pengurus kami atau silaturahmi langsung ke sekretariat.
                        </p>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-black/5 dark:border-white/5">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Alamat</p>
                            <p class="text-sm leading-relaxed text-slate-700 dark:text-slate-300 font-normal">{{ config('organisasi.alamat') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Email</p>
                            <a href="mailto:{{ config('organisasi.email') }}" class="inline-flex items-center min-h-[44px] text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline">
                                {{ config('organisasi.email') }}
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        @if(config('site.whatsapp'))
                        <a href="https://wa.me/{{ config('site.whatsapp') }}" target="_blank" rel="noopener noreferrer" 
                           class="w-full sm:w-auto inline-flex btn-active-scale items-center justify-center gap-2 px-5 py-3 min-h-[44px] rounded-xl text-sm font-semibold transition-colors bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm shadow-emerald-950/10">
                            <span>Hubungi via WhatsApp</span>
                        </a>
                        @endif
                        <a href="mailto:{{ config('organisasi.email') }}" 
                           class="w-full sm:w-auto inline-flex btn-active-scale items-center justify-center gap-2 px-5 py-3 min-h-[44px] rounded-xl text-sm font-semibold transition-colors border-2 border-emerald-600 text-emerald-700 hover:bg-emerald-50 dark:border-emerald-500/80 dark:text-emerald-400 dark:hover:bg-emerald-950/40">
                            <span>Hubungi via Email</span>
                        </a>
                    </div>
                </div>

                <!-- KANAN (lg:col-span-7) -->
                <div class="lg:col-span-7 w-full">
                    <div class="p-5 sm:p-6 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 shadow-sm space-y-4">
                        @if($hasCoordinates)
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Peta Lokasi</p>
                            
                            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
                            <style>
                                .dark .leaflet-tile-pane {
                                    filter: brightness(0.75) contrast(1.05) saturate(0.85);
                                }
                                .leaflet-control-zoom {
                                    border: 0 !important;
                                    box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
                                    border-radius: 8px !important;
                                    overflow: hidden !important;
                                }
                            </style>

                            @once
                            <script>
                                (function() {
                                    function registerMapEmbed() {
                                        if (window.Alpine && !window.Alpine._mapEmbedRegistered) {
                                            window.Alpine._mapEmbedRegistered = true;
                                            window.Alpine.data('mapEmbed', (config = {}) => ({
                                                active: false, map: null, observer: null, resizeObserver: null,
                                                lat: config.lat || -7.4218, lng: config.lng || 109.1608, zoom: config.zoom || 14,
                                                pinUrl: config.pinUrl || '/images/pin-hijau.svg',
                                                init() { this.loadLeaflet(); this.setupObserver(); },
                                                loadLeaflet() {
                                                    if (typeof L === 'undefined') {
                                                        const s = document.createElement('script');
                                                        s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                                                        s.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
                                                        s.crossOrigin = '';
                                                        s.onload = () => this.setupMap();
                                                        document.head.appendChild(s);
                                                    } else { this.setupMap(); }
                                                },
                                                setupMap() {
                                                    if (this.map) return;
                                                    const c = this.$refs.mapContainer;
                                                    if (!c) return;
                                                    this.map = L.map(c, {
                                                        attributionControl: false, zoomControl: true, scrollWheelZoom: false,
                                                        dragging: false, touchZoom: false, doubleClickZoom: false, boxZoom: false,
                                                    }).setView([this.lat, this.lng], this.zoom);
                                                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(this.map);
                                                    const pinIcon = L.icon({ iconUrl: this.pinUrl, iconSize: [32, 40], iconAnchor: [16, 40], popupAnchor: [0, -40] });
                                                    L.marker([this.lat, this.lng], { icon: pinIcon }).addTo(this.map);
                                                    if (window.ResizeObserver) {
                                                        this.resizeObserver = new ResizeObserver(() => { if (this.map) this.map.invalidateSize(); });
                                                        this.resizeObserver.observe(c);
                                                    }
                                                },
                                                setupObserver() {
                                                    this.observer = new IntersectionObserver((entries) => {
                                                        entries.forEach((e) => { if (!e.isIntersecting) this.toggleActive(false); });
                                                    }, { threshold: 0.1 });
                                                    this.observer.observe(this.$el);
                                                },
                                                toggleActive(state) {
                                                    this.active = state;
                                                    if (!this.map) return;
                                                    const h = state ? 'enable' : 'disable';
                                                    this.map.dragging[h](); this.map.touchZoom[h](); this.map.scrollWheelZoom[h]();
                                                },
                                                destroy() {
                                                    if (this.resizeObserver) this.resizeObserver.disconnect();
                                                    if (this.observer) this.observer.disconnect();
                                                    if (this.map) { this.map.remove(); this.map = null; }
                                                },
                                            }));
                                        }
                                    }
                                    if (window.Alpine) { registerMapEmbed(); } else { document.addEventListener('alpine:init', registerMapEmbed); }
                                })();
                            </script>
                            @endonce

                            <!-- Container Peta Interaktif dengan Pelindung Scroll & Auto-Lock -->
                            <div x-data="mapEmbed({
                                    lat: {{ (float)$lat }},
                                    lng: {{ (float)$lng }},
                                    zoom: 14,
                                    pinUrl: @js(asset('images/pin-hijau.svg'))
                                 })"
                                 @click.outside="toggleActive(false)"
                                 class="relative rounded-xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xs bg-slate-100 dark:bg-slate-900 group">
                                
                                <div x-ref="mapContainer" class="w-full h-[220px] sm:h-[260px] md:h-[280px] z-0"></div>

                                <div x-show="!active" 
                                     @click="toggleActive(true)"
                                     class="absolute inset-0 bg-black/15 dark:bg-black/35 backdrop-blur-[1px] flex items-center justify-center cursor-pointer transition-opacity z-10 p-3"
                                     role="button"
                                     tabindex="0"
                                     @keydown.enter="toggleActive(true)"
                                     @keydown.space.prevent="toggleActive(true)"
                                     aria-label="Ketuk untuk mengaktifkan interaksi peta">
                                    <div class="px-3.5 py-2 rounded-xl bg-white/95 dark:bg-[#070D09]/95 text-slate-800 dark:text-slate-100 text-xs font-medium shadow-md border border-black/10 dark:border-white/10 flex items-center gap-2 select-none group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                                        </svg>
                                        <span>Ketuk untuk membuka interaksi peta</span>
                                    </div>
                                </div>

                                <button x-show="active" 
                                        x-cloak
                                        @click.stop="toggleActive(false)"
                                        type="button"
                                        class="absolute top-2 right-2 z-20 px-2.5 py-1 rounded-lg bg-white/95 dark:bg-[#070D09]/95 text-xs font-medium text-slate-700 dark:text-slate-200 border border-black/10 dark:border-white/10 shadow-sm hover:bg-white dark:hover:bg-black transition-colors"
                                        aria-label="Kunci peta">
                                    Kunci peta
                                </button>
                            </div>

                            <div class="mt-1 text-xs text-slate-400 dark:text-slate-500 text-right">
                                <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer" class="hover:underline">© OpenStreetMap kontributor</a>
                            </div>
                        </div>
                        @endif

                        <div class="pt-2">
                            <a href="{{ $mapLink }}" target="_blank" rel="noopener noreferrer" 
                               class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 min-h-[44px] bg-emerald-600 hover:bg-emerald-700 active:scale-95 rounded-xl text-sm font-semibold transition-all text-white shadow-sm shadow-emerald-950/10 group">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Buka di Peta</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>

    @include('layouts.public-footer')
</body>
</html>

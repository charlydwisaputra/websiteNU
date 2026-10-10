<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kalisari — Tumbuh Bersama. Bergerak Bersama.</title>
    <meta name="description" content="Ruang digital untuk mengenal, terhubung, dan bergerak bersama masyarakat Kalisari.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.theme-script')
    
    <style>
        :root {
            --home-bg: #F5F8F6;
            --home-surface: #FFFFFF;
            --home-text: #10231B;
            --home-text-muted: #4A6356;
            --home-accent: #008F5A;
            --home-glass-border: rgba(0, 0, 0, 0.05);
        }
        
        html.dark {
            --home-bg: #050706;
            --home-surface: #07130F;
            --home-text: #E8FFF5;
            --home-text-muted: #8AA89A;
            --home-accent: #00A86B;
            --home-glass-border: rgba(255, 255, 255, 0.08);
        }

        body {
            background-color: var(--home-bg);
            color: var(--home-text);
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        .font-editorial { font-family: 'Cormorant Garamond', serif; }
        
        .hero-overlay {
            background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%);
        }

        .glass-panel {
            background: var(--home-surface);
            border: 1px solid var(--home-glass-border);
            border-radius: 16px;
        }

        /* Fallback for broken images */
        img.fallback-ready { position: relative; }
        img.fallback-ready::after {
            content: attr(alt);
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            inset: 0;
            background: var(--home-bg);
            color: var(--home-text-muted);
            font-size: 0.875rem;
            text-align: center;
            border: 1px dashed var(--home-glass-border);
            border-radius: inherit;
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="selection:bg-emerald-500/30 selection:text-emerald-900 dark:selection:text-emerald-100">

    @include("layouts.public-navigation")

    <main>
        <!-- 1. HERO SECTION -->
        <section class="relative w-full min-h-[100dvh] flex items-center justify-center overflow-hidden bg-black">
            <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-0" poster="https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1920&auto=format&fit=crop">
                <source src="{{ asset('videos/village_sample.mp4') }}" type="video/mp4">
            </video>
            
            <div class="absolute inset-0 z-[1] hero-overlay"></div>

            <div class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-start pt-20 text-white">
                <p class="tracking-[0.25em] text-xs uppercase font-semibold mb-4 opacity-90" style="color: var(--home-accent)">
                    Ekosistem Digital
                </p>
                <h1 class="font-editorial text-[clamp(2.75rem,10vw,7rem)] font-bold leading-[1.1] mb-6 max-w-4xl">
                    KALISARI
                </h1>
                <p class="text-[clamp(1rem,2.5vw,1.5rem)] font-light opacity-90 max-w-2xl leading-relaxed mb-10">
                    Tumbuh Bersama. Bergerak Bersama. Ruang digital untuk mengenal dan terhubung dengan masyarakat Nahdlatul Ulama di desa Kalisari.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                    <a href="#intro" class="w-full sm:w-auto px-8 py-4 rounded-full text-sm font-medium tracking-wide text-center transition-transform hover:-translate-y-1" style="background: var(--home-accent); color: #fff;">
                        Jelajahi Kalisari
                    </a>
                    <a href="#activities" class="w-full sm:w-auto px-8 py-4 rounded-full text-sm font-medium tracking-wide text-center backdrop-blur-md border border-white/20 hover:bg-white/10 transition-colors">
                        Lihat Kegiatan
                    </a>
                </div>
            </div>
        </section>

        <!-- 2. INTRO SECTION -->
        <section id="intro" class="py-16 md:py-24 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            <h2 class="font-editorial text-[clamp(2rem,6vw,4rem)] leading-tight mb-8">
                Kalisari bukan hanya sebuah tempat. Ia adalah jalinan <span style="color: var(--home-accent)">tradisi</span>, agama, dan persaudaraan.
            </h2>
            <p class="text-base md:text-lg opacity-80 leading-relaxed mb-6 font-light">
                Sebagai akar rumput Nahdlatul Ulama, kami membangun harmoni melalui pendekatan sosial, pendidikan, dan dakwah. Setiap warga mengambil peran dalam merawat nilai-nilai luhur yang telah diwariskan dari generasi ke generasi, sembari beradaptasi dengan kemajuan zaman.
            </p>
            <div class="flex flex-wrap gap-2 mb-10 text-xs uppercase tracking-widest font-semibold" style="color: var(--home-text-muted)">
                <span class="px-3 py-1 rounded-full border" style="border-color: var(--home-glass-border)">Kalisari</span>
                <span class="px-3 py-1 rounded-full border" style="border-color: var(--home-glass-border)">Cilongok</span>
                <span class="px-3 py-1 rounded-full border" style="border-color: var(--home-glass-border)">Banyumas</span>
                <span class="px-3 py-1 rounded-full border" style="border-color: var(--home-glass-border)">Jawa Tengah</span>
            </div>
            <div>
                <a href="{{ Route::has('tentang') ? route('tentang') : '#' }}" class="text-sm font-medium border-b pb-1 transition-colors uppercase tracking-widest hover:opacity-80" style="color: var(--home-accent); border-color: var(--home-accent)">
                    Baca Selengkapnya &rarr;
                </a>
            </div>
        </section>

        <!-- 3. EKOSISTEM SECTION -->
        <section id="ekosistem" class="py-16 md:py-24 bg-black/5 dark:bg-white/5">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12">
                    <p class="tracking-[0.2em] text-xs uppercase font-semibold mb-2 opacity-70" style="color: var(--home-accent)">Organisasi</p>
                    <h2 class="font-editorial text-[clamp(2.5rem,5vw,4rem)] leading-tight">Ekosistem</h2>
                </div>

                @php
                    $orgs = [
                        ['name' => 'Muslimat NU', 'desc' => 'Pergerakan perempuan Islam untuk kesejahteraan keluarga dan keumatan.', 'logo' => 'MuslimatNU.png'],
                        ['name' => 'Fatayat NU', 'desc' => 'Wadah pemudi NU dalam pengembangan diri, agama, dan sosial budaya.', 'logo' => 'FATAYAT-NU.png'],
                        ['name' => 'GP Ansor', 'desc' => 'Pemuda NU yang bergerak dalam sosial kemasyarakatan dan kepemudaan.', 'logo' => 'GP-Ansor.png'],
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    @foreach($orgs as $org)
                    <div class="glass-panel p-6 flex flex-col items-start hover:-translate-y-1 transition-transform">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6 bg-white shadow-sm overflow-hidden p-2">
                            <img src="{{ asset('storage/logo organisasi/' . $org['logo']) }}" alt="{{ $org['name'] }}" class="w-full h-full object-contain fallback-ready" loading="lazy">
                        </div>
                        <h3 class="font-editorial text-2xl font-semibold mb-3">{{ $org['name'] }}</h3>
                        <p class="text-sm opacity-70 font-light leading-relaxed">{{ $org['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
                
                <div class="text-left md:text-center">
                    <a href="{{ Route::has('ekosistem') ? route('ekosistem') : '#' }}" class="inline-flex items-center justify-center px-8 py-3 rounded-full text-sm font-medium tracking-wide border transition-colors hover:bg-black/5 dark:hover:bg-white/10" style="border-color: var(--home-glass-border)">
                        Lihat Ekosistem Selengkapnya
                    </a>
                </div>
            </div>
        </section>

        <!-- 4. ACTIVITIES SECTION -->
        <section id="activities" class="py-16 md:py-24 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
            <div class="mb-12">
                <p class="tracking-[0.2em] text-xs uppercase font-semibold mb-2 opacity-70" style="color: var(--home-accent)">Warta & Gerak</p>
                <h2 class="font-editorial text-[clamp(2.5rem,5vw,4rem)] leading-tight">Kegiatan Terbaru</h2>
            </div>

            <!-- Mobile: Stacked, Desktop: Bento Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Large Featured -->
                <div class="lg:col-span-8 glass-panel overflow-hidden group hover:border-emerald-500/30 transition-colors flex flex-col">
                    <div class="relative w-full aspect-[4/3] lg:aspect-[16/9] overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1511649475669-e288648b2339?q=80&w=1200&auto=format&fit=crop" alt="GP Ansor Kalisari" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 fallback-ready" loading="lazy">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 text-xs font-semibold uppercase tracking-widest mb-3 opacity-70">
                            <span style="color: var(--home-accent)">GP Ansor</span>
                            <span class="w-1 h-1 rounded-full bg-current"></span>
                            <span>24 Okt 2026</span>
                        </div>
                        <h3 class="font-editorial text-2xl lg:text-3xl font-semibold mb-2 leading-tight">Pelatihan Kepemimpinan Dasar GP Ansor Kalisari</h3>
                        <p class="text-sm font-light opacity-70 leading-relaxed">Membangun karakter dan kepemimpinan generasi muda dalam menghadapi tantangan zaman melalui nilai-nilai Ahlussunnah wal Jamaah.</p>
                    </div>
                </div>

                <!-- Right Column (Small Cards) -->
                <div class="lg:col-span-4 flex flex-col gap-6">
                    <div class="glass-panel overflow-hidden flex flex-row lg:flex-col group hover:border-emerald-500/30 transition-colors flex-1">
                        <div class="relative w-1/3 lg:w-full aspect-square lg:aspect-video overflow-hidden shrink-0">
                            <img src="https://images.unsplash.com/photo-1429982260586-77884824e4c2?q=80&w=600&auto=format&fit=crop" alt="Muslimat NU" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 fallback-ready" loading="lazy">
                        </div>
                        <div class="p-4 lg:p-5 flex flex-col justify-center">
                            <div class="text-[10px] font-semibold uppercase tracking-widest mb-2 opacity-70">18 Okt 2026</div>
                            <h3 class="font-editorial text-lg font-semibold leading-tight">Pengajian Rutin Selapanan Muslimat NU</h3>
                        </div>
                    </div>
                    
                    <div class="glass-panel overflow-hidden flex flex-row lg:flex-col group hover:border-emerald-500/30 transition-colors flex-1">
                        <div class="relative w-1/3 lg:w-full aspect-square lg:aspect-video overflow-hidden shrink-0">
                            <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=600&auto=format&fit=crop" alt="Fatayat NU" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 fallback-ready" loading="lazy">
                        </div>
                        <div class="p-4 lg:p-5 flex flex-col justify-center">
                            <div class="text-[10px] font-semibold uppercase tracking-widest mb-2 opacity-70">12 Okt 2026</div>
                            <h3 class="font-editorial text-lg font-semibold leading-tight">Pelatihan Ekonomi Kreatif Fatayat</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <a href="{{ Route::has('berita.index') ? route('berita.index') : '#' }}" class="text-sm font-medium border-b pb-1 transition-colors uppercase tracking-widest hover:opacity-80" style="color: var(--home-accent); border-color: var(--home-accent)">
                    Lihat Semua Kegiatan &rarr;
                </a>
            </div>
        </section>

        <!-- 5. DOCUMENTATION SECTION -->
        <section class="py-16 md:py-24 bg-black/5 dark:bg-white/5">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12">
                    <p class="tracking-[0.2em] text-xs uppercase font-semibold mb-2 opacity-70" style="color: var(--home-accent)">Arsip Visual</p>
                    <h2 class="font-editorial text-[clamp(2.5rem,5vw,4rem)] leading-tight">Dokumentasi</h2>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                    <div class="col-span-2 row-span-2 aspect-square lg:aspect-auto overflow-hidden rounded-2xl">
                        <img src="https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?q=80&w=800&auto=format&fit=crop" alt="Dokumentasi 1" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700 fallback-ready" loading="lazy">
                    </div>
                    <div class="col-span-1 aspect-square overflow-hidden rounded-2xl">
                        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=800&auto=format&fit=crop" alt="Dokumentasi 2" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700 fallback-ready" loading="lazy">
                    </div>
                    <div class="col-span-1 aspect-square overflow-hidden rounded-2xl">
                        <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?q=80&w=800&auto=format&fit=crop" alt="Dokumentasi 3" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700 fallback-ready" loading="lazy">
                    </div>
                    <div class="col-span-2 lg:col-span-2 aspect-video overflow-hidden rounded-2xl">
                        <img src="https://images.unsplash.com/photo-1529070538774-1843cb3265df?q=80&w=800&auto=format&fit=crop" alt="Dokumentasi 4" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700 fallback-ready" loading="lazy">
                    </div>
                </div>
                
                <div class="mt-10">
                    <a href="{{ Route::has('galeri') ? route('galeri') : '#' }}" class="text-sm font-medium border-b pb-1 transition-colors uppercase tracking-widest hover:opacity-80" style="color: var(--home-accent); border-color: var(--home-accent)">
                        Buka Galeri Penuh &rarr;
                    </a>
                </div>
            </div>
        </section>

        <!-- 6. CTA SECTION -->
        <section class="py-20 md:py-32 px-4 sm:px-6 lg:px-8 text-center max-w-4xl mx-auto">
            <h2 class="font-editorial text-[clamp(2.5rem,6vw,5rem)] mb-6 leading-tight">Ruang untuk bergerak bersama.</h2>
            <p class="text-base md:text-lg opacity-70 mb-10 max-w-xl mx-auto font-light leading-relaxed">
                Mari berkontribusi dan menjadi bagian dari pergerakan Nahdlatul Ulama di desa Kalisari.
            </p>
            <a href="{{ Route::has('login') ? route('login') : '#' }}" class="inline-flex items-center justify-center px-8 py-4 rounded-full text-sm font-medium tracking-wide transition-transform hover:-translate-y-1" style="background: var(--home-accent); color: #fff;">
                Masuk Dashboard
            </a>
        </section>
    </main>

    @include("layouts.public-footer")
</body>
</html>

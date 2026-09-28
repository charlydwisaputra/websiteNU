<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DIGDAYA NU - Digitalisasi Data dan Layanan Nahdlatul Ulama</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.theme-script')
</head>

<body class="font-sans antialiased text-gray-900 bg-slate-50 flex flex-col min-h-screen selection:bg-emerald-500 selection:text-white relative overflow-x-hidden">

    <!-- Efek Hijau Samar -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-6xl h-48 bg-gradient-to-b from-emerald-500/20 via-emerald-500/5 to-transparent blur-3xl pointer-events-none z-40"></div>

    <!-- NAVBAR PILL (Diperlebar ke max-w-6xl) -->
    @include('layouts.public-navigation')

    <!-- HERO SECTION -->
    <div id="hero-section" class="pt-20 sm:pt-32 px-4 sm:px-6 w-full mx-auto scroll-mt-24">
        <div class="w-full max-w-[1200px] mx-auto relative overflow-visible">
            
            <!-- Ambient Glow -->
            <div class="absolute top-1/2 left-0 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-r from-emerald-100/40 to-transparent blur-3xl pointer-events-none rounded-full -z-10"></div>

            <div class="grid grid-cols-2 gap-3.5 sm:gap-6 lg:gap-8 items-center pt-2 pb-12 sm:pt-4 sm:pb-16 lg:pt-16 lg:pb-32">
                
                <!-- Left Content (Teks) -->
                <div class="relative z-10 lg:pr-8 flex flex-col items-start text-left">
                    <span class="inline-block text-[9px] sm:text-[11px] md:text-[12px] font-bold text-gray-400 uppercase tracking-[0.15em] sm:tracking-[0.2em] mb-1.5 sm:mb-3 reveal delay-100">
                        Transformasi Digital
                    </span>
                    <h1 class="text-[1.35rem] min-[400px]:text-[1.55rem] sm:text-4xl md:text-5xl lg:text-[4.5rem] font-black tracking-tight text-[#1c242c] mb-2 sm:mb-4 lg:mb-6 leading-[1.1] reveal delay-200">
                        Ekosistem<br />
                        Digital<br />
                        <span class="text-[#087A4B]">Nahdlatul<br />Ulama.</span>
                    </h1>
                    <p class="text-[11px] min-[400px]:text-xs sm:text-[15px] lg:text-[17px] text-gray-500 font-medium leading-snug sm:leading-relaxed mb-3 sm:mb-6 lg:mb-10 max-w-md reveal delay-300">
                        Mengintegrasikan tata kelola Jam'iyah dan layanan Jamaah mandiri.
                    </p>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 reveal delay-400 w-full sm:w-auto">
                        <a href="#produk-section" class="text-center bg-[#087A4B] text-white font-bold px-3.5 py-2 sm:px-6 sm:py-3 rounded-full shadow-md shadow-emerald-500/20 hover:shadow-lg hover:shadow-emerald-500/30 transition-all text-[11px] sm:text-xs md:text-sm whitespace-nowrap">
                            Jelajahi Produk
                        </a>
                        <a href="{{ route('berita.index') }}" class="text-center bg-white text-[#087A4B] font-bold px-3.5 py-2 sm:px-6 sm:py-3 rounded-full border border-gray-200 hover:bg-gray-50 transition-all text-[11px] sm:text-xs md:text-sm whitespace-nowrap dark:bg-white/5 dark:text-white dark:border-white/10 dark:hover:bg-white/10 dark:backdrop-blur-md dark:shadow-[inset_0_1px_0_rgba(255,255,255,0.1)]">
                            Lihat Dampak
                        </a>
                    </div>
                </div>

                <!-- Right Content (Foto di Samping Teks pada HP) -->
                <div class="relative z-10 reveal delay-300 w-full">
                    <!-- Decorative shadow blob behind image -->
                    <div class="absolute -inset-3 sm:-inset-6 bg-gradient-to-br from-[#087A4B]/25 via-emerald-400/15 to-transparent blur-xl sm:blur-3xl rounded-full pointer-events-none"></div>
                    <div class="absolute -bottom-4 -right-4 sm:-bottom-8 sm:-right-8 w-3/4 h-3/4 bg-emerald-500/10 blur-xl sm:blur-[60px] rounded-full pointer-events-none"></div>
                    
                    <div class="relative rounded-[1.25rem] sm:rounded-[2rem] overflow-hidden shadow-[0_12px_36px_rgba(8,122,75,0.22),0_4px_16px_rgba(0,0,0,0.08)] aspect-[4/5] sm:aspect-[4/4] lg:aspect-[4.5/5] transform hover:-translate-y-1.5 transition-all duration-500 bg-gradient-to-tr from-[#087A4B] to-emerald-400">
                        <img src="{{ asset('images/flag-nu.jpg') }}" alt="Bendera NU" class="absolute inset-0 w-full h-full object-cover">
                        
                        <!-- Base Overlay gradient tipis -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-black/15 to-transparent"></div>
                        
                        <!-- Dark Mode Blending Overlay (Very soft left-to-right/bottom-to-top transition) -->
                        <div class="hidden dark:block absolute inset-0 bg-gradient-to-tr from-[#111111]/70 via-[#111111]/15 to-transparent pointer-events-none"></div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <section class="mx-auto grid max-w-[1200px] grid-cols-2 gap-4 px-4 py-8 sm:px-6 md:grid-cols-4 relative z-10 -mt-12 sm:-mt-16">
        @foreach ($stats as $stat)
            <div class="reveal rounded-3xl border border-white/80 bg-white/70 backdrop-blur-xl p-6 text-center shadow-[0_8px_30px_rgba(0,0,0,0.04)] transition hover:-translate-y-1 hover:shadow-[0_12px_40px_rgba(0,0,0,0.08)]">
                <strong class="block text-3xl font-black text-gray-900 tracking-tight">{{ number_format($stat['value'], 0, ',', '.') }}</strong>
                <span class="mt-1.5 block text-[11px] font-semibold uppercase tracking-widest text-gray-400">{{ $stat['label'] }}</span>
            </div>
        @endforeach
    </section>

    <section class="mx-auto grid max-w-[1200px] gap-12 px-4 py-16 sm:px-6 md:grid-cols-2 md:items-center">
        <div class="reveal">
            <span class="text-[11px] font-bold uppercase tracking-widest text-[#087A4B]">Tentang NU Cikembulan</span>
            <h2 class="mt-3 text-3xl sm:text-4xl font-black tracking-tight text-gray-900">Satu ruang untuk tumbuh bersama.</h2>
            <p class="mt-5 max-w-xl text-[15px] leading-relaxed text-gray-500">Website ini menjadi pusat informasi dan digitalisasi data organisasi NU Cikembulan, membantu warga menemukan berita, dokumentasi, dan informasi anggota dengan lebih mudah.</p>
            <a href="#produk-section" class="mt-8 inline-flex rounded-full bg-gray-900 px-6 py-3 text-[14px] font-medium text-white shadow-[0_4px_12px_rgba(0,0,0,0.1)] transition hover:-translate-y-0.5 hover:shadow-[0_6px_16px_rgba(0,0,0,0.15)]">Kenali Organisasi Kami</a>
        </div>
        <div class="reveal rounded-[2.5rem] bg-gray-900 p-10 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-[#087A4B]/30 blur-3xl rounded-full"></div>
            <p class="text-[15px] leading-relaxed text-gray-300 relative z-10 font-medium">â€œDigitalisasi bukan hanya tentang teknologi, tetapi tentang membuat pelayanan dan informasi lebih dekat dengan masyarakat.â€</p>
            <div class="mt-10 h-1.5 rounded-full bg-white/10 relative z-10"><div class="h-1.5 w-3/4 rounded-full bg-[#087A4B]"></div></div>
            <p class="mt-4 text-[11px] font-bold uppercase tracking-widest text-gray-400 relative z-10">Kolaborasi â€¢ Transparansi â€¢ Pelayanan</p>
        </div>
    </section>

    <!-- Organisasi Section -->
    <div id="produk-section" class="max-w-[1200px] mx-auto px-4 sm:px-6 pt-16 pb-24 scroll-mt-28 flex-grow">
        <div class="text-center mb-16 reveal">
            <span class="text-gray-500 font-semibold text-[11px] uppercase tracking-widest block mb-3">Ekosistem Digital</span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">ORGANISASI NU</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="reveal delay-100 bg-white/60 backdrop-blur-xl p-8 rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-white/80 transition duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_rgba(0,0,0,0.08)] flex flex-col h-full group">
                <div>
                    <div class="w-14 h-14 shrink-0 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/logo%20organisasi/MuslimatNU.png') }}" alt="Logo Muslimat NU" class="w-9 h-9 object-contain transition-transform duration-300 group-hover:scale-110">
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Muslimat NU</h3>
                    <p class="text-gray-500 text-[14px] leading-relaxed">Badan otonom khusus perempuan di bawah naungan organisasi kemasyarakatan Islam terbesar di Indonesia, Nahdlatul Ulama (NU).</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="reveal delay-200 bg-white/60 backdrop-blur-xl p-8 rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-white/80 transition duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_rgba(0,0,0,0.08)] flex flex-col h-full group">
                <div>
                    <div class="h-14 shrink-0 w-max px-3 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/logo%20organisasi/FATAYAT-NU.png') }}" alt="Logo Fatayat NU" class="h-9 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Fatayat NU</h3>
                    <p class="text-gray-500 text-[14px] leading-relaxed">Badan otonom Nahdlatul Ulama untuk perempuan muda usia 20 hingga 40 tahun, bergerak di bidang keagamaan, sosial, dan pemberdayaan.</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="reveal delay-300 bg-white/60 backdrop-blur-xl p-8 rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-white/80 transition duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_rgba(0,0,0,0.08)] flex flex-col h-full group">
                <div>
                    <div class="w-14 h-14 shrink-0 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/logo%20organisasi/GP-Ansor.png') }}" alt="Logo GP Ansor" class="w-9 h-9 object-contain transition-transform duration-300 group-hover:scale-110">
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">GP Ansor</h3>
                    <p class="text-gray-500 text-[14px] leading-relaxed">Organisasi kepemudaan Islam di Indonesia yang berafiliasi dengan Nahdlatul Ulama (NU).</p>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="reveal delay-100 bg-white/60 backdrop-blur-xl p-8 rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-white/80 transition duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_rgba(0,0,0,0.08)] flex flex-col h-full group">
                <div>
                    <div class="w-14 h-14 shrink-0 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/logo%20organisasi/IPNU.png') }}" alt="Logo IPNU" class="w-9 h-9 object-contain transition-transform duration-300 group-hover:scale-110">
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">IPNU</h3>
                    <p class="text-gray-500 text-[14px] leading-relaxed">Ikatan Pelajar Nahdlatul Ulama, yaitu wadah berhimpun, komunikasi, dan kaderisasi bagi pelajar serta santri putra.</p>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="reveal delay-200 bg-white/60 backdrop-blur-xl p-8 rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-white/80 transition duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_rgba(0,0,0,0.08)] flex flex-col h-full group">
                <div>
                    <div class="w-14 h-14 shrink-0 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/logo%20organisasi/IPPNU.png') }}" alt="Logo IPPNU" class="w-9 h-9 object-contain transition-transform duration-300 group-hover:scale-110">
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">IPPNU</h3>
                    <p class="text-gray-500 text-[14px] leading-relaxed">Ikatan Pelajar Putri Nahdlatul Ulama, yaitu badan otonom yang mewadahi pelajar dan santri putri.</p>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="reveal delay-300 bg-white/60 backdrop-blur-xl p-8 rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-white/80 transition duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_rgba(0,0,0,0.08)] flex flex-col h-full group">
                <div>
                    <div class="w-14 h-14 shrink-0 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/logo%20organisasi/PMII.png') }}" alt="Logo PMII" class="w-9 h-9 object-contain transition-transform duration-300 group-hover:scale-110">
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">PMII</h3>
                    <p class="text-gray-500 text-[14px] leading-relaxed">Pergerakan Mahasiswa Islam Indonesia, yaitu organisasi kemahasiswaan ekstrakampus yang berlandaskan Islam Ahlussunnah wal Jama'ah.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('layouts.public-footer')

</body>
</html>
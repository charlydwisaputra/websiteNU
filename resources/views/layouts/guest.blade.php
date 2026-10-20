<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('organisasi.nama', 'NU Kalisari') }} — Portal Administrasi</title>
    <x-seo-meta
        :title="'Portal Administrasi — ' . config('organisasi.nama', 'NU Kalisari')"
        :description="'Masuk ke portal tata kelola administrasi Nahdlatul Ulama Ranting Kalisari.'"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.theme-script')
    <style>
        /* Fade-up animation halus untuk kartu form auth (hormati prefers-reduced-motion) */
        @keyframes authCardFadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @media (prefers-reduced-motion: no-preference) {
            .auth-card-motion {
                animation: authCardFadeUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex flex-col justify-between"
      style="background:var(--bg-base);color:var(--text-primary)">

    <div class="min-h-screen flex flex-col lg:grid lg:grid-cols-12 relative overflow-hidden">
        
        <!-- KOLOM KIRI: FORM AUTH (Mobile & Desktop) -->
        <div class="w-full lg:col-span-6 xl:col-span-5 flex flex-col justify-between p-4 sm:p-6 md:p-8 lg:p-10 z-10 relative bg-gradient-to-b from-emerald-500/[0.05] via-transparent to-emerald-500/[0.03] dark:from-emerald-950/25 dark:via-transparent dark:to-emerald-950/15">
            
            <!-- BAR POJOK ATAS: Link Kembali ke Beranda & Toggle Dark/Light (identik dengan navbar utama) -->
            <div class="flex items-center justify-between w-full max-w-md mx-auto mb-6 lg:mb-8">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-full text-xs sm:text-sm font-medium transition-all text-slate-700 dark:text-slate-300 hover:text-emerald-700 dark:hover:text-emerald-400 bg-white/80 dark:bg-[#070D09]/80 hover:bg-white dark:hover:bg-[#070D09] border border-black/5 dark:border-white/10 backdrop-blur-md shadow-xs group"
                   aria-label="Kembali ke beranda">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke beranda</span>
                </a>

                <!-- Theme Toggle Button (44px min touch area, persis seperti di navbar utama) -->
                <button type="button" 
                        onclick="window.toggleTheme()" 
                        aria-label="Ganti Tema" 
                        class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-full flex items-center justify-center cursor-pointer transition-all bg-white/80 dark:bg-[#070D09]/80 hover:bg-white dark:hover:bg-[#070D09] border border-black/5 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 backdrop-blur-md shadow-xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>

            <!-- KONTEN TENGAH: BRAND + KARTU FORM KACA HALUS -->
            <div class="w-full max-w-md mx-auto my-auto py-2">
                
                {{-- Brand Header --}}
                <div class="mb-6 text-center">
                    <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-2.5 group">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center p-2 shadow-sm transition-transform group-hover:scale-105 bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10">
                            <img src="{{ asset('images/brand/logo-nu.png') }}" alt="Logo Nahdlatul Ulama Kalisari" width="48" height="48" class="w-11 h-11 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <div class="w-full h-full rounded-xl items-center justify-center text-white font-bold text-sm hidden" style="background:var(--accent); display:none;">NU</div>
                        </div>
                        <div>
                            <p class="font-serif font-bold text-xl tracking-tight" style="color:var(--text-primary)">NU Kalisari</p>
                            <!-- Ganti DIGITAL PORTAL -> PORTAL DIGITAL -->
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mt-0.5">PORTAL DIGITAL</p>
                        </div>
                    </a>
                </div>

                {{-- Kartu Form Kaca Halus (border tipis, blur, bayangan lembut) --}}
                <div class="w-full auth-card-motion rounded-3xl p-6 sm:p-8 bg-white/80 dark:bg-[#070D09]/80 border border-black/10 dark:border-white/10 backdrop-blur-xl shadow-xl shadow-emerald-950/5 dark:shadow-black/60">
                    {{ $slot }}
                </div>

                {{-- Catatan Kecil di Bawah Kartu --}}
                <div class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">
                    <p>
                        Halaman ini khusus pengurus. Butuh akses?
                        @if(config('site.whatsapp'))
                            <a href="https://wa.me/{{ config('site.whatsapp') }}?text={{ urlencode('Assalamu\'alaikum Admin NU Kalisari, saya membutuhkan akses ke portal pengurus.') }}" 
                               target="_blank" rel="noopener noreferrer" 
                               class="font-semibold text-emerald-600 dark:text-emerald-400 hover:underline inline-flex items-center gap-1">
                                <span>Hubungi admin</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                        @else
                            <span class="font-semibold text-slate-700 dark:text-slate-300">Hubungi admin.</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Footer Hak Cipta Ringkas -->
            <div class="w-full max-w-md mx-auto pt-6 text-center">
                <p class="text-[11px] text-slate-400 dark:text-slate-600">
                    &copy; {{ date('Y') }} Pengurus Ranting NU Kalisari. Hak cipta dilindungi.
                </p>
            </div>
        </div>

        <!-- KOLOM KANAN: PANEL FOTO UDARA KALISARI (Desktop Only, disembunyikan di Mobile) -->
        <div class="hidden lg:flex lg:col-span-6 xl:col-span-7 relative overflow-hidden bg-[#063323] items-center justify-center">
            <!-- Foto Udara Latar -->
            <picture>
                <source 
                    type="image/webp"
                    srcset="{{ asset('images/banner-1-768.webp') }} 768w, {{ asset('images/banner-1-1280.webp') }} 1280w, {{ asset('images/banner-1-1920.webp') }} 1920w"
                    sizes="(max-width: 1280px) 50vw, 60vw">
                <source 
                    type="image/jpeg"
                    srcset="{{ asset('images/banner-1-1920.jpg') }} 1920w"
                    sizes="60vw">
                <img 
                    src="{{ asset('images/banner-1-1920.jpg') }}" 
                    alt="Pemandangan udara bentang alam dan desa Kalisari" 
                    width="1920" 
                    height="1280" 
                    class="absolute inset-0 w-full h-full object-cover object-[center_55%] select-none pointer-events-none scale-105 transition-transform duration-1000 ease-out">
            </picture>

            <!-- Overlay Hijau Gelap & Gradasi Artistik NU -->
            <div class="absolute inset-0 bg-[#063a28]/60 mix-blend-multiply pointer-events-none"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#02150e]/95 via-[#02150e]/60 to-[#02150e]/30 pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,rgba(5,150,105,0.35),transparent_75%)] pointer-events-none"></div>

            <!-- Teks Tagline & Kutipan di Kolom Kanan -->
            <div class="relative z-10 p-10 lg:p-14 xl:p-16 text-white max-w-lg text-left my-auto">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 backdrop-blur-md mb-6 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Khidmah Warga Nahdliyin</span>
                </div>

                <h2 class="font-serif text-3xl xl:text-5xl font-medium tracking-tight leading-tight mb-4 text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.7)]">
                    Tumbuh Bersama.<br>
                    <span class="text-emerald-400">Bergerak Bersama.</span>
                </h2>

                <p class="text-base xl:text-lg text-emerald-100/90 font-light leading-relaxed mb-8 drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]">
                    Satu portal terintegrasi untuk pendataan anggota, syi'ar kegiatan ranting, dan penguatan badan otonom di Desa Kalisari.
                </p>

                <!-- Nilai-nilai Kelembagaan -->
                <div class="grid grid-cols-2 gap-4 pt-6 border-t border-white/15">
                    <div>
                        <p class="font-serif text-xl font-bold text-white mb-0.5">Amanah</p>
                        <p class="text-xs text-emerald-100/70 font-light">Tata kelola organisasi yang transparan dan tertib.</p>
                    </div>
                    <div>
                        <p class="font-serif text-xl font-bold text-white mb-0.5">Khidmah</p>
                        <p class="text-xs text-emerald-100/70 font-light">Dedikasi tulus melayani jamaah dan jam'iyyah.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>

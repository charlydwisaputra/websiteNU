<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $berita->title }} - NU CIKEMBULAN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.theme-script')
</head>
<body class="font-sans antialiased text-gray-900 bg-[#FAFCFB] flex flex-col min-h-screen relative overflow-x-hidden selection:bg-emerald-100 selection:text-[#087A4B]">

    <!-- Ambient Glow Background -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[450px] bg-gradient-to-b from-emerald-500/10 via-emerald-500/5 to-transparent blur-3xl pointer-events-none z-0"></div>

    <!-- Floating Glass Navbar yang Selaras -->
    @include('layouts.public-navigation')

    <!-- Main Content Container -->
    <main class="pt-28 md:pt-32 pb-20 flex-grow relative z-10">
        <div class="max-w-[960px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-[#087A4B] transition-colors">Beranda</a>
                <span>/</span>
                <a href="{{ route('berita.index') }}" class="hover:text-[#087A4B] transition-colors">Berita</a>
                <span>/</span>
                <span class="text-gray-600 truncate max-w-[200px] sm:max-w-xs">{{ $berita->title }}</span>
            </nav>

            <!-- Article Container Card -->
            <article class="bg-white/80 backdrop-blur-2xl rounded-[2rem] border border-gray-200/70 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                
                <!-- Article Header -->
                <div class="p-6 sm:p-10 md:p-12 pb-6 sm:pb-8 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2.5 mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-50 text-[#087A4B] border border-emerald-200/60 uppercase tracking-wider">
                            {{ $berita->category ?? 'Kegiatan' }}
                        </span>
                        <span class="text-xs text-gray-400 font-medium flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $berita->created_at->isoFormat('dddd, D MMMM Y') ?? $berita->created_at->format('l, d F Y') }}
                        </span>
                        <span class="text-xs text-gray-300">â€¢</span>
                        <span class="text-xs text-gray-400 font-medium">Oleh Redaksi NU Cikembulan</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-gray-900 tracking-tight leading-[1.25]">
                        {{ $berita->title }}
                    </h1>
                </div>

                <!-- Featured Image -->
                @if($berita->image)
                    <div class="px-6 sm:px-10 md:px-12 pt-6">
                        <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 relative max-h-[500px] bg-slate-900">
                            <img src="{{ asset('storage/'.$berita->image) }}" alt="{{ $berita->title }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                @endif

                <!-- Article Body -->
                <div class="p-6 sm:p-10 md:p-12 pt-8 text-[16px] sm:text-[17px] text-gray-700 leading-[1.8] space-y-4">
                    <div class="prose prose-emerald max-w-none text-gray-700 leading-relaxed font-normal">
                        {!! nl2br(e($berita->content)) !!}
                    </div>
                </div>

                <!-- Bottom Bar: Back to News & Share -->
                <div class="p-6 sm:p-10 bg-gray-50/70 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#087A4B] hover:text-[#065C39] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Kembali ke Semua Warta & Berita</span>
                    </a>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-400">Bagikan:</span>
                        <button onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berita berhasil disalin!');" class="px-3.5 py-1.5 bg-white hover:bg-gray-100 border border-gray-200 text-gray-700 text-xs font-bold rounded-full shadow-xs transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Salin Tautan
                        </button>
                    </div>
                </div>
            </article>

            <!-- Berita Terkait Section -->
            @if(isset($terkait) && $terkait->count() > 0)
                <div class="mt-12">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1.5 h-4 bg-[#087A4B] rounded-full"></div>
                        <h2 class="text-xl font-black text-gray-900 tracking-tight">Berita Lainnya</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($terkait as $item)
                            <a href="{{ route('berita.show', $item->id) }}" class="group bg-white/70 backdrop-blur-xl border border-gray-200/60 rounded-2xl p-3 hover:bg-white hover:shadow-md transition-all duration-200 flex flex-col">
                                <div class="w-full aspect-[16/10] rounded-xl overflow-hidden bg-gray-100 mb-3 relative">
                                    @if($item->image)
                                        <img src="{{ asset('storage/'.$item->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-emerald-700 text-white font-black text-xs">NU</div>
                                    @endif
                                </div>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-[#087A4B] mb-1">{{ $item->category ?? 'Kegiatan' }}</span>
                                <h3 class="text-xs font-bold text-gray-900 group-hover:text-[#087A4B] transition-colors line-clamp-2 leading-snug">{{ $item->title }}</h3>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </main>

    <!-- Footer Selaras -->
    @include('layouts.public-footer')

</body>
</html>
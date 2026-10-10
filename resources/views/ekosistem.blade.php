<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ekosistem — NU Kalisari</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.theme-script')
</head>
<body class="font-sans antialiased flex flex-col min-h-screen overflow-x-hidden"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-24 flex-grow w-full">
        {{-- PAGE HEADER --}}
        <header class="mb-16 pb-10 text-center" style="border-bottom:1px solid var(--border-light)">
            <p class="text-label mb-3 uppercase tracking-[0.2em] font-semibold text-xs" style="color:var(--accent)">Organisasi & Badan Otonom</p>
            <h1 class="font-serif text-5xl md:text-7xl font-bold tracking-tight mb-6" style="color:var(--text-primary)">
                Ekosistem Kalisari.
            </h1>
            <p class="text-lg md:text-xl leading-relaxed max-w-2xl mx-auto font-light" style="color:var(--text-secondary)">
                Kolaborasi berbagai elemen dalam merawat tradisi dan membangun masyarakat melalui bingkai Nahdlatul Ulama.
            </p>
        </header>

        {{-- CONTENT --}}
        @php
            $orgs = [
                ['name' => 'Muslimat NU', 'desc' => 'Pergerakan perempuan Islam untuk kesejahteraan keluarga dan keumatan.', 'logo' => 'MuslimatNU.png'],
                ['name' => 'Fatayat NU', 'desc' => 'Wadah pemudi NU dalam pengembangan diri, agama, dan sosial budaya.', 'logo' => 'FATAYAT-NU.png'],
                ['name' => 'GP Ansor', 'desc' => 'Pemuda NU yang bergerak dalam sosial kemasyarakatan dan kepemudaan.', 'logo' => 'GP-Ansor.png'],
                ['name' => 'IPNU', 'desc' => 'Pelajar putra NU yang berfokus pada kaderisasi dan pendidikan.', 'logo' => 'IPNU.png'],
                ['name' => 'IPPNU', 'desc' => 'Pelajar putri NU yang berdedikasi pada kemajuan pelajar dan tradisi.', 'logo' => 'IPPNU.png']
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($orgs as $org)
            <div class="rounded-3xl p-10 flex flex-col justify-between h-full group hover:-translate-y-2 transition-all duration-500" 
                 style="background:var(--bg-surface); border:1px solid var(--border-light); box-shadow:0 4px 24px -4px rgba(0,0,0,0.03);">
                <div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-8 shadow-sm transition-transform duration-500 group-hover:scale-110 overflow-hidden bg-white">
                        <img src="{{ asset('storage/logo organisasi/' . $org['logo']) }}" alt="{{ $org['name'] }}" class="w-full h-full object-contain p-2">
                    </div>
                    <h3 class="font-serif text-3xl font-bold mb-4">{{ $org['name'] }}</h3>
                    <p class="text-base font-light leading-relaxed" style="color:var(--text-secondary)">{{ $org['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @include('layouts.public-footer')
</body>
</html>
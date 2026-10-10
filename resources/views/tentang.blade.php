<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang — NU Kalisari</title>
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

    <div class="max-w-[900px] mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-24 flex-grow w-full">
        {{-- PAGE HEADER --}}
        <header class="mb-16 pb-10 text-center" style="border-bottom:1px solid var(--border-light)">
            <p class="text-label mb-3 uppercase tracking-[0.2em] font-semibold text-xs" style="color:var(--accent)">Tentang Kalisari</p>
            <h1 class="font-serif text-5xl md:text-7xl font-bold tracking-tight mb-6" style="color:var(--text-primary)">
                Satu Ekosistem.<br>Satu Pergerakan.
            </h1>
            <p class="text-lg md:text-xl leading-relaxed max-w-2xl mx-auto font-light" style="color:var(--text-secondary)">
                Kalisari bukan sekadar tempat. Ini adalah ekosistem masyarakat, organisasi, dan aktivitas yang terus bergerak bersama merawat jejak langkah Nahdlatul Ulama.
            </p>
        </header>

        {{-- CONTENT --}}
        <article class="prose prose-lg dark:prose-invert max-w-none font-serif text-xl leading-relaxed" style="color:var(--text-primary)">
            <p>
                Platform digital <strong>NU Kalisari</strong> dibangun sebagai jembatan informasi, mempererat silaturahmi, dan ruang kolaborasi bagi masyarakat Kalisari, Cilongok, Banyumas. 
            </p>
            <p>
                Kami menyadari bahwa di era modern, pergerakan tidak hanya terjadi di ruang-ruang pertemuan fisik, tetapi juga di ruang digital. Melalui platform ini, kami mendokumentasikan kegiatan, warta, dan dinamika dari berbagai Badan Otonom Nahdlatul Ulama yang ada di desa kami.
            </p>
            <br>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 my-10">
                <div class="p-8 rounded-3xl" style="background:var(--bg-surface-elevated)">
                    <h3 class="font-serif text-3xl font-bold mb-4">Visi</h3>
                    <p class="font-sans text-sm md:text-base font-light" style="color:var(--text-secondary)">
                        Menjadi ekosistem digital terintegrasi yang memberdayakan masyarakat dan merawat tradisi Ahlussunnah wal Jamaah di desa Kalisari.
                    </p>
                </div>
                <div class="p-8 rounded-3xl" style="background:var(--bg-surface-elevated)">
                    <h3 class="font-serif text-3xl font-bold mb-4">Misi</h3>
                    <p class="font-sans text-sm md:text-base font-light" style="color:var(--text-secondary)">
                        Mendokumentasikan rekam jejak, menyebarkan warta yang bermanfaat, dan menjadi simpul penghubung seluruh elemen Nahdlatul Ulama di tingkat ranting.
                    </p>
                </div>
            </div>
            
            <p class="text-center italic mt-12 font-sans text-base" style="color:var(--text-muted)">
                "Tumbuh Bersama. Bergerak Bersama."
            </p>
        </article>
    </div>

    @include('layouts.public-footer')
</body>
</html>
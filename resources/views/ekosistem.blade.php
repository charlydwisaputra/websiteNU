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
<body class="font-sans antialiased flex flex-col min-h-screen overflow-x-hidden"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-24 flex-grow w-full">
        <!-- PAGE HEADER -->
        <header class="mb-16 pb-10 border-b border-black/5 dark:border-white/5">
            <h1 class="font-serif text-[clamp(2.5rem,6vw,5rem)] font-medium tracking-tight mb-4" style="color:var(--text-primary)">
                Ekosistem
            </h1>
            <p class="text-lg md:text-xl leading-relaxed max-w-2xl font-light" style="color:var(--text-secondary)">
                Badan otonom dan lembaga yang bersama-sama menghidupkan dan memberdayakan masyarakat di lingkungan Nahdlatul Ulama Kalisari.
            </p>
        </header>

        <!-- CONTENT -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach(config('organisasi.banom') as $org)
            <div class="p-8 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/5 hover:border-emerald-500/30 transition-colors flex flex-col md:flex-row gap-6">
                <div class="w-16 h-16 shrink-0 rounded-xl bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-serif text-3xl font-medium overflow-hidden">
                    @if(isset($org['logo']))
                        <img src="{{ asset('storage/logo-organisasi/' . $org['logo']) }}" alt="{{ $org['nama'] }}" class="w-full h-full object-contain p-2 dark:brightness-200 dark:contrast-100" loading="lazy">
                    @else
                        {{ substr($org['nama'], 0, 1) }}
                    @endif
                </div>
                <div class="flex-grow">
                    <h3 class="font-serif text-3xl font-medium mb-3" style="color:var(--text-primary)">{{ $org['nama'] }}</h3>
                    <p class="text-sm font-normal leading-relaxed mb-6" style="color:var(--text-secondary)">{{ $org['deskripsi'] }}</p>
                    
                    @php
                        $koordinator = $org['koordinator'] ?? $org['coordinator'] ?? null;
                        $hasKoordinator = !empty($koordinator) && !str_contains($koordinator, 'ISI:');
                        $jadwal = $org['jadwal_rutin'] ?? $org['jadwal'] ?? null;
                        $hasJadwal = !empty($jadwal) && !str_contains($jadwal, 'ISI:');
                    @endphp
                    @if($hasKoordinator || $hasJadwal)
                    <div class="pt-4 border-t border-black/5 dark:border-white/5 grid grid-cols-2 gap-4">
                        @if($hasKoordinator)
                        <div>
                            <p class="text-xs uppercase tracking-widest font-semibold mb-1" style="color:var(--text-muted)">Koordinator</p>
                            <p class="text-sm" style="color:var(--text-primary)">{{ $koordinator }}</p>
                        </div>
                        @endif
                        @if($hasJadwal)
                        <div>
                            <p class="text-xs uppercase tracking-widest font-semibold mb-1" style="color:var(--text-muted)">Jadwal Rutin</p>
                            <p class="text-sm" style="color:var(--text-primary)">{{ $jadwal }}</p>
                        </div>
                        @endif
                    </div>
                    @endif
                    <div class="mt-6 pt-4 border-t border-black/5 dark:border-white/5 flex justify-end">
                        <a href="{{ route('anggota', ['banom' => $org['singkatan']]) }}" class="text-xs font-semibold uppercase tracking-widest transition-colors hover:text-emerald-600 dark:hover:text-emerald-400" style="color:var(--text-primary)">
                            Lihat anggota &rarr;
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @include('layouts.public-footer')
</body>
</html>

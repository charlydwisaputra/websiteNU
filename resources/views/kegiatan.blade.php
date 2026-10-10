1<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kegiatan — NU Kalisari</title>
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
        <header class="mb-16 pb-10" style="border-bottom:1px solid var(--border-light)">
            <p class="text-label mb-3 uppercase tracking-[0.2em] font-semibold text-xs" style="color:var(--accent)">WARTA & GERAK</p>
            <h1 class="font-serif text-5xl md:text-7xl font-bold tracking-tight mb-4" style="color:var(--text-primary)">
                Kegiatan.
            </h1>
            <p class="text-lg md:text-xl leading-relaxed max-w-2xl font-light" style="color:var(--text-secondary)">
                Informasi terbaru seputar kegiatan dan agenda yang telah atau akan dilaksanakan oleh seluruh elemen masyarakat dan Banom NU Kalisari.
            </p>
        </header>

        {{-- CONTENT --}}
        @if($kegiatan->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
            @foreach($kegiatan as $item)
            <a href="{{ route('berita.show', $item->id) }}" class="group block">
                <div class="w-full aspect-[4/3] rounded-3xl overflow-hidden mb-6" style="background:var(--bg-surface-elevated)">
                    @if($item->image)
                        <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-xs uppercase tracking-widest font-semibold" style="color:var(--text-muted)">Tanpa Gambar</div>
                    @endif
                </div>
                <div class="flex items-center gap-3 mb-3 text-[10px] font-bold uppercase tracking-widest">
                    <span style="color:var(--accent)">{{ $item->category ?: 'Kegiatan' }}</span>
                    <span style="color:var(--border-medium)">·</span>
                    <span style="color:var(--text-muted)">{{ $item->created_at->format('d M Y') }}</span>
                </div>
                <h3 class="font-serif text-2xl font-bold leading-snug line-clamp-2 mb-3 transition-colors"
                    style="color:var(--text-primary)"
                    onmouseover="this.style.color='var(--accent)'"
                    onmouseout="this.style.color='var(--text-primary)'">
                    {{ $item->title }}
                </h3>
                <p class="text-sm font-light leading-relaxed line-clamp-3" style="color:var(--text-secondary)">
                    {{ \Illuminate\Support\Str::limit(strip_tags($item->content), 120) }}
                </p>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-24 rounded-3xl" style="background:var(--bg-surface-elevated)">
            <h3 class="font-serif text-3xl font-bold mb-3" style="color:var(--text-primary)">Belum ada kegiatan</h3>
            <p class="text-base font-light max-w-md mx-auto" style="color:var(--text-secondary)">
                Belum ada rekam kegiatan yang dipublikasikan saat ini.
            </p>
        </div>
        @endif
    </div>

    @include('layouts.public-footer')
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anggota — NU Kalisari</title>
    <x-seo-meta
        :title="'Anggota — NU Kalisari'"
        :description="'Direktori anggota Nahdlatul Ulama Ranting Kalisari.'"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.theme-script')
</head>
<body class="font-sans antialiased flex flex-col min-h-screen overflow-x-hidden"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    <main class="relative z-10 pt-28 sm:pt-36 lg:pt-40 pb-24 flex-grow flex flex-col items-center px-4 sm:px-6">

        {{-- Header --}}
        <div class="text-center max-w-2xl mx-auto mb-14 reveal">
            <p class="text-label mb-3" style="color:var(--accent)">DIREKTORI ANGGOTA</p>
            @if(isset($banom) && $banom)
                <h1 class="font-serif text-5xl md:text-6xl font-bold tracking-tight mb-4" style="color:var(--text-primary)">
                    Anggota <span style="color:var(--accent)">{{ $banom }}</span>
                </h1>
                <p class="text-lg leading-relaxed mb-4" style="color:var(--text-secondary)">
                    Menampilkan anggota dari {{ $banom }} NU Kalisari.
                </p>
            @else
                <h1 class="font-serif text-5xl md:text-6xl font-bold tracking-tight mb-4" style="color:var(--text-primary)">
                    Orang-Orang di Balik<br><span style="color:var(--accent)">Gerakan</span>
                </h1>
                <p class="text-lg leading-relaxed mb-4" style="color:var(--text-secondary)">
                    Kenali anggota yang menjadi bagian dari perjalanan dan gerakan NU Kalisari.
                </p>
            @endif
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold" style="background:var(--accent-subtle);color:var(--accent)">
                {{ $anggotas->total() }} anggota
            </div>
        </div>

        {{-- Search + Filter --}}
        <div class="w-full max-w-[1100px] mx-auto mb-14 reveal">

            {{-- Search --}}
            <form action="{{ route('anggota') }}" method="GET" class="w-full max-w-xl mx-auto mb-8 relative group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none"
                     style="color:var(--text-muted)">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="search" name="search" id="search-input"
                       value="{{ request('search') }}"
                       placeholder="Cari anggota, posisi, atau wilayah..."
                       aria-label="Cari anggota"
                       class="block w-full pl-14 pr-5 py-4 rounded-2xl text-sm font-medium outline-none transition-all"
                       style="background:var(--bg-surface);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px var(--accent-subtle)'"
                       onblur="this.style.borderColor='var(--border-medium)';this.style.boxShadow=''">
                <input type="hidden" name="banom" value="{{ request('banom') }}">
            </form>

            {{-- Filter pills --}}
            @php
                $filters = ['Semua' => null, 'Muslimat' => 'Muslimat', 'Fatayat' => 'Fatayat', 'GP Ansor' => 'GP Ansor', 'IPNU' => 'IPNU', 'IPPNU' => 'IPPNU', 'PMII' => 'PMII'];
                $active  = request('banom');
            @endphp
            <div class="flex gap-2 overflow-x-auto scrollbar-none pb-1 justify-start md:justify-center">
                @foreach($filters as $label => $value)
                <a href="{{ route('anggota', ['banom' => $value, 'search' => request('search')]) }}"
                   class="shrink-0 px-5 py-2 rounded-full text-sm font-semibold transition-all border"
                   style="{{ ($active == $value)
                       ? 'background:var(--accent);color:#fff;border-color:var(--accent)'
                       : 'background:var(--bg-surface);color:var(--text-secondary);border-color:var(--border-medium)' }}"
                   onmouseover="{{ ($active == $value) ? '' : "this.style.borderColor='var(--text-muted)';this.style.color='var(--text-primary)'" }}"
                   onmouseout="{{ ($active == $value) ? '' : "this.style.borderColor='var(--border-medium)';this.style.color='var(--text-secondary)'" }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- Member grid --}}
        <div class="w-full max-w-[1100px] mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($anggotas as $anggota)
            <div class="group flex flex-col text-center surface-card rounded-2xl p-6 hover-lift cursor-default reveal">
                {{-- Avatar --}}
                <div class="relative mx-auto mb-4 w-20 h-20">
                    @if($anggota->photo)
                        <img src="{{ asset('storage/'.$anggota->photo) }}" alt="{{ $anggota->name }}"
                             class="w-full h-full rounded-full object-cover transition-transform group-hover:scale-105">
                    @else
                        <div class="w-full h-full rounded-full flex items-center justify-center text-xl font-bold transition-all group-hover:scale-105"
                             style="background:var(--bg-surface-elevated);color:var(--text-muted);border:1px solid var(--border-light)">
                            {{ strtoupper(substr($anggota->name, 0, 2)) }}
                        </div>
                    @endif
                    @if($anggota->position)
                    <div class="absolute -bottom-1 -right-1 rounded-full p-0.5"
                         style="background:var(--bg-surface);border:1px solid var(--border-light)">
                        <span class="block text-[9px] font-bold px-2 py-0.5 rounded-full"
                              style="background:var(--accent-subtle);color:var(--accent)">
                            {{ \Illuminate\Support\Str::limit($anggota->position, 8, '') }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Info --}}
                <h3 class="font-serif text-base font-bold mb-1 truncate w-full transition-colors"
                    style="color:var(--text-primary)"
                    onmouseover="this.style.color='var(--accent)'"
                    onmouseout="this.style.color='var(--text-primary)'">
                    {{ $anggota->name }}
                </h3>
                @if($anggota->position)
                <p class="text-xs font-semibold mb-3 truncate" style="color:var(--accent)">
                    {{ $anggota->position }}
                </p>
                @endif

                @if($anggota->region)
                <div class="mt-auto inline-flex items-center justify-center gap-1 text-xs font-medium mx-auto px-3 py-1.5 rounded-full max-w-full"
                     style="background:var(--bg-surface-elevated);color:var(--text-secondary)">
                    <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="truncate">{{ $anggota->region }}</span>
                </div>
                @endif
            </div>
            @empty
            <div class="col-span-full flex flex-col items-center justify-center py-20 px-4 rounded-2xl"
                 style="background:var(--bg-surface);border:1px dashed var(--border-medium)">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4"
                     style="background:var(--bg-surface-elevated);color:var(--text-muted)">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="font-serif text-xl font-bold mb-2" style="color:var(--text-primary)">Belum ada anggota{{ isset($banom) && $banom ? ' ' . $banom : '' }}</h3>
                <p class="text-sm text-center max-w-sm mb-4" style="color:var(--text-secondary)">
                    Belum ada anggota{{ isset($banom) && $banom ? ' ' . $banom : '' }} yang terdaftar.
                </p>
                <a href="{{ route('anggota') }}" class="text-sm font-semibold transition-colors hover:underline" style="color:var(--accent)">
                    Lihat semua anggota &rarr;
                </a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($anggotas->hasPages())
        <div class="w-full max-w-[1100px] mx-auto mt-12 flex justify-center">
            {{ $anggotas->links() }}
        </div>
        @endif

    </main>

    @include('layouts.public-footer')

    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && !['INPUT','TEXTAREA'].includes(document.activeElement.tagName)) {
                e.preventDefault();
                document.getElementById('search-input')?.focus();
            }
        });
    </script>
</body>
</html>

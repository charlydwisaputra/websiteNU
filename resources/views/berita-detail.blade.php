<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $berita->title }} — Warta NU Cikembulan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.theme-script')
</head>
<body class="font-sans antialiased flex flex-col min-h-screen overflow-x-hidden"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    {{-- Hero image --}}
    @if($berita->image)
    <div class="w-full max-w-[1100px] mx-auto px-4 sm:px-6 mt-28 mb-10 reveal">
        <div class="w-full aspect-video md:aspect-[21/9] rounded-3xl overflow-hidden relative"
             style="background:var(--black-iris)">
            <img src="{{ asset('storage/'.$berita->image) }}" alt="{{ $berita->title }}"
                 class="w-full h-full object-cover opacity-95">
            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(8,8,19,0.35), transparent 50%)"></div>
        </div>
    </div>
    @else
    <div class="pt-32"></div>
    @endif

    <main class="flex-grow w-full pb-20">

        {{-- Article --}}
        <div class="max-w-2xl mx-auto px-4 sm:px-6">
            <article class="reveal">

                {{-- Breadcrumb + meta --}}
                <div class="flex flex-col items-center text-center mb-10">
                    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest mb-6"
                         style="color:var(--text-muted)">
                        <a href="{{ route('home') }}" class="transition-colors"
                           onmouseover="this.style.color='var(--accent)'"
                           onmouseout="this.style.color='var(--text-muted)'">Beranda</a>
                        <span>/</span>
                        <a href="{{ route('berita.index') }}" class="transition-colors"
                           onmouseover="this.style.color='var(--accent)'"
                           onmouseout="this.style.color='var(--text-muted)'">Warta</a>
                        <span>/</span>
                        <span style="color:var(--accent)">{{ $berita->category ?? 'Kegiatan' }}</span>
                    </nav>

                    <h1 class="font-serif text-3xl md:text-5xl lg:text-6xl font-bold tracking-tight leading-tight mb-8"
                        style="color:var(--text-primary)">
                        {{ $berita->title }}
                    </h1>

                    <div class="flex items-center gap-4 text-xs font-semibold uppercase tracking-wider py-4 w-full justify-center"
                         style="border-top:1px solid var(--border-light);border-bottom:1px solid var(--border-light);color:var(--text-muted)">
                        <span class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full" style="background:var(--accent)"></span>
                            {{ $berita->author->name ?? 'Redaksi' }}
                        </span>
                        <span>·</span>
                        <span>{{ $berita->created_at->isoFormat('D MMMM Y') }}</span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="prose prose-lg max-w-none leading-[1.9]"
                     style="color:var(--text-secondary)">
                    {!! nl2br(e($berita->content)) !!}
                </div>

                {{-- Actions --}}
                <div class="mt-16 pt-8 flex flex-col sm:flex-row items-center justify-between gap-6"
                     style="border-top:1px solid var(--border-light)">
                    <a href="{{ route('berita.index') }}"
                       class="inline-flex items-center gap-2 text-sm font-semibold transition-colors"
                       style="color:var(--text-secondary)"
                       onmouseover="this.style.color='var(--accent)'"
                       onmouseout="this.style.color='var(--text-secondary)'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Warta
                    </a>

                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold uppercase tracking-widest" style="color:var(--text-muted)">Bagikan:</span>
                        <button aria-label="Salin tautan"
                                onclick="navigator.clipboard.writeText(window.location.href).then(()=>{ this.style.background='var(--accent)'; this.style.color='#fff'; setTimeout(()=>{ this.style.background=''; this.style.color=''; },1500) })"
                                class="w-9 h-9 rounded-full flex items-center justify-center transition-all border"
                                style="border-color:var(--border-medium);color:var(--text-secondary)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </article>
        </div>

        {{-- Related articles --}}
        @if(isset($terkait) && $terkait->count() > 0)
        <div class="max-w-[1100px] mx-auto px-4 sm:px-6 mt-24 pt-16 reveal"
             style="border-top:1px solid var(--border-light)">
            <div class="mb-8 pb-2 inline-block" style="border-bottom:2px solid var(--text-primary)">
                <h3 class="text-xs font-bold uppercase tracking-widest" style="color:var(--text-primary)">Baca Juga</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($terkait as $item)
                <a href="{{ route('berita.show', $item->id) }}" class="group flex flex-col gap-4">
                    <div class="w-full aspect-[16/10] rounded-xl overflow-hidden img-zoom-wrap"
                         style="background:var(--bg-surface-elevated);border:1px solid var(--border-light)">
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" class="w-full h-full object-cover" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-xs font-bold opacity-30"
                                 style="color:var(--text-muted)">NU</div>
                        @endif
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest"
                              style="color:var(--accent)">{{ $item->category ?? 'Kegiatan' }}</span>
                        <h4 class="font-serif text-base font-bold leading-snug line-clamp-2 mt-1 transition-colors"
                            style="color:var(--text-primary)"
                            onmouseover="this.style.color='var(--accent)'"
                            onmouseout="this.style.color='var(--text-primary)'">
                            {{ $item->title }}
                        </h4>
                        <span class="text-xs font-medium mt-1 block" style="color:var(--text-muted)">
                            {{ $item->created_at->format('d M Y') }}
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </main>

    @include('layouts.public-footer')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $berita->title }} — Warta NU Kalisari</title>
    <x-seo-meta
        :title="$berita->title . ' — Warta NU Kalisari'"
        :description="\Illuminate\Support\Str::limit(strip_tags($berita->content), 160)"
        :image="$berita->image ? asset('storage/' . $berita->image) : asset('images/flag-nu.jpg')"
        type="article"
    />
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
                 class="w-full h-full object-cover opacity-95" loading="lazy">
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
                            {{ ($berita->author->name ?? 'Redaksi') === 'admin' ? 'Redaksi NU Kalisari' : ($berita->author->name ?? 'Redaksi NU Kalisari') }}
                        </span>
                        <span>·</span>
                        <span>@tanggal($berita->created_at)</span>
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
                        <a href="https://wa.me/?text={{ urlencode($berita->title . ' — ' . route('berita.show', $berita->id)) }}"
                           target="_blank" rel="noopener noreferrer"
                           aria-label="Bagikan ke WhatsApp"
                           class="w-9 h-9 min-w-[44px] min-h-[44px] rounded-full flex items-center justify-center transition-all border hover:bg-green-500 hover:text-white hover:border-green-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500"
                           style="border-color:var(--border-medium);color:var(--text-secondary)">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        <button aria-label="Salin tautan"
                                onclick="navigator.clipboard.writeText(window.location.href).then(()=>{ this.style.background='var(--accent)'; this.style.color='#fff'; setTimeout(()=>{ this.style.background=''; this.style.color=''; },1500) })"
                                class="w-9 h-9 min-w-[44px] min-h-[44px] rounded-full flex items-center justify-center transition-all border focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500"
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
            <div class="-mx-4 px-4 sm:mx-0 sm:px-0 flex sm:grid sm:grid-cols-2 lg:grid-cols-4 gap-6 overflow-x-auto sm:overflow-visible snap-x snap-mandatory overscroll-x-contain scrollbar-none pb-4 sm:pb-0" style="scroll-snap-type: x mandatory; overscroll-behavior-x: contain;">
                @foreach($terkait as $item)
                <a href="{{ route('berita.show', $item->id) }}" class="group flex flex-col gap-4 flex-[0_0_75%] sm:flex-initial w-[75%] sm:w-auto shrink-0 snap-start" style="scroll-snap-align: start;">
                    <div class="w-full aspect-[16/10] rounded-xl overflow-hidden img-zoom-wrap"
                         style="background:var(--bg-surface-elevated);border:1px solid var(--border-light)">
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" class="w-full h-full object-cover" alt="{{ $item->title }}" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-xs font-bold opacity-30"
                                 style="color:var(--text-muted)">NU</div>
                        @endif
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest"
                              style="color:var(--accent)">{{ $item->category ?? 'Kegiatan' }}</span>
                        <h4 class="font-serif text-base font-semibold leading-snug line-clamp-2 mt-1 transition-colors"
                            style="color:var(--text-primary)"
                            onmouseover="this.style.color='var(--accent)'"
                            onmouseout="this.style.color='var(--text-primary)'">
                            {{ $item->title }}
                        </h4>
                        <span class="text-xs font-medium mt-1 block" style="color:var(--text-muted)">
                            @tanggal($item->created_at)
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

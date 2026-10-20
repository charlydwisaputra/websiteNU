<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $berita->title }} — Warta {{ config('organisasi.nama') }}</title>
    <x-seo-meta
        :title="$berita->title . ' — Warta ' . config('organisasi.nama')"
        :description="\Illuminate\Support\Str::limit(strip_tags($berita->content), 160)"
        :image="$berita->image ? asset('storage/' . $berita->image) : asset('images/brand/logo-nu.png')"
        :url="route('berita.show', $berita->id)"
        type="article"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.theme-script')
</head>
<body class="font-sans antialiased flex flex-col min-h-screen"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    @php
        $category = $berita->category ?: 'Kegiatan';
        $badgeClass = match ($category) {
            'Pengumuman' => 'text-amber-700 dark:text-amber-300 bg-amber-500/10 border-amber-500/25',
            'Organisasi' => 'text-sky-700 dark:text-sky-300 bg-sky-500/10 border-sky-500/25',
            'Sosial'     => 'text-rose-700 dark:text-rose-300 bg-rose-500/10 border-rose-500/25',
            default      => 'text-emerald-700 dark:text-emerald-300 bg-emerald-500/10 border-emerald-500/25',
        };
    @endphp

    {{-- Hero image / Header banner --}}
    <div class="w-full max-w-[1100px] mx-auto px-4 sm:px-6 mt-24 md:mt-28 mb-6 md:mb-8 reveal">
        @if($berita->image)
        <div class="w-full aspect-video md:aspect-[21/9] max-h-[460px] rounded-2xl sm:rounded-3xl overflow-hidden relative border border-black/5 dark:border-white/5 shadow-sm"
             style="background:var(--bg-surface-elevated)">
            <img src="{{ asset('storage/'.$berita->image) }}" alt="{{ $berita->title }}"
                 class="w-full h-full object-cover" loading="lazy">
            <div class="absolute inset-0 pointer-events-none" style="background:linear-gradient(to top, rgba(0,0,0,0.35), transparent 60%)"></div>
        </div>
        @else
        <div class="w-full aspect-[21/9] max-h-[300px] rounded-2xl sm:rounded-3xl overflow-hidden flex flex-col items-center justify-center bg-gradient-to-br from-[#064e3b] via-[#04382a] to-[#022118] text-emerald-300/80 border border-black/5 dark:border-white/5 shadow-sm select-none relative">
            <div class="absolute inset-0 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:20px_20px] opacity-15"></div>
            <div class="relative z-10 flex flex-col items-center justify-center">
                @if($category === 'Kegiatan')
                    <svg class="w-14 h-14 mb-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                @elseif($category === 'Pengumuman')
                    <svg class="w-14 h-14 mb-2 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                @elseif($category === 'Organisasi')
                    <svg class="w-14 h-14 mb-2 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                @elseif($category === 'Sosial')
                    <svg class="w-14 h-14 mb-2 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                @else
                    <svg class="w-14 h-14 mb-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8"/></svg>
                @endif
                <span class="text-xs font-semibold tracking-wider uppercase text-emerald-200/90">{{ $category }}</span>
            </div>
        </div>
        @endif
    </div>

    <main class="flex-grow w-full pb-16 md:pb-20">

        {{-- Article Section --}}
        <div class="max-w-[75ch] mx-auto px-4 sm:px-6">
            <article class="reveal">

                {{-- Breadcrumb + Meta Header --}}
                <div class="flex flex-col items-center text-center mb-6 md:mb-8">
                    <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest mb-4 md:mb-6 text-slate-400">
                        <a href="{{ route('home') }}" class="transition-colors hover:text-emerald-600 dark:hover:text-emerald-400 min-h-[44px] inline-flex items-center">Beranda</a>
                        <span>/</span>
                        <a href="{{ route('berita.index') }}" class="transition-colors hover:text-emerald-600 dark:hover:text-emerald-400 min-h-[44px] inline-flex items-center">Warta</a>
                        <span>/</span>
                        <span class="inline-flex items-center min-h-[44px] px-2.5 py-0.5 rounded-full border text-[11px] {{ $badgeClass }}">{{ $category }}</span>
                    </nav>

                    <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight leading-tight mb-4 md:mb-6"
                        style="color:var(--text-primary)">
                        {{ $berita->title }}
                    </h1>

                    <div class="flex items-center flex-wrap gap-2.5 sm:gap-4 text-xs font-semibold uppercase tracking-wider py-3.5 w-full justify-center border-t border-b border-black/5 dark:border-white/10 text-slate-500 dark:text-slate-400">
                        <span class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ ($berita->author->name ?? 'Redaksi') === 'admin' ? 'Redaksi NU Kalisari' : ($berita->author->name ?? 'Redaksi NU Kalisari') }}
                        </span>
                        <span>·</span>
                        <span>@tanggal($berita->created_at)</span>
                        <span>·</span>
                        <span class="lowercase text-emerald-700 dark:text-emerald-400 font-medium">{{ $berita->reading_time }}</span>
                    </div>
                </div>

                {{-- Content: Tipografi nyaman (max-w-prose, leading-[1.85], text-base/lg) --}}
                <div class="prose prose-lg max-w-prose mx-auto text-base sm:text-lg leading-[1.85] text-justify sm:text-left space-y-4"
                     style="color:var(--text-secondary)">
                    {!! nl2br(e($berita->content)) !!}
                </div>

                {{-- Actions: Bagikan & Kembali --}}
                <div class="mt-12 md:mt-16 pt-6 md:pt-8 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 sm:gap-6 border-t border-black/5 dark:border-white/10">
                    <a href="{{ route('berita.index') }}"
                       class="inline-flex items-center justify-center sm:justify-start gap-2 min-h-[44px] py-2 px-3 rounded-xl text-sm font-semibold transition-colors border sm:border-0 hover:bg-black/5 dark:hover:bg-white/5 text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400"
                       style="border-color:var(--border-medium)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Warta
                    </a>

                    <div class="flex items-center justify-center gap-3">
                        <span class="text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400">Bagikan:</span>
                        
                        {{-- Tombol Bagikan ke WhatsApp --}}
                        <a href="https://wa.me/?text={{ urlencode($berita->title . ' — ' . route('berita.show', $berita->id)) }}"
                           target="_blank" rel="noopener noreferrer"
                           aria-label="Bagikan ke WhatsApp"
                           class="inline-flex items-center gap-2 px-3.5 py-2 min-h-[44px] rounded-xl text-xs font-semibold transition-all border border-black/10 dark:border-white/10 bg-white dark:bg-[#070D09] text-slate-700 dark:text-slate-200 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 shadow-sm active:scale-95">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            <span>WhatsApp</span>
                        </a>

                        {{-- Tombol Salin Tautan --}}
                        <button aria-label="Salin tautan"
                                x-data="{ copied: false }"
                                @click="navigator.clipboard.writeText(window.location.href).then(() => { copied = true; setTimeout(() => copied = false, 2000); })"
                                class="inline-flex items-center gap-2 px-3.5 py-2 min-h-[44px] rounded-xl text-xs font-semibold transition-all border focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 cursor-pointer shadow-sm active:scale-95"
                                :class="copied ? 'bg-emerald-600 text-white border-emerald-600' : 'text-slate-700 dark:text-slate-200 border-black/10 dark:border-white/10 hover:border-emerald-500/40 bg-white dark:bg-[#070D09]'">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <span x-text="copied ? 'Tersalin!' : 'Salin tautan'">Salin tautan</span>
                        </button>
                    </div>
                </div>
            </article>
        </div>

        {{-- Warta Terkait / Warta Lainnya (Maksimal 3) --}}
        @if(isset($terkait) && $terkait->count() > 0)
        <div class="max-w-[1100px] mx-auto px-4 sm:px-6 mt-20 pt-14 reveal border-t border-black/5 dark:border-white/10">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-700 dark:text-emerald-400 mb-1">Rekomendasi</p>
                    <h3 class="font-serif text-2xl sm:text-3xl font-bold" style="color:var(--text-primary)">Warta Lainnya</h3>
                </div>
                <a href="{{ route('berita.index') }}" class="text-xs sm:text-sm font-semibold text-emerald-700 dark:text-emerald-400 hover:underline">
                    Lihat semua warta &rarr;
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($terkait->take(3) as $item)
                @php
                    $itemCategory = $item->category ?: 'Kegiatan';
                    $itemBadgeClass = match ($itemCategory) {
                        'Pengumuman' => 'text-amber-700 dark:text-amber-300 bg-amber-500/10 border-amber-500/25',
                        'Organisasi' => 'text-sky-700 dark:text-sky-300 bg-sky-500/10 border-sky-500/25',
                        'Sosial'     => 'text-rose-700 dark:text-rose-300 bg-rose-500/10 border-rose-500/25',
                        default      => 'text-emerald-700 dark:text-emerald-300 bg-emerald-500/10 border-emerald-500/25',
                    };
                @endphp
                <a href="{{ route('berita.show', $item->id) }}" 
                   class="group flex flex-col justify-between h-full p-4 sm:p-5 rounded-2xl bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/10 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition-all duration-300 hover:-translate-y-1">
                    <div>
                        {{-- Thumbnail 16:9 aspect-video dengan object-cover & placeholder gradien tanpa teks --}}
                        <div class="w-full aspect-video rounded-xl overflow-hidden mb-3.5 bg-emerald-950/20 border border-black/5 dark:border-white/5 relative">
                            @if($item->image)
                                <img src="{{ asset('storage/'.$item->image) }}" class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105" alt="{{ $item->title }}" loading="lazy">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-[#064e3b] via-[#04382a] to-[#022118] text-emerald-300/80 select-none">
                                    @if($itemCategory === 'Kegiatan')
                                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @elseif($itemCategory === 'Pengumuman')
                                        <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    @elseif($itemCategory === 'Organisasi')
                                        <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    @elseif($itemCategory === 'Sosial')
                                        <svg class="w-8 h-8 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    @else
                                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8"/></svg>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        {{-- Meta --}}
                        <div class="flex items-center flex-wrap gap-1.5 mb-2 text-[11px] font-semibold uppercase tracking-wider">
                            <span class="px-2 py-0.5 rounded border text-[10px] {{ $itemBadgeClass }}">{{ $itemCategory }}</span>
                            <span class="text-black/30 dark:text-white/30">·</span>
                            <span class="text-slate-500 dark:text-slate-400 font-medium">@tanggal($item->created_at)</span>
                            <span class="text-black/30 dark:text-white/30">·</span>
                            <span class="text-slate-500 dark:text-slate-400 font-medium lowercase">{{ $item->reading_time }}</span>
                        </div>
                        <h4 class="font-serif text-base sm:text-lg font-bold leading-snug line-clamp-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors"
                            style="color:var(--text-primary)">
                            {{ $item->title }}
                        </h4>
                    </div>

                    <div class="mt-4 pt-2.5 border-t border-black/5 dark:border-white/5 flex items-center gap-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400 group-hover:underline">
                        <span>Baca selengkapnya</span>
                        <span aria-hidden="true" class="transition-transform duration-200 group-hover:translate-x-1">&rarr;</span>
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

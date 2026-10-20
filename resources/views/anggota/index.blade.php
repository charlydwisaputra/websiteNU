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
    <style>[x-cloak]{display:none!important}</style>
    @include('components.theme-script')
</head>
<body class="font-sans antialiased flex flex-col min-h-screen"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    <style>
        .surface-card {
            opacity: 1 !important;
            transform: none !important;
            visibility: visible !important;
        }
    </style>

    @php
        $activeBadan = request('badan') ?? request('banom');
        $isTopLeader = function ($a) {
            $pos = strtolower($a->position ?? '');
            $full = strtolower($a->jabatan_lengkap ?? '');
            return str_contains($pos, 'rais syuriyah') 
                || str_contains($full, 'rais syuriyah')
                || str_contains($full, 'ketua tanfidziyah')
                || str_contains($full, 'ketua pengurus ranting')
                || ($pos === 'ketua' && empty($a->banom_id));
        };
        $topLeadersCount = $anggotas->filter($isTopLeader)->count();
    @endphp

    <main class="relative z-10 pt-24 sm:pt-28 lg:pt-32 pb-16 md:pb-20 flex-grow flex flex-col items-center px-4 sm:px-6 w-full"
          x-data="{
              activeMember: null,
              isModalOpen: false,
              openModal(m, triggerEl) {
                  this.activeMember = m;
                  this.isModalOpen = true;
                  this.lastTrigger = triggerEl || document.activeElement;
                  document.body.style.overflow = 'hidden';
                  this.$nextTick(() => {
                      document.getElementById('modal-close-btn')?.focus();
                  });
              },
              closeModal() {
                  this.isModalOpen = false;
                  this.activeMember = null;
                  document.body.style.overflow = '';
                  if (this.lastTrigger && typeof this.lastTrigger.focus === 'function') {
                      this.lastTrigger.focus();
                  }
              }
          }"
          @keydown.escape.window="if (isModalOpen) closeModal()">

        {{-- Header --}}
        <div class="text-center max-w-2xl mx-auto mb-8 md:mb-10 reveal">
            <p class="text-label mb-2 md:mb-3 font-semibold tracking-wider text-xs" style="color:var(--accent)">DIREKTORI ANGGOTA</p>
            @if(isset($banom) && $banom)
                <h1 class="font-serif text-3xl sm:text-5xl md:text-6xl font-bold tracking-tight mb-3 md:mb-4" style="color:var(--text-primary)">
                    Anggota <span style="color:var(--accent)">{{ $banom }}</span>
                </h1>
                <p class="text-sm sm:text-lg leading-relaxed mb-4" style="color:var(--text-secondary)">
                    Menampilkan anggota dari {{ $banom }} NU Kalisari.
                </p>
            @else
                <h1 class="font-serif text-3xl sm:text-5xl md:text-6xl font-bold tracking-tight mb-3 md:mb-4" style="color:var(--text-primary)">
                    Orang-Orang di Balik<br><span style="color:var(--accent)">Gerakan</span>
                </h1>
                <p class="text-sm sm:text-lg leading-relaxed mb-4" style="color:var(--text-secondary)">
                    Kenali pengurus dan anggota yang menjadi bagian dari perjalanan dan pengabdian NU Kalisari.
                </p>
            @endif
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20">
                <span>Menampilkan {{ $anggotas->total() }} anggota</span>
            </div>
        </div>

        {{-- Search + Filter: sticky di bawah navbar saat scroll --}}
        <div class="sticky top-[60px] sm:top-[68px] z-30 w-full py-3 sm:py-4 -mt-3 sm:-mt-4 mb-6 md:mb-8"
             style="background: color-mix(in srgb, var(--bg-base) 92%, transparent); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">
            <div class="w-full max-w-[1100px] mx-auto px-0">

                {{-- Search Bar --}}
                <form action="{{ route('anggota') }}" method="GET" class="w-full max-w-xl mx-auto mb-3 relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="search" name="search" id="search-input"
                           value="{{ request('search') }}"
                           placeholder="Cari nama, jabatan, atau wilayah..."
                           aria-label="Cari anggota"
                           class="block w-full pl-12 sm:pl-14 pr-10 py-3 sm:py-3.5 min-h-[44px] rounded-2xl text-sm font-medium outline-none transition-all bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/10 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 shadow-sm">
                    @if(request('search'))
                    <a href="{{ route('anggota', array_filter(['badan' => $activeBadan])) }}"
                       class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                       title="Hapus pencarian">&times;</a>
                    @endif
                    <input type="hidden" name="badan" value="{{ $activeBadan }}">
                </form>

                {{-- Filter Chips: satu baris horizontal scroll di mobile, min-h 44px --}}
                <div class="relative w-full">
                    <div id="filter-chips-container"
                         class="flex gap-2 overflow-x-auto scrollbar-none pb-1 justify-start md:justify-center snap-x snap-mandatory"
                         style="scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;">
                        @foreach($filters as $filter)
                        @php
                            $val   = $filter['value'];
                            $label = $filter['label'];
                            $count = $filter['count'] ?? null;
                            $isActive = ($val === null && empty($activeBadan))
                                || (!empty($activeBadan) && $val !== null && (
                                    strcasecmp((string)$activeBadan, (string)$val) === 0
                                    || strcasecmp((string)$activeBadan, (string)$label) === 0
                                    || (!empty($banom) && strcasecmp((string)$banom, (string)$label) === 0)
                                ));
                        @endphp
                        <a href="{{ $val ? route('anggota', array_filter(['badan' => $val, 'search' => request('search')])) : route('anggota', array_filter(['search' => request('search')])) }}"
                           class="snap-start shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 min-h-[44px] rounded-full text-xs sm:text-sm font-semibold transition-all border select-none
                                  {{ $isActive
                                      ? 'active-filter-chip bg-emerald-600 border-emerald-600 text-white shadow-sm shadow-emerald-950/20'
                                      : 'bg-white dark:bg-[#070D09] border-black/10 dark:border-white/10 hover:border-emerald-500/40 text-slate-700 dark:text-slate-300 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20' }}">
                            <span>{{ $label }}</span>
                            @if($count !== null)
                            <span class="text-[11px] font-bold {{ $isActive ? 'text-emerald-100' : 'text-slate-400 dark:text-slate-500' }}">({{ $count }})</span>
                            @endif
                        </a>
                        @endforeach
                    </div>
                    {{-- Fade mask tepi kanan di mobile --}}
                    <div class="pointer-events-none absolute right-0 top-0 bottom-1 w-10 bg-gradient-to-l from-[var(--bg-base)] to-transparent md:hidden"></div>
                </div>
            </div>
        </div>

        {{-- Member grid: Semua kartu berukuran sama (col-span-1 selalu), grid seragam --}}
        <div class="w-full max-w-[1100px] mx-auto
            {{ $anggotas->count() === 1 ? 'max-w-xs' : '' }}
            {{ $anggotas->count() === 2 ? 'grid grid-cols-1 sm:grid-cols-2 max-w-2xl gap-4 sm:gap-6' : '' }}
            {{ $anggotas->count() === 3 ? 'grid grid-cols-1 sm:grid-cols-3 max-w-4xl gap-4 sm:gap-6' : '' }}
            {{ $anggotas->count() >= 4 ? 'grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-5' : '' }}
            transition-all duration-300">
            @forelse($anggotas as $anggota)
            @php
                $isLeader    = $isTopLeader($anggota);
                $badge       = $anggota->badge;
                $avatarColor = $anggota->avatar_color;
                $memberJson  = [
                    'id'              => $anggota->id,
                    'name'            => $anggota->name,
                    'jabatan_lengkap' => $anggota->jabatan_lengkap,
                    'badge'           => $badge,
                    'avatar_color'    => $avatarColor,
                    'photo'           => $anggota->photo ? asset('storage/'.$anggota->photo) : null,
                    'wilayah'         => ($anggota->tampil_wilayah && $anggota->region) ? $anggota->region : null,
                    'bio'             => $anggota->bio ?? null,
                    'joined_at'       => $anggota->joined_at ? \Carbon\Carbon::parse($anggota->joined_at)->isoFormat('D MMMM Y') : null,
                ];
            @endphp
            {{-- Semua kartu col-span-1 — Pimpinan Ranting hanya diberi aksen warna, bukan lebar berbeda --}}
            <div @click="openModal({{ \Illuminate\Support\Js::from($memberJson) }}, $event.currentTarget)"
                 @keydown.enter="openModal({{ \Illuminate\Support\Js::from($memberJson) }}, $event.currentTarget)"
                 @keydown.space.prevent="openModal({{ \Illuminate\Support\Js::from($memberJson) }}, $event.currentTarget)"
                 role="button"
                 tabindex="0"
                 aria-label="Lihat profil {{ $anggota->name }}"
                 class="group col-span-1 flex flex-col justify-between text-center surface-card rounded-2xl p-4 sm:p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg active:scale-[0.98] cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#070D09] h-full relative overflow-hidden
                 {{ $isLeader
                     ? 'border border-emerald-500/40 dark:border-emerald-500/35 ring-1 ring-emerald-500/20 bg-gradient-to-b from-emerald-50/70 via-white to-white dark:from-[#063323]/35 dark:via-[#070D09] dark:to-[#070D09] shadow-md shadow-emerald-950/10'
                     : 'bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 hover:border-emerald-500/35' }}">
                
                {{-- Aksen Khusus Pimpinan Utama Ranting (Syuriyah / Tanfidziyah) --}}
                @if($isLeader && $anggotas->count() >= 4)
                <div class="w-full flex items-center justify-center mb-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-800 dark:text-emerald-300 border border-emerald-500/25">
                        <svg class="w-2.5 h-2.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span>Pimpinan Ranting</span>
                    </span>
                </div>
                @endif

                <div class="flex flex-col items-center w-full">
                    {{-- Avatar bulat dengan ring tipis beraksen atau inisial di atas gradien lembut --}}
                    <div class="relative mx-auto mb-3 {{ $isLeader ? 'w-18 h-18 sm:w-20 sm:h-20 md:w-24 md:h-24' : 'w-14 h-14 sm:w-16 sm:h-16 md:w-20 md:h-20' }} shrink-0">
                        @if($anggota->photo)
                            <img src="{{ asset('storage/'.$anggota->photo) }}" alt="{{ $anggota->name }}" width="96" height="96" loading="lazy"
                                 class="w-full h-full rounded-full object-cover p-0.5 ring-2 ring-emerald-500/30 dark:ring-emerald-400/30 shadow-sm transition-transform duration-300 group-hover:scale-105 group-hover:ring-emerald-500">
                        @else
                            <div class="w-full h-full rounded-full flex items-center justify-center font-serif font-bold {{ $isLeader ? 'text-xl sm:text-2xl' : 'text-base sm:text-lg md:text-xl' }} transition-all duration-300 group-hover:scale-105 shadow-sm p-0.5 ring-2 ring-emerald-500/20 dark:ring-emerald-400/20"
                                 style="background:{{ $avatarColor['bg'] }};color:{{ $avatarColor['text'] }};border:1.5px solid {{ $avatarColor['border'] }}">
                                @inisial($anggota->name)
                            </div>
                        @endif
                    </div>

                    {{-- Nama --}}
                    <h3 class="font-serif {{ $isLeader ? 'text-base sm:text-lg md:text-xl font-bold' : 'text-sm sm:text-base font-bold' }} mb-1 leading-snug line-clamp-2 w-full transition-colors group-hover:text-emerald-600 dark:group-hover:text-emerald-400"
                        style="color:var(--text-primary)"
                        title="{{ $anggota->name }}">
                        {{ $anggota->name }}
                    </h3>

                    {{-- Jabatan Lengkap --}}
                    <p class="text-[11px] sm:text-xs font-semibold mb-2.5 leading-tight line-clamp-2 w-full text-emerald-700 dark:text-emerald-400"
                       title="{{ $anggota->jabatan_lengkap }}">
                        {{ $anggota->jabatan_lengkap }}
                    </p>

                    {{-- Badge Seragam (Banom / Pengurus Ranting dengan kontras tinggi) --}}
                    @if($badge)
                    <div class="mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold tracking-wide border {{ $badge['class'] ?? '' }}"
                              style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};border-color:{{ $badge['border'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Wilayah (Privasi: Hanya tampil bila tampil_wilayah bernilai true) --}}
                @if($anggota->tampil_wilayah && $anggota->region)
                <div class="mt-2 pt-2 border-t w-full flex items-center justify-center gap-1 text-[10px] sm:text-xs font-medium border-black/5 dark:border-white/5 text-slate-500 dark:text-slate-400">
                    <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="truncate max-w-[120px] sm:max-w-[160px]" title="{{ $anggota->region }}">{{ $anggota->region }}</span>
                </div>
                @endif
            </div>
            @empty
            {{-- Empty state: berbeda antara pencarian kosong vs belum ada data sama sekali --}}
            <div class="col-span-full w-full max-w-lg mx-auto">
                <div class="flex flex-col items-center justify-center py-16 sm:py-20 px-6 rounded-3xl text-center
                            bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/10 shadow-sm">
                    @if(request('search') || request('badan') || request('banom'))
                        {{-- State kosong karena filter / pencarian --}}
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-5
                                    bg-slate-100 dark:bg-slate-800/60 text-slate-400 dark:text-slate-500
                                    border border-slate-200/80 dark:border-slate-700/40">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-serif text-2xl font-bold mb-2" style="color:var(--text-primary)">
                            Tidak ditemukan
                        </h3>
                        <p class="text-sm sm:text-base max-w-xs mb-6 leading-relaxed text-slate-500 dark:text-slate-400">
                            @if(request('search'))
                                Tidak ada anggota yang cocok dengan kata kunci
                                <span class="font-semibold text-slate-700 dark:text-slate-200">&ldquo;{{ request('search') }}&rdquo;</span>.
                            @else
                                Tidak ada anggota pada filter ini.
                            @endif
                            Coba ubah kata kunci atau filter lain.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-2.5 w-full sm:w-auto justify-center">
                            @if(request('search'))
                            <a href="{{ route('anggota', array_filter(['badan' => $activeBadan])) }}"
                               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 min-h-[44px] rounded-xl text-sm font-semibold
                                      border border-black/10 dark:border-white/10 bg-white dark:bg-[#070D09]
                                      hover:border-emerald-500/50 text-slate-700 dark:text-slate-200 transition-colors">
                                Hapus kata kunci
                            </a>
                            @endif
                            <a href="{{ route('anggota') }}"
                               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 min-h-[44px] rounded-xl text-sm font-semibold
                                      bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-colors active:scale-95">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Lihat semua anggota
                            </a>
                        </div>
                    @else
                        {{-- State kosong karena belum ada data sama sekali --}}
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-5
                                    bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                                    border border-emerald-500/20">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-serif text-2xl font-bold mb-2" style="color:var(--text-primary)">
                            Belum ada anggota
                        </h3>
                        <p class="text-sm sm:text-base max-w-xs mb-2 leading-relaxed text-slate-500 dark:text-slate-400">
                            Data anggota akan segera ditambahkan oleh pengurus.
                        </p>
                    @endif
                </div>
            </div>
            @endforelse
        </div>

        {{-- Pagination: Hanya tampil bila total anggota lebih dari jumlah per halaman (24) --}}
        @if($anggotas->hasPages())
        <nav role="navigation" aria-label="Navigasi Halaman" class="w-full max-w-[1100px] mx-auto mt-12 flex items-center justify-center gap-3">
            @if($anggotas->onFirstPage())
                <span class="px-4 py-2 text-sm font-medium rounded-xl border border-black/10 dark:border-white/10 opacity-50 cursor-not-allowed select-none bg-white dark:bg-[#070D09] text-slate-400">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $anggotas->previousPageUrl() }}" rel="prev" class="px-4 py-2 text-sm font-medium rounded-xl border border-black/10 dark:border-white/10 hover:border-emerald-500 hover:text-emerald-600 transition-colors bg-white dark:bg-[#070D09]" style="color:var(--text-primary)">
                    Sebelumnya
                </a>
            @endif

            <span class="text-xs sm:text-sm px-2 font-medium text-slate-600 dark:text-slate-400">
                Halaman {{ $anggotas->currentPage() }} dari {{ $anggotas->lastPage() }}
            </span>

            @if($anggotas->hasMorePages())
                <a href="{{ $anggotas->nextPageUrl() }}" rel="next" class="px-4 py-2 text-sm font-medium rounded-xl border border-black/10 dark:border-white/10 hover:border-emerald-500 hover:text-emerald-600 transition-colors bg-white dark:bg-[#070D09]" style="color:var(--text-primary)">
                    Selanjutnya
                </a>
            @else
                <span class="px-4 py-2 text-sm font-medium rounded-xl border border-black/10 dark:border-white/10 opacity-50 cursor-not-allowed select-none bg-white dark:bg-[#070D09] text-slate-400">
                    Selanjutnya
                </span>
            @endif
        </nav>
        @endif

        {{-- MODAL PROFIL SINGKAT (Klik kartu anggota membuka modal ini) --}}
        <div x-show="isModalOpen"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm select-none"
             role="dialog"
             aria-modal="true"
             aria-label="Profil Anggota"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="closeModal()">

            <div class="relative w-full max-w-md bg-white dark:bg-[#070D09] border border-black/10 dark:border-white/15 rounded-3xl p-6 sm:p-8 shadow-2xl text-center overflow-hidden"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                <!-- Ambient glow di sudut modal -->
                <div class="pointer-events-none absolute -right-16 -top-16 w-48 h-48 rounded-full bg-emerald-500/10 blur-2xl"></div>
                <div class="pointer-events-none absolute -left-16 -bottom-16 w-48 h-48 rounded-full bg-emerald-600/10 blur-2xl"></div>

                <!-- Tombol Tutup (×) -->
                <button id="modal-close-btn"
                        type="button"
                        @click="closeModal()"
                        aria-label="Tutup profil"
                        class="absolute right-4 top-4 w-10 h-10 min-w-[40px] min-h-[40px] flex items-center justify-center rounded-full bg-black/5 dark:bg-white/10 hover:bg-black/10 dark:hover:bg-white/20 active:scale-95 text-slate-500 dark:text-slate-300 transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <!-- Avatar -->
                <div class="relative mx-auto mb-4 w-24 h-24 shrink-0 mt-2">
                    <template x-if="activeMember?.photo">
                        <img :src="activeMember.photo" :alt="activeMember.name" class="w-full h-full rounded-full object-cover p-0.5 ring-2 ring-emerald-500 shadow-md">
                    </template>
                    <template x-if="!activeMember?.photo">
                        <div class="w-full h-full rounded-full flex items-center justify-center font-serif font-bold text-3xl shadow-md p-0.5 ring-2 ring-emerald-500/30"
                             :style="{ background: activeMember?.avatar_color?.bg, color: activeMember?.avatar_color?.text, border: '2px solid ' + activeMember?.avatar_color?.border }">
                            <span x-text="activeMember?.name ? activeMember.name.substring(0, 2).toUpperCase() : 'NU'"></span>
                        </div>
                    </template>
                </div>

                <!-- Nama -->
                <h3 class="font-serif text-2xl sm:text-3xl font-bold mb-1 leading-snug" style="color:var(--text-primary)" x-text="activeMember?.name"></h3>

                <!-- Jabatan Lengkap -->
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-3" x-text="activeMember?.jabatan_lengkap"></p>

                <!-- Badge Banom / Pengurus Ranting -->
                <template x-if="activeMember?.badge">
                    <div class="mb-5">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wide border shadow-sm"
                              :class="activeMember?.badge?.class || ''"
                              :style="{ background: activeMember?.badge?.bg, color: activeMember?.badge?.text, borderColor: activeMember?.badge?.border }"
                              x-text="activeMember?.badge?.label"></span>
                    </div>
                </template>

                <!-- Informasi Tambahan (Wilayah & Tanggal Bergabung) -->
                <div class="pt-4 border-t border-black/5 dark:border-white/10 flex flex-col gap-2.5 text-xs text-slate-600 dark:text-slate-300 text-left">
                    <template x-if="activeMember?.wilayah">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-medium" x-text="activeMember.wilayah"></span>
                        </div>
                    </template>
                    <template x-if="activeMember?.joined_at">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Bergabung sejak <strong class="text-slate-800 dark:text-slate-100" x-text="activeMember.joined_at"></strong></span>
                        </div>
                    </template>
                    <template x-if="activeMember?.bio">
                        <p class="mt-2 text-xs leading-relaxed italic text-slate-500 dark:text-slate-400 border-l-2 border-emerald-500/40 pl-3" x-text="activeMember.bio"></p>
                    </template>
                </div>

                <div class="mt-6">
                    <button type="button"
                            @click="closeModal()"
                            class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold bg-black/5 dark:bg-white/10 hover:bg-black/10 dark:hover:bg-white/15 text-slate-700 dark:text-slate-200 transition-colors cursor-pointer active:scale-95">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </main>

    @include('layouts.public-footer')

    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && !['INPUT','TEXTAREA'].includes(document.activeElement.tagName)) {
                e.preventDefault();
                document.getElementById('search-input')?.focus();
            }
        });

        // Chip aktif otomatis scroll ke tengah pada mobile
        (function() {
            function scrollActiveChip() {
                const activeChip = document.querySelector('.active-filter-chip');
                if (activeChip) {
                    activeChip.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', scrollActiveChip);
            } else {
                scrollActiveChip();
            }
            setTimeout(scrollActiveChip, 150);
        })();

        // Fallback: pastikan semua elemen reveal aktif dan kartu langsung terlihat jika observer tidak terpicu
        (function() {
            function activateAll() {
                document.querySelectorAll('.reveal, .reveal-up, .reveal-fade, .surface-card').forEach(function(el) {
                    el.classList.add('active');
                });
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', activateAll);
            } else {
                activateAll();
            }
            setTimeout(activateAll, 50);
            setTimeout(activateAll, 250);
        })();
    </script>
</body>
</html>

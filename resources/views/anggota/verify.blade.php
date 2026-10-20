<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keanggotaan — NU Kalisari</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @include('components.theme-script')
</head>
<body class="font-sans antialiased min-h-screen flex flex-col justify-center items-center p-4"
      style="background:var(--bg-base);color:var(--text-primary)">

    @php
        $isAktif = in_array(strtolower($anggota->status ?? ''), ['aktif', 'admin', 'administrator']);
    @endphp

    <div class="w-full max-w-sm my-8">

        {{-- Logo --}}
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-xs font-bold"
                     style="background:var(--accent)">NU</div>
                <span class="font-serif font-bold text-sm" style="color:var(--text-primary)">NU Kalisari</span>
            </a>
        </div>

        {{-- Card --}}
        <div class="surface-card rounded-2xl overflow-hidden">

            {{-- Status banner --}}
            <div class="px-6 pt-6 pb-5 text-center" style="border-bottom:1px solid var(--border-light)">
                @if($isAktif)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold mb-4"
                      style="background:var(--accent-subtle);color:var(--accent)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Terverifikasi Resmi
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold mb-4"
                      style="background:var(--status-warning-bg);color:var(--status-warning-fg)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Status: {{ ucfirst($anggota->status ?? 'Pending') }}
                </span>
                @endif

                {{-- Avatar --}}
                <div class="w-20 h-20 mx-auto rounded-2xl overflow-hidden flex items-center justify-center font-bold text-xl mb-4"
                     style="background:var(--bg-surface-elevated);color:var(--accent);border:1px solid var(--border-light)">
                    @if($anggota->photo)
                        <img src="{{ asset('storage/'.$anggota->photo) }}" alt="{{ $anggota->name }}"
                             class="w-full h-full object-cover">
                    @else
                        @inisial($anggota->name)
                    @endif
                </div>

                <h2 class="font-serif text-xl font-bold tracking-tight" style="color:var(--text-primary)">
                    {{ $anggota->name }}
                </h2>
                <p class="text-xs font-semibold mt-1" style="color:var(--accent)">
                    {{ $anggota->role ?: ($anggota->user && $anggota->user->isAdmin() ? 'Administrator' : 'Anggota') }}
                </p>
            </div>

            {{-- Detail rows --}}
            <dl class="px-6 py-5 space-y-0 divide-y" style="--tw-divide-opacity:1">
                @foreach([
                    ['key' => 'ID Keanggotaan',  'val' => 'ID-'.str_pad($anggota->id, 5, '0', STR_PAD_LEFT)],
                    ['key' => 'Wilayah',         'val' => $anggota->wilayah ?: 'Pusat — Kalisari'],
                    ['key' => 'Status',          'val' => ucfirst($anggota->status ?? 'Pending'), 'accent' => $isAktif],
                    ['key' => 'Terdaftar',       'val' => $anggota->joined_at ? \Carbon\Carbon::parse($anggota->joined_at)->isoFormat('D MMM Y') : '—'],
                ] as $row)
                <div class="flex items-center justify-between py-3">
                    <dt class="text-xs" style="color:var(--text-muted)">{{ $row['key'] }}</dt>
                    <dd class="text-xs font-semibold"
                        style="color:{{ ($row['accent'] ?? false) ? 'var(--accent)' : 'var(--text-primary)' }}">
                        {{ $row['val'] }}
                    </dd>
                </div>
                @endforeach
            </dl>

            {{-- Footer --}}
            <div class="px-6 pb-6">
                <a href="{{ route('home') }}"
                   class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold text-white transition-all"
                   style="background:var(--accent)"
                   onmouseover="this.style.background='var(--accent-hover)'"
                   onmouseout="this.style.background='var(--accent)'">
                    Kunjungi Website NU Kalisari
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>

        <p class="text-center text-[10px] mt-5" style="color:var(--text-muted)">
            Nahdlatul Ulama Kalisari · Sistem Verifikasi Digital
        </p>
    </div>

</body>
</html>

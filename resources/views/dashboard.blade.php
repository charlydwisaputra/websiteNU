@extends('layouts.admin')

@section('content')
@php
    $user     = Auth::user();
    $anggota  = class_exists(\App\Models\Anggota::class) ? $user->getOrBuildProfilAnggota() : null;
    $isAdmin  = method_exists($user, 'isAdmin') ? $user->isAdmin() : ($user->role === 'admin');

    $countAnggota = class_exists(\App\Models\Anggota::class) ? \App\Models\Anggota::count() : 0;
    $countBerita  = class_exists(\App\Models\Berita::class)  ? \App\Models\Berita::count()  : 0;
    $countGaleri  = class_exists(\App\Models\Galeri::class)  ? \App\Models\Galeri::count()  : 0;
    $countAdmin   = \App\Models\User::where('role', 'admin')->count();

    $recentBerita  = class_exists(\App\Models\Berita::class)  ? \App\Models\Berita::latest()->take(3)->get()  : collect();
    $recentAnggota = class_exists(\App\Models\Anggota::class) ? \App\Models\Anggota::latest()->take(2)->get() : collect();

    // Data Perlu Perhatian
    $draftBeritaCount     = class_exists(\App\Models\Berita::class)  ? \App\Models\Berita::where('status', 'draft')->count() : 0;
    $nonaktifAnggotaCount = class_exists(\App\Models\Anggota::class) ? \App\Models\Anggota::whereNotIn('status', ['Aktif', 'aktif'])->count() : 0;
    $hasAttention = ($draftBeritaCount > 0 || $nonaktifAnggotaCount > 0);

    // Agenda Terdekat untuk kartu informasi desktop
    $nearestAgenda = class_exists(\App\Models\Agenda::class)
        ? \App\Models\Agenda::where('tanggal_mulai', '>=', now()->startOfDay())
            ->orderBy('tanggal_mulai', 'asc')
            ->first()
        : null;
    if (! $nearestAgenda && class_exists(\App\Models\Agenda::class)) {
        $nearestAgenda = \App\Models\Agenda::latest('tanggal_mulai')->first();
    }

    $hour     = (int) date('H');
    $greeting = match(true) {
        $hour >= 18 || $hour < 4 => 'Selamat Malam',
        $hour >= 15              => 'Selamat Sore',
        $hour >= 11              => 'Selamat Siang',
        default                  => 'Selamat Pagi',
    };
@endphp

<div class="max-w-5xl mx-auto flex flex-col md:grid md:grid-cols-12 gap-4 sm:gap-5 pb-6 md:pb-8">

    {{-- ── 1. PAGE HEADER (HP: Urutan 1 | Desktop: Top col-12) ── --}}
    <div class="order-1 md:order-1 md:col-span-12">
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:var(--accent)">
            {{ $greeting }}, {{ explode(' ', $user->name)[0] }}
        </p>
        <h1 class="font-serif text-2xl sm:text-3xl md:text-4xl font-bold tracking-tight" style="color:var(--text-primary)">
            Dashboard
        </h1>
        <p class="text-xs sm:text-sm mt-1 max-w-md leading-relaxed" style="color:var(--text-muted)">
            Kelola ekosistem digital NU Kalisari dari satu tempat.
        </p>
    </div>

    {{-- ── 2. QUICK ACTIONS (HP: Urutan 3 | Desktop: col-12 order-2) ── --}}
    @if($isAdmin)
    <div class="order-3 md:order-2 md:col-span-12">
        {{-- Grid 2 kolom di HP, Tambah Berita lebar penuh (col-span-2) --}}
        <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2.5">
            @foreach([
                ['route' => 'admin.berita.create',       'label' => 'Tambah Berita',   'primary' => true,  'full_mobile' => true],
                ['route' => 'admin.agenda.create',       'label' => 'Tambah Agenda',   'primary' => false, 'full_mobile' => false],
                ['route' => 'admin.anggota.create',      'label' => 'Tambah Anggota',  'primary' => false, 'full_mobile' => false],
                ['route' => 'admin.galeri.create',       'label' => 'Upload Foto',     'primary' => false, 'full_mobile' => false],
                ['route' => 'admin.anggota.print-cards', 'label' => 'Cetak Kartu',     'primary' => false, 'full_mobile' => false],
            ] as $action)
            <a href="{{ route($action['route']) }}"
               class="{{ $action['full_mobile'] ? 'col-span-2 sm:col-auto' : 'col-span-1 sm:col-auto' }} inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px] active:scale-95 touch-manipulation"
               style="{{ $action['primary']
                   ? 'background:var(--accent);color:#fff;box-shadow:var(--shadow-sm)'
                   : 'background:var(--bg-surface);color:var(--text-secondary);border:1px solid var(--border-medium)' }}"
               onmouseover="{{ $action['primary']
                   ? "this.style.background='var(--accent-hover)'"
                   : "this.style.color='var(--text-primary)';this.style.borderColor='var(--border-strong)'" }}"
               onmouseout="{{ $action['primary']
                   ? "this.style.background='var(--accent)'"
                   : "this.style.color='var(--text-secondary)';this.style.borderColor='var(--border-medium)'" }}">
                @if($action['primary'])
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                @endif
                {{ $action['label'] }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── 3. STATISTIK (HP: Urutan 2, grid 2x2 ringkas tanpa tinggi tetap | Desktop: col-8 order-4) ── --}}
    <div class="order-2 md:order-4 md:col-span-8 surface-card rounded-2xl p-4 sm:p-5 grid grid-cols-2 gap-3 sm:gap-4 self-start">
        @foreach([
            ['label' => 'Anggota',      'count' => $countAnggota, 'sub' => 'Total terdaftar'],
            ['label' => 'Berita',        'count' => $countBerita,  'sub' => 'Artikel & kabar'],
            ['label' => 'Dokumentasi',   'count' => $countGaleri,  'sub' => 'Foto kegiatan'],
            ['label' => 'Administrator', 'count' => $countAdmin,   'sub' => 'Pengelola aktif'],
        ] as $i => $stat)
        <div class="p-3 sm:p-4 rounded-xl transition-all"
             style="background:var(--bg-surface-elevated);border:1px solid var(--border-light)">
            <p class="text-[10px] font-semibold uppercase tracking-widest mb-1.5"
               style="color:var(--text-muted)">{{ $stat['label'] }}</p>
            <p class="font-serif text-2xl sm:text-3xl font-bold tracking-tight leading-none"
               style="color:var(--text-primary)">{{ $stat['count'] }}</p>
            <p class="text-[10px] mt-1 hidden sm:block" style="color:var(--text-muted)">{{ $stat['sub'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ── 4. KARTU PROFIL RINGKAS (HP: Urutan 6, satu baris di HP | Desktop: col-4 order-3) ── --}}
    <div class="order-6 md:order-3 md:col-span-4 surface-card rounded-2xl p-4 sm:p-5 flex flex-col justify-between">
        {{-- Satu baris di HP: Avatar, Nama, Badge, Edit Profil link --}}
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-11 h-11 md:w-12 md:h-12 rounded-xl overflow-hidden shrink-0 flex items-center justify-center font-bold text-sm md:text-base"
                     style="background:var(--bg-surface-elevated);color:var(--text-muted);border:1px solid var(--border-light)">
                    @if($anggota && $anggota->photo)
                        <img src="{{ asset('storage/'.$anggota->photo) }}" class="w-full h-full object-cover">
                    @else
                        @inisial($user->name)
                    @endif
                </div>
                <div class="min-w-0">
                    <h3 class="font-semibold text-sm leading-snug truncate" style="color:var(--text-primary)">
                        {{ $user->name }}
                    </h3>
                    <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider text-white"
                          style="background:var(--accent)">
                        {{ $isAdmin ? 'Administrator' : 'Anggota' }}
                    </span>
                </div>
            </div>

            {{-- Link Edit Profil khusus HP (satu baris) --}}
            <a href="{{ route('admin.profil') }}"
               class="md:hidden text-xs font-semibold shrink-0 py-2 px-2 min-h-[44px] inline-flex items-center transition-colors"
               style="color:var(--accent)"
               aria-label="Edit Profil Saya">
                Edit profil →
            </a>
        </div>

        {{-- Di Desktop: Agenda Terdekat & Informasi Ringkas --}}
        <div class="hidden md:flex flex-col gap-2.5 mt-3.5 pt-3 border-t" style="border-color:var(--border-light)">
            <div class="flex items-center justify-between text-xs">
                <span class="text-[10px] uppercase font-semibold tracking-wider" style="color:var(--text-muted)">Agenda Terdekat</span>
                <a href="{{ route('admin.agenda.index') }}" class="text-[11px] font-semibold hover:underline" style="color:var(--accent)">Lihat Semua</a>
            </div>
            @if($nearestAgenda)
            <a href="{{ route('admin.agenda.edit', $nearestAgenda->id) }}"
               class="p-2.5 rounded-xl transition-all border block group hover:border-[var(--accent)]"
               style="background:var(--bg-surface-elevated);border-color:var(--border-light)"
               title="Detail Agenda: {{ $nearestAgenda->judul }}">
                <p class="text-xs font-semibold truncate group-hover:text-[var(--accent)] transition-colors" style="color:var(--text-primary)">
                    {{ $nearestAgenda->judul }}
                </p>
                <p class="text-[10px] mt-0.5 flex items-center gap-1.5" style="color:var(--text-muted)">
                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $nearestAgenda->tanggal_mulai ? $nearestAgenda->tanggal_mulai->isoFormat('D MMM Y') : 'Jadwal belum ditentukan' }}</span>
                </p>
            </a>
            @else
            <div class="p-2.5 rounded-xl border text-[11px] italic"
                 style="background:var(--bg-surface-elevated);border-color:var(--border-light);color:var(--text-muted)">
                Belum ada agenda mendatang.
            </div>
            @endif
        </div>

        {{-- Desktop Footer link --}}
        <div class="hidden md:flex mt-3 pt-2.5 items-center justify-between border-t" style="border-color:var(--border-light)">
            <span class="text-[10px] font-medium" style="color:var(--text-muted)">
                ID #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }} · {{ ucfirst($anggota->status ?? 'Aktif') }}
            </span>
            <a href="{{ route('admin.profil') }}"
               class="text-xs font-semibold transition-colors py-1 px-1 min-h-[32px] inline-flex items-center"
               style="color:var(--accent)">
                Edit profil →
            </a>
        </div>
    </div>

    {{-- ── 5. KARTU PERLU PERHATIAN (HP: Urutan 4 | Desktop: col-4 order-5) ── --}}
    <div class="order-4 md:order-5 md:col-span-4 surface-card rounded-2xl p-4 sm:p-5 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold uppercase tracking-widest" style="color:var(--text-muted)">
                    Perlu Perhatian
                </h3>
                @if($hasAttention)
                <span class="w-2 h-2 rounded-full animate-pulse" style="background:#eab308"></span>
                @else
                <span class="w-2 h-2 rounded-full" style="background:var(--accent)"></span>
                @endif
            </div>

            @if($hasAttention)
            <div class="space-y-2.5">
                @if($draftBeritaCount > 0)
                <a href="{{ route('admin.berita.index', ['status' => 'draft']) }}"
                   class="flex items-center justify-between p-2.5 rounded-xl transition-all min-h-[44px] border group"
                   style="background:var(--bg-surface-elevated);border-color:rgba(234,179,8,0.3)">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <span class="w-2 h-2 rounded-full shrink-0" style="background:#eab308"></span>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold truncate group-hover:text-[var(--accent)] transition-colors"
                               style="color:var(--text-primary)">{{ $draftBeritaCount }} Berita Draft</p>
                            <p class="text-[10px]" style="color:var(--text-muted)">Menunggu dipublikasikan</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 shrink-0 transition-transform group-hover:translate-x-0.5" style="color:var(--text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endif

                @if($nonaktifAnggotaCount > 0)
                <a href="{{ route('admin.anggota.index') }}"
                   class="flex items-center justify-between p-2.5 rounded-xl transition-all min-h-[44px] border group"
                   style="background:var(--bg-surface-elevated);border-color:rgba(239,68,68,0.3)">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <span class="w-2 h-2 rounded-full shrink-0" style="background:#ef4444"></span>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold truncate group-hover:text-[var(--accent)] transition-colors"
                               style="color:var(--text-primary)">{{ $nonaktifAnggotaCount }} Anggota Tertunda/Nonaktif</p>
                            <p class="text-[10px]" style="color:var(--text-muted)">Perlu verifikasi atau update</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 shrink-0 transition-transform group-hover:translate-x-0.5" style="color:var(--text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endif
            </div>
            @else
            <div class="p-3 rounded-xl flex items-center gap-3"
                 style="background:var(--bg-surface-elevated);border:1px solid var(--border-light)">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-white"
                     style="background:var(--accent)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold" style="color:var(--text-primary)">Semua Berjalan Normal</p>
                    <p class="text-[10px]" style="color:var(--text-muted)">Tidak ada draf atau data pending.</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ── 6. AKTIVITAS TERBARU (HP: Urutan 5 | Desktop: col-8 order-6) ── --}}
    <div class="order-5 md:order-6 md:col-span-8 surface-card rounded-2xl p-4 sm:p-6 flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-semibold uppercase tracking-widest"
                style="color:var(--text-muted)">Aktivitas Terbaru</h3>
            <span class="text-[10px]" style="color:var(--text-muted)">Sentuh item untuk detail</span>
        </div>

        <div class="space-y-1.5 flex-grow">
            @forelse($recentBerita as $b)
            <a href="{{ route('admin.berita.edit', $b->id) }}"
               class="flex items-center gap-3 p-2.5 rounded-xl transition-all min-h-[44px] hover:bg-[var(--bg-surface-elevated)] group active:scale-[0.99] touch-manipulation"
               title="Edit Berita: {{ $b->title }}"
               aria-label="Edit Berita {{ $b->title }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                     style="background:var(--accent-subtle);color:var(--accent)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold leading-snug truncate group-hover:text-[var(--accent)] transition-colors"
                       style="color:var(--text-primary)">{{ $b->title }}</p>
                    <p class="text-[10px] mt-0.5" style="color:var(--text-muted)">
                        Berita · @tanggal($b->created_at)
                    </p>
                </div>
                <svg class="w-4 h-4 shrink-0 opacity-40 group-hover:opacity-100 transition-opacity" style="color:var(--text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @empty
            <p class="text-xs py-3" style="color:var(--text-muted)">Belum ada data berita.</p>
            @endforelse

            @foreach($recentAnggota as $ag)
            <a href="{{ route('admin.anggota.edit', $ag->id) }}"
               class="flex items-center gap-3 p-2.5 rounded-xl transition-all min-h-[44px] hover:bg-[var(--bg-surface-elevated)] group active:scale-[0.99] touch-manipulation"
               title="Edit Anggota: {{ $ag->name }}"
               aria-label="Edit Anggota {{ $ag->name }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                     style="background:var(--accent-subtle);color:var(--accent)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold leading-snug truncate group-hover:text-[var(--accent)] transition-colors"
                       style="color:var(--text-primary)">{{ $ag->name }}</p>
                    <p class="text-[10px] mt-0.5" style="color:var(--text-muted)">
                        Anggota baru · @tanggal($ag->created_at)
                    </p>
                </div>
                <svg class="w-4 h-4 shrink-0 opacity-40 group-hover:opacity-100 transition-opacity" style="color:var(--text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endforeach
        </div>
    </div>

    {{-- ── QR card (non-admin) ── --}}
    @if(!$isAdmin && $anggota)
    <div class="order-7 md:order-7 md:col-span-12 surface-card rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center gap-6">
        <div class="flex-1">
            <h3 class="font-semibold text-base mb-1" style="color:var(--text-primary)">Kartu Anggota Digital</h3>
            <p class="text-sm leading-relaxed mb-4 max-w-sm" style="color:var(--text-secondary)">
                Tunjukkan atau pindai kode QR ini untuk verifikasi keanggotaan di kegiatan resmi NU Kalisari.
            </p>
            <a href="{{ route('anggota.print-my-card') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition-all border"
               style="color:var(--text-secondary);border-color:var(--border-medium)"
               onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'"
               onmouseout="this.style.color='var(--text-secondary)';this.style.borderColor='var(--border-medium)'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Cetak Kartu
            </a>
        </div>
        <div class="rounded-xl p-3 shrink-0" style="background:var(--bg-surface-elevated);border:1px solid var(--border-light)">
            <div id="user-qr-code"></div>
        </div>
    </div>
    @endif

</div>

@if(!$isAdmin && $anggota)
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('user-qr-code');
    if (el) {
        new QRCode(el, {
            text: "{{ route('kartu.verify', $anggota->id) }}",
            width: 88, height: 88,
            colorDark: '{{ ltrim(config('services.nu.accent', '#008A5A'), '#') }}',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
    }
});
</script>
@endif
@endsection

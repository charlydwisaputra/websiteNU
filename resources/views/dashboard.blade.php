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

    $hour     = (int) date('H');
    $greeting = match(true) {
        $hour >= 18 || $hour < 4 => 'Selamat Malam',
        $hour >= 15              => 'Selamat Sore',
        $hour >= 11              => 'Selamat Siang',
        default                  => 'Selamat Pagi',
    };
@endphp

<div class="max-w-5xl mx-auto space-y-8 pb-16">

    {{-- ── PAGE HEADER ── --}}
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:var(--accent)">
            {{ $greeting }}, {{ explode(' ', $user->name)[0] }}
        </p>
        <h1 class="font-serif text-3xl md:text-4xl font-bold tracking-tight" style="color:var(--text-primary)">
            Dashboard
        </h1>
        <p class="text-sm mt-1 max-w-md leading-relaxed" style="color:var(--text-muted)">
            Kelola ekosistem digital NU Cikembulan dari satu tempat.
        </p>
    </div>

    {{-- ── QUICK ACTIONS (admin only) ── --}}
    @if($isAdmin)
    <div class="flex flex-wrap gap-2">
        @foreach([
            ['route' => 'admin.berita.create',       'label' => 'Tambah Berita',   'primary' => true],
            ['route' => 'admin.anggota.create',      'label' => 'Tambah Anggota',  'primary' => false],
            ['route' => 'admin.galeri.create',       'label' => 'Upload Foto',     'primary' => false],
            ['route' => 'admin.anggota.print-cards', 'label' => 'Cetak Kartu',     'primary' => false],
        ] as $action)
        <a href="{{ route($action['route']) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold transition-all"
           style="{{ $action['primary']
               ? 'background:var(--accent);color:#fff'
               : 'background:var(--bg-surface);color:var(--text-secondary);border:1px solid var(--border-medium)' }}"
           onmouseover="{{ $action['primary']
               ? "this.style.background='var(--accent-hover)'"
               : "this.style.color='var(--text-primary)';this.style.borderColor='var(--border-strong)'" }}"
           onmouseout="{{ $action['primary']
               ? "this.style.background='var(--accent)'"
               : "this.style.color='var(--text-secondary)';this.style.borderColor='var(--border-medium)'" }}">
            {{ $action['label'] }}
        </a>
        @endforeach
    </div>
    @endif

    {{-- ── MAIN BENTO GRID ── --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">

        {{-- Profile panel --}}
        <div class="md:col-span-4 surface-card rounded-2xl p-6 flex flex-col">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0 flex items-center justify-center font-bold text-lg"
                     style="background:var(--bg-surface-elevated);color:var(--text-muted);border:1px solid var(--border-light)">
                    @if($anggota && $anggota->photo)
                        <img src="{{ asset('storage/'.$anggota->photo) }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div class="min-w-0">
                    <h3 class="font-semibold text-sm leading-snug truncate" style="color:var(--text-primary)">
                        {{ $user->name }}
                    </h3>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider text-white"
                          style="background:var(--accent)">
                        {{ $isAdmin ? 'Administrator' : 'Anggota' }}
                    </span>
                </div>
            </div>

            <dl class="space-y-4 flex-grow">
                @foreach([
                    ['key' => 'Status',           'val' => ucfirst($anggota->status ?? 'Aktif')],
                    ['key' => 'Email',             'val' => $user->email],
                    ['key' => 'Bergabung',         'val' => $user->created_at->format('d M Y')],
                ] as $row)
                <div>
                    <dt class="text-[10px] font-semibold uppercase tracking-widest mb-0.5"
                        style="color:var(--text-muted)">{{ $row['key'] }}</dt>
                    <dd class="text-sm font-medium break-all" style="color:var(--text-primary)">{{ $row['val'] }}</dd>
                </div>
                @endforeach
            </dl>

            <div class="mt-6 pt-5 flex items-center justify-between" style="border-top:1px solid var(--border-light)">
                <span class="text-[10px] font-medium" style="color:var(--text-muted)">
                    ID #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
                </span>
                <a href="#profile-settings"
                   onclick="document.getElementById('profile-settings')?.scrollIntoView({behavior:'smooth'})"
                   class="text-xs font-semibold transition-colors"
                   style="color:var(--accent)">
                    Edit profil →
                </a>
            </div>
        </div>

        {{-- Stats + Activity --}}
        <div class="md:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Stats --}}
            <div class="surface-card rounded-2xl p-6 grid grid-cols-2 gap-5">
                @foreach([
                    ['label' => 'Anggota',      'count' => $countAnggota],
                    ['label' => 'Berita',        'count' => $countBerita],
                    ['label' => 'Dokumentasi',   'count' => $countGaleri],
                    ['label' => 'Administrator', 'count' => $countAdmin],
                ] as $i => $stat)
                <div class="{{ $i >= 2 ? 'pt-4' : '' }}"
                     style="{{ $i >= 2 ? 'border-top:1px solid var(--border-light)' : '' }}">
                    <p class="text-[10px] font-semibold uppercase tracking-widest mb-1"
                       style="color:var(--text-muted)">{{ $stat['label'] }}</p>
                    <p class="font-serif text-3xl font-bold tracking-tight leading-none"
                       style="color:var(--text-primary)">{{ $stat['count'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Recent activity --}}
            <div class="surface-card rounded-2xl p-6 flex flex-col">
                <h3 class="text-xs font-semibold uppercase tracking-widest mb-5"
                    style="color:var(--text-muted)">Aktivitas Terbaru</h3>
                <div class="space-y-4 flex-grow">
                    @forelse($recentBerita as $b)
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 mt-0.5"
                             style="background:var(--accent-subtle);color:var(--accent)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold leading-snug line-clamp-1"
                               style="color:var(--text-primary)">{{ $b->title }}</p>
                            <p class="text-[10px] mt-0.5" style="color:var(--text-muted)">
                                {{ $b->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs" style="color:var(--text-muted)">Belum ada aktivitas.</p>
                    @endforelse

                    @foreach($recentAnggota as $ag)
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 mt-0.5"
                             style="background:var(--accent-subtle);color:var(--accent)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold leading-snug line-clamp-1"
                               style="color:var(--text-primary)">{{ $ag->name }}</p>
                            <p class="text-[10px] mt-0.5" style="color:var(--text-muted)">
                                Anggota baru · {{ $ag->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- QR card (non-admin) --}}
        @if(!$isAdmin && $anggota)
        <div class="md:col-span-12 surface-card rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <div class="flex-1">
                <h3 class="font-semibold text-base mb-1" style="color:var(--text-primary)">Kartu Anggota Digital</h3>
                <p class="text-sm leading-relaxed mb-4 max-w-sm" style="color:var(--text-secondary)">
                    Tunjukkan atau pindai kode QR ini untuk verifikasi keanggotaan di kegiatan resmi NU Cikembulan.
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

    {{-- ── PROFILE SETTINGS ── --}}
    <div id="profile-settings" class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Update profile --}}
        <div class="surface-card rounded-2xl p-6">
            <h3 class="font-semibold text-base mb-1" style="color:var(--text-primary)">Informasi Profil</h3>
            <p class="text-xs mb-6" style="color:var(--text-muted)">Perbarui foto dan informasi akun Anda.</p>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('patch')

                {{-- Photo --}}
                <div>
                    <label class="block text-[10px] font-semibold uppercase tracking-widest mb-2"
                           style="color:var(--text-muted)">Foto Profil</label>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 flex items-center justify-center text-xs font-bold"
                             style="background:var(--bg-surface-elevated);border:1px solid var(--border-light);color:var(--text-muted)">
                            <img id="dashboard-photo-preview"
                                 src="{{ $anggota && $anggota->photo ? asset('storage/'.$anggota->photo) : '' }}"
                                 class="{{ ($anggota && $anggota->photo) ? '' : 'hidden' }} w-full h-full object-cover">
                            @if(!$anggota || !$anggota->photo)
                                <span id="dashboard-photo-placeholder">?</span>
                            @endif
                        </div>
                        <input type="file" name="photo" id="dashboard_photo_input"
                               accept="image/jpeg,image/png,image/jpg"
                               onchange="previewDashboardPhoto(this)"
                               class="text-xs cursor-pointer w-full"
                               style="color:var(--text-secondary)">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-widest mb-2"
                               style="color:var(--text-muted)">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3 py-2.5 rounded-xl text-sm font-medium outline-none transition-all"
                               style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                               onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px var(--accent-subtle)'"
                               onblur="this.style.borderColor='var(--border-medium)';this.style.boxShadow=''">
                        @error('name')
                        <p class="text-xs mt-1" style="color:var(--status-danger-fg)">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-widest mb-2"
                               style="color:var(--text-muted)">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-3 py-2.5 rounded-xl text-sm font-medium outline-none transition-all"
                               style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                               onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px var(--accent-subtle)'"
                               onblur="this.style.borderColor='var(--border-medium)';this.style.boxShadow=''">
                        @error('email')
                        <p class="text-xs mt-1" style="color:var(--status-danger-fg)">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-1">
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white transition-all"
                            style="background:var(--accent)"
                            onmouseover="this.style.background='var(--accent-hover)'"
                            onmouseout="this.style.background='var(--accent)'">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Change password --}}
        <div class="surface-card rounded-2xl p-6">
            <h3 class="font-semibold text-base mb-1" style="color:var(--text-primary)">Keamanan Akun</h3>
            <p class="text-xs mb-6" style="color:var(--text-muted)">Gunakan kata sandi yang kuat dan unik.</p>

            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('put')

                @foreach([
                    ['name' => 'current_password', 'label' => 'Sandi Saat Ini',   'error_bag' => 'updatePassword'],
                    ['name' => 'password',          'label' => 'Sandi Baru',        'error_bag' => 'updatePassword'],
                    ['name' => 'password_confirmation','label'=>'Konfirmasi Sandi','error_bag' => null],
                ] as $field)
                <div>
                    <label class="block text-[10px] font-semibold uppercase tracking-widest mb-2"
                           style="color:var(--text-muted)">{{ $field['label'] }}</label>
                    <input type="password" name="{{ $field['name'] }}" required
                           class="w-full px-3 py-2.5 rounded-xl text-sm font-medium outline-none transition-all"
                           style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                           onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px var(--accent-subtle)'"
                           onblur="this.style.borderColor='var(--border-medium)';this.style.boxShadow=''">
                    @if($field['error_bag'])
                        @error($field['name'], $field['error_bag'])
                        <p class="text-xs mt-1" style="color:var(--status-danger-fg)">{{ $message }}</p>
                        @enderror
                    @endif
                </div>
                @endforeach

                <div class="flex justify-end pt-1">
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white transition-all"
                            style="background:var(--accent)"
                            onmouseover="this.style.background='var(--accent-hover)'"
                            onmouseout="this.style.background='var(--accent)'">
                        Ubah Sandi
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

<script>
function previewDashboardPhoto(input) {
    var preview     = document.getElementById('dashboard-photo-preview');
    var placeholder = document.getElementById('dashboard-photo-placeholder');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if (preview)     { preview.src = e.target.result; preview.classList.remove('hidden'); }
            if (placeholder) { placeholder.classList.add('hidden'); }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

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

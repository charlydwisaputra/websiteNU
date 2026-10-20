@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-16">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 text-xs font-semibold py-2 px-3 rounded-xl transition-all min-h-[44px] border"
           style="background:var(--bg-surface);color:var(--text-secondary);border-color:var(--border-medium)"
           onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'"
           onmouseout="this.style.color='var(--text-secondary)';this.style.borderColor='var(--border-medium)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard
        </a>
        <span class="text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full"
              style="background:var(--accent-subtle);color:var(--accent)">
            Admin Workspace
        </span>
    </div>

    {{-- Header --}}
    <div>
        <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">
            Pengaturan Akun & Profil
        </h1>
        <p class="text-xs sm:text-sm mt-1 leading-relaxed" style="color:var(--text-muted)">
            Kelola identitas, foto profil, dan kata sandi akun administrator Anda.
        </p>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
    <div class="p-4 rounded-xl flex items-center gap-3 text-sm font-medium"
         style="background:rgba(0,138,90,0.12);color:var(--accent);border:1px solid rgba(0,138,90,0.25)">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('status') === 'password-updated')
    <div class="p-4 rounded-xl flex items-center gap-3 text-sm font-medium"
         style="background:rgba(0,138,90,0.12);color:var(--accent);border:1px solid rgba(0,138,90,0.25)">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>Kata sandi berhasil diperbarui dengan aman.</span>
    </div>
    @endif

    {{-- Ringkasan Identitas --}}
    <div class="surface-card rounded-2xl p-4 sm:p-5 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl overflow-hidden shrink-0 flex items-center justify-center font-bold text-lg"
             style="background:var(--bg-surface-elevated);color:var(--text-muted);border:1px solid var(--border-light)">
            @if($anggota && $anggota->photo)
                <img src="{{ asset('storage/'.$anggota->photo) }}" class="w-full h-full object-cover">
            @else
                @inisial($user->name)
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2 flex-wrap">
                <h2 class="font-semibold text-base sm:text-lg leading-snug truncate" style="color:var(--text-primary)">
                    {{ $user->name }}
                </h2>
                <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider text-white"
                      style="background:var(--accent)">
                    {{ $user->role === 'admin' ? 'Administrator' : 'Pengurus' }}
                </span>
            </div>
            <p class="text-xs mt-0.5 truncate" style="color:var(--text-muted)">{{ $user->email }}</p>
        </div>
    </div>

    {{-- Grid 2 Form (Informasi Profil & Keamanan Akun) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Form 1: Informasi Profil --}}
        <div class="surface-card rounded-2xl p-4 sm:p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2.5 mb-1">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                         style="background:var(--accent-subtle);color:var(--accent)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-serif font-bold text-lg" style="color:var(--text-primary)">Informasi Profil</h3>
                </div>
                <p class="text-xs mb-5" style="color:var(--text-muted)">Perbarui foto profil dan data kontak Anda.</p>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('patch')

                    {{-- Foto Profil --}}
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-widest mb-2"
                               style="color:var(--text-muted)">Foto Profil</label>
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0 flex items-center justify-center text-xs font-bold"
                                 style="background:var(--bg-surface-elevated);border:1px solid var(--border-light);color:var(--text-muted)">
                                <img id="admin-photo-preview"
                                     src="{{ $anggota && $anggota->photo ? asset('storage/'.$anggota->photo) : '' }}"
                                     class="{{ ($anggota && $anggota->photo) ? '' : 'hidden' }} w-full h-full object-cover">
                                @if(!$anggota || !$anggota->photo)
                                    <span id="admin-photo-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <input type="file" name="photo" id="admin_photo_input"
                                       accept="image/jpeg,image/png,image/jpg"
                                       onchange="previewAdminPhoto(this)"
                                       class="text-xs cursor-pointer w-full py-2 min-h-[44px]"
                                       style="color:var(--text-secondary)">
                                <p class="text-[10px]" style="color:var(--text-muted)">JPG, PNG maks 2MB.</p>
                            </div>
                        </div>
                        @error('photo')
                        <p class="text-xs mt-1" style="color:var(--status-danger-fg)">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-widest mb-1.5"
                               style="color:var(--text-muted)">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                               style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                               onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px var(--accent-subtle)'"
                               onblur="this.style.borderColor='var(--border-medium)';this.style.boxShadow=''">
                        @error('name')
                        <p class="text-xs mt-1" style="color:var(--status-danger-fg)">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-widest mb-1.5"
                               style="color:var(--text-muted)">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                               style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                               onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px var(--accent-subtle)'"
                               onblur="this.style.borderColor='var(--border-medium)';this.style.boxShadow=''">
                        @error('email')
                        <p class="text-xs mt-1" style="color:var(--status-danger-fg)">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-white transition-all min-h-[44px] active:scale-95 touch-manipulation shadow-sm"
                                style="background:var(--accent)"
                                onmouseover="this.style.background='var(--accent-hover)'"
                                onmouseout="this.style.background='var(--accent)'">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Form 2: Keamanan Akun --}}
        <div class="surface-card rounded-2xl p-4 sm:p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2.5 mb-1">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                         style="background:var(--accent-subtle);color:var(--accent)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="font-serif font-bold text-lg" style="color:var(--text-primary)">Keamanan Akun</h3>
                </div>
                <p class="text-xs mb-5" style="color:var(--text-muted)">Gunakan kata sandi baru yang kuat untuk melindungi akun.</p>

                <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('put')

                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-widest mb-1.5"
                               style="color:var(--text-muted)">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" required autocomplete="current-password"
                               class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                               style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                               onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px var(--accent-subtle)'"
                               onblur="this.style.borderColor='var(--border-medium)';this.style.boxShadow=''">
                        @error('current_password', 'updatePassword')
                        <p class="text-xs mt-1" style="color:var(--status-danger-fg)">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-widest mb-1.5"
                               style="color:var(--text-muted)">Kata Sandi Baru</label>
                        <input type="password" name="password" required autocomplete="new-password"
                               class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                               style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                               onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px var(--accent-subtle)'"
                               onblur="this.style.borderColor='var(--border-medium)';this.style.boxShadow=''">
                        @error('password', 'updatePassword')
                        <p class="text-xs mt-1" style="color:var(--status-danger-fg)">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-widest mb-1.5"
                               style="color:var(--text-muted)">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                               class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                               style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                               onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px var(--accent-subtle)'"
                               onblur="this.style.borderColor='var(--border-medium)';this.style.boxShadow=''">
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-white transition-all min-h-[44px] active:scale-95 touch-manipulation shadow-sm"
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

</div>

<script>
function previewAdminPhoto(input) {
    var preview = document.getElementById('admin-photo-preview');
    var placeholder = document.getElementById('admin-photo-placeholder');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if (preview) { preview.src = e.target.result; preview.classList.remove('hidden'); }
            if (placeholder) { placeholder.classList.add('hidden'); }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

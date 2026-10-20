@extends('layouts.admin')

@section('hide_bottom_nav', true)

@section('content')
@php
    $sec1ErrorCount = ($errors->has('name') ? 1 : 0) + ($errors->has('photo') ? 1 : 0);
    $sec2ErrorCount = 0;
    foreach (['banom_id', 'position', 'role', 'status', 'urutan', 'is_pengurus'] as $f) {
        if ($errors->has($f)) $sec2ErrorCount++;
    }
    $sec3ErrorCount = 0;
    foreach (['wilayah', 'region', 'tampil_wilayah', 'phone', 'email', 'address', 'joined_at', 'is_public'] as $f) {
        if ($errors->has($f)) $sec3ErrorCount++;
    }
@endphp

<div class="max-w-3xl mx-auto space-y-6 pb-28 md:pb-12"
     x-data="{
         openSections: {
             identitas: true,
             organisasi: {{ $sec2ErrorCount > 0 ? 'true' : 'false' }},
             wilayah: {{ $sec3ErrorCount > 0 ? 'true' : 'false' }}
         },
         previewUrl: null,
         submitting: false,
         toggleSection(sec) {
             this.openSections[sec] = !this.openSections[sec];
         }
     }">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('admin.anggota.index') }}"
           class="inline-flex items-center gap-2 text-xs font-semibold py-2 px-3 rounded-xl transition-all min-h-[44px] border"
           style="background:var(--bg-surface);color:var(--text-secondary);border-color:var(--border-medium)"
           onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'"
           onmouseout="this.style.color='var(--text-secondary)';this.style.borderColor='var(--border-medium)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Anggota
        </a>
        <span class="text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full"
              style="background:var(--accent-subtle);color:var(--accent)">
            Pendaftaran Baru
        </span>
    </div>

    {{-- Page Header --}}
    <div>
        <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">
            Tambah Anggota Baru
        </h1>
        <p class="text-xs sm:text-sm mt-1 leading-relaxed" style="color:var(--text-muted)">
            Lengkapi data diri, badan otonom, status, dan penugasan organisasi anggota.
        </p>
    </div>

    {{-- Summary Errors Alert --}}
    @if($errors->any())
    <div class="p-4 rounded-xl flex items-start gap-3 text-sm font-medium"
         style="background:rgba(239,68,68,0.1);color:#ef4444;border:1px solid rgba(239,68,68,0.25)">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <p class="font-bold">Gagal menyimpan: terdapat {{ $errors->count() }} kolom yang perlu diperbaiki.</p>
            <ul class="text-xs mt-1 space-y-0.5 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.anggota.store') }}" method="POST" enctype="multipart/form-data" id="anggota-form" class="space-y-5" @submit="submitting = true">
        @csrf

        {{-- Seksi 1: Identitas & Foto Profil --}}
        <div class="surface-card rounded-2xl overflow-hidden border" style="border-color:var(--border-light)">
            <button type="button"
                    @click="toggleSection('identitas')"
                    class="w-full px-5 py-4 flex items-center justify-between text-left transition-colors min-h-[44px]"
                    style="background:var(--bg-surface-elevated)">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                         style="background:var(--accent-subtle);color:var(--accent)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold" style="color:var(--text-primary)">1. Identitas & Foto Profil</h2>
                            @if($sec1ErrorCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-400 border border-red-200 dark:border-red-800">
                                {{ $sec1ErrorCount }} kolom perlu diperbaiki
                            </span>
                            @endif
                        </div>
                        <p class="text-[11px]" style="color:var(--text-muted)">Nama lengkap dan foto kartu anggota</p>
                    </div>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="openSections.identitas ? 'rotate-180' : ''"
                     style="color:var(--text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="openSections.identitas" x-collapse class="p-5 sm:p-6 space-y-5">
                {{-- Foto Profil dengan Pratinjau --}}
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">
                        Foto Profil (Kamera / Galeri HP)
                    </label>
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-24 h-24 rounded-2xl overflow-hidden shrink-0 flex items-center justify-center border font-bold text-lg"
                             style="background:var(--bg-surface-elevated);border-color:var(--border-medium);color:var(--text-muted)">
                            <template x-if="previewUrl">
                                <img :src="previewUrl" class="w-full h-full object-cover" alt="Preview Foto">
                            </template>
                            <template x-if="!previewUrl">
                                <span class="text-xs text-center px-2" style="color:var(--text-muted)">Belum ada foto</span>
                            </template>
                        </div>
                        <div class="flex-1 w-full space-y-1.5">
                            <input type="file" name="photo" id="photo"
                                   accept="image/*"
                                   @change="if ($event.target.files.length) { previewUrl = URL.createObjectURL($event.target.files[0]); }"
                                   class="w-full text-xs cursor-pointer py-2 min-h-[44px]"
                                   style="color:var(--text-secondary)">
                            <p class="text-[11px]" style="color:var(--text-muted)">
                                Format: JPG, PNG, WEBP. Maksimal 2MB. Di HP dapat langsung membuka kamera.
                            </p>
                            @error('photo')
                            <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Nama Lengkap --}}
                <div class="space-y-1.5">
                    <label for="name" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                        Nama Lengkap <span style="color:#ef4444">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           placeholder="Cth: Ahmad Fauzan Mustofa"
                           class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px] @error('name') border-red-500 is-invalid @enderror"
                           style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                           onfocus="this.style.borderColor='var(--accent)'"
                           onblur="this.style.borderColor='var(--border-medium)'">
                    @error('name')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Seksi 2: Organisasi & Penugasan --}}
        <div class="surface-card rounded-2xl overflow-hidden border" style="border-color:var(--border-light)">
            <button type="button"
                    @click="toggleSection('organisasi')"
                    class="w-full px-5 py-4 flex items-center justify-between text-left transition-colors min-h-[44px]"
                    style="background:var(--bg-surface-elevated)">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                         style="background:var(--accent-subtle);color:var(--accent)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold" style="color:var(--text-primary)">2. Organisasi & Penugasan</h2>
                            @if($sec2ErrorCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-400 border border-red-200 dark:border-red-800">
                                {{ $sec2ErrorCount }} kolom perlu diperbaiki
                            </span>
                            @endif
                        </div>
                        <p class="text-[11px]" style="color:var(--text-muted)">Badan otonom, jabatan, status, dan nomor urut</p>
                    </div>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="openSections.organisasi ? 'rotate-180' : ''"
                     style="color:var(--text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="openSections.organisasi" x-collapse class="p-5 sm:p-6 space-y-5">
                {{-- Badan Otonom --}}
                <div class="space-y-1.5">
                    <label for="banom_id" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                        Pilihan Badan Otonom (Banom)
                    </label>
                    <select name="banom_id" id="banom_id"
                            class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                            style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                        <option value="">-- Bukan Anggota Banom Khusus / Pengurus Ranting --</option>
                        @foreach($banoms as $b)
                        <option value="{{ $b->id }}" {{ old('banom_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->nama }} ({{ $b->singkatan }})
                        </option>
                        @endforeach
                    </select>
                    @error('banom_id')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jabatan / Posisi --}}
                <div class="space-y-1.5">
                    <label for="position" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                        Jabatan / Posisi Organisasi
                    </label>
                    <input type="text" name="position" id="position" value="{{ old('position', old('role')) }}"
                           placeholder="Cth: Ketua, Sekretaris, Anggota, Koordinator"
                           class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                           style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                    @error('position')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Keanggotaan --}}
                <div class="space-y-1.5">
                    <label class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                        Status Keanggotaan <span style="color:#ef4444">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        @foreach(['Aktif', 'Pending', 'Alumni', 'Nonaktif'] as $st)
                        <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all min-h-[44px]"
                               style="background:var(--bg-surface-elevated);border-color:var(--border-medium)">
                            <input type="radio" name="status" value="{{ $st }}"
                                   {{ old('status', 'Aktif') === $st ? 'checked' : '' }}
                                   class="text-[var(--accent)] focus:ring-[var(--accent)]">
                            <span class="text-xs font-semibold" style="color:var(--text-primary)">{{ $st }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('status')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Urutan Tampil --}}
                    <div class="space-y-1.5">
                        <label for="urutan" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                            Nomor Urut Tampil
                        </label>
                        <input type="number" name="urutan" id="urutan" value="{{ old('urutan', 0) }}" min="0"
                               class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                               style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                        <p class="text-[10px]" style="color:var(--text-muted)">Angka lebih kecil tampil lebih awal.</p>
                        @error('urutan')
                        <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Flag Tampil di Pengurus Inti --}}
                    <div class="space-y-1.5 flex flex-col justify-end">
                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer min-h-[44px]"
                               style="background:var(--bg-surface-elevated);border-color:var(--border-medium)">
                            <input type="checkbox" name="is_pengurus" value="1"
                                   {{ old('is_pengurus') ? 'checked' : '' }}
                                   class="rounded text-[var(--accent)] focus:ring-[var(--accent)] w-4 h-4">
                            <div>
                                <span class="text-xs font-semibold block" style="color:var(--text-primary)">Pengurus Inti Ranting</span>
                                <span class="text-[10px] block" style="color:var(--text-muted)">Tampilkan di bagan struktur pengurus utama</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Seksi 3: Wilayah & Kontak --}}
        <div class="surface-card rounded-2xl overflow-hidden border" style="border-color:var(--border-light)">
            <button type="button"
                    @click="toggleSection('wilayah')"
                    class="w-full px-5 py-4 flex items-center justify-between text-left transition-colors min-h-[44px]"
                    style="background:var(--bg-surface-elevated)">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                         style="background:var(--accent-subtle);color:var(--accent)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold" style="color:var(--text-primary)">3. Wilayah & Kontak</h2>
                            @if($sec3ErrorCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-400 border border-red-200 dark:border-red-800">
                                {{ $sec3ErrorCount }} kolom perlu diperbaiki
                            </span>
                            @endif
                        </div>
                        <p class="text-[11px]" style="color:var(--text-muted)">RT/RW, kontak, alamat, dan visibilitas direktori</p>
                    </div>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="openSections.wilayah ? 'rotate-180' : ''"
                     style="color:var(--text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="openSections.wilayah" x-collapse class="p-5 sm:p-6 space-y-5">
                {{-- Wilayah RT/RW --}}
                <div class="space-y-1.5">
                    <label for="wilayah" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                        Wilayah Lingkungan / RT RW
                    </label>
                    <input type="text" name="wilayah" id="wilayah" value="{{ old('wilayah', old('region')) }}"
                           placeholder="Cth: RT 03 / RW 02 Kalisari"
                           class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                           style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                    @error('wilayah')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- No Telepon/WhatsApp --}}
                    <div class="space-y-1.5">
                        <label for="phone" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                            Nomor WhatsApp / HP
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               placeholder="Cth: 081234567890"
                               class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                               style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                        @error('phone')
                        <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                            Email Anggota
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               placeholder="Cth: fauzan@example.com"
                               class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                               style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                        @error('email')
                        <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Alamat Lengkap --}}
                <div class="space-y-1.5">
                    <label for="address" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                        Alamat Domisili Lengkap
                    </label>
                    <textarea name="address" id="address" rows="3"
                              placeholder="Jl. Kalisari Raya No. ..., RT ..., RW ..."
                              class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all resize-y"
                              style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">{{ old('address') }}</textarea>
                    @error('address')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Bergabung --}}
                <div class="space-y-1.5">
                    <label for="joined_at" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                        Tanggal Bergabung Organisasi
                    </label>
                    <input type="date" name="joined_at" id="joined_at" value="{{ old('joined_at') }}"
                           class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                           style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                    @error('joined_at')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Visibilitas Publik --}}
                <div class="p-3.5 rounded-xl border flex items-center justify-between gap-4"
                     style="background:var(--bg-surface-elevated);border-color:var(--border-medium)">
                    <div>
                        <p class="text-xs font-semibold" style="color:var(--text-primary)">Tampilkan di Direktori Publik</p>
                        <p class="text-[11px]" style="color:var(--text-muted)">Jika dicentang, profil anggota dapat dilihat masyarakat umum.</p>
                    </div>
                    <input type="hidden" name="is_public" value="0">
                    <input type="checkbox" name="is_public" value="1" id="is_public"
                           {{ old('is_public', '1') == '1' ? 'checked' : '' }}
                           class="rounded text-[var(--accent)] focus:ring-[var(--accent)] w-5 h-5">
                </div>

                {{-- Privasi Wilayah di Publik --}}
                <div class="p-3.5 rounded-xl border flex items-center justify-between gap-4"
                     style="background:var(--bg-surface-elevated);border-color:var(--border-medium)">
                    <div>
                        <p class="text-xs font-semibold" style="color:var(--text-primary)">Tampilkan wilayah di publik</p>
                        <p class="text-[11px]" style="color:var(--text-muted)">Jika dicentang, informasi wilayah/RT-RW akan ditampilkan pada direktori publik.</p>
                    </div>
                    <input type="hidden" name="tampil_wilayah" value="0">
                    <input type="checkbox" name="tampil_wilayah" value="1" id="tampil_wilayah"
                           {{ old('tampil_wilayah', '0') == '1' ? 'checked' : '' }}
                           class="rounded text-[var(--accent)] focus:ring-[var(--accent)] w-5 h-5">
                </div>
            </div>
        </div>

        {{-- Desktop Action Buttons (md+) --}}
        <div class="hidden md:flex items-center justify-end gap-3 pt-4 border-t" style="border-color:var(--border-light)">
            <a href="{{ route('admin.anggota.index') }}"
               class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px] inline-flex items-center border"
               style="color:var(--text-secondary);border-color:var(--border-medium);background:var(--bg-surface)">
                Batal
            </a>
            <button type="submit"
                    :disabled="submitting"
                    :class="submitting ? 'opacity-70 cursor-not-allowed' : ''"
                    class="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white transition-all min-h-[44px] inline-flex items-center gap-2 shadow-sm"
                    style="background:var(--accent)">
                <svg x-show="submitting" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan Data Anggota'">Simpan Data Anggota</span>
            </button>
        </div>

        {{-- Mobile Sticky Action Bar (Hanya HP, md:hidden, Hormati Safe Area, Solid / Backdrop-blur) --}}
        <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 border-t p-3 flex items-center gap-2.5 shadow-2xl"
             style="background:var(--bg-overlay);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-color:var(--border-light);padding-bottom:max(0.75rem,env(safe-area-inset-bottom))">
            <a href="{{ route('admin.anggota.index') }}"
               class="py-3 px-4 rounded-xl text-xs font-semibold text-center transition-all min-h-[48px] border inline-flex items-center justify-center shrink-0 w-24 sm:w-28"
               style="color:var(--text-secondary);border-color:var(--border-medium);background:var(--bg-surface)">
                Batal
            </a>
            <button type="submit"
                    :disabled="submitting"
                    :class="submitting ? 'opacity-70 cursor-not-allowed' : ''"
                    class="flex-1 py-3 px-5 rounded-xl text-xs sm:text-sm font-bold text-white text-center transition-all min-h-[48px] shadow-sm inline-flex items-center justify-center gap-2"
                    style="background:var(--accent)">
                <svg x-show="submitting" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan'">Simpan</span>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const firstError = document.querySelector('.is-invalid, .border-red-500, [aria-invalid="true"]');
    if (firstError) {
        setTimeout(function () {
            firstError.focus();
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    }
});
</script>
@endsection
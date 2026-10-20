@extends('layouts.admin')

@section('hide_bottom_nav', true)

@section('content')
<div class="max-w-3xl mx-auto space-y-6 pb-28 md:pb-12" x-data="{ submitting: false }">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('admin.banom.index') }}"
           class="inline-flex items-center gap-2 text-xs font-semibold py-2 px-3 rounded-xl transition-all min-h-[44px] border"
           style="background:var(--bg-surface);color:var(--text-secondary);border-color:var(--border-medium)"
           onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'"
           onmouseout="this.style.color='var(--text-secondary)';this.style.borderColor='var(--border-medium)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Banom
        </a>
        <span class="badge badge-green">Ekosistem Banom</span>
    </div>

    {{-- Header --}}
    <div>
        <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">
            Tambah Badan Otonom
        </h1>
        <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">
            Daftarkan entitas badan otonom baru dalam ekosistem NU Kalisari.
        </p>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.banom.store') }}" method="POST" enctype="multipart/form-data"
          class="p-5 sm:p-7 rounded-2xl surface-card space-y-5 border" style="border-color:var(--border-light)"
          @submit="submitting = true">
        @csrf

        @if($errors->any())
        <div class="flex items-start gap-3 p-4 rounded-xl text-sm font-medium"
             style="background:rgba(239,68,68,0.08);color:#ef4444;border:1px solid rgba(239,68,68,0.25)">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-bold">Gagal menyimpan: terdapat {{ $errors->count() }} kolom yang perlu diperbaiki.</p>
                <ul class="space-y-0.5 text-xs list-disc list-inside mt-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label for="nama" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Nama Lengkap Organisasi <span style="color:#ef4444">*</span>
                </label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                       placeholder="Cth: Muslimat NU"
                       class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px] @error('nama') border-red-500 is-invalid @enderror"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
                @error('nama') <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label for="singkatan" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Singkatan / Panggilan <span style="color:#ef4444">*</span>
                </label>
                <input type="text" name="singkatan" id="singkatan" value="{{ old('singkatan') }}" required
                       placeholder="Cth: Muslimat"
                       class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px] @error('singkatan') border-red-500 is-invalid @enderror"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
                @error('singkatan') <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-1.5">
            <label for="deskripsi" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                Deskripsi Profil Organisasi
            </label>
            <textarea name="deskripsi" id="deskripsi" rows="3"
                      placeholder="Uraikan visi, ruang lingkup pergerakan, atau peran banom ini di masyarakat..."
                      class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all resize-y"
                      style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                      onfocus="this.style.borderColor='var(--accent)'"
                      onblur="this.style.borderColor='var(--border-medium)'">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label for="koordinator_id" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Ketua / Koordinator Banom
                </label>
                <select name="koordinator_id" id="koordinator_id"
                        class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                        style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                    <option value="">-- Pilih dari Anggota Terdaftar (Opsional) --</option>
                    @foreach(($anggotas ?? $anggota ?? []) as $ang)
                        <option value="{{ $ang->id }}" {{ old('koordinator_id', old('ketua_anggota_id')) == $ang->id ? 'selected' : '' }}>
                            {{ $ang->name }} ({{ $ang->position ?: 'Anggota' }})
                        </option>
                    @endforeach
                </select>
                @error('koordinator_id') <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p> @enderror
                @error('ketua_anggota_id') <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label for="urutan" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Nomor Urut Tampil
                </label>
                <input type="number" name="urutan" id="urutan" value="{{ old('urutan', 0) }}" min="0"
                       class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                @error('urutan') <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-1.5">
            <label for="logo" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                Logo Lambang Organisasi <span class="font-normal" style="color:var(--text-muted)">(Opsional)</span>
            </label>
            <input type="file" name="logo" id="logo" accept="image/*"
                   class="w-full text-xs cursor-pointer py-2 min-h-[44px]"
                   style="color:var(--text-secondary)">
            <p class="text-[11px]" style="color:var(--text-muted)">Biarkan kosong untuk menggunakan logo bawaan sistem berdasarkan singkatan.</p>
            @error('logo') <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p> @enderror
        </div>

        {{-- Desktop Action Buttons (md+) --}}
        <div class="hidden md:flex items-center justify-end gap-3 pt-4 border-t" style="border-color:var(--border-light)">
            <a href="{{ route('admin.banom.index') }}"
               class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px] inline-flex items-center border"
               style="color:var(--text-secondary);border-color:var(--border-medium);background:var(--bg-surface)">
                Batal
            </a>
            <button type="submit"
                    :disabled="submitting"
                    :class="submitting ? 'opacity-70 cursor-not-allowed' : ''"
                    class="px-7 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white transition-all min-h-[44px] inline-flex items-center gap-2 shadow-sm"
                    style="background:var(--accent)">
                <svg x-show="submitting" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan Banom'">Simpan Banom</span>
            </button>
        </div>

        {{-- Mobile Sticky Action Bar (Hanya HP, md:hidden, Hormati Safe Area, Solid / Backdrop-blur) --}}
        <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 border-t p-3 flex items-center gap-2.5 shadow-2xl"
             style="background:var(--bg-overlay);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-color:var(--border-light);padding-bottom:max(0.75rem,env(safe-area-inset-bottom))">
            <a href="{{ route('admin.banom.index') }}"
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

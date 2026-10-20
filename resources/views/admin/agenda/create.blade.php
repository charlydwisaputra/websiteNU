@extends('layouts.admin')

@section('hide_bottom_nav', true)

@section('content')
<div class="max-w-3xl mx-auto space-y-6 pb-28 md:pb-12" x-data="{ submitting: false }">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('admin.agenda.index') }}"
           class="inline-flex items-center gap-2 text-xs font-semibold py-2 px-3 rounded-xl transition-all min-h-[44px] border"
           style="background:var(--bg-surface);color:var(--text-secondary);border-color:var(--border-medium)"
           onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'"
           onmouseout="this.style.color='var(--text-secondary)';this.style.borderColor='var(--border-medium)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Agenda
        </a>
        <span class="badge badge-green">Kegiatan</span>
    </div>

    {{-- Header --}}
    <div>
        <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">
            Tambah Agenda Baru
        </h1>
        <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">
            Publikasikan jadwal kegiatan, pengajian, atau musyawarah ranting NU Kalisari.
        </p>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.agenda.store') }}" method="POST"
          class="p-4 sm:p-7 rounded-2xl surface-card space-y-5 border" style="border-color:var(--border-light)"
          @submit="submitting = true">
        @csrf

        {{-- Error Alert --}}
        @if($errors->any())
        <div class="flex items-start gap-3 p-4 rounded-xl text-sm font-medium"
             style="background:rgba(239,68,68,0.08);color:#ef4444;border:1px solid rgba(239,68,68,0.18)">
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

        {{-- Judul Agenda --}}
        <div class="space-y-1.5">
            <label class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                Nama / Judul Kegiatan <span style="color:#ef4444">*</span>
            </label>
            <input type="text" name="judul" value="{{ old('judul') }}" required
                   placeholder="Misal: Lailatul Ijtima' & Santunan Anak Yatim"
                   class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px] @error('judul') border-red-500 is-invalid @enderror"
                   style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                   onfocus="this.style.borderColor='var(--accent)'"
                   onblur="this.style.borderColor='var(--border-medium)'">
            @error('judul') <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p> @enderror
        </div>

        {{-- Tanggal & Waktu Mulai --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Waktu Pelaksanaan <span style="color:#ef4444">*</span>
                </label>
                <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                       class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px] @error('tanggal_mulai') border-red-500 is-invalid @enderror"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
                <p class="text-[11px]" style="color:var(--text-muted)">Pilih tanggal beserta jam mulai kegiatan.</p>
                @error('tanggal_mulai') <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p> @enderror
            </div>

            {{-- Lokasi Kegiatan --}}
            <div class="space-y-1.5">
                <label class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Lokasi / Tempat Kegiatan <span style="color:#ef4444">*</span>
                </label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" required
                       placeholder="Misal: Masjid Baiturrohim, RW 03 Kalisari"
                       class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px] @error('lokasi') border-red-500 is-invalid @enderror"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
                @error('lokasi') <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Deskripsi Kegiatan --}}
        <div class="space-y-1.5">
            <label class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                Deskripsi / Keterangan Tambahan
            </label>
            <textarea name="deskripsi" rows="4"
                      placeholder="Uraian susunan acara, pemateri, dresscode atau perlengkapan yang perlu dibawa jamaah..."
                      class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all resize-y"
                      style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                      onfocus="this.style.borderColor='var(--accent)'"
                      onblur="this.style.borderColor='var(--border-medium)'">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p> @enderror
        </div>

        {{-- Desktop Action Buttons (md+) --}}
        <div class="hidden md:flex items-center justify-end gap-3 pt-4 border-t" style="border-color:var(--border-light)">
            <a href="{{ route('admin.agenda.index') }}"
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
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan Agenda'">Simpan Agenda</span>
            </button>
        </div>

        {{-- Mobile Sticky Action Bar (Hanya HP, md:hidden, Hormati Safe Area, Solid / Backdrop-blur) --}}
        <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 border-t p-3 flex items-center gap-2.5 shadow-2xl"
             style="background:var(--bg-overlay);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-color:var(--border-light);padding-bottom:max(0.75rem,env(safe-area-inset-bottom))">
            <a href="{{ route('admin.agenda.index') }}"
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
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan Agenda'">Simpan Agenda</span>
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

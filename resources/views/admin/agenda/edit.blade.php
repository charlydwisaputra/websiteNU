@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 pb-12">

    {{-- Header --}}
    <div class="p-4 sm:p-7 rounded-2xl surface-card border" style="border-color:var(--border-light)">
        <span class="badge badge-green mb-2.5 inline-flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full" style="background:var(--accent)"></span>
            Perbarui Kegiatan
        </span>
        <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">
            Edit Agenda Kegiatan
        </h1>
        <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">
            Perbarui informasi waktu, lokasi, atau deskripsi agenda kegiatan.
        </p>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.agenda.update', $agenda->id) }}" method="POST"
          class="p-4 sm:p-7 rounded-2xl surface-card space-y-5 border" style="border-color:var(--border-light)">
        @csrf
        @method('PUT')

        {{-- Error Alert --}}
        @if($errors->any())
        <div class="flex items-start gap-3 p-4 rounded-xl text-sm font-medium"
             style="background:rgba(239,68,68,0.08);color:#ef4444;border:1px solid rgba(239,68,68,0.18)">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <ul class="space-y-1 text-xs sm:text-sm">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Judul Agenda --}}
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">
                Nama / Judul Kegiatan <span style="color:#ef4444">*</span>
            </label>
            <input type="text" name="judul" value="{{ old('judul', $agenda->judul) }}" required
                   placeholder="Misal: Lailatul Ijtima' & Santunan Anak Yatim"
                   class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm outline-none transition-all min-h-[44px]"
                   style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                   onfocus="this.style.borderColor='var(--accent)'"
                   onblur="this.style.borderColor='var(--border-medium)'">
        </div>

        {{-- Tanggal & Waktu Mulai --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">
                    Waktu Pelaksanaan <span style="color:#ef4444">*</span>
                </label>
                <input type="datetime-local" name="tanggal_mulai"
                       value="{{ old('tanggal_mulai', $agenda->tanggal_mulai ? $agenda->tanggal_mulai->format('Y-m-d\TH:i') : '') }}"
                       required
                       class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm outline-none transition-all min-h-[44px]"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
                <p class="text-[11px] mt-1" style="color:var(--text-muted)">Pilih tanggal beserta jam mulai kegiatan.</p>
            </div>

            {{-- Lokasi Kegiatan --}}
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">
                    Lokasi / Tempat Kegiatan <span style="color:#ef4444">*</span>
                </label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $agenda->lokasi) }}" required
                       placeholder="Misal: Masjid Baiturrohim, RW 03 Kalisari"
                       class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm outline-none transition-all min-h-[44px]"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
            </div>
        </div>

        {{-- Deskripsi Kegiatan --}}
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">
                Deskripsi / Keterangan Tambahan
            </label>
            <textarea name="deskripsi" rows="4"
                      placeholder="Uraian susunan acara, pemateri, dresscode atau perlengkapan yang perlu dibawa jamaah..."
                      class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm outline-none transition-all resize-y"
                      style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                      onfocus="this.style.borderColor='var(--accent)'"
                      onblur="this.style.borderColor='var(--border-medium)'">{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
        </div>

        {{-- Action Buttons (Mobile-first min-h-[44px]) --}}
        <div class="pt-4 flex flex-col-reverse sm:flex-row items-stretch sm:items-center gap-3 border-t"
             style="border-color:var(--border-light)">
            <a href="{{ route('admin.agenda.index') }}"
               class="inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold transition-all min-h-[44px] border text-center"
               style="background:var(--bg-surface-elevated);color:var(--text-secondary);border-color:var(--border-medium)"
               onmouseover="this.style.color='var(--text-primary)'"
               onmouseout="this.style.color='var(--text-secondary)'">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-7 py-3 rounded-xl text-sm font-semibold text-white transition-all min-h-[44px] shadow-sm active:scale-95 text-center"
                    style="background:var(--accent)"
                    onmouseover="this.style.background='var(--accent-hover)'"
                    onmouseout="this.style.background='var(--accent)'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

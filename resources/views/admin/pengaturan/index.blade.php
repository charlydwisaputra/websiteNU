@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 pb-16">

    {{-- Header --}}
    <div class="p-4 sm:p-7 rounded-2xl surface-card border" style="border-color:var(--border-light)">
        <span class="badge badge-green mb-2.5 inline-flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full" style="background:var(--accent)"></span>
            Pengaturan Situs
        </span>
        <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">Pengaturan</h1>
        <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">
            Kelola konfigurasi metrik dan informasi pelengkap website NU Kalisari.
        </p>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-4 sm:px-5 py-3.5 rounded-xl text-sm font-medium"
         style="background:var(--accent-subtle);color:var(--accent);border:1px solid rgba(0,140,90,0.2)">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="break-words">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Info Card Hijau --}}
    <div class="p-4 sm:p-6 rounded-2xl surface-card border" style="border-color:var(--border-light)">
        <div class="flex items-start gap-3 p-4 rounded-xl"
             style="background:var(--accent-subtle);border:1px solid rgba(0,140,90,0.18)">
            <svg class="w-5 h-5 shrink-0 mt-0.5" style="color:var(--accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-xs sm:text-sm leading-relaxed" style="color:var(--text-primary)">
                Koordinator, logo, dan urutan badan otonom diatur di menu
                <a href="{{ route('admin.banom.index') }}" class="underline font-semibold" style="color:var(--accent)">Badan Otonom</a>.
                Pengurus inti diatur di menu
                <a href="{{ route('admin.pengurus.index') }}" class="underline font-semibold" style="color:var(--accent)">Pengurus</a>.
            </div>
        </div>
    </div>

    {{-- Alert Error Validasi --}}
    @if($errors->any())
    <div class="p-4 sm:p-5 rounded-xl text-sm font-medium bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-300 space-y-1">
        <div class="flex items-center gap-2 font-semibold">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Terdapat kesalahan pada input pengaturan:</span>
        </div>
        <ul class="list-disc list-inside pl-7 text-xs sm:text-sm space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Seksi Media Banner Video Beranda (Hero Media) --}}
        <div class="p-4 sm:p-7 rounded-2xl surface-card space-y-5 border" style="border-color:var(--border-light)">
            <div class="pb-3 border-b" style="border-color:var(--border-light)">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                    <h2 class="font-serif text-lg sm:text-xl font-bold" style="color:var(--text-primary)">Banner Video Beranda (Hero Media)</h2>
                </div>
                <p class="text-xs mt-1" style="color:var(--text-muted)">
                    Ganti video dan poster latar belakang di Beranda publik tanpa mengubah kode. Jika dikosongkan, sistem otomatis menggunakan video dan foto desa default.
                </p>
            </div>

            <div class="space-y-6">
                {{-- 1. Video Desktop --}}
                <div class="p-4 rounded-xl border bg-black/[0.02] dark:bg-white/[0.02]" style="border-color:var(--border-light)">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                        <label for="hero_video_desktop" class="text-xs font-semibold uppercase tracking-wider" style="color:var(--text-primary)">
                            Video Banner Desktop (Layar Lebar)
                        </label>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">Format MP4 / WebM &bull; Maks 15 MB</span>
                    </div>

                    @if(!empty($heroMedia['video_desktop']))
                    <div class="mb-3 space-y-2">
                        <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Video desktop aktif saat ini:
                        </p>
                        <div class="max-w-md rounded-xl overflow-hidden border border-black/10 dark:border-white/10 bg-black">
                            <video src="{{ asset('storage/' . $heroMedia['video_desktop']) }}" controls playsinline preload="metadata" class="w-full max-h-48 object-cover"></video>
                        </div>
                        <label class="inline-flex items-center gap-2 text-xs text-red-600 dark:text-red-400 cursor-pointer pt-1">
                            <input type="checkbox" name="delete_hero_video_desktop" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                            <span>Hapus video ini (kembali ke video desa default)</span>
                        </label>
                    </div>
                    @else
                    <p class="text-xs text-slate-500 dark:text-slate-400 italic mb-2">
                        Saat ini menggunakan video default: <code class="text-[11px] px-1.5 py-0.5 rounded bg-black/5 dark:bg-white/10">public/videos/village_sample.mp4</code>
                    </p>
                    @endif

                    <input type="file"
                           id="hero_video_desktop"
                           name="hero_video_desktop"
                           accept="video/mp4,video/webm"
                           class="w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-medium outline-none transition-all file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 dark:file:bg-emerald-950 file:text-emerald-700 dark:file:text-emerald-300"
                           style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                </div>

                {{-- 2. Video Mobile (Opsional) --}}
                <div class="p-4 rounded-xl border bg-black/[0.02] dark:bg-white/[0.02]" style="border-color:var(--border-light)">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                        <label for="hero_video_mobile" class="text-xs font-semibold uppercase tracking-wider" style="color:var(--text-primary)">
                            Video Banner Mobile (Opsional &bull; Layar HP)
                        </label>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">Format MP4 / WebM &bull; Maks 15 MB</span>
                    </div>

                    @if(!empty($heroMedia['video_mobile']))
                    <div class="mb-3 space-y-2">
                        <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Video mobile aktif saat ini:
                        </p>
                        <div class="w-48 rounded-xl overflow-hidden border border-black/10 dark:border-white/10 bg-black">
                            <video src="{{ asset('storage/' . $heroMedia['video_mobile']) }}" controls playsinline preload="metadata" class="w-full max-h-56 object-cover"></video>
                        </div>
                        <label class="inline-flex items-center gap-2 text-xs text-red-600 dark:text-red-400 cursor-pointer pt-1">
                            <input type="checkbox" name="delete_hero_video_mobile" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                            <span>Hapus video mobile ini (desktop video akan dipakai bersama)</span>
                        </label>
                    </div>
                    @else
                    <p class="text-xs text-slate-500 dark:text-slate-400 italic mb-2">
                        Belum diatur. HP akan otomatis memuat video desktop yang disesuaikan.
                    </p>
                    @endif

                    <input type="file"
                           id="hero_video_mobile"
                           name="hero_video_mobile"
                           accept="video/mp4,video/webm"
                           class="w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-medium outline-none transition-all file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 dark:file:bg-emerald-950 file:text-emerald-700 dark:file:text-emerald-300"
                           style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                </div>

                {{-- 3. Poster Video Hero --}}
                <div class="p-4 rounded-xl border bg-black/[0.02] dark:bg-white/[0.02]" style="border-color:var(--border-light)">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                        <label for="hero_poster" class="text-xs font-semibold uppercase tracking-wider" style="color:var(--text-primary)">
                            Gambar Poster Hero (Cadangan / Hemat Data)
                        </label>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">Format JPG / WebP &bull; Maks 4 MB</span>
                    </div>

                    @if(!empty($heroMedia['poster']))
                    <div class="mb-3 space-y-2">
                        <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Poster kustom aktif saat ini:
                        </p>
                        <div class="max-w-md rounded-xl overflow-hidden border border-black/10 dark:border-white/10 bg-black/10">
                            <img src="{{ asset('storage/' . $heroMedia['poster']) }}" alt="Poster Hero" class="w-full h-32 object-cover">
                        </div>
                        <label class="inline-flex items-center gap-2 text-xs text-red-600 dark:text-red-400 cursor-pointer pt-1">
                            <input type="checkbox" name="delete_hero_poster" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                            <span>Hapus poster kustom (kembali ke foto pemandangan default)</span>
                        </label>
                    </div>
                    @else
                    <p class="text-xs text-slate-500 dark:text-slate-400 italic mb-2">
                        Saat ini menggunakan foto lanskap default: <code class="text-[11px] px-1.5 py-0.5 rounded bg-black/5 dark:bg-white/10">public/images/banner-1-1920.webp</code>
                    </p>
                    @endif

                    <input type="file"
                           id="hero_poster"
                           name="hero_poster"
                           accept="image/jpeg,image/webp,image/png"
                           class="w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-medium outline-none transition-all file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 dark:file:bg-emerald-950 file:text-emerald-700 dark:file:text-emerald-300"
                           style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                    <p class="text-[11px] mt-1.5 text-slate-500 dark:text-slate-400">
                        Ditampilkan sebelum video berputar, serta menjadi gambar utama jika pengunjung mengaktifkan mode hemat data atau pengurangan animasi.
                    </p>
                </div>
            </div>
        </div>

        {{-- Seksi Statistik (Satu kolom di HP) --}}
        <div class="p-4 sm:p-7 rounded-2xl surface-card space-y-4 border" style="border-color:var(--border-light)">
            <div class="pb-3 border-b" style="border-color:var(--border-light)">
                <h2 class="font-serif text-lg sm:text-xl font-bold" style="color:var(--text-primary)">Statistik Website</h2>
                <p class="text-xs mt-0.5" style="color:var(--text-muted)">
                    Jumlah anggota dan badan otonom dihitung otomatis dari database. Anda dapat memilih mode perhitungan kegiatan di bawah ini.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4">
                {{-- Mode Kegiatan --}}
                <div>
                    <label for="stat_kegiatan_mode" class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">
                        Mode Perhitungan Angka Kegiatan
                    </label>
                    <select name="stat_kegiatan_mode" id="stat_kegiatan_mode"
                            class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                            style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                            onfocus="this.style.borderColor='var(--accent)'"
                            onblur="this.style.borderColor='var(--border-medium)'">
                        <option value="auto" {{ $kegiatanMode === 'auto' ? 'selected' : '' }}>
                            Otomatis (Dihitung dari Agenda)
                        </option>
                        <option value="manual" {{ $kegiatanMode === 'manual' ? 'selected' : '' }}>
                            Manual (Gunakan angka khusus yang diisi di bawah)
                        </option>
                    </select>
                </div>

                {{-- Nilai Manual --}}
                <div>
                    <label for="stat_kegiatan_per_bulan" class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">
                        Kegiatan Rutin / Bulan (Jika Mode Manual)
                    </label>
                    <input type="number"
                           id="stat_kegiatan_per_bulan"
                           name="stat_kegiatan_per_bulan"
                           value="{{ old('stat_kegiatan_per_bulan', $kegiatanPerBulan) }}"
                           placeholder="Contoh: 12"
                           min="0"
                           class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                           style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                           onfocus="this.style.borderColor='var(--accent)'"
                           onblur="this.style.borderColor='var(--border-medium)'">
                    <p class="text-[11px] mt-1" style="color:var(--text-muted)">
                        Bila memilih mode otomatis, angka ini disimpan sebagai cadangan.
                    </p>
                </div>
            </div>
        </div>

        {{-- Tombol Simpan Sticky di HP --}}
        <div class="sticky bottom-16 md:static p-4 rounded-2xl surface-card border flex items-center justify-end shadow-md z-20"
             style="background:var(--bg-surface);border-color:var(--border-light)">
            <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 rounded-xl text-sm font-semibold text-white transition-all min-h-[44px] shadow-sm active:scale-95 text-center"
                    style="background:var(--accent)"
                    onmouseover="this.style.background='var(--accent-hover)'"
                    onmouseout="this.style.background='var(--accent)'">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection

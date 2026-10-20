@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-16">

    {{-- Header --}}
    <div class="p-4 sm:p-7 rounded-2xl surface-card border" style="border-color:var(--border-light)">
        <span class="badge badge-green mb-2.5 inline-flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full" style="background:var(--accent)"></span>
            Struktur Organisasi
        </span>
        <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">
            Pengurus Inti
        </h1>
        <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">
            Tetapkan pejabat Syuriyah dan Tanfidziyah langsung dari daftar anggota. Perubahan tersimpan secara atomik.
        </p>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-4 sm:px-5 py-3.5 rounded-xl text-sm font-medium"
         style="background:var(--accent-subtle);color:var(--accent);border:1px solid rgba(0,140,90,0.2)">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="break-words">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Info Card --}}
    <div class="p-4 sm:p-6 rounded-2xl surface-card border" style="border-color:var(--border-light)">
        <div class="flex items-start gap-3 p-4 rounded-xl"
             style="background:var(--accent-subtle);border:1px solid rgba(0,140,90,0.18)">
            <svg class="w-5 h-5 shrink-0 mt-0.5" style="color:var(--accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-xs sm:text-sm leading-relaxed" style="color:var(--text-primary)">
                Pejabat diambil langsung dari database anggota terdaftar. Bila jabatan wakil dikosongkan, nama tidak akan ditampilkan di halaman publik dan tidak menyimpan nilai dummy.
            </div>
        </div>
    </div>

    <form action="{{ route('admin.pengurus.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Seksi Syuriyah --}}
        <div class="p-4 sm:p-7 rounded-2xl surface-card space-y-4 border" style="border-color:var(--border-light)">
            <div class="pb-3 border-b flex items-center justify-between" style="border-color:var(--border-light)">
                <div>
                    <h2 class="font-serif text-lg sm:text-xl font-bold" style="color:var(--text-primary)">Dewan Syuriyah</h2>
                    <p class="text-xs mt-0.5" style="color:var(--text-muted)">Pimpinan tertinggi pembina dan penjaga syariat di ranting.</p>
                </div>
                <span class="badge" style="background:var(--bg-surface-elevated);color:var(--text-secondary);border:1px solid var(--border-light)">
                    Syuriyah
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                @foreach($positions as $key => $meta)
                @if($meta['category'] === 'Syuriyah')
                <div>
                    <label for="{{ $key }}" class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">
                        {{ $meta['name'] }}
                        @if($meta['is_wakil'])
                        <span class="text-[11px] font-normal lowercase" style="color:var(--text-muted)">(opsional)</span>
                        @else
                        <span style="color:#ef4444">*</span>
                        @endif
                    </label>
                    <select name="{{ $key }}" id="{{ $key }}"
                            class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                            style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                            onfocus="this.style.borderColor='var(--accent)'"
                            onblur="this.style.borderColor='var(--border-medium)'">
                        <option value="">— Kosong / Belum ada pejabat —</option>
                        @foreach($anggotas as $anggota)
                        <option value="{{ $anggota->id }}" {{ ($currentOfficers[$key] ?? null) == $anggota->id ? 'selected' : '' }}>
                            {{ $anggota->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif
                @endforeach
            </div>
        </div>

        {{-- Seksi Tanfidziyah --}}
        <div class="p-4 sm:p-7 rounded-2xl surface-card space-y-4 border" style="border-color:var(--border-light)">
            <div class="pb-3 border-b flex items-center justify-between" style="border-color:var(--border-light)">
                <div>
                    <h2 class="font-serif text-lg sm:text-xl font-bold" style="color:var(--text-primary)">Dewan Tanfidziyah</h2>
                    <p class="text-xs mt-0.5" style="color:var(--text-muted)">Pelaksana harian kegiatan dan kepengurusan organisasi ranting.</p>
                </div>
                <span class="badge" style="background:var(--bg-surface-elevated);color:var(--text-secondary);border:1px solid var(--border-light)">
                    Tanfidziyah
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                @foreach($positions as $key => $meta)
                @if($meta['category'] === 'Tanfidziyah')
                <div>
                    <label for="{{ $key }}" class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">
                        {{ $meta['name'] }}
                        @if($meta['is_wakil'])
                        <span class="text-[11px] font-normal lowercase" style="color:var(--text-muted)">(opsional)</span>
                        @else
                        <span style="color:#ef4444">*</span>
                        @endif
                    </label>
                    <select name="{{ $key }}" id="{{ $key }}"
                            class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                            style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                            onfocus="this.style.borderColor='var(--accent)'"
                            onblur="this.style.borderColor='var(--border-medium)'">
                        <option value="">— Kosong / Belum ada pejabat —</option>
                        @foreach($anggotas as $anggota)
                        <option value="{{ $anggota->id }}" {{ ($currentOfficers[$key] ?? null) == $anggota->id ? 'selected' : '' }}>
                            {{ $anggota->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif
                @endforeach
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
                Simpan Susunan Pengurus
            </button>
        </div>
    </form>
</div>
@endsection

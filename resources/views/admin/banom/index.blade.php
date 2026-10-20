@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 sm:p-7 rounded-2xl surface-card border"
         style="border-color:var(--border-light)">
        <div>
            <span class="badge badge-green mb-2.5 inline-flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--accent)"></span>
                Modul Ekosistem
            </span>
            <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">Badan Otonom</h1>
            <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">Kelola daftar badan otonom dan koordinatornya yang tampil di halaman Ekosistem.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
            {{-- Tombol Impor HANYA muncul jika tabel banoms kosong --}}
            @if($banoms->isEmpty())
            <form action="{{ route('admin.banom.seed') }}" method="POST"
                  onsubmit="return confirm('Impor badan otonom dari konfigurasi? Data yang sudah ada tidak akan ditimpa.')"
                  class="w-full sm:w-auto">
                @csrf
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-semibold transition-all min-h-[44px] border"
                        style="color:var(--text-secondary);border-color:var(--border-medium);background:var(--bg-surface-elevated)"
                        onmouseover="this.style.color='var(--text-primary)'"
                        onmouseout="this.style.color='var(--text-secondary)'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Impor dari Konfigurasi
                </button>
            </form>
            @endif

            <a href="{{ route('admin.banom.create') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold text-white transition-all min-h-[44px] shadow-sm active:scale-95"
               style="background:var(--accent)"
               onmouseover="this.style.background='var(--accent-hover)'"
               onmouseout="this.style.background='var(--accent)'">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Banom
            </a>
        </div>
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

    {{-- Content List: Mobile Cards + Desktop Table --}}
    <div class="space-y-4">

        {{-- MOBILE VIEW: Card List (360px - 430px) --}}
        <div class="block md:hidden space-y-3.5">
            @forelse($banoms as $banom)
            <div class="p-4 rounded-2xl surface-card space-y-3 border transition-all"
                 style="border-color:var(--border-light)">
                
                {{-- Card Header: Urutan & Singkatan (jika beda) --}}
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-lg"
                          style="background:var(--bg-surface-elevated);color:var(--text-muted)">
                        Urutan #{{ $banom->urutan }}
                    </span>
                    @if($banom->singkatan && $banom->singkatan !== $banom->nama)
                    <span class="badge badge-green text-xs">
                        {{ $banom->singkatan }}
                    </span>
                    @endif
                </div>

                {{-- Card Body: Logo & Info --}}
                <div class="flex items-start gap-3.5">
                    @if($banom->logo_url)
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 border p-1.5 overflow-hidden"
                         style="background:var(--bg-surface-elevated);border-color:var(--border-light)">
                        <img src="{{ $banom->logo_url }}"
                             alt="Logo {{ $banom->nama }}"
                             width="48"
                             height="48"
                             class="max-w-full max-h-full object-contain"
                             onerror="this.parentElement.innerHTML='<span class=\'font-serif font-semibold text-sm\' style=\'color:var(--accent)\'>{{ substr($banom->singkatan, 0, 2) }}</span>'">
                    </div>
                    @else
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 font-serif font-bold text-sm"
                         style="background:var(--accent-subtle);color:var(--accent)">
                        {{ substr($banom->singkatan, 0, 2) }}
                    </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <h2 class="font-serif font-bold text-base leading-snug" style="color:var(--text-primary)">
                            {{ $banom->nama }}
                        </h2>
                        <div class="mt-1">
                            <span class="text-[11px] uppercase tracking-wider font-semibold block" style="color:var(--text-muted)">
                                Koordinator:
                            </span>
                            @if($banom->koordinator)
                                <p class="text-xs sm:text-sm font-semibold" style="color:var(--text-primary)">
                                    {{ $banom->koordinator->name }}
                                </p>
                            @else
                                <span class="text-xs italic" style="color:var(--text-muted)">
                                    Belum ada koordinator
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Card Actions: Min-h-[44px] --}}
                <div class="pt-2 flex items-center gap-2 border-t" style="border-color:var(--border-light)">
                    <a href="{{ route('admin.banom.edit', $banom->id) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px]"
                       style="background:var(--accent-subtle);color:var(--accent)"
                       aria-label="Edit Badan Otonom {{ $banom->nama }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('admin.banom.destroy', $banom->id) }}" method="POST"
                          class="inline"
                          onsubmit="return confirm('Hapus {{ $banom->nama }}? Anggota tidak ikut terhapus, hanya hubungannya dilepas.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-1 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px]"
                                style="background:rgba(239,68,68,0.08);color:#ef4444"
                                aria-label="Hapus Badan Otonom {{ $banom->nama }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-8 text-center rounded-2xl surface-card border" style="border-color:var(--border-light)">
                <p class="text-sm font-semibold" style="color:var(--text-primary)">Belum ada badan otonom</p>
                <p class="text-xs mt-1" style="color:var(--text-muted)">Impor dari konfigurasi bawaan atau tambahkan secara manual.</p>
                <form action="{{ route('admin.banom.seed') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold transition-all min-h-[44px]"
                            style="background:var(--accent-subtle);color:var(--accent)">
                        Impor dari Konfigurasi
                    </button>
                </form>
            </div>
            @endforelse
        </div>

        {{-- DESKTOP VIEW: Table (>= 768px) --}}
        <div class="hidden md:block surface-card rounded-2xl overflow-hidden border" style="border-color:var(--border-light)">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider"
                            style="background:var(--bg-surface-elevated);border-bottom:1px solid var(--border-light);color:var(--text-muted)">
                            <th class="px-6 py-4 text-center w-20">Urutan</th>
                            <th class="px-6 py-4">Badan Otonom</th>
                            <th class="px-6 py-4">Koordinator</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banoms as $banom)
                        <tr class="transition-colors" style="border-bottom:1px solid var(--border-light)"
                            onmouseover="this.style.background='var(--bg-surface-elevated)'"
                            onmouseout="this.style.background=''">
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-semibold" style="color:var(--text-muted)">{{ $banom->urutan }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($banom->logo_url)
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border p-1.5 overflow-hidden"
                                         style="background:var(--bg-surface-elevated);border-color:var(--border-light)">
                                        <img src="{{ $banom->logo_url }}"
                                             alt="Logo {{ $banom->nama }}"
                                             width="40"
                                             height="40"
                                             class="max-w-full max-h-full object-contain"
                                             onerror="this.parentElement.innerHTML='<span class=\'font-serif font-semibold text-sm\' style=\'color:var(--accent)\'>{{ substr($banom->singkatan, 0, 2) }}</span>'">
                                    </div>
                                    @else
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                                         style="background:var(--accent-subtle);color:var(--accent)">
                                        <span class="font-serif font-medium text-sm">{{ substr($banom->singkatan, 0, 2) }}</span>
                                    </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold leading-snug" style="color:var(--text-primary)">{{ $banom->nama }}</p>
                                        {{-- Tampilkan singkatan hanya jika berbeda dari nama --}}
                                        @if($banom->singkatan && $banom->singkatan !== $banom->nama)
                                        <span class="badge badge-green mt-0.5">{{ $banom->singkatan }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($banom->koordinator)
                                    <p class="text-sm font-medium" style="color:var(--text-primary)">{{ $banom->koordinator->name }}</p>
                                @else
                                    <span class="text-xs" style="color:var(--text-muted)">
                                        Belum ada koordinator
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.banom.edit', $banom->id) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all min-h-[36px] inline-flex items-center"
                                       style="background:var(--accent-subtle);color:var(--accent)">Edit</a>
                                    <form action="{{ route('admin.banom.destroy', $banom->id) }}" method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Hapus {{ $banom->nama }}? Anggota tidak ikut terhapus, hanya hubungannya dilepas.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all min-h-[36px] inline-flex items-center"
                                                style="background:rgba(239,68,68,0.08);color:#ef4444">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4"
                                         style="background:var(--bg-surface-elevated);color:var(--text-muted);border:1px solid var(--border-light)">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold mb-1" style="color:var(--text-primary)">Belum ada badan otonom</p>
                                    <p class="text-xs mb-5" style="color:var(--text-muted)">Impor dari konfigurasi atau tambah secara manual.</p>
                                    <div class="flex gap-3">
                                        <form action="{{ route('admin.banom.seed') }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 rounded-xl text-xs font-semibold transition-all"
                                                    style="background:var(--accent-subtle);color:var(--accent)">
                                                Impor dari Konfigurasi
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.banom.create') }}"
                                           class="px-4 py-2 rounded-xl text-xs font-semibold text-white transition-all"
                                           style="background:var(--accent)">
                                            + Tambah Manual
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

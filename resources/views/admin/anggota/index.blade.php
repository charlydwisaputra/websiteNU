@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-16"
     x-data="{
         showFilterSheet: false,
         showDeleteModal: false,
         deleteAction: '',
         deleteName: '',
         showQrModal: false,
         qrUrl: '',
         qrName: '',
         memberId: ''
     }">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 sm:p-7 rounded-2xl surface-card">
        <div>
            <span class="badge badge-green mb-2 inline-flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--accent)"></span>
                Modul Keanggotaan
            </span>
            <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">Data Anggota</h1>
            <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">Kelola data seluruh anggota organisasi berdasarkan Badan Otonom.</p>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            @php
                $printCardsParams = array_filter([
                    'q' => request('search', request('q')),
                    'banom_id' => request('banom_id'),
                    'status' => request('status'),
                ]);
            @endphp
            <a href="{{ route('admin.anggota.print-cards', $printCardsParams) }}"
               class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all border min-h-[44px]"
               style="color:var(--text-secondary);border-color:var(--border-medium);background:var(--bg-surface)"
               onmouseover="this.style.color='var(--text-primary)'"
               onmouseout="this.style.color='var(--text-secondary)'">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak Massal</span>
            </a>
            <a href="{{ route('admin.anggota.create') }}"
               class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white transition-all min-h-[44px] shadow-sm active:scale-95 touch-manipulation"
               style="background:var(--accent)"
               onmouseover="this.style.background='var(--accent-hover)'"
               onmouseout="this.style.background='var(--accent)'">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Anggota
            </a>
        </div>
    </div>

    {{-- Toast Notification --}}
    @if(session('success'))
    <div class="flex items-center justify-between gap-3 px-4 sm:px-5 py-3.5 rounded-xl text-sm font-medium transition-all"
         style="background:rgba(0,138,90,0.12);color:var(--accent);border:1px solid rgba(0,138,90,0.25)">
        <div class="flex items-center gap-2.5 min-w-0">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="break-words">{{ session('success') }}</span>
        </div>
        <button type="button" @click="$el.parentElement.remove()" class="p-1 rounded-lg hover:opacity-75 transition-opacity" title="Tutup">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    {{-- Search & Filter Section --}}
    <div class="p-4 sm:p-6 rounded-2xl surface-card space-y-3">
        <form id="anggota-filter-form" action="{{ route('admin.anggota.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5">
            {{-- Search Bar --}}
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" style="color:var(--text-muted)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama, jabatan, atau wilayah..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
            </div>

            {{-- Desktop Filters (Hidden on Mobile) --}}
            <div class="hidden sm:flex items-center gap-2.5">
                <select name="banom_id"
                        class="form-select-custom px-4 py-2.5 rounded-xl text-sm font-medium outline-none transition-all min-h-[44px] w-44"
                        style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                    <option value="">Semua Banom</option>
                    @foreach($banoms as $b)
                    <option value="{{ $b->id }}" {{ request('banom_id') == $b->id ? 'selected' : '' }}>
                        {{ $b->singkatan ?? $b->nama }}
                    </option>
                    @endforeach
                </select>

                <select name="status"
                        class="form-select-custom px-4 py-2.5 rounded-xl text-sm font-medium outline-none transition-all min-h-[44px] w-36"
                        style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                    <option value="">Semua Status</option>
                    @foreach(['Aktif','Nonaktif','Alumni','Pending'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all min-h-[44px]"
                        style="background:var(--text-primary)"
                        onmouseover="this.style.opacity='.88'"
                        onmouseout="this.style.opacity='1'">
                    Cari
                </button>
            </div>

            {{-- Mobile Action Buttons: Submit Search + Open Bottom Sheet --}}
            <div class="flex sm:hidden items-center gap-2">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition-all min-h-[44px]"
                        style="background:var(--text-primary)">
                    Cari
                </button>
                <button type="button"
                        @click="showFilterSheet = true"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all min-h-[44px] border"
                        style="background:var(--bg-surface-elevated);color:var(--text-secondary);border-color:var(--border-medium)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                    @php
                        $activeFiltersCount = (request('status') ? 1 : 0) + (request('banom_id') ? 1 : 0);
                    @endphp
                    @if($activeFiltersCount > 0)
                    <span class="w-5 h-5 rounded-full text-[11px] font-bold text-white flex items-center justify-center"
                          style="background:var(--accent)">{{ $activeFiltersCount }}</span>
                    @endif
                </button>
            </div>
        </form>

        {{-- Active Filter Chips --}}
        @php
            $hasActiveFilters = request('search') || request('status') || request('banom_id');
        @endphp
        @if($hasActiveFilters)
        <div class="flex items-center gap-2 flex-wrap pt-2 border-t" style="border-color:var(--border-light)">
            <span class="text-[11px] font-medium" style="color:var(--text-muted)">Filter aktif:</span>

            @if(request('search'))
            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
               style="background:var(--bg-surface-elevated);color:var(--text-primary);border:1px solid var(--border-medium)"
               title="Hapus filter cari">
                <span>"{{ request('search') }}"</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif

            @if(request('banom_id'))
            @php $currentBanom = $banoms->firstWhere('id', request('banom_id')); @endphp
            <a href="{{ request()->fullUrlWithQuery(['banom_id' => null]) }}"
               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
               style="background:var(--bg-surface-elevated);color:var(--text-primary);border:1px solid var(--border-medium)"
               title="Hapus filter banom">
                <span>Banom: {{ $currentBanom->singkatan ?? $currentBanom->nama ?? 'ID #'.request('banom_id') }}</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif

            @if(request('status'))
            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}"
               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
               style="background:var(--bg-surface-elevated);color:var(--text-primary);border:1px solid var(--border-medium)"
               title="Hapus filter status">
                <span>Status: {{ request('status') }}</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif

            <a href="{{ route('admin.anggota.index') }}"
               class="text-xs font-semibold py-1 px-1.5 underline transition-colors"
               style="color:var(--status-danger-fg)">
                Reset Semua
            </a>
        </div>
        @endif
    </div>

    {{-- Content List: Mobile Cards (< md) & Desktop Table (>= md) --}}
    <div>
        {{-- MOBILE VIEW: Card List --}}
        <div class="block md:hidden space-y-3.5">
            @forelse($anggotas as $anggota)
            <div class="surface-card rounded-2xl p-4 space-y-3 border transition-all"
                 style="border-color:var(--border-light)">
                
                {{-- Header Kartu: Inisial/Avatar, Nama (truncate), Status Badge --}}
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0 flex items-center justify-center font-bold text-xs"
                             style="background:var(--accent-subtle);color:var(--accent);border:1px solid var(--border-light)">
                            @if($anggota->photo)
                                <img src="{{ asset('storage/'.$anggota->photo) }}" class="w-full h-full object-cover" alt="{{ $anggota->name }}">
                            @else
                                @inisial($anggota->name ?? 'A')
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h2 class="font-semibold text-sm leading-snug truncate" style="color:var(--text-primary)">
                                {{ $anggota->name }}
                            </h2>
                            <p class="text-[11px] truncate mt-0.5" style="color:var(--text-muted)">
                                ID #{{ str_pad($anggota->id, 5, '0', STR_PAD_LEFT) }}
                                @if($anggota->joined_at) · @tanggal($anggota->joined_at) @endif
                            </p>
                        </div>
                    </div>

                    {{-- Badge Status --}}
                    @php $st = strtolower($anggota->status ?? ''); @endphp
                    @if($st === 'aktif')
                        <span class="badge badge-green text-[10px] shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full" style="background:var(--accent)"></span>
                            Aktif
                        </span>
                    @elseif($st === 'alumni')
                        <span class="badge text-[10px] shrink-0" style="background:rgba(59,130,246,0.1);color:#3b82f6;border:1px solid rgba(59,130,246,0.2)">Alumni</span>
                    @elseif($st === 'pending')
                        <span class="badge text-[10px] shrink-0" style="background:rgba(245,158,11,0.1);color:#d97706;border:1px solid rgba(245,158,11,0.2)">Pending</span>
                    @else
                        <span class="badge badge-muted text-[10px] shrink-0">Non-Aktif</span>
                    @endif
                </div>

                {{-- Detail Organisasi & Badan Otonom --}}
                <div class="p-2.5 rounded-xl text-xs space-y-1.5"
                     style="background:var(--bg-surface-elevated);border:1px solid var(--border-light)">
                    <div class="flex items-center justify-between gap-2">
                        <span style="color:var(--text-muted)">Jabatan:</span>
                        <span class="font-medium truncate" style="color:var(--text-primary)">
                            {{ $anggota->position ?: ($anggota->role ?: 'Anggota') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span style="color:var(--text-muted)">Badan Otonom:</span>
                        <span class="font-semibold" style="color:var(--accent)">
                            {{ $anggota->banom?->singkatan ?? $anggota->banom?->nama ?? 'NU Ranting Kalisari' }}
                        </span>
                    </div>
                    @if($anggota->region || $anggota->wilayah)
                    <div class="flex items-center justify-between gap-2">
                        <span style="color:var(--text-muted)">Wilayah:</span>
                        <span class="font-medium truncate" style="color:var(--text-secondary)">
                            {{ $anggota->region ?: $anggota->wilayah }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Action Buttons: Touch Target >= 44px --}}
                <div class="grid grid-cols-3 gap-2 pt-2 border-t" style="border-color:var(--border-light)">
                    <a href="{{ route('admin.anggota.print-single', $anggota->id) }}"
                       class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all min-h-[44px] border"
                       style="background:var(--bg-surface-elevated);color:var(--accent);border-color:var(--border-medium)"
                       title="Cetak kartu {{ $anggota->name }}"
                       aria-label="Cetak kartu {{ $anggota->name }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                        </svg>
                        <span>Kartu</span>
                    </a>

                    <a href="{{ route('admin.anggota.edit', $anggota->id) }}"
                       class="inline-flex items-center justify-center gap-1 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all min-h-[44px]"
                       style="background:var(--accent-subtle);color:var(--accent)"
                       aria-label="Edit Anggota {{ $anggota->name }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Edit</span>
                    </a>

                    <button type="button"
                            @click="deleteAction = '{{ route('admin.anggota.destroy', $anggota->id) }}'; deleteName = '{{ addslashes($anggota->name) }}'; showDeleteModal = true"
                            class="inline-flex items-center justify-center gap-1 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all min-h-[44px]"
                            style="background:rgba(239,68,68,0.08);color:#ef4444"
                            aria-label="Hapus Anggota {{ $anggota->name }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Hapus</span>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 px-4 text-center surface-card rounded-2xl border" style="border-color:var(--border-light)">
                <div class="flex flex-col items-center max-w-sm mx-auto">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4"
                         style="background:var(--bg-surface-elevated);color:var(--text-muted);border:1px solid var(--border-light)">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-base font-serif font-bold mb-1" style="color:var(--text-primary)">Tidak Ada Data Anggota</p>
                    <p class="text-xs mb-5 leading-relaxed" style="color:var(--text-muted)">
                        @if($hasActiveFilters)
                            Tidak ada anggota yang sesuai dengan kriteria filter saat ini. Coba reset filter.
                        @else
                            Belum ada anggota terdaftar di sistem. Mulai tambahkan anggota pertama Anda.
                        @endif
                    </p>
                    @if($hasActiveFilters)
                    <a href="{{ route('admin.anggota.index') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold transition-all min-h-[44px] border"
                       style="color:var(--text-secondary);border-color:var(--border-medium)">
                        Reset Filter
                    </a>
                    @else
                    <a href="{{ route('admin.anggota.create') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold text-white transition-all min-h-[44px]"
                       style="background:var(--accent)">
                        + Tambah Anggota Pertama
                    </a>
                    @endif
                </div>
            </div>
            @endforelse
        </div>

        {{-- DESKTOP VIEW: Table --}}
        <div class="hidden md:block surface-card rounded-2xl overflow-hidden border" style="border-color:var(--border-light)">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider"
                            style="background:var(--bg-surface-elevated);border-bottom:1px solid var(--border-light);color:var(--text-muted)">
                            <th class="px-6 py-4">Anggota</th>
                            <th class="px-6 py-4">Badan Otonom & Wilayah</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggotas as $anggota)
                        <tr class="transition-colors" style="border-bottom:1px solid var(--border-light)"
                            onmouseover="this.style.background='var(--bg-surface-elevated)'"
                            onmouseout="this.style.background=''">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center text-xs font-bold shrink-0"
                                         style="background:var(--accent-subtle);color:var(--accent)">
                                        @if($anggota->photo)
                                            <img src="{{ asset('storage/'.$anggota->photo) }}" class="w-full h-full object-cover" alt="{{ $anggota->name }}">
                                        @else
                                            @inisial($anggota->name ?? 'A')
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold leading-snug" style="color:var(--text-primary)">{{ $anggota->name }}</p>
                                        <p class="text-xs mt-0.5" style="color:var(--text-muted)">
                                            {{ $anggota->position ?: ($anggota->role ?: 'Anggota') }}
                                            @if($anggota->joined_at) · @tanggal($anggota->joined_at) @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <span class="badge" style="background:var(--bg-surface-elevated);color:var(--accent);border:1px solid var(--border-light)">
                                        {{ $anggota->banom?->singkatan ?? $anggota->banom?->nama ?? 'NU Kalisari' }}
                                    </span>
                                    @if($anggota->region || $anggota->wilayah)
                                    <p class="text-xs" style="color:var(--text-muted)">{{ $anggota->region ?: $anggota->wilayah }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php $st = strtolower($anggota->status ?? ''); @endphp
                                @if($st === 'aktif')
                                    <span class="badge badge-green">
                                        <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--accent)"></span> Aktif
                                    </span>
                                @elseif($st === 'alumni')
                                    <span class="badge" style="background:rgba(59,130,246,0.1);color:#3b82f6;border:1px solid rgba(59,130,246,0.2)">Alumni</span>
                                @elseif($st === 'pending')
                                    <span class="badge" style="background:rgba(245,158,11,0.1);color:#d97706;border:1px solid rgba(245,158,11,0.2)">Pending</span>
                                @else
                                    <span class="badge badge-muted">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.anggota.print-single', $anggota->id) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all min-h-[36px] inline-flex items-center gap-1.5 border"
                                       style="background:var(--bg-surface-elevated);color:var(--accent);border-color:var(--border-medium)"
                                       title="Cetak kartu {{ $anggota->name }}"
                                       aria-label="Cetak kartu {{ $anggota->name }}">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                        <span>Kartu</span>
                                    </a>
                                    <button type="button"
                                            @click="showQrModal = true; memberId = '{{ $anggota->id }}'; qrUrl = '{{ route('kartu.verify', $anggota->kartu_token ?: $anggota->id) }}'; qrName = '{{ addslashes($anggota->name) }}'; $nextTick(() => { document.getElementById('modal-qr-container').innerHTML = ''; new QRCode(document.getElementById('modal-qr-container'), { text: qrUrl, width: 200, height: 200, colorDark: '#008a5a', colorLight: '#ffffff' }); })"
                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all min-h-[36px]"
                                            style="background:var(--bg-surface-elevated);color:var(--text-secondary);border:1px solid var(--border-light)">
                                        QR
                                    </button>
                                    <a href="{{ route('admin.anggota.edit', $anggota->id) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all min-h-[36px] inline-flex items-center"
                                       style="background:var(--accent-subtle);color:var(--accent)">Edit</a>
                                    <button type="button"
                                            @click="deleteAction = '{{ route('admin.anggota.destroy', $anggota->id) }}'; deleteName = '{{ addslashes($anggota->name) }}'; showDeleteModal = true"
                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all min-h-[36px]"
                                            style="background:rgba(239,68,68,0.08);color:#ef4444">Hapus</button>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold mb-1" style="color:var(--text-primary)">Belum ada data anggota</p>
                                    <p class="text-xs mb-5" style="color:var(--text-muted)">Data anggota yang ditambahkan akan tampil di sini.</p>
                                    <a href="{{ route('admin.anggota.create') }}"
                                       class="px-4 py-2 rounded-xl text-xs font-semibold transition-all"
                                       style="background:var(--accent-subtle);color:var(--accent)">
                                        + Tambah Anggota Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Responsive Pagination ("Sebelumnya / Berikutnya" yang besar + "Halaman X dari Y") --}}
    @if(isset($anggotas) && method_exists($anggotas, 'hasPages') && $anggotas->hasPages())
    <div class="surface-card p-4 rounded-2xl border flex flex-col sm:flex-row items-center justify-between gap-3"
         style="border-color:var(--border-light)">
        <div class="text-xs font-medium order-2 sm:order-1" style="color:var(--text-muted)">
            Halaman <span class="font-bold" style="color:var(--text-primary)">{{ $anggotas->currentPage() }}</span> dari <span class="font-bold" style="color:var(--text-primary)">{{ $anggotas->lastPage() }}</span>
            <span class="hidden sm:inline">({{ $anggotas->total() }} total anggota)</span>
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-end order-1 sm:order-2">
            @if($anggotas->onFirstPage())
            <span class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold opacity-40 cursor-not-allowed min-h-[44px] flex-1 sm:flex-none border"
                  style="background:var(--bg-surface-elevated);color:var(--text-muted);border-color:var(--border-light)">
                ← Sebelumnya
            </span>
            @else
            <a href="{{ $anggotas->previousPageUrl() }}"
               class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px] flex-1 sm:flex-none border active:scale-95"
               style="background:var(--bg-surface);color:var(--text-primary);border-color:var(--border-medium)">
                ← Sebelumnya
            </a>
            @endif

            @if($anggotas->hasMorePages())
            <a href="{{ $anggotas->nextPageUrl() }}"
               class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px] flex-1 sm:flex-none border active:scale-95"
               style="background:var(--bg-surface);color:var(--text-primary);border-color:var(--border-medium)">
                Berikutnya →
            </a>
            @else
            <span class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold opacity-40 cursor-not-allowed min-h-[44px] flex-1 sm:flex-none border"
                  style="background:var(--bg-surface-elevated);color:var(--text-muted);border-color:var(--border-light)">
                Berikutnya →
            </span>
            @endif
        </div>
    </div>
    @endif

    {{-- Bottom Sheet Filter Mobile --}}
    <div x-show="showFilterSheet" class="fixed inset-0 z-50 flex items-end sm:hidden" x-cloak style="display: none;">
        <div class="fixed inset-0 backdrop-blur-sm transition-opacity" style="background:rgba(0,0,0,0.6)" @click="showFilterSheet = false" x-transition.opacity></div>
        <div class="relative w-full surface-card rounded-t-3xl p-6 space-y-5 shadow-2xl border-t z-10"
             style="border-color:var(--border-light); max-height:85vh; overflow-y:auto; padding-bottom: max(1.5rem, env(safe-area-inset-bottom))"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full">
            
            <div class="w-12 h-1.5 rounded-full mx-auto" style="background:var(--border-medium)"></div>
            
            <div class="flex items-center justify-between">
                <h3 class="font-serif font-bold text-lg" style="color:var(--text-primary)">Filter Data Anggota</h3>
                <button type="button" @click="showFilterSheet = false" class="p-2 rounded-full" style="color:var(--text-muted)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('admin.anggota.index') }}" method="GET" class="space-y-4">
                @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">Badan Otonom</label>
                    <select name="banom_id" class="w-full px-4 py-3 rounded-xl text-base font-medium outline-none transition-all min-h-[44px]"
                            style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                        <option value="">Semua Badan Otonom</option>
                        @foreach($banoms as $b)
                        <option value="{{ $b->id }}" {{ request('banom_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->singkatan ?? $b->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">Status Keanggotaan</label>
                    <select name="status" class="w-full px-4 py-3 rounded-xl text-base font-medium outline-none transition-all min-h-[44px]"
                            style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                        <option value="">Semua Status</option>
                        @foreach(['Aktif','Nonaktif','Alumni','Pending'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2 flex gap-3">
                    <a href="{{ route('admin.anggota.index') }}"
                       class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-xl text-sm font-semibold transition-all min-h-[44px] border"
                       style="color:var(--text-secondary);border-color:var(--border-medium)">
                        Reset
                    </a>
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-xl text-sm font-semibold text-white transition-all min-h-[44px]"
                            style="background:var(--accent)">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Touch-Friendly Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak style="display: none;">
        <div class="fixed inset-0 backdrop-blur-sm transition-opacity" style="background:rgba(0,0,0,0.6)" @click="showDeleteModal = false" x-transition.opacity></div>
        <div class="relative surface-card rounded-2xl p-6 max-w-sm w-full mx-auto space-y-4 shadow-2xl border text-center z-10"
             style="border-color:var(--border-light)"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <div class="w-12 h-12 rounded-full mx-auto flex items-center justify-center" style="background:rgba(239,68,68,0.12);color:#ef4444">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>

            <div>
                <h3 class="font-serif font-bold text-lg" style="color:var(--text-primary)">Hapus Data Anggota?</h3>
                <p class="text-xs mt-1 leading-relaxed" style="color:var(--text-muted)">
                    Anda akan menghapus data <span class="font-semibold" style="color:var(--text-primary)" x-text="deleteName"></span>. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <form :action="deleteAction" method="POST" class="flex gap-2.5 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false"
                        class="flex-1 py-3 px-4 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px] border"
                        style="color:var(--text-secondary);border-color:var(--border-medium);background:var(--bg-surface)">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-3 px-4 rounded-xl text-xs sm:text-sm font-semibold text-white transition-all min-h-[44px]"
                        style="background:#ef4444">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- QR Modal --}}
    <div x-show="showQrModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak style="display: none;">
        <div class="fixed inset-0 backdrop-blur-sm" style="background:rgba(0,0,0,0.6)" @click="showQrModal = false" x-transition.opacity></div>
        <div class="relative surface-card p-6 md:p-8 rounded-3xl shadow-2xl max-w-sm w-full mx-auto flex flex-col items-center text-center z-10" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90">
            
            <button type="button" @click="showQrModal = false" class="absolute top-4 right-4 p-2 rounded-full transition-colors"
                    style="color:var(--text-muted)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <h3 class="font-serif font-bold text-xl mb-1" style="color:var(--text-primary)" x-text="qrName"></h3>
            <p class="text-xs mb-6" style="color:var(--text-muted)">Scan QR ini untuk memverifikasi keanggotaan</p>
            
            <div class="p-4 rounded-2xl bg-white border flex items-center justify-center mb-6" style="border-color:var(--border-light); width: 232px; height: 232px;">
                <div id="modal-qr-container"></div>
            </div>
            
            <a :href="'{{ url('admin/anggota') }}/' + memberId + '/cetak-kartu'"
               class="w-full flex items-center justify-center py-3 rounded-xl text-sm font-bold text-white transition-all shadow-lg min-h-[44px]"
               style="background:var(--accent)">
                Cetak Kartu
            </a>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endsection

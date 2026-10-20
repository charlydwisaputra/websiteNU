@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-16"
     x-data="{
         showFilterSheet: false,
         showDeleteModal: false,
         deleteAction: '',
         deleteTitle: ''
     }">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 sm:p-7 rounded-2xl surface-card">
        <div>
            <span class="badge badge-green mb-2.5 inline-flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--accent)"></span>
                Modul Publikasi
            </span>
            <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">Data Berita</h1>
            <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">Kelola artikel, informasi, dan publikasi website NU Kalisari.</p>
        </div>
        <a href="{{ route('admin.berita.create') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-xs sm:text-sm font-semibold text-white transition-all min-h-[44px] shadow-sm active:scale-95 touch-manipulation"
           style="background:var(--accent)"
           onmouseover="this.style.background='var(--accent-hover)'"
           onmouseout="this.style.background='var(--accent)'">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Berita
        </a>
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

    {{-- Search & Filters --}}
    <div class="p-4 sm:p-6 rounded-2xl surface-card space-y-3">
        <form action="{{ route('admin.berita.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5">
            {{-- Search Bar --}}
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" style="color:var(--text-muted)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul berita..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
            </div>

            {{-- Desktop Filters --}}
            <div class="hidden sm:flex items-center gap-2.5">
                <select name="category"
                        class="form-select-custom px-4 py-2.5 rounded-xl text-sm font-medium outline-none transition-all min-h-[44px] w-44"
                        style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                    <option value="">Semua Kategori</option>
                    @foreach(['Kegiatan','Pengumuman','Organisasi','Sosial'] as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <select name="status"
                        class="form-select-custom px-4 py-2.5 rounded-xl text-sm font-medium outline-none transition-all min-h-[44px] w-40"
                        style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft"     {{ request('status') == 'draft'      ? 'selected' : '' }}>Draft</option>
                </select>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all min-h-[44px]"
                        style="background:var(--text-primary)"
                        onmouseover="this.style.opacity='.88'"
                        onmouseout="this.style.opacity='1'">
                    Cari
                </button>
            </div>

            {{-- Mobile Action Buttons --}}
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
                        $activeFiltersCount = (request('category') ? 1 : 0) + (request('status') ? 1 : 0);
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
            $hasActiveFilters = request('search') || request('category') || request('status');
        @endphp
        @if($hasActiveFilters)
        <div class="flex items-center gap-2 flex-wrap pt-2 border-t" style="border-color:var(--border-light)">
            <span class="text-[11px] font-medium" style="color:var(--text-muted)">Filter aktif:</span>

            @if(request('search'))
            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
               style="background:var(--bg-surface-elevated);color:var(--text-primary);border:1px solid var(--border-medium)"
               title="Hapus filter pencarian">
                <span>"{{ request('search') }}"</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif

            @if(request('category'))
            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}"
               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
               style="background:var(--bg-surface-elevated);color:var(--text-primary);border:1px solid var(--border-medium)"
               title="Hapus filter kategori">
                <span>Kategori: {{ request('category') }}</span>
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

            <a href="{{ route('admin.berita.index') }}"
               class="text-xs font-semibold py-1 px-1.5 underline transition-colors"
               style="color:var(--status-danger-fg)">
                Reset Semua
            </a>
        </div>
        @endif
    </div>

    {{-- Content List --}}
    <div>
        {{-- MOBILE VIEW: Card List (< md) --}}
        <div class="block md:hidden space-y-3.5">
            @forelse($beritas as $berita)
            <div class="p-4 rounded-2xl surface-card space-y-3 border transition-all"
                 style="border-color:var(--border-light)">
                
                {{-- Card Meta: Kategori & Status --}}
                <div class="flex items-center justify-between gap-2">
                    <span class="badge" style="background:var(--bg-surface-elevated);color:var(--text-secondary);border:1px solid var(--border-light)">
                        {{ $berita->category ?? 'Kegiatan' }}
                    </span>
                    @if($berita->status === 'published')
                    <span class="badge badge-green text-[10px] shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:var(--accent)"></span>
                        Published
                    </span>
                    @else
                    <span class="badge badge-muted text-[10px] shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:var(--text-muted)"></span>
                        Draft
                    </span>
                    @endif
                </div>

                {{-- Card Body: Thumbnail & Judul (maks 2 baris) & Tanggal --}}
                <div class="flex gap-3">
                    @if($berita->image)
                    <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0"
                         style="background:var(--bg-surface-elevated);border:1px solid var(--border-light)">
                        <img src="{{ asset('storage/'.$berita->image) }}" class="w-full h-full object-cover" alt="{{ $berita->title }}">
                    </div>
                    @else
                    <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0 flex items-center justify-center font-bold text-lg"
                         style="background:var(--accent-subtle);color:var(--accent);border:1px solid var(--border-light)">
                        NU
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h2 class="font-serif font-bold text-sm sm:text-base line-clamp-2 leading-snug" style="color:var(--text-primary)">
                            {{ $berita->title }}
                        </h2>
                        <p class="text-xs font-medium mt-2 flex items-center gap-1.5" style="color:var(--text-muted)">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            @tanggal($berita->created_at)
                        </p>
                    </div>
                </div>

                {{-- Action Buttons: Touch Target >= 44px --}}
                <div class="pt-2 flex items-center gap-2 border-t" style="border-color:var(--border-light)">
                    @if($berita->status === 'published')
                    <a href="{{ route('berita.show', $berita->id) }}" target="_blank"
                       class="inline-flex items-center justify-center p-2.5 rounded-xl text-xs font-semibold transition-all min-h-[44px] min-w-[44px] border"
                       style="color:var(--text-secondary);border-color:var(--border-medium);background:var(--bg-surface)"
                       title="Lihat Berita Publik"
                       aria-label="Lihat Berita {{ $berita->title }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                    @endif

                    <a href="{{ route('admin.berita.edit', $berita->id) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px]"
                       style="background:var(--accent-subtle);color:var(--accent)"
                       aria-label="Edit Berita {{ $berita->title }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>

                    <button type="button"
                            @click="deleteAction = '{{ route('admin.berita.destroy', $berita->id) }}'; deleteTitle = '{{ addslashes($berita->title) }}'; showDeleteModal = true"
                            class="inline-flex items-center justify-center gap-1 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px]"
                            style="background:rgba(239,68,68,0.08);color:#ef4444"
                            aria-label="Hapus Berita {{ $berita->title }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
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
                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="text-base font-serif font-bold mb-1" style="color:var(--text-primary)">Tidak Ada Data Berita</p>
                    <p class="text-xs mb-5 leading-relaxed" style="color:var(--text-muted)">
                        @if($hasActiveFilters)
                            Tidak ada artikel berita yang cocok dengan kriteria filter saat ini.
                        @else
                            Belum ada artikel berita yang dipublikasikan. Mulai tulis warta pertama.
                        @endif
                    </p>
                    @if($hasActiveFilters)
                    <a href="{{ route('admin.berita.index') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold transition-all min-h-[44px] border"
                       style="color:var(--text-secondary);border-color:var(--border-medium)">
                        Reset Filter
                    </a>
                    @else
                    <a href="{{ route('admin.berita.create') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold text-white transition-all min-h-[44px]"
                       style="background:var(--accent)">
                        + Tambah Berita Pertama
                    </a>
                    @endif
                </div>
            </div>
            @endforelse
        </div>

        {{-- DESKTOP VIEW: Table (>= md) --}}
        <div class="hidden md:block surface-card rounded-2xl overflow-hidden border" style="border-color:var(--border-light)">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider"
                            style="background:var(--bg-surface-elevated);border-bottom:1px solid var(--border-light);color:var(--text-muted)">
                            <th class="px-6 py-4">Berita</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beritas as $berita)
                        <tr class="transition-colors" style="border-bottom:1px solid var(--border-light)"
                            onmouseover="this.style.background='var(--bg-surface-elevated)'"
                            onmouseout="this.style.background=''">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-11 rounded-xl overflow-hidden shrink-0 flex items-center justify-center text-xs font-bold"
                                         style="background:var(--bg-surface-elevated);border:1px solid var(--border-light);color:var(--text-muted)">
                                        @if($berita->image)
                                            <img src="{{ asset('storage/'.$berita->image) }}" class="w-full h-full object-cover" alt="{{ $berita->title }}">
                                        @else
                                            NU
                                        @endif
                                    </div>
                                    <p class="text-sm font-semibold line-clamp-2 leading-snug" style="color:var(--text-primary)">
                                        {{ $berita->title }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge" style="background:var(--bg-surface-elevated);color:var(--text-secondary);border:1px solid var(--border-light)">
                                    {{ $berita->category ?? 'Kegiatan' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($berita->status === 'published')
                                <span class="badge badge-green">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background:var(--accent)"></span>
                                    Published
                                </span>
                                @else
                                <span class="badge badge-muted">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background:var(--text-muted)"></span>
                                    Draft
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-medium whitespace-nowrap" style="color:var(--text-muted)">
                                @tanggal($berita->created_at)
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.berita.edit', $berita->id) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all min-h-[36px] inline-flex items-center"
                                       style="background:var(--accent-subtle);color:var(--accent)">Edit</a>
                                    <button type="button"
                                            @click="deleteAction = '{{ route('admin.berita.destroy', $berita->id) }}'; deleteTitle = '{{ addslashes($berita->title) }}'; showDeleteModal = true"
                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all min-h-[36px] inline-flex items-center"
                                            style="background:rgba(239,68,68,0.08);color:#ef4444">Hapus</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4"
                                         style="background:var(--bg-surface-elevated);color:var(--text-muted);border:1px solid var(--border-light)">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold mb-1" style="color:var(--text-primary)">Belum ada data berita</p>
                                    <p class="text-xs mb-5" style="color:var(--text-muted)">Berita yang ditambahkan akan tampil di sini.</p>
                                    <a href="{{ route('admin.berita.create') }}"
                                       class="px-4 py-2 rounded-xl text-xs font-semibold transition-all"
                                       style="background:var(--accent-subtle);color:var(--accent)">
                                        + Tambah Berita Pertama
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

    {{-- Responsive Pagination --}}
    @if(isset($beritas) && method_exists($beritas, 'hasPages') && $beritas->hasPages())
    <div class="surface-card p-4 rounded-2xl border flex flex-col sm:flex-row items-center justify-between gap-3"
         style="border-color:var(--border-light)">
        <div class="text-xs font-medium order-2 sm:order-1" style="color:var(--text-muted)">
            Halaman <span class="font-bold" style="color:var(--text-primary)">{{ $beritas->currentPage() }}</span> dari <span class="font-bold" style="color:var(--text-primary)">{{ $beritas->lastPage() }}</span>
            <span class="hidden sm:inline">({{ $beritas->total() }} total artikel)</span>
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-end order-1 sm:order-2">
            @if($beritas->onFirstPage())
            <span class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold opacity-40 cursor-not-allowed min-h-[44px] flex-1 sm:flex-none border"
                  style="background:var(--bg-surface-elevated);color:var(--text-muted);border-color:var(--border-light)">
                ← Sebelumnya
            </span>
            @else
            <a href="{{ $beritas->previousPageUrl() }}"
               class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px] flex-1 sm:flex-none border active:scale-95"
               style="background:var(--bg-surface);color:var(--text-primary);border-color:var(--border-medium)">
                ← Sebelumnya
            </a>
            @endif

            @if($beritas->hasMorePages())
            <a href="{{ $beritas->nextPageUrl() }}"
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
                <h3 class="font-serif font-bold text-lg" style="color:var(--text-primary)">Filter Berita</h3>
                <button type="button" @click="showFilterSheet = false" class="p-2 rounded-full" style="color:var(--text-muted)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('admin.berita.index') }}" method="GET" class="space-y-4">
                @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">Kategori</label>
                    <select name="category" class="w-full px-4 py-3 rounded-xl text-base font-medium outline-none transition-all min-h-[44px]"
                            style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                        <option value="">Semua Kategori</option>
                        @foreach(['Kegiatan','Pengumuman','Organisasi','Sosial'] as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider mb-2" style="color:var(--text-muted)">Status</label>
                    <select name="status" class="w-full px-4 py-3 rounded-xl text-base font-medium outline-none transition-all min-h-[44px]"
                            style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft"     {{ request('status') == 'draft'      ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="pt-2 flex gap-3">
                    <a href="{{ route('admin.berita.index') }}"
                       class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-xl text-sm font-semibold transition-all min-h-[44px] border"
                       style="color:var(--text-secondary);border-color:var(--border-medium)">
                        Reset
                    </a>
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-xl text-sm font-semibold text-white transition-all min-h-[44px]"
                            style="background:var(--accent)">
                        Terapkan
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
                <h3 class="font-serif font-bold text-lg" style="color:var(--text-primary)">Hapus Berita Ini?</h3>
                <p class="text-xs mt-1 leading-relaxed" style="color:var(--text-muted)">
                    Anda akan menghapus artikel <span class="font-semibold" style="color:var(--text-primary)" x-text="deleteTitle"></span>. Data tidak dapat dipulihkan.
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
</div>
@endsection

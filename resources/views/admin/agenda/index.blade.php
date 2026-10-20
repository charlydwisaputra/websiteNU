@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 sm:p-7 rounded-2xl surface-card">
        <div>
            <span class="badge badge-green mb-2.5 inline-flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--accent)"></span>
                Modul Kegiatan
            </span>
            <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">Agenda Kegiatan</h1>
            <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">Jadwal pengajian, rapat, dan kegiatan warga NU Kalisari.</p>
        </div>
        <a href="{{ route('admin.agenda.create') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold text-white transition-all min-h-[44px] shadow-sm active:scale-95"
           style="background:var(--accent)"
           onmouseover="this.style.background='var(--accent-hover)'"
           onmouseout="this.style.background='var(--accent)'">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Agenda
        </a>
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

    {{-- Filters & Search --}}
    <div class="p-4 sm:p-6 rounded-2xl surface-card">
        <form action="{{ route('admin.agenda.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" style="color:var(--text-muted)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul atau lokasi agenda..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
            </div>
            <select name="filter"
                    class="form-select-custom px-4 py-2.5 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px] sm:w-48"
                    style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                <option value="">Semua Waktu</option>
                <option value="upcoming" {{ request('filter') === 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                <option value="past" {{ request('filter') === 'past' ? 'selected' : '' }}>Selesai / Lewat</option>
            </select>
            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition-all min-h-[44px]"
                        style="background:var(--text-primary)"
                        onmouseover="this.style.opacity='.88'"
                        onmouseout="this.style.opacity='1'">
                    Cari
                </button>
                @if(request()->filled('search') || request()->filled('filter'))
                <a href="{{ route('admin.agenda.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold transition-all min-h-[44px] border"
                   style="color:var(--text-muted);border-color:var(--border-medium)"
                   title="Reset Filter">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Content List: Mobile Cards + Desktop Table --}}
    <div class="space-y-4">
        {{-- MOBILE VIEW: Card List (lebar 360px - 430px optimasi) --}}
        <div class="block md:hidden space-y-3.5">
            @forelse($agendas as $agenda)
            <div class="p-4 rounded-2xl surface-card space-y-3 border transition-all"
                 style="border-color:var(--border-light)">
                {{-- Card Header: Tanggal & Status --}}
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg"
                          style="background:var(--bg-surface-elevated);color:var(--text-secondary)">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $agenda->tanggal_mulai ? $agenda->tanggal_mulai->isoFormat('D MMMM Y, HH:mm') : '-' }} WIB
                    </span>
                    @if($agenda->tanggal_mulai && $agenda->tanggal_mulai->isFuture())
                    <span class="badge badge-green text-[11px] shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:var(--accent)"></span>
                        Terdekat
                    </span>
                    @else
                    <span class="badge badge-muted text-[11px] shrink-0">
                        Selesai
                    </span>
                    @endif
                </div>

                {{-- Judul & Lokasi --}}
                <div>
                    <h2 class="font-serif font-bold text-base leading-snug break-words" style="color:var(--text-primary)">
                        {{ $agenda->judul }}
                    </h2>
                    <p class="text-xs font-medium flex items-center gap-1.5 mt-1.5" style="color:var(--text-muted)">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--accent)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="break-words">{{ $agenda->lokasi }}</span>
                    </p>
                </div>

                @if($agenda->deskripsi)
                <p class="text-xs line-clamp-2 leading-relaxed" style="color:var(--text-secondary)">
                    {{ $agenda->deskripsi }}
                </p>
                @endif

                {{-- Mobile Action Buttons (Min 44px touch targets) --}}
                <div class="pt-2 flex items-center gap-2 border-t" style="border-color:var(--border-light)">
                    <a href="{{ route('admin.agenda.edit', $agenda->id) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all min-h-[44px]"
                       style="background:var(--accent-subtle);color:var(--accent)"
                       onmouseover="this.style.background='var(--accent-subtle-hover)'"
                       onmouseout="this.style.background='var(--accent-subtle)'">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('admin.agenda.destroy', $agenda->id) }}" method="POST"
                          class="flex-1"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all min-h-[44px]"
                                style="background:rgba(239,68,68,0.08);color:#ef4444"
                                onmouseover="this.style.background='rgba(239,68,68,0.16)'"
                                onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-8 text-center surface-card rounded-2xl border" style="border-color:var(--border-light)">
                <div class="w-12 h-12 mx-auto rounded-2xl flex items-center justify-center mb-3"
                     style="background:var(--bg-surface-elevated);color:var(--text-muted)">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold mb-1" style="color:var(--text-primary)">Belum ada agenda kegiatan</p>
                <p class="text-xs mb-4" style="color:var(--text-muted)">Jadwalkan kegiatan baru untuk warga NU Kalisari.</p>
                <a href="{{ route('admin.agenda.create') }}"
                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-semibold text-white min-h-[44px]"
                   style="background:var(--accent)">
                    + Tambah Agenda
                </a>
            </div>
            @endforelse
        </div>

        {{-- DESKTOP VIEW: Table (Hidden on mobile) --}}
        <div class="hidden md:block surface-card rounded-2xl overflow-hidden border" style="border-color:var(--border-light)">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[11px] font-bold uppercase tracking-wider"
                        style="background:var(--bg-surface-elevated);border-bottom:1px solid var(--border-light);color:var(--text-muted)">
                        <th class="px-6 py-4">Waktu & Tanggal</th>
                        <th class="px-6 py-4">Agenda & Lokasi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agendas as $agenda)
                    <tr class="transition-colors" style="border-bottom:1px solid var(--border-light)"
                        onmouseover="this.style.background='var(--bg-surface-elevated)'"
                        onmouseout="this.style.background=''">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-semibold" style="color:var(--text-primary)">
                                {{ $agenda->tanggal_mulai ? $agenda->tanggal_mulai->isoFormat('dddd, D MMMM Y') : '-' }}
                            </p>
                            <p class="text-xs font-medium mt-0.5" style="color:var(--text-muted)">
                                {{ $agenda->tanggal_mulai ? $agenda->tanggal_mulai->format('H:i') : '--:--' }} WIB
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold font-serif leading-snug" style="color:var(--text-primary)">
                                {{ $agenda->judul }}
                            </p>
                            <p class="text-xs flex items-center gap-1.5 mt-1" style="color:var(--text-muted)">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--accent)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $agenda->lokasi }}</span>
                            </p>
                            @if($agenda->deskripsi)
                            <p class="text-xs line-clamp-1 mt-1" style="color:var(--text-secondary)">
                                {{ $agenda->deskripsi }}
                            </p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($agenda->tanggal_mulai && $agenda->tanggal_mulai->isFuture())
                            <span class="badge badge-green">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:var(--accent)"></span>
                                Akan Datang
                            </span>
                            @else
                            <span class="badge badge-muted">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:var(--text-muted)"></span>
                                Selesai
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.agenda.edit', $agenda->id) }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all min-h-[36px] inline-flex items-center"
                                   style="background:var(--accent-subtle);color:var(--accent)"
                                   onmouseover="this.style.background='var(--accent-subtle-hover)'"
                                   onmouseout="this.style.background='var(--accent-subtle)'">
                                    Edit
                                </a>
                                <form action="{{ route('admin.agenda.destroy', $agenda->id) }}" method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda {{ $agenda->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all min-h-[36px] inline-flex items-center"
                                            style="background:rgba(239,68,68,0.08);color:#ef4444"
                                            onmouseover="this.style.background='rgba(239,68,68,0.16)'"
                                            onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                                     style="background:var(--bg-surface-elevated);color:var(--text-muted);border:1px solid var(--border-light)">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold mb-1" style="color:var(--text-primary)">Belum ada agenda kegiatan</p>
                                <p class="text-xs mb-4" style="color:var(--text-muted)">Agenda yang ditambahkan akan tampil di kalender website.</p>
                                <a href="{{ route('admin.agenda.create') }}"
                                   class="px-4 py-2 rounded-xl text-xs font-semibold transition-all"
                                   style="background:var(--accent-subtle);color:var(--accent)">
                                    + Tambah Agenda Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($agendas->hasPages())
        <div class="surface-card rounded-2xl p-4 border" style="border-color:var(--border-light)">
            {{ $agendas->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

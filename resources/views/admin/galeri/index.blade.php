@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 p-6 md:p-7 rounded-2xl surface-card">
        <div>
            <span class="badge badge-green mb-3">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--accent)"></span>
                Modul Galeri & Dokumentasi
            </span>
            <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">Data Galeri Foto</h1>
            <p class="text-sm mt-1" style="color:var(--text-muted)">Kelola dokumentasi foto kegiatan organisasi berdasarkan kategori.</p>
        </div>
        <a href="{{ route('admin.galeri.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all"
           style="background:var(--accent)"
           onmouseover="this.style.background='var(--accent-hover)'"
           onmouseout="this.style.background='var(--accent)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Foto
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-5 py-4 rounded-xl text-sm font-medium"
         style="background:var(--accent-subtle);color:var(--accent);border:1px solid rgba(0,138,90,0.15)">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Filters --}}
    <div class="p-5 md:p-6 rounded-2xl surface-card">
        <form action="{{ route('admin.galeri.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none" style="color:var(--text-muted)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari keterangan foto..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm font-medium outline-none transition-all"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
            </div>
            <select name="category"
                    class="form-select-custom px-4 py-2.5 rounded-xl text-sm font-medium outline-none transition-all sm:w-52"
                    style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                <option value="">Semua Kategori Banom</option>
                @foreach(['Muslimat','Fatayat','GP Ansor','IPNU','IPPNU','PMII'] as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all"
                    style="background:var(--text-primary)"
                    onmouseover="this.style.opacity='.88'"
                    onmouseout="this.style.opacity='1'">
                Cari
            </button>
            @if(request('search') || request('category'))
            <a href="{{ route('admin.galeri.index') }}"
               class="px-4 py-2.5 rounded-xl text-sm font-semibold text-center transition-all"
               style="background:var(--bg-surface-elevated);color:var(--text-secondary);border:1px solid var(--border-light)">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Photo grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($galeris as $galeri)
        <div class="surface-card rounded-2xl overflow-hidden flex flex-col group hover-lift">
            <div class="relative aspect-video overflow-hidden img-zoom-wrap"
                 style="background:var(--bg-surface-elevated)">
                <img src="{{ asset('storage/'.$galeri->photo) }}" alt="{{ $galeri->title }}"
                     class="w-full h-full object-cover">
                @if($galeri->category)
                <div class="absolute top-2.5 left-2.5">
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg text-white"
                          style="background:var(--accent)">
                        {{ $galeri->category }}
                    </span>
                </div>
                @endif
            </div>
            <div class="p-4 flex-1 flex flex-col justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold line-clamp-2" style="color:var(--text-primary)">
                        {{ $galeri->title ?: 'Tanpa Keterangan' }}
                    </p>
                    @if($galeri->created_at)
                    <p class="text-xs mt-1" style="color:var(--text-muted)">{{ $galeri->created_at->format('d M Y') }}</p>
                    @endif
                </div>
                <div class="flex items-center justify-end gap-2 pt-3" style="border-top:1px solid var(--border-light)">
                    <a href="{{ route('admin.galeri.edit', $galeri->id) }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                       style="background:var(--accent-subtle);color:var(--accent)"
                       onmouseover="this.style.background='var(--accent-subtle-hover)'"
                       onmouseout="this.style.background='var(--accent-subtle)'">Edit</a>
                    <form action="{{ route('admin.galeri.destroy', $galeri->id) }}" method="POST"
                          class="inline" onsubmit="return confirm('Yakin hapus foto ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                                style="background:rgba(239,68,68,0.08);color:#ef4444"
                                onmouseover="this.style.background='rgba(239,68,68,0.14)'"
                                onmouseout="this.style.background='rgba(239,68,68,0.08)'">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center surface-card rounded-2xl">
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4"
                     style="background:var(--bg-surface-elevated);color:var(--text-muted);border:1px solid var(--border-light)">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold mb-1" style="color:var(--text-primary)">Belum ada foto galeri</p>
                <p class="text-xs mb-5" style="color:var(--text-muted)">Foto kegiatan yang ditambahkan akan tampil di sini.</p>
                <a href="{{ route('admin.galeri.create') }}"
                   class="px-4 py-2 rounded-xl text-xs font-semibold transition-all"
                   style="background:var(--accent-subtle);color:var(--accent)">
                    + Tambah Foto Pertama
                </a>
            </div>
        </div>
        @endforelse
    </div>

    @if(isset($galeris) && method_exists($galeris,'hasPages') && $galeris->hasPages())
    <div class="surface-card px-6 py-4 rounded-2xl">
        {{ $galeris->links() }}
    </div>
    @endif
</div>
@endsection

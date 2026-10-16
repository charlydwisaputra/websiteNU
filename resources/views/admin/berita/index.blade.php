@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 p-6 md:p-7 rounded-2xl surface-card">
        <div>
            <span class="badge badge-green mb-3">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--accent)"></span>
                Modul Publikasi
            </span>
            <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">Data Berita</h1>
            <p class="text-sm mt-1" style="color:var(--text-muted)">Kelola artikel, informasi, dan publikasi website NU Kalisari.</p>
        </div>
        <a href="{{ route('admin.berita.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all"
           style="background:var(--accent)"
           onmouseover="this.style.background='var(--accent-hover)'"
           onmouseout="this.style.background='var(--accent)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Berita
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
        <form action="{{ route('admin.berita.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none" style="color:var(--text-muted)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul berita..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm font-medium outline-none transition-all"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
            </div>
            <select name="category"
                    class="form-select-custom px-4 py-2.5 rounded-xl text-sm font-medium outline-none transition-all sm:w-44"
                    style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                <option value="">Semua Kategori</option>
                @foreach(['Kegiatan','Pengumuman','Organisasi','Sosial'] as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <select name="status"
                    class="form-select-custom px-4 py-2.5 rounded-xl text-sm font-medium outline-none transition-all sm:w-40"
                    style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft"     {{ request('status') == 'draft'      ? 'selected' : '' }}>Draft</option>
            </select>
            <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all"
                    style="background:var(--text-primary)"
                    onmouseover="this.style.opacity='.88'"
                    onmouseout="this.style.opacity='1'">
                Cari
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="surface-card rounded-2xl overflow-hidden">
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
                                <div class="w-16 h-11 rounded-xl overflow-hidden shrink-0"
                                     style="background:var(--bg-surface-elevated);border:1px solid var(--border-light)">
                                    @if($berita->image)
                                        <img src="{{ asset('storage/'.$berita->image) }}" class="w-full h-full object-cover">
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
                        <td class="px-6 py-4">
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
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                                   style="background:var(--accent-subtle);color:var(--accent)"
                                   onmouseover="this.style.background='var(--accent-subtle-hover)'"
                                   onmouseout="this.style.background='var(--accent-subtle)'">Edit</a>
                                <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST"
                                      class="inline" onsubmit="return confirm('Yakin hapus berita ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                                            style="background:rgba(239,68,68,0.08);color:#ef4444"
                                            onmouseover="this.style.background='rgba(239,68,68,0.14)'"
                                            onmouseout="this.style.background='rgba(239,68,68,0.08)'">Hapus</button>
                                </form>
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
                                              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold mb-1" style="color:var(--text-primary)">Belum ada berita</p>
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
        @if($beritas->hasPages())
        <div class="px-6 py-4" style="border-top:1px solid var(--border-light)">
            {{ $beritas->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

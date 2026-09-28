@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 pb-12">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-[#087A4B] text-xs font-bold mb-3 border border-emerald-100/60">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Modul Publikasi
                </div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Data Berita</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola artikel, informasi, dan publikasi website DIGDAYA NU.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-[#087A4B] hover:bg-emerald-700 text-white font-bold text-xs rounded-2xl shadow-md shadow-emerald-500/20 transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Berita
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200/80 text-[#087A4B] px-6 py-4 rounded-3xl shadow-sm flex items-center gap-3">
                <div class="w-8 h-8 rounded-2xl bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#087A4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white p-5 md:p-6 rounded-3xl border border-slate-100 shadow-sm">
            <form action="{{ route('admin.berita.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul berita..." class="w-full pl-10 pr-4 py-3 bg-slate-50/60 border border-slate-200/80 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-emerald-500/20 rounded-2xl text-sm transition-all text-slate-800 outline-none placeholder:text-slate-400 font-bold">
                </div>
                
                <select name="category" class="form-select-modern px-4 py-3 bg-slate-50/60 border border-slate-200/80 focus:bg-white focus:border-[#087A4B] focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-bold text-slate-700 sm:w-44 cursor-pointer outline-none transition-all">
                    <option value="">Semua Kategori</option>
                    <option value="Kegiatan" {{ request('category') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                    <option value="Pengumuman" {{ request('category') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                    <option value="Organisasi" {{ request('category') == 'Organisasi' ? 'selected' : '' }}>Organisasi</option>
                    <option value="Sosial" {{ request('category') == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                </select>
                
                <select name="status" class="form-select-modern px-4 py-3 bg-slate-50/60 border border-slate-200/80 focus:bg-white focus:border-[#087A4B] focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-bold text-slate-700 sm:w-44 cursor-pointer outline-none transition-all">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                
                <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-2xl transition-all shadow-sm">
                    Cari Data
                </button>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/60 border-b border-slate-100 text-[11px] text-slate-400 uppercase tracking-wider font-black">
                            <th class="px-6 py-4">Judul Berita</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($beritas as $berita)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-12 rounded-2xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200/60 shadow-sm">
                                            @if($berita->image)
                                                <img src="{{ asset('storage/'.$berita->image) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-[10px] font-bold text-slate-400 uppercase">No Img</div>
                                            @endif
                                        </div>
                                        <p class="text-sm font-bold text-slate-800 line-clamp-2 leading-snug">{{ $berita->title }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200/60">
                                        {{ $berita->category ?? 'Kegiatan' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($berita->status === 'published')
                                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-[11px] font-bold bg-emerald-50 text-[#087A4B] border border-emerald-100/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-500 whitespace-nowrap">
                                    {{ $berita->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.berita.edit', $berita->id) }}" class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-[#087A4B] font-bold text-xs rounded-xl transition">Edit</a>
                                        <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus berita ini secara permanen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3.5 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs rounded-xl transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="inline-flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-16 h-16 bg-slate-50 rounded-3xl flex items-center justify-center mb-4 border border-slate-100">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-700 mb-1">Belum ada data berita yang ditambahkan.</p>
                                        <p class="text-xs text-slate-400 max-w-xs mb-5">Data berita yang Anda buat akan muncul di tabel ini.</p>
                                        <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-[#087A4B] font-bold text-xs rounded-xl transition">
                                            + Buat Berita Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($beritas->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $beritas->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
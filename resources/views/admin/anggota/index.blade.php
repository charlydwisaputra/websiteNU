@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 pb-12">
        
        <!-- Header Banner -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-[#087A4B] text-xs font-bold mb-3 border border-emerald-100/60">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Modul Keanggotaan
                </div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Data Anggota</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola data seluruh anggota organisasi berdasarkan Badan Otonom.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.anggota.print-cards') }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-3 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-2xl shadow-sm border border-slate-200 transition-all active:scale-95">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Kartu
                </a>
                <a href="{{ route('admin.anggota.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-[#087A4B] hover:bg-emerald-700 text-white font-bold text-xs rounded-2xl shadow-md shadow-emerald-500/20 transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Anggota
                </a>
            </div>
        </div>

        <!-- Alert Success -->
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

        <!-- Filter & Search Card -->
        <div class="bg-white p-5 md:p-6 rounded-3xl border border-slate-100 shadow-sm">
            <form action="{{ route('admin.anggota.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, role (banom), atau wilayah..." class="w-full pl-10 pr-4 py-3 bg-slate-50/60 border border-slate-200/80 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-emerald-500/20 rounded-2xl text-sm transition-all text-slate-800 outline-none placeholder:text-slate-400 font-bold">
                </div>
                
                <select name="status" class="form-select-modern px-4 py-3 bg-slate-50/60 border border-slate-200/80 focus:bg-white focus:border-[#087A4B] focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-bold text-slate-700 sm:w-48 cursor-pointer outline-none transition-all">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                    <option value="Alumni" {{ request('status') == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                </select>
                
                <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-2xl transition-all shadow-sm">
                    Terapkan
                </button>
            </form>
        </div>

        <!-- Table Content Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/60 border-b border-slate-100 text-[11px] text-slate-400 uppercase tracking-wider font-black">
                            <th class="px-6 py-4">Nama Anggota</th>
                            <th class="px-6 py-4">Role & Wilayah</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($anggotas as $anggota)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <!-- Kolom Nama & Tanggal Bergabung -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-[#087A4B] font-black text-xs flex items-center justify-center border border-emerald-100/60 shrink-0 shadow-sm">
                                            {{ strtoupper(substr($anggota->name ?? 'A', 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800 leading-snug">{{ $anggota->name }}</p>
                                            @if($anggota->joined_at)
                                                <p class="text-xs text-slate-400 font-medium flex items-center gap-1 mt-0.5">
                                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    Gabung: {{ \Carbon\Carbon::parse($anggota->joined_at)->format('d M Y') }}
                                                </p>
                                            @else
                                                <p class="text-xs text-slate-400 font-medium mt-0.5">Anggota Resmi</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Kolom Role & Wilayah yang Rapi -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1.5">
                                        @if($anggota->role)
                                            <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-[#087A4B] rounded-lg text-xs font-bold border border-emerald-100/60 shadow-sm">
                                                {{ $anggota->role }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-bold border border-amber-200/60 shadow-sm">
                                                Belum Diatur
                                            </span>
                                        @endif
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-semibold pl-0.5">
                                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span>{{ $anggota->wilayah ?: 'Belum diisi' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status Keanggotaan -->
                                <td class="px-6 py-4">
                                    @if(strtolower($anggota->status ?? '') === 'aktif')
                                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-[11px] font-bold bg-emerald-50 text-[#087A4B] border border-emerald-100/60 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif
                                        </span>
                                    @elseif(strtolower($anggota->status ?? '') === 'alumni')
                                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-100/60 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Alumni
                                        </span>
                                    @elseif(strtolower($anggota->status ?? '') === 'pending')
                                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-100/60 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200/60 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Non-Aktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.anggota.print-cards', ['id' => $anggota->id]) }}" target="_blank" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition shadow-sm inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Cetak QR
                                        </a>
                                        <a href="{{ route('admin.anggota.edit', $anggota->id) }}" class="px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-[#087A4B] font-bold text-xs rounded-xl transition shadow-sm">Edit</a>
                                        <form action="{{ route('admin.anggota.destroy', $anggota->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data anggota ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs rounded-xl transition shadow-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <!-- State Kosong -->
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="inline-flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-16 h-16 bg-slate-50 rounded-3xl flex items-center justify-center mb-4 border border-slate-100 shadow-sm">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-700 mb-1">Belum ada data anggota.</p>
                                        <p class="text-xs text-slate-400 max-w-xs mb-5">Data anggota yang Anda tambahkan akan muncul di sini.</p>
                                        <a href="{{ route('admin.anggota.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-[#087A4B] font-bold text-xs rounded-xl transition shadow-sm">
                                            + Tambah Anggota Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($anggotas) && method_exists($anggotas, 'hasPages') && $anggotas->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $anggotas->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
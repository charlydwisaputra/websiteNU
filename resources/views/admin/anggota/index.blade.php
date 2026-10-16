@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12" x-data="{ showQrModal: false, qrUrl: '', qrName: '', memberId: '' }">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 p-6 md:p-7 rounded-2xl surface-card">
        <div>
            <span class="badge badge-green mb-3">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--accent)"></span>
                Modul Keanggotaan
            </span>
            <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">Data Anggota</h1>
            <p class="text-sm mt-1" style="color:var(--text-muted)">Kelola data seluruh anggota organisasi berdasarkan Badan Otonom.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.anggota.print-cards') }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all border"
               style="color:var(--text-secondary);border-color:var(--border-medium)"
               onmouseover="this.style.color='var(--text-primary)'"
               onmouseout="this.style.color='var(--text-secondary)'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Kartu
            </a>
            <a href="{{ route('admin.anggota.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all"
               style="background:var(--accent)"
               onmouseover="this.style.background='var(--accent-hover)'"
               onmouseout="this.style.background='var(--accent)'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Anggota
            </a>
        </div>
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
        <form action="{{ route('admin.anggota.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none" style="color:var(--text-muted)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama, posisi, atau wilayah..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm font-medium outline-none transition-all"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
            </div>
            <select name="status"
                    class="form-select-custom px-4 py-2.5 rounded-xl text-sm font-medium outline-none transition-all sm:w-44"
                    style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                <option value="">Semua Status</option>
                @foreach(['Aktif','Nonaktif','Alumni','Pending'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
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
                        <th class="px-6 py-4">Anggota</th>
                        <th class="px-6 py-4">Organisasi & Wilayah</th>
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
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold shrink-0"
                                     style="background:var(--accent-subtle);color:var(--accent)">
                                    {{ strtoupper(substr($anggota->name ?? 'A', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold leading-snug" style="color:var(--text-primary)">{{ $anggota->name }}</p>
                                    @if($anggota->joined_at)
                                    <p class="text-xs mt-0.5" style="color:var(--text-muted)">
                                        Bergabung @tanggal($anggota->joined_at)
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1.5">
                                @if($anggota->role)
                                <span class="badge badge-green">{{ $anggota->role }}</span>
                                @else
                                <span class="badge" style="background:rgba(245,158,11,0.1);color:#d97706;border:1px solid rgba(245,158,11,0.2)">Belum Diatur</span>
                                @endif
                                @if($anggota->wilayah)
                                <div class="flex items-center gap-1 text-xs" style="color:var(--text-muted)">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $anggota->wilayah }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php $status = strtolower($anggota->status ?? ''); @endphp
                            @if($status === 'aktif')
                                <span class="badge badge-green">
                                    <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--accent)"></span> Aktif
                                </span>
                            @elseif($status === 'alumni')
                                <span class="badge" style="background:rgba(59,130,246,0.1);color:#3b82f6;border:1px solid rgba(59,130,246,0.2)">Alumni</span>
                            @elseif($status === 'pending')
                                <span class="badge" style="background:rgba(245,158,11,0.1);color:#d97706;border:1px solid rgba(245,158,11,0.2)">Pending</span>
                            @else
                                <span class="badge badge-muted">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button"
                                   @click="showQrModal = true; memberId = '{{ $anggota->id }}'; qrUrl = '{{ route('kartu.verify', $anggota->id) }}'; qrName = '{{ addslashes($anggota->name) }}'; $nextTick(() => { document.getElementById('modal-qr-container').innerHTML = ''; new QRCode(document.getElementById('modal-qr-container'), { text: qrUrl, width: 200, height: 200, colorDark: '#008a5a', colorLight: '#ffffff' }); })"
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer"
                                   style="background:var(--bg-surface-elevated);color:var(--text-secondary);border:1px solid var(--border-light)"
                                   onmouseover="this.style.color='var(--text-primary)';this.style.borderColor='var(--border-medium)'"
                                   onmouseout="this.style.color='var(--text-secondary)';this.style.borderColor='var(--border-light)'">
                                    QR
                                </button>
                                <a href="{{ route('admin.anggota.edit', $anggota->id) }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                                   style="background:var(--accent-subtle);color:var(--accent)"
                                   onmouseover="this.style.background='var(--accent-subtle-hover)'"
                                   onmouseout="this.style.background='var(--accent-subtle)'">Edit</a>
                                <form action="{{ route('admin.anggota.destroy', $anggota->id) }}" method="POST"
                                      class="inline" onsubmit="return confirm('Yakin hapus data anggota ini?')">
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
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4"
                                     style="background:var(--bg-surface-elevated);color:var(--text-muted);border:1px solid var(--border-light)">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold mb-1" style="color:var(--text-primary)">Belum ada anggota</p>
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
        @if(isset($anggotas) && method_exists($anggotas,'hasPages') && $anggotas->hasPages())
        <div class="px-6 py-4" style="border-top:1px solid var(--border-light)">
            {{ $anggotas->links() }}
        </div>
        @endif
    </div>
</div>

    {{-- QR Modal --}}
    <div x-show="showQrModal" class="fixed inset-0 z-[100] flex items-center justify-center" x-cloak style="display: none;">
        <div class="fixed inset-0 backdrop-blur-sm" style="background:rgba(0,0,0,0.6)" @click="showQrModal = false" x-transition.opacity></div>
        <div class="relative surface-card p-6 md:p-8 rounded-3xl shadow-2xl max-w-sm w-full mx-4 flex flex-col items-center text-center" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90">
            
            <button type="button" @click="showQrModal = false" class="absolute top-4 right-4 p-2 rounded-full transition-colors"
                    style="color:var(--text-muted)" onmouseover="this.style.background='var(--bg-surface-elevated)'" onmouseout="this.style.background=''">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <h3 class="font-serif font-bold text-xl mb-1" style="color:var(--text-primary)" x-text="qrName"></h3>
            <p class="text-xs mb-6" style="color:var(--text-muted)">Scan QR ini untuk memverifikasi keanggotaan</p>
            
            <div class="p-4 rounded-2xl bg-white border flex items-center justify-center mb-6" style="border-color:var(--border-light); width: 232px; height: 232px;">
                <div id="modal-qr-container"></div>
            </div>
            
            <a :href="'{{ url('admin/anggota/print-cards') }}?id=' + memberId" target="_blank" 
                    class="w-full flex items-center justify-center py-3 rounded-xl text-sm font-bold text-white transition-all shadow-lg"
                    style="background:var(--accent)"
                    onmouseover="this.style.background='var(--accent-hover)'" onmouseout="this.style.background='var(--accent)'">
                Cetak Fisik
            </a>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endsection

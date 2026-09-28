@extends('layouts.admin')

@section('content')
@php
    $user = Auth::user();
    $anggota = class_exists(\App\Models\Anggota::class) ? $user->getOrBuildProfilAnggota() : null;
    $isAdmin = method_exists($user, 'isAdmin') ? $user->isAdmin() : ($user->role === 'admin');
    
    $countAnggota = class_exists(\App\Models\Anggota::class) ? \App\Models\Anggota::count() : 0;
    $countBerita = class_exists(\App\Models\Berita::class) ? \App\Models\Berita::count() : 0;
    $countGaleri = class_exists(\App\Models\Galeri::class) ? \App\Models\Galeri::count() : 0;
    $countAdmin = \App\Models\User::where('role', 'admin')->count();

    $recentBerita = class_exists(\App\Models\Berita::class) ? \App\Models\Berita::latest()->take(3)->get() : collect();
    $recentAnggota = class_exists(\App\Models\Anggota::class) ? \App\Models\Anggota::latest()->take(2)->get() : collect();
    
    $greeting = 'Good Morning';
    $hour = date('H');
    if ($hour >= 12 && $hour < 15) $greeting = 'Good Afternoon';
    elseif ($hour >= 15 && $hour < 18) $greeting = 'Good Evening';
    elseif ($hour >= 18) $greeting = 'Good Evening';
@endphp

<div class="max-w-6xl mx-auto space-y-10 pb-12">
    
    <!-- HEADER -->
    <div class="mb-10 pl-2">
        <div class="flex items-center gap-3 mb-3">
            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-100/50 px-2.5 py-1 rounded-md">{{ strtoupper($greeting) }}, {{ strtoupper(explode(' ', $user->name)[0]) }}</span>
            <div class="flex items-center gap-2 px-2.5 py-1 rounded-md bg-white border border-slate-200/50 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                <span class="w-1.5 h-1.5 rounded-full bg-[#087A4B] animate-pulse"></span>
                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">System Active</span>
            </div>
        </div>
        <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-3 leading-none">Dashboard</h1>
        <p class="text-slate-500 font-semibold text-sm max-w-md leading-relaxed">Kelola ekosistem digital NU Cikembulan, pantau statistik terkini, dan akses aksi cepat dari satu tempat.</p>
    </div>

    <!-- BENTO GRID -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        
        <!-- PROFILE PANEL (Col 1-4) -->
        <div class="md:col-span-4 glass-panel rounded-[2rem] p-7 flex flex-col relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-400/10 blur-2xl rounded-full -mr-10 -mt-10 pointer-events-none transition-opacity group-hover:opacity-100 opacity-50"></div>
            
            <div class="flex items-center gap-4 mb-8 relative z-10">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 shadow-inner flex items-center justify-center overflow-hidden border-2 border-white shrink-0">
                    @if($anggota && $anggota->photo)
                        <img src="{{ asset('storage/' . $anggota->photo) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-xl font-black text-slate-400">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight line-clamp-1">{{ $user->name }}</h3>
                    <span class="inline-block mt-1.5 px-2 py-0.5 bg-[#087A4B] text-white shadow-sm text-[9px] font-bold uppercase tracking-wider rounded">{{ $isAdmin ? 'Administrator' : 'Anggota' }}</span>
                </div>
            </div>

            <div class="space-y-5 mb-8 flex-grow relative z-10">
                <div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Status Akun</p>
                    <p class="text-[13px] font-bold text-slate-800">{{ ucfirst($anggota->status ?? 'Aktif') }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Email</p>
                    <p class="text-[13px] font-bold text-slate-800 break-all">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Bergabung Sejak</p>
                    <p class="text-[13px] font-bold text-slate-800">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <div class="pt-5 border-t border-slate-200/50 mt-auto relative z-10 flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID: #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                <a href="#profile-settings" onclick="document.getElementById('profile-settings').scrollIntoView({behavior: 'smooth'})" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-200 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </a>
            </div>
        </div>

        <!-- STATS & ACTIVITY (Col 5-12) -->
        <div class="md:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- QUICK ACTIONS (Col-span-2) -->
            @if($isAdmin)
            <div class="md:col-span-2 flex flex-wrap gap-3">
                <a href="{{ route('admin.berita.create') }}" class="group flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-5 py-3 rounded-[1rem] text-[11px] font-bold uppercase tracking-wider transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                    <svg class="w-4 h-4 text-emerald-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Berita
                </a>
                <a href="{{ route('admin.anggota.create') }}" class="group flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-5 py-3 rounded-[1rem] text-[11px] font-bold uppercase tracking-wider transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Tambah Anggota
                </a>
                <a href="{{ route('admin.galeri.create') }}" class="group flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-5 py-3 rounded-[1rem] text-[11px] font-bold uppercase tracking-wider transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Upload Galeri
                </a>
                <a href="{{ route('admin.anggota.print-cards') }}" class="group flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-5 py-3 rounded-[1rem] text-[11px] font-bold uppercase tracking-wider transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Kartu
                </a>
            </div>
            @endif

            <!-- STATS BLOCK (2x2 grid inside) -->
            <div class="glass-panel rounded-[2rem] p-7 grid grid-cols-2 gap-x-6 gap-y-8 md:col-span-2 lg:col-span-1">
                <div class="flex flex-col relative">
                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">Total Anggota</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-4xl font-black text-slate-900 tracking-tighter leading-none">{{ $countAnggota }}</span>
                        <span class="text-[10px] font-bold text-[#087A4B] uppercase tracking-wider">Aktif</span>
                    </div>
                </div>
                <div class="flex flex-col relative">
                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">Berita Dirilis</span>
                    <div class="text-4xl font-black text-slate-900 tracking-tighter leading-none">{{ $countBerita }}</div>
                </div>
                <div class="flex flex-col pt-6 border-t border-slate-200/50">
                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">Dokumentasi</span>
                    <div class="text-3xl font-black text-slate-900 tracking-tighter leading-none">{{ $countGaleri }}</div>
                </div>
                <div class="flex flex-col pt-6 border-t border-slate-200/50">
                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">Admin</span>
                    <div class="text-3xl font-black text-slate-900 tracking-tighter leading-none">{{ $countAdmin }}</div>
                </div>
            </div>

            <!-- RECENT ACTIVITY -->
            <div class="glass-panel rounded-[2rem] p-7 flex flex-col md:col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xs font-black text-slate-900 tracking-widest uppercase">Aktivitas Terbaru</h3>
                </div>
                <div class="space-y-6 flex-grow">
                    @forelse($recentBerita as $berita)
                    <div class="flex items-start gap-3.5 group cursor-default">
                        <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center shrink-0 border border-sky-100 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        </div>
                        <div class="pt-0.5">
                            <p class="text-[13px] font-bold text-slate-800 leading-tight group-hover:text-sky-600 transition-colors line-clamp-1">{{ $berita->title }}</p>
                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wide">Berita Diterbitkan &bull; {{ $berita->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    @endforelse

                    @forelse($recentAnggota as $ag)
                    <div class="flex items-start gap-3.5 group cursor-default">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-[#087A4B] flex items-center justify-center shrink-0 border border-emerald-100 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                        <div class="pt-0.5">
                            <p class="text-[13px] font-bold text-slate-800 leading-tight group-hover:text-[#087A4B] transition-colors line-clamp-1">{{ $ag->nama_lengkap }}</p>
                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wide">Anggota Baru &bull; {{ $ag->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>

        </div>
        
        <!-- PROFILE SETTINGS BENTO -->
        <div id="profile-settings" class="md:col-span-12 grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            
            <!-- Update Profile Form -->
            <div class="glass-panel rounded-[2rem] p-7">
                <div class="mb-8">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Informasi Profil</h3>
                    <p class="text-xs text-slate-500 font-medium mt-1">Perbarui foto dan informasi akun Anda.</p>
                </div>
                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('patch')
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2.5">Foto Profil / Kartu</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
                                <img id="dashboard-photo-preview" src="{{ $anggota && $anggota->photo ? asset('storage/'.$anggota->photo) : '' }}" class="{{ $anggota && $anggota->photo ? '' : 'hidden' }} w-full h-full object-cover">
                                @if(!$anggota || !$anggota->photo)
                                    <span id="dashboard-photo-placeholder" class="text-xs font-black text-slate-300">FOTO</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="photo" id="dashboard_photo_input" accept="image/jpeg,image/png,image/jpg" onchange="previewDashboardPhoto(this)"
                                       class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:uppercase file:tracking-wider file:bg-white file:border-slate-200 file:shadow-sm file:text-slate-700 hover:file:bg-slate-50 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-white/50 border border-slate-200 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-[#087A4B]/20 rounded-xl px-4 py-3 text-[13px] font-bold text-slate-800 transition outline-none shadow-sm">
                            @error('name') <p class="text-[10px] text-red-500 font-bold mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2.5">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-white/50 border border-slate-200 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-[#087A4B]/20 rounded-xl px-4 py-3 text-[13px] font-bold text-slate-800 transition outline-none shadow-sm">
                            @error('email') <p class="text-[10px] text-red-500 font-bold mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm transition active:scale-95">Simpan Profil</button>
                    </div>
                </form>
            </div>

            <!-- Update Password Form -->
            <div class="glass-panel rounded-[2rem] p-7">
                <div class="mb-8">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Keamanan Akun</h3>
                    <p class="text-xs text-slate-500 font-medium mt-1">Pastikan menggunakan kata sandi yang kuat.</p>
                </div>

                <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('put')

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2.5">Sandi Saat Ini</label>
                        <input type="password" name="current_password" required class="w-full bg-white/50 border border-slate-200 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-[#087A4B]/20 rounded-xl px-4 py-3 text-[13px] font-bold text-slate-800 transition outline-none shadow-sm">
                        @error('current_password', 'updatePassword') <p class="text-[10px] text-red-500 font-bold mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2.5">Sandi Baru</label>
                            <input type="password" name="password" required class="w-full bg-white/50 border border-slate-200 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-[#087A4B]/20 rounded-xl px-4 py-3 text-[13px] font-bold text-slate-800 transition outline-none shadow-sm">
                            @error('password', 'updatePassword') <p class="text-[10px] text-red-500 font-bold mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2.5">Konfirmasi</label>
                            <input type="password" name="password_confirmation" required class="w-full bg-white/50 border border-slate-200 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-[#087A4B]/20 rounded-xl px-4 py-3 text-[13px] font-bold text-slate-800 transition outline-none shadow-sm">
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm transition active:scale-95">Ubah Sandi</button>
                    </div>
                </form>
            </div>
            
        </div>

        @if(!$isAdmin && $anggota)
        <!-- MEMBER QR CARD -->
        <div class="md:col-span-12 glass-panel rounded-[2rem] p-7 flex flex-col sm:flex-row items-center justify-between gap-6 relative overflow-hidden mt-4">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 to-transparent pointer-events-none"></div>
            <div class="relative z-10">
                <h3 class="text-xl font-black text-slate-900 tracking-tight mb-2">Kartu Anggota Digital</h3>
                <p class="text-sm text-slate-500 font-medium max-w-sm mb-5 leading-relaxed">Tunjukkan atau pindai kode QR ini untuk verifikasi keanggotaan Anda di kegiatan resmi NU Cikembulan.</p>
                <a href="{{ route('anggota.print-my-card') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:text-[#087A4B] hover:border-[#087A4B]/30 hover:bg-emerald-50 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Kartu Cetak
                </a>
            </div>
            <div class="p-4 bg-white rounded-2xl shadow-sm border border-slate-100 relative z-10 shrink-0">
                <div id="user-qr-code"></div>
            </div>
        </div>
        @endif
        
    </div>
</div>

<script>
    function previewDashboardPhoto(input) {
        const preview = document.getElementById('dashboard-photo-preview');
        const placeholder = document.getElementById('dashboard-photo-placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@if(!$isAdmin && $anggota)
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qrContainer = document.getElementById('user-qr-code');
        if (qrContainer) {
            new QRCode(qrContainer, {
                text: "{{ route('kartu.verify', $anggota->id) }}",
                width: 90,
                height: 90,
                colorDark: '#087A4B',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });
        }
    });
</script>
@endif
@endsection
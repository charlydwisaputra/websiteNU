<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keanggotaan - NU Cikembulan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 min-h-screen flex flex-col justify-center items-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden my-6">
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-[#087A4B] to-emerald-600 p-6 text-white text-center relative">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md text-white font-black text-lg mb-2 shadow-sm border border-white/20">
                NU
            </div>
            <h1 class="text-xs uppercase tracking-widest font-extrabold text-emerald-100">Nahdlatul Ulama Ã‚Â· Cikembulan</h1>
            <h2 class="text-base font-black tracking-tight mt-0.5">Sistem Verifikasi Digital</h2>
        </div>

        <!-- Verification Status Pill -->
        <div class="p-6 md:p-8 text-center">
            @php
                $isAktif = in_array(strtolower($anggota->status ?? ''), ['aktif', 'admin', 'administrator']);
            @endphp

            @if($isAktif)
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 text-[#087A4B] border border-emerald-200 text-xs font-black shadow-sm mb-6">
                    <svg class="w-4 h-4 text-[#087A4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                    KARTU TERVERIFIKASI RESMI
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-black shadow-sm mb-6">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    STATUS: {{ strtoupper($anggota->status ?? 'PENDING') }}
                </div>
            @endif

            <!-- Foto Anggota -->
            <div class="w-24 h-24 mx-auto rounded-2xl bg-slate-100 border-4 border-white shadow-lg overflow-hidden flex items-center justify-center text-2xl font-black text-[#087A4B] mb-4">
                @if($anggota->photo)
                    <img src="{{ asset('storage/' . $anggota->photo) }}" alt="{{ $anggota->name }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($anggota->name, 0, 2)) }}
                @endif
            </div>

            <h3 class="text-xl font-black text-slate-900 tracking-tight">{{ $anggota->name }}</h3>
            <div class="inline-block mt-1 px-3 py-1 bg-emerald-50 text-[#087A4B] border border-emerald-100 font-bold text-xs rounded-full">
                {{ $anggota->role ?: ($anggota->user && $anggota->user->isAdmin() ? 'Administrator' : 'Anggota') }}
            </div>

            <!-- Detail Box -->
            <div class="mt-6 bg-slate-50/80 rounded-2xl p-4 text-left border border-slate-100 divide-y divide-slate-100 text-xs space-y-2.5">
                <div class="flex justify-between items-center pt-2">
                    <span class="text-slate-400 font-bold">ID Keanggotaan</span>
                    <span class="font-mono font-black text-slate-700">ID-{{ str_pad($anggota->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-slate-400 font-bold">Wilayah / Ranting</span>
                    <span class="font-bold text-slate-700">{{ $anggota->wilayah ?: 'Pusat / Cikembulan' }}</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-slate-400 font-bold">Status Akun</span>
                    <span class="font-black {{ $isAktif ? 'text-[#087A4B]' : 'text-amber-600' }}">{{ ucfirst($anggota->status ?: 'Pending') }}</span>
                </div>
                @if($anggota->joined_at)
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-slate-400 font-bold">Terdaftar Sejak</span>
                        <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($anggota->joined_at)->format('d M Y') }}</span>
                    </div>
                @endif
            </div>

            <div class="mt-6 pt-6 border-t border-slate-100 flex flex-col gap-2">
                <a href="{{ route('home') }}" class="w-full py-3 bg-[#087A4B] hover:bg-emerald-700 text-white font-bold text-xs rounded-2xl shadow-md shadow-emerald-500/20 transition flex items-center justify-center gap-2">
                    Kunjungi Website Resmi NU Cikembulan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

        </div>

        <div class="bg-slate-50 py-3 text-center border-t border-slate-100 text-[10px] font-bold text-slate-400">
            DIGDAYA Ã‚Â· Nahdlatul Ulama Cikembulan
        </div>

    </div>

</body>
</html>

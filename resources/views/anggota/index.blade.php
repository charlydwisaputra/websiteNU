<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anggota - NU CIKEMBULAN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.theme-script')
</head>

<body class="font-sans antialiased text-gray-900 bg-slate-50 flex flex-col min-h-screen relative overflow-x-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-6xl h-48 bg-gradient-to-b from-emerald-500/20 via-emerald-500/5 to-transparent blur-3xl pointer-events-none z-40"></div>

    @include('layouts.public-navigation')

    <main class="max-w-[1200px] mx-auto px-4 sm:px-6 pt-28 pb-16 flex-grow w-full">
        <!-- Clean iOS-style Header -->
        <div class="reveal relative overflow-hidden rounded-[2.5rem] bg-white/60 backdrop-blur-3xl border border-white/80 shadow-[0_8px_30px_rgba(0,0,0,0.03)] px-6 py-10 sm:px-10">
            <div class="absolute -right-16 -top-20 h-56 w-56 rounded-full bg-emerald-100/50 blur-3xl"></div>
            <div class="relative z-10">
                <span class="text-gray-500 font-semibold text-[11px] uppercase tracking-widest block mb-2">Modul Keanggotaan</span>
                <h1 class="mt-2 text-3xl md:text-4xl font-black text-gray-900 tracking-tight">Anggota NU Cikembulan</h1>
                <p class="mt-3 max-w-xl text-[15px] leading-relaxed text-gray-500">Kenali anggota aktif dari berbagai badan otonom NU Cikembulan dalam satu direktori.</p>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-5 rounded-[2rem] border border-white/80 bg-white/60 backdrop-blur-xl p-5 shadow-[0_8px_30px_rgba(0,0,0,0.02)] sm:p-6 reveal">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-500">Filter Banom</p>
                    <p class="mt-1 text-[13px] text-gray-400">Pilih kategori untuk melihat anggota terkait.</p>
                </div>
                <form action="{{ route('anggota') }}" method="GET" class="flex w-full gap-2 md:w-auto">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari anggota..." class="w-full rounded-2xl border border-gray-200/50 bg-white px-5 py-2.5 text-[14px] font-medium outline-none shadow-sm focus:border-gray-300 focus:ring-0 md:w-64">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <button type="submit" class="rounded-2xl bg-gray-900 px-5 py-2.5 text-[14px] font-medium text-white transition hover:bg-black shadow-[0_4px_12px_rgba(0,0,0,0.1)] hover:shadow-[0_6px_16px_rgba(0,0,0,0.15)] hover:scale-[1.02]">Cari</button>
                </form>
            </div>
            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-none">
                <a href="{{ route('anggota') }}" class="shrink-0 rounded-full px-5 py-2.5 text-[13px] font-medium transition-all {{ request('category') ? 'bg-black/5 text-gray-600 hover:bg-black/10 hover:text-gray-900' : 'bg-gray-900 text-white shadow-md' }}">Semua</a>
                @foreach ($categories as $category)
                    <a href="{{ route('anggota', ['category' => $category, 'search' => request('search')]) }}" class="shrink-0 rounded-full px-5 py-2.5 text-[13px] font-medium transition-all {{ request('category') === $category ? 'bg-gray-900 text-white shadow-md' : 'bg-black/5 text-gray-600 hover:bg-black/10 hover:text-gray-900' }}">{{ $category }}</a>
                @endforeach
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($anggotas as $anggota)
                <article class="reveal group overflow-hidden rounded-[1.5rem] border border-white/80 bg-white/70 backdrop-blur-md shadow-[0_4px_20px_rgba(0,0,0,0.02)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(0,0,0,0.08)]">
                    <div class="flex items-center gap-4 p-5">
                        @if ($anggota->photo)
                            <img src="{{ asset('storage/' . $anggota->photo) }}" alt="{{ $anggota->name }}" class="h-16 w-16 rounded-2xl object-cover shadow-sm transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gray-100 text-lg font-black text-gray-400 shadow-inner transition-colors duration-300 group-hover:bg-gray-900 group-hover:text-white">
                                {{ strtoupper(substr($anggota->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-grow">
                            <h2 class="truncate text-[16px] font-bold text-gray-900">{{ $anggota->name }}</h2>
                            <p class="mt-1 text-[13px] font-semibold text-[#087A4B] truncate">{{ $anggota->position }}</p>
                            <p class="mt-0.5 truncate text-[12px] text-gray-500">{{ $anggota->region ?: 'Wilayah Pusat / Umum' }}</p>
                        </div>
                    </div>
                </article>
            @empty
                <div class="reveal col-span-full rounded-2xl border border-dashed border-gray-200 bg-white py-16 text-center">
                    <p class="font-medium text-gray-400">Belum ada anggota publik yang tersedia.</p>
                </div>
            @endforelse
        </div>

        @if ($anggotas->hasPages())
            <div class="mt-8">{{ $anggotas->links() }}</div>
        @endif
    </main>
    @include('layouts.public-footer')
</body>

</html>
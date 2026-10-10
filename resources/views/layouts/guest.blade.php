<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NU Cikembulan') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.theme-script')
</head>
<body class="font-sans antialiased min-h-screen flex flex-col justify-center items-center p-4 relative overflow-x-hidden"
      style="background:var(--bg-base);color:var(--text-primary)">

    <div class="relative z-10 w-full max-w-sm my-auto py-8 flex flex-col items-center">

        {{-- Brand --}}
        <div class="mb-8 text-center">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-bold text-sm shadow-sm transition-transform group-hover:scale-105"
                     style="background:var(--accent)">NU</div>
                <div>
                    <p class="font-serif font-bold text-lg tracking-tight" style="color:var(--text-primary)">NU Cikembulan</p>
                    <p class="text-[10px] font-semibold uppercase tracking-widest" style="color:var(--text-muted)">Digital Portal</p>
                </div>
            </a>
        </div>

        {{-- Card --}}
        <div class="w-full surface-card rounded-2xl p-7 sm:p-8">
            {{ $slot }}
        </div>
    </div>
</body>
</html>

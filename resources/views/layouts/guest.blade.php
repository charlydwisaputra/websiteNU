<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'NU Cikembulan') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @include('components.theme-script')
    </head>
    <body class="font-sans text-slate-800 antialiased bg-[#F8FAF9] min-h-screen flex flex-col justify-center items-center p-4 relative overflow-x-hidden selection:bg-emerald-600 selection:text-white">

        {{-- Ambient Mesh Lighting --}}
        <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
            <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-gradient-to-b from-emerald-100/50 via-emerald-50/20 to-transparent blur-[120px] rounded-full"></div>
            <div class="absolute -bottom-40 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-emerald-100/40 blur-[140px] rounded-full"></div>
        </div>

        <div class="relative z-10 w-full max-w-md my-auto py-6 flex flex-col items-center">
            {{-- Brand Logo --}}
            <div class="mb-6 text-center">
                <a href="/" class="inline-flex items-center gap-2.5 group">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#087A4B] to-[#065C39] flex items-center justify-center text-white font-black text-sm shadow-md shadow-emerald-900/20 group-hover:scale-105 transition-transform duration-200">
                        NU
                    </div>
                    <div class="text-left">
                        <span class="block font-black text-slate-900 text-base tracking-tight leading-tight">
                            NU <span class="text-[#087A4B]">CIKEMBULAN</span>
                        </span>
                        <span class="block text-[9px] font-bold text-slate-400 tracking-widest uppercase">
                            Digital Portal
                        </span>
                    </div>
                </a>
            </div>

            {{-- Glassmorphism Card --}}
            <div class="w-full bg-white/85 backdrop-blur-2xl border border-white/80 shadow-[0_16px_40px_rgba(0,0,0,0.06)] rounded-[2.25rem] p-6 sm:p-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
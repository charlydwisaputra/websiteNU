<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Cetak Kartu — {{ $anggota->name }} — NU Kalisari</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.theme-script')

    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .print-single-container {
                padding: 0 !important;
                max-width: 100% !important;
                margin: 0 !important;
                display: block !important;
            }

            .member-card {
                width: 85.6mm !important;
                height: 54mm !important;
                max-width: 85.6mm !important;
                box-shadow: none !important;
                border: 1px solid #94a3b8 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                margin: 0 !important;
                float: left !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="font-sans antialiased min-h-[100dvh] flex flex-col"
      style="background:var(--bg-base);color:var(--text-primary)">

    {{-- Header (No Print) --}}
    <header class="no-print sticky top-0 z-40 border-b p-3 sm:p-4 glass-panel shadow-sm"
            style="background:var(--bg-overlay);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-color:var(--border-light)">
        <div class="max-w-4xl mx-auto flex items-center justify-between gap-3">
            {{-- Tombol Kembali + Judul --}}
            <div class="flex items-center gap-2.5 min-w-0">
                <a href="{{ url()->previous() ?: route('admin.anggota.index') }}"
                   class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-xs font-semibold border transition-all shrink-0 min-h-[44px] min-w-[44px]"
                   style="background:var(--bg-surface);color:var(--text-secondary);border-color:var(--border-medium)"
                   title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                
                {{-- HP: Judul 1 baris + nama anggota --}}
                <div class="md:hidden min-w-0">
                    <h1 class="font-serif font-bold text-sm leading-tight truncate" style="color:var(--text-primary)">
                        Cetak Kartu &middot; <span class="font-sans font-medium text-xs" style="color:var(--text-secondary)">{{ $anggota->name }}</span>
                    </h1>
                </div>

                {{-- Desktop: Judul lengkap & subtitle --}}
                <div class="hidden md:block min-w-0">
                    <h1 class="font-serif font-bold text-lg leading-tight" style="color:var(--text-primary)">
                        Cetak Kartu Anggota
                    </h1>
                    <p class="text-xs truncate max-w-md" style="color:var(--text-muted)">
                        {{ $anggota->name }} &middot; ID-{{ str_pad($anggota->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>

            {{-- Desktop Action Button: "Cetak / Simpan PDF" (Hidden on mobile) --}}
            <div class="hidden md:flex items-center gap-2">
                <button type="button"
                        onclick="window.print()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-sm transition-all min-h-[44px]"
                        style="background:var(--accent)"
                        onmouseover="this.style.background='var(--accent-hover)'"
                        onmouseout="this.style.background='var(--accent)'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak / Simpan PDF</span>
                </button>
            </div>
        </div>
    </header>

    {{-- Main Preview Area (No huge empty gap at top, starts right below header) --}}
    <main class="flex-1 max-w-4xl w-full mx-auto p-4 sm:p-6 flex flex-col items-center justify-start print-single-container pb-28 md:pb-8">

        {{-- Notice Box (Dismissible, 1-2 short sentences) --}}
        <div class="no-print w-full max-w-[420px] mb-4" x-data="{ showTip: true }" x-show="showTip" x-transition>
            <div class="p-3 rounded-xl border text-xs flex items-center justify-between gap-2 shadow-sm"
                 style="background:var(--accent-subtle);border-color:rgba(0,138,90,0.2);color:var(--text-primary)">
                <div class="flex items-center gap-2 min-w-0">
                    <svg class="w-4 h-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-[11.5px] leading-snug" style="color:var(--text-secondary)">
                        Gunakan ukuran kertas <strong>A4</strong> dan matikan <strong>"Headers and footers"</strong>. Di ponsel, pilih <strong>"Simpan sebagai PDF"</strong>.
                    </p>
                </div>
                <button type="button" @click="showTip = false" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 shrink-0" title="Tutup tips">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Card Container: Centered, fills full width up to max-width ~420px, aspect-ratio 85.6/54, without right margin space --}}
        <div class="w-full flex justify-center items-center">
            <div class="w-full max-w-[420px] transition-transform hover:scale-[1.01]">
                @include('admin.anggota.partials.kartu-member', ['anggota' => $anggota])
            </div>
        </div>
    </main>

    {{-- HP Sticky Action Bar (Safe-area inset, only Cetak / Simpan PDF) (No Print) --}}
    <div class="no-print md:hidden fixed bottom-0 left-0 right-0 z-40 p-3 border-t shadow-2xl flex items-center gap-2"
         style="background:var(--bg-overlay);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-color:var(--border-light);padding-bottom:max(0.75rem,env(safe-area-inset-bottom))">
        <button type="button"
                onclick="window.print()"
                class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white text-center shadow-sm inline-flex items-center justify-center gap-2 min-h-[44px]"
                style="background:var(--accent)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            <span>Cetak / Simpan PDF</span>
        </button>
    </div>

    {{-- Script Generate QR --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const boxes = document.querySelectorAll('.qr-canvas-box');
            boxes.forEach(function (box) {
                const targetUrl = box.getAttribute('data-qr-target');
                if (!targetUrl) return;

                new QRCode(box, {
                    text: targetUrl,
                    width: 56,
                    height: 56,
                    colorDark: '#087A4B',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.M,
                });
            });
        });
    </script>
</body>
</html>

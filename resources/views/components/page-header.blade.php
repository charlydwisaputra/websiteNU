@props([
    'eyebrow',
    'title',
    'subtitle',
    'count' => 0,
    'unit' => '',
    'countExpression' => null,
])

<header {{ $attributes->merge(['class' => 'mb-8 pb-6 md:mb-10 md:pb-8 border-b border-black/5 dark:border-white/5 text-left']) }}>
    <!-- Eyebrow: teks polos tanpa background/pill (kapital 12px, letter-spacing lebar, hijau) -->
    <p class="text-xs font-semibold uppercase tracking-widest mb-3" style="color:var(--accent)">
        {{ $eyebrow }}
    </p>

    <!-- Judul: ukuran sekarang dipertahankan. Jarak judul->subjudul 16px -->
    <h1 class="font-serif text-[clamp(2.5rem,6vw,5rem)] font-medium tracking-tight mb-4 leading-tight" style="color:var(--text-primary)">
        {{ $title }}
    </h1>

    <!-- Subjudul: max-width 560px + text-wrap: balance -->
    <p class="text-lg md:text-xl leading-relaxed font-light mb-4" style="color:var(--text-secondary); max-width: 560px; text-wrap: balance;">
        {{ $subtitle }}
    </p>

    <!-- Chip jumlah: pill kecil hijau transparan. Isi dinamis {n} warta / {n} foto -->
    <div class="page-header-chip inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold">
        @if($countExpression)
            <span x-text="{{ $countExpression }}">{{ $count }}</span>
        @else
            <span>{{ $count }}</span>
        @endif
        <span>{{ $unit }}</span>
    </div>

    <style>
        .page-header-chip {
            background: var(--accent-subtle);
            color: var(--accent);
        }
        .dark .page-header-chip {
            background: rgba(0, 179, 115, 0.18);
            color: #34d399;
        }
    </style>
</header>

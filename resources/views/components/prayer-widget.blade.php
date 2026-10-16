@props(['prayer'])

@if($prayer && !empty($prayer['timings']))
<section class="border-b border-black/5 dark:border-white/5"
    x-data="{
        timings: {{ \Illuminate\Support\Js::from($prayer['timings']) }},
        hijri: {{ \Illuminate\Support\Js::from($prayer['hijri']) }},
        nextPrayer: '',
        init() {
            this.updateNext();
            setInterval(() => this.updateNext(), 60000);
        },
        updateNext() {
            const now = new Date();
            const hh = now.getHours();
            const mm = now.getMinutes();
            const nowMin = hh * 60 + mm;
            let found = '';
            for (const [name, time] of Object.entries(this.timings)) {
                if (!time) continue;
                const [h, m] = time.split(':').map(Number);
                if (h * 60 + m > nowMin) { found = name; break; }
            }
            this.nextPrayer = found || Object.keys(this.timings)[0];
        }
    }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        {{-- Hijri date --}}
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-medium tracking-wide" style="color: var(--text-muted)">
                <span x-text="hijri.day + ' ' + hijri.month + ' ' + hijri.year + ' H'"></span>
            </p>
            <p class="text-[10px] font-medium uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Jadwal Salat</p>
        </div>

        {{-- Prayer times strip --}}
        <div class="-mx-4 px-4 sm:mx-0 sm:px-0 flex gap-2 overflow-x-auto sm:overflow-visible snap-x snap-mandatory scrollbar-none pb-1 sm:pb-0" style="scroll-snap-type: x mandatory;">
            <template x-for="[name, time] in Object.entries(timings)" :key="name">
                <div class="shrink-0 snap-start flex items-center gap-2 px-3 py-2 rounded-xl text-sm transition-colors border sm:flex-1 sm:justify-center"
                     :class="nextPrayer === name
                         ? 'bg-emerald-600 text-white border-emerald-600 font-semibold'
                         : 'bg-black/5 dark:bg-white/5 border-transparent'"
                     :style="nextPrayer !== name ? 'color: var(--text-primary)' : ''"
                     style="scroll-snap-align: start;">
                    <span class="font-medium" x-text="name"></span>
                    <span class="opacity-70" x-text="time"></span>
                </div>
            </template>
        </div>
    </div>
</section>
@endif

@props(['prayer'])

@if($prayer && !empty($prayer['timings']))
<section class="border-b border-black/5 dark:border-white/5 bg-slate-50/70 dark:bg-[#060B08] transition-colors"
    x-data="{
        timings: {{ \Illuminate\Support\Js::from($prayer['timings']) }},
        hijri: {{ \Illuminate\Support\Js::from($prayer['hijri'] ?? []) }},
        isFallback: {{ \Illuminate\Support\Js::from($prayer['is_fallback'] ?? false) }},
        nextPrayer: '',
        countdownText: '',
        init() {
            this.updateNext();
            setInterval(() => this.updateNext(), 20000);
        },
        updateNext() {
            const now = new Date();
            const nowMin = now.getHours() * 60 + now.getMinutes();
            let found = '';
            let nextMin = 0;
            const entries = Object.entries(this.timings).filter(([_, t]) => !!t);

            for (const [name, time] of entries) {
                const [h, m] = time.split(':').map(Number);
                const tMin = h * 60 + m;
                if (tMin > nowMin) {
                    found = name;
                    nextMin = tMin;
                    break;
                }
            }

            if (!found && entries.length > 0) {
                // Next prayer is tomorrow's Subuh
                found = entries[0][0];
                const [h, m] = entries[0][1].split(':').map(Number);
                nextMin = (h * 60 + m) + (24 * 60);
            }

            this.nextPrayer = found;

            if (found) {
                const diffMin = nextMin >= nowMin ? (nextMin - nowMin) : ((nextMin + 24 * 60) - nowMin);
                const hours = Math.floor(diffMin / 60);
                const mins = diffMin % 60;
                if (hours > 0) {
                    this.countdownText = `${found} dalam ${hours}j ${mins}m`;
                } else {
                    this.countdownText = `${found} dalam ${mins}m`;
                }
            }
        }
    }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 sm:py-4">
        {{-- Header: Location + Hijri date + Countdown --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4 mb-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-emerald-700 dark:text-emerald-400">
                    <svg class="w-3.5 h-3.5 text-emerald-700 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Kalisari, Banyumas (WIB)
                </span>
                <template x-if="hijri && hijri.day">
                    <span class="flex items-center gap-2">
                        <span class="text-xs text-black/30 dark:text-white/30 hidden sm:inline">·</span>
                        <span class="text-xs sm:text-sm font-medium tracking-normal text-slate-700 dark:text-slate-300"
                              x-text="hijri.day + ' ' + hijri.month + ' ' + hijri.year + ' H'">
                        </span>
                    </span>
                </template>
                <template x-if="isFallback">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50">
                        Jadwal Standar
                    </span>
                </template>
            </div>

            <div class="flex items-center justify-between sm:justify-end gap-2.5">
                <template x-if="countdownText">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] sm:text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 border border-emerald-300/80 dark:border-emerald-800/80 shadow-sm"
                          x-text="countdownText">
                    </span>
                </template>
                <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-700 dark:text-emerald-400">Jadwal Salat</span>
            </div>
        </div>

        {{-- Prayer times strip: 5-column grid on mobile (fits 360px-430px without cards cut off) & desktop (left-aligned name, right-aligned time, balanced padding) --}}
        <div class="grid grid-cols-5 gap-1.5 sm:gap-2.5 md:gap-3 w-full">
            <template x-for="[name, time] in Object.entries(timings)" :key="name">
                <div :id="'prayer-card-' + name"
                     class="min-w-0 flex flex-col sm:flex-row items-center justify-center sm:justify-between text-center sm:text-left px-1 py-2 sm:px-3.5 sm:py-2.5 md:px-4 md:py-3 rounded-lg sm:rounded-xl text-xs sm:text-sm transition-all border select-none"
                     :class="nextPrayer === name
                         ? 'bg-emerald-700 text-white border-emerald-700 font-semibold shadow-md shadow-emerald-950/20 ring-2 ring-emerald-600/30 dark:bg-emerald-700 dark:border-emerald-600'
                         : 'bg-white dark:bg-[#101813] border-black/5 dark:border-white/10 hover:border-emerald-500/25 dark:hover:border-white/20'"
                     :style="nextPrayer !== name ? 'color: var(--text-primary)' : ''">
                    <span class="font-semibold text-[11px] sm:text-sm leading-tight tracking-tight truncate w-full sm:w-auto text-center sm:text-left"
                          :class="nextPrayer === name ? 'text-white' : 'text-slate-800 dark:text-slate-100'"
                          x-text="name"></span>
                    <span class="text-[10px] sm:text-xs md:text-sm leading-tight font-mono mt-0.5 sm:mt-0 font-medium shrink-0 sm:ml-auto text-center sm:text-right"
                          :class="nextPrayer === name ? 'text-white font-semibold' : 'text-slate-600 dark:text-slate-300'"
                          x-text="time"></span>
                </div>
            </template>
        </div>
    </div>
</section>
@else
{{-- Tampilan Cadangan ketika jadwal salat tidak tersedia --}}
<section class="border-b border-black/5 dark:border-white/5 bg-slate-50/70 dark:bg-[#060B08]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs">
        <span class="inline-flex items-center gap-1.5 font-semibold text-emerald-700 dark:text-emerald-400">
            <svg class="w-3.5 h-3.5 text-emerald-700 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Kalisari, Banyumas (WIB)
        </span>
        <span class="text-slate-600 dark:text-slate-400 text-[11px]">
            Jadwal salat sedang diperbarui dari Kementerian Agama RI (WIB).
        </span>
    </div>
</section>
@endif

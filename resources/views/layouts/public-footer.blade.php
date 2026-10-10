<footer class="mt-auto relative z-10 overflow-hidden" style="background:var(--black-iris);border-top:1px solid rgba(255,255,255,0.06)">
    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-8 lg:gap-16">
            
            {{-- Brand --}}
            <div class="md:col-span-5 lg:col-span-4 flex flex-col items-start text-left">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-[10px] font-bold" style="background:var(--nu-green)">NU</div>
                    <span class="font-serif font-bold text-base text-white tracking-tight">NU Kalisari</span>
                </div>
                <p class="text-sm leading-relaxed max-w-xs" style="color:rgba(160,189,219,0.55)">
                    Menghubungkan jamaah, organisasi, dan informasi dalam satu ekosistem digital di tingkat ranting.
                </p>
            </div>

            {{-- Navigation --}}
            <div class="md:col-span-3 lg:col-span-4 flex flex-col items-start md:items-center text-left md:text-center">
                <div class="w-full md:w-auto text-left">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-5" style="color:rgba(160,189,219,0.45)">Navigasi</h3>
                    <ul class="flex flex-col gap-3">
                        @foreach([
                            ['route' => route('home'),        'label' => 'Beranda'],
                            ['route' => route('tentang'),     'label' => 'Tentang'],
                            ['route' => route('ekosistem'),   'label' => 'Ekosistem'],
                            ['route' => route('kegiatan'),    'label' => 'Kegiatan'],
                            ['route' => route('berita.index'),'label' => 'Berita'],
                            ['route' => route('galeri'),      'label' => 'Dokumentasi'],
                        ] as $item)
                        <li>
                            <a href="{{ $item['route'] }}" class="text-sm font-medium transition-colors hover:text-white focus-visible:ring-2 focus-visible:ring-white rounded" style="color:rgba(160,189,219,0.65)">
                                {{ $item['label'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Contact --}}
            <div class="md:col-span-4 lg:col-span-4 flex flex-col items-start md:items-end text-left md:text-right">
                <div class="w-full md:w-auto text-left md:text-right">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-5" style="color:rgba(160,189,219,0.45)">Informasi</h3>
                    <div class="flex flex-col gap-2 text-sm" style="color:rgba(160,189,219,0.55)">
                        <span class="font-semibold text-white">Pengurus Ranting NU</span>
                        <span>Desa Kalisari</span>
                        <span>Kec. Andir, Kota Bandung</span>
                        <span>Jawa Barat, Indonesia</span>
                        <a href="mailto:info@nukalisari.or.id" class="mt-3 flex items-center md:justify-end gap-1.5 text-sm w-fit md:w-full transition-colors hover:text-white focus-visible:ring-2 focus-visible:ring-white rounded" style="color:var(--nu-green)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            info@nukalisari.or.id
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="border-top:1px solid rgba(255,255,255,0.05)">
        <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col md:flex-row items-center justify-between gap-3 text-[11px] font-medium" style="color:rgba(160,189,219,0.40)">
            <span>&copy; {{ date('Y') }} Nahdlatul Ulama Kalisari</span>
            <span class="uppercase tracking-widest text-[10px] text-center md:text-right">Satu Ekosistem. Satu Gerakan. Satu Ruang Digital.</span>
        </div>
    </div>
</footer>
<footer class="mt-auto relative z-10 overflow-hidden"
        style="background:var(--black-iris);border-top:1px solid rgba(255,255,255,0.06)">

    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid gap-10 md:grid-cols-12 lg:gap-16">

            {{-- Brand --}}
            <div class="md:col-span-5 lg:col-span-4">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-[10px] font-bold"
                         style="background:var(--nu-green)">NU</div>
                    <span class="font-serif font-bold text-base text-white tracking-tight">NU Cikembulan</span>
                </div>
                <p class="text-sm leading-relaxed max-w-xs" style="color:rgba(160,189,219,0.55)">
                    Menghubungkan jamaah, organisasi, dan informasi dalam satu ekosistem digital di tingkat ranting.
                </p>
            </div>

            {{-- Navigation --}}
            <div class="md:col-span-3 lg:col-span-4 flex md:justify-center">
                <div>
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-5"
                        style="color:rgba(160,189,219,0.45)">Navigasi</h3>
                    <ul class="flex flex-col gap-3">
                        @foreach([
                            ['route' => route('home'),        'label' => 'Beranda'],
                            ['route' => route('anggota'),     'label' => 'Anggota'],
                            ['route' => route('galeri'),      'label' => 'Dokumentasi'],
                            ['route' => route('berita.index'),'label' => 'Berita'],
                        ] as $item)
                        <li>
                            <a href="{{ $item['route'] }}"
                               class="text-sm font-medium transition-colors"
                               style="color:rgba(160,189,219,0.65)"
                               onmouseover="this.style.color='#fff'"
                               onmouseout="this.style.color='rgba(160,189,219,0.65)'">
                                {{ $item['label'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Contact --}}
            <div class="md:col-span-4 lg:col-span-4 flex md:justify-end">
                <div>
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-5"
                        style="color:rgba(160,189,219,0.45)">Informasi</h3>
                    <div class="flex flex-col gap-2 text-sm" style="color:rgba(160,189,219,0.55)">
                        <span class="font-semibold text-white">Pengurus Ranting NU</span>
                        <span>Desa Cikembulan</span>
                        <span>Kec. Andir, Kota Bandung</span>
                        <span>Jawa Barat, Indonesia</span>
                        <a href="mailto:info@nucikembulan.or.id"
                           class="mt-3 flex items-center gap-1.5 text-sm w-fit transition-colors"
                           style="color:var(--nu-green)"
                           onmouseover="this.style.color='#fff'"
                           onmouseout="this.style.color='var(--nu-green)'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            info@nucikembulan.or.id
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="border-top:1px solid rgba(255,255,255,0.05)">
        <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 py-5
                    flex flex-col sm:flex-row items-center justify-between gap-3
                    text-[11px] font-medium"
             style="color:rgba(160,189,219,0.40)">
            <span>&copy; {{ date('Y') }} Nahdlatul Ulama Cikembulan</span>
            <span class="uppercase tracking-widest text-[10px]">Satu Ekosistem. Satu Gerakan. Satu Ruang Digital.</span>
        </div>
    </div>
</footer>

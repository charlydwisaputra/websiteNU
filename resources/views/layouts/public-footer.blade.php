{{-- ============================================================
     PUBLIC FOOTER â€” Modern, Clean, Apple-Inspired Light Aesthetic
     Padding bawah ekstra (pb-24) agar tidak tertutup iPhone Dock
     ============================================================ --}}
<footer class="mt-auto border-t border-slate-200/70 bg-white/70 backdrop-blur-md text-slate-700 pb-12 pt-12 sm:pt-16">
    <div class="mx-auto grid max-w-5xl gap-10 px-4 sm:px-6 md:grid-cols-4">
        <div class="md:col-span-2">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[#087A4B] to-[#065C39] text-xs font-black text-white shadow-sm shadow-emerald-900/15">
                    NU
                </div>
                <span class="text-base font-black tracking-tight text-slate-900">
                    NU <span class="text-[#087A4B]">CIKEMBULAN</span>
                </span>
            </div>
            <p class="mt-3.5 max-w-md text-xs sm:text-sm leading-relaxed text-slate-500 font-normal">
                Pusat integrasi data, pelayanan jamaah, dan transformasi digital ekosistem Nahdlatul Ulama Cikembulan yang terbuka, akuntabel, dan berkeadaban.
            </p>
        </div>

        <div>
            <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#087A4B]">Navigasi</h2>
            <div class="mt-3.5 flex flex-col gap-2.5 text-xs sm:text-sm text-slate-600 font-medium">
                <a href="{{ route('home') }}" class="transition hover:text-[#087A4B]">Beranda</a>
                <a href="{{ route('anggota') }}" class="transition hover:text-[#087A4B]">Anggota</a>
                <a href="{{ route('galeri') }}" class="transition hover:text-[#087A4B]">Dokumentasi</a>
                <a href="{{ route('berita.index') }}" class="transition hover:text-[#087A4B]">Berita</a>
            </div>
        </div>

        <div>
            <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#087A4B]">Informasi</h2>
            <div class="mt-3.5 flex flex-col gap-2.5 text-xs sm:text-sm text-slate-600 font-normal">
                <span class="text-slate-800 font-medium">Pengurus Ranting NU Cikembulan</span>
                <span class="text-slate-500">Kec. Andir, Kota Bandung</span>
                <span class="text-slate-500">Jawa Barat, Indonesia</span>
            </div>
        </div>
    </div>

    <div class="mt-12 border-t border-slate-100">
        <div class="mx-auto max-w-5xl px-4 pt-6 text-xs text-slate-400 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>&copy; {{ date('Y') }} Nahdlatul Ulama Cikembulan. Hak Cipta Dilindungi.</span>
            <span class="text-[11px] text-slate-400">Transformasi Ekosistem Digital Berkelanjutan</span>
        </div>
    </div>
</footer>

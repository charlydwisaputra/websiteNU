<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang — {{ config('organisasi.nama') }}</title>
    <x-seo-meta
        :title="'Tentang — ' . config('organisasi.nama')"
        :description="'Pengurus Ranting Nahdlatul Ulama Kalisari — sejarah, visi misi, pengurus, dan kegiatan rutin.'"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.theme-script')
</head>
<body class="font-sans antialiased flex flex-col min-h-screen overflow-x-hidden"
      style="background:var(--bg-base);color:var(--text-primary)">

    @include('layouts.public-navigation')

    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-24 flex-grow w-full">
        <!-- PAGE HEADER -->
        <header class="mb-16 pb-10 text-left border-b border-black/5 dark:border-white/5">
            <h1 class="font-serif text-[clamp(2.5rem,6vw,5rem)] font-medium tracking-tight mb-6" style="color:var(--text-primary)">
                Tentang Kami
            </h1>
            <p class="text-lg md:text-xl leading-relaxed max-w-3xl font-light" style="color:var(--text-secondary)">
                Kami adalah pengurus dan warga Nahdlatul Ulama Ranting Kalisari. Bersama merawat tradisi, memberdayakan umat, dan menghidupkan nilai-nilai Ahlussunnah wal Jamaah di tengah masyarakat.
            </p>
        </header>

        <!-- CONTENT GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
            
            <div class="lg:col-span-8 flex flex-col gap-12">
                
                <section>
                    <h2 class="font-serif text-3xl font-medium mb-4" style="color:var(--text-primary)">Sejarah Singkat</h2>
                    <div class="prose prose-lg dark:prose-invert max-w-none text-base leading-relaxed" style="color:var(--text-secondary)">
                        <p>
                            Pengurus Ranting Nahdlatul Ulama Kalisari merupakan representasi organisasi NU di tingkat desa yang berperan mengoordinasikan kehidupan keagamaan, sosial, dan kemasyarakatan bagi warga Nahdliyin di wilayah Kalisari, Kecamatan Cilongok, Kabupaten Banyumas, Jawa Tengah.
                        </p>
                        <p>
                            Sejak awal berdirinya, ranting ini menjadi wadah bagi para kiai, ustadz, dan tokoh masyarakat setempat untuk bersama-sama merawat tradisi keagamaan Ahlussunnah wal Jamaah. Berbagai kegiatan seperti pengajian, tahlilan, dan musyawarah rutin menjadi bagian tak terpisahkan dari perjalanan organisasi ini.
                        </p>
                        <p>
                            Seiring perkembangan zaman, NU Ranting Kalisari terus beradaptasi dengan memanfaatkan teknologi informasi untuk memperluas jangkauan dakwah dan memperkuat silaturahmi antarwarga, tanpa meninggalkan nilai-nilai tradisi yang telah mengakar di masyarakat.
                        </p>
                    </div>
                </section>

                <section>
                    <h2 class="font-serif text-3xl font-medium mb-4" style="color:var(--text-primary)">Visi & Misi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-6 rounded-2xl bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/5">
                            <h3 class="font-serif text-2xl font-medium mb-3">Visi</h3>
                            <p class="text-sm font-normal leading-relaxed" style="color:var(--text-secondary)">
                                Terwujudnya masyarakat Kalisari yang religius, berkeadilan, dan sejahtera berdasarkan nilai-nilai Ahlussunnah wal Jamaah.
                            </p>
                        </div>
                        <div class="p-6 rounded-2xl bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/5">
                            <h3 class="font-serif text-2xl font-medium mb-3">Misi</h3>
                            <ol class="list-decimal pl-4 space-y-2 text-sm font-normal leading-relaxed" style="color:var(--text-secondary)">
                                <li>Memperkuat kehidupan keagamaan dan tradisi Ahlussunnah wal Jamaah di lingkungan masyarakat.</li>
                                <li>Meningkatkan kualitas pendidikan dan pemberdayaan umat melalui kegiatan dakwah dan pelatihan.</li>
                                <li>Membangun solidaritas sosial melalui kegiatan gotong royong, bakti sosial, dan santunan.</li>
                                <li>Mengembangkan kader-kader organisasi yang kompeten, berintegritas, dan siap mengabdi.</li>
                            </ol>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="font-serif text-3xl font-medium mb-4" style="color:var(--text-primary)">Susunan Pengurus</h2>
                    <div class="prose prose-lg dark:prose-invert max-w-none text-base leading-relaxed mb-6" style="color:var(--text-secondary)">
                        <p>Periode kepengurusan Ranting NU Kalisari masa bakti saat ini.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @php
                            $pengurusTentang = [
                                ['nama' => 'KH. Ahmad Fauzan',   'jabatan' => 'Rais Syuriyah'],
                                ['nama' => 'H. Mukhtar Sholeh',   'jabatan' => 'Ketua Tanfidziyah'],
                                ['nama' => 'Siti Nurjanah',        'jabatan' => 'Sekretaris'],
                                ['nama' => 'Hj. Aminah Zahra',     'jabatan' => 'Bendahara'],
                            ];
                        @endphp
                        @foreach($pengurusTentang as $p)
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/5">
                            <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-900/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-serif text-xl font-medium">
                                {{ strtoupper(substr($p['nama'], 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium" style="color:var(--text-primary)">{{ $p['nama'] }}</p>
                                <p class="text-xs" style="color:var(--text-muted)">{{ $p['jabatan'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-6 flex justify-start">
                        <a href="{{ route('anggota') }}" class="inline-flex btn-active-scale items-center justify-center px-6 py-2.5 bg-emerald-50 dark:bg-emerald-900/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/30 rounded-xl text-sm font-medium transition-colors hover:bg-emerald-100 dark:hover:bg-emerald-900/30">
                            Lihat semua anggota &rarr;
                        </a>
                    </div>
                </section>
                
                <section>
                    <h2 class="font-serif text-3xl font-medium mb-4" style="color:var(--text-primary)">Kegiatan Rutin</h2>
                    <div class="space-y-3">
                        @php
                            $kegiatanRutin = [
                                ['nama' => 'Pengajian Selapanan', 'detail' => 'Seluruh warga — Mushola Al-Hikmah', 'jadwal' => 'Setiap Ahad Legi'],
                                ['nama' => 'Yasinan & Tahlilan',  'detail' => 'Bapak-bapak — bergilir per RT',    'jadwal' => 'Setiap Kamis malam'],
                                ['nama' => 'Rapat Pengurus',      'detail' => 'Pengurus ranting — Balai Desa',     'jadwal' => 'Minggu ke-2 setiap bulan'],
                            ];
                        @endphp
                        @foreach($kegiatanRutin as $k)
                        <div class="flex flex-col sm:flex-row sm:justify-between py-3 border-b border-black/5 dark:border-white/5 gap-2">
                            <div>
                                <h4 class="font-medium text-base" style="color:var(--text-primary)">{{ $k['nama'] }}</h4>
                                <p class="text-sm" style="color:var(--text-secondary)">{{ $k['detail'] }}</p>
                            </div>
                            <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400 text-left sm:text-right">
                                {{ $k['jadwal'] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

            </div>
            
            <div class="lg:col-span-4">
                <div class="sticky top-32 p-6 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/5">
                    <h3 class="font-serif text-2xl font-medium mb-6" style="color:var(--text-primary)">Kontak & Lokasi</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:var(--text-muted)">Alamat</p>
                            <p class="text-sm leading-relaxed" style="color:var(--text-secondary)">{{ config('organisasi.alamat') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:var(--text-muted)">Email</p>
                            <a href="mailto:{{ config('organisasi.email') }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline">
                                {{ config('organisasi.email') }}
                            </a>
                        </div>
                        
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Media Sosial</p>
                            <div class="flex gap-4">
                                <a href="{{ config('organisasi.sosial.facebook') }}" class="text-sm hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" style="color:var(--text-secondary)">Facebook</a>
                                <a href="{{ config('organisasi.sosial.instagram') }}" class="text-sm hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" style="color:var(--text-secondary)">Instagram</a>
                                <a href="{{ config('organisasi.sosial.youtube') }}" class="text-sm hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" style="color:var(--text-secondary)">YouTube</a>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-black/5 dark:border-white/5 flex flex-col gap-3">
                            @if(config('site.whatsapp'))
                            <a href="https://wa.me/{{ config('site.whatsapp') }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 min-h-[44px] bg-green-600 hover:bg-green-700 rounded-xl text-sm font-medium transition-colors text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                Hubungi via WhatsApp
                            </a>
                            @endif
                            <a href="https://maps.google.com/?q={{ urlencode(config('organisasi.alamat')) }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 min-h-[44px] bg-black/5 dark:bg-white/5 hover:bg-black/10 dark:hover:bg-white/10 rounded-xl text-sm font-medium transition-colors" style="color:var(--text-primary)">
                                Buka di Peta &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('layouts.public-footer')
</body>
</html>

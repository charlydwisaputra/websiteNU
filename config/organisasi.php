<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Identitas Umum Organisasi
    |--------------------------------------------------------------------------
    | Ubah teks di sini. Setelah mengubah, jalankan: php artisan config:clear
    */
    'nama' => 'NU Kalisari',
    'deskripsi' => 'Ruang digital untuk mengenal, terhubung, dan bergerak bersama masyarakat Kalisari.',
    'alamat' => 'Desa Kalisari, Kec. Cilongok, Kab. Banyumas, Jawa Tengah',
    'email' => 'halo@nukalisari.id',
    'lokasi' => ['Kalisari', 'Cilongok', 'Banyumas', 'Jawa Tengah'],
    'lokasi_lat' => env('ORGANISASI_LAT', -7.4218),
    'lokasi_lng' => env('ORGANISASI_LNG', 109.1608),

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Peta Lokasi Sekretariat
    |--------------------------------------------------------------------------
    | Koordinat dan tingkat perbesaran (zoom) peta pada halaman Tentang.
    | Ubah data di sini. Setelah mengubah, jalankan: php artisan config:clear
    */
    'peta' => [
        'lat' => env('ORGANISASI_LAT', -7.4218),
        'lng' => env('ORGANISASI_LNG', 109.1608),
        'zoom' => 14,
    ],

    /*
    |--------------------------------------------------------------------------
    | Konten Halaman Tentang: Deskripsi Banner
    |--------------------------------------------------------------------------
    | Kalimat pembuka pada banner utama halaman Tentang Kami.
    | Ubah teks di sini. Setelah mengubah, jalankan: php artisan config:clear
    */
    'tentang_deskripsi' => 'Kami adalah pengurus dan warga Nahdlatul Ulama Ranting Kalisari. Bersama merawat tradisi, memberdayakan umat, dan menghidupkan nilai-nilai Ahlussunnah wal Jamaah di tengah masyarakat.',

    /*
    |--------------------------------------------------------------------------
    | Konten Halaman Tentang: Sejarah Singkat
    |--------------------------------------------------------------------------
    | Paragraf-paragraf sejarah perkembangan Ranting NU Kalisari.
    | Ubah teks di sini. Setelah mengubah, jalankan: php artisan config:clear
    */
    'sejarah' => [
        'Pengurus Ranting Nahdlatul Ulama Kalisari merupakan representasi organisasi NU di tingkat desa yang berperan mengoordinasikan kehidupan keagamaan, sosial, dan kemasyarakatan bagi warga Nahdliyin di wilayah Kalisari, Kecamatan Cilongok, Kabupaten Banyumas, Jawa Tengah.',
        'Sejak awal berdirinya, ranting ini aktif menggerakkan berbagai kegiatan keagamaan seperti pengajian, tahlilan, ziarah, dan musyawarah rutin sebagai pilar penguat silaturahmi antarwarga.',
        'Seiring perkembangan zaman, NU Ranting Kalisari terus beradaptasi dengan memanfaatkan teknologi informasi untuk memperluas jangkauan dakwah dan memperkuat pelayanan sosial, tanpa meninggalkan nilai-nilai tradisi mulia yang telah mengakar.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Konten Halaman Tentang: Kutipan Sejarah
    |--------------------------------------------------------------------------
    | Kutipan yang disorot di dalam kotak kutipan seksi Sejarah.
    | Ubah teks di sini. Setelah mengubah, jalankan: php artisan config:clear
    */
    'sejarah_kutipan' => 'Wadah bagi para kiai, ustadz, dan tokoh masyarakat setempat untuk bersama-sama merawat tradisi keagamaan Ahlussunnah wal Jamaah.',

    /*
    |--------------------------------------------------------------------------
    | Konten Halaman Tentang: Visi & Misi
    |--------------------------------------------------------------------------
    | Pernyataan visi dan butir-butir misi organisasi.
    | Ubah teks di sini. Setelah mengubah, jalankan: php artisan config:clear
    */
    'visi' => 'Terwujudnya masyarakat Kalisari yang religius, berkeadilan, dan sejahtera berdasarkan nilai-nilai Ahlussunnah wal Jamaah.',

    'misi' => [
        'Memperkuat kehidupan keagamaan dan tradisi Ahlussunnah wal Jamaah di lingkungan masyarakat.',
        'Meningkatkan kualitas pendidikan dan pemberdayaan umat melalui dakwah dan pelatihan.',
        'Membangun solidaritas sosial melalui kegiatan gotong royong, bakti sosial, dan santunan.',
        'Mengembangkan kader-kader organisasi yang kompeten, berintegritas, dan siap mengabdi.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Sosial
    |--------------------------------------------------------------------------
    | Tautan akun media sosial resmi organisasi.
    | Ubah data di sini. Setelah mengubah, jalankan: php artisan config:clear
    */
    'sosial' => [
        'facebook' => 'https://facebook.com/#',
        'instagram' => 'https://instagram.com/#',
        'youtube' => 'https://youtube.com/#',
    ],

    /*
    |--------------------------------------------------------------------------
    | Badan Otonom (Banom)
    |--------------------------------------------------------------------------
    | Data bawaan untuk 6 badan otonom NU.
    | Ubah data di sini. Setelah mengubah, jalankan: php artisan config:clear
    */
    'banom' => [
        [
            'nama' => 'Muslimat NU',
            'deskripsi' => 'Wadah perjuangan perempuan NU dalam membangun keluarga dan masyarakat yang religius, cerdas, dan sejahtera.',
            'singkatan' => 'Muslimat',
            'logo' => 'MuslimatNU.png',
            'koordinator' => 'Hj. Siti Rohmah',
        ],
        [
            'nama' => 'Fatayat NU',
            'deskripsi' => 'Gerakan perempuan muda NU yang berfokus pada pemberdayaan, pendidikan, dan advokasi sosial kemasyarakatan.',
            'singkatan' => 'Fatayat',
            'logo' => 'FATAYAT-NU.png',
            'koordinator' => 'Dewi Lestari',
        ],
        [
            'nama' => 'GP Ansor',
            'deskripsi' => 'Barisan pemuda pengawal ulama dan NKRI yang berkomitmen pada pengembangan kader tangguh dan berintegritas.',
            'singkatan' => 'GP Ansor',
            'logo' => 'GP-Ansor.png',
            'koordinator' => 'Fikri Ramadhan',
        ],
        [
            'nama' => 'IPNU',
            'deskripsi' => 'Ikatan Pelajar Nahdlatul Ulama yang mewadahi pelajar dan santri dalam mengembangkan potensi diri dan organisasi.',
            'singkatan' => 'IPNU',
            'logo' => 'IPNU.png',
            'koordinator' => 'Rizki Aditya',
        ],
        [
            'nama' => 'IPPNU',
            'deskripsi' => 'Ikatan Pelajar Putri Nahdlatul Ulama, wadah pelajar putri untuk belajar, berjuang, dan bertakwa.',
            'singkatan' => 'IPPNU',
            'logo' => 'IPPNU.png',
            'koordinator' => 'Anisa Nur Fadilah',
        ],
        [
            'nama' => 'PMII',
            'deskripsi' => 'Pergerakan Mahasiswa Islam Indonesia, wadah mahasiswa berasaskan Islam Ahlussunnah wal Jamaah.',
            'singkatan' => 'PMII',
            'logo' => 'PMII.png',
            'koordinator' => 'Hendra Kusuma',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Kegiatan Rutin Organisasi
    |--------------------------------------------------------------------------
    | Agenda berkala masyarakat dan pengurus (icon: calendar | clock | lokasi).
    | Ubah data di sini. Setelah mengubah, jalankan: php artisan config:clear
    */
    'kegiatan_rutin' => [
        [
            'nama' => 'Pengajian Selapanan',
            'peserta' => 'Seluruh warga',
            'lokasi' => 'Mushola Al-Hikmah',
            'jadwal' => 'Setiap Ahad Legi',
            'icon' => 'calendar',
        ],
        [
            'nama' => 'Yasinan & Tahlilan',
            'peserta' => 'Bapak-bapak',
            'lokasi' => 'Bergilir per RT',
            'jadwal' => 'Setiap Kamis malam',
            'icon' => 'clock',
        ],
        [
            'nama' => 'Rapat Pengurus',
            'peserta' => 'Pengurus ranting',
            'lokasi' => 'Balai Desa Kalisari',
            'jadwal' => 'Minggu ke-2 setiap bulan',
            'icon' => 'lokasi',
        ],
    ],
];

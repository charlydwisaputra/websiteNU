<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Navigation Configuration
    |--------------------------------------------------------------------------
    |
    | Sumber data tunggal untuk navigasi panel admin NU Kalisari.
    | Digunakan secara konsisten oleh sidebar desktop, drawer mobile,
    | dan bottom navigation bar di HP.
    |
    */

    'menu' => [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'match' => 'admin.dashboard',
            'mobile_bar' => true,
            'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'title' => 'Dashboard Utama Admin',
            'aria_label' => 'Buka Dashboard Admin',
        ],
        [
            'key' => 'agenda',
            'label' => 'Agenda',
            'route' => 'admin.agenda.index',
            'match' => 'admin.agenda.*',
            'mobile_bar' => false,
            'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            'title' => 'Kelola Agenda Kegiatan',
            'aria_label' => 'Buka Kelola Agenda Kegiatan',
        ],
        [
            'key' => 'berita',
            'label' => 'Berita',
            'route' => 'admin.berita.index',
            'match' => 'admin.berita.*',
            'mobile_bar' => true,
            'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z',
            'title' => 'Kelola Data Berita',
            'aria_label' => 'Buka Kelola Data Berita',
        ],
        [
            'key' => 'anggota',
            'label' => 'Anggota',
            'route' => 'admin.anggota.index',
            'match' => 'admin.anggota.*',
            'mobile_bar' => true,
            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
            'title' => 'Kelola Data Anggota',
            'aria_label' => 'Buka Data Anggota',
        ],
        [
            'key' => 'galeri',
            'label' => 'Galeri',
            'route' => 'admin.galeri.index',
            'match' => 'admin.galeri.*',
            'mobile_bar' => true,
            'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
            'title' => 'Kelola Galeri Dokumentasi',
            'aria_label' => 'Buka Galeri Dokumentasi',
        ],
        [
            'key' => 'banom',
            'label' => 'Badan Otonom',
            'route' => 'admin.banom.index',
            'match' => 'admin.banom.*',
            'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
            'title' => 'Kelola Badan Otonom & Ekosistem',
            'aria_label' => 'Buka Badan Otonom dan Ekosistem',
        ],
        [
            'key' => 'pengurus',
            'label' => 'Pengurus Inti',
            'route' => 'admin.pengurus.index',
            'fallback_route' => 'admin.pengaturan.index',
            'match' => 'admin.pengurus.*',
            'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
            'title' => 'Kelola Data Pengurus Inti',
            'aria_label' => 'Buka Data Pengurus Inti',
        ],
        [
            'key' => 'pengaturan',
            'label' => 'Pengaturan',
            'route' => 'admin.pengaturan.index',
            'match' => 'admin.pengaturan.*',
            'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
            'title' => 'Pengaturan Situs & Organisasi',
            'aria_label' => 'Buka Pengaturan Situs',
        ],
    ],

    'footer' => [
        [
            'key' => 'profil',
            'label' => 'Profil',
            'route' => 'admin.profil',
            'fallback_route' => 'profile.edit',
            'match' => 'admin.profil*',
            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'title' => 'Pengaturan Profil Pengguna',
            'aria_label' => 'Buka Profil Akun Saya',
        ],
        [
            'key' => 'keluar',
            'label' => 'Keluar',
            'route' => 'logout',
            'method' => 'POST',
            'icon' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
            'title' => 'Keluar dari Sesi Admin',
            'aria_label' => 'Keluar dari Akun',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Mobile Bottom Bar Items
    |--------------------------------------------------------------------------
    | Navigasi bawah untuk akses cepat dengan satu tangan di layar ponsel.
    */
    'bottom_bar' => [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'match' => 'admin.dashboard',
            'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'title' => 'Dashboard',
            'aria_label' => 'Buka Dashboard',
        ],
        [
            'key' => 'berita',
            'label' => 'Berita',
            'route' => 'admin.berita.index',
            'match' => 'admin.berita.*',
            'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z',
            'title' => 'Berita',
            'aria_label' => 'Buka Berita',
        ],
        [
            'key' => 'anggota',
            'label' => 'Anggota',
            'route' => 'admin.anggota.index',
            'match' => 'admin.anggota.*',
            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
            'title' => 'Anggota',
            'aria_label' => 'Buka Anggota',
        ],
        [
            'key' => 'galeri',
            'label' => 'Galeri',
            'route' => 'admin.galeri.index',
            'match' => 'admin.galeri.*',
            'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
            'title' => 'Galeri',
            'aria_label' => 'Buka Galeri',
        ],
        [
            'key' => 'lainnya',
            'label' => 'Lainnya',
            'action' => 'open-drawer',
            'icon' => 'M4 6h16M4 12h16M4 18h16',
            'title' => 'Buka Menu Lainnya',
            'aria_label' => 'Buka Menu Lengkap',
        ],
    ],
];

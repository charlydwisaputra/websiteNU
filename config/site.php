<?php

return [
    'whatsapp' => env('SITE_WHATSAPP', ''),

    /*
    |--------------------------------------------------------------------------
    | Jadwal Salat (Aladhan API)
    |--------------------------------------------------------------------------
    */
    'prayer' => [
        'enabled' => env('SITE_PRAYER_ENABLED', true),
        'latitude' => env('SITE_PRAYER_LAT', -7.4218),
        'longitude' => env('SITE_PRAYER_LNG', 109.1608),
        'method' => env('SITE_PRAYER_METHOD', 20), // 20 = Kemenag RI
        'timezone' => 'Asia/Jakarta',
        'hijri_adjustment' => env('SITE_HIJRI_ADJUSTMENT', 0), // -1, 0, or 1
    ],
];

<?php

use App\Models\Anggota;
use App\Models\Banom;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('brand logo files exist in public/images/brand and are not empty', function () {
    $brandFiles = [
        public_path('images/brand/logo-nu.svg'),
        public_path('images/brand/logo-nu.png'),
        public_path('images/brand/logo-nu-sm.png'),
        public_path('images/brand/favicon.ico'),
        public_path('favicon.ico'),
    ];

    foreach ($brandFiles as $file) {
        expect(file_exists($file))->toBeTrue("File {$file} must exist");
        expect(filesize($file))->toBeGreaterThan(0, "File {$file} must not be empty");
    }
});

test('default 6 banom logos exist in public/images/banom and are not empty', function () {
    $defaultBanoms = [
        'MuslimatNU.png',
        'FATAYAT-NU.png',
        'GP-Ansor.png',
        'IPNU.png',
        'IPPNU.png',
        'PMII.png',
    ];

    foreach ($defaultBanoms as $logo) {
        $path = public_path('images/banom/'.$logo);
        expect(file_exists($path))->toBeTrue("Logo {$logo} must exist in public/images/banom");
        expect(filesize($path))->toBeGreaterThan(0, "Logo {$logo} must not be empty");
    }
});

test('banom resolveLogoUrl falls back gracefully to public images or brand logo', function () {
    // 1. Logo null, singkatan ada di public/images/banom -> fallback ke public/images/banom/
    $urlBySingkatan = Banom::resolveLogoUrl(null, 'ipnu');
    expect($urlBySingkatan)->toContain('images/banom/');

    // 2. Singkatan tidak dikenal & storage kosong -> fallback ke brand logo
    $fallbackUrl = Banom::resolveLogoUrl('nonexistent-logo.png', 'UNKNOWN_ORG_XYZ');
    expect($fallbackUrl)->toContain('images/brand/logo-nu.png');

    // 3. Jika file ada di storage disk public -> URL menuju storage (prioritas tertinggi)
    Storage::fake('public');
    Storage::disk('public')->put('logo-organisasi/custom-banom.png', 'dummy');
    $storageUrl = Banom::resolveLogoUrl('custom-banom.png', 'CUSTOM');
    expect($storageUrl)->toContain('logo-organisasi/custom-banom.png');

    // 4. Setelah Storage::fake, GP-Ansor.png tidak ada di storage fake -> public/images/banom/
    $url = Banom::resolveLogoUrl('GP-Ansor.png', 'GP Ansor');
    expect($url)->toContain('images/banom/');
});

test('public pages and admin print cards load brand logo without broken references', function () {
    $this->seed(DatabaseSeeder::class);

    // 1. Beranda
    $responseHome = $this->get(route('home'));
    $responseHome->assertOk();
    $responseHome->assertSee('images/brand/logo-nu.png');

    // 2. Tentang
    $responseTentang = $this->get(route('tentang'));
    $responseTentang->assertOk();
    $responseTentang->assertSee('images/brand/logo-nu.png');

    // 3. Ekosistem
    $responseEkosistem = $this->get(route('ekosistem'));
    $responseEkosistem->assertOk();

    // 4. Login
    $responseLogin = $this->get(route('login'));
    $responseLogin->assertOk();
    $responseLogin->assertSee('images/brand/logo-nu.png');

    // 5. Cetak kartu admin
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $anggota = Anggota::first();

    $responsePrint = $this->actingAs($admin)->get(route('admin.anggota.print-cards'));
    $responsePrint->assertOk();
    $responsePrint->assertSee('images/brand/logo-nu.png');

    $responseSingle = $this->actingAs($admin)->get(route('admin.anggota.print-single', $anggota->id));
    $responseSingle->assertOk();
    $responseSingle->assertSee('images/brand/logo-nu.png');
});

test('all 6 banoms render distinct logo urls and do not all resolve to default brand logo', function () {
    $this->seed(DatabaseSeeder::class);

    $banoms = Banom::all();
    expect($banoms->count())->toBeGreaterThanOrEqual(6);

    $urls = $banoms->map(fn (Banom $b) => $b->logo_url)->all();

    // Pastikan semua URL unik
    $uniqueUrls = array_unique($urls);
    expect(count($uniqueUrls))->toBe($banoms->count(), 'Each banom must have a distinct logo URL');

    // Pastikan tidak ada yang jatuh ke logo-nu.png default
    foreach ($urls as $url) {
        expect($url)->not->toBe(asset('images/brand/logo-nu.png'));
    }

    // Periksa bahwa di halaman Tentang, logo tiap banom ter-render
    $response = $this->get(route('tentang'));
    $response->assertOk();
    foreach ($banoms as $banom) {
        $response->assertSee(basename($banom->logo));
    }
});

test('halaman tentang tidak membocorkan kode javascript peta sebagai teks dan layout utuh', function () {
    $this->seed(DatabaseSeeder::class);

    $response = $this->get(route('tentang'));
    $response->assertOk();

    $content = $response->getContent();

    // Hapus blok script dan style untuk memeriksa teks HTML yang dirender
    $bodyWithoutScripts = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content);
    $bodyWithoutScripts = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $bodyWithoutScripts);

    // Memastikan tidak ada potongan kode JS atau ikon yang bocor ke teks
    expect($bodyWithoutScripts)->not->toContain('iconSize')
        ->and($bodyWithoutScripts)->not->toContain('L.marker')
        ->and($bodyWithoutScripts)->not->toContain('iconAnchor')
        ->and($bodyWithoutScripts)->not->toContain('popupAnchor');

    // Memastikan tombol interaksi peta dan kartu Kontak & Lokasi ter-render
    expect($content)->toContain('x-data="mapEmbed(')
        ->and($content)->toContain('pin-hijau.svg')
        ->and($content)->toContain('Ketuk untuk membuka interaksi peta')
        ->and($content)->toContain('Kunci peta');
});

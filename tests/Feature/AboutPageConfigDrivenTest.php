<?php

use App\Models\Anggota;

/*
|--------------------------------------------------------------------------
| Halaman Tentang — Test Konten Berbasis Config
|--------------------------------------------------------------------------
| Memastikan semua teks statis pada /tentang bersumber dari
| config/organisasi.php, bukan hardcode di view, dan bahwa data
| pengurus diambil murni dari tabel `anggota`.
*/

test('halaman tentang dapat diakses dan mengembalikan 200', function () {
    $this->get(route('tentang'))->assertStatus(200);
});

test('teks deskripsi banner berasal dari config organisasi.tentang_deskripsi', function () {
    config(['organisasi.tentang_deskripsi' => 'DESKRIPSI_UNIK_TEST_BANNER']);

    $this->get(route('tentang'))
        ->assertStatus(200)
        ->assertSee('DESKRIPSI_UNIK_TEST_BANNER');
});

test('paragraf sejarah berasal dari config organisasi.sejarah', function () {
    config(['organisasi.sejarah' => [
        'Paragraf pertama sejarah khusus test.',
        'Paragraf kedua sejarah khusus test.',
    ]]);

    $response = $this->get(route('tentang'));

    $response->assertSee('Paragraf pertama sejarah khusus test.');
    $response->assertSee('Paragraf kedua sejarah khusus test.');
});

test('kutipan sejarah berasal dari config organisasi.sejarah_kutipan bukan site_settings', function () {
    config(['organisasi.sejarah_kutipan' => 'Kutipan Sejarah Khas Test']);

    $this->get(route('tentang'))
        ->assertStatus(200)
        ->assertSee('Kutipan Sejarah Khas Test');
});

test('teks visi berasal dari config organisasi.visi', function () {
    config(['organisasi.visi' => 'Visi Unik Untuk Test Ini']);

    $this->get(route('tentang'))
        ->assertStatus(200)
        ->assertSee('Visi Unik Untuk Test Ini');
});

test('butir misi berasal dari config organisasi.misi', function () {
    config(['organisasi.misi' => [
        'Misi pertama test.',
        'Misi kedua test.',
    ]]);

    $response = $this->get(route('tentang'));

    $response->assertSee('Misi pertama test.');
    $response->assertSee('Misi kedua test.');
});

test('pengurus yang ditampilkan diambil dari tabel anggota is_pengurus true status aktif', function () {
    Anggota::factory()->create([
        'name' => 'KH. Uji Rais Syuriyah',
        'position' => 'Rais Syuriyah',
        'is_pengurus' => true,
        'status' => 'Aktif',
        'urutan' => 1,
    ]);

    Anggota::factory()->create([
        'name' => 'H. Uji Ketua Tanfidziyah',
        'position' => 'Ketua Tanfidziyah',
        'is_pengurus' => true,
        'status' => 'Aktif',
        'urutan' => 2,
    ]);

    $response = $this->get(route('tentang'));

    $response->assertSee('KH. Uji Rais Syuriyah');
    $response->assertSee('Rais Syuriyah');
    $response->assertSee('H. Uji Ketua Tanfidziyah');
    $response->assertSee('Ketua Tanfidziyah');
});

test('pengurus dengan jabatan kosong tidak ditampilkan', function () {
    Anggota::factory()->create([
        'name' => 'Anggota Tanpa Jabatan',
        'position' => '',
        'is_pengurus' => true,
        'status' => 'Aktif',
        'urutan' => 1,
    ]);

    $this->get(route('tentang'))
        ->assertStatus(200)
        ->assertDontSee('Anggota Tanpa Jabatan');
});

test('pengurus tidak aktif tidak ditampilkan', function () {
    Anggota::factory()->create([
        'name' => 'Pengurus Non-Aktif Test',
        'position' => 'Rais Syuriyah',
        'is_pengurus' => true,
        'status' => 'Tidak Aktif',
        'urutan' => 1,
    ]);

    $this->get(route('tentang'))
        ->assertStatus(200)
        ->assertDontSee('Pengurus Non-Aktif Test');
});

test('pengurus syuriyah muncul sebelum tanfidziyah sesuai urutan', function () {
    Anggota::factory()->create([
        'name' => 'Urutan Pertama Rais',
        'position' => 'Rais Syuriyah',
        'is_pengurus' => true,
        'status' => 'Aktif',
        'urutan' => 1,
    ]);

    Anggota::factory()->create([
        'name' => 'Urutan Kedua Ketua',
        'position' => 'Ketua Tanfidziyah',
        'is_pengurus' => true,
        'status' => 'Aktif',
        'urutan' => 2,
    ]);

    $html = $this->get(route('tentang'))->getContent();

    expect(strpos($html, 'Urutan Pertama Rais'))
        ->toBeLessThan(strpos($html, 'Urutan Kedua Ketua'));
});

test('aria-label kartu pengurus adalah Lihat daftar anggota bukan nama individu', function () {
    Anggota::factory()->create([
        'name' => 'KH. Uji Aria Label',
        'position' => 'Rais Syuriyah',
        'is_pengurus' => true,
        'status' => 'Aktif',
        'urutan' => 1,
    ]);

    $response = $this->get(route('tentang'));

    $response->assertSee('aria-label="Lihat daftar anggota"', false);
    $response->assertDontSee('aria-label="Lihat profil anggota KH. Uji Aria Label"', false);
});

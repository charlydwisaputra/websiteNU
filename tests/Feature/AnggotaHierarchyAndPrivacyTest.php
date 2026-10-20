<?php

use App\Models\Anggota;
use App\Models\Banom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('direktori anggota mengurutkan berdasarkan hierarki: Syuriyah, Tanfidziyah, Banom, lalu Anggota Lain', function () {
    $banomAnsor = Banom::create([
        'nama' => 'Gerakan Pemuda Ansor',
        'singkatan' => 'GP Ansor',
        'slug' => 'gp-ansor',
        'urutan' => 3,
    ]);

    // 1. Anggota lain (urutan 0)
    $anggotaBiasa = Anggota::create([
        'name' => 'Zulham Efendi',
        'position' => 'Anggota',
        'status' => 'Aktif',
        'is_public' => true,
        'is_pengurus' => false,
        'urutan' => 0,
    ]);

    // 2. Koordinator Banom (urutan 0)
    $ketuaAnsor = Anggota::create([
        'name' => 'Fikri Ramadhan',
        'position' => 'Ketua GP Ansor',
        'banom_id' => $banomAnsor->id,
        'status' => 'Aktif',
        'is_public' => true,
        'is_pengurus' => false,
        'urutan' => 0,
    ]);

    // 3. Tanfidziyah (urutan 2)
    $ketuaTanfidziyah = Anggota::create([
        'name' => 'H. Mukhtar Sholeh',
        'position' => 'Ketua Tanfidziyah',
        'status' => 'Aktif',
        'is_public' => true,
        'is_pengurus' => true,
        'urutan' => 2,
    ]);

    // 4. Syuriyah (urutan 1)
    $raisSyuriyah = Anggota::create([
        'name' => 'KH. Ahmad Fauzan',
        'position' => 'Rais Syuriyah',
        'status' => 'Aktif',
        'is_public' => true,
        'is_pengurus' => true,
        'urutan' => 1,
    ]);

    $response = $this->get(route('anggota'));
    $response->assertOk();

    // Verifikasi urutan tampilan di HTML
    $content = $response->getContent();
    $posSyuriyah = strpos($content, 'KH. Ahmad Fauzan');
    $posTanfidziyah = strpos($content, 'H. Mukhtar Sholeh');
    $posBanom = strpos($content, 'Fikri Ramadhan');
    $posBiasa = strpos($content, 'Zulham Efendi');

    expect($posSyuriyah)->toBeLessThan($posTanfidziyah);
    expect($posTanfidziyah)->toBeLessThan($posBanom);
    expect($posBanom)->toBeLessThan($posBiasa);
});

test('wilayah anggota disembunyikan secara default dan hanya tampil jika tampil_wilayah true', function () {
    // Anggota 1: tampil_wilayah = false (default)
    $rahasia = Anggota::create([
        'name' => 'Ahmad Rahasia',
        'position' => 'Anggota',
        'region' => 'RT 05 / RW 03 Kalisari',
        'tampil_wilayah' => false,
        'status' => 'Aktif',
        'is_public' => true,
    ]);

    // Anggota 2: tampil_wilayah = true
    $terbuka = Anggota::create([
        'name' => 'Budi Terbuka',
        'position' => 'Anggota',
        'region' => 'RT 01 / RW 02 Kalisari',
        'tampil_wilayah' => true,
        'status' => 'Aktif',
        'is_public' => true,
    ]);

    $response = $this->get(route('anggota'));
    $response->assertOk();

    $response->assertSee('Budi Terbuka');
    $response->assertSee('RT 01 / RW 02 Kalisari');

    $response->assertSee('Ahmad Rahasia');
    $response->assertDontSee('RT 05 / RW 03 Kalisari');
});

test('badge informatif menampilkan nama banom atau Pengurus Ranting dan jabatan lengkap ditampilkan', function () {
    $banomMuslimat = Banom::create([
        'nama' => 'Muslimat NU',
        'singkatan' => 'Muslimat',
        'slug' => 'muslimat',
        'urutan' => 1,
    ]);

    $anggotaBanom = Anggota::create([
        'name' => 'Hj. Siti Rohmah',
        'position' => 'Koordinator Muslimat',
        'banom_id' => $banomMuslimat->id,
        'status' => 'Aktif',
        'is_public' => true,
        'is_pengurus' => false,
    ]);

    $pengurus = Anggota::create([
        'name' => 'Bambang Sulistyo',
        'position' => 'Wakil Ketua',
        'status' => 'Aktif',
        'is_public' => true,
        'is_pengurus' => true,
    ]);

    $response = $this->get(route('anggota'));
    $response->assertOk();

    // Badge Banom
    $response->assertSee('Muslimat');

    // Badge Pengurus Ranting
    $response->assertSee('Pengurus Ranting');

    // Jabatan lengkap: bukan cuma "Wakil Ketua" menggantung
    $response->assertSee('Wakil Ketua Pengurus Ranting');
});

test('admin dapat mengatur checkbox tampil_wilayah pada form create dan edit anggota', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    // 1. Simpan anggota baru dengan tampil_wilayah = 1
    $responseCreate = $this->actingAs($admin)->post(route('admin.anggota.store'), [
        'name' => 'Kader Baru',
        'position' => 'Anggota',
        'region' => 'RT 09 / RW 01',
        'tampil_wilayah' => '1',
        'status' => 'Aktif',
        'is_public' => '1',
    ]);

    $responseCreate->assertRedirect(route('admin.anggota.index'));

    $anggota = Anggota::where('name', 'Kader Baru')->firstOrFail();
    expect($anggota->tampil_wilayah)->toBeTrue();

    // 2. Akses halaman edit memastikan checkbox tampil
    $responseEditPage = $this->actingAs($admin)->get(route('admin.anggota.edit', $anggota->id));
    $responseEditPage->assertOk();
    $responseEditPage->assertSee('Tampilkan wilayah di publik');

    // 3. Update menjadi tampil_wilayah = 0
    $responseUpdate = $this->actingAs($admin)->put(route('admin.anggota.update', $anggota->id), [
        'name' => 'Kader Baru',
        'position' => 'Anggota',
        'region' => 'RT 09 / RW 01',
        'tampil_wilayah' => '0',
        'status' => 'Aktif',
        'is_public' => '1',
    ]);

    $responseUpdate->assertRedirect(route('admin.anggota.index'));

    $anggota->refresh();
    expect($anggota->tampil_wilayah)->toBeFalse();
});

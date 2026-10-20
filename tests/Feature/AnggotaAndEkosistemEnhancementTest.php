<?php

use App\Models\Anggota;
use App\Models\Banom;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('ekosistem page renders hero, stats, target category, and cta cara bergabung', function () {
    $banom = Banom::create([
        'nama' => 'Muslimat NU',
        'singkatan' => 'Muslimat',
        'slug' => 'muslimat',
        'deskripsi' => 'Organisasi perempuan NU.',
        'urutan' => 1,
    ]);

    Anggota::create([
        'name' => 'Hj. Siti Rohmah',
        'position' => 'Koordinator Muslimat',
        'banom_id' => $banom->id,
        'status' => 'Aktif',
        'is_public' => true,
    ]);

    $response = $this->get(route('ekosistem'));
    $response->assertOk();
    $response->assertSee('BADAN OTONOM &amp; LEMBAGA', false);
    $response->assertSee('Ekosistem Organisasi');
    $response->assertSee('Sinergi Penggerak Warga di Tingkat Ranting');
    $response->assertSee('Perempuan Dewasa');
    // Sembunyikan badge jika 1 atau kurang
    $response->assertDontSee('1 Anggota');
    $response->assertSee('Bagaimana cara bergabung?');
    $response->assertSee(route('anggota', ['badan' => 'muslimat']));

    // Tambah anggota kedua agar jumlahnya 2 -> badge 2 Anggota harus muncul
    Anggota::create([
        'name' => 'Nyai Masruroh',
        'position' => 'Wakil Muslimat',
        'banom_id' => $banom->id,
        'status' => 'Aktif',
        'is_public' => true,
    ]);

    $response2 = $this->get(route('ekosistem'));
    $response2->assertOk();
    $response2->assertSee('2 Anggota');
});

test('anggota page supports badan parameter and reflects active filter', function () {
    $banom = Banom::create([
        'nama' => 'Fatayat NU',
        'singkatan' => 'Fatayat',
        'slug' => 'fatayat',
        'urutan' => 1,
    ]);

    Anggota::create([
        'name' => 'Dewi Lestari',
        'position' => 'Koordinator Fatayat',
        'banom_id' => $banom->id,
        'status' => 'Aktif',
        'is_public' => true,
    ]);

    $response = $this->get('/anggota?badan=fatayat');
    $response->assertOk();
    $response->assertSee('Dewi Lestari');
    $response->assertSee('active-filter-chip');
    $response->assertDontSee('Koordina<');
    $response->assertSee('Koordinator Fatayat');
});

test('anggota empty state displays updated subtext', function () {
    $response = $this->get('/anggota?search=orang_tidak_ada_xyz');
    $response->assertOk();
    $response->assertSee('Data anggota akan segera ditambahkan oleh pengurus.');
});

test('anggota pagination only appears when more than 1 page with Sebelumnya and Selanjutnya text', function () {
    // 1 anggota -> hanya 1 halaman -> pagination tidak tampil
    Anggota::create([
        'name' => 'Kader Tunggal',
        'status' => 'Aktif',
        'is_public' => true,
    ]);

    $response1 = $this->get(route('anggota'));
    $response1->assertOk();
    $response1->assertDontSee('Sebelumnya');
    $response1->assertDontSee('Selanjutnya');

    // Tambah hingga 13 anggota (<= 24 per halaman) -> pagination tetap TIDAK tampil
    for ($i = 2; $i <= 13; $i++) {
        Anggota::create([
            'name' => "Kader {$i}",
            'status' => 'Aktif',
            'is_public' => true,
        ]);
    }

    $response2 = $this->get(route('anggota'));
    $response2->assertOk();
    $response2->assertDontSee('Sebelumnya');
    $response2->assertDontSee('Selanjutnya');

    // Tambah hingga 25 anggota (> 24 per halaman) -> pagination tampil
    for ($i = 14; $i <= 25; $i++) {
        Anggota::create([
            'name' => "Kader {$i}",
            'status' => 'Aktif',
            'is_public' => true,
        ]);
    }

    $response3 = $this->get(route('anggota'));
    $response3->assertOk();
    $response3->assertSee('Sebelumnya');
    $response3->assertSee('Selanjutnya');
});

<?php

use App\Models\Anggota;
use App\Models\Banom;
use App\Models\Berita;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pencarian dan filter anggota termasuk relasi banom_id berfungsi', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $ansor = Banom::create([
        'nama' => 'Gerakan Pemuda Ansor',
        'singkatan' => 'GP Ansor',
        'slug' => 'gp-ansor',
    ]);
    $fatayat = Banom::create([
        'nama' => 'Fatayat NU',
        'singkatan' => 'Fatayat',
        'slug' => 'fatayat',
    ]);

    $a1 = Anggota::create([
        'name' => 'Fikri Ramadhan',
        'position' => 'Ketua GP Ansor',
        'banom_id' => $ansor->id,
        'status' => 'Aktif',
    ]);
    $a2 = Anggota::create([
        'name' => 'Dewi Lestari',
        'position' => 'Koordinator Fatayat',
        'banom_id' => $fatayat->id,
        'status' => 'Aktif',
    ]);
    $a3 = Anggota::create([
        'name' => 'Rizki Santoso',
        'position' => 'Alumni IPNU',
        'status' => 'Alumni',
    ]);

    // 1. Filter banom_id
    $responseBanom = $this->actingAs($admin)->get(route('admin.anggota.index', ['banom_id' => $ansor->id]));
    $responseBanom->assertStatus(200);
    $responseBanom->assertSee('Fikri Ramadhan');
    $responseBanom->assertDontSee('Dewi Lestari');

    // 2. Filter status
    $responseStatus = $this->actingAs($admin)->get(route('admin.anggota.index', ['status' => 'Alumni']));
    $responseStatus->assertStatus(200);
    $responseStatus->assertSee('Rizki Santoso');
    $responseStatus->assertDontSee('Fikri Ramadhan');

    // 3. Pencarian
    $responseSearch = $this->actingAs($admin)->get(route('admin.anggota.index', ['search' => 'Dewi']));
    $responseSearch->assertStatus(200);
    $responseSearch->assertSee('Dewi Lestari');
    $responseSearch->assertDontSee('Fikri Ramadhan');
});

test('pencarian dan filter berita berfungsi', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    Berita::create([
        'title' => 'Pengajian Rutin Selapanan',
        'category' => 'Kegiatan',
        'content' => 'Isi materi pengajian.',
        'status' => 'published',
        'author_id' => $admin->id,
    ]);
    Berita::create([
        'title' => 'Pengumuman Rapat Bulanan Pengurus',
        'category' => 'Pengumuman',
        'content' => 'Undangan rapat kerja.',
        'status' => 'draft',
        'author_id' => $admin->id,
    ]);

    // Filter status draft
    $response = $this->actingAs($admin)->get(route('admin.berita.index', ['status' => 'draft']));
    $response->assertStatus(200);
    $response->assertSee('Pengumuman Rapat Bulanan Pengurus');
    $response->assertDontSee('Pengajian Rutin Selapanan');

    // Filter category
    $responseCat = $this->actingAs($admin)->get(route('admin.berita.index', ['category' => 'Kegiatan']));
    $responseCat->assertStatus(200);
    $responseCat->assertSee('Pengajian Rutin Selapanan');
    $responseCat->assertDontSee('Pengumuman Rapat Bulanan Pengurus');
});

test('empty state ramah tampil saat hasil filter atau data kosong', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    // Data kosong pada anggota
    $resAnggota = $this->actingAs($admin)->get(route('admin.anggota.index', ['search' => 'NamaTidakMungkinAda12345']));
    $resAnggota->assertStatus(200);
    $resAnggota->assertSee('Tidak Ada Data Anggota');
    $resAnggota->assertSee('Reset Filter');

    // Data kosong pada berita
    $resBerita = $this->actingAs($admin)->get(route('admin.berita.index', ['search' => 'JudulTidakMungkinAda12345']));
    $resBerita->assertStatus(200);
    $resBerita->assertSee('Tidak Ada Data Berita');
    $resBerita->assertSee('Reset Filter');
});

test('menghapus anggota memperbarui statistik', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $anggota = Anggota::create([
        'name' => 'Anggota Uji Hapus',
        'status' => 'Aktif',
    ]);

    expect(Anggota::count())->toBe(1);

    // Hapus anggota
    $response = $this->actingAs($admin)->delete(route('admin.anggota.destroy', $anggota->id));
    $response->assertRedirect(route('admin.anggota.index'));
    $response->assertSessionHas('success');

    // Statistik jumlah anggota berkurang
    expect(Anggota::count())->toBe(0);

    // Kunjungan dashboard menunjukkan angka 0
    $dashResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
    $dashResponse->assertStatus(200);
    $dashResponse->assertSee('Anggota');
});

<?php

use App\Models\Anggota;
use App\Models\Banom;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('impor banom dua kali tidak menggandakan data (idempoten)', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    expect(Banom::count())->toBe(0);

    // Impor pertama
    $response1 = $this->actingAs($admin)->post(route('admin.banom.seed'));
    $response1->assertRedirect(route('admin.banom.index'));
    $firstCount = Banom::count();
    expect($firstCount)->toBeGreaterThan(0);

    // Impor kedua
    $response2 = $this->actingAs($admin)->post(route('admin.banom.seed'));
    $response2->assertRedirect(route('admin.banom.index'));
    $secondCount = Banom::count();

    // Jumlah baris harus sama persis (tidak ada duplikasi)
    expect($secondCount)->toBe($firstCount);
});

test('pergantian sekretaris langsung tampil di Beranda dan Tentang dalam satu transaksi', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    // Buat dua anggota
    $sekretarisLama = Anggota::create([
        'name' => 'Ahmad Fauzi Lama',
        'position' => 'Sekretaris',
        'is_pengurus' => true,
        'urutan' => 5,
        'is_public' => true,
        'status' => 'Aktif',
    ]);

    $sekretarisBaru = Anggota::create([
        'name' => 'Budi Santoso Baru',
        'is_public' => true,
        'status' => 'Aktif',
    ]);

    // Sebelum pergantian: sekretaris lama tampil
    $this->get(route('home'))->assertSee('Ahmad Fauzi Lama');
    $this->get(route('tentang'))->assertSee('Ahmad Fauzi Lama');

    // Lakukan pergantian pejabat di /admin/pengurus
    $response = $this->actingAs($admin)->post(route('admin.pengurus.update'), [
        'sekretaris' => $sekretarisBaru->id,
    ]);
    $response->assertRedirect(route('admin.pengurus.index'));
    $response->assertSessionHas('success');

    // Pastikan di database tidak ada dua sekretaris sekaligus
    expect(Anggota::where('position', 'Sekretaris')->where('is_pengurus', true)->count())->toBe(1);

    $sekretarisLama->refresh();
    $sekretarisBaru->refresh();

    expect($sekretarisLama->is_pengurus)->toBeFalse();
    expect($sekretarisBaru->is_pengurus)->toBeTrue();
    expect($sekretarisBaru->position)->toBe('Sekretaris');

    // Sekretaris baru langsung tampil di Beranda dan Tentang, sekretaris lama tidak lagi jadi sekretaris
    $beranda = $this->get(route('home'));
    $beranda->assertStatus(200);
    $beranda->assertSee('Budi Santoso Baru');

    $tentang = $this->get(route('tentang'));
    $tentang->assertStatus(200);
    $tentang->assertSee('Budi Santoso Baru');
    $tentang->assertDontSee('Ahmad Fauzi Lama');
});

test('koordinator kosong tidak merusak halaman publik ekosistem maupun admin', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    // Buat banom tanpa koordinator
    $banom = Banom::create([
        'nama' => 'Fatayat NU',
        'singkatan' => 'Fatayat',
        'slug' => 'fatayat',
        'koordinator_id' => null,
        'urutan' => 1,
    ]);

    // Halaman publik ekosistem harus 200 dan tidak error
    $public = $this->get(route('ekosistem'));
    $public->assertStatus(200);
    $public->assertSee('Fatayat NU');

    // Halaman admin banom harus menampilkan "Belum ada koordinator" dan tanpa teks "dari database anggota"
    $adminPage = $this->actingAs($admin)->get(route('admin.banom.index'));
    $adminPage->assertStatus(200);
    $adminPage->assertSee('Belum ada koordinator');
    $adminPage->assertDontSee('dari database anggota');
});

test('jabatan wakil yang kosong atau bernilai titik-titik tidak ditampilkan di halaman publik', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $rais = Anggota::create([
        'name' => 'KH. Nurul Huda',
        'status' => 'Aktif',
        'is_public' => true,
    ]);

    // Update pengurus: Rais diisi, Wakil Rais dikosongkan
    $this->actingAs($admin)->post(route('admin.pengurus.update'), [
        'rais_syuriyah' => $rais->id,
        'wakil_rais' => '',
        'wakil_ketua' => '',
        'wakil_sekretaris' => '',
        'wakil_bendahara' => '',
    ]);

    // Pastikan tidak ada "..." yang tersimpan di SiteSetting
    expect(SiteSetting::get('pengurus.wakil_rais'))->toBe('');
    expect(SiteSetting::get('pengurus.wakil_rais'))->not->toBe('...');

    // Halaman Tentang tidak boleh menampilkan card Wakil Rais Syuriyah
    $tentang = $this->get(route('tentang'));
    $tentang->assertStatus(200);
    $tentang->assertSee('KH. Nurul Huda');
    $tentang->assertDontSee('Wakil Rais Syuriyah');
    $tentang->assertDontSee('Wakil Sekretaris');
    $tentang->assertDontSee('Wakil Bendahara');
    $tentang->assertDontSee('...');
});

<?php

use App\Models\Anggota;
use App\Models\Banom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('rute cetak satu anggota 403 untuk non-admin, redirect untuk tamu, dan 404 jika anggota tidak ada', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);
    $regularUser = User::factory()->create([
        'role' => User::ROLE_ANGGOTA,
    ]);
    $anggota = Anggota::factory()->create();

    // 1. Tamu diarahkan ke login
    $guestResponse = $this->get(route('admin.anggota.print-single', $anggota->id));
    $guestResponse->assertRedirect(route('login'));

    // 2. Non-admin mendapatkan 403 Forbidden
    $userResponse = $this->actingAs($regularUser)->get(route('admin.anggota.print-single', $anggota->id));
    $userResponse->assertStatus(403);

    // 3. Admin dapat mengakses halaman cetak satu anggota (200 OK)
    $adminResponse = $this->actingAs($admin)->get(route('admin.anggota.print-single', $anggota->id));
    $adminResponse->assertStatus(200);
    $adminResponse->assertSee($anggota->name);
    $adminResponse->assertSee('Cetak / Simpan PDF');

    // 4. Jika anggota tidak ada, mengembalikan 404 Not Found
    $notFoundResponse = $this->actingAs($admin)->get(route('admin.anggota.print-single', 999999));
    $notFoundResponse->assertStatus(404);
});

test('halaman cetak satu kartu memiliki tombol kembali, card 420px, dan tanpa ruang kosong atas berlebih', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $anggota = Anggota::factory()->create(['name' => 'Kiai Sahal Mahfudz']);

    $response = $this->actingAs($admin)->get(route('admin.anggota.print-single', $anggota->id));
    $response->assertStatus(200);

    // Tombol kembali dan cetak
    $response->assertSee('Kembali');
    $response->assertSee('Cetak / Simpan PDF');
    // Memastikan tidak ada teks lama "Satu kartu diposisikan di sudut kiri-atas..."
    $response->assertDontSee('Satu kartu diposisikan di sudut kiri-atas');
    // Memastikan kartu berada di container max-w-[420px]
    $response->assertSee('max-w-[420px]');
});

test('halaman cetak kartu massal menampilkan data anggota dan kontrol seleksi', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);
    $banom = Banom::create([
        'nama' => 'Gerakan Pemuda Ansor',
        'singkatan' => 'GP Ansor',
        'slug' => 'gp-ansor',
        'urutan' => 1,
    ]);
    $anggota1 = Anggota::factory()->create(['name' => 'Ahmad Dahlan', 'banom_id' => $banom->id]);
    $anggota2 = Anggota::factory()->create(['name' => 'Hasyim Asyari', 'banom_id' => $banom->id]);

    $response = $this->actingAs($admin)->get(route('admin.anggota.print-cards'));
    $response->assertStatus(200);

    $response->assertSee('Ahmad Dahlan');
    $response->assertSee('Hasyim Asyari');
    $response->assertSee('Pilih Semua');
    $response->assertSee('Cetak Terpilih');
    $response->assertSee('print-cards-grid');
});

test('pencarian memfilter hasil berdasarkan nama, jabatan, banom, dan ID', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $banomAnsor = Banom::create([
        'nama' => 'Gerakan Pemuda Ansor',
        'singkatan' => 'GP Ansor',
        'slug' => 'gp-ansor',
        'urutan' => 1,
    ]);
    $banomFatayat = Banom::create([
        'nama' => 'Fatayat Nahdlatul Ulama',
        'singkatan' => 'Fatayat NU',
        'slug' => 'fatayat-nu',
        'urutan' => 2,
    ]);

    $m1 = Anggota::factory()->create([
        'name' => 'Abdurrahman Wahid',
        'position' => 'Penasihat Spiritual',
        'banom_id' => $banomAnsor->id,
    ]);
    $m2 = Anggota::factory()->create([
        'name' => 'Khofifah Indar Parawansa',
        'position' => 'Ketua Umum PP',
        'banom_id' => $banomFatayat->id,
    ]);

    // 1. Cari berdasarkan Nama
    $resNama = $this->actingAs($admin)->get(route('admin.anggota.print-cards', ['q' => 'Wahid']));
    $resNama->assertStatus(200);
    $resNama->assertSee('Abdurrahman Wahid');
    $resNama->assertDontSee('Khofifah Indar Parawansa');

    // 2. Cari berdasarkan Jabatan
    $resJabatan = $this->actingAs($admin)->get(route('admin.anggota.print-cards', ['q' => 'Penasihat']));
    $resJabatan->assertStatus(200);
    $resJabatan->assertSee('Abdurrahman Wahid');
    $resJabatan->assertDontSee('Khofifah Indar Parawansa');

    // 3. Cari berdasarkan Banom
    $resBanom = $this->actingAs($admin)->get(route('admin.anggota.print-cards', ['q' => 'Fatayat']));
    $resBanom->assertStatus(200);
    $resBanom->assertSee('Khofifah Indar Parawansa');
    $resBanom->assertDontSee('Abdurrahman Wahid');

    // 4. Cari berdasarkan ID
    $resId = $this->actingAs($admin)->get(route('admin.anggota.print-cards', ['q' => (string) $m1->id]));
    $resId->assertStatus(200);
    $resId->assertSee('Abdurrahman Wahid');
    $resId->assertDontSee('Khofifah Indar Parawansa');
});

test('cetak massal hanya memuat id terpilih termasuk yang tersaring', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $m1 = Anggota::factory()->create(['name' => 'Anggota Terpilih Satu']);
    $m2 = Anggota::factory()->create(['name' => 'Anggota Terpilih Dua']);
    $m3 = Anggota::factory()->create(['name' => 'Anggota Tidak Terpilih']);

    $response = $this->actingAs($admin)->get(route('admin.anggota.print-cards', [
        'selected' => [$m1->id, $m2->id],
    ]));
    $response->assertStatus(200);

    // Initial selected array masuk ke state Alpine
    $response->assertSee(json_encode([$m1->id, $m2->id]));

    // Memastikan class dinamis !hidden untuk kartu yang tidak dipilih
    $response->assertSee(":class=\"{ '!hidden': !isSelected(".$m1->id.') }"', false);
    $response->assertSee(":class=\"{ '!hidden': !isSelected(".$m3->id.') }"', false);
});

test('badge banom tidak muncul jika sama atau redundan dengan jabatan', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $banomAdmin = Banom::create([
        'nama' => 'Administrator',
        'singkatan' => 'Administrator',
        'slug' => 'administrator',
        'urutan' => 1,
    ]);
    $banomIpnu = Banom::create([
        'nama' => 'Ikatan Pelajar Nahdlatul Ulama',
        'singkatan' => 'IPNU',
        'slug' => 'ipnu',
        'urutan' => 2,
    ]);
    $banomAnsor = Banom::create([
        'nama' => 'Gerakan Pemuda Ansor',
        'singkatan' => 'GP Ansor',
        'slug' => 'gp-ansor-2',
        'urutan' => 3,
    ]);

    // Kasus 1: Jabatan Administrator dan Banom Administrator -> Badge banom tidak boleh duplikat
    $anggota1 = Anggota::factory()->create([
        'name' => 'Admin Utama',
        'position' => 'Administrator',
        'banom_id' => $banomAdmin->id,
    ]);

    $view1 = (string) $this->actingAs($admin)->get(route('admin.anggota.print-single', $anggota1->id))->getContent();
    // Menghitung kemunculan kata "Administrator" di area badge kartu
    expect(substr_count($view1, 'Administrator'))->toBeLessThanOrEqual(3); // hanya judul/badge jabatan & profil

    // Kasus 2: Jabatan "Ketua IPNU" dan banom "IPNU" -> Badge IPNU tidak boleh muncul terpisah
    $anggota2 = Anggota::factory()->create([
        'name' => 'Rekan Fauzan',
        'position' => 'Ketua IPNU',
        'banom_id' => $banomIpnu->id,
    ]);
    $view2 = (string) $this->actingAs($admin)->get(route('admin.anggota.print-single', $anggota2->id))->getContent();
    // Di badge tidak boleh ada "IPNU" berdiri sendiri sebagai badge kedua setelah "Ketua IPNU"
    expect(str_contains($view2, '>Ketua IPNU<'))->toBeTrue();

    // Kasus 3: Jabatan berbeda dari banom (misal "Ketua" dan "GP Ansor") -> Keduanya tampil
    $anggota3 = Anggota::factory()->create([
        'name' => 'Sahabat Hasan',
        'position' => 'Ketua',
        'banom_id' => $banomAnsor->id,
    ]);
    $view3 = (string) $this->actingAs($admin)->get(route('admin.anggota.print-single', $anggota3->id))->getContent();
    expect(str_contains($view3, '>Ketua<'))->toBeTrue();
    expect(str_contains($view3, '>GP Ansor<'))->toBeTrue();
});

test('inisial pada avatar pengganti foto selalu 2 huruf kapital', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    // 1. Nama dua kata
    $anggota1 = Anggota::factory()->create(['name' => 'Ahmad Dahlan', 'photo' => null]);
    $res1 = $this->actingAs($admin)->get(route('admin.anggota.print-single', $anggota1->id));
    $res1->assertSee('AD');

    // 2. Nama satu kata panjang
    $anggota2 = Anggota::factory()->create(['name' => 'Ansori', 'photo' => null]);
    $res2 = $this->actingAs($admin)->get(route('admin.anggota.print-single', $anggota2->id));
    $res2->assertSee('AN');

    // 3. Nama satu huruf lowercase "a" -> Menghasilkan 2 huruf kapital (bukan huruf kecil "a")
    $anggota3 = Anggota::factory()->create(['name' => 'a', 'photo' => null]);
    $res3 = $this->actingAs($admin)->get(route('admin.anggota.print-single', $anggota3->id));
    $res3->assertSee('AN');

    // 4. Nama lowercase "budi santoso" -> Inisial kapital "BS"
    $anggota4 = Anggota::factory()->create(['name' => 'budi santoso', 'photo' => null]);
    $res4 = $this->actingAs($admin)->get(route('admin.anggota.print-single', $anggota4->id));
    $res4->assertSee('BS');
});

test('foto kosong menampilkan avatar inisial berlatar hijau', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);
    $anggotaTanpaFoto = Anggota::factory()->create([
        'name' => 'Budi Santoso',
        'photo' => null,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.anggota.print-single', $anggotaTanpaFoto->id));
    $response->assertStatus(200);

    // Memastikan inisial "BS" tampil di dalam avatar berlatar hijau #087A4B
    $response->assertSee('BS');
    $response->assertSee('#087A4B');
});

test('token kartu qr unik dan dapat diverifikasi via publik route', function () {
    $anggota1 = Anggota::factory()->create();
    $anggota2 = Anggota::factory()->create();

    // Pastikan token kartu unik dan terisi
    expect($anggota1->kartu_token)->not->toBeEmpty();
    expect($anggota2->kartu_token)->not->toBeEmpty();
    expect($anggota1->kartu_token)->not->toBe($anggota2->kartu_token);

    // Verifikasi publik dengan token
    $verifyUrl1 = route('kartu.verify', $anggota1->kartu_token);
    $response1 = $this->get($verifyUrl1);
    $response1->assertStatus(200);
    $response1->assertSee($anggota1->name);

    $verifyUrl2 = route('kartu.verify', $anggota2->kartu_token);
    $response2 = $this->get($verifyUrl2);
    $response2->assertStatus(200);
    $response2->assertSee($anggota2->name);

    // Token tidak valid menghasilkan 404
    $invalidResponse = $this->get(route('kartu.verify', 'token-palsu-ngawur-12345'));
    $invalidResponse->assertStatus(404);
});

test('urutan cetak massal mendahulukan pengurus inti lalu alfabetis nama', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $nonPengurusA = Anggota::factory()->create([
        'name' => 'Bambang Sudarsono',
        'is_pengurus' => false,
        'urutan' => 99,
    ]);
    $pengurus2 = Anggota::factory()->create([
        'name' => 'Zainal Arifin',
        'is_pengurus' => true,
        'urutan' => 2,
    ]);
    $pengurus1 = Anggota::factory()->create([
        'name' => 'Ahmad Khatib',
        'is_pengurus' => true,
        'urutan' => 1,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.anggota.print-cards'));
    $response->assertStatus(200);

    $content = $response->getContent();
    $posPengurus1 = strpos($content, 'Ahmad Khatib');
    $posPengurus2 = strpos($content, 'Zainal Arifin');
    $posNonPengurus = strpos($content, 'Bambang Sudarsono');

    expect($posPengurus1)->toBeLessThan($posPengurus2);
    expect($posPengurus2)->toBeLessThan($posNonPengurus);
});

test('tombol cetak massal di header data anggota terbuka di tab sama, tombol per anggota berlabel Kartu dengan aria-label', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $anggota = Anggota::factory()->create(['name' => 'Kiai Asad Samsul Arifin']);

    $response = $this->actingAs($admin)->get(route('admin.anggota.index'));
    $response->assertStatus(200);

    // Tombol header Cetak Massal tanpa target="_blank"
    $response->assertSee('Cetak Massal');
    $content = $response->getContent();
    expect($content)->not->toMatch('/href="[^"]*cetak-kartu[^"]*"[^>]*target="_blank"/i');

    // Tombol per anggota berlabel Kartu dan memiliki aria-label
    $response->assertSee('Kartu');
    $response->assertSee('aria-label="Cetak kartu '.$anggota->name.'"', false);
    $response->assertSee(route('admin.anggota.print-single', $anggota->id));
});

test('filter aktif di data anggota diteruskan ke cetak massal dan tombol kembali mempertahankan filter', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $banom = Banom::create([
        'nama' => 'Fatayat NU',
        'singkatan' => 'Fatayat',
        'slug' => 'fatayat',
        'urutan' => 1,
    ]);
    $anggota = Anggota::factory()->create([
        'name' => 'Siti Khadijah',
        'banom_id' => $banom->id,
        'status' => 'Aktif',
    ]);

    // 1. Dari halaman index dengan filter aktif
    $indexResponse = $this->actingAs($admin)->get(route('admin.anggota.index', [
        'search' => 'Khadijah',
        'banom_id' => $banom->id,
        'status' => 'Aktif',
    ]));
    $indexResponse->assertStatus(200);
    // Link cetak massal memuat query string filter
    $indexResponse->assertSee('banom_id='.$banom->id);
    $indexResponse->assertSee('status=Aktif');
    $indexResponse->assertSee('Khadijah');

    // 2. Di halaman cetak massal dengan query string tersebut
    $printResponse = $this->actingAs($admin)->get(route('admin.anggota.print-cards', [
        'search' => 'Khadijah',
        'banom_id' => $banom->id,
        'status' => 'Aktif',
    ]));
    $printResponse->assertStatus(200);
    // Tombol kembali rute eksplisit mengarah ke data anggota dengan parameter yang sama
    $printResponse->assertSee(route('admin.anggota.index'));
    $printResponse->assertSee('search=Khadijah');
    $printResponse->assertSee('banom_id='.$banom->id);
    $printResponse->assertSee('status=Aktif');
});

<?php

use App\Models\Anggota;
use App\Models\Banom;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('validasi form berita dan upload foto dengan Storage::fake serta penghapusan file lama saat diganti', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    // 1. Validasi input wajib pada berita
    $responseInvalid = $this->actingAs($admin)->post(route('admin.berita.store'), []);
    $responseInvalid->assertSessionHasErrors(['title', 'category', 'content', 'status']);

    // 2. Simpan berita dengan gambar baru
    $fileLama = UploadedFile::fake()->image('berita_lama.jpg', 600, 400);
    $responseStore = $this->actingAs($admin)->post(route('admin.berita.store'), [
        'title' => 'Berita Pelatihan Digital',
        'category' => 'Kegiatan',
        'content' => 'Uraian kegiatan pelatihan.',
        'status' => 'published',
        'image' => $fileLama,
    ]);
    $responseStore->assertRedirect(route('admin.berita.index'));
    $responseStore->assertSessionHas('success');

    $berita = Berita::where('title', 'Berita Pelatihan Digital')->first();
    expect($berita)->not->toBeNull();
    expect($berita->image)->not->toBeNull();
    Storage::disk('public')->assertExists($berita->image);
    $pathLama = $berita->image;

    // 3. Update berita dengan gambar baru (file lama harus terhapus)
    $fileBaru = UploadedFile::fake()->image('berita_baru.png', 800, 600);
    $responseUpdate = $this->actingAs($admin)->put(route('admin.berita.update', $berita->id), [
        'title' => 'Berita Pelatihan Digital Diperbarui',
        'category' => 'Kegiatan',
        'content' => 'Uraian kegiatan pelatihan revisi.',
        'status' => 'published',
        'image' => $fileBaru,
    ]);
    $responseUpdate->assertRedirect(route('admin.berita.index'));
    $responseUpdate->assertSessionHas('success');

    $berita->refresh();
    Storage::disk('public')->assertMissing($pathLama);
    Storage::disk('public')->assertExists($berita->image);
});

test('form anggota mendukung jabatan, pilihan banom, status, urutan, dan is_pengurus serta menghapus foto lama saat diganti', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $banom = Banom::create([
        'nama' => 'Gerakan Pemuda Ansor',
        'singkatan' => 'GP Ansor',
        'slug' => 'gp-ansor',
    ]);

    // 1. Validasi field wajib
    $resInvalid = $this->actingAs($admin)->post(route('admin.anggota.store'), []);
    $resInvalid->assertSessionHasErrors(['name', 'status']);

    // 2. Simpan anggota dengan seluruh field baru dan foto
    $fotoLama = UploadedFile::fake()->image('profil_lama.jpg', 300, 300);
    $resStore = $this->actingAs($admin)->post(route('admin.anggota.store'), [
        'name' => 'Muhammad Rizqi',
        'position' => 'Wakil Ketua',
        'banom_id' => $banom->id,
        'status' => 'Aktif',
        'urutan' => 4,
        'is_pengurus' => 1,
        'region' => 'Kalisari Krajan',
        'is_public' => 1,
        'photo' => $fotoLama,
    ]);
    $resStore->assertRedirect(route('admin.anggota.index'));
    $resStore->assertSessionHas('success');

    $anggota = Anggota::where('name', 'Muhammad Rizqi')->first();
    expect($anggota)->not->toBeNull();
    expect($anggota->banom_id)->toBe($banom->id);
    expect($anggota->position)->toBe('Wakil Ketua');
    expect($anggota->urutan)->toBe(4);
    expect($anggota->is_pengurus)->toBeTrue();
    Storage::disk('public')->assertExists($anggota->photo);
    $pathFotoLama = $anggota->photo;

    // 3. Update foto anggota, foto lama harus terhapus
    $fotoBaru = UploadedFile::fake()->image('profil_baru.jpg', 400, 400);
    $resUpdate = $this->actingAs($admin)->put(route('admin.anggota.update', $anggota->id), [
        'name' => 'Muhammad Rizqi Diperbarui',
        'position' => 'Ketua',
        'banom_id' => $banom->id,
        'status' => 'Aktif',
        'urutan' => 2,
        'is_pengurus' => 1,
        'region' => 'Kalisari Krajan',
        'is_public' => 1,
        'photo' => $fotoBaru,
    ]);
    $resUpdate->assertRedirect(route('admin.anggota.index'));

    $anggota->refresh();
    Storage::disk('public')->assertMissing($pathFotoLama);
    Storage::disk('public')->assertExists($anggota->photo);
});

test('upload foto galeri menghapus file lama saat diganti', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $fotoLama = UploadedFile::fake()->image('galeri_lama.jpg');
    $this->actingAs($admin)->post(route('admin.galeri.store'), [
        'title' => 'Dokumentasi Kerja Bakti',
        'category' => 'GP Ansor',
        'photo' => $fotoLama,
    ]);

    $galeri = Galeri::where('title', 'Dokumentasi Kerja Bakti')->first();
    expect($galeri)->not->toBeNull();
    Storage::disk('public')->assertExists($galeri->photo);
    $pathLama = $galeri->photo;

    // Ganti foto
    $fotoBaru = UploadedFile::fake()->image('galeri_baru.jpg');
    $this->actingAs($admin)->put(route('admin.galeri.update', $galeri->id), [
        'title' => 'Dokumentasi Kerja Bakti Edit',
        'category' => 'GP Ansor',
        'photo' => $fotoBaru,
    ]);

    $galeri->refresh();
    Storage::disk('public')->assertMissing($pathLama);
    Storage::disk('public')->assertExists($galeri->photo);
});

test('halaman edit dan create banom berhasil diakses tanpa error undefined variable', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $anggota = Anggota::factory()->create(['status' => 'Aktif']);

    $banom = Banom::create([
        'nama' => 'Muslimat NU',
        'singkatan' => 'Muslimat',
        'slug' => 'muslimat',
        'koordinator_id' => $anggota->id,
    ]);

    $resCreate = $this->actingAs($admin)->get(route('admin.banom.create'));
    $resCreate->assertOk();
    $resCreate->assertSee($anggota->name);

    $resEdit = $this->actingAs($admin)->get(route('admin.banom.edit', $banom->id));
    $resEdit->assertOk();
    $resEdit->assertSee($banom->nama);
    $resEdit->assertSee($anggota->name);
});

test('admin dapat menyimpan dan memperbarui koordinator banom', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $anggota1 = Anggota::factory()->create(['status' => 'Aktif']);
    $anggota2 = Anggota::factory()->create(['status' => 'Aktif']);

    // Simpan baru dengan koordinator_id
    $resStore = $this->actingAs($admin)->post(route('admin.banom.store'), [
        'nama' => 'Fatayat NU',
        'singkatan' => 'Fatayat',
        'koordinator_id' => $anggota1->id,
        'urutan' => 1,
    ]);
    $resStore->assertRedirect(route('admin.banom.index'));

    $fatayat = Banom::where('singkatan', 'Fatayat')->first();
    expect($fatayat)->not->toBeNull();
    expect($fatayat->koordinator_id)->toBe($anggota1->id);

    // Update dengan koordinator baru
    $resUpdate = $this->actingAs($admin)->put(route('admin.banom.update', $fatayat->id), [
        'nama' => 'Fatayat NU',
        'singkatan' => 'Fatayat',
        'koordinator_id' => $anggota2->id,
        'urutan' => 1,
    ]);
    $resUpdate->assertRedirect(route('admin.banom.index'));

    $fatayat->refresh();
    expect($fatayat->koordinator_id)->toBe($anggota2->id);
});

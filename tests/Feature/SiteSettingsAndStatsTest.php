<?php

use App\Models\Anggota;
use App\Models\Banom;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('statistics on beranda are dynamic from database', function () {
    // 1. Awalnya belum ada anggota atau banom
    $response = $this->get(route('home'));
    $response->assertStatus(200);
    $response->assertSee('Anggota & Warga Aktif', false);
    $response->assertSee('Badan Otonom & Lembaga', false);
    $response->assertSee('Kegiatan Rutin / Bulan', false);

    // 2. Tambahkan anggota dan banom
    Anggota::create([
        'name' => 'Ahmad Warga',
        'status' => 'Aktif',
        'is_public' => true,
    ]);
    Anggota::create([
        'name' => 'Budi Warga',
        'status' => 'Aktif',
        'is_public' => true,
    ]);

    Banom::create([
        'nama' => 'Muslimat NU',
        'singkatan' => 'Muslimat',
        'slug' => 'muslimat',
    ]);
    Banom::create([
        'nama' => 'GP Ansor',
        'singkatan' => 'GP Ansor',
        'slug' => 'gp-ansor',
    ]);

    // 3. Periksa halaman beranda
    $response2 = $this->get(route('home'));
    $response2->assertStatus(200);
    $response2->assertSee('2'); // Anggota aktif persis tanpa tanda '+'
    $response2->assertSee('2'); // Badan otonom
});

test('admin can update statistics via pengaturan and reflected in public pages', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.pengaturan.update'), [
        'stat_kegiatan_mode' => 'manual',
        'stat_kegiatan_per_bulan' => '15',
    ]);

    $response->assertRedirect(route('admin.pengaturan.index'));
    $response->assertSessionHas('success');

    expect(SiteSetting::get('stat.kegiatan_mode'))->toBe('manual');
    expect(SiteSetting::get('stat.kegiatan_per_bulan'))->toBe('15');

    // Cek statistik di Beranda
    $beranda = $this->get(route('home'));
    $beranda->assertStatus(200);
    $beranda->assertSee('15');
});

test('admin can upload hero video and poster and they appear on beranda', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $videoFile = UploadedFile::fake()->create('custom_video.mp4', 3000, 'video/mp4');
    $posterFile = UploadedFile::fake()->image('custom_poster.webp', 1920, 1080);

    $response = $this->actingAs($admin)->post(route('admin.pengaturan.update'), [
        'hero_video_desktop' => $videoFile,
        'hero_poster' => $posterFile,
    ]);

    $response->assertRedirect(route('admin.pengaturan.index'));
    $response->assertSessionHas('success');

    $desktopPath = SiteSetting::get('hero_video_desktop');
    $posterPath = SiteSetting::get('hero_poster');

    expect($desktopPath)->not->toBeEmpty();
    expect($posterPath)->not->toBeEmpty();

    Storage::disk('public')->assertExists($desktopPath);
    Storage::disk('public')->assertExists($posterPath);

    // Periksa halaman beranda memuat url video baru
    $beranda = $this->get(route('home'));
    $beranda->assertStatus(200);
    $beranda->assertSee('storage/'.$desktopPath, false);
    $beranda->assertSee('storage/'.$posterPath, false);
});

test('old hero video file is deleted when replaced or deleted via checkbox', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    // 1. Upload video awal
    $video1 = UploadedFile::fake()->create('video_awal.mp4', 2000, 'video/mp4');
    $this->actingAs($admin)->post(route('admin.pengaturan.update'), [
        'hero_video_desktop' => $video1,
    ]);
    $path1 = SiteSetting::get('hero_video_desktop');
    Storage::disk('public')->assertExists($path1);

    // 2. Ganti dengan video baru
    $video2 = UploadedFile::fake()->create('video_baru.webm', 2500, 'video/webm');
    $this->actingAs($admin)->post(route('admin.pengaturan.update'), [
        'hero_video_desktop' => $video2,
    ]);
    $path2 = SiteSetting::get('hero_video_desktop');

    expect($path2)->not->toBe($path1);
    Storage::disk('public')->assertMissing($path1);
    Storage::disk('public')->assertExists($path2);

    // 3. Hapus via checkbox
    $this->actingAs($admin)->post(route('admin.pengaturan.update'), [
        'delete_hero_video_desktop' => '1',
    ]);
    expect(SiteSetting::get('hero_video_desktop'))->toBe('');
    Storage::disk('public')->assertMissing($path2);
});

test('hero video upload validates file type and max size', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    // File terlalu besar (> 15 MB = 15360 KB)
    $tooBig = UploadedFile::fake()->create('big.mp4', 16000, 'video/mp4');
    $response = $this->actingAs($admin)->post(route('admin.pengaturan.update'), [
        'hero_video_desktop' => $tooBig,
    ]);
    $response->assertSessionHasErrors('hero_video_desktop');

    // Tipe file bukan video
    $wrongType = UploadedFile::fake()->create('test.pdf', 500, 'application/pdf');
    $response2 = $this->actingAs($admin)->post(route('admin.pengaturan.update'), [
        'hero_video_desktop' => $wrongType,
    ]);
    $response2->assertSessionHasErrors('hero_video_desktop');
});

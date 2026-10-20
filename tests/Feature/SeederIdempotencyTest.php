<?php

use App\Models\Anggota;
use App\Models\Banom;
use App\Models\Berita;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('database seeder can be run twice safely without duplicating or overwriting data', function () {
    // 1. Jalankan seeder pertama kali
    $this->seed(DatabaseSeeder::class);

    $countUserInitial = User::count();
    $countAnggotaInitial = Anggota::count();
    $countBanomInitial = Banom::count();
    $countBeritaInitial = Berita::count();
    $countSettingInitial = SiteSetting::count();

    expect($countUserInitial)->toBeGreaterThan(0);
    expect($countAnggotaInitial)->toBeGreaterThan(0);
    expect($countBanomInitial)->toBe(6);

    // 2. Simulasikan perubahan data oleh admin
    $adminUser = User::where('email', 'admin@example.com')->first();
    $adminUser->name = 'Super Admin NU Kalisari';
    $adminUser->save();

    $setting = SiteSetting::where('key', 'stat.kegiatan_per_bulan')->first();
    $setting->value = '25';
    $setting->save();

    $anggota = Anggota::where('name', 'KH. Ahmad Fauzan')->first();
    $anggota->region = 'Kalisari Krajan';
    $anggota->save();

    // 3. Jalankan seeder kedua kali
    $this->seed(DatabaseSeeder::class);

    // 4. Pastikan jumlah data tidak berlipat ganda
    expect(User::count())->toBe($countUserInitial);
    expect(Anggota::count())->toBe($countAnggotaInitial);
    expect(Banom::count())->toBe($countBanomInitial);
    expect(Berita::count())->toBe($countBeritaInitial);
    expect(SiteSetting::count())->toBe($countSettingInitial);

    // 5. Pastikan data hasil edit admin TIDAK tertimpa kembali ke nilai bawaan seeder
    expect($adminUser->fresh()->name)->toBe('Super Admin NU Kalisari');
    expect($setting->fresh()->value)->toBe('25');
    expect($anggota->fresh()->region)->toBe('Kalisari Krajan');
});

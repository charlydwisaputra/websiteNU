<?php

use App\Models\Anggota;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('dashboard and admin profil can be accessed by admin', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
        'name' => 'Admin NU Kalisari',
        'email' => 'admin@kalisari.nu.or.id',
    ]);

    // Akses /admin/dashboard
    $dashboardResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
    $dashboardResponse->assertStatus(200);
    $dashboardResponse->assertSee('Dashboard');
    $dashboardResponse->assertSee('Admin NU Kalisari');
    $dashboardResponse->assertSee('Workspace');

    // Akses /admin (redirects to admin.dashboard)
    $adminRedirectResponse = $this->actingAs($admin)->get('/admin');
    $adminRedirectResponse->assertRedirect(route('admin.dashboard'));

    // Akses /admin/profil
    $profilResponse = $this->actingAs($admin)->get(route('admin.profil'));
    $profilResponse->assertStatus(200);
    $profilResponse->assertSee('Informasi Profil');
    $profilResponse->assertSee('Keamanan Akun');
    $profilResponse->assertSee($admin->email);
});

test('non-admin user receives 403 forbidden on dashboard and admin profil', function () {
    $regularUser = User::factory()->create([
        'role' => User::ROLE_ANGGOTA,
    ]);

    $this->actingAs($regularUser)->get(route('admin.dashboard'))->assertStatus(403);
    $this->actingAs($regularUser)->get(route('admin.profil'))->assertStatus(403);
});

test('guest is redirected to login when accessing dashboard or admin profil', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    $this->get(route('admin.profil'))->assertRedirect(route('login'));
});

test('statistics on dashboard display accurate counts for anggota, berita, galeri, and admin', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    // Buat data uji
    Anggota::create([
        'name' => 'Ahmad Fauzi',
        'status' => 'Aktif',
    ]);
    Anggota::create([
        'name' => 'Budi Santoso',
        'status' => 'Aktif',
    ]);
    Anggota::create([
        'name' => 'Cahyo Utomo',
        'status' => 'Nonaktif',
    ]);

    Berita::create([
        'title' => 'Kajian Rutin Malam Jumat Kalisari',
        'category' => 'Kegiatan',
        'content' => 'Kajian rutin mingguan warga nahdliyin.',
        'status' => 'published',
        'author_id' => $admin->id,
    ]);
    Berita::create([
        'title' => 'Rencana Peringatan Maulid Nabi',
        'category' => 'Kegiatan',
        'content' => 'Persiapan panitia maulid.',
        'status' => 'draft',
        'author_id' => $admin->id,
    ]);

    Galeri::create([
        'title' => 'Dokumentasi Kerja Bakti',
        'photo' => 'galeri/test1.jpg',
    ]);

    expect(Anggota::count())->toBe(3);
    expect(Berita::count())->toBe(2);
    expect(Galeri::count())->toBe(1);
    expect(User::where('role', User::ROLE_ADMIN)->count())->toBe(1);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));
    $response->assertStatus(200);

    // Pastikan angka statistik tampil di halaman
    $response->assertSee('Anggota');
    $response->assertSee('3');
    $response->assertSee('Berita');
    $response->assertSee('2');
    $response->assertSee('Dokumentasi');
    $response->assertSee('1');
    $response->assertSee('Administrator');
});

test('dashboard implements mobile first layout specifications with order classes and quick actions', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $berita = Berita::create([
        'title' => 'Kajian Rutin Pengurus Ranting',
        'category' => 'Kegiatan',
        'content' => 'Isi materi pengajian.',
        'status' => 'published',
        'author_id' => $admin->id,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));
    $response->assertStatus(200);

    // 1. Verifikasi urutan mobile (order-*)
    $response->assertSee('order-1'); // Sapaan / Header
    $response->assertSee('order-2'); // Statistik (grid 2x2)
    $response->assertSee('order-3'); // Aksi Cepat
    $response->assertSee('order-4'); // Perlu Perhatian
    $response->assertSee('order-5'); // Aktivitas Terbaru
    $response->assertSee('order-6'); // Profil Ringkas

    // 2. Aksi cepat: Tambah Berita lebar penuh di HP (col-span-2)
    $response->assertSee('col-span-2 sm:col-auto');
    $response->assertSee('Tambah Berita');

    // 3. Aktivitas terbaru: item bisa disentuh menuju detail/edit
    $response->assertSee(route('admin.berita.edit', $berita->id));
    $response->assertSee('min-h-[44px]');

    // 4. Kartu profil ringkas: link Edit profil menuju admin.profil
    $response->assertSee(route('admin.profil'));
});

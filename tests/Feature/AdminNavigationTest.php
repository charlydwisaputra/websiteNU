<?php

use App\Helpers\AdminNavHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('sidebar desktop, mobile drawer, and bottom bar derive from unified admin-nav config', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));
    $response->assertStatus(200);

    // Pastikan semua label menu utama ada di tampilan
    $menuItems = AdminNavHelper::getMenu();
    expect($menuItems)->not->toBeEmpty();

    foreach ($menuItems as $item) {
        $response->assertSee($item['label']);
        if (isset($item['title'])) {
            $response->assertSee($item['title']);
        }
        if (isset($item['aria_label'])) {
            $response->assertSee($item['aria_label']);
        }
    }

    // Pastikan item footer (Profil & Keluar) ada di tampilan
    $footerItems = AdminNavHelper::getFooter();
    expect($footerItems)->not->toBeEmpty();
    foreach ($footerItems as $fItem) {
        $response->assertSee($fItem['label']);
    }

    // Pastikan semua 9 item yang disyaratkan Tahap 1 tersedia
    $allLabels = array_merge(
        array_column($menuItems, 'label'),
        array_column($footerItems, 'label')
    );
    $requiredLabels = [
        'Dashboard', 'Berita', 'Anggota', 'Galeri',
        'Badan Otonom', 'Pengurus Inti', 'Pengaturan',
        'Profil', 'Keluar',
    ];
    foreach ($requiredLabels as $req) {
        expect($allLabels)->toContain($req);
    }

    // Pastikan bottom bar mobile dirender dengan 4 item tetap + Lainnya
    $bottomBarItems = AdminNavHelper::getBottomBar();
    expect($bottomBarItems)->toHaveCount(5);

    foreach ($bottomBarItems as $item) {
        $response->assertSee($item['label']);
    }

    // Pastikan bottom sheet memiliki standar mobile (escape key, backdrop click, transisi)
    $response->assertSee('@keydown.window.escape="moreSheetOpen = false"', false);
    $response->assertSee('@click="moreSheetOpen = false"', false);
    $response->assertSee('Menu Lainnya');
    $response->assertSee('Modul Tambahan');

    // Pastikan tombol hamburger mobile sudah tidak ada lagi (tidak redundan)
    $response->assertDontSee('mobileMenuOpen');
});

test('more item in mobile bottom bar is active when visiting sheet routes', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    // Buka salah satu halaman di dalam sheet, misalnya /admin/pengaturan
    $response = $this->actingAs($admin)->get(route('admin.pengaturan.index'));
    $response->assertStatus(200);

    expect(AdminNavHelper::isSheetActive())->toBeTrue();
});

test('admin nav helper safely resolves urls using Route::has without errors', function () {
    // 1. Rute valid yang ada
    $validItem = [
        'route' => 'admin.dashboard',
    ];
    expect(AdminNavHelper::resolveUrl($validItem))->toBe(route('admin.dashboard'));

    // 2. Rute palsu tanpa fallback mengembalikan '#'
    $invalidItem = [
        'route' => 'admin.rute.tidak.ada',
    ];
    expect(AdminNavHelper::resolveUrl($invalidItem))->toBe('#');

    // 3. Rute palsu dengan fallback valid mengembalikan rute fallback
    $fallbackItem = [
        'route' => 'admin.rute.tidak.ada',
        'fallback_route' => 'admin.dashboard',
    ];
    expect(AdminNavHelper::resolveUrl($fallbackItem))->toBe(route('admin.dashboard'));

    // 4. URL eksplisit diprioritaskan
    $urlItem = [
        'url' => '/custom/path',
        'route' => 'admin.dashboard',
    ];
    expect(AdminNavHelper::resolveUrl($urlItem))->toBe(url('/custom/path'));
});

test('guest cannot access admin routes and is redirected to login', function () {
    $routes = [
        'admin.dashboard',
        'admin.agenda.index',
        'admin.berita.index',
        'admin.anggota.index',
        'admin.galeri.index',
        'admin.banom.index',
        'admin.pengaturan.index',
    ];

    foreach ($routes as $route) {
        $response = $this->get(route($route));
        $response->assertRedirect(route('login'));
    }
});

test('non-admin user receives 403 forbidden on all admin routes', function () {
    $regularUser = User::factory()->create([
        'role' => User::ROLE_ANGGOTA,
    ]);

    $routes = [
        'admin.dashboard',
        'admin.agenda.index',
        'admin.berita.index',
        'admin.anggota.index',
        'admin.galeri.index',
        'admin.banom.index',
        'admin.pengaturan.index',
    ];

    foreach ($routes as $route) {
        $response = $this->actingAs($regularUser)->get(route($route));
        $response->assertStatus(403);
    }
});

test('mobile admin navigation conforms to UI requirements (workspace link, view site, dock 44px, safe area padding)', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));
    $response->assertOk();

    // 1. Workspace link ke Dashboard
    $response->assertSee(route('admin.dashboard'));
    $response->assertSee('Workspace');
    $response->assertSee('aria-label="Ke Dashboard Workspace"', false);

    // 2. Topbar Lihat Website (bisa diakses di mobile tanpa hidden sm:flex)
    $response->assertSee('title="Lihat Website"', false);
    $response->assertSee('aria-label="Lihat Website"', false);

    // 3. Menu Lainnya memiliki item Lihat Website dan tombol Keluar terpisah dengan gaya destruktif
    $response->assertSee('Menu Lainnya');
    $response->assertSee('Lihat Website');
    $response->assertSee('Keluar dari Akun');
    $response->assertSee('style="color:#ef4444;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25)"', false);

    // 4. Area sentuh dock minimal 44x44px dan aman safe-area
    $response->assertSee('min-h-[48px] min-w-[44px]');
    $response->assertSee('admin-main-content');
    $response->assertSee('padding-bottom: calc(5.25rem + env(safe-area-inset-bottom, 0px))', false);
});

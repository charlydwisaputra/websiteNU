<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\ProfileController;
use App\Models\Anggota;
use App\Models\Berita;
use App\Models\Galeri;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Utama / Landing Page
Route::get('/', function () {
    $beritas = class_exists(Berita::class) ? Berita::latest()->take(3)->get() : collect();
    $stats = [
        ['value' => Anggota::where('is_public', true)->count(), 'label' => 'Anggota'],
        ['value' => 6, 'label' => 'Organisasi'],
        ['value' => Berita::where('status', 'published')->count(), 'label' => 'Berita'],
        ['value' => Galeri::count(), 'label' => 'Dokumentasi'],
    ];

    return view('welcome', compact('beritas', 'stats'));
})->name('home');

// 2. Halaman Galeri Publik
Route::get('/galeri', [GaleriController::class, 'publicIndex'])->name('galeri');

// 3. Halaman Anggota Publik
Route::get('/anggota', [AnggotaController::class, 'publicIndex'])->name('anggota');

// 3b. Halaman Verifikasi Scan QR Kartu
Route::get('/kartu/verifikasi/{id}', [AnggotaController::class, 'verifyCard'])->name('kartu.verify');

// 4. Halaman Berita Publik
Route::get('/berita', function () {
    $beritas = class_exists(Berita::class) ? Berita::latest()->get() : collect();

    return view('berita.index', compact('beritas'));
})->name('berita.index');

// Halaman Detail Berita
Route::get('/berita/detail/{id}', function ($id) {
    $berita = class_exists(Berita::class) ? Berita::with('author')->findOrFail($id) : abort(404);
    $terkait = class_exists(Berita::class) 
        ? Berita::where('id', '!=', $id)->where('status', 'published')->latest()->take(4)->get() 
        : collect();

    return view('berita-detail', compact('berita', 'terkait'));
})->name('berita.show');

// Halaman Dashboard Bawaan
Route::get('/dashboard', function () {
    return view('dashboard', ['user' => Auth::user()]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute yang Memerlukan Login
Route::middleware('auth')->group(function () {

    // Rute Cetak Kartu Anggota Mandiri (User)
    Route::get('/kartu-saya/cetak', [AnggotaController::class, 'printMyCard'])->name('anggota.print-my-card');

    // Rute Profile (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ====================================================================
    // KUMPULAN RUTE ADMIN
    // ====================================================================

    Route::get('/admin/dashboard', function () {
        return view('dashboard', ['user' => Auth::user()]);
    })->name('admin.dashboard');

    // Admin Berita
    Route::get('/admin/berita', [BeritaController::class, 'index'])->name('admin.berita.index');
    Route::get('/admin/berita/tambah', [BeritaController::class, 'create'])->name('admin.berita.create');
    Route::post('/admin/berita', [BeritaController::class, 'store'])->name('admin.berita.store');
    Route::get('/admin/berita/{id}/edit', [BeritaController::class, 'edit'])->name('admin.berita.edit');
    Route::put('/admin/berita/{id}', [BeritaController::class, 'update'])->name('admin.berita.update');
    Route::delete('/admin/berita/{id}', [BeritaController::class, 'destroy'])->name('admin.berita.destroy');

    // Admin Anggota (SUDAH DIPERBAIKI MENJADI admin.anggota.create)
    Route::get('/admin/anggota', [AnggotaController::class, 'index'])->name('admin.anggota.index');
    Route::get('/admin/anggota/cetak-kartu', [AnggotaController::class, 'printCards'])->name('admin.anggota.print-cards');
    Route::get('/admin/anggota/tambah', [AnggotaController::class, 'create'])->name('admin.anggota.create');
    Route::post('/admin/anggota', [AnggotaController::class, 'store'])->name('admin.anggota.store');
    Route::get('/admin/anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('admin.anggota.edit');
    Route::put('/admin/anggota/{id}', [AnggotaController::class, 'update'])->name('admin.anggota.update');
    Route::delete('/admin/anggota/{id}', [AnggotaController::class, 'destroy'])->name('admin.anggota.destroy');

    // Admin Galeri
    Route::get('/admin/galeri', [GaleriController::class, 'index'])->name('admin.galeri.index');
    Route::get('/admin/galeri/tambah', [GaleriController::class, 'create'])->name('admin.galeri.create');
    Route::post('/admin/galeri', [GaleriController::class, 'store'])->name('admin.galeri.store');
    Route::get('/admin/galeri/{id}/edit', [GaleriController::class, 'edit'])->name('admin.galeri.edit');
    Route::put('/admin/galeri/{id}', [GaleriController::class, 'update'])->name('admin.galeri.update');
    Route::delete('/admin/galeri/{id}', [GaleriController::class, 'destroy'])->name('admin.galeri.destroy');
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\FrontController;
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

Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/tentang', [FrontController::class, 'tentang'])->name('tentang');
Route::get('/ekosistem', [FrontController::class, 'ekosistem'])->name('ekosistem');
Route::get('/kegiatan', [FrontController::class, 'kegiatan'])->name('kegiatan');
Route::get('/dokumentasi', [GaleriController::class, 'publicIndex'])->name('galeri');

Route::get('/anggota', [AnggotaController::class, 'publicIndex'])->name('anggota');
Route::get('/kartu/verifikasi/{id}', [AnggotaController::class, 'verifyCard'])->name('kartu.verify');

Route::get('/berita', [FrontController::class, 'beritaIndex'])->name('berita.index');
Route::get('/berita/detail/{id}', [FrontController::class, 'show'])->name('berita.show');

Route::get('/dashboard', function () {
    return view('dashboard', ['user' => Auth::user()]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/kartu-saya/cetak', [AnggotaController::class, 'printMyCard'])->name('anggota.print-my-card');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/dashboard', function () {
        return view('dashboard', ['user' => Auth::user()]);
    })->name('admin.dashboard');

    Route::get('/admin/berita', [BeritaController::class, 'index'])->name('admin.berita.index');
    Route::get('/admin/berita/tambah', [BeritaController::class, 'create'])->name('admin.berita.create');
    Route::post('/admin/berita', [BeritaController::class, 'store'])->name('admin.berita.store');
    Route::get('/admin/berita/{id}/edit', [BeritaController::class, 'edit'])->name('admin.berita.edit');
    Route::put('/admin/berita/{id}', [BeritaController::class, 'update'])->name('admin.berita.update');
    Route::delete('/admin/berita/{id}', [BeritaController::class, 'destroy'])->name('admin.berita.destroy');

    Route::get('/admin/anggota', [AnggotaController::class, 'index'])->name('admin.anggota.index');
    Route::get('/admin/anggota/cetak-kartu', [AnggotaController::class, 'printCards'])->name('admin.anggota.print-cards');
    Route::get('/admin/anggota/tambah', [AnggotaController::class, 'create'])->name('admin.anggota.create');
    Route::post('/admin/anggota', [AnggotaController::class, 'store'])->name('admin.anggota.store');
    Route::get('/admin/anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('admin.anggota.edit');
    Route::put('/admin/anggota/{id}', [AnggotaController::class, 'update'])->name('admin.anggota.update');
    Route::delete('/admin/anggota/{id}', [AnggotaController::class, 'destroy'])->name('admin.anggota.destroy');

    Route::get('/admin/galeri', [GaleriController::class, 'index'])->name('admin.galeri.index');
    Route::get('/admin/galeri/tambah', [GaleriController::class, 'create'])->name('admin.galeri.create');
    Route::post('/admin/galeri', [GaleriController::class, 'store'])->name('admin.galeri.store');
    Route::get('/admin/galeri/{id}/edit', [GaleriController::class, 'edit'])->name('admin.galeri.edit');
    Route::put('/admin/galeri/{id}', [GaleriController::class, 'update'])->name('admin.galeri.update');
    Route::delete('/admin/galeri/{id}', [GaleriController::class, 'destroy'])->name('admin.galeri.destroy');
});

require __DIR__.'/auth.php';

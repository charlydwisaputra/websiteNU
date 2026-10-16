<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\ProfileController;
use App\Models\Berita;
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
Route::redirect('/kegiatan', '/berita?category=Kegiatan', 301);
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
    })->middleware('admin')->name('admin.dashboard');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
        Route::get('/berita/tambah', [BeritaController::class, 'create'])->name('berita.create');
        Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
        Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
        Route::put('/berita/{id}', [BeritaController::class, 'update'])->name('berita.update');
        Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');

        Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
        Route::get('/anggota/cetak-kartu', [AnggotaController::class, 'printCards'])->name('anggota.print-cards');
        Route::get('/anggota/tambah', [AnggotaController::class, 'create'])->name('anggota.create');
        Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
        Route::get('/anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
        Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
        Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');

        Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');
        Route::get('/galeri/tambah', [GaleriController::class, 'create'])->name('galeri.create');
        Route::post('/galeri', [GaleriController::class, 'store'])->name('galeri.store');
        Route::get('/galeri/{id}/edit', [GaleriController::class, 'edit'])->name('galeri.edit');
        Route::put('/galeri/{id}', [GaleriController::class, 'update'])->name('galeri.update');
        Route::delete('/galeri/{id}', [GaleriController::class, 'destroy'])->name('galeri.destroy');
    });
});

Route::get('/sitemap.xml', function () {
    $beritas = class_exists(Berita::class)
        ? Berita::where('status', 'published')->latest()->get()
        : collect();

    return response()
        ->view('sitemap', compact('beritas'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

require __DIR__.'/auth.php';

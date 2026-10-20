<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BanomController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteSettingController;
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
Route::get('/kartu/verifikasi/{token}', [AnggotaController::class, 'verifyCard'])->name('kartu.verify');

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

    Route::redirect('/admin', '/admin/dashboard');
    Route::get('/admin/dashboard', function () {
        return view('dashboard', ['user' => Auth::user()]);
    })->middleware('admin')->name('admin.dashboard');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // Agenda Kegiatan
        Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
        Route::get('/agenda/tambah', [AgendaController::class, 'create'])->name('agenda.create');
        Route::post('/agenda', [AgendaController::class, 'store'])->name('agenda.store');
        Route::get('/agenda/{id}/edit', [AgendaController::class, 'edit'])->name('agenda.edit');
        Route::put('/agenda/{id}', [AgendaController::class, 'update'])->name('agenda.update');
        Route::delete('/agenda/{id}', [AgendaController::class, 'destroy'])->name('agenda.destroy');

        Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
        Route::get('/berita/tambah', [BeritaController::class, 'create'])->name('berita.create');
        Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
        Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
        Route::put('/berita/{id}', [BeritaController::class, 'update'])->name('berita.update');
        Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');

        Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
        Route::get('/anggota/cetak-kartu', [AnggotaController::class, 'printCards'])->name('anggota.print-cards');
        Route::get('/anggota/{anggota}/cetak-kartu', [AnggotaController::class, 'printSingleCard'])->name('anggota.print-single');
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

        // Ekosistem — Kelola Badan Otonom (Banom)
        Route::get('/banom', [BanomController::class, 'index'])->name('banom.index');
        Route::get('/banom/tambah', [BanomController::class, 'create'])->name('banom.create');
        Route::post('/banom', [BanomController::class, 'store'])->name('banom.store');
        Route::get('/banom/{id}/edit', [BanomController::class, 'edit'])->name('banom.edit');
        Route::put('/banom/{id}', [BanomController::class, 'update'])->name('banom.update');
        Route::delete('/banom/{id}', [BanomController::class, 'destroy'])->name('banom.destroy');
        Route::post('/banom/seed-dari-config', [BanomController::class, 'seedFromConfig'])->name('banom.seed');

        // Struktur Pengurus Inti & Pengaturan Situs
        Route::get('/pengurus', [PengurusController::class, 'index'])->name('pengurus.index');
        Route::post('/pengurus', [PengurusController::class, 'update'])->name('pengurus.update');
        Route::get('/pengaturan', [SiteSettingController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [SiteSettingController::class, 'update'])->name('pengaturan.update');

        // Profil Admin
        Route::get('/profil', [ProfileController::class, 'adminEdit'])->name('profil');
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

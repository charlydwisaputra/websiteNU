<?php

namespace App\Http\Controllers;

use App\Models\anggota;
use App\Models\Berita;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        // Menggunakan pemanggilan namespace langsung (\App\Models\...)
        // untuk mem-bypass bug linter VS Code pada class berhuruf kecil.

        // Statistik Data Anggota
        $totalAnggota = anggota::count();
        $anggotaAktif = anggota::where('status', 'Aktif')->count();

        // Statistik Berita
        $totalBerita = Berita::count();
        $draftBerita = Berita::where('status', 'draft')->count();

        // Statistik Pengguna / Akun (Aturan #45)
        $jumlahAdmin = User::where('role', User::ROLE_ADMIN)->count();
        $jumlahAkunAnggota = User::where('role', User::ROLE_ANGGOTA)->count();

        return view('admin.dashboard', compact(
            'totalAnggota',
            'anggotaAktif',
            'totalBerita',
            'draftBerita',
            'jumlahAdmin',
            'jumlahAkunAnggota'
        ));
    }
}

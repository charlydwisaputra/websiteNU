<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Anggota;

class FrontController extends Controller
{
    public function index()
    {
        $beritas = class_exists(Berita::class) ? Berita::where('status', 'published')->latest()->take(5)->get() : collect();
        $galeris = class_exists(Galeri::class) ? Galeri::latest()->take(6)->get() : collect();
        $stats = [
            ['value' => class_exists(Anggota::class) ? Anggota::where('is_public', true)->count() : 0, 'label' => 'Anggota'],
            ['value' => 6, 'label' => 'Organisasi'],
            ['value' => class_exists(Berita::class) ? Berita::where('status', 'published')->count() : 0, 'label' => 'Berita'],
            ['value' => class_exists(Galeri::class) ? Galeri::count() : 0, 'label' => 'Dokumentasi'],
        ];

        return view('welcome', compact('beritas', 'stats', 'galeris'));
    }

    public function tentang()
    {
        return view('tentang');
    }

    public function ekosistem()
    {
        return view('ekosistem');
    }

    public function kegiatan()
    {
        $kegiatan = class_exists(Berita::class) ? Berita::where('status', 'published')->where('category', 'Kegiatan')->latest()->get() : collect();
        return view('kegiatan', compact('kegiatan'));
    }

    public function beritaIndex()
    {
        $beritas = class_exists(Berita::class) ? Berita::latest()->get() : collect();
        return view('berita.index', compact('beritas'));
    }

    public function show(int $id)
    {
        $berita = class_exists(Berita::class) ? Berita::with('author')->findOrFail($id) : abort(404);
        $terkait = class_exists(Berita::class)
            ? Berita::where('id', '!=', $id)->where('status', 'published')->latest()->take(4)->get()
            : collect();

        return view('berita-detail', compact('berita', 'terkait'));
    }
}

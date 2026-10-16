<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    // Halaman Publik Galeri dengan Filter Kategori
    public function publicIndex(Request $request)
    {
        $category = $request->query('category');

        $galeris = Galeri::when($category, function ($query, $cat) {
            return $query->where('category', $cat);
        })->latest()->get();

        return view('galeri.index', compact('galeris'));
    }

    // Admin: Tampilkan Semua Galeri
    public function index(Request $request)
    {
        $query = Galeri::query();

        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->string('search')->toString());
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $galeris = $query->latest()->paginate(12)->withQueryString();

        return view('admin.galeri.index', compact('galeris'));
    }

    // Admin: Form Tambah Galeri
    public function create()
    {
        return view('admin.galeri.create');
    }

    // Admin: Simpan Galeri Baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'category' => 'nullable|string|in:Muslimat,Fatayat,GP Ansor,IPNU,IPPNU,PMII',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = $request->file('photo')->store('galeri', 'public');

        Galeri::create([
            'title' => $request->title,
            'category' => $request->category,
            'photo' => $photoPath,
        ]);

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil ditambahkan.');
    }

    // Admin: Form Edit Galeri
    public function edit(int $id)
    {
        $galeri = Galeri::findOrFail($id);

        return view('admin.galeri.edit', compact('galeri'));
    }

    // Admin: Update Galeri
    public function update(Request $request, int $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'category' => 'nullable|string|in:Muslimat,Fatayat,GP Ansor,IPNU,IPPNU,PMII',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $galeri = Galeri::findOrFail($id);

        $photoPath = $galeri->photo;
        if ($request->hasFile('photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('galeri', 'public');
        }

        $galeri->update([
            'title' => $request->title,
            'category' => $request->category,
            'photo' => $photoPath,
        ]);

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil diperbarui.');
    }

    // Admin: Hapus Galeri
    public function destroy(int $id)
    {
        $galeri = Galeri::findOrFail($id);

        if ($galeri->photo && Storage::disk('public')->exists($galeri->photo)) {
            Storage::disk('public')->delete($galeri->photo);
        }

        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil dihapus.');
    }
}

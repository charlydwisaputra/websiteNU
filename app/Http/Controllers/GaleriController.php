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

    // Admin: Simpan Galeri Baru (1 event = 1 record, bisa banyak foto)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|in:Muslimat,Fatayat,GP Ansor,IPNU,IPPNU,PMII,Lainnya',
            'description' => 'nullable|string|max:1000',
            'images' => 'required|array|min:1|max:20',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $photoPaths = [];
        foreach ($request->file('images') as $file) {
            $photoPaths[] = $file->store('galeri', 'public');
        }

        Galeri::create([
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'photo' => $photoPaths[0],  // cover = foto pertama
            'photos' => $photoPaths,     // semua foto
        ]);

        $count = count($photoPaths);

        return redirect()->route('admin.galeri.index')
            ->with('success', "Album \"{$request->title}\" berhasil ditambahkan dengan {$count} foto.");
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
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|in:Muslimat,Fatayat,GP Ansor,IPNU,IPPNU,PMII,Lainnya',
            'description' => 'nullable|string|max:1000',
            'new_images' => 'nullable|array|max:20',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'delete_photos' => 'nullable|array',
            'delete_photos.*' => 'string',
        ]);

        $galeri = Galeri::findOrFail($id);

        // Hapus foto yang diminta dihapus
        $existingPhotos = $galeri->photos ?? ($galeri->photo ? [$galeri->photo] : []);
        $deletePhotos = $request->input('delete_photos', []);

        foreach ($deletePhotos as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $existingPhotos = array_filter($existingPhotos, fn ($img) => $img !== $path);
        }

        // Tambah foto baru
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $existingPhotos[] = $file->store('galeri', 'public');
            }
        }

        $allPhotos = array_values($existingPhotos);

        $galeri->update([
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'photo' => $allPhotos[0] ?? null,
            'photos' => $allPhotos ?: null,
        ]);

        return redirect()->route('admin.galeri.index')->with('success', 'Album galeri berhasil diperbarui.');
    }

    // Admin: Hapus Galeri (seluruh album beserta semua fotonya)
    public function destroy(int $id)
    {
        $galeri = Galeri::findOrFail($id);

        // Hapus semua file foto dari storage
        $allPhotos = $galeri->photos ?? ($galeri->photo ? [$galeri->photo] : []);
        foreach ($allPhotos as $photoPath) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
        }

        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Album galeri berhasil dihapus.');
    }
}

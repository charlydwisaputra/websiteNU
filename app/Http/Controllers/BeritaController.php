<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Berita::query();

        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->string('search')->toString());
            $query->where('title', 'like', "%{$search}%");
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $beritas = $query->latest()->paginate(10)->withQueryString();

        return view('admin.berita.index', compact('beritas'));
    }

    public function create(): View
    {
        return view('admin.berita.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => ['required', 'string', Rule::in(Berita::CATEGORIES)],
            'content' => 'required',
            'status' => 'required|in:published,draft',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $allImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $allImages[] = $file->store('photos/berita', 'public');
            }
        }

        // Foto pertama jadi sampul utama
        $validated['image'] = $allImages[0] ?? null;
        $validated['images'] = count($allImages) > 1 ? array_slice($allImages, 1) : null;

        $validated['author_id'] = Auth::id();

        Berita::create($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit($id): View
    {
        $berita = Berita::findOrFail($id);

        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $berita = Berita::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => ['required', 'string', Rule::in(Berita::CATEGORIES)],
            'content' => 'required',
            'status' => 'required|in:published,draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'string',
        ]);

        if ($request->hasFile('image')) {
            if ($berita->image && Storage::disk('public')->exists($berita->image)) {
                Storage::disk('public')->delete($berita->image);
            }
            $validated['image'] = $request->file('image')->store('photos/berita', 'public');
        }

        // Handle existing images — remove deleted ones
        $existingImages = $berita->images ?? [];
        $deleteImages = $request->input('delete_images', []);
        foreach ($deleteImages as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $existingImages = array_filter($existingImages, fn ($img) => $img !== $path);
        }

        // Append newly uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $existingImages[] = $file->store('photos/berita', 'public');
            }
        }

        $validated['images'] = array_values($existingImages) ?: null;

        $berita->update($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $berita = Berita::findOrFail($id);

        if ($berita->image && Storage::disk('public')->exists($berita->image)) {
            Storage::disk('public')->delete($berita->image);
        }

        foreach ($berita->images ?? [] as $img) {
            if (Storage::disk('public')->exists($img)) {
                Storage::disk('public')->delete($img);
            }
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'category' => 'required|string|in:Kegiatan,Pengumuman,Organisasi,Sosial',
            'content' => 'required',
            'status' => 'required|in:published,draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('photos/berita', 'public');
        }

        // Gunakan auth()->user()->id untuk menghindari error Intelephense
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
            'category' => 'required|string|in:Kegiatan,Pengumuman,Organisasi,Sosial',
            'content' => 'required',
            'status' => 'required|in:published,draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($berita->image && Storage::disk('public')->exists($berita->image)) {
                Storage::disk('public')->delete($berita->image);
            }
            $validated['image'] = $request->file('image')->store('photos/berita', 'public');
        }

        $berita->update($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $berita = Berita::findOrFail($id);

        if ($berita->image && Storage::disk('public')->exists($berita->image)) {
            Storage::disk('public')->delete($berita->image);
        }
        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    public function publicIndex(Request $request): View
    {
        $query = Anggota::query()
            ->where('is_public', true)
            ->whereIn('status', ['Aktif', 'aktif']);

        $category = $request->query('category');
        $categories = ['Muslimat', 'Fatayat', 'GP Ansor', 'IPNU', 'IPPNU', 'PMII'];
        $query->when($category && in_array($category, $categories, true), function ($query) use ($category) {
            $query->where('position', $category);
        });

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('region', 'like', "%{$search}%");
            });
        }

        $anggotas = $query->latest()->paginate(12)->withQueryString();

        return view('anggota.index', compact('anggotas', 'categories'));
    }

    public function index(Request $request): View
    {
        $query = Anggota::query();

        // Fitur Pencarian (nama, role/position, atau wilayah/region)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('region', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Menggunakan Pagination 10 data per halaman
        $anggotas = $query->latest()->paginate(10)->withQueryString();

        return view('admin.anggota.index', compact('anggotas'));
    }

    public function create(): View
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|in:Muslimat,Fatayat,GP Ansor,IPNU,IPPNU,PMII',
            'wilayah' => 'nullable|string|max:255',
            'status' => 'required|in:Aktif,aktif,Nonaktif,nonaktif,Alumni,alumni,Pending,pending',
            'joined_at' => 'nullable|date',
            'is_public' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'status.required' => 'Status keanggotaan wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus berupa jpeg, png, atau jpg.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos/anggota', 'public');
        }

        Anggota::create($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function edit($id): View
    {
        $anggota = Anggota::findOrFail($id);

        return view('admin.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $anggota = Anggota::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|in:Muslimat,Fatayat,GP Ansor,IPNU,IPPNU,PMII',
            'wilayah' => 'nullable|string|max:255',
            'status' => 'required|in:Aktif,aktif,Nonaktif,nonaktif,Alumni,alumni,Pending,pending',
            'joined_at' => 'nullable|date',
            'is_public' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'status.required' => 'Status keanggotaan wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('photo')) {
            if ($anggota->photo && Storage::disk('public')->exists($anggota->photo)) {
                Storage::disk('public')->delete($anggota->photo);
            }
            $validated['photo'] = $request->file('photo')->store('photos/anggota', 'public');
        }

        $anggota->update($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function printCards(Request $request)
    {
        $query = Anggota::query();

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('role')) {
            $query->where('position', $request->role);
        }

        $anggotas = $query->latest()->get();

        return view('admin.anggota.print-cards', compact('anggotas'));
    }

    public function printMyCard(Request $request)
    {
        $user = $request->user();
        $anggota = $user->getOrBuildProfilAnggota();
        $anggotas = collect([$anggota]);

        return view('admin.anggota.print-cards', compact('anggotas'));
    }

    public function verifyCard($id): View
    {
        $anggota = Anggota::findOrFail($id);

        return view('anggota.verify', compact('anggota'));
    }

    public function destroy($id): RedirectResponse
    {
        $anggota = Anggota::findOrFail($id);

        if ($anggota->photo && Storage::disk('public')->exists($anggota->photo)) {
            Storage::disk('public')->delete($anggota->photo);
        }

        $anggota->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Data anggota berhasil dihapus.');
    }
}

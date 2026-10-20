<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Banom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BanomController extends Controller
{
    public function index(): View
    {
        $banoms = Banom::with('koordinator')->orderBy('urutan')->get();

        return view('admin.banom.index', compact('banoms'));
    }

    public function create(): View
    {
        $anggotas = Anggota::whereIn('status', ['Aktif', 'aktif'])
            ->orderBy('name')
            ->get();

        return view('admin.banom.create', [
            'anggotas' => $anggotas,
            'anggota' => $anggotas,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'koordinator_id' => 'nullable|exists:anggota,id',
            'ketua_anggota_id' => 'nullable|exists:anggota,id',
            'urutan' => 'nullable|integer|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        if ($request->filled('ketua_anggota_id') && empty($validated['koordinator_id'])) {
            $validated['koordinator_id'] = $request->input('ketua_anggota_id');
        }
        unset($validated['ketua_anggota_id']);

        $validated['slug'] = Str::slug($validated['singkatan']);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logo-organisasi', 'public');
        }

        $validated['urutan'] = $validated['urutan'] ?? Banom::max('urutan') + 1;

        Banom::create($validated);

        Cache::forget('site_banoms_data');

        return redirect()->route('admin.banom.index')
            ->with('success', 'Badan otonom berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $banom = Banom::findOrFail($id);
        $anggotas = Anggota::whereIn('status', ['Aktif', 'aktif'])
            ->orderBy('name')
            ->get();

        return view('admin.banom.edit', [
            'banom' => $banom,
            'anggotas' => $anggotas,
            'anggota' => $anggotas,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $banom = Banom::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'koordinator_id' => 'nullable|exists:anggota,id',
            'ketua_anggota_id' => 'nullable|exists:anggota,id',
            'urutan' => 'nullable|integer|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        if ($request->filled('ketua_anggota_id') && empty($validated['koordinator_id'])) {
            $validated['koordinator_id'] = $request->input('ketua_anggota_id');
        }
        unset($validated['ketua_anggota_id']);

        $validated['slug'] = Str::slug($validated['singkatan']);

        if ($request->hasFile('logo')) {
            // Logo lama tidak dihapus karena bisa berupa file bawaan (dari storage public)
            $validated['logo'] = $request->file('logo')->store('logo-organisasi', 'public');
        }

        $banom->update($validated);

        Cache::forget('site_banoms_data');

        return redirect()->route('admin.banom.index')
            ->with('success', 'Badan otonom berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $banom = Banom::findOrFail($id);

        // Hapus logo hanya jika bukan file bawaan dari storage/logo-organisasi yang lama
        if ($banom->logo && str_starts_with($banom->logo, 'logo-organisasi/') && Storage::disk('public')->exists($banom->logo)) {
            Storage::disk('public')->delete($banom->logo);
        }

        $banom->delete();

        Cache::forget('site_banoms_data');

        return redirect()->route('admin.banom.index')
            ->with('success', 'Badan otonom berhasil dihapus.');
    }

    /**
     * Seed data banom dari config/organisasi.php ke database.
     * Hanya menambah yang belum ada (berdasarkan singkatan).
     */
    public function seedFromConfig(): RedirectResponse
    {
        $configBanoms = config('organisasi.banom', []);
        $added = 0;

        foreach ($configBanoms as $index => $item) {
            $slug = Str::slug($item['singkatan'] ?? $item['nama']);
            $exists = Banom::where('singkatan', $item['singkatan'])
                ->orWhere('slug', $slug)
                ->orWhere('nama', $item['nama'])
                ->exists();

            if (! $exists) {
                $logoName = $item['logo'] ?? null;
                if ($logoName) {
                    $storagePath = 'logo-organisasi/'.$logoName;
                    $publicSource = public_path('images/banom/'.$logoName);
                    if (file_exists($publicSource) && ! Storage::disk('public')->exists($storagePath)) {
                        Storage::disk('public')->put($storagePath, (string) file_get_contents($publicSource));
                    }
                }

                Banom::create([
                    'nama' => $item['nama'],
                    'singkatan' => $item['singkatan'],
                    'slug' => $slug,
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'logo' => $logoName,
                    'urutan' => $index,
                ]);
                $added++;
            }
        }

        Cache::forget('site_banoms_data');

        $msg = $added > 0
            ? "{$added} badan otonom berhasil diimpor dari konfigurasi."
            : 'Semua badan otonom sudah ada di database.';

        return redirect()->route('admin.banom.index')->with('success', $msg);
    }
}

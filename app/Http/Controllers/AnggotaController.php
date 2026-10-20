<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Banom;
use App\Services\SiteStats;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    public function publicIndex(Request $request): View
    {
        $query = app(SiteStats::class)->getAnggotaAktifQuery();

        $banomInput = $request->query('badan') ?? $request->query('banom');
        $validBanoms = ['Muslimat', 'Fatayat', 'GP Ansor', 'IPNU', 'IPPNU', 'PMII'];
        $banom = null;

        if ($banomInput) {
            $inputLower = strtolower(trim((string) $banomInput));
            if (in_array($inputLower, ['pengurus-ranting', 'ranting', 'pengurus ranting'])) {
                $banom = 'Pengurus Ranting';
                $query->where(function ($q) {
                    $q->whereNull('banom_id')->orWhere('is_pengurus', 1);
                });
            } else {
                $matchedBanom = Banom::where('singkatan', $banomInput)
                    ->orWhere('slug', Str::slug($banomInput))
                    ->orWhere('nama', 'like', "%{$banomInput}%")
                    ->first();

                if ($matchedBanom) {
                    $banom = $matchedBanom->singkatan;
                    $query->where(function ($q) use ($matchedBanom) {
                        $q->where('banom_id', $matchedBanom->id)
                            ->orWhere('id', $matchedBanom->koordinator_id)
                            ->orWhere('position', 'like', "%{$matchedBanom->singkatan}%");
                    });
                } else {
                    foreach ($validBanoms as $valid) {
                        if (strcasecmp($valid, $banomInput) === 0 || strcasecmp(Str::slug($valid), Str::slug($banomInput)) === 0) {
                            $banom = $valid;
                            $query->where(function ($q) use ($valid) {
                                $q->where('position', 'like', "%{$valid}%")
                                    ->orWhereHas('banom', function ($bq) use ($valid) {
                                        $bq->where('singkatan', $valid)
                                            ->orWhere('nama', 'like', "%{$valid}%");
                                    });
                            });
                            break;
                        }
                    }
                }
            }
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('region', 'like', "%{$search}%");
            });
        }

        $baseCountQuery = app(SiteStats::class)->getAnggotaAktifQuery();
        $totalAll = (clone $baseCountQuery)->count();
        $totalRanting = (clone $baseCountQuery)->where(function ($q) {
            $q->whereNull('banom_id')->orWhere('is_pengurus', 1);
        })->count();

        $dynamicFilters = [
            [
                'label' => 'Semua',
                'value' => null,
                'count' => $totalAll,
            ],
            [
                'label' => 'Pengurus Ranting',
                'value' => 'pengurus-ranting',
                'count' => $totalRanting,
            ],
        ];

        $banomsFromDb = Banom::orderBy('urutan')->get();
        foreach ($banomsFromDb as $b) {
            $bCount = (clone $baseCountQuery)->where(function ($q) use ($b) {
                $q->where('banom_id', $b->id)
                    ->orWhere('position', 'like', "%{$b->singkatan}%");
            })->count();

            $dynamicFilters[] = [
                'label' => $b->singkatan,
                'value' => Str::slug($b->singkatan),
                'count' => $bCount,
            ];
        }

        $anggotas = $query->with('banom')
            ->orderByRaw("
                CASE 
                    WHEN (LOWER(position) LIKE '%syuriyah%' OR LOWER(position) LIKE '%rais%' OR LOWER(position) LIKE '%katib%' OR LOWER(position) LIKE '%awan%') THEN 1
                    WHEN is_pengurus = 1 THEN 2
                    WHEN (banom_id IS NOT NULL AND (LOWER(position) LIKE '%koordinator%' OR LOWER(position) LIKE '%ketua%' OR LOWER(position) LIKE '%pimpinan%')) THEN 3
                    ELSE 4
                END ASC
            ")
            ->orderByRaw('CASE WHEN urutan > 0 THEN urutan ELSE 999 END ASC')
            ->orderBy('name', 'asc')
            ->paginate(24)
            ->withQueryString();

        return view('anggota.index', [
            'anggotas' => $anggotas,
            'banom' => $banom,
            'filters' => $dynamicFilters,
        ]);
    }

    public function index(Request $request): View
    {
        $query = Anggota::query()->with('banom');

        // Fitur Pencarian (nama, role/position, atau wilayah/region)
        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->string('search')->toString());
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

        // Fitur Filter Badan Otonom (relasi banom_id)
        if ($request->filled('banom_id')) {
            $query->where('banom_id', $request->banom_id);
        }

        // Menggunakan Pagination 10 data per halaman
        $anggotas = $query->latest()->paginate(10)->withQueryString();
        $banoms = Banom::orderBy('urutan')->orderBy('nama')->get();

        return view('admin.anggota.index', compact('anggotas', 'banoms'));
    }

    public function create(): View
    {
        $banoms = Banom::orderBy('urutan')->orderBy('nama')->get();

        return view('admin.anggota.create', compact('banoms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'banom_id' => 'nullable|exists:banoms,id',
            'wilayah' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'tampil_wilayah' => 'nullable|boolean',
            'status' => 'required|in:Aktif,aktif,Nonaktif,nonaktif,Alumni,alumni,Pending,pending',
            'urutan' => 'nullable|integer|min:0',
            'is_pengurus' => 'nullable|boolean',
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

        $validated['tampil_wilayah'] = $request->boolean('tampil_wilayah');

        if (! empty($validated['position'])) {
            $validated['role'] = $validated['position'];
        } elseif (! empty($validated['role'])) {
            $validated['position'] = $validated['role'];
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos/anggota', 'public');
        }

        Anggota::create($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function edit($id): View
    {
        $anggota = Anggota::findOrFail($id);
        $banoms = Banom::orderBy('urutan')->orderBy('nama')->get();

        return view('admin.anggota.edit', compact('anggota', 'banoms'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $anggota = Anggota::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'banom_id' => 'nullable|exists:banoms,id',
            'wilayah' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'tampil_wilayah' => 'nullable|boolean',
            'status' => 'required|in:Aktif,aktif,Nonaktif,nonaktif,Alumni,alumni,Pending,pending',
            'urutan' => 'nullable|integer|min:0',
            'is_pengurus' => 'nullable|boolean',
            'joined_at' => 'nullable|date',
            'is_public' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'status.required' => 'Status keanggotaan wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $validated['tampil_wilayah'] = $request->boolean('tampil_wilayah');

        if (! empty($validated['position'])) {
            $validated['role'] = $validated['position'];
        } elseif (! empty($validated['role'])) {
            $validated['position'] = $validated['role'];
        }

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
        $query = Anggota::query()->with('banom');

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        $searchKey = $request->input('q', $request->input('search'));
        if (! empty($searchKey) && strlen(trim((string) $searchKey)) > 0) {
            $keyword = trim((string) $searchKey);
            $query->where(function ($sub) use ($keyword) {
                $sub->where('name', 'like', "%{$keyword}%")
                    ->orWhere('position', 'like', "%{$keyword}%")
                    ->orWhere('id', 'like', "%{$keyword}%")
                    ->orWhereHas('banom', function ($bq) use ($keyword) {
                        $bq->where('nama', 'like', "%{$keyword}%")
                            ->orWhere('singkatan', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('banom_id')) {
            $query->where('banom_id', $request->banom_id);
        } elseif ($request->filled('role')) {
            $query->where(function ($q) use ($request) {
                $q->where('position', $request->role)
                    ->orWhereHas('banom', function ($bq) use ($request) {
                        $bq->where('singkatan', $request->role)->orWhere('nama', $request->role);
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Urutan: pengurus inti dulu (is_pengurus desc, urutan asc), lalu nama A-Z
        $anggotas = $query->orderByDesc('is_pengurus')
            ->orderBy('urutan')
            ->orderBy('name')
            ->get();

        // Pastikan setiap anggota memiliki token kartu
        foreach ($anggotas as $anggota) {
            if (empty($anggota->kartu_token)) {
                $anggota->update(['kartu_token' => Str::random(32)]);
            }
        }

        $banoms = Banom::orderBy('urutan')->get();

        $selectedParam = $request->input('selected');
        $initialSelected = [];
        if (is_array($selectedParam)) {
            $initialSelected = array_map('intval', $selectedParam);
        } elseif (is_string($selectedParam) && strlen(trim($selectedParam)) > 0) {
            $initialSelected = array_map('intval', explode(',', $selectedParam));
        }

        return view('admin.anggota.print-cards', compact('anggotas', 'banoms', 'initialSelected'));
    }

    public function printSingleCard($anggota): View
    {
        if (! $anggota instanceof Anggota) {
            $anggota = Anggota::with('banom')->findOrFail($anggota);
        } else {
            $anggota->loadMissing('banom');
        }

        if (empty($anggota->kartu_token)) {
            $anggota->update(['kartu_token' => Str::random(32)]);
        }

        return view('admin.anggota.print-single', compact('anggota'));
    }

    public function printMyCard(Request $request)
    {
        $user = $request->user();
        $anggota = $user->getOrBuildProfilAnggota();
        if (empty($anggota->kartu_token)) {
            $anggota->update(['kartu_token' => Str::random(32)]);
        }

        return view('admin.anggota.print-single', compact('anggota'));
    }

    public function verifyCard(string $token): View
    {
        $anggota = Anggota::with('banom')->where('kartu_token', $token)->first();

        if (! $anggota && is_numeric($token)) {
            $anggota = Anggota::with('banom')->find($token);
        }

        if (! $anggota) {
            abort(404, 'Data verifikasi kartu anggota tidak ditemukan atau token tidak valid.');
        }

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

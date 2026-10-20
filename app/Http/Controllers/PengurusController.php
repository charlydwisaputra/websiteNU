<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengurusController extends Controller
{
    /**
     * Daftar 8 jabatan pengurus inti resmi Ranting NU Kalisari.
     *
     * @var array<string, array{name: string, urutan: int, category: string, is_wakil: bool}>
     */
    public const POSITIONS = [
        'rais_syuriyah' => [
            'name' => 'Rais Syuriyah',
            'urutan' => 1,
            'category' => 'Syuriyah',
            'is_wakil' => false,
        ],
        'wakil_rais' => [
            'name' => 'Wakil Rais Syuriyah',
            'urutan' => 2,
            'category' => 'Syuriyah',
            'is_wakil' => true,
        ],
        'ketua' => [
            'name' => 'Ketua Tanfidziyah',
            'urutan' => 3,
            'category' => 'Tanfidziyah',
            'is_wakil' => false,
        ],
        'wakil_ketua' => [
            'name' => 'Wakil Ketua',
            'urutan' => 4,
            'category' => 'Tanfidziyah',
            'is_wakil' => true,
        ],
        'sekretaris' => [
            'name' => 'Sekretaris',
            'urutan' => 5,
            'category' => 'Tanfidziyah',
            'is_wakil' => false,
        ],
        'wakil_sekretaris' => [
            'name' => 'Wakil Sekretaris',
            'urutan' => 6,
            'category' => 'Tanfidziyah',
            'is_wakil' => true,
        ],
        'bendahara' => [
            'name' => 'Bendahara',
            'urutan' => 7,
            'category' => 'Tanfidziyah',
            'is_wakil' => false,
        ],
        'wakil_bendahara' => [
            'name' => 'Wakil Bendahara',
            'urutan' => 8,
            'category' => 'Tanfidziyah',
            'is_wakil' => true,
        ],
    ];

    /**
     * Tampilkan formulir penetapan pengurus inti.
     */
    public function index(): View
    {
        $anggotas = Anggota::whereIn('status', ['Aktif', 'aktif'])
            ->orderBy('name')
            ->get();

        // Ambil pejabat saat ini berdasarkan flag is_pengurus dan position
        $currentPengurus = Anggota::where('is_pengurus', true)
            ->whereNotNull('position')
            ->get();

        $currentOfficers = [];
        foreach (self::POSITIONS as $key => $meta) {
            $officer = $currentPengurus->firstWhere('position', $meta['name']);
            $currentOfficers[$key] = $officer ? $officer->id : null;
        }

        $positions = self::POSITIONS;

        return view('admin.pengurus.index', compact('positions', 'anggotas', 'currentOfficers'));
    }

    /**
     * Perbarui pengurus inti dalam satu DB::transaction.
     */
    public function update(Request $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $coreNames = array_column(self::POSITIONS, 'name');

            // 1. Reset jabatan pengurus inti yang lama agar tidak ada pejabat ganda
            Anggota::where('is_pengurus', true)
                ->whereIn('position', $coreNames)
                ->update([
                    'is_pengurus' => false,
                    'position' => null,
                    'urutan' => 0,
                ]);

            // 2. Pasang anggota terpilih untuk tiap jabatan
            foreach (self::POSITIONS as $key => $meta) {
                $memberId = $request->input($key);

                if (! empty($memberId) && is_numeric($memberId)) {
                    $member = Anggota::find((int) $memberId);
                    if ($member) {
                        $member->update([
                            'is_pengurus' => true,
                            'position' => $meta['name'],
                            'urutan' => $meta['urutan'],
                            'is_public' => true,
                            'status' => 'Aktif',
                        ]);
                        SiteSetting::set('pengurus.'.$key, $member->name);

                        continue;
                    }
                }

                // Jika dikosongkan, hapus nilainya dan pastikan nilai "..." tidak pernah tersimpan
                SiteSetting::set('pengurus.'.$key, '');
            }

            Cache::forget('site_pengurus_data');
            Cache::forget('site_stats_data');
        });

        return redirect()->route('admin.pengurus.index')
            ->with('success', 'Susunan pengurus inti berhasil diperbarui.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Anggota;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FrontController extends Controller
{
    public function index()
    {
        $agendas = class_exists(Agenda::class)
            ? Agenda::where('tanggal_mulai', '>=', now()->startOfDay())
                ->orderBy('tanggal_mulai', 'asc')
                ->take(5)
                ->get()
            : collect();
        $beritas = class_exists(Berita::class) ? Berita::where('status', 'published')->latest()->take(5)->get() : collect();
        $galeris = class_exists(Galeri::class) ? Galeri::latest()->take(8)->get() : collect();
        $penguruses = class_exists(Anggota::class)
            ? Anggota::where('is_public', true)
                ->whereIn('status', ['Aktif', 'aktif'])
                ->where(function ($q) {
                    $q->whereNull('user_id')
                        ->orWhereHas('user', fn ($u) => $u->where('role', '!=', User::ROLE_ADMIN));
                })
                ->orderByRaw("CASE WHEN position IS NOT NULL AND TRIM(position) != '' THEN 0 ELSE 1 END")
                ->orderBy('position', 'asc')
                ->orderBy('id', 'asc')
                ->take(8)
                ->get()
            : collect();
        $stats = [
            ['value' => class_exists(Anggota::class) ? Anggota::where('is_public', true)->count() : 0, 'label' => 'Anggota'],
            ['value' => 6, 'label' => 'Organisasi'],
            ['value' => class_exists(Berita::class) ? Berita::where('status', 'published')->count() : 0, 'label' => 'Berita'],
            ['value' => class_exists(Galeri::class) ? Galeri::count() : 0, 'label' => 'Dokumentasi'],
        ];

        $prayer = $this->getPrayerData();

        return view('welcome', compact('beritas', 'stats', 'galeris', 'agendas', 'penguruses', 'prayer'));
    }

    public function tentang()
    {
        return view('tentang');
    }

    public function ekosistem()
    {
        return view('ekosistem');
    }

    public function beritaIndex()
    {
        $beritas = class_exists(Berita::class) ? Berita::where('status', 'published')->latest()->get() : collect();

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

    /**
     * Fetch prayer times from Aladhan API, cached per day.
     *
     * @return array{timings: array, hijri: array}|null
     */
    private function getPrayerData(): ?array
    {
        if (! config('site.prayer.enabled')) {
            return null;
        }

        $tz = config('site.prayer.timezone', 'Asia/Jakarta');
        $today = now($tz)->format('Y-m-d');
        $cacheKey = "prayer_times:{$today}";

        return Cache::remember($cacheKey, now($tz)->endOfDay()->diffInSeconds(now($tz)), function () use ($tz) {
            try {
                $response = Http::timeout(5)
                    ->retry(1, 200)
                    ->get('https://api.aladhan.com/v1/timings', [
                        'latitude' => config('site.prayer.latitude'),
                        'longitude' => config('site.prayer.longitude'),
                        'method' => config('site.prayer.method', 20),
                        'timezonestring' => $tz,
                        'adjustment' => config('site.prayer.hijri_adjustment', 0),
                    ]);

                if (! $response->successful()) {
                    return null;
                }

                $data = $response->json('data');

                $hijriMonths = [
                    1 => 'Muharram', 2 => 'Safar', 3 => "Rabi'ul Awal", 4 => "Rabi'ul Akhir",
                    5 => 'Jumadil Awal', 6 => 'Jumadil Akhir', 7 => 'Rajab', 8 => "Sya'ban",
                    9 => 'Ramadhan', 10 => 'Syawal', 11 => "Dzulqa'dah", 12 => 'Dzulhijjah',
                ];

                $hijri = $data['date']['hijri'] ?? [];
                $monthNum = (int) ($hijri['month']['number'] ?? 0);

                return [
                    'timings' => [
                        'Subuh' => $data['timings']['Fajr'] ?? null,
                        'Dzuhur' => $data['timings']['Dhuhr'] ?? null,
                        'Ashar' => $data['timings']['Asr'] ?? null,
                        'Maghrib' => $data['timings']['Maghrib'] ?? null,
                        'Isya' => $data['timings']['Isha'] ?? null,
                    ],
                    'hijri' => [
                        'day' => $hijri['day'] ?? '',
                        'month' => $hijriMonths[$monthNum] ?? ($hijri['month']['en'] ?? ''),
                        'year' => $hijri['year'] ?? '',
                    ],
                ];
            } catch (\Throwable) {
                return null;
            }
        });
    }
}

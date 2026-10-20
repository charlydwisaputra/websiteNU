<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Anggota;
use App\Models\Banom;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\SiteStats;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
                ->where('name', '!=', '...')
                ->whereNotNull('position')
                ->where('position', '!=', '')
                ->where('position', '!=', '...')
                ->where(function ($q) {
                    $q->whereNull('user_id')
                        ->orWhereHas('user', fn ($u) => $u->where('role', '!=', User::ROLE_ADMIN));
                })
                ->when(
                    Anggota::where('is_public', true)
                        ->whereIn('status', ['Aktif', 'aktif'])
                        ->where('is_pengurus', true)
                        ->whereNotNull('position')
                        ->where('position', '!=', '')
                        ->exists(),
                    fn ($q) => $q->where('is_pengurus', true)
                )
                ->orderByRaw('CASE WHEN urutan > 0 THEN urutan ELSE 999 END')
                ->orderBy('urutan', 'asc')
                ->orderBy('id', 'asc')
                ->take(6)
                ->get()
            : collect();
        $siteStats = app(SiteStats::class)->getStats();
        $stats = [
            'anggota' => $siteStats['anggota']['raw'],
            'anggota_display' => $siteStats['anggota']['formatted'],
            'banom' => $siteStats['banom']['raw'],
            'banom_display' => $siteStats['banom']['formatted'],
            'kegiatan' => $siteStats['kegiatan']['raw'],
            'kegiatan_display' => $siteStats['kegiatan']['formatted'],
            'galeri' => class_exists(Galeri::class) ? Galeri::count() : 0,
            0 => ['value' => $siteStats['anggota']['raw'], 'display' => $siteStats['anggota']['formatted'], 'label' => 'Anggota & Warga Aktif'],
            1 => ['value' => $siteStats['banom']['raw'], 'display' => $siteStats['banom']['formatted'], 'label' => 'Badan Otonom & Lembaga'],
            2 => ['value' => $siteStats['kegiatan']['raw'], 'display' => $siteStats['kegiatan']['formatted'], 'label' => 'Kegiatan Rutin / Bulan'],
            3 => ['value' => class_exists(Galeri::class) ? Galeri::count() : 0, 'label' => 'Dokumentasi'],
        ];

        $prayer = $this->getPrayerData();
        $heroMedia = $this->getHeroMediaData();

        return view('welcome', compact('beritas', 'stats', 'galeris', 'agendas', 'penguruses', 'prayer', 'heroMedia'));
    }

    public function tentang()
    {
        // 1. Data Pengurus Murni dari Tabel Anggota (is_pengurus = true, status aktif, position tidak kosong)
        $pengurusAnggotas = class_exists(Anggota::class)
            ? Anggota::where('is_pengurus', true)
                ->whereIn('status', ['Aktif', 'aktif'])
                ->whereNotNull('position')
                ->where('position', '!=', '')
                ->where('name', '!=', '...')
                ->orderByRaw('CASE WHEN urutan > 0 THEN urutan ELSE 999 END')
                ->orderBy('urutan')
                ->get()
            : collect();

        $syuriyahList = [];
        $tanfidziyahList = [];
        $pengurus = [];

        foreach ($pengurusAnggotas as $member) {
            $pos = trim($member->position ?? '');
            $name = trim($member->name ?? '');

            if ($pos === '' || $name === '' || $name === '...') {
                continue;
            }

            $pengurus[$pos] = $name;

            $item = [
                'nama' => $name,
                'jabatan' => $pos,
                'photo' => $member->photo,
                'urutan' => $member->urutan ?: 999,
            ];

            // Pengelompokan Syuriyah vs Tanfidziyah berdasarkan pola jabatan NU
            $posLower = strtolower($pos);
            if (str_contains($posLower, 'syuriyah') || str_contains($posLower, 'rais') || str_contains($posLower, 'katib') || str_contains($posLower, "a'wan") || str_contains($posLower, 'awan')) {
                $syuriyahList[] = $item;
            } else {
                $tanfidziyahList[] = $item;
            }
        }

        // 2. Banner Halaman
        $bannerWebp1920 = 'images/banner-1-1920.webp';
        $bannerWebp1280 = 'images/banner-1-1280.webp';
        $bannerWebp768 = 'images/banner-1-768.webp';
        $bannerJpg1920 = 'images/banner-1-1920.jpg';

        $hasBannerImage = file_exists(public_path($bannerWebp1920))
            || file_exists(public_path($bannerJpg1920))
            || file_exists(public_path('images/banner-1.jpg'));

        // 3. Foto Sejarah Singkat (Prioritas: Galeri Asli -> Potongan Banner -> Sembunyikan)
        $latestGaleri = null;
        if (class_exists(Galeri::class)) {
            $galeriCandidates = Galeri::whereNotNull('photo')
                ->where('photo', '!=', '')
                ->latest()
                ->take(10)
                ->get();

            foreach ($galeriCandidates as $candidate) {
                $isPlaceholder = str_contains(strtolower($candidate->photo), 'placeholder');
                if (! $isPlaceholder && (Storage::disk('public')->exists($candidate->photo) || file_exists(public_path('storage/'.$candidate->photo)))) {
                    $latestGaleri = $candidate;
                    break;
                }
            }
        }

        $galeriPhoto = $latestGaleri?->photo;
        $hasRealGaleriPhoto = ! empty($galeriPhoto)
            && ! str_contains(strtolower($galeriPhoto), 'placeholder')
            && (
                Storage::disk('public')->exists($galeriPhoto)
                || file_exists(public_path('storage/'.$galeriPhoto))
            );
        $hasBannerCrop = file_exists(public_path('images/sejarah-768.webp')) || file_exists(public_path('images/sejarah.jpg'));
        $sejarahPhotoType = $hasRealGaleriPhoto ? 'galeri' : ($hasBannerCrop ? 'banner' : null);
        $realCaption = $hasRealGaleriPhoto ? trim($latestGaleri->keterangan ?: ($latestGaleri->title ?: '')) : '';
        $hasFlagNu = file_exists(public_path('images/flag-nu.jpg'));

        // 4. Konten Terpusat dari Config
        $tentangDeskripsi = config('organisasi.tentang_deskripsi');
        $sejarahParagraphs = config('organisasi.sejarah', []);
        $kutipanSejarah = config('organisasi.sejarah_kutipan');
        $visi = config('organisasi.visi');
        $misi = config('organisasi.misi', []);
        $kegiatanRutin = config('organisasi.kegiatan_rutin', []);
        $peta = config('organisasi.peta', [
            'lat' => config('organisasi.lokasi_lat', -7.4218),
            'lng' => config('organisasi.lokasi_lng', 109.1608),
            'zoom' => 14,
        ]);

        // 5. Statistik singkat dari SiteStats
        $siteStats = app(SiteStats::class)->getStats();
        $stats = [
            'anggota' => $siteStats['anggota'] ?? null,
            'banom' => $siteStats['banom'] ?? null,
        ];

        // 6. Deretan logo Banom untuk tautan ke Ekosistem
        $banomDb = class_exists(Banom::class)
            ? Banom::orderBy('urutan')->get()
            : collect();

        if ($banomDb->isNotEmpty()) {
            $banoms = $banomDb->map(fn (Banom $b) => [
                'nama' => $b->nama,
                'singkatan' => $b->singkatan,
                'logo_url' => $b->logo_url,
            ])->filter(fn ($b) => ! empty($b['logo_url']))->values()->all();
        } else {
            $banoms = collect(config('organisasi.banom', []))->map(fn ($b) => [
                'nama' => $b['nama'],
                'singkatan' => $b['singkatan'],
                'logo_url' => Banom::resolveLogoUrl($b['logo'] ?? null, $b['singkatan'] ?? null),
            ])->filter(fn ($b) => ! empty($b['logo_url']))->values()->all();
        }

        return view('tentang', compact(
            'pengurus',
            'syuriyahList',
            'tanfidziyahList',
            'latestGaleri',
            'stats',
            'banoms',
            'kegiatanRutin',
            'bannerWebp1920',
            'bannerWebp1280',
            'bannerWebp768',
            'bannerJpg1920',
            'hasBannerImage',
            'sejarahPhotoType',
            'galeriPhoto',
            'realCaption',
            'hasFlagNu',
            'tentangDeskripsi',
            'sejarahParagraphs',
            'kutipanSejarah',
            'visi',
            'misi',
            'peta'
        ));
    }

    public function ekosistem()
    {
        // 1. Ambil banom dari DB jika ada
        $banomDb = class_exists(Banom::class)
            ? Banom::with('koordinator')->orderBy('urutan')->get()
            : collect();

        // 2. Banner Halaman (selaras dengan halaman Tentang)
        $bannerWebp1920 = 'images/banner-1-1920.webp';
        $bannerWebp1280 = 'images/banner-1-1280.webp';
        $bannerWebp768 = 'images/banner-1-768.webp';
        $bannerJpg1920 = 'images/banner-1-1920.jpg';

        $hasBannerImage = file_exists(public_path($bannerWebp1920))
            || file_exists(public_path($bannerJpg1920))
            || file_exists(public_path('images/banner-1.jpg'));

        // 3. Statistik dari SiteStats
        $siteStats = app(SiteStats::class)->getStats();
        $stats = [
            'banom' => $siteStats['banom']['raw'],
            'banom_display' => $siteStats['banom']['formatted'],
            'anggota' => $siteStats['anggota']['raw'],
            'anggota_display' => $siteStats['anggota']['formatted'],
            'kegiatan' => $siteStats['kegiatan']['raw'],
            'kegiatan_display' => $siteStats['kegiatan']['formatted'],
        ];

        // 4. Pemetaan kategori sasaran dan grup filter
        $targetCategories = [
            'Muslimat' => ['sasaran' => 'Perempuan Dewasa', 'group' => 'perempuan'],
            'Fatayat' => ['sasaran' => 'Perempuan Muda', 'group' => 'perempuan'],
            'IPPNU' => ['sasaran' => 'Pelajar Putri', 'group' => 'perempuan'],
            'GP Ansor' => ['sasaran' => 'Pemuda', 'group' => 'pemuda'],
            'IPNU' => ['sasaran' => 'Pelajar Putra', 'group' => 'pemuda'],
            'PMII' => ['sasaran' => 'Mahasiswa', 'group' => 'mahasiswa'],
        ];

        if ($banomDb->isNotEmpty()) {
            $banoms = $banomDb->map(function (Banom $b) use ($targetCategories) {
                $meta = $targetCategories[$b->singkatan] ?? ['sasaran' => $b->singkatan, 'group' => 'semua'];

                $memberCount = Anggota::where('is_public', true)
                    ->whereIn('status', ['Aktif', 'aktif'])
                    ->whereDoesntHave('user', fn ($q) => $q->where('role', 'admin'))
                    ->where(function ($q) use ($b) {
                        $q->where('banom_id', $b->id)
                            ->orWhere('id', $b->koordinator_id)
                            ->orWhere('position', 'like', "%{$b->singkatan}%");
                    })
                    ->count();

                return [
                    'id' => $b->id,
                    'nama' => $b->nama,
                    'singkatan' => $b->singkatan,
                    'slug' => $b->slug ?: Str::slug($b->singkatan),
                    'deskripsi' => $b->deskripsi ?? '',
                    'logo' => $b->logo,
                    'logo_url' => $b->logo_url,
                    'koordinator' => $b->koordinator?->name ?? '',
                    'jadwal_rutin' => '',
                    'kategori_sasaran' => $meta['sasaran'],
                    'kategori_group' => $meta['group'],
                    'anggota_count' => $memberCount,
                    'koordinator_whatsapp' => $b->koordinator?->whatsapp ?? $b->koordinator?->phone ?? null,
                ];
            })->all();
        } else {
            $banoms = collect(config('organisasi.banom', []))->map(function ($b) use ($targetCategories) {
                $singkatan = $b['singkatan'] ?? '';
                $meta = $targetCategories[$singkatan] ?? ['sasaran' => $singkatan, 'group' => 'semua'];

                return array_merge($b, [
                    'slug' => Str::slug($singkatan),
                    'logo_url' => Banom::resolveLogoUrl($b['logo'] ?? null, $singkatan),
                    'kategori_sasaran' => $meta['sasaran'],
                    'kategori_group' => $meta['group'],
                    'anggota_count' => 0,
                    'koordinator_whatsapp' => null,
                ]);
            })->all();
        }

        return view('ekosistem', compact(
            'banoms',
            'stats',
            'hasBannerImage',
            'bannerWebp1920',
            'bannerWebp1280',
            'bannerWebp768',
            'bannerJpg1920'
        ));
    }

    public function beritaIndex()
    {
        $beritas = class_exists(Berita::class) ? Berita::where('status', 'published')->latest()->get() : collect();

        return view('berita.index', compact('beritas'));
    }

    public function show(int $id)
    {
        $berita = class_exists(Berita::class) ? Berita::with('author')->findOrFail($id) : abort(404);

        $terkait = collect();
        if (class_exists(Berita::class)) {
            $query = Berita::where('id', '!=', $id)->where('status', 'published');
            if (! empty($berita->category)) {
                $query->where('category', $berita->category);
            }
            $terkait = $query->latest()->take(3)->get();

            if ($terkait->count() < 3) {
                $excludeIds = $terkait->pluck('id')->push($id)->all();
                $additional = Berita::whereNotIn('id', $excludeIds)
                    ->where('status', 'published')
                    ->latest()
                    ->take(3 - $terkait->count())
                    ->get();
                $terkait = $terkait->concat($additional);
            }
        }

        return view('berita-detail', compact('berita', 'terkait'));
    }

    /**
     * Fetch prayer times from Aladhan API (Kemenag RI method), cached per day in WIB.
     * Includes reliable fallback if network/API is temporarily unavailable.
     *
     * @return array{timings: array, hijri: array, source: string, timezone: string, is_fallback: bool}|null
     */
    private function getPrayerData(): ?array
    {
        if (! config('site.prayer.enabled', true)) {
            return null;
        }

        $tz = config('site.prayer.timezone', 'Asia/Jakarta');
        $today = now($tz)->format('Y-m-d');
        $cacheKey = "prayer_times:{$today}";
        $cacheSeconds = max(3600, (int) now($tz)->endOfDay()->diffInSeconds(now($tz)));

        return Cache::remember($cacheKey, $cacheSeconds, function () use ($tz) {
            try {
                $response = Http::timeout(4)
                    ->retry(1, 150)
                    ->get('https://api.aladhan.com/v1/timings', [
                        'latitude' => config('site.prayer.latitude', -7.4218),
                        'longitude' => config('site.prayer.longitude', 109.1608),
                        'method' => config('site.prayer.method', 20), // 20 = Kementerian Agama RI
                        'timezonestring' => $tz,
                        'adjustment' => config('site.prayer.hijri_adjustment', 0),
                    ]);

                if ($response->successful()) {
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
                            'Subuh' => $data['timings']['Fajr'] ?? '04:25',
                            'Dzuhur' => $data['timings']['Dhuhr'] ?? '11:45',
                            'Ashar' => $data['timings']['Asr'] ?? '15:02',
                            'Maghrib' => $data['timings']['Maghrib'] ?? '17:51',
                            'Isya' => $data['timings']['Isha'] ?? '19:01',
                        ],
                        'hijri' => [
                            'day' => $hijri['day'] ?? '',
                            'month' => $hijriMonths[$monthNum] ?? ($hijri['month']['en'] ?? ''),
                            'year' => $hijri['year'] ?? '',
                        ],
                        'source' => 'Kementerian Agama RI (via Aladhan API)',
                        'timezone' => 'WIB',
                        'is_fallback' => false,
                    ];
                }
            } catch (\Throwable) {
                // Return fallback below
            }

            // Fallback: Jadwal standar Banyumas & Sekitarnya (WIB)
            return [
                'timings' => [
                    'Subuh' => '04:25',
                    'Dzuhur' => '11:45',
                    'Ashar' => '15:02',
                    'Maghrib' => '17:51',
                    'Isya' => '19:01',
                ],
                'hijri' => [
                    'day' => '',
                    'month' => '',
                    'year' => '',
                ],
                'source' => 'Jadwal Standar Banyumas (WIB - Cadangan)',
                'timezone' => 'WIB',
                'is_fallback' => true,
            ];
        });
    }

    /**
     * Ambil data banner media hero (video desktop, video mobile, dan poster) dari site_settings dengan fallback aman.
     */
    protected function getHeroMediaData(): array
    {
        $videoDesktopPath = (string) SiteSetting::get('hero_video_desktop', '');
        $videoMobilePath = (string) SiteSetting::get('hero_video_mobile', '');
        $posterPath = (string) SiteSetting::get('hero_poster', '');

        $videoDesktopUrl = null;
        $videoDesktopType = 'video/mp4';
        $videoDesktopWebmUrl = null;

        if (! empty($videoDesktopPath) && (Storage::disk('public')->exists($videoDesktopPath) || file_exists(public_path('storage/'.$videoDesktopPath)))) {
            $videoDesktopUrl = asset('storage/'.$videoDesktopPath);
            $videoDesktopType = str_ends_with(strtolower($videoDesktopPath), '.webm') ? 'video/webm' : 'video/mp4';

            // Jika desktop MP4, cek apakah ada WebM berdampingan untuk dual-source
            if ($videoDesktopType === 'video/mp4') {
                $webmAlt = preg_replace('/\.mp4$/i', '.webm', $videoDesktopPath);
                if (! empty($webmAlt) && (Storage::disk('public')->exists($webmAlt) || file_exists(public_path('storage/'.$webmAlt)))) {
                    $videoDesktopWebmUrl = asset('storage/'.$webmAlt);
                }
            }
        } elseif (file_exists(public_path('videos/village_sample.mp4'))) {
            $videoDesktopUrl = asset('videos/village_sample.mp4');
            $videoDesktopType = 'video/mp4';
            if (file_exists(public_path('videos/village_sample.webm'))) {
                $videoDesktopWebmUrl = asset('videos/village_sample.webm');
            }
        }

        $videoMobileUrl = null;
        $videoMobileType = 'video/mp4';
        if (! empty($videoMobilePath) && (Storage::disk('public')->exists($videoMobilePath) || file_exists(public_path('storage/'.$videoMobilePath)))) {
            $videoMobileUrl = asset('storage/'.$videoMobilePath);
            $videoMobileType = str_ends_with(strtolower($videoMobilePath), '.webm') ? 'video/webm' : 'video/mp4';
        }

        $posterUrl = null;
        if (! empty($posterPath) && (Storage::disk('public')->exists($posterPath) || file_exists(public_path('storage/'.$posterPath)))) {
            $posterUrl = asset('storage/'.$posterPath);
        } elseif (file_exists(public_path('images/banner-1-1920.webp'))) {
            $posterUrl = asset('images/banner-1-1920.webp');
        } elseif (file_exists(public_path('images/banner-1-1280.webp'))) {
            $posterUrl = asset('images/banner-1-1280.webp');
        } elseif (file_exists(public_path('images/banner-1.jpg'))) {
            $posterUrl = asset('images/banner-1.jpg');
        }

        return [
            'video_desktop_url' => $videoDesktopUrl,
            'video_desktop_type' => $videoDesktopType,
            'video_desktop_webm_url' => $videoDesktopWebmUrl,
            'video_mobile_url' => $videoMobileUrl,
            'video_mobile_type' => $videoMobileType,
            'poster_url' => $posterUrl,
            'has_video' => ! empty($videoDesktopUrl) || ! empty($videoMobileUrl),
            'has_poster' => ! empty($posterUrl),
        ];
    }
}

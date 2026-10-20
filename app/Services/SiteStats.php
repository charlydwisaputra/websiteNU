<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\Anggota;
use App\Models\Banom;
use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class SiteStats
{
    /**
     * Cache key for site statistics.
     */
    public const CACHE_KEY = 'site_stats_data';

    /**
     * Cache TTL in seconds (60 seconds).
     */
    public const CACHE_TTL = 60;

    /**
     * Get all statistics data (cached).
     *
     * @return array{
     *     anggota: array{raw: int, formatted: string, label: string},
     *     kegiatan: array{raw: int, formatted: string, label: string},
     *     banom: array{raw: int, formatted: string, label: string}
     * }
     */
    public function getStats(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $anggotaMode = SiteSetting::get('stats_anggota_mode', 'auto');
            $anggotaCount = $this->getAnggotaAktifCount();
            $anggotaPlus = $anggotaMode === 'manual' && (bool) SiteSetting::get('stats_anggota_plus', false);

            $kegiatanMode = SiteSetting::get('stat.kegiatan_mode', SiteSetting::get('stats_kegiatan_mode', 'auto'));
            $kegiatanCount = $this->getKegiatanPerBulanCount();
            $manualKegiatanRaw = (string) SiteSetting::get('stat.kegiatan_per_bulan', SiteSetting::get('stats_kegiatan_manual', '12'));
            $kegiatanPlus = $kegiatanMode === 'manual' && ((bool) SiteSetting::get('stats_kegiatan_plus', false) || str_ends_with(trim($manualKegiatanRaw), '+'));

            $banomMode = SiteSetting::get('stats_banom_mode', 'auto');
            $banomCount = $this->getBanomCount();
            $banomPlus = $banomMode === 'manual' && (bool) SiteSetting::get('stats_banom_plus', false);

            $kegiatanFormatted = (string) $kegiatanCount;
            if ($kegiatanMode === 'manual') {
                $trimmed = trim($manualKegiatanRaw);
                $kegiatanFormatted = ($kegiatanPlus && ! str_ends_with($trimmed, '+')) ? "{$trimmed}+" : $trimmed;
            }

            return [
                'anggota' => [
                    'raw' => $anggotaCount,
                    'formatted' => $anggotaPlus ? "{$anggotaCount}+" : (string) $anggotaCount,
                    'label' => 'Anggota & Warga Aktif',
                ],
                'kegiatan' => [
                    'raw' => $kegiatanCount,
                    'formatted' => $kegiatanFormatted,
                    'label' => 'Kegiatan Rutin / Bulan',
                ],
                'banom' => [
                    'raw' => $banomCount,
                    'formatted' => $banomPlus ? "{$banomCount}+" : (string) $banomCount,
                    'label' => 'Badan Otonom & Lembaga',
                ],
            ];
        });
    }

    /**
     * Clear cached statistics immediately.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get base query for active public members (single source of truth).
     *
     * @return Builder<Anggota>
     */
    public function getAnggotaAktifQuery()
    {
        return Anggota::query()
            ->where('is_public', true)
            ->whereIn('status', ['Aktif', 'aktif'])
            ->whereDoesntHave('user', function ($q) {
                $q->where('role', 'admin');
            });
    }

    /**
     * Calculate active members count.
     */
    public function getAnggotaAktifCount(): int
    {
        if (SiteSetting::get('stats_anggota_mode', 'auto') === 'manual') {
            return (int) SiteSetting::get('stats_anggota_manual', 0);
        }

        return $this->getAnggotaAktifQuery()->count();
    }

    /**
     * Calculate banom count.
     */
    public function getBanomCount(): int
    {
        if (SiteSetting::get('stats_banom_mode', 'auto') === 'manual') {
            return (int) SiteSetting::get('stats_banom_manual', 6);
        }

        return Banom::count();
    }

    /**
     * Calculate routine activities per month based on Pengaturan definition.
     */
    public function getKegiatanPerBulanCount(): int
    {
        $mode = SiteSetting::get('stat.kegiatan_mode', SiteSetting::get('stats_kegiatan_mode', 'auto'));
        if ($mode === 'manual') {
            return (int) SiteSetting::get('stat.kegiatan_per_bulan', SiteSetting::get('stats_kegiatan_manual', 12));
        }

        // Definisi di Pengaturan: otomatis dari agenda
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $agendaCount = Agenda::query()
            ->whereBetween('tanggal_mulai', [$startOfMonth, $endOfMonth])
            ->count();

        if ($agendaCount === 0) {
            $agendaCount = Agenda::count();
        }

        return $agendaCount;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan situs (Statistik & Hero Media).
     */
    public function index(): View
    {
        $kegiatanMode = SiteSetting::get('stat.kegiatan_mode', 'auto');
        $kegiatanPerBulan = SiteSetting::get('stat.kegiatan_per_bulan', '12');

        $heroVideoDesktop = SiteSetting::get('hero_video_desktop', '');
        $heroVideoMobile = SiteSetting::get('hero_video_mobile', '');
        $heroPoster = SiteSetting::get('hero_poster', '');

        // Cek apakah file fisik masih ada di disk
        $heroMedia = [
            'video_desktop' => (! empty($heroVideoDesktop) && Storage::disk('public')->exists($heroVideoDesktop)) ? $heroVideoDesktop : null,
            'video_mobile' => (! empty($heroVideoMobile) && Storage::disk('public')->exists($heroVideoMobile)) ? $heroVideoMobile : null,
            'poster' => (! empty($heroPoster) && Storage::disk('public')->exists($heroPoster)) ? $heroPoster : null,
        ];

        return view('admin.pengaturan.index', compact(
            'kegiatanMode',
            'kegiatanPerBulan',
            'heroMedia'
        ));
    }

    /**
     * Perbarui pengaturan statistik dan media hero situs.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'stat_kegiatan_mode' => 'nullable|in:auto,manual',
            'stat_kegiatan_per_bulan' => 'nullable|numeric|min:0',
            'hero_video_desktop' => 'nullable|file|mimes:mp4,webm|max:15360',
            'hero_video_mobile' => 'nullable|file|mimes:mp4,webm|max:15360',
            'hero_poster' => 'nullable|file|mimes:jpg,jpeg,webp,png|max:4096',
            'delete_hero_video_desktop' => 'nullable|boolean',
            'delete_hero_video_mobile' => 'nullable|boolean',
            'delete_hero_poster' => 'nullable|boolean',
        ], [
            'hero_video_desktop.mimes' => 'Format video desktop harus berupa MP4 atau WebM.',
            'hero_video_desktop.max' => 'Ukuran video desktop tidak boleh melebihi 15 MB.',
            'hero_video_mobile.mimes' => 'Format video mobile harus berupa MP4 atau WebM.',
            'hero_video_mobile.max' => 'Ukuran video mobile tidak boleh melebihi 15 MB.',
            'hero_poster.mimes' => 'Format poster harus berupa JPG, JPEG, WebP, atau PNG.',
            'hero_poster.max' => 'Ukuran poster tidak boleh melebihi 4 MB.',
        ]);

        $mode = $request->input('stat_kegiatan_mode', 'auto');
        SiteSetting::set('stat.kegiatan_mode', in_array($mode, ['auto', 'manual']) ? $mode : 'auto');

        if ($request->has('stat_kegiatan_per_bulan')) {
            SiteSetting::set('stat.kegiatan_per_bulan', (string) $request->input('stat_kegiatan_per_bulan', '12'));
        }

        // 1. Hero Video Desktop
        if ($request->boolean('delete_hero_video_desktop')) {
            $oldDesktop = SiteSetting::get('hero_video_desktop', '');
            if (! empty($oldDesktop) && Storage::disk('public')->exists($oldDesktop)) {
                Storage::disk('public')->delete($oldDesktop);
            }
            SiteSetting::set('hero_video_desktop', '');
        }

        if ($request->hasFile('hero_video_desktop')) {
            $oldDesktop = SiteSetting::get('hero_video_desktop', '');
            if (! empty($oldDesktop) && Storage::disk('public')->exists($oldDesktop)) {
                Storage::disk('public')->delete($oldDesktop);
            }
            $desktopPath = $request->file('hero_video_desktop')->store('hero', 'public');
            SiteSetting::set('hero_video_desktop', $desktopPath);
        }

        // 2. Hero Video Mobile
        if ($request->boolean('delete_hero_video_mobile')) {
            $oldMobile = SiteSetting::get('hero_video_mobile', '');
            if (! empty($oldMobile) && Storage::disk('public')->exists($oldMobile)) {
                Storage::disk('public')->delete($oldMobile);
            }
            SiteSetting::set('hero_video_mobile', '');
        }

        if ($request->hasFile('hero_video_mobile')) {
            $oldMobile = SiteSetting::get('hero_video_mobile', '');
            if (! empty($oldMobile) && Storage::disk('public')->exists($oldMobile)) {
                Storage::disk('public')->delete($oldMobile);
            }
            $mobilePath = $request->file('hero_video_mobile')->store('hero', 'public');
            SiteSetting::set('hero_video_mobile', $mobilePath);
        }

        // 3. Hero Poster
        if ($request->boolean('delete_hero_poster')) {
            $oldPoster = SiteSetting::get('hero_poster', '');
            if (! empty($oldPoster) && Storage::disk('public')->exists($oldPoster)) {
                Storage::disk('public')->delete($oldPoster);
            }
            SiteSetting::set('hero_poster', '');
        }

        if ($request->hasFile('hero_poster')) {
            $oldPoster = SiteSetting::get('hero_poster', '');
            if (! empty($oldPoster) && Storage::disk('public')->exists($oldPoster)) {
                Storage::disk('public')->delete($oldPoster);
            }
            $posterPath = $request->file('hero_poster')->store('hero', 'public');
            SiteSetting::set('hero_poster', $posterPath);
        }

        Cache::forget('site_settings_all');
        Cache::forget('site_stats_data');

        return redirect()->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan situs dan media hero berhasil disimpan.');
    }
}

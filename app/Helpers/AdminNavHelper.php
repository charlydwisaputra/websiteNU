<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class AdminNavHelper
{
    /**
     * Resolve URL for an admin navigation item safely using Route::has().
     *
     * @param  array<string, mixed>  $item
     */
    public static function resolveUrl(array $item): string
    {
        if (! empty($item['url'])) {
            return url($item['url']);
        }

        if (! empty($item['route']) && Route::has($item['route'])) {
            return route($item['route']);
        }

        if (! empty($item['fallback_route']) && Route::has($item['fallback_route'])) {
            return route($item['fallback_route']);
        }

        return '#';
    }

    /**
     * Check if a navigation item is currently active.
     *
     * @param  array<string, mixed>  $item
     */
    public static function isActive(array $item): bool
    {
        if (empty($item['match'])) {
            return false;
        }

        return request()->routeIs($item['match']);
    }

    /**
     * Get main menu items from config.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getMenu(): array
    {
        return config('admin-nav.menu', []);
    }

    /**
     * Get footer items from config.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getFooter(): array
    {
        return config('admin-nav.footer', []);
    }

    /**
     * Get 4 primary items flagged with mobile_bar => true.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getMobileBarItems(): array
    {
        return array_values(array_filter(self::getMenu(), function (array $item) {
            return ! empty($item['mobile_bar']);
        }));
    }

    /**
     * Get remaining menu items for the bottom sheet (Agenda, Banom, Pengurus, Pengaturan, Profil).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getSheetItems(): array
    {
        $nonBarMenu = array_values(array_filter(self::getMenu(), function (array $item) {
            return empty($item['mobile_bar']);
        }));

        $profilFooter = array_values(array_filter(self::getFooter(), function (array $item) {
            return ($item['key'] ?? '') !== 'keluar';
        }));

        return array_merge($nonBarMenu, $profilFooter);
    }

    /**
     * Get the logout action item.
     *
     * @return array<string, mixed>|null
     */
    public static function getLogoutItem(): ?array
    {
        foreach (self::getFooter() as $item) {
            if (($item['key'] ?? '') === 'keluar') {
                return $item;
            }
        }

        return null;
    }

    /**
     * Check if current route is within any of the bottom sheet items.
     */
    public static function isSheetActive(): bool
    {
        foreach (self::getSheetItems() as $item) {
            if (self::isActive($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get bottom bar items for mobile: 4 fixed items + "Lainnya" trigger.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getBottomBar(): array
    {
        $items = self::getMobileBarItems();

        $items[] = [
            'key' => 'lainnya',
            'label' => 'Lainnya',
            'action' => 'open-sheet',
            'icon' => 'M4 6h16M4 12h16M4 18h16',
            'title' => 'Menu Lengkap Lainnya',
            'aria_label' => 'Buka Menu Lainnya',
            'is_active' => self::isSheetActive(),
        ];

        return $items;
    }
}

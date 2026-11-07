<?php

use App\Models\Galeri;

test('dokumentasi page renders mobile grid with consistent 2 columns and aspect ratio', function () {
    Galeri::create([
        'title' => 'Dokumentasi Apel Akbar',
        'category' => 'Banser',
        'photo' => 'galeri/apel.jpg',
    ]);

    Galeri::create([
        'title' => 'Dokumentasi Pengajian Rutin',
        'category' => 'Muslimat',
        'photo' => 'galeri/pengajian.jpg',
    ]);

    $response = $this->get(route('galeri'));

    $response->assertOk();
    $response->assertSee('Dokumentasi Apel Akbar');
    $response->assertSee('grid-cols-2');
    $response->assertSee('aspect-[4/3]');
    $response->assertSee('object-cover');
    // Ensure unprefixed min-h-[260px] is absent so mobile grid does not have vertical empty space
    $response->assertDontSee('min-h-[260px]');
    // Ensure mobile gap is 2.5 (10px, between 8-12px)
    $response->assertSee('gap-2.5');
});
test('dokumentasi page renders multi photo album data for lightbox with correct photo count and photos array', function () {
    Galeri::create([
        'title' => 'Dokumentasi Harlah',
        'category' => 'Umum',
        'photo' => 'galeri/cover.jpg',
        'photos' => [
            'galeri/cover.jpg',
            'galeri/foto2.jpg',
            'galeri/foto3.jpg',
        ],
    ]);

    $response = $this->get(route('galeri'));
    $response->assertOk();
    $response->assertSee('Dokumentasi Harlah');
    $response->assertSee('foto2.jpg');
    $response->assertSee('foto3.jpg');
    $response->assertSee('lb-thumbs-wrap');
    $response->assertSee('lb-album-nav');
});

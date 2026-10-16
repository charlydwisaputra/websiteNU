<?php

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\User;

test('berita index page renders empty state when no news exist', function () {
    $response = $this->get(route('berita.index'));

    $response->assertStatus(200);
    $response->assertSee('Belum ada warta yang dipublikasikan.');
});

test('berita index page renders news grid and alpine bindings when news exist', function () {
    $user = User::factory()->create();

    Berita::create([
        'title' => 'Kegiatan Utama',
        'category' => 'Kegiatan',
        'content' => 'Isi berita kegiatan utama untuk komunitas.',
        'status' => 'published',
        'author_id' => $user->id,
    ]);

    $response = $this->get(route('berita.index'));

    $response->assertStatus(200);
    $response->assertSee('Kegiatan Utama');
    $response->assertSee('get hero()', false);
    $response->assertSee('get side()', false);
    $response->assertSee('get rest()', false);
    $response->assertSee('x-for="item in side"', false);
    $response->assertSee('x-for="item in rest"', false);
    $response->assertSee('Muat lebih banyak', false);
});

test('welcome and ekosistem pages do not render placeholder coordinator', function () {
    $welcomeResponse = $this->get(route('home'));
    $welcomeResponse->assertStatus(200);
    $welcomeResponse->assertDontSee('[ISI: Nama Koordinator]');

    $ekosistemResponse = $this->get(route('ekosistem'));
    $ekosistemResponse->assertStatus(200);
    $ekosistemResponse->assertDontSee('[ISI: Nama]');
    $ekosistemResponse->assertDontSee('[ISI: Waktu]');
});

test('welcome page renders dokumentasi horizontal snap gallery on mobile and grid on desktop', function () {
    $galeri = Galeri::create([
        'title' => 'Foto Dokumentasi Pengajian',
        'category' => 'Kegiatan',
        'photo' => 'photos/galeri/test.jpg',
    ]);

    $response = $this->get(route('home'));
    $response->assertStatus(200);
    $response->assertSee('Foto Dokumentasi Pengajian');
    $response->assertSee('snap-x');
    $response->assertSee('snap-mandatory');
    $response->assertSee('overscroll-x-contain');
    $response->assertSee('flex-[0_0_75%]', false);
    $response->assertSee('loading="lazy"', false);
    $response->assertSee('Buka Galeri Penuh');

    $galeri->delete();
});

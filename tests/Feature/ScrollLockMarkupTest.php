<?php

/**
 * Memverifikasi bahwa tidak ada kelas scroll-lock (overflow-hidden, overflow-x-hidden,
 * menu-open) yang hadir pada <html> atau <body> saat halaman publik pertama kali dirender.
 *
 * overflow-x: hidden dan max-width: 100vw pada html+body secara bersamaan memblokir
 * scroll vertikal di banyak browser — ini adalah akar penyebab bug scroll yang dilaporkan.
 */
describe('ScrollLock Markup — halaman publik', function () {
    $publicRoutes = [
        'home' => '/',
        'tentang' => '/tentang',
        'ekosistem' => '/ekosistem',
        'berita.index' => '/berita',
        'galeri' => '/dokumentasi',
        'anggota' => '/anggota',
    ];

    foreach ($publicRoutes as $name => $path) {
        it("tidak ada overflow-hidden / overflow-x-hidden / menu-open pada <html> atau <body> — {$name} ({$path})", function () use ($path) {
            $response = $this->get($path);
            $response->assertSuccessful();

            $html = $response->getContent();

            // <html> tidak boleh punya class scroll-lock
            expect($html)->not->toContain('html class="overflow-hidden')
                ->and($html)->not->toContain('html class="menu-open')
                ->and($html)->not->toContain('html class="overflow-x-hidden');

            // <body> tidak boleh punya overflow-hidden / overflow-x-hidden
            preg_match('/<body[^>]*>/', $html, $bodyTagMatches);
            $bodyTag = $bodyTagMatches[0] ?? '';

            expect($bodyTag)->not->toContain('overflow-hidden')
                ->and($bodyTag)->not->toContain('overflow-x-hidden');

            // style attribute pada body tidak boleh mengandung overflow: hidden
            expect($bodyTag)->not->toContain('overflow: hidden')
                ->and($bodyTag)->not->toContain('overflow:hidden');
        });
    }

    it('tidak ada class menu-open pada <html> saat render awal Beranda', function () {
        $html = $this->get('/')->getContent();

        preg_match('/<html[^>]*>/', $html, $htmlTagMatches);
        $htmlTag = $htmlTagMatches[0] ?? '';

        expect($htmlTag)->not->toContain('menu-open');
    });

    it('CSS pada Beranda mendefinisikan html.menu-open bukan html.overflow-hidden sebagai scroll lock', function () {
        $html = $this->get('/')->getContent();

        // Harus ada definisi menu-open
        expect($html)->toContain('html.menu-open');

        // TIDAK boleh ada html.overflow-hidden atau body.overflow-hidden
        expect($html)->not->toContain('html.overflow-hidden')
            ->and($html)->not->toContain('body.overflow-hidden');
    });

    it('overflow-x hidden tidak muncul bersamaan pada selector html dan body', function () {
        $html = $this->get('/')->getContent();

        // Pola "html, body { ... overflow-x: hidden ... }" adalah akar penyebab bug
        expect($html)->not->toMatch('/html\s*,\s*body\s*\{[^}]*overflow-x\s*:\s*hidden/');
    });
});

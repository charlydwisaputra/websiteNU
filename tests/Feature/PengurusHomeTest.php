<?php

use App\Models\Anggota;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('welcome page renders empty state when no pengurus exists', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Pengurus');
    $response->assertSee('Belum ada data pengurus yang ditampilkan.');
    $response->assertSee('Lihat Semua Anggota');
    $response->assertSee(route('anggota'));
});

test('welcome page renders top 6 pengurus with initials or photos and 3-column layout', function () {
    // Create 10 active public members with positions A through J
    $letters = range('A', 'J');
    foreach ($letters as $letter) {
        Anggota::create([
            'name' => "Pengurus {$letter}",
            'position' => "Jabatan {$letter}",
            'status' => 'Aktif',
            'is_public' => true,
        ]);
    }

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Pengurus');
    $response->assertDontSee('Belum ada data pengurus yang ditampilkan.');

    // Top 6 (A through F) should be present
    foreach (range('A', 'F') as $letter) {
        $response->assertSee("Pengurus {$letter}");
        $response->assertSee("Jabatan {$letter}");
    }

    // 7 through 10 (G through J) should not be rendered
    $response->assertDontSee('Pengurus G');
    $response->assertDontSee('Pengurus H');
    $response->assertDontSee('Pengurus I');
    $response->assertDontSee('Pengurus J');

    // Assert layout classes: 2 cols on mobile, 3 cols on desktop (3+3)
    $response->assertSee('grid-cols-2');
    $response->assertSee('md:grid-cols-3');
    $response->assertSee('Lihat Semua Anggota');
});

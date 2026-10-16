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

test('welcome page renders top 8 pengurus with initials or photos and responsive snap layout', function () {
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

    // Top 8 (A through H) should be present
    foreach (range('A', 'H') as $letter) {
        $response->assertSee("Pengurus {$letter}");
        $response->assertSee("Jabatan {$letter}");
    }

    // 9 and 10 (I and J) should not be rendered
    $response->assertDontSee('Pengurus I');
    $response->assertDontSee('Pengurus J');

    // Assert layout classes
    $response->assertSee('flex-[0_0_40%]', false);
    $response->assertSee('snap-x');
    $response->assertSee('snap-mandatory');
    $response->assertSee('scrollbar-none');
    $response->assertSee('md:grid-cols-4');
    $response->assertSee('Lihat Semua Anggota');
});

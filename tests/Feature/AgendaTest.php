<?php

use App\Models\Agenda;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('welcome page renders empty state when no upcoming agenda exists', function () {
    Agenda::create([
        'judul' => 'Agenda Masa Lalu',
        'tanggal_mulai' => Carbon::now()->subDays(2),
        'lokasi' => 'Masjid Al-Ikhlas',
        'deskripsi' => 'Agenda yang sudah lewat.',
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Agenda Terdekat');
    $response->assertSee('Belum ada agenda terjadwal.');
    $response->assertDontSee('Agenda Masa Lalu');
});

test('welcome page renders upcoming agendas up to 5 ordered by date ascending with responsive snap', function () {
    for ($i = 1; $i <= 7; $i++) {
        Agenda::create([
            'judul' => "Agenda Hari Ke-{$i}",
            'tanggal_mulai' => Carbon::today()->addDays($i)->setHour(9),
            'lokasi' => "Gedung MWC NU Lantai {$i}",
            'deskripsi' => "Deskripsi kegiatan hari ke-{$i}",
        ]);
    }

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Agenda Terdekat');
    $response->assertDontSee('Belum ada agenda terjadwal.');

    // 5 agendas should be visible
    $response->assertSee('Agenda Hari Ke-1');
    $response->assertSee('Agenda Hari Ke-2');
    $response->assertSee('Agenda Hari Ke-3');
    $response->assertSee('Agenda Hari Ke-4');
    $response->assertSee('Agenda Hari Ke-5');

    // 6th and 7th should not be shown (take 5)
    $response->assertDontSee('Agenda Hari Ke-6');
    $response->assertDontSee('Agenda Hari Ke-7');

    // Assert layout classes
    $response->assertSee('flex-[0_0_80%]', false);
    $response->assertSee('snap-x');
    $response->assertSee('snap-mandatory');
    $response->assertSee('overscroll-x-contain');
    $response->assertSee('scrollbar-none');
    $response->assertSee('md:grid-cols-3');
});

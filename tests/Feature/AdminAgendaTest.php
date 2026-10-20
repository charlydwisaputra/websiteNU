<?php

use App\Models\Agenda;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access admin agenda pages and is redirected to login', function () {
    $response = $this->get(route('admin.agenda.index'));
    $response->assertRedirect(route('login'));

    $responseCreate = $this->get(route('admin.agenda.create'));
    $responseCreate->assertRedirect(route('login'));
});

test('regular user without admin role receives 403 forbidden on admin agenda routes', function () {
    $user = User::factory()->create([
        'role' => User::ROLE_ANGGOTA,
    ]);

    $response = $this->actingAs($user)->get(route('admin.agenda.index'));
    $response->assertStatus(403);
});

test('admin can view agenda index page with listings', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $agenda = Agenda::create([
        'judul' => 'Pengajian Akbar Ahad Pagi',
        'tanggal_mulai' => Carbon::now()->addDays(3),
        'lokasi' => 'Masjid Baiturrahman Kalisari',
        'deskripsi' => 'Pengajian rutin bersama para kiai.',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.agenda.index'));

    $response->assertStatus(200);
    $response->assertSee('Agenda Kegiatan');
    $response->assertSee('Pengajian Akbar Ahad Pagi');
    $response->assertSee('Masjid Baiturrahman Kalisari');
});

test('admin can filter agenda by search keyword and upcoming/past filter', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    Agenda::create([
        'judul' => 'Rapat Pleno MWC',
        'tanggal_mulai' => Carbon::now()->addDays(5),
        'lokasi' => 'Kantor MWC',
    ]);

    Agenda::create([
        'judul' => 'Santunan Anak Yatim Lalu',
        'tanggal_mulai' => Carbon::now()->subDays(10),
        'lokasi' => 'Aula Serbaguna',
    ]);

    // Test search keyword
    $searchResponse = $this->actingAs($admin)->get(route('admin.agenda.index', ['search' => 'Rapat Pleno']));
    $searchResponse->assertStatus(200);
    $searchResponse->assertSee('Rapat Pleno MWC');
    $searchResponse->assertDontSee('Santunan Anak Yatim Lalu');

    // Test upcoming filter
    $upcomingResponse = $this->actingAs($admin)->get(route('admin.agenda.index', ['filter' => 'upcoming']));
    $upcomingResponse->assertStatus(200);
    $upcomingResponse->assertSee('Rapat Pleno MWC');
    $upcomingResponse->assertDontSee('Santunan Anak Yatim Lalu');

    // Test past filter
    $pastResponse = $this->actingAs($admin)->get(route('admin.agenda.index', ['filter' => 'past']));
    $pastResponse->assertStatus(200);
    $pastResponse->assertSee('Santunan Anak Yatim Lalu');
    $pastResponse->assertDontSee('Rapat Pleno MWC');
});

test('admin can view create agenda page', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.agenda.create'));

    $response->assertStatus(200);
    $response->assertSee('Tambah Agenda Baru');
    $response->assertSee('Nama / Judul Kegiatan');
});

test('admin can store new agenda with valid data', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $payload = [
        'judul' => 'Khataman Al-Quran Bulanan',
        'tanggal_mulai' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
        'lokasi' => 'Gedung Serbaguna Kalisari',
        'deskripsi' => 'Dihadiri seluruh banom dan lembaga ranting.',
    ];

    $response = $this->actingAs($admin)->post(route('admin.agenda.store'), $payload);

    $response->assertRedirect(route('admin.agenda.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('agendas', [
        'judul' => 'Khataman Al-Quran Bulanan',
        'lokasi' => 'Gedung Serbaguna Kalisari',
    ]);
});

test('storing agenda requires judul, tanggal_mulai, and lokasi', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.agenda.store'), []);

    $response->assertSessionHasErrors(['judul', 'tanggal_mulai', 'lokasi']);
});

test('admin can view edit agenda page', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $agenda = Agenda::create([
        'judul' => 'Majelis Dzikir & Sholawat',
        'tanggal_mulai' => Carbon::now()->addDays(4),
        'lokasi' => 'Masjid Jami Kalisari',
        'deskripsi' => 'Kegiatan rutin malam Jumat.',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.agenda.edit', $agenda->id));

    $response->assertStatus(200);
    $response->assertSee('Edit Agenda Kegiatan');
    $response->assertSee('Majelis Dzikir & Sholawat');
});

test('admin can update existing agenda', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $agenda = Agenda::create([
        'judul' => 'Agenda Awal',
        'tanggal_mulai' => Carbon::now()->addDays(2),
        'lokasi' => 'Lokasi Lama',
        'deskripsi' => 'Deskripsi lama',
    ]);

    $updatePayload = [
        'judul' => 'Agenda Diperbarui',
        'tanggal_mulai' => Carbon::now()->addDays(3)->format('Y-m-d H:i:s'),
        'lokasi' => 'Lokasi Baru Kalisari',
        'deskripsi' => 'Deskripsi baru yang diperbarui',
    ];

    $response = $this->actingAs($admin)->put(route('admin.agenda.update', $agenda->id), $updatePayload);

    $response->assertRedirect(route('admin.agenda.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('agendas', [
        'id' => $agenda->id,
        'judul' => 'Agenda Diperbarui',
        'lokasi' => 'Lokasi Baru Kalisari',
    ]);
});

test('admin can delete agenda', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $agenda = Agenda::create([
        'judul' => 'Agenda yang akan dihapus',
        'tanggal_mulai' => Carbon::now()->addDays(1),
        'lokasi' => 'Ruang Rapat',
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.agenda.destroy', $agenda->id));

    $response->assertRedirect(route('admin.agenda.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('agendas', [
        'id' => $agenda->id,
    ]);
});

<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Banom;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BanomDanPengurusSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed / Update Pengurus Inti di tabel anggota
        $pengurusInti = [
            [
                'name' => 'KH. Ahmad Fauzan',
                'position' => 'Rais Syuriyah',
                'urutan' => 1,
                'region' => 'Kalisari',
            ],
            [
                'name' => 'Ustadz Ridwan Hasan',
                'position' => 'Wakil Rais Syuriyah',
                'urutan' => 2,
                'region' => 'Kalisari',
            ],
            [
                'name' => 'H. Mukhtar Sholeh',
                'position' => 'Ketua Tanfidziyah',
                'urutan' => 3,
                'region' => 'Kalisari',
            ],
            [
                'name' => 'Bambang Sulistyo',
                'position' => 'Wakil Ketua',
                'urutan' => 4,
                'region' => 'Kalisari',
            ],
            [
                'name' => 'Siti Nurjanah',
                'position' => 'Sekretaris',
                'urutan' => 5,
                'region' => 'Kalisari',
            ],
            [
                'name' => 'Hj. Aminah Zahra',
                'position' => 'Bendahara',
                'urutan' => 6,
                'region' => 'Kalisari',
            ],
        ];

        foreach ($pengurusInti as $p) {
            Anggota::firstOrCreate(
                ['name' => $p['name']],
                [
                    'position' => $p['position'],
                    'region' => $p['region'],
                    'status' => 'Aktif',
                    'is_public' => true,
                    'is_pengurus' => true,
                    'urutan' => $p['urutan'],
                    'joined_at' => now()->subYears(2),
                ]
            );
        }

        // 2. Seed / Update Koordinator Banom di tabel anggota
        $koordinators = [
            'Muslimat' => ['name' => 'Hj. Siti Rohmah',   'position' => 'Koordinator Muslimat'],
            'Fatayat' => ['name' => 'Dewi Lestari',       'position' => 'Koordinator Fatayat'],
            'GP Ansor' => ['name' => 'Fikri Ramadhan',     'position' => 'Ketua GP Ansor'],
            'IPNU' => ['name' => 'Rizki Aditya',       'position' => 'Ketua IPNU'],
            'IPPNU' => ['name' => 'Anisa Nur Fadilah',  'position' => 'Ketua IPPNU'],
            'PMII' => ['name' => 'Hendra Kusuma',      'position' => 'Ketua PMII'],
        ];

        $koordinatorModels = [];
        foreach ($koordinators as $key => $k) {
            $koordinatorModels[$key] = Anggota::firstOrCreate(
                ['name' => $k['name']],
                [
                    'position' => $k['position'],
                    'region' => 'Kalisari',
                    'status' => 'Aktif',
                    'is_public' => true,
                    'is_pengurus' => false,
                    'urutan' => 0,
                    'joined_at' => now()->subYear(),
                ]
            );
        }

        // 3. Seed / Update 6 Badan Otonom dengan relasi koordinator_id
        $banoms = [
            [
                'nama' => 'Muslimat NU',
                'slug' => 'muslimat',
                'singkatan' => 'Muslimat',
                'deskripsi' => 'Wadah perjuangan perempuan NU dalam membangun keluarga dan masyarakat yang religius, cerdas, dan sejahtera.',
                'logo' => 'MuslimatNU.png',
                'koordinator_id' => $koordinatorModels['Muslimat']->id,
                'urutan' => 1,
            ],
            [
                'nama' => 'Fatayat NU',
                'slug' => 'fatayat',
                'singkatan' => 'Fatayat',
                'deskripsi' => 'Gerakan perempuan muda NU yang berfokus pada pemberdayaan, pendidikan, dan advokasi sosial kemasyarakatan.',
                'logo' => 'FATAYAT-NU.png',
                'koordinator_id' => $koordinatorModels['Fatayat']->id,
                'urutan' => 2,
            ],
            [
                'nama' => 'GP Ansor',
                'slug' => 'gp-ansor',
                'singkatan' => 'GP Ansor',
                'deskripsi' => 'Barisan pemuda pengawal ulama dan NKRI yang berkomitmen pada pengembangan kader tangguh dan berintegritas.',
                'logo' => 'GP-Ansor.png',
                'koordinator_id' => $koordinatorModels['GP Ansor']->id,
                'urutan' => 3,
            ],
            [
                'nama' => 'IPNU',
                'slug' => 'ipnu',
                'singkatan' => 'IPNU',
                'deskripsi' => 'Ikatan Pelajar Nahdlatul Ulama yang mewadahi pelajar dan santri dalam mengembangkan potensi diri dan organisasi.',
                'logo' => 'IPNU.png',
                'koordinator_id' => $koordinatorModels['IPNU']->id,
                'urutan' => 4,
            ],
            [
                'nama' => 'IPPNU',
                'slug' => 'ippnu',
                'singkatan' => 'IPPNU',
                'deskripsi' => 'Ikatan Pelajar Putri Nahdlatul Ulama, wadah pelajar putri untuk belajar, berjuang, dan bertakwa.',
                'logo' => 'IPPNU.png',
                'koordinator_id' => $koordinatorModels['IPPNU']->id,
                'urutan' => 5,
            ],
            [
                'nama' => 'PMII',
                'slug' => 'pmii',
                'singkatan' => 'PMII',
                'deskripsi' => 'Pergerakan Mahasiswa Islam Indonesia, wadah mahasiswa berasaskan Islam Ahlussunnah wal Jamaah.',
                'logo' => 'PMII.png',
                'koordinator_id' => $koordinatorModels['PMII']->id,
                'urutan' => 6,
            ],
        ];

        foreach ($banoms as $b) {
            // Sinkronkan ke storage jika belum ada dan berkas bawaan tersedia di public/images/banom/
            if (! empty($b['logo'])) {
                $storagePath = 'logo-organisasi/'.$b['logo'];
                $publicSource = public_path('images/banom/'.$b['logo']);
                if (file_exists($publicSource) && ! Storage::disk('public')->exists($storagePath)) {
                    Storage::disk('public')->put($storagePath, (string) file_get_contents($publicSource));
                }
            }

            $banomModel = Banom::firstOrCreate(
                ['slug' => $b['slug']],
                [
                    'nama' => $b['nama'],
                    'singkatan' => $b['singkatan'],
                    'deskripsi' => $b['deskripsi'],
                    'logo' => $b['logo'],
                    'koordinator_id' => $b['koordinator_id'],
                    'urutan' => $b['urutan'],
                ]
            );

            // Assign banom_id pada koordinator jika belum diset
            if ($b['koordinator_id']) {
                $koordinatorAnggota = Anggota::find($b['koordinator_id']);
                if ($koordinatorAnggota && ! $koordinatorAnggota->banom_id) {
                    $koordinatorAnggota->update(['banom_id' => $banomModel->id]);
                }
            }
        }

        // 4. Default Site Settings (Pengurus, Koordinator Banom, & Statistik)
        SiteSetting::firstOrCreate(['key' => 'pengurus.rais_syuriyah'], ['value' => 'KH. Ahmad Fauzan']);
        SiteSetting::firstOrCreate(['key' => 'pengurus.wakil_rais'], ['value' => 'Ustadz Ridwan Hasan']);
        SiteSetting::firstOrCreate(['key' => 'pengurus.ketua'], ['value' => 'H. Mukhtar Sholeh']);
        SiteSetting::firstOrCreate(['key' => 'pengurus.wakil_ketua'], ['value' => 'Bambang Sulistyo']);
        SiteSetting::firstOrCreate(['key' => 'pengurus.sekretaris'], ['value' => 'Siti Nurjanah']);
        SiteSetting::firstOrCreate(['key' => 'pengurus.bendahara'], ['value' => 'Hj. Aminah Zahra']);

        SiteSetting::firstOrCreate(['key' => 'banom.koordinator.muslimat'], ['value' => 'Hj. Siti Rohmah']);
        SiteSetting::firstOrCreate(['key' => 'banom.koordinator.fatayat'], ['value' => 'Dewi Lestari']);
        SiteSetting::firstOrCreate(['key' => 'banom.koordinator.gp-ansor'], ['value' => 'Fikri Ramadhan']);
        SiteSetting::firstOrCreate(['key' => 'banom.koordinator.ipnu'], ['value' => 'Rizki Aditya']);
        SiteSetting::firstOrCreate(['key' => 'banom.koordinator.ippnu'], ['value' => 'Anisa Nur Fadilah']);
        SiteSetting::firstOrCreate(['key' => 'banom.koordinator.pmii'], ['value' => 'Hendra Kusuma']);

        SiteSetting::firstOrCreate(['key' => 'stat.kegiatan_per_bulan'], ['value' => '12']);
    }
}

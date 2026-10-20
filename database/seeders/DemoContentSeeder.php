<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Anggota;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAuthor();
        $this->seedPengurus();
        $this->seedBeritas();
        $this->seedAgendas();
        $this->seedGaleris();
    }

    private function seedAuthor(): void
    {
        User::firstOrCreate(
            ['email' => 'redaksi@nukalisari.id'],
            [
                'name' => 'Redaksi NU Kalisari',
                'password' => Hash::make('redaksi-demo-2026'),
                'role' => User::ROLE_ANGGOTA,
            ]
        );
    }

    private function seedPengurus(): void
    {
        $pengurus = [
            ['name' => 'KH. Ahmad Fauzan',   'position' => 'Rais Syuriyah',       'region' => 'Kalisari'],
            ['name' => 'H. Mukhtar Sholeh',   'position' => 'Ketua Tanfidziyah',   'region' => 'Kalisari'],
            ['name' => 'Siti Nurjanah',        'position' => 'Sekretaris',           'region' => 'Kalisari'],
            ['name' => 'Hj. Aminah Zahra',     'position' => 'Bendahara',            'region' => 'Kalisari'],
            ['name' => 'Ustadz Ridwan Hasan', 'position' => 'Wakil Rais Syuriyah', 'region' => 'Kalisari'],
            ['name' => 'Bambang Sulistyo',     'position' => 'Wakil Ketua',          'region' => 'Kalisari'],
        ];

        foreach ($pengurus as $data) {
            Anggota::firstOrCreate(
                ['name' => $data['name']],
                [
                    'position' => $data['position'],
                    'region' => $data['region'],
                    'status' => 'Aktif',
                    'is_public' => true,
                    'joined_at' => now()->subYears(2),
                ]
            );
        }
    }

    private function seedBeritas(): void
    {
        $author = User::where('email', 'redaksi@nukalisari.id')->first() ?? User::first();

        $articles = [
            [
                'title' => 'Pengajian Rutin Selapanan Kembali Digelar, Warga Antusias Hadir',
                'category' => 'Kegiatan',
                'content' => '<p>Pengajian rutin selapanan NU Kalisari kembali digelar dan dihadiri warga dari berbagai dusun. Kegiatan diawali dengan pembacaan tahlil dan shalawat bersama, dilanjutkan tausiyah yang mengajak jamaah menjaga kerukunan.</p><p>Acara ditutup dengan doa bersama dan ramah tamah. Pengurus berharap kegiatan ini terus menjadi ruang silaturahmi yang menguatkan hubungan antarwarga.</p>',
            ],
            [
                'title' => 'Pengurus Ranting Bahas Program Kerja Triwulan',
                'category' => 'Organisasi',
                'content' => '<p>Pengurus ranting NU Kalisari menggelar rapat untuk membahas rencana program kerja triwulan mendatang. Pembahasan mencakup kegiatan keagamaan rutin, kolaborasi dengan badan otonom, serta pembenahan dokumentasi.</p><p>Setiap bidang menyampaikan usulan kegiatan dan pembagian tugas. Hasil rapat dijadikan acuan pelaksanaan program.</p>',
            ],
            [
                'title' => 'Bakti Sosial Warga, Santunan dan Gotong Royong Bersama',
                'category' => 'Sosial',
                'content' => '<p>Pengurus bersama badan otonom menggelar kegiatan bakti sosial berupa santunan bagi warga yang membutuhkan dan kerja bakti membersihkan lingkungan.</p><p>Kegiatan sosial seperti ini diharapkan menjadi kebiasaan yang terus dirawat, sebagai wujud nyata kepedulian dan semangat gotong royong.</p>',
            ],
            [
                'title' => 'Pelatihan Kader Muda: Belajar Organisasi dan Kepemimpinan',
                'category' => 'Kegiatan',
                'content' => '<p>Pelatihan kader digelar untuk membekali generasi muda dengan dasar-dasar organisasi, kepemimpinan, dan keterampilan berkomunikasi.</p><p>Melalui pelatihan ini, pengurus berharap lahir kader-kader baru yang siap berkontribusi dalam kegiatan keagamaan dan sosial di lingkungan Kalisari.</p>',
            ],
            [
                'title' => 'Jadwal Kegiatan Bulan Ini dan Pendaftaran Pelatihan',
                'category' => 'Pengumuman',
                'content' => '<p>Diberitahukan kepada seluruh warga dan pengurus bahwa jadwal kegiatan terbaru telah diperbarui. Bagi yang berminat mengikuti pelatihan kader, silakan menghubungi pengurus ranting.</p><p>Mohon memeriksa halaman Warta secara berkala untuk mendapatkan informasi terbaru.</p>',
            ],
        ];

        foreach ($articles as $i => $data) {
            Berita::firstOrCreate(
                ['title' => $data['title']],
                [
                    'category' => $data['category'],
                    'content' => $data['content'],
                    'status' => 'published',
                    'author_id' => $author->id,
                    'created_at' => now()->subDays(count($articles) - $i),
                    'updated_at' => now()->subDays(count($articles) - $i),
                ]
            );
        }
    }

    private function seedAgendas(): void
    {
        $agendas = [
            [
                'judul' => 'Pengajian Selapanan Ahad Legi',
                'tanggal_mulai' => Carbon::now()->addDays(7)->setHour(9)->setMinute(0),
                'lokasi' => 'Mushola Al-Hikmah, Kalisari',
                'deskripsi' => 'Pengajian rutin setiap Ahad Legi dengan pemateri dari pengurus syuriyah.',
            ],
            [
                'judul' => 'Yasinan dan Tahlilan Rutin',
                'tanggal_mulai' => Carbon::now()->addDays(3)->setHour(19)->setMinute(30),
                'lokasi' => 'Masjid Baitul Muttaqin, Kalisari',
                'deskripsi' => 'Pembacaan Yasin dan tahlil bersama, dilanjutkan kultum singkat.',
            ],
            [
                'judul' => 'Rapat Pengurus Bulanan',
                'tanggal_mulai' => Carbon::now()->addDays(14)->setHour(13)->setMinute(0),
                'lokasi' => 'Balai Desa Kalisari',
                'deskripsi' => 'Evaluasi program bulanan dan koordinasi kegiatan mendatang.',
            ],
            [
                'judul' => 'Pelatihan Administrasi Organisasi',
                'tanggal_mulai' => Carbon::now()->addDays(21)->setHour(8)->setMinute(0),
                'lokasi' => 'Gedung Serbaguna Kalisari',
                'deskripsi' => 'Pelatihan tata kelola administrasi untuk pengurus dan kader baru.',
            ],
        ];

        foreach ($agendas as $data) {
            Agenda::firstOrCreate(
                ['judul' => $data['judul']],
                $data
            );
        }
    }

    private function seedGaleris(): void
    {
        $items = [
            ['title' => 'Pengajian Selapanan', 'category' => null],
            ['title' => 'Rapat Pengurus',       'category' => null],
            ['title' => 'Bakti Sosial',          'category' => null],
            ['title' => 'Pelatihan Kader',       'category' => 'GP Ansor'],
            ['title' => 'Kegiatan Muslimat',     'category' => 'Muslimat'],
            ['title' => 'Yasinan Bersama',       'category' => null],
        ];

        foreach ($items as $data) {
            Galeri::firstOrCreate(
                ['title' => $data['title']],
                [
                    'category' => $data['category'],
                    'photo' => 'galeri/placeholder.svg',
                ]
            );
        }
    }
}

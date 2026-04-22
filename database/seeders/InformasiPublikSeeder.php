<?php

namespace Database\Seeders;

use App\Models\InformasiPublik;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InformasiPublikSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding Informasi Publik (Pengumuman & Agenda)...');

        // ── PENGUMUMAN ──
        $pengumumans = [
            [
                'title'          => 'Pemadaman Listrik Bergilir & Perbaikan Jaringan Air Bersih di Dusun I dan II',
                'excerpt'        => 'Sehubungan dengan adanya perbaikan gardu induk PLN dan pipanisasi saluran air bersih desa, akan dilakukan pemadaman listrik sementara dan penghentian aliran air.',
                'content_html'   => '<p>Sehubungan dengan adanya perbaikan gardu induk PLN dan pipanisasi saluran air bersih desa, akan dilakukan pemadaman listrik sementara dan penghentian aliran air pada hari Sabtu mulai pukul 08.00 - 15.00 WIB.</p><p>Dimohon warga mempersiapkan cadangan air untuk keperluan sehari-hari. Atas perhatian dan kerja sama warga, kami ucapkan terima kasih.</p>',
                'start_date'     => now()->addDays(5)->setTime(8, 0),
                'end_date'       => now()->addDays(5)->setTime(15, 0),
                'location'       => 'Dusun I & Dusun II',
                'contact_person' => 'Pak Edi (Kaur Umum) - 0812 5678 9012',
                'time_text'      => '08.00 - 15.00 WIB',
                'is_important'   => true,
            ],
            [
                'title'          => 'Pendaftaran Penerima Bantuan PKH Tahap II Tahun 2026',
                'excerpt'        => 'Bagi warga yang memenuhi kriteria dan belum terdaftar sebagai penerima PKH, silakan mendaftarkan diri ke kantor desa.',
                'content_html'   => '<p>Pemerintah Desa Sindangmukti membuka pendaftaran calon penerima Program Keluarga Harapan (PKH) Tahap II Tahun 2026.</p><h3>Syarat Pendaftaran</h3><ul><li>KTP dan KK asli beserta fotokopi</li><li>Surat Keterangan Tidak Mampu (SKTM)</li><li>Foto 3x4 sebanyak 2 lembar</li></ul><p>Pendaftaran dibuka selama 2 minggu sejak tanggal pengumuman ini.</p>',
                'start_date'     => now()->subDays(2)->setTime(8, 0),
                'end_date'       => now()->addDays(12)->setTime(16, 0),
                'location'       => 'Kantor Desa Sindangmukti',
                'contact_person' => 'Ibu Nining (Kasi Kesra) - 0813 4567 8901',
                'time_text'      => '08.00 - 16.00 WIB',
                'is_important'   => false,
            ],
            [
                'title'          => 'Himbauan Waspada Cuaca Ekstrem & Potensi Banjir',
                'excerpt'        => 'BMKG mengeluarkan peringatan dini cuaca ekstrem untuk wilayah Ciamis dan sekitarnya. Warga diimbau untuk waspada.',
                'content_html'   => '<p>Berdasarkan informasi dari BMKG, wilayah Ciamis dan sekitarnya berpotensi mengalami cuaca ekstrem berupa hujan lebat disertai angin kencang.</p><p>Warga diimbau untuk:</p><ul><li>Tidak beraktivitas di luar rumah saat hujan lebat</li><li>Menjauhi tebing dan sungai</li><li>Mempersiapkan tas siaga bencana</li></ul>',
                'start_date'     => now()->setTime(0, 0),
                'end_date'       => now()->addDays(7)->setTime(23, 59),
                'location'       => null,
                'contact_person' => 'Posko Siaga - 0812 3456 0000',
                'time_text'      => null,
                'is_important'   => true,
            ],
        ];

        foreach ($pengumumans as $data) {
            InformasiPublik::firstOrCreate(
                ['slug' => Str::slug($data['title'])],
                array_merge($data, [
                    'type'   => 'pengumuman',
                    'slug'   => Str::slug($data['title']),
                    'status' => 'publish',
                ])
            );
        }

        // ── AGENDA ──
        $agendas = [
            [
                'title'          => 'Penyuluhan Stunting & Posyandu Serentak',
                'excerpt'        => 'Kegiatan rutin bulanan bagi balita dan ibu hamil yang akan dipantau langsung oleh tim medis dari Puskesmas kecamatan.',
                'content_html'   => '<p>Pemerintah Desa Sindangmukti bekerja sama dengan Puskesmas Kecamatan mengundang seluruh ibu hamil dan orang tua yang memiliki anak usia balita (0-5 tahun) untuk hadir dalam kegiatan Posyandu Serentak dan Penyuluhan Pencegahan Stunting.</p><p>Layanan yang akan diberikan:</p><ul><li>Penimbangan berat badan dan pengukuran tinggi badan anak</li><li>Pemberian makanan tambahan (PMT) bergizi</li><li>Imunisasi dasar lengkap (bagi yang belum)</li><li>Penyuluhan gizi bagi ibu hamil dan menyusui</li></ul><p><strong>Catatan:</strong> Harap membawa Buku KIA (Kesehatan Ibu dan Anak) sebagai syarat pendaftaran.</p>',
                'start_date'     => now()->startOfMonth()->addDays(9)->setTime(8, 0),
                'end_date'       => now()->startOfMonth()->addDays(9)->setTime(12, 0),
                'location'       => 'Balai Desa Sindangmukti',
                'contact_person' => 'Ibu Siti Aminah (Bidan Desa) - 0812 3456 7890',
                'time_text'      => '08.00 - Selesai',
            ],
            [
                'title'          => 'Rapat Koordinasi BPD & Perangkat Desa (Musdes)',
                'excerpt'        => 'Pembahasan evaluasi rancangan Anggaran Pendapatan dan Belanja Desa (APBDes) Perubahan untuk kuartal akhir tahun.',
                'content_html'   => '<p>Musyawarah Desa (Musdes) akan membahas evaluasi rancangan APBDes Perubahan bersama seluruh anggota BPD dan perangkat desa.</p><p>Agenda pembahasan meliputi:</p><ul><li>Evaluasi realisasi APBDes semester I</li><li>Rencana perubahan alokasi anggaran</li><li>Pembahasan program prioritas semester II</li></ul>',
                'start_date'     => now()->startOfMonth()->addDays(14)->setTime(19, 30),
                'end_date'       => now()->startOfMonth()->addDays(14)->setTime(22, 0),
                'location'       => 'Ruang Rapat Kantor Desa',
                'contact_person' => 'Sekretaris Desa - 0813 7890 1234',
                'time_text'      => '19.30 - 22.00',
            ],
            [
                'title'          => 'Kerja Bakti Massal Normalisasi Saluran Irigasi',
                'excerpt'        => 'Diimbau kepada seluruh warga Dusun II laki-laki untuk membawa peralatan guna membersihkan saluran irigasi.',
                'content_html'   => '<p>Dalam rangka menjaga kelancaran distribusi air irigasi pertanian, akan diadakan kerja bakti massal membersihkan saluran irigasi sepanjang Dusun II.</p><p>Warga diminta membawa:</p><ul><li>Cangkul dan sabit</li><li>Pakaian kerja dan sepatu boot</li><li>Air minum pribadi</li></ul><p>Konsumsi akan disediakan oleh panitia.</p>',
                'start_date'     => now()->startOfMonth()->addDays(24)->setTime(7, 0),
                'end_date'       => now()->startOfMonth()->addDays(24)->setTime(11, 0),
                'location'       => 'Sepanjang Jalur Irigasi Dusun II',
                'contact_person' => 'Pak Dedi (Kepala Dusun II) - 0815 1234 5678',
                'time_text'      => '07.00 - 11.00',
            ],
            [
                'title'          => 'Pelatihan Keterampilan Menjahit bagi Ibu-Ibu PKK',
                'excerpt'        => 'Program pemberdayaan masyarakat berupa pelatihan menjahit tingkat dasar untuk ibu-ibu PKK Desa Sindangmukti.',
                'content_html'   => '<p>PKK Desa Sindangmukti mengadakan pelatihan keterampilan menjahit tingkat dasar. Peserta akan dibekali kemampuan membuat produk fashion sederhana yang bisa dipasarkan.</p><ul><li>Alat dan bahan disediakan</li><li>Sertifikat pelatihan</li><li>Kuota terbatas: 30 peserta</li></ul>',
                'start_date'     => now()->startOfMonth()->addDays(19)->setTime(9, 0),
                'end_date'       => now()->startOfMonth()->addDays(19)->setTime(15, 0),
                'location'       => 'Aula PKK Desa Sindangmukti',
                'contact_person' => 'Ibu Yati (Ketua PKK) - 0821 5678 9012',
                'time_text'      => '09.00 - 15.00',
            ],
            [
                'title'          => 'Senam Sehat Bersama Lansia',
                'excerpt'        => 'Kegiatan rutin senam bersama untuk warga lanjut usia di halaman kantor desa.',
                'content_html'   => '<p>Kegiatan senam sehat rutin untuk warga lanjut usia. Dipandu instruktur dari Puskesmas setempat.</p><p>Terbuka untuk seluruh warga lansia tanpa biaya pendaftaran.</p>',
                'start_date'     => now()->startOfMonth()->addDays(4)->setTime(6, 30),
                'end_date'       => now()->startOfMonth()->addDays(4)->setTime(8, 0),
                'location'       => 'Halaman Kantor Desa',
                'contact_person' => 'Kader Posyandu Lansia',
                'time_text'      => '06.30 - 08.00',
            ],
        ];

        foreach ($agendas as $data) {
            InformasiPublik::firstOrCreate(
                ['slug' => Str::slug($data['title'])],
                array_merge($data, [
                    'type'         => 'agenda',
                    'slug'         => Str::slug($data['title']),
                    'status'       => 'publish',
                    'is_important' => false,
                ])
            );
        }

        $this->command->info('  → Pengumuman & Agenda seeded successfully!');
    }
}

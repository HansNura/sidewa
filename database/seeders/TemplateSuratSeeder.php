<?php

namespace Database\Seeders;

use App\Models\TemplateSurat;
use Illuminate\Database\Seeder;

class TemplateSuratSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'nama'          => 'Surat Keterangan Tidak Mampu (SKTM)',
                'kode'          => 'sktm',
                'kategori'      => 'keterangan',
                'deskripsi'     => 'Template standar untuk warga prasejahtera.',
                'body_template' => "Yang bertanda tangan di bawah ini Kepala Desa Sindangmukti, Kecamatan Mangunjaya, Kabupaten Pangandaran, menerangkan dengan sebenarnya bahwa:\n\nNama: {{nama_pemohon}}\nNIK: {{nik_pemohon}}\nTempat/Tgl Lahir: {{ttl_pemohon}}\nPekerjaan: {{pekerjaan_pemohon}}\nAlamat: {{alamat_lengkap}}\n\nAdalah benar warga kami yang berdomisili di alamat tersebut dan tergolong keluarga Tidak Mampu.\n\nSurat keterangan ini dibuat untuk keperluan: {{keperluan}}\n\nDemikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.",
                'is_active'     => true,
                'versi'         => 'v1.2',
                'layout_settings' => ['show_kop' => true, 'show_ttd' => true, 'show_qr' => true, 'margin_top' => 3, 'margin_bottom' => 3, 'margin_left' => 3, 'margin_right' => 2.5],
            ],
            [
                'nama'          => 'Surat Keterangan Domisili',
                'kode'          => 'domisili',
                'kategori'      => 'keterangan',
                'deskripsi'     => 'Menerangkan domisili tempat tinggal warga.',
                'body_template' => "Yang bertanda tangan di bawah ini Kepala Desa Sindangmukti, Kecamatan Mangunjaya, Kabupaten Pangandaran, menerangkan dengan sebenarnya bahwa:\n\nNama: {{nama_pemohon}}\nNIK: {{nik_pemohon}}\nTempat/Tgl Lahir: {{ttl_pemohon}}\nPekerjaan: {{pekerjaan_pemohon}}\nAlamat: {{alamat_lengkap}}\n\nAdalah benar warga kami yang bertempat tinggal di alamat tersebut di atas.\n\nSurat keterangan ini diberikan untuk keperluan: {{keperluan}}\n\nDemikian surat keterangan ini dibuat dengan sebenarnya.",
                'is_active'     => true,
                'versi'         => 'v1.0',
                'layout_settings' => ['show_kop' => true, 'show_ttd' => true, 'show_qr' => true, 'margin_top' => 3, 'margin_bottom' => 3, 'margin_left' => 3, 'margin_right' => 2.5],
            ],
            [
                'nama'          => 'Surat Pengantar Usaha',
                'kode'          => 'pengantar_usaha',
                'kategori'      => 'pengantar',
                'deskripsi'     => 'Dokumen untuk pengajuan KUR atau legalitas UMKM dasar.',
                'body_template' => "Yang bertanda tangan di bawah ini Kepala Desa Sindangmukti, Kecamatan Mangunjaya, Kabupaten Pangandaran, menerangkan dengan sebenarnya bahwa:\n\nNama: {{nama_pemohon}}\nNIK: {{nik_pemohon}}\nPekerjaan: {{pekerjaan_pemohon}}\nAlamat: {{alamat_lengkap}}\nNama Usaha: {{nama_usaha}}\n\nAdalah benar warga kami yang menjalankan usaha sebagaimana tersebut di atas di wilayah Desa Sindangmukti.\n\nSurat pengantar ini diberikan untuk keperluan: {{keperluan}}\n\nDemikian surat pengantar ini dibuat untuk dapat dipergunakan sebagaimana mestinya.",
                'is_active'     => true,
                'versi'         => 'v1.0',
                'layout_settings' => ['show_kop' => true, 'show_ttd' => true, 'show_qr' => true, 'margin_top' => 3, 'margin_bottom' => 3, 'margin_left' => 3, 'margin_right' => 2.5],
            ],
            [
                'nama'          => 'Surat Pengantar Nikah (N1-N4)',
                'kode'          => 'pengantar_nikah',
                'kategori'      => 'pengantar',
                'deskripsi'     => 'Draft pengantar KUA. Belum selesai diedit.',
                'body_template' => "Yang bertanda tangan di bawah ini Kepala Desa Sindangmukti, Kecamatan Mangunjaya, Kabupaten Pangandaran, dengan ini memberikan Surat Pengantar Nikah untuk:\n\nNama: {{nama_pemohon}}\nNIK: {{nik_pemohon}}\nTempat/Tgl Lahir: {{ttl_pemohon}}\nAgama: {{agama_pemohon}}\nPekerjaan: {{pekerjaan_pemohon}}\nAlamat: {{alamat_lengkap}}\nNo. KK: {{no_kk}}\n\nBahwa yang bersangkutan bermaksud untuk melangsungkan pernikahan.\n\nSurat pengantar ini diberikan untuk keperluan pengurusan di Kantor Urusan Agama (KUA) setempat.",
                'is_active'     => false,
                'versi'         => 'v0.1',
                'layout_settings' => ['show_kop' => true, 'show_ttd' => true, 'show_qr' => false, 'margin_top' => 3, 'margin_bottom' => 3, 'margin_left' => 3, 'margin_right' => 2.5],
            ],
            [
                'nama'          => 'Surat Keterangan Kematian',
                'kode'          => 'kematian',
                'kategori'      => 'keterangan',
                'deskripsi'     => 'Keterangan meninggal dunia untuk administrasi.',
                'body_template' => "Yang bertanda tangan di bawah ini Kepala Desa Sindangmukti, Kecamatan Mangunjaya, Kabupaten Pangandaran, menerangkan bahwa:\n\nNama: {{nama_pemohon}}\nNIK: {{nik_pemohon}}\nTempat/Tgl Lahir: {{ttl_pemohon}}\nAlamat: {{alamat_lengkap}}\n\nTelah meninggal dunia pada tanggal yang tercantum.\n\nSurat keterangan ini dibuat untuk keperluan administrasi: {{keperluan}}",
                'is_active'     => true,
                'versi'         => 'v1.0',
                'layout_settings' => ['show_kop' => true, 'show_ttd' => true, 'show_qr' => true, 'margin_top' => 3, 'margin_bottom' => 3, 'margin_left' => 3, 'margin_right' => 2.5],
            ],
            [
                'nama'          => 'Surat Keterangan Pindah',
                'kode'          => 'pindah',
                'kategori'      => 'keterangan',
                'deskripsi'     => 'Keterangan pindah domisili ke luar wilayah desa.',
                'body_template' => "Yang bertanda tangan di bawah ini Kepala Desa Sindangmukti, Kecamatan Mangunjaya, Kabupaten Pangandaran, menerangkan bahwa:\n\nNama: {{nama_pemohon}}\nNIK: {{nik_pemohon}}\nTempat/Tgl Lahir: {{ttl_pemohon}}\nPekerjaan: {{pekerjaan_pemohon}}\nAlamat Asal: {{alamat_lengkap}}\n\nBermaksud untuk pindah domisili ke alamat tujuan yang telah dinyatakan.\n\nSurat keterangan ini dibuat untuk keperluan: {{keperluan}}",
                'is_active'     => true,
                'versi'         => 'v1.0',
                'layout_settings' => ['show_kop' => true, 'show_ttd' => true, 'show_qr' => true, 'margin_top' => 3, 'margin_bottom' => 3, 'margin_left' => 3, 'margin_right' => 2.5],
            ],
        ];

        foreach ($templates as $data) {
            TemplateSurat::updateOrCreate(
                ['kode' => $data['kode']],
                $data
            );
        }
    }
}

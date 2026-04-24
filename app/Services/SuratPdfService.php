<?php

namespace App\Services;

use App\Models\SuratPermohonan;
use App\Models\TemplateSurat;
use App\Models\VillageSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SuratPdfService
{
    /**
     * Generate a PDF for the given surat permohonan.
     * Returns the storage path of the generated PDF.
     */
    public function generate(SuratPermohonan $surat): string
    {
        $surat->load(['penduduk', 'kades']);
        $village = VillageSetting::instance();

        // Try to find a matching template
        $template = TemplateSurat::where('kode', $surat->jenis_surat)
            ->where('is_active', true)
            ->first();

        $body = $template
            ? $this->renderTemplate($template, $surat, $village)
            : $this->renderDefault($surat, $village);

        $pdf = Pdf::loadView('pdf.surat', [
            'surat'   => $surat,
            'village' => $village,
            'body'    => $body,
        ])->setPaper('a4');

        $filename = "surat_{$surat->id}_{$surat->jenis_surat}.pdf";
        $path = "surat-pdf/{$filename}";

        Storage::disk('public')->put($path, $pdf->output());

        $surat->update(['pdf_path' => $path]);

        return $path;
    }

    /**
     * Replace template placeholders with actual data.
     */
    private function renderTemplate(TemplateSurat $template, SuratPermohonan $surat, VillageSetting $village): string
    {
        $penduduk = $surat->penduduk;

        $replacements = [
            '{{nama_pemohon}}'    => $penduduk->nama ?? '-',
            '{{nik_pemohon}}'     => $penduduk->nik ?? '-',
            '{{ttl_pemohon}}'     => ($penduduk->tempat_lahir ?? '') . ', ' . ($penduduk->tanggal_lahir?->translatedFormat('d F Y') ?? '-'),
            '{{pekerjaan_pemohon}}' => $penduduk->pekerjaan ?? '-',
            '{{alamat_lengkap}}'  => $penduduk->alamatLengkap() ?? '-',
            '{{agama_pemohon}}'   => $penduduk->agama ?? '-',
            '{{jenis_kelamin}}'   => $penduduk->jenisKelaminLabel() ?? '-',
            '{{keperluan}}'       => $surat->keperluan ?? '-',
            '{{berlaku_hingga}}'  => $surat->formatBerlakuHingga(),
            '{{nama_usaha}}'      => $surat->nama_usaha ?? '-',
            '{{nomor_surat}}'     => $surat->nomor_tiket,
            '{{tahun}}'           => now()->year,
            '{{tanggal_surat}}'   => now()->translatedFormat('d F Y'),
            '{{nama_desa}}'       => $village->nama_desa,
            '{{kecamatan}}'       => $village->kecamatan,
            '{{kabupaten}}'       => $village->kabupaten,
            '{{nama_kades}}'      => $village->nama_kades,
            '{{nip_kades}}'       => $village->nip_kades ?? '-',
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template->body_template
        );
    }

    /**
     * Default letter body when no template exists.
     */
    private function renderDefault(SuratPermohonan $surat, VillageSetting $village): string
    {
        $p = $surat->penduduk;

        return "
            <p>Yang bertanda tangan di bawah ini, Kepala Desa {$village->nama_desa}, Kecamatan {$village->kecamatan},
            Kabupaten {$village->kabupaten}, menerangkan bahwa:</p>

            <table style='margin: 20px 0; line-height: 1.8;'>
                <tr><td style='width: 180px;'>Nama</td><td>: <strong>{$p->nama}</strong></td></tr>
                <tr><td>NIK</td><td>: {$p->nik}</td></tr>
                <tr><td>Tempat/Tgl Lahir</td><td>: {$p->tempat_lahir}, {$p->tanggal_lahir?->translatedFormat('d F Y')}</td></tr>
                <tr><td>Jenis Kelamin</td><td>: {$p->jenisKelaminLabel()}</td></tr>
                <tr><td>Agama</td><td>: {$p->agama}</td></tr>
                <tr><td>Pekerjaan</td><td>: {$p->pekerjaan}</td></tr>
                <tr><td>Alamat</td><td>: {$p->alamatLengkap()}</td></tr>
            </table>

            <p>Adalah benar warga Desa {$village->nama_desa} yang dikenal baik dan berkelakuan baik.</p>

            <p>Surat keterangan ini dibuat untuk keperluan: <strong>{$surat->keperluan}</strong></p>

            <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
        ";
    }
}

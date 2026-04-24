<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $surat->jenisLabel() }} - {{ $surat->nomor_tiket }}</title>
    <style>
        @page {
            margin: 2.5cm 2cm;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .kop-surat .desa {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .kop-surat .sub {
            font-size: 11pt;
        }

        .kop-surat .alamat {
            font-size: 9pt;
            color: #333;
        }

        .judul-surat {
            text-align: center;
            margin: 30px 0 20px;
        }

        .judul-surat h2 {
            font-size: 14pt;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 0;
        }

        .judul-surat .nomor {
            font-size: 11pt;
            margin-top: 5px;
        }

        .body-surat {
            text-align: justify;
        }

        .ttd-section {
            margin-top: 40px;
            float: right;
            width: 250px;
            text-align: center;
        }

        .ttd-section .tanggal {
            font-size: 11pt;
            margin-bottom: 5px;
        }

        .ttd-section .nama-kades {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 60px;
        }

        .ttd-section .nip {
            font-size: 10pt;
        }

        .tte-badge {
            margin-top: 10px;
            border: 1px solid #28a745;
            color: #28a745;
            font-size: 8pt;
            padding: 4px 8px;
            display: inline-block;
            border-radius: 4px;
        }

        .footer {
            clear: both;
            margin-top: 120px;
            font-size: 8pt;
            color: #999;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    {{-- Kop Surat --}}
    <div class="kop-surat">
        <div class="sub">Pemerintah Kabupaten {{ $village->kabupaten }}</div>
        <div class="sub">Kecamatan {{ $village->kecamatan }}</div>
        <div class="desa">Desa {{ $village->nama_desa }}</div>
        <div class="alamat">{{ $village->alamat ?? '' }} | Kode Pos: {{ $village->kode_pos ?? '' }}</div>
    </div>

    {{-- Judul Surat --}}
    <div class="judul-surat">
        <h2>{{ $surat->jenisLabel() }}</h2>
        <div class="nomor">Nomor: {{ $surat->nomor_tiket }}</div>
    </div>

    {{-- Body / Isi Surat --}}
    <div class="body-surat">
        {!! $body !!}
    </div>

    {{-- Tanda Tangan --}}
    <div class="ttd-section">
        <div class="tanggal">
            {{ $village->nama_desa }}, {{ now()->translatedFormat('d F Y') }}
        </div>
        <div>{{ $village->jabatan_kades ?? 'Kepala Desa' }}</div>

        <div class="nama-kades">{{ $village->nama_kades }}</div>
        @if($village->nip_kades)
            <div class="nip">NIP. {{ $village->nip_kades }}</div>
        @endif

        @if($surat->status === 'selesai' && $surat->kades_id)
            <div class="tte-badge">
                ✓ Ditandatangani secara elektronik (TTE)
            </div>
        @endif
    </div>

    <div class="footer">
        Dokumen ini diterbitkan secara elektronik melalui Sistem Informasi Desa {{ $village->nama_desa }}
        &bull; {{ $surat->nomor_tiket }}
    </div>

</body>
</html>

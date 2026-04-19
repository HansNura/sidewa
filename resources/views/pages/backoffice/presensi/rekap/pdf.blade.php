<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Kehadiran Pegawai - {{ $period }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header h2 { margin: 5px 0 0; font-size: 14px; font-weight: normal; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .table th { background-color: #f5f5f5; font-weight: bold; }
        .table td.left { text-align: left; }
        .footer { position: fixed; bottom: 0; left: 0; width: 100%; text-align: right; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
        .signature { width: 100%; margin-top: 50px; }
        .signature td { border: none; text-align: center; width: 50%; }
        .mt-50 { margin-top: 80px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pemerintah Desa Sindangmukti</h1>
        <h2>Laporan Rekapitulasi Kehadiran Tingkat Aparatur Desa</h2>
        <p>Periode: <strong>{{ $period }}</strong></p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th class="left">Nama Pegawai</th>
                <th>NIP</th>
                <th>Jabatan</th>
                <th width="10%">Hadir</th>
                <th width="10%">Terlambat</th>
                <th width="10%">Izin/Sakit</th>
                <th width="10%">Alpha</th>
                <th width="12%">Perform (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                @php
                    $totalAktif = $row['Hadir'] + $row['Terlambat'];
                    $totalKeseluruhan = $totalAktif + $row['Izin/Sakit'] + $row['Alpha'];
                    $persentase = $totalKeseluruhan > 0 ? round(($totalAktif / $totalKeseluruhan) * 100, 1) : 0;
                @endphp
                <tr>
                    <td>{{ $row['No'] }}</td>
                    <td class="left"><strong>{{ $row['Nama Pegawai'] }}</strong></td>
                    <td>{{ $row['NIP'] }}</td>
                    <td>{{ $row['Jabatan'] }}</td>
                    <td>{{ $row['Hadir'] }}</td>
                    <td>{{ $row['Terlambat'] }}</td>
                    <td>{{ $row['Izin/Sakit'] }}</td>
                    <td>{{ $row['Alpha'] }}</td>
                    <td><strong>{{ $persentase }}%</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala Desa Sindangmukti<br>
                <div class="mt-50"></div>
                <strong>H. Kepala Desa</strong><br>
                NIP. -
            </td>
            <td>
                Sindangmukti, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Sekretaris Desa<br>
                <div class="mt-50"></div>
                <strong>Sekdes M.Si</strong><br>
                NIP. -
            </td>
        </tr>
    </table>

    <div class="footer">
        Dicetak dari SID Sindangmukti pada {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
    </div>
</body>
</html>

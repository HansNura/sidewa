<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Buku Tamu - {{ $period }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header h2 { margin: 5px 0 0; font-size: 14px; font-weight: normal; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .table th { background-color: #f5f5f5; font-weight: bold; }
        .table td.left { text-align: left; }
        
        .footer { 
            position: fixed; 
            bottom: 0; 
            left: 0; 
            width: 100%; 
            text-align: right; 
            font-size: 10px; 
            color: #777; 
            border-top: 1px solid #ddd; 
            padding-top: 10px; 
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pemerintah Desa Sindangmukti</h1>
        <h2>Laporan Rekapitulasi Buku Tamu</h2>
        <p>Periode: <strong>{{ $period }}</strong></p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal</th>
                <th width="12%">Waktu</th>
                <th class="left">Nama Tamu</th>
                <th class="left">Asal/Instansi</th>
                <th>Kategori</th>
                <th class="left">Keperluan Detil</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row['No'] }}</td>
                    <td>{{ $row['Tanggal'] }}</td>
                    <td>{{ $row['Waktu'] }}</td>
                    <td class="left"><strong>{{ $row['Nama Tamu'] }}</strong></td>
                    <td class="left">{{ $row['Asal/Instansi'] }}</td>
                    <td>{{ $row['Kategori Tujuan'] }}</td>
                    <td class="left">{{ $row['Keperluan Detil'] }}</td>
                    <td>{{ $row['Status'] }}</td>
                </tr>
            @endforeach
            @if(count($data) == 0)
                <tr>
                    <td colspan="8">Tidak ada data kunjungan tamu pada periode ini.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dicetak dari SID Sindangmukti pada {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
    </div>
</body>
</html>

<?php

use App\Models\Warga;
use App\Models\Penduduk;

foreach (Warga::all() as $w) {
    $p = Penduduk::where('nik', $w->nik)->first();
    echo $w->nik . ' | ' . $w->nama . ' => ' . ($p ? $p->nama . ' (MATCH)' : 'NOT FOUND') . PHP_EOL;
}

echo PHP_EOL . 'Columns in surat_permohonan: ' . implode(', ', Schema::getColumnListing('surat_permohonan')) . PHP_EOL;

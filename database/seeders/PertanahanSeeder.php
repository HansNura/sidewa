<?php

namespace Database\Seeders;

use App\Models\Penduduk;
use App\Models\Pertanahan;
use Illuminate\Database\Seeder;

class PertanahanSeeder extends Seeder
{
    public function run(): void
    {
        $pendudukList = Penduduk::where('status', 'hidup')->limit(6)->get();

        // ─── Aset Desa ───────────────────────────────────────────
        Pertanahan::create([
            'kode_lahan'       => 'LHN-001-DS',
            'kepemilikan'      => 'desa',
            'nama_pemilik'     => 'Pemerintah Desa',
            'luas'             => 4500,
            'lokasi_blok'      => 'Kompleks Balai Desa',
            'dusun'            => 'Kaler',
            'rt'               => '01',
            'rw'               => '01',
            'legalitas'        => 'shgb',
            'nomor_sertifikat' => 'SHP-DS-2024-001',
            'geojson'          => [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [[[107.6300, -6.8200], [107.6310, -6.8195], [107.6315, -6.8205], [107.6305, -6.8210], [107.6300, -6.8200]]]
                ]
            ],
        ]);

        Pertanahan::create([
            'kode_lahan'       => 'LHN-002-DS',
            'kepemilikan'      => 'desa',
            'nama_pemilik'     => 'Pemerintah Desa',
            'luas'             => 7500,
            'lokasi_blok'      => 'Tanah Kas Desa Blok Utara',
            'dusun'            => 'Kaler',
            'rt'               => '02',
            'rw'               => '01',
            'legalitas'        => 'shgb',
            'nomor_sertifikat' => 'SHP-DS-2024-002',
            'geojson'          => [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [[[107.6280, -6.8180], [107.6295, -6.8175], [107.6298, -6.8190], [107.6283, -6.8195], [107.6280, -6.8180]]]
                ]
            ],
        ]);

        // ─── Tanah Warga ─────────────────────────────────────────
        if ($pendudukList->count() >= 4) {
            $wargaData = [
                ['idx' => 0, 'luas' => 1200, 'blok' => 'Blok Sawah Kidul',    'dusun' => 'Kidul', 'rt' => '03', 'rw' => '02', 'leg' => 'shm',   'sert' => 'SHM-2025-00123',
                 'geo' => [[[107.6325, -6.8220], [107.6335, -6.8218], [107.6338, -6.8228], [107.6328, -6.8230], [107.6325, -6.8220]]]],
                ['idx' => 1, 'luas' => 540,  'blok' => 'Blok Pemukiman',       'dusun' => 'Kaler', 'rt' => '02', 'rw' => '01', 'leg' => 'girik',  'sert' => null,
                 'geo' => [[[107.6340, -6.8200], [107.6348, -6.8198], [107.6350, -6.8206], [107.6342, -6.8208], [107.6340, -6.8200]]]],
                ['idx' => 2, 'luas' => 2000, 'blok' => 'Blok Kebun Selatan',   'dusun' => 'Kidul', 'rt' => '01', 'rw' => '02', 'leg' => 'shm',   'sert' => 'SHM-2023-00456',
                 'geo' => [[[107.6310, -6.8240], [107.6325, -6.8238], [107.6328, -6.8250], [107.6313, -6.8252], [107.6310, -6.8240]]]],
                ['idx' => 3, 'luas' => 800,  'blok' => 'Blok Pekarangan Barat','dusun' => 'Kaler', 'rt' => '01', 'rw' => '01', 'leg' => 'ajb',   'sert' => 'AJB-2022-0099',
                 'geo' => [[[107.6290, -6.8210], [107.6298, -6.8208], [107.6300, -6.8215], [107.6292, -6.8217], [107.6290, -6.8210]]]],
            ];

            foreach ($wargaData as $i => $w) {
                $p = $pendudukList->get($w['idx']);
                if (!$p) continue;

                Pertanahan::create([
                    'kode_lahan'       => sprintf('LHN-%03d-WG', 401 + $i),
                    'penduduk_id'      => $p->id,
                    'kepemilikan'      => 'warga',
                    'luas'             => $w['luas'],
                    'lokasi_blok'      => $w['blok'],
                    'dusun'            => $w['dusun'],
                    'rt'               => $w['rt'],
                    'rw'               => $w['rw'],
                    'legalitas'        => $w['leg'],
                    'nomor_sertifikat' => $w['sert'],
                    'geojson'          => [
                        'type' => 'Feature',
                        'geometry' => ['type' => 'Polygon', 'coordinates' => $w['geo']]
                    ],
                ]);
            }
        }

        // ─── Fasilitas Umum ──────────────────────────────────────
        Pertanahan::create([
            'kode_lahan'   => 'LHN-001-FS',
            'kepemilikan'  => 'fasum',
            'nama_pemilik' => 'Fasilitas Umum',
            'luas'         => 2000,
            'lokasi_blok'  => 'Lapangan Olahraga Desa',
            'dusun'        => 'Kaler',
            'rt'           => '01',
            'rw'           => '01',
            'legalitas'    => 'shgb',
            'geojson'      => [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [[[107.6315, -6.8160], [107.6328, -6.8158], [107.6330, -6.8170], [107.6317, -6.8172], [107.6315, -6.8160]]]
                ]
            ],
        ]);

        Pertanahan::create([
            'kode_lahan'   => 'LHN-002-FS',
            'kepemilikan'  => 'fasum',
            'nama_pemilik' => 'Fasilitas Umum',
            'luas'         => 1500,
            'lokasi_blok'  => 'Jalan Desa Utama',
            'dusun'        => 'Kaler',
            'rt'           => '01',
            'rw'           => '01',
            'legalitas'    => 'shgb',
            'geojson'      => [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [[[107.6295, -6.8200], [107.6340, -6.8198], [107.6340, -6.8202], [107.6295, -6.8204], [107.6295, -6.8200]]]
                ]
            ],
        ]);
    }
}

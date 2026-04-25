<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Tampilkan halaman Data Penduduk
     */
    public function penduduk()
    {
        $pageTitle = "Data Penduduk";
        $pageSubtitle = "Ringkasan data kependudukan total Desa Sindangmukti."; // Adjusted to Sindangmukti

        $totalPenduduk = [
            'kk' => 1234,
            'l' => 1940,
            'p' => 1883,
            'total' => 3823,
        ];

        $dataKelompokUmur = [
            'labels' => ["Balita (0-5)", "Anak (6-17)", "Produktif (18-55)", "Lansia (56+)"],
            'data' => [350, 800, 1950, 723],
        ];

        $statistikPenduduk = [
            [
                'label' => "Total Penduduk",
                'value' => 3823,
                'unit' => "Jiwa",
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />',
                'isHighlight' => true,
                'timestamp' => "9 Nov 2025 18:40 WIB",
                'color' => "primary",
            ],
            [
                'label' => "Total Laki-laki",
                'value' => 1940,
                'unit' => "Jiwa",
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />',
                'isHighlight' => false,
                'color' => "blue",
            ],
            [
                'label' => "Total Perempuan",
                'value' => 1883,
                'unit' => "Jiwa",
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />',
                'isHighlight' => false,
                'color' => "pink",
            ],
            [
                'label' => "Total Kepala Keluarga",
                'value' => 1234,
                'unit' => "KK",
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />',
                'isHighlight' => false,
                'color' => "amber",
            ],
        ];

        return view('pages.frontend.data.penduduk', compact('pageTitle', 'pageSubtitle', 'totalPenduduk', 'dataKelompokUmur', 'statistikPenduduk'));
    }

    /**
     * Tampilkan halaman Data Wilayah
     */
    public function wilayah()
    {
        $pageTitle = "Data Wilayah Administratif";
        $pageSubtitle = "Rincian wilayah administrasi Desa Sindangmukti, mencakup total luas, jumlah dusun, RW, dan RT.";

        $dataDusun = [
            [
                'nama' => "SUKAMANAH",
                'kadus' => "EDI SETIAWAN",
                'kk' => 336,
                'l' => 528,
                'p' => 514,
                'total' => 1042,
                'rw' => [
                    [
                        'nama' => "RW 1",
                        'ketua' => "KOMAR",
                        'kk' => 135,
                        'l' => 201,
                        'p' => 213,
                        'total' => 414,
                        'rt' => [
                            ['nama' => "RT 1", 'ketua' => "GANI HIKMAT MAULANA", 'kk' => 61, 'l' => 93, 'p' => 89, 'total' => 182],
                            ['nama' => "RT 2", 'ketua' => "DENI RAHMAN", 'kk' => 44, 'l' => 58, 'p' => 75, 'total' => 133],
                            ['nama' => "RT 3", 'ketua' => "OTONG KADARUSMAN", 'kk' => 30, 'l' => 50, 'p' => 49, 'total' => 99],
                        ],
                    ],
                    [
                        'nama' => "RW 2",
                        'ketua' => "WAWAN RIDWAN",
                        'kk' => 201,
                        'l' => 327,
                        'p' => 301,
                        'total' => 628,
                        'rt' => [
                            ['nama' => "RT 1", 'ketua' => "ANUR INSANUR", 'kk' => 45, 'l' => 74, 'p' => 66, 'total' => 140],
                            ['nama' => "RT 2", 'ketua' => "ATE SUHERLAN", 'kk' => 47, 'l' => 71, 'p' => 80, 'total' => 151],
                            ['nama' => "RT 3", 'ketua' => "IMAT ROHIMAT", 'kk' => 55, 'l' => 92, 'p' => 74, 'total' => 166],
                            ['nama' => "RT 4", 'ketua' => "APID", 'kk' => 54, 'l' => 90, 'p' => 81, 'total' => 171],
                        ],
                    ],
                ],
            ],
            [
                'nama' => "SUKASARI",
                'kadus' => "WAHYU",
                'kk' => 301,
                'l' => 478,
                'p' => 459,
                'total' => 937,
                'rw' => [
                    [
                        'nama' => "RW 1",
                        'ketua' => "MAMAT SELAMAT SUBAGJA",
                        'kk' => 120,
                        'l' => 173,
                        'p' => 187,
                        'total' => 360,
                        'rt' => [
                            ['nama' => "RT 1", 'ketua' => "NANA SUPRIATNA", 'kk' => 47, 'l' => 61, 'p' => 75, 'total' => 136],
                            ['nama' => "RT 2", 'ketua' => "MURDIYATI", 'kk' => 40, 'l' => 63, 'p' => 62, 'total' => 125],
                            ['nama' => "RT 3", 'ketua' => "UCU ROHIMAT", 'kk' => 33, 'l' => 49, 'p' => 50, 'total' => 99],
                        ],
                    ],
                    [
                        'nama' => "RW 2",
                        'ketua' => "ENANG",
                        'kk' => 181,
                        'l' => 305,
                        'p' => 272,
                        'total' => 577,
                        'rt' => [
                            ['nama' => "RT 1", 'ketua' => "CUNCUN MANSUR", 'kk' => 31, 'l' => 43, 'p' => 52, 'total' => 95],
                            ['nama' => "RT 2", 'ketua' => "ARIP SANTOSO", 'kk' => 36, 'l' => 65, 'p' => 49, 'total' => 114],
                            ['nama' => "RT 3", 'ketua' => "WAWAN RIDWAN", 'kk' => 66, 'l' => 115, 'p' => 99, 'total' => 214],
                            ['nama' => "RT 4", 'ketua' => "YADI INDRAWAN", 'kk' => 48, 'l' => 82, 'p' => 72, 'total' => 154],
                        ],
                    ],
                ],
            ],
            [
                'nama' => "CIDOYANG",
                'kadus' => "YOSEP SEPTIAN GHUSMAN",
                'kk' => 597,
                'l' => 934,
                'p' => 910,
                'total' => 1844,
                'rw' => [
                    [
                        'nama' => "RW 1",
                        'ketua' => "ATTO",
                        'kk' => 206,
                        'l' => 323,
                        'p' => 292,
                        'total' => 615,
                        'rt' => [
                            ['nama' => "RT 1", 'ketua' => "ENJANG KUSAERI", 'kk' => 92, 'l' => 159, 'p' => 144, 'total' => 303],
                            ['nama' => "RT 2", 'ketua' => "YAYAT RUHIYAT", 'kk' => 36, 'l' => 53, 'p' => 46, 'total' => 99],
                            ['nama' => "RT 3", 'ketua' => "CUCU SANTIKA", 'kk' => 44, 'l' => 68, 'p' => 56, 'total' => 124],
                            ['nama' => "RT 4", 'ketua' => "AI KOMARIAH", 'kk' => 34, 'l' => 43, 'p' => 46, 'total' => 89],
                        ],
                    ],
                    [
                        'nama' => "RW 2",
                        'ketua' => "NURLELA SARI",
                        'kk' => 180,
                        'l' => 258,
                        'p' => 290,
                        'total' => 548,
                        'rt' => [
                            ['nama' => "RT 1", 'ketua' => "KARTIWAN MUHAMAD ROFI", 'kk' => 42, 'l' => 58, 'p' => 74, 'total' => 132],
                            ['nama' => "RT 2", 'ketua' => "MASTUR", 'kk' => 55, 'l' => 87, 'p' => 92, 'total' => 179],
                            ['nama' => "RT 3", 'ketua' => "SITI ROHIMAH", 'kk' => 40, 'l' => 55, 'p' => 62, 'total' => 117],
                            ['nama' => "RT 4", 'ketua' => "IIN KUSTINI", 'kk' => 43, 'l' => 58, 'p' => 62, 'total' => 120],
                        ],
                    ],
                    [
                        'nama' => "RW 3",
                        'ketua' => "MANSUR RUSMANA",
                        'kk' => 208,
                        'l' => 347,
                        'p' => 326,
                        'total' => 673,
                        'rt' => [
                            ['nama' => "RT 1", 'ketua' => "AI NIA KUSNIATI", 'kk' => 39, 'l' => 53, 'p' => 62, 'total' => 115],
                            ['nama' => "RT 2", 'ketua' => "DEDE ADING KURNIAWAN", 'kk' => 48, 'l' => 82, 'p' => 66, 'total' => 148],
                            ['nama' => "RT 3", 'ketua' => "WAWAN KURNIA", 'kk' => 42, 'l' => 70, 'p' => 71, 'total' => 141],
                            ['nama' => "RT 4", 'ketua' => "UCU SAMSU", 'kk' => 79, 'l' => 142, 'p' => 127, 'total' => 269],
                        ],
                    ],
                ],
            ],
        ];

        // Calculated totals
        $totalDusun = count($dataDusun);
        $totalRW = collect($dataDusun)->sum(function ($dusun) {
            return count($dusun['rw']);
        });
        $totalRT = collect($dataDusun)->sum(function ($dusun) {
            return collect($dusun['rw'])->sum(function ($rw) {
                return count($rw['rt']);
            });
        });

        $statistikWilayah = [
            [
                'label' => "Total Luas Wilayah",
                'value' => "1.210 Ha",
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-10.5v.112c0 .071 0 .141-.021.21L13.635 17.74a4.5 4.5 0 01-1.42 2.367l-1.594 1.398a.75.75 0 01-1.242-.57V17.5a.75.75 0 01.75-.75h1.5a.75.75 0 00.75-.75v-1.5a.75.75 0 01.75-.75h1.5a.75.75 0 00.75-.75v-1.5a.75.75 0 01.75-.75h1.5a.75.75 0 00.75-.75V5.25a.75.75 0 01.75-.75h1.5a.75.75 0 00.75-.75V3" />',
                'isHighlight' => false,
                'color' => "blue",
            ],
            [
                'label' => "Total Dusun",
                'value' => $totalDusun,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-3h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />',
                'isHighlight' => false,
                'color' => "amber",
            ],
            [
                'label' => "Total RW",
                'value' => $totalRW,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />',
                'isHighlight' => false,
                'color' => "indigo",
            ],
            [
                'label' => "Total RT",
                'value' => $totalRT,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />',
                'isHighlight' => false,
                'color' => "purple",
            ],
        ];

        $totalPenduduk = [
            'kk' => 1234,
            'l' => 1940,
            'p' => 1883,
            'total' => 3823,
        ];

        $mapData = [
            'center' => [-7.145544, 108.213709],
            'zoom' => 14,
            'urlGeoJSON' => "assets/data/wilayah_dusun.geojson",
            'colors' => [
                'SUKAMANAH' => "#2E7D32",
                'SUKASARI' => "#FBC02D",
                'CIDOYANG' => "#0288D1",
            ],
        ];

        return view('pages.frontend.data.wilayah', compact('pageTitle', 'pageSubtitle', 'statistikWilayah', 'dataDusun', 'totalPenduduk', 'mapData'));
    }

    /**
     * Tampilkan halaman Data Pendidikan (Ditempuh)
     */
    public function pendidikanDitempuh()
    {
        $pageTitle = "Data Pendidikan (Ditempuh)";
        $pageSubtitle = "Distribusi penduduk berdasarkan pendidikan yang sedang ditempuh (Total: 70 jiwa).";

        $statistikPendidikanDitempuh = [
            [
                'label' => "Total Penduduk (Dlm Pendidikan)",
                'value' => 70,
                'unit' => "Jiwa",
                'timestamp' => "9 Nov 2025 20:00 WIB",
                'isHighlight' => true,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />',
                'color' => "primary",
            ],
            [
                'label' => "BELUM MASUK TK/KELOMPOK BERMAIN",
                'value' => 34,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
                'color' => "red",
            ],
            [
                'label' => "SEDANG SD/SEDERAJAT",
                'value' => 13,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" /><path fill-rule="evenodd" d="M3.087 9l.54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.163 3.75A.75.75 0 0 1 10 12h4a.75.75 0 0 1 0 1.5h-4a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />',
                'color' => "blue",
            ],
            [
                'label' => "SEDANG SLTA/SEDERAJAT",
                'value' => 10,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 1.052A56.25 56.25 0 0 0 12 8.443m0 0v3.675m0 0v3.675m0 0v3.675M12 12.118v3.675M17.25 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />',
                'color' => "green",
            ],
            [
                'label' => "SEDANG SLTP/SEDERAJAT",
                'value' => 7,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />',
                'color' => "cyan",
            ],
            [
                'label' => "SEDANG TK/KELOMPOK BERMAIN",
                'value' => 4,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.31h5.364c.518 0 .734.654.372 1.014l-4.341 3.155a.562.562 0 0 0-.182.658l1.658 5.111a.563.563 0 0 1-.816.623l-4.341-3.155a.563.563 0 0 0-.658 0l-4.341 3.155a.563.563 0 0 1-.816-.623l1.658-5.111a.562.562 0 0 0-.182-.658L2.48 9.924a.563.563 0 0 1 .372-1.014h5.364a.562.562 0 0 0 .475-.31l2.125-5.111Z" />',
                'color' => "pink",
            ],
            [
                'label' => "TIDAK PERNAH SEKOLAH",
                'value' => 1,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
                'color' => "gray",
            ],
            [
                'label' => "TIDAK TAMAT SD/SEDERAJAT",
                'value' => 1,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />',
                'color' => "amber",
            ],
        ];

        return view('pages.frontend.data.pendidikan-ditempuh', compact('pageTitle', 'pageSubtitle', 'statistikPendidikanDitempuh'));
    }

    /**
     * Tampilkan halaman Data Pekerjaan Penduduk
     */
    public function pekerjaan()
    {
        $pageTitle = "Data Pekerjaan Penduduk";
        $pageSubtitle = "Distribusi jenis pekerjaan dari total 2.855 penduduk di Desa Sindangmukti.";

        $statistikPekerjaan = [
            [
                'label' => "Total Penduduk (Bekerja/Tidak)",
                'value' => 2855,
                'unit' => "Jiwa",
                'timestamp' => "9 Nov 2025 19:00 WIB",
                'isHighlight' => true,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />',
                'color' => "primary",
            ],
            [
                'label' => "BELUM/TIDAK BEKERJA",
                'value' => 1284,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />',
                'color' => "red",
            ],
            [
                'label' => "MENGURUS RUMAH TANGGA",
                'value' => 884,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />',
                'color' => "pink",
            ],
            [
                'label' => "PELAJAR/MAHASISWA",
                'value' => 430,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 1.052A56.25 56.25 0 0 0 12 8.443m0 0v3.675m0 0v3.675m0 0v3.675M12 12.118v3.675M17.25 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />',
                'color' => "blue",
            ],
            [
                'label' => "PEGAWAI NEGERI SIPIL (PNS)",
                'value' => 120,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />',
                'color' => "green",
            ],
            [
                'label' => "PENSIUNAN",
                'value' => 58,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />',
                'color' => "gray",
            ],
            [
                'label' => "PETANI/PEKEBUN",
                'value' => 54,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.075c0 1.313-.964 2.485-2.25 2.75-1.083.206-2.176.324-3.29.38-1.4.056-2.81.083-4.22.083s-2.817-.027-4.22-.083c-1.114-.056-2.207-.174-3.29-.38A2.72 2.72 0 0 1 3.75 18.225v-4.075c0-1.313.964-2.485 2.25-2.75 1.083-.206 2.176-.324 3.29-.38 1.4-.056 2.81-.083 4.22-.083s2.817.027 4.22.083c1.114.056 2.207.174 3.29.38 1.286.265 2.25 1.437 2.25 2.75Z" />',
                'color' => "amber",
            ],
            [
                'label' => "PERDAGANGAN",
                'value' => 18,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.106M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
                'color' => "cyan",
            ],
            [
                'label' => "KEPOLISIAN RI (POLRI)",
                'value' => 3,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.623 0-1.604-.368-3.14-.992-4.501M12 2.75c0 .416.02.826.059 1.232" />',
                'color' => "indigo",
            ],
            [
                'label' => "PETERNAK",
                'value' => 4,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.75 0h.008v.008H9v-.008Zm4.5 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.75 0h.008v.008H13.5v-.008Z" />',
                'color' => "purple",
            ],
        ];

        return view('pages.frontend.data.pekerjaan', compact('pageTitle', 'pageSubtitle', 'statistikPekerjaan'));
    }

    /**
     * Tampilkan halaman Data Agama Penduduk
     */
    public function agama()
    {
        $pageTitle = "Data Agama Penduduk";
        $pageSubtitle = "Distribusi agama dari total 3.823 penduduk di Desa Sindangmukti.";

        $statistikAgama = [
            [
                'label' => "Total Penduduk",
                'value' => 3823,
                'unit' => "Jiwa",
                'timestamp' => "3 Nov 2025",
                'isHighlight' => true,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />',
                'color' => "primary",
            ],
            [
                'label' => "ISLAM",
                'value' => 3823,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />',
                'color' => "blue",
            ],
            [
                'label' => "KRISTEN",
                'value' => 0,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />',
                'color' => "gray",
            ],
            [
                'label' => "PROTESTAN",
                'value' => 0,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />',
                'color' => "gray",
            ],
            [
                'label' => "KATOLIK",
                'value' => 0,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />',
                'color' => "gray",
            ],
            [
                'label' => "HINDU",
                'value' => 0,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />',
                'color' => "gray",
            ],
            [
                'label' => "BUDHA",
                'value' => 0,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />',
                'color' => "gray",
            ],
            [
                'label' => "KONGHUCU",
                'value' => 0,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />',
                'color' => "gray",
            ],
            [
                'label' => "BELUM MENGISI",
                'value' => 0,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
                'color' => "gray",
            ],
        ];

        return view('pages.frontend.data.agama', compact('pageTitle', 'pageSubtitle', 'statistikAgama'));
    }

    /**
     * Tampilkan halaman Data Jenis Kelamin Penduduk
     */
    public function jenisKelamin()
    {
        $pageTitle = "Data Jenis Kelamin";
        $pageSubtitle = "Distribusi penduduk berdasarkan jenis kelamin (Total: 3.823 jiwa).";

        $statistikJenisKelamin = [
            [
                'label' => "Total Penduduk",
                'value' => 3823,
                'unit' => "Jiwa",
                'timestamp' => "9 Nov 2025",
                'isHighlight' => true,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />',
                'color' => "primary",
            ],
            [
                'label' => "LAKI-LAKI",
                'value' => 1940,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />',
                'color' => "blue",
            ],
            [
                'label' => "PEREMPUAN",
                'value' => 1883,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />',
                'color' => "pink",
            ],
            [
                'label' => "BELUM MENGISI",
                'value' => 0,
                'unit' => "Jiwa",
                'isHighlight' => false,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
                'color' => "gray",
            ],
        ];

        return view('pages.frontend.data.jenis-kelamin', compact('pageTitle', 'pageSubtitle', 'statistikJenisKelamin'));
    }

    /**
     * Tampilkan halaman Data Kelompok Umur Penduduk
     */
    public function kelompokUmur()
    {
        $pageTitle = "Data Kelompok Umur";
        $pageSubtitle = "Distribusi penduduk berdasarkan rentang umur (Total: 7.910 jiwa).";

        $statistikKelompokUmur = [
            [
                'label' => "Total Penduduk",
                'value' => 7910,
                'unit' => "Jiwa",
                'timestamp' => "9 Nov 2025 23:28 WIB",
                'isHighlight' => true,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />',
                'color' => "primary",
            ],
            [
                'label' => "6 s.d 10 tahun",
                'value' => 369,
                'isHighlight' => false,
                'color' => "blue",
            ],
            [
                'label' => "11 s.d 15 tahun",
                'value' => 564,
                'isHighlight' => false,
                'color' => "cyan",
            ],
            [
                'label' => "16 s.d 20 tahun",
                'value' => 669,
                'isHighlight' => false,
                'color' => "teal",
            ],
            [
                'label' => "21 s.d 25 tahun",
                'value' => 674,
                'isHighlight' => false,
                'color' => "green",
            ],
            [
                'label' => "26 s.d 30 tahun",
                'value' => 624,
                'isHighlight' => false,
                'color' => "lime",
            ],
            [
                'label' => "31 s.d 35 tahun",
                'value' => 593,
                'isHighlight' => false,
                'color' => "yellow",
            ],
            [
                'label' => "36 s.d 40 tahun",
                'value' => 530,
                'isHighlight' => false,
                'color' => "amber",
            ],
            [
                'label' => "41 s.d 45 tahun",
                'value' => 634,
                'isHighlight' => false,
                'color' => "orange",
            ],
            [
                'label' => "46 s.d 50 tahun",
                'value' => 582,
                'isHighlight' => false,
                'color' => "deeporange",
            ],
            [
                'label' => "51 s.d 55 tahun",
                'value' => 517,
                'isHighlight' => false,
                'color' => "brown",
            ],
            [
                'label' => "56 s.d 60 tahun",
                'value' => 516,
                'isHighlight' => false,
                'color' => "gray",
            ],
            [
                'label' => "61 s.d 65 tahun",
                'value' => 471,
                'isHighlight' => false,
                'color' => "bluegray",
            ],
            [
                'label' => "66 s.d 70 tahun",
                'value' => 370,
                'isHighlight' => false,
                'color' => "purple",
            ],
            [
                'label' => "71 s.d 75 tahun",
                'value' => 284,
                'isHighlight' => false,
                'color' => "indigo",
            ],
            [
                'label' => "75 tahun ke atas",
                'value' => 513,
                'isHighlight' => false,
                'color' => "pink",
            ],
        ];

        // Format array dengan default icon (User icon) dan format unit yang konsisten
        foreach ($statistikKelompokUmur as &$item) {
            if (!isset($item['icon'])) {
                $item['icon'] = '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />';
            }
            if (!isset($item['unit'])) {
                $item['unit'] = "Jiwa";
            }
        }

        return view('pages.frontend.data.kelompok-umur', compact('pageTitle', 'pageSubtitle', 'statistikKelompokUmur'));
    }
}

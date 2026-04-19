<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WargaDashboardController extends Controller
{
    /**
     * Display the warga self-service dashboard.
     */
    public function index()
    {
        $warga = Auth::guard('warga')->user();

        // Summary data for dashboard cards
        $layananItems = [
            [
                'title'       => 'Cetak Surat Keterangan',
                'description' => 'Domisili, Usaha, SKTM, dan lainnya',
                'icon'        => 'fa-solid fa-file-lines',
                'color'       => 'green',
                'route'       => '#',
            ],
            [
                'title'       => 'Cek Bantuan Sosial',
                'description' => 'Status penerima bantuan terpadu',
                'icon'        => 'fa-solid fa-hand-holding-heart',
                'color'       => 'blue',
                'route'       => '#',
            ],
            [
                'title'       => 'Update Data Keluarga',
                'description' => 'Perbarui data profil keluarga',
                'icon'        => 'fa-solid fa-users',
                'color'       => 'amber',
                'route'       => '#',
            ],
            [
                'title'       => 'Riwayat Pengajuan',
                'description' => 'Lacak status pengajuan surat',
                'icon'        => 'fa-solid fa-clock-rotate-left',
                'color'       => 'purple',
                'route'       => '#',
            ],
        ];

        return view('pages.frontend.layanan-mandiri.dashboard', [
            'warga'        => $warga,
            'layananItems' => $layananItems,
        ]);
    }
}

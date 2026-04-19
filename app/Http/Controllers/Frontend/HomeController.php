<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama publik (frontend)
     */
    public function index()
    {
        $bpdMembers = [
            ['nama' => 'Tateng', 'jabatan' => 'Ketua', 'kontak' => '082118738256', 'alamat' => 'Dusun Cidoyang RT 3 RW 2'],
            ['nama' => 'Samsudin, S.Pd, M.M', 'jabatan' => 'Wakil Ketua', 'kontak' => '081323209666', 'alamat' => 'Dusun Sukamanah RT 3 RW 1'],
            ['nama' => 'Nonoh Sukanah, S.Pd, M.Pd', 'jabatan' => 'Sekretaris', 'kontak' => '082218923613', 'alamat' => 'Dusun Sukasari RT 2 RW 2'],
            ['nama' => 'Lili Sudirahayu', 'jabatan' => 'Anggota', 'kontak' => '081323175321', 'alamat' => 'Dusun Sukamanah RT 4 RW 2'],
            ['nama' => 'Arif Hidayat, S.Pd', 'jabatan' => 'Anggota', 'kontak' => '082126414546', 'alamat' => 'Dusun Sukasari RT 3 RW 1'],
            ['nama' => 'Heri Simri', 'jabatan' => 'Anggota', 'kontak' => '081320077590', 'alamat' => 'Dusun Sukasari RT 2 RW 2'],
            ['nama' => 'Norma Mustika, S.Pd.I', 'jabatan' => 'Anggota', 'kontak' => '087802837560', 'alamat' => 'Dusun Cidoyang RT 1 RW 1'],
            ['nama' => 'Nana', 'jabatan' => 'Anggota', 'kontak' => '-', 'alamat' => 'Dusun Cidoyang RT 1 RW 1']
        ];

        $produkLapak = [
            ['id' => 1, 'foto' => 'https://placehold.co/600x400/2E7D32/white?text=Produk+1', 'nama' => 'Madu Odeng Asli', 'harga' => 200000, 'kategori' => 'Obat dan Herbal', 'pelapak' => 'SITI ROHIMAH'],
            ['id' => 2, 'foto' => 'https://placehold.co/600x400/2E7D32/white?text=Produk+2', 'nama' => 'Laundry', 'harga' => 3500, 'kategori' => 'Jasa', 'pelapak' => 'SUSI SUCILAWATI'],
            ['id' => 3, 'foto' => 'https://placehold.co/600x400/2E7D32/white?text=Produk+3', 'nama' => 'Cobek Kayu', 'harga' => 8500, 'kategori' => 'Toko Online', 'pelapak' => 'CEPI CIPTA'],
            ['id' => 4, 'foto' => 'https://placehold.co/600x400/2E7D32/white?text=Produk+4', 'nama' => 'RIFAN\'S SNACK & COOKIES', 'harga' => 1000, 'kategori' => 'Foods and Drinks', 'pelapak' => 'NANI SURYANI'],
        ];

        $beritaLokal = [
            ['id' => 1, 'imgSrc' => 'assets/img/berita/berita1.jpg', 'tanggal' => '21 Okt 2025', 'judul' => 'Desa Cerdas, Desa Berdaulat: Saatnya Mengelola Data Sendiri', 'admin' => 'Edi Setiawan', 'views' => '3.514'],
            ['id' => 2, 'imgSrc' => 'assets/img/berita/berita1.jpg', 'tanggal' => '18 Okt 2025', 'judul' => 'Desa di Era Medsos dan Efek Domino Kepercayaan', 'admin' => 'Edi Setiawan', 'views' => '3.354'],
            ['id' => 3, 'imgSrc' => 'assets/img/berita/berita1.jpg', 'tanggal' => '17 Okt 2025', 'judul' => 'Posbankum Desa: Melindungi Warga dengan Kearifan Lokal', 'admin' => 'Edi Setiawan', 'views' => '1.904'],
            ['id' => 4, 'imgSrc' => 'assets/img/berita/berita1.jpg', 'tanggal' => '15 Okt 2025', 'judul' => 'Gotong Royong Bersihkan Saluran Irigasi Menjelang Musim Hujan', 'admin' => 'Asep', 'views' => '980'],
            ['id' => 5, 'imgSrc' => 'assets/img/berita/berita1.jpg', 'tanggal' => '12 Okt 2025', 'judul' => 'Pelatihan UMKM: Ibu-Ibu Desa Sindangmukti Belajar Pemasaran Digital', 'admin' => 'Edi Setiawan', 'views' => '1.203'],
            ['id' => 6, 'imgSrc' => 'assets/img/berita/berita1.jpg', 'tanggal' => '10 Okt 2025', 'judul' => 'Pembangunan Posyandu Baru Telah Selesai 100%', 'admin' => 'Asep', 'views' => '850'],
        ];

        return view('pages.frontend.home', compact('bpdMembers', 'produkLapak', 'beritaLokal'));
    }
}

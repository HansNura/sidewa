<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman Identitas Desa
     */
    public function identitas()
    {
        // Data for administrative info
        $infoAdministratif = [
            ['label' => 'Kode Desa', 'value' => '3207132009'],
            ['label' => 'Kecamatan', 'value' => 'Panumbangan'],
            ['label' => 'Kabupaten', 'value' => 'Ciamis'],
            ['label' => 'Provinsi', 'value' => 'Jawa Barat'],
            ['label' => 'Kepala Desa', 'value' => 'H. Engkos Kosim'],
            ['label' => 'Masa Jabatan', 'value' => '2018 – 2024'],
        ];

        // Data for statistics
        $statistik = [
            [
                'label' => 'Jumlah Penduduk',
                'value' => '4.217',
                'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM8.25 10.375a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0z',
                'bgColor' => 'bg-green-100',
                'textColor' => 'text-green-700',
            ],
            [
                'label' => 'Luas Wilayah',
                'value' => '1.210 Ha',
                'icon' => 'M3.75 21v-4.5m0 4.5h-1.5m1.5 0h1.5m-1.5 0v-4.5m0 4.5h1.5m-1.5 0h-1.5m3.75-9.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zM3.75 12h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zM3.75 6.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zM3.75 9h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zM6.75 21v-4.5m0 4.5h-1.5m1.5 0h1.5m-1.5 0V15M6.75 12h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zM6.75 6.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zM6.75 9h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zm3-6.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zM9.75 9h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zm0 3h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zm0 3.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zm3-6.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zm0 3h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zm0 3.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zm3-6.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zm0 3h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zm0 3.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zm3-6.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zm0 3h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zm0 3.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5z',
                'bgColor' => 'bg-blue-100',
                'textColor' => 'text-blue-700',
            ],
            [
                'label' => 'Jumlah Dusun',
                'value' => '5 Dusun',
                'icon' => 'M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21H8.25V10.888c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21zM8.25 10.888V6.111c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V10.888M12.75 6.111V3.545M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'bgColor' => 'bg-yellow-100',
                'textColor' => 'text-yellow-700',
            ],
            [
                'label' => 'RT / RW',
                'value' => '24 RT / 8 RW',
                'icon' => 'M10.5 6a7.5 7.5 0 100 15 7.5 7.5 0 000-15zM5.25 9H10.5m0 0H10.5m0 0V3m0 3V9m0 6v3m0-3v-3m0 0h5.25M10.5 9h-5.25',
                'bgColor' => 'bg-indigo-100',
                'textColor' => 'text-indigo-700',
            ]
        ];

        return view('pages.frontend.profil.identitas', compact('infoAdministratif', 'statistik'));
    }

    /**
     * Tampilkan halaman Visi & Misi
     */
    public function visiMisi()
    {
        $visi = "“Mewujudkan Desa Sukakerta yang Maju, Mandiri, Sejahtera dan Religius.”";

        $misiList = [
            "Meningkatkan kualitas SDM dan pendidikan masyarakat.",
            "Meningkatkan pelayanan publik berbasis teknologi informasi.",
            "Mengembangkan potensi ekonomi lokal melalui UMKM dan pertanian produktif.",
            "Memperkuat nilai-nilai religius dan gotong royong dalam kehidupan masyarakat.",
            "Mendorong transparansi dan partisipasi aktif masyarakat dalam pembangunan.",
        ];

        $programUnggulan = [
            [
                'title' => 'Digitalisasi Layanan Desa',
                'description' => 'Menghadirkan sistem informasi desa berbasis web untuk pelayanan administrasi yang cepat dan transparan.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v.007a.375.375 0 0 1-.375.375h-6.375a.375.375 0 0 1-.375-.375v-.007A11.248 11.248 0 0 1 2.25 12c0-4.74 3.093-8.711 7.219-10.045a.375.375 0 0 1 .562.338v.007a11.25 11.25 0 0 1 0 19.412v.007a.375.375 0 0 1-.562.338A11.248 11.248 0 0 1 9 17.25ZM15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />',
            ],
            [
                'title' => 'Pemberdayaan UMKM',
                'description' => 'Meningkatkan ekonomi masyarakat melalui pelatihan, pendampingan, dan promosi produk lokal.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5A.75.75 0 0 1 14.25 12h.001M13.5 21H18M13.5 21H9m1.5-9v7.5A.75.75 0 0 0 11.25 21h-2.5a.75.75 0 0 0-.75.75v.375c0 .621.504 1.125 1.125 1.125h3.25a1.125 1.125 0 0 0 1.125-1.125V21.75a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 1-.75-.75V12m0 0v-3.75A.75.75 0 0 1 9.75 7.5h.001M9 12h.001M9 12H6m3 0H3v-3.75A.75.75 0 0 1 3.75 7.5h.001M6 12v3.75c0 .621.504 1.125 1.125 1.125h3.25a1.125 1.125 0 0 0 1.125-1.125V12" />',
            ],
            [
                'title' => 'Desa Hijau & Bersih',
                'description' => 'Program penghijauan, pengelolaan sampah, dan konservasi alam untuk menjaga keindahan lingkungan desa.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.355a11.95 11.95 0 0 1-4.5 0m4.5-4.855a11.95 11.95 0 0 0-4.5 0m4.5 0a12.019 12.019 0 0 0-4.5 0m-2.25 0a12.019 12.019 0 0 0-4.5 0m11.25 0c.39 0 .777.027 1.15.076M11.25 12.75c-.39 0-.777.027-1.15.076M6.378 17.81a11.9 11.9 0 0 1-.28-.52m.28.52a11.9 11.9 0 0 0-.28-.52m-2.25-3.102a11.935 11.935 0 0 1-.28-.52m.28.52c-.18-.28-.34-.57-.48-.87M3.75 12H3m.75 0a11.935 11.935 0 0 0-.48-.87m.48.87c-.18-.28-.34-.57-.48-.87m2.25-3.102c-.18-.28-.34-.57-.48-.87M6.378 6.19c-.18-.28-.34-.57-.48-.87M3.75 12c0-.39.027-.777.076-1.15M3.826 10.85c-.049-.372-.076-.759-.076-1.15m0 0c0-.39.027-.777.076-1.15m0 0c.049-.372.076-.759.076-1.15m0 0A12.019 12.019 0 0 1 6 5.625m.378 1.565c.18.28.34.57.48.87m0 0c.18.28.34.57.48.87m2.25 3.102c.18.28.34.57.48.87m0 0c.18.28.34.57.48.87m2.25 3.102c.18.28.34.57.48.87M14.622 17.81c.18.28.34.57.48.87m2.25-3.102c.18.28.34.57.48.87m0 0c.18.28.34.57.48.87M17.622 6.19c.18.28.34.57.48.87m2.25 3.102c.18.28.34.57.48.87m0 0c.18.28.34.57.48.87M20.174 10.85c.049.372.076.759.076 1.15m0 0c0 .39-.027.777-.076 1.15m0 0c-.049.372-.076.759-.076 1.15m0 0A12.019 12.019 0 0 1 18 18.375m-1.622-1.565c-.18-.28-.34-.57-.48-.87m0 0c-.18-.28-.34-.57-.48-.87m-2.25-3.102c-.18-.28-.34-.57-.48-.87m0 0c-.18-.28-.34-.57-.48-.87m-2.25-3.102c-.18-.28-.34-.57-.48-.87" />',
            ],
        ];

        return view('pages.frontend.profil.visi-misi', compact('visi', 'misiList', 'programUnggulan'));
    }

    /**
     * Tampilkan halaman Struktur BPD & Lembaga Desa
     */
    public function strukturBpd()
    {
        $bpdDeskripsi = "Badan Permusyawaratan Desa yang selanjutnya disingkat BPD adalah lembaga yang melaksanakan fungsi Pemerintahan yang anggotanya merupakan wakil dari penduduk Desa berdasarkan keterwakilan wilayah dan ditetapkan secara demokratis.";

        $bpdFungsi = [
            "Membahas dan menyepakati Rancangan Peraturan Desa bersama Kepala Desa.",
            "Menampung dan menyalurkan aspirasi masyarakat Desa.",
            "Melakukan pengawasan kinerja Kepala Desa.",
        ];

        $bpdTugas = [
            "Menggali aspirasi masyarakat.",
            "Menampung aspirasi masyarakat.",
            "Mengelola aspirasi masyarakat.",
            "Menyalurkan aspirasi masyarakat.",
            "Menyelenggarakan musyawarah BPD.",
            "Menyelenggarakan musyawarah Desa.",
            "Membentuk panitia pemilihan Kepala Desa.",
            "Menyelenggarakan musyawarah Desa khusus untuk pemilihan Kepala Desa Antar Waktu.",
            "Membahas dan menyepakati Rancangan Peraturan Desa bersama Kepala Desa.",
            "Melaksanakan pengawasan terhadap kinerja Kepala Desa.",
            "Melakukan evaluasi laporan keterangan penyelenggaraan Pemerintahan Desa.",
            "Menciptakan hubungan kerja yang harmonis dengan Pemerintah Desa dan lembaga Desa lainnya.",
            "Melaksanakan tugas lain yang diatur dalam ketentuan Peraturan perundang-undangan.",
        ];

        $bpdMembers = [
            ['name' => 'Tateng', 'position' => 'Ketua', 'address' => 'Cidoyang RT 3 RW 2', 'contact' => '082118738256', 'photo' => asset('assets/img/people.png')],
            ['name' => 'Samsudin, S.Pd, M.M', 'position' => 'Wakil Ketua', 'address' => 'Sukamanah RT 3 RW 1', 'contact' => '081323209666', 'photo' => asset('assets/img/people.png')],
            ['name' => 'Nonoh Sukanah, S.Pd, M.Pd', 'position' => 'Sekretaris', 'address' => 'Sukasari RT 2 RW 2', 'contact' => '082218923613', 'photo' => asset('assets/img/people.png')],
            ['name' => 'Lili Sudirahayu', 'position' => 'Anggota', 'address' => 'Sukamanah RT 4 RW 2', 'contact' => '081323175321', 'photo' => asset('assets/img/people.png')],
            ['name' => 'Arif Hidayat, S.Pd', 'position' => 'Anggota', 'address' => 'Sukasari RT 3 RW 1', 'contact' => '082126414546', 'photo' => asset('assets/img/people.png')],
            ['name' => 'Heri Simri', 'position' => 'Anggota', 'address' => 'Sukasari RT 2 RW 2', 'contact' => '081320077590', 'photo' => asset('assets/img/people.png')],
            ['name' => 'Norma Mustika, S.Pd.I', 'position' => 'Anggota', 'address' => 'Cidoyang RT 1 RW 1', 'contact' => '087802837560', 'photo' => asset('assets/img/people.png')],
            ['name' => 'Nana', 'position' => 'Anggota', 'address' => 'Cidoyang RT 1 RW 1', 'contact' => '-', 'photo' => asset('assets/img/people.png')],
        ];

        $strukturSOTK = [
            [
                'jabatan' => 'Kepala Desa',
                'deskripsi' => 'Berwenang menyelenggarakan Pemerintahan Desa, melaksanakan pembangunan, pembinaan kemasyarakatan, dan pemberdayaan masyarakat.',
                'fungsi' => []
            ],
            [
                'jabatan' => 'Sekretaris Desa',
                'deskripsi' => 'Membantu Kepala Desa dalam bidang administrasi pemerintahan. Berkedudukan sebagai pimpinan Sekretariat Desa.',
                'fungsi' => ['Melaksanakan urusan umum, keuangan, dan perencanaan.', 'Mengkoordinasikan tugas–tugas seksi (Kasi) dan urusan (Kaur).']
            ],
            [
                'jabatan' => 'Kaur Tata Usaha dan Umum',
                'deskripsi' => 'Membantu Sekretaris Desa dalam urusan ketatausahaan dan umum.',
                'fungsi' => ['Pengelolaan tata naskah, administrasi surat menyurat, arsip, dan ekspedisi.', 'Penataan administrasi perangkat desa dan inventarisasi aset desa.']
            ],
            [
                'jabatan' => 'Kaur Perencanaan',
                'deskripsi' => 'Membantu Sekretaris Desa dalam urusan perencanaan pembangunan desa.',
                'fungsi' => ['Memfasilitasi tahapan dan administrasi perencanaan.', 'Melakukan monitoring dan evaluasi program.', 'Penyiapan bahan dan penyusun Rencana APBDes.']
            ],
            [
                'jabatan' => 'Kaur Keuangan',
                'deskripsi' => 'Membantu Sekretaris Desa dalam urusan keuangan desa.',
                'fungsi' => ['Menyusun Rencana Anggaran Kas (RAK) Desa.', 'Melakukan penatausahaan yang meliputi menerima, menyimpan, menyetor/membayar, dll.']
            ],
            [
                'jabatan' => 'Kasi Pemerintahan',
                'deskripsi' => 'Bertanggungjawab kepada Kepala Desa dalam manajemen tata praja.',
                'fungsi' => ['Melaksanakan manajemen tata praja dan menyusun rancangan regulasi desa.', 'Pembinaan masalah pertanahan, ketenteraman, dan ketertiban.', 'Pengelolaan administrasi kependudukan dan profil desa.']
            ],
            [
                'jabatan' => 'Kasi Kesejahteraan',
                'deskripsi' => 'Bertanggungjawab kepada Kepala Desa dalam pembangunan sarana dan prasarana.',
                'fungsi' => ['Melaksanakan pembangunan sarana prasarana perdesaan.', 'Fasilitasi dan pembangunan bidang pendidikan, kesehatan, dan keagamaan.']
            ],
            [
                'jabatan' => 'Kasi Pelayanan',
                'deskripsi' => 'Bertanggungjawab kepada Kepala Desa dalam pelayanan dan pemberdayaan masyarakat.',
                'fungsi' => ['Melaksanakan penyuluhan dan motivasi hak/kewajiban masyarakat.', 'Fasilitasi pembinaan keagamaan, pemberdayaan perempuan, kesehatan, dll.', 'Melaksanakan pelayanan perizinan, rekomendasi, dan surat keterangan.']
            ],
            [
                'jabatan' => 'Kepala Dusun (Pelaksana Kewilayahan)',
                'deskripsi' => 'Membantu Kepala Desa sebagai unsur satuan tugas kewilayahan di setiap Dusun.',
                'fungsi' => ['Pembinaan ketenteraman, ketertiban, dan perlindungan masyarakat di wilayahnya.', 'Mengkoordinasikan pelaksanaan pembangunan dan pembinaan kemasyarakatan.', 'Melakukan upaya pemberdayaan masyarakat di dusun.']
            ]
        ];

        $lembagaDesa = [
            [
                'nama' => 'Karang Taruna',
                'deskripsi' => 'Organisasi sosial wadah generasi muda yang aktif dalam kegiatan sosial dan pemberdayaan masyarakat.',
                'program' => ['Pelatihan keterampilan pemuda', 'Kegiatan sosial dan olahraga', 'Partisipasi dalam event desa']
            ],
            [
                'nama' => 'PKK Desa',
                'deskripsi' => 'Pemberdayaan Kesejahteraan Keluarga yang berfokus pada keluarga sejahtera, kesehatan, dan pendidikan.',
                'program' => ['Posyandu dan kesehatan ibu-anak', 'Pelatihan keterampilan rumah tangga', 'Gerakan lingkungan bersih']
            ],
            [
                'nama' => 'LPM (Lembaga Pemberdayaan Masyarakat)',
                'deskripsi' => 'Mitra pemerintah desa dalam perencanaan dan pelaksanaan pembangunan desa.',
                'program' => ['Musrenbangdes', 'Monitoring pembangunan fisik', 'Pendampingan masyarakat']
            ],
            [
                'nama' => 'Linmas Desa',
                'deskripsi' => 'Lembaga yang berperan dalam keamanan dan ketertiban lingkungan desa.',
                'program' => ['Keamanan malam', 'Kesiapsiagaan bencana', 'Pelatihan tanggap darurat']
            ]
        ];

        return view('pages.frontend.profil.struktur-bpd', compact('bpdDeskripsi', 'bpdFungsi', 'bpdTugas', 'bpdMembers', 'strukturSOTK', 'lembagaDesa'));
    }

    /**
     * Tampilkan halaman Sejarah Desa
     */
    public function sejarahDesa()
    {
        $sejarahTitle = "Sejarah Desa Sukakerta";
        $sejarahSubtitle = "Jejak panjang perjalanan Desa Sukakerta dari masa Dalem Cageur hingga masa modern, mencerminkan semangat masyarakat yang tangguh dan berdaya.";

        $asalUsulTitle = "Asal-usul Berdirinya Desa";
        $asalUsulParagraphs = [
            "Konon menurut cerita orang tua, proses berdirinya desa Sukakerta dimulai dengan keberadaan sosok tokoh masyarakat kharismatik yang bernama <strong>Dalem Cageur</strong> (Rd. Muhamad Satari Suryadilaga). Beliau adalah seorang dalem yang berasal dari daerah Kuningan yang pergi meninggalkan kampung halamannya.",
            "Beliau “pundung” dan mengembara, sehingga sampailah beliau di suatu tempat yang berada di kawasan kaki gunung Sawal. Beliau tinggal ditempat tersebut membangun pemukiman dan menikah dengan seorang wanita yang bernama <strong>Jasmirah</strong>. Dari hasil pernikahan tersebut, beliau dikaruniai tiga orang putra yang kemudian memimpin wilayah-wilayah <strong>Kakuwuan Cisaar, Kakuwuan Cidoyang, dan Kakuwuan Sukapulang</strong>.",
            "Seiring perkembangan zaman, putra Dalem Cageur yang bernama <strong>Anggadimerta (H. Muhamad Soleh)</strong> menggabungkan kakuwuan Cisaar dan Cidoyang menjadi <strong>Kakuwuan Sukaraja</strong>. Pada masa kepemimpinan kuwu <strong>H. Sudja’i</strong> (sekitar tahun 1902), Kakuwuan Sukaraja digabung dengan Kakuwuan Sukapulang dan diberi nama <strong>Desa Sukakerta</strong>."
        ];

        $tokohKunci = [
            [
                'nama' => "Dalem Cageur",
                'deskripsi' => "Pendiri desa, berasal dari Kuningan.",
                'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>'
            ],
            [
                'nama' => "Jasmirah",
                'deskripsi' => "Istri Dalem Cageur.",
                'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" /></svg>'
            ],
            [
                'nama' => "Anggadimerta (H. M. Soleh)",
                'deskripsi' => "Putra Dalem Cageur, pemersatu Kakuwuan.",
                'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-3.741-5.582M11.995 12.75a9.004 9.004 0 0 1-5.482-1.935A3 3 0 0 0 3 13.181V18.72A9.094 9.094 0 0 0 6.259 18.24m5.736-5.49a3 3 0 0 0-5.482-1.935 3 3 0 0 0-3.741 5.582M21 13.181V18.72a9.094 9.094 0 0 1-3.741-.479 3 3 0 0 1 3.741-5.582Z" /></svg>'
            ]
        ];

        $kepalaDesa = [
            ['nama' => "Dadang Jayusman", 'masa' => "2021–2026", 'deskripsi' => "Menjabat Kepala Desa saat ini, melanjutkan program pembangunan desa.", 'photo' => asset('assets/img/logo.webp')],
            ['nama' => "H. Engkos Kosim", 'masa' => "2018–2024", 'deskripsi' => "Dilantik pasca Pilkades serentak 2018. Menyusun RPJMDes 2019-2025.", 'photo' => asset('assets/img/logo.webp')],
            ['nama' => "Ujang Tolib (Pj.)", 'masa' => "2018", 'deskripsi' => "Penjabat (Pj.) PNS dari Kec. Panumbangan. Melaksanakan Pilkades serentak 2018 dan membangun Jogging Track.", 'photo' => asset('assets/img/logo.webp')],
            ['nama' => "H. Engkos Kosim", 'masa' => "2012–2018", 'deskripsi' => "Membangun Gedung Dakwah Islam (GDI), Kios Desa, dan perubahan status SMEA Bhakti menjadi SMKN 1 Panumbangan.", 'photo' => asset('assets/img/logo.webp')],
            ['nama' => "E. Husenudin (Pj.)", 'masa' => "2010–2011", 'deskripsi' => "Penjabat (Pj.) Kepala Desa selama satu periode (1 tahun).", 'photo' => null],
            ['nama' => "Edi Kusniadi (Pj.)", 'masa' => "2007–2010", 'deskripsi' => "Penjabat setelah Pilkades 2007 dimenangkan 'Kotak Kosong'. Melaksanakan Rehab Masjid As Salam dan TK Mawar.", 'photo' => null],
            ['nama' => "Herman Suherman (Pj.)", 'masa' => "2006–2007", 'deskripsi' => "Penjabat (Pj.) Sekretaris Desa setelah Dedi Kusmayadi mengundurkan diri. Membangun jalan Sukamanah.", 'photo' => null],
            ['nama' => "Dedi Kusmayadi, S.E.", 'masa' => "1999–2006", 'deskripsi' => "Kuwu termuda pada masanya, memimpin era modernisasi awal Desa Sukakerta dan pembangunan jalan strategis.", 'photo' => null],
            ['nama' => "Oleh Permana & Herman Suherman (Pj.)", 'masa' => "1996–1999", 'deskripsi' => "Masa transisi diisi oleh Penjabat (Pj.) dari Kecamatan (Oleh Permana) dan Sekdes (Herman Suherman).", 'photo' => null],
            ['nama' => "Otong Sukayat Djuwaeni", 'masa' => "1994–1996", 'deskripsi' => "Seorang pensiunan PNS yang berperan dalam penataan administrasi pemerintahan desa.", 'photo' => null],
            ['nama' => "Irin Suherdi", 'masa' => "1984–1994", 'deskripsi' => "Mengembangkan sarana olahraga (Kompetisi antar klub), rehab masjid As Salam, dan rehab THR.", 'photo' => null],
            ['nama' => "Ede Suhada", 'masa' => "1979–1982", 'deskripsi' => "Memimpin masa pemekaran Desa Sukakerta dan Desa Kertaraharja. (Memilih menjadi Kades Kertaraharja).", 'photo' => null],
            ['nama' => "Sutardi", 'masa' => "1975–1978", 'deskripsi' => "Melanjutkan pemerintahan desa pada masa transisi pembangunan daerah.", 'photo' => null],
            ['nama' => "Wihanda (H. Ibrahim)", 'masa' => "1951–1974", 'deskripsi' => "Masa keemasan desa dengan berdirinya THR dan menginisiasi berdirinya SMEA Bhakti Panumbangan.", 'photo' => null],
            ['nama' => "Atmawinata (Emo)", 'masa' => "1948–1951", 'deskripsi' => "Memimpin di masa pemberontakan DI/TII, gugur dalam menjalankan tugas pemerintahan.", 'photo' => null],
            ['nama' => "H. Yusuf Yudharedja", 'masa' => "Tidak Diketahui", 'deskripsi' => "Putra Dalem Cageur yang melanjutkan garis kepemimpinan awal.", 'photo' => null],
            ['nama' => "Sukatmadiredja (Guru Sukatma)", 'masa' => "Tidak Diketahui", 'deskripsi' => "Tokoh pendidik yang juga menjabat kepala desa di masa awal pembentukan.", 'photo' => null],
            ['nama' => "H. Pakih", 'masa' => "Tidak Diketahui", 'deskripsi' => "Salah satu Kuwu pasca H. Sudja'i.", 'photo' => null],
            ['nama' => "H. Sudja’i", 'masa' => "Sekitar 1902", 'deskripsi' => "Menyatukan Kakuwuan Sukaraja menjadi Desa Sukakerta yang diakui pemerintah kolonial Belanda.", 'photo' => null],
            ['nama' => "Anggadimerta (H. M. Soleh)", 'masa' => "Pra 1902", 'deskripsi' => "Pemersatu kakuwuan Cisaar dan Cidoyang menjadi Kakuwuan Sukaraja.", 'photo' => null],
            ['nama' => "Dalem Cageur (Rd. M. Satari S.)", 'masa' => "Pendiri", 'deskripsi' => "Pendiri awal pemukiman di kaki Gunung Sawal yang menjadi cikal bakal desa.", 'photo' => null]
        ];

        return view('pages.frontend.profil.sejarah-desa', compact('sejarahTitle', 'sejarahSubtitle', 'asalUsulTitle', 'asalUsulParagraphs', 'tokohKunci', 'kepalaDesa'));
    }
}

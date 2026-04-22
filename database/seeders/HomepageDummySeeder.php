<?php

namespace Database\Seeders;

use App\Models\AlbumGaleri;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\MediaGaleri;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HomepageDummySeeder extends Seeder
{
    /**
     * Seed dummy data for the public homepage:
     * - Article categories + 6 articles
     * - Product categories + 4 products
     * - 4 gallery albums
     */
    public function run(): void
    {
        $this->seedArticles();
        $this->seedProducts();
        $this->seedGallery();

        $this->command->info('✅ Homepage dummy data seeded successfully!');
    }

    private function seedArticles(): void
    {
        // ── Kategori Artikel ──
        $categories = [
            ['slug' => 'pemerintahan',  'nama_kategori' => 'Pemerintahan'],
            ['slug' => 'pembangunan',   'nama_kategori' => 'Pembangunan'],
            ['slug' => 'kesehatan',     'nama_kategori' => 'Kesehatan'],
            ['slug' => 'pertanian',     'nama_kategori' => 'Pertanian'],
            ['slug' => 'pemberdayaan',  'nama_kategori' => 'Pemberdayaan'],
            ['slug' => 'kegiatan',      'nama_kategori' => 'Kegiatan'],
        ];

        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[$cat['slug']] = ArticleCategory::firstOrCreate(
                ['slug' => $cat['slug']],
                ['nama_kategori' => $cat['nama_kategori']]
            )->id;
        }

        // Find first admin user
        $admin = User::where('role', 'administrator')->first() ?? User::first();
        $adminId = $admin?->id ?? 1;

        $loremBody = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p><h2>Tujuan Kegiatan</h2><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><blockquote>Pembangunan desa yang berkelanjutan memerlukan partisipasi aktif dari seluruh lapisan masyarakat.</blockquote><p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>';

        $articles = [
            [
                'judul'       => 'Musyawarah Rencana Pembangunan (Musrenbang) Desa Sindangmukti Tahun 2026',
                'kategori'    => 'pemerintahan',
                'ringkasan'   => 'Pemerintah Desa Sindangmukti menggelar Musrenbang untuk menyusun Rencana Kerja Pemerintah Desa (RKPDes) tahun 2026. Acara ini dihadiri oleh tokoh masyarakat, RT/RW, dan perwakilan BPD.',
                'view_count'  => 4521,
            ],
            [
                'judul'       => 'Panen Raya Jagung Hibrida Tembus Target, Petani Semringah',
                'kategori'    => 'pertanian',
                'ringkasan'   => 'Kelompok Tani Mekar Jaya Desa Sindangmukti melaksanakan panen raya jagung hibrida binaan desa dengan hasil yang memuaskan dan melebihi target tahun lalu.',
                'view_count'  => 3210,
            ],
            [
                'judul'       => 'Penyaluran BLT Dana Desa Tahap III Berjalan Lancar',
                'kategori'    => 'pemerintahan',
                'ringkasan'   => 'Pemerintahan Desa Sindangmukti telah sukses menyalurkan Bantuan Langsung Tunai (BLT) Dana Desa Tahap III kepada 85 Keluarga Penerima Manfaat.',
                'view_count'  => 2890,
            ],
            [
                'judul'       => 'Posyandu Remaja & Edukasi Pencegahan Stunting Dini',
                'kategori'    => 'kesehatan',
                'ringkasan'   => 'Kader Posyandu bekerjasama dengan Puskesmas setempat mengadakan penyuluhan gizi bagi remaja putri untuk mencegah resiko stunting di masa depan.',
                'view_count'  => 1950,
            ],
            [
                'judul'       => 'Pengaspalan Jalan Dusun II Sepanjang 1,2 KM Telah Rampung',
                'kategori'    => 'pembangunan',
                'ringkasan'   => 'Proyek pengaspalan jalan poros Dusun II yang bersumber dari Dana Desa tahun ini telah selesai 100%, akses perekonomian warga kini lebih mudah.',
                'view_count'  => 2450,
            ],
            [
                'judul'       => 'Pelatihan Digital Marketing Bagi Pelaku UMKM Kerajinan Bambu',
                'kategori'    => 'pemberdayaan',
                'ringkasan'   => 'BUMDes memfasilitasi puluhan pengrajin bambu lokal untuk go-digital melalui pelatihan pemasaran online guna menjangkau pasar nasional.',
                'view_count'  => 1820,
            ],
            [
                'judul'       => 'Budaya Gotong Royong Membersihkan Saluran Irigasi Jelang Musim Hujan',
                'kategori'    => 'kegiatan',
                'ringkasan'   => 'Warga Desa Sindangmukti serentak melakukan kerja bakti membersihkan irigasi persawahan untuk mengantisipasi banjir di musim penghujan.',
                'view_count'  => 1580,
            ],
            [
                'judul'       => 'Desa Cerdas, Desa Berdaulat: Saatnya Mengelola Data Sendiri',
                'kategori'    => 'pemerintahan',
                'ringkasan'   => 'Desa Sindangmukti mulai menerapkan sistem informasi desa digital untuk mengelola data penduduk, administrasi, dan keuangan secara mandiri.',
                'view_count'  => 3514,
            ],
            [
                'judul'       => 'Pembangunan Posyandu Baru Telah Selesai 100%',
                'kategori'    => 'pembangunan',
                'ringkasan'   => 'Posyandu baru di Dusun Sukasari telah selesai dibangun dan siap melayani ibu hamil, balita, dan lansia.',
                'view_count'  => 1250,
            ],
            [
                'judul'       => 'Pelatihan Kader Kesehatan Desa untuk Penanganan Pertama Kecelakaan',
                'kategori'    => 'kesehatan',
                'ringkasan'   => 'Sebanyak 30 kader kesehatan desa mengikuti pelatihan penanganan pertama kecelakaan (P3K) yang diselenggarakan bekerja sama dengan PMI Kabupaten.',
                'view_count'  => 920,
            ],
            [
                'judul'       => 'Program Pembibitan Tanaman Organik untuk Ketahanan Pangan Desa',
                'kategori'    => 'pertanian',
                'ringkasan'   => 'Kelompok Wanita Tani menanam bibit sayur organik di lahan demplot desa sebagai upaya peningkatan ketahanan pangan lokal.',
                'view_count'  => 780,
            ],
            [
                'judul'       => 'Turnamen Voli Antar RT Meriahkan HUT RI ke-81',
                'kategori'    => 'kegiatan',
                'ringkasan'   => 'Dalam rangka memperingati HUT Kemerdekaan RI, Karang Taruna Desa Sindangmukti menyelenggarakan turnamen bola voli antar RT yang diikuti 16 tim.',
                'view_count'  => 2100,
            ],
        ];

        foreach ($articles as $i => $data) {
            Article::firstOrCreate(
                ['slug' => Str::slug($data['judul'])],
                [
                    'judul'        => $data['judul'],
                    'slug'         => Str::slug($data['judul']),
                    'kategori_id'  => $catMap[$data['kategori']],
                    'user_id'      => $adminId,
                    'ringkasan'    => $data['ringkasan'],
                    'konten_html'  => '<p>' . $data['ringkasan'] . '</p>' . $loremBody,
                    'cover_image'  => null,
                    'status'       => 'publish',
                    'published_at' => now()->subDays(($i + 1) * 2),
                    'view_count'   => $data['view_count'],
                ]
            );
        }

        $this->command->info("  → {$this->countNew(Article::class)} articles created");
    }

    private function seedProducts(): void
    {
        $categories = [
            ['name' => 'Makanan', 'slug' => 'makanan', 'icon' => 'fa-utensils'],
            ['name' => 'Minuman', 'slug' => 'minuman', 'icon' => 'fa-mug-hot'],
            ['name' => 'Kerajinan', 'slug' => 'kerajinan', 'icon' => 'fa-basket-shopping'],
            ['name' => 'Kesehatan', 'slug' => 'kesehatan', 'icon' => 'fa-leaf'],
            ['name' => 'Pertanian', 'slug' => 'pertanian', 'icon' => 'fa-seedling'],
            ['name' => 'Fashion', 'slug' => 'fashion', 'icon' => 'fa-shirt'],
        ];

        foreach ($categories as $cat) {
            ProductCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $products = [
            ['name' => 'Keripik Pisang Kepok Manis Gurih (250gr)', 'price' => 15000, 'category_slug' => 'makanan', 'seller_name' => 'UMKM Mekar Jaya', 'seller_phone' => '081234567890', 'stock' => 50, 'desc' => 'Keripik pisang khas Sindangmukti yang terbuat dari pisang kepok pilihan. Diiris tipis, digoreng renyah dengan minyak berkualitas.'],
            ['name' => 'Bakul Nasi Anyaman Bambu Tradisional', 'price' => 45000, 'category_slug' => 'kerajinan', 'seller_name' => 'Pengrajin Dusun II', 'seller_phone' => '081234567891', 'stock' => 25, 'desc' => 'Bakul nasi anyaman bambu tradisional buatan tangan pengrajin lokal. Cocok untuk penyajian nasi liwet.'],
            ['name' => 'Madu Hutan Liar Murni (500ml)', 'price' => 85000, 'category_slug' => 'kesehatan', 'seller_name' => 'Kelompok Tani Lebah', 'seller_phone' => '081234567892', 'stock' => 40, 'desc' => 'Madu hutan liar murni hasil panen langsung dari hutan sekitar Desa Sindangmukti. Tanpa campuran.'],
            ['name' => 'Kopi Bubuk Robusta Asli Petani Lokal', 'price' => 25000, 'category_slug' => 'minuman', 'seller_name' => 'Kopi Mang Ujang', 'seller_phone' => '081234567893', 'stock' => 100, 'desc' => 'Kopi robusta asli petani lokal Sindangmukti. Disangrai dan digiling dengan metode tradisional.'],
            ['name' => 'Kain Batik Tulis Motif Khas Pedesaan', 'price' => 150000, 'category_slug' => 'fashion', 'seller_name' => 'Sanggar Batik Ibu', 'seller_phone' => '081234567894', 'stock' => 15, 'desc' => 'Batik tulis dengan motif khas pedesaan Sindangmukti. Setiap lembar dibuat manual oleh pengrajin terampil.'],
            ['name' => 'Bibit Tanaman Buah Alpukat Miki Unggul', 'price' => 35000, 'category_slug' => 'pertanian', 'seller_name' => 'Koperasi Tani Sejahtera', 'seller_phone' => '081234567895', 'stock' => 200, 'desc' => 'Bibit alpukat miki unggul siap tanam. Sudah berumur 6 bulan dengan akar kuat.'],
            ['name' => 'Sambal Bawang Pedas Botol Kaca (150gr)', 'price' => 22000, 'category_slug' => 'makanan', 'seller_name' => 'Dapur Ibu Titi', 'seller_phone' => '081234567896', 'stock' => 75, 'desc' => 'Sambal bawang pedas homemade. Dibuat dari bawang putih pilihan dan cabai rawit segar tanpa pengawet.'],
            ['name' => 'Rengginang Ketan Asli Renyah Isi 20 Pcs', 'price' => 18000, 'category_slug' => 'makanan', 'seller_name' => 'UMKM Suka Rasa', 'seller_phone' => '081234567897', 'stock' => 60, 'desc' => 'Rengginang ketan asli renyah buatan tangan. Cocok untuk oleh-oleh atau camilan keluarga.'],
        ];

        foreach ($products as $prod) {
            $category = ProductCategory::where('slug', $prod['category_slug'])->first();
            Product::firstOrCreate(
                ['slug' => Str::slug($prod['name'])],
                [
                    'category_id'      => $category?->id,
                    'name'             => $prod['name'],
                    'slug'             => Str::slug($prod['name']),
                    'price'            => $prod['price'],
                    'stock'            => $prod['stock'],
                    'seller_name'      => $prod['seller_name'],
                    'seller_phone'     => $prod['seller_phone'],
                    'description_html' => '<p>' . $prod['desc'] . '</p>',
                    'image_path'       => null,
                    'status'           => 'aktif',
                ]
            );
        }

        $this->command->info("  → " . Product::count() . " total products");
    }

    private function seedGallery(): void
    {
        $albums = [
            ['nama_album' => 'Penyaluran BLT Dana Desa TA. 2024', 'deskripsi' => 'Dokumentasi penyaluran Bantuan Langsung Tunai Dana Desa tahun anggaran 2024.'],
            ['nama_album' => 'PKK Desa Jelang HKP 2024', 'deskripsi' => 'Kegiatan PKK menjelang Hari Kesatuan Perempuan Indonesia.'],
            ['nama_album' => 'TPT Jalan Lapang Volley Sukasari', 'deskripsi' => 'Pembangunan rabat beton jalan menuju lapangan voli Dusun Sukasari.'],
            ['nama_album' => 'Normalisasi Saluran Irigasi', 'deskripsi' => 'Kegiatan normalisasi saluran irigasi untuk mencegah banjir.'],
        ];

        foreach ($albums as $data) {
            AlbumGaleri::firstOrCreate(
                ['nama_album' => $data['nama_album']],
                $data
            );
        }

        $this->command->info("  → {$this->countNew(AlbumGaleri::class)} gallery albums created");
    }

    private function countNew(string $model): int
    {
        return $model::count();
    }
}

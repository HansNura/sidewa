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
        // Find or create article categories
        $katBerita = ArticleCategory::firstOrCreate(
            ['slug' => 'berita'],
            ['nama_kategori' => 'Berita Desa']
        );

        $katKegiatan = ArticleCategory::firstOrCreate(
            ['slug' => 'kegiatan'],
            ['nama_kategori' => 'Kegiatan']
        );

        // Find first admin user
        $admin = User::where('role', 'administrator')->first()
              ?? User::first();

        $adminId = $admin?->id ?? 1;

        $articles = [
            [
                'judul'        => 'Desa Cerdas, Desa Berdaulat: Saatnya Mengelola Data Sendiri',
                'kategori_id'  => $katBerita->id,
                'ringkasan'    => 'Desa Sindangmukti mulai menerapkan sistem informasi desa digital untuk mengelola data penduduk, administrasi, dan keuangan secara mandiri.',
                'cover_image'  => null,
                'view_count'   => 3514,
            ],
            [
                'judul'        => 'Desa di Era Medsos dan Efek Domino Kepercayaan',
                'kategori_id'  => $katBerita->id,
                'ringkasan'    => 'Bagaimana media sosial membentuk persepsi masyarakat terhadap pemerintahan desa dan pentingnya transparansi digital.',
                'cover_image'  => null,
                'view_count'   => 3354,
            ],
            [
                'judul'        => 'Posbankum Desa: Melindungi Warga dengan Kearifan Lokal',
                'kategori_id'  => $katBerita->id,
                'ringkasan'    => 'Program bantuan hukum gratis bagi warga desa yang membutuhkan, dikelola dengan pendekatan kearifan lokal.',
                'cover_image'  => null,
                'view_count'   => 1904,
            ],
            [
                'judul'        => 'Gotong Royong Bersihkan Saluran Irigasi Menjelang Musim Hujan',
                'kategori_id'  => $katKegiatan->id,
                'ringkasan'    => 'Warga Desa Sindangmukti bersama-sama membersihkan saluran irigasi untuk antisipasi banjir saat musim penghujan.',
                'cover_image'  => null,
                'view_count'   => 980,
            ],
            [
                'judul'        => 'Pelatihan UMKM: Ibu-Ibu Desa Sindangmukti Belajar Pemasaran Digital',
                'kategori_id'  => $katKegiatan->id,
                'ringkasan'    => 'Program pelatihan pemasaran digital untuk pelaku UMKM di Desa Sindangmukti guna meningkatkan omzet penjualan.',
                'cover_image'  => null,
                'view_count'   => 1203,
            ],
            [
                'judul'        => 'Pembangunan Posyandu Baru Telah Selesai 100%',
                'kategori_id'  => $katKegiatan->id,
                'ringkasan'    => 'Posyandu baru di Dusun Sukasari telah selesai dibangun dan siap melayani ibu hamil, balita, dan lansia.',
                'cover_image'  => null,
                'view_count'   => 850,
            ],
        ];

        foreach ($articles as $i => $data) {
            Article::firstOrCreate(
                ['slug' => Str::slug($data['judul'])],
                array_merge($data, [
                    'slug'         => Str::slug($data['judul']),
                    'user_id'      => $adminId,
                    'konten_html'  => '<p>' . $data['ringkasan'] . '</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>',
                    'status'       => 'publish',
                    'published_at' => now()->subDays(($i + 1) * 3),
                ])
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

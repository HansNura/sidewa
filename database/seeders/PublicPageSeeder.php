<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PublicPage;

class PublicPageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Beranda
        PublicPage::firstOrCreate(
            ['slug' => '/'],
            [
                'title' => 'Beranda Utama',
                'type' => 'system',
                'status' => 'publish',
                'order' => 1,
            ]
        );

        // 2. Profil Desa (Parent)
        $profil = PublicPage::firstOrCreate(
            ['slug' => 'profil'],
            [
                'title' => 'Profil Desa',
                'type' => 'system',
                'status' => 'publish',
                'order' => 2,
            ]
        );

        // 3. Layanan
        PublicPage::firstOrCreate(
            ['slug' => 'layanan'],
            [
                'title' => 'Layanan Administrasi',
                'type' => 'system',
                'status' => 'publish',
                'order' => 3,
            ]
        );
        
        // 4. Berita (System)
        PublicPage::firstOrCreate(
            ['slug' => 'berita'],
            [
                'title' => 'Berita & Artikel',
                'type' => 'system',
                'status' => 'publish',
                'order' => 4,
            ]
        );

        // Children of Profil
        PublicPage::firstOrCreate(
            ['slug' => 'sejarah'],
            [
                'parent_id' => $profil->id,
                'title' => 'Sejarah Desa',
                'type' => 'custom',
                'status' => 'publish',
                'order' => 1,
                'content_html' => '<p>Desa didirikan oleh tokoh masyarakat pada ratusan tahun lalu.</p>'
            ]
        );

        PublicPage::firstOrCreate(
            ['slug' => 'visi-misi'],
            [
                'parent_id' => $profil->id,
                'title' => 'Visi & Misi',
                'type' => 'custom',
                'status' => 'publish',
                'order' => 2,
                'content_html' => '<p>Visi Maju Jaya Misi Bersama.</p>'
            ]
        );
    }
}

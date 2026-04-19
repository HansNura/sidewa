<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JdihCategory;
use App\Models\JdihDocument;

class TempJdihSeederReal extends Seeder
{
    public function run()
    {
        if (JdihCategory::count() === 0) {
            JdihCategory::insert([
                ['name' => 'Peraturan Desa (Perdes)', 'slug' => 'peraturan-desa', 'description' => 'Keputusan tertinggi di level desa, disetujui BPD.'],
                ['name' => 'SK Kepala Desa', 'slug' => 'sk-kepala-desa', 'description' => 'Surat Keputusan yang bersifat menetapkan.'],
                ['name' => 'Peraturan Kepala Desa (Perkades)', 'slug' => 'peraturan-kepala-desa', 'description' => 'Aturan teknis pelaksana Perdes.']
            ]);
            JdihDocument::factory()->count(40)->create();
        }
    }
}

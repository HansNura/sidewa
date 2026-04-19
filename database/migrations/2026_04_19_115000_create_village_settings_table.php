<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('village_settings', function (Blueprint $table) {
            $table->id();

            // Informasi Administratif
            $table->string('nama_desa')->default('Sindangmukti');
            $table->string('kecamatan')->default('Mangunjaya');
            $table->string('kabupaten')->default('Pangandaran');
            $table->string('provinsi')->default('Jawa Barat');
            $table->string('kode_pos')->default('46353');
            $table->text('alamat')->nullable();

            // Kontak Resmi
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->string('website')->nullable();

            // Pejabat Utama (Kepala Desa)
            $table->string('nama_kades')->nullable();
            $table->string('nip_kades')->nullable();
            $table->string('jabatan_kades')->default('Kepala Desa');

            // Media
            $table->string('logo_path')->nullable();     // storage path
            $table->string('banner_path')->nullable();    // storage path

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('village_settings');
    }
};

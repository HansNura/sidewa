<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertanahan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lahan')->unique();       // LHN-001-DS, LHN-402-WG
            $table->foreignId('penduduk_id')->nullable()->constrained('penduduk')->nullOnDelete();
            $table->enum('kepemilikan', ['desa', 'warga', 'fasum'])->default('warga');
            $table->string('nama_pemilik')->nullable();     // "Pemerintah Desa" or null (uses penduduk)
            $table->integer('luas');                         // m²
            $table->string('lokasi_blok');                   // "Kompleks Balai Desa"
            $table->string('dusun')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->enum('legalitas', ['shm', 'shgb', 'girik', 'ajb', 'belum_sertifikat'])->default('belum_sertifikat');
            $table->string('nomor_sertifikat')->nullable();
            $table->json('geojson')->nullable();             // GeoJSON polygon data
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertanahan');
    }
};

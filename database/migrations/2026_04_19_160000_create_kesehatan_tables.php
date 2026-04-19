<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Measurement records for children (posyandu visits)
        Schema::create('pengukuran_balita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduk')->cascadeOnDelete();
            $table->date('tanggal_pengukuran');
            $table->integer('umur_bulan');              // Age in months at measurement
            $table->decimal('tinggi_badan', 5, 1);      // Height in cm
            $table->decimal('berat_badan', 5, 1);       // Weight in kg
            $table->enum('status_gizi', ['normal', 'pendek', 'sangat_pendek', 'tinggi'])
                  ->default('normal');
            $table->string('nama_ortu')->nullable();     // Parent/guardian name
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Stunting intervention programs
        Schema::create('intervensi_stunting', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                      // Program name
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['berjalan', 'terjadwal', 'selesai'])->default('terjadwal');
            $table->integer('target_peserta')->default(0);
            $table->integer('peserta_terdaftar')->default(0);
            $table->integer('progres')->default(0);      // 0-100 percentage
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intervensi_stunting');
        Schema::dropIfExists('pengukuran_balita');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku_tamu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tamu');
            $table->string('instansi')->nullable();
            $table->enum('tujuan_kategori', ['Layanan Surat', 'Koordinasi', 'Lain-lain'])->default('Lain-lain');
            $table->text('keperluan')->nullable();
            $table->string('foto_ktp_url')->nullable();
            $table->enum('metode_input', ['kiosk', 'manual'])->default('manual');
            $table->enum('status', ['menunggu', 'dilayani', 'selesai'])->default('selesai');
            $table->timestamp('waktu_masuk')->useCurrent();
            $table->timestamp('waktu_keluar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_tamu');
    }
};

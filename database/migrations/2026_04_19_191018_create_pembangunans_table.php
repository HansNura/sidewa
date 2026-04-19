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
        Schema::create('pembangunans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apbdes_id')->nullable()->constrained('apbdes')->onDelete('set null'); // Tautkan ke Belanja APBDes
            $table->string('nama_proyek');
            $table->text('deskripsi')->nullable();
            $table->string('kategori'); // Infrastruktur Jalan, Fasilitas Umum, Irigasi/Pertanian, dll
            
            // Lokasi
            $table->string('lokasi_dusun')->nullable();
            $table->string('rt_rw')->nullable();
            $table->decimal('latitude', 10, 8)->nullable(); // Koordinat peta
            $table->decimal('longitude', 11, 8)->nullable();

            // Timeline
            $table->date('tanggal_mulai')->nullable();
            $table->date('target_selesai')->nullable();

            // Progress & Status
            $table->integer('progres_fisik')->default(0); // 0 - 100
            $table->enum('status', ['perencanaan', 'berjalan', 'selesai', 'terlambat'])->default('perencanaan');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembangunans');
    }
};

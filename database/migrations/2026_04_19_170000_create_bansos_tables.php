<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Aid programs (BLT DD, BPNT, Rutilahu, etc.)
        Schema::create('program_bansos', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                      // "BLT Dana Desa"
            $table->text('deskripsi')->nullable();        // "Bantuan Tunai Langsung (Tahap 2)"
            $table->string('icon')->default('fa-hand-holding-dollar');
            $table->string('icon_bg')->default('bg-blue-50');
            $table->string('icon_color')->default('text-blue-600');
            $table->string('periode')->nullable();        // "Q2 2026", "Tahunan"
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });

        // Recipients (penerima manfaat / KPM)
        Schema::create('penerima_bansos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduk')->cascadeOnDelete();
            $table->foreignId('program_bansos_id')->constrained('program_bansos')->cascadeOnDelete();
            $table->string('tahap')->nullable();          // "Tahap 1", "Tahap 2 (April)"
            $table->integer('desil')->nullable();         // 1-4 (poverty level)
            $table->enum('status_distribusi', ['pending', 'siap_diambil', 'diterima', 'tertahan'])
                  ->default('pending');
            $table->date('tanggal_distribusi')->nullable();
            $table->text('catatan_audit')->nullable();
            $table->boolean('is_duplikat')->default(false);
            $table->timestamps();

            // Prevent exact duplicate (same person + same program + same tahap)
            $table->unique(['penduduk_id', 'program_bansos_id', 'tahap'], 'unique_penerima');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerima_bansos');
        Schema::dropIfExists('program_bansos');
    }
};

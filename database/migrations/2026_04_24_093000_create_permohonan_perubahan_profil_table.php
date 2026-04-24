<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel untuk menyimpan permohonan perubahan data profil warga.
 * Warga mengajukan → Operator desa memvalidasi & approve/reject.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan_perubahan_profil', function (Blueprint $table) {
            $table->id();

            // Warga yang mengajukan
            $table->string('nik', 16)->index();
            $table->string('nama_warga');

            // Status permohonan
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');

            // Data yang ingin diubah (JSON: hanya field yang berubah)
            $table->json('data_lama')->nullable();  // Snapshot data sebelum perubahan
            $table->json('data_baru');              // Data yang diajukan warga

            // Dokumen pendukung (path file)
            $table->string('foto_ktp')->nullable();
            $table->string('foto_kk')->nullable();

            // Keterangan alasan pengajuan
            $table->text('keterangan')->nullable();

            // Response dari operator
            $table->text('catatan_operator')->nullable();
            $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diproses_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_perubahan_profil');
    }
};

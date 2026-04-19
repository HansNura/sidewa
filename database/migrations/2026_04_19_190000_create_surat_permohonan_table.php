<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_permohonan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tiket')->unique();            // #TKT-260418-001
            $table->foreignId('penduduk_id')->constrained('penduduk')->cascadeOnDelete();
            $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('jenis_surat', [
                'sktm', 'domisili', 'pengantar_usaha',
                'kematian', 'pengantar_nikah', 'pindah',
            ]);
            $table->enum('prioritas', ['normal', 'tinggi'])->default('normal');
            $table->enum('status', [
                'pengajuan', 'verifikasi', 'menunggu_tte', 'selesai', 'ditolak',
            ])->default('pengajuan');
            $table->text('catatan')->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_permohonan');
    }
};

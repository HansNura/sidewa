<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_permohonan', function (Blueprint $table) {
            $table->string('keperluan')->nullable()->after('catatan');
            $table->string('berlaku_hingga')->default('1 Bulan')->after('keperluan');
            $table->string('nama_usaha')->nullable()->after('berlaku_hingga');
        });

        // Add 'draft' to status enum
        DB::statement("ALTER TABLE surat_permohonan MODIFY COLUMN status ENUM('draft','pengajuan','verifikasi','menunggu_tte','selesai','ditolak') DEFAULT 'pengajuan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE surat_permohonan MODIFY COLUMN status ENUM('pengajuan','verifikasi','menunggu_tte','selesai','ditolak') DEFAULT 'pengajuan'");

        Schema::table('surat_permohonan', function (Blueprint $table) {
            $table->dropColumn(['keperluan', 'berlaku_hingga', 'nama_usaha']);
        });
    }
};

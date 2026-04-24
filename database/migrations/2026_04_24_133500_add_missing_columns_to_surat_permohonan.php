<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix GAP-02/08/09: Add missing columns to surat_permohonan.
     * - kades_id: Track who signed (TTE) for audit trail
     * - lampiran_path: For future file upload feature
     * - pdf_path: For generated PDF storage
     *
     * Note: keperluan, berlaku_hingga, nama_usaha already exist in DB.
     */
    public function up(): void
    {
        Schema::table('surat_permohonan', function (Blueprint $table) {
            // GAP-02: Track siapa Kades yang TTE (audit trail)
            if (!Schema::hasColumn('surat_permohonan', 'kades_id')) {
                $table->foreignId('kades_id')->nullable()->after('operator_id')
                      ->constrained('users')->nullOnDelete();
            }

            // Untuk fitur upload lampiran & PDF (Tahap 2)
            if (!Schema::hasColumn('surat_permohonan', 'lampiran_path')) {
                $table->string('lampiran_path')->nullable()->after('nama_usaha');
            }

            if (!Schema::hasColumn('surat_permohonan', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('lampiran_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('surat_permohonan', function (Blueprint $table) {
            if (Schema::hasColumn('surat_permohonan', 'kades_id')) {
                $table->dropForeign(['kades_id']);
                $table->dropColumn('kades_id');
            }
            $table->dropColumn(array_filter([
                Schema::hasColumn('surat_permohonan', 'lampiran_path') ? 'lampiran_path' : null,
                Schema::hasColumn('surat_permohonan', 'pdf_path') ? 'pdf_path' : null,
            ]));
        });
    }
};

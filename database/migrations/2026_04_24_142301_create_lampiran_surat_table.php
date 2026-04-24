<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * GAP-08: Table for surat lampiran (attachments).
     * Supports multiple file uploads per surat permohonan.
     */
    public function up(): void
    {
        Schema::create('lampiran_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_permohonan_id')
                  ->constrained('surat_permohonan')
                  ->cascadeOnDelete();
            $table->string('nama_file');
            $table->string('path');
            $table->string('tipe'); // ktp, kk, pendukung
            $table->unsignedInteger('ukuran')->default(0); // bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampiran_surat');
    }
};

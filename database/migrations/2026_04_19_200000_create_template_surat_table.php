<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                                          // "Surat Keterangan Tidak Mampu"
            $table->string('kode')->unique();                                // "sktm" — maps to jenis_surat
            $table->enum('kategori', ['keterangan', 'pengantar', 'rekomendasi'])->default('keterangan');
            $table->string('deskripsi')->nullable();
            $table->longText('body_template');                               // HTML/text with {{placeholders}}
            $table->json('fields')->nullable();                              // Dynamic field definitions
            $table->json('layout_settings')->nullable();                     // {show_kop, show_ttd, show_qr, margins}
            $table->boolean('is_active')->default(true);
            $table->string('versi')->default('v1.0');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_surat');
    }
};

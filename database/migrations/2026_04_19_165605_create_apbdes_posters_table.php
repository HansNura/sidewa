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
        Schema::create('apbdes_posters', function (Blueprint $table) {
            $table->id();
            $table->year('tahun')->unique();
            $table->string('gambar_baliho_url')->nullable();
            $table->string('perdes_dokumen_url')->nullable();
            $table->string('rab_dokumen_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apbdes_posters');
    }
};

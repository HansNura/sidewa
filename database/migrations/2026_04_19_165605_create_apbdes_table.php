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
        Schema::create('apbdes', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->enum('tipe_anggaran', ['PENDAPATAN', 'BELANJA', 'PEMBIAYAAN']);
            $table->string('kode_rekening');
            $table->string('nama_kegiatan');
            $table->bigInteger('pagu_anggaran')->default(0);
            $table->string('sumber_dana')->nullable(); // DD, ADD, PADesa, dll
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            // Kombinasi tahun dan kode_rekening harus unik (karena ini buku besar APBDes per tahun)
            $table->unique(['tahun', 'kode_rekening']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apbdes');
    }
};

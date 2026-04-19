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
        Schema::create('perencanaans', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_rencana', ['rkpdes', 'rpjmdes']);
            $table->string('tahun_pelaksanaan'); // Contoh: '2026' atau '2024 - 2030'
            $table->string('prioritas'); // 'tinggi', 'sedang', 'normal'
            $table->string('nama_program');
            $table->text('tujuan_sasaran')->nullable();
            $table->decimal('estimasi_pagu', 15, 2)->default(0);
            $table->string('sumber_dana')->nullable();
            $table->string('kategori')->default('Infrastruktur Jalan');
            
            // Targets
            $table->string('target_mulai')->nullable(); // format Y-m misal 2026-05
            $table->string('target_selesai')->nullable(); // format Y-m

            // Traceability / Convertion
            $table->enum('status', ['draft', 'dikonversi'])->default('draft');
            $table->foreignId('pembangunan_id')->nullable()->constrained('pembangunans')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perencanaans');
    }
};

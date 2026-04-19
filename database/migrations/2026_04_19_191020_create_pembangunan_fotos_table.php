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
        Schema::create('pembangunan_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembangunan_id')->constrained('pembangunans')->onDelete('cascade');
            $table->string('foto_path');
            $table->string('keterangan')->nullable(); // e.g. 0% Persiapan
            $table->integer('progres_saat_foto')->default(0); // Buat nempel data progres ke foto ini.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembangunan_fotos');
    }
};

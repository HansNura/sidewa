<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['dusun', 'rw', 'rt']);
            $table->string('nama');                          // "Kaler", "01", "03"
            $table->foreignId('parent_id')->nullable()       // Self-referencing hierarchy
                  ->constrained('wilayah')->cascadeOnDelete();
            $table->string('kepala_nama')->nullable();       // Head / leader name
            $table->string('kepala_jabatan')->nullable();    // e.g. "Kadus Kaler", "Ketua RW"
            $table->string('kepala_telepon')->nullable();
            $table->json('geojson')->nullable();             // GeoJSON polygon data
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah');
    }
};

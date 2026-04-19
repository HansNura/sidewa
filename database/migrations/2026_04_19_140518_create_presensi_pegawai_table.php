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
        Schema::create('presensi_pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('waktu_masuk')->nullable();
            $table->time('waktu_pulang')->nullable();
            
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'sakit', 'dinas', 'alpha'])->default('alpha');
            $table->string('metode_masuk')->nullable(); // kiosk, face_capture, manual
            $table->string('metode_pulang')->nullable(); // kiosk, face_capture, manual
            
            $table->string('foto_masuk_url')->nullable();
            $table->string('foto_pulang_url')->nullable();
            
            $table->text('catatan')->nullable();
            $table->foreignId('dikoreksi_oleh')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();

            $table->unique(['user_id', 'tanggal'], 'presensi_user_tanggal_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_pegawai');
    }
};

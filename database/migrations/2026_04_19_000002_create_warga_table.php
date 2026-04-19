<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel warga untuk sistem Layanan Mandiri Warga.
     * Auth terpisah dari tabel users (petugas desa).
     */
    public function up(): void
    {
        Schema::create('warga', function (Blueprint $table) {
            $table->id();

            // Identitas utama
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->string('no_kk', 16)->nullable();

            // Data kependudukan
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('agama')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->enum('status_perkawinan', ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'])->nullable();

            // Alamat
            $table->text('alamat')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('dusun')->nullable();

            // Keamanan (login)
            $table->string('pin');  // hashed 6-digit PIN
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warga');
    }
};

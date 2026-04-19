<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penduduk', function (Blueprint $table) {
            $table->id();

            // Identitas Utama
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama');
            $table->string('golongan_darah')->nullable();

            // Data Keluarga
            $table->string('no_kk', 16);
            $table->string('status_hubungan');        // Kepala Keluarga, Istri, Anak, dll
            $table->string('status_perkawinan');       // Belum Kawin, Kawin, Cerai Hidup, Cerai Mati
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();

            // Pendidikan & Pekerjaan
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();

            // Alamat & Wilayah
            $table->text('alamat')->nullable();
            $table->string('dusun')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();

            // Status Warga
            $table->enum('status', ['hidup', 'pindah', 'meninggal'])->default('hidup');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};

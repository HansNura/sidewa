<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_exchange_logs', function (Blueprint $table) {
            $table->id();
            $table->string('tipe'); // export / import
            $table->string('modul_tujuan'); // penduduk / kk / bansos / apbdes
            $table->string('nama_file');
            $table->string('status'); // success / failed
            $table->integer('jumlah_berhasil')->default(0);
            $table->integer('jumlah_gagal')->default(0);
            $table->text('catatan_error')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_exchange_logs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_keluarga', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 16)->unique();
            $table->text('alamat')->nullable();
            $table->string('dusun')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->date('tanggal_dikeluarkan')->nullable();
            $table->timestamps();
        });

        // Link penduduk to kartu_keluarga via FK
        Schema::table('penduduk', function (Blueprint $table) {
            $table->foreignId('kartu_keluarga_id')->nullable()->after('no_kk')
                ->constrained('kartu_keluarga')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('penduduk', function (Blueprint $table) {
            $table->dropForeign(['kartu_keluarga_id']);
            $table->dropColumn('kartu_keluarga_id');
        });
        Schema::dropIfExists('kartu_keluarga');
    }
};

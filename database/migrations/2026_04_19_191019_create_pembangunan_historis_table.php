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
        Schema::create('pembangunan_historis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembangunan_id')->constrained('pembangunans')->onDelete('cascade');
            $table->string('judul_update'); // Misal: Penerimaan Material
            $table->string('deskripsi')->nullable();
            $table->date('tanggal');
            $table->string('oleh_siapa')->nullable();
            $table->boolean('is_milestone')->default(false); // kalau true, dikasih icon unik
            $table->integer('progres_dicapai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembangunan_historis');
    }
};

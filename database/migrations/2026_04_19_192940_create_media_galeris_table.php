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
        Schema::create('media_galeris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->nullable()->constrained('album_galeris')->onDelete('set null');
            $table->string('file_path');
            $table->string('file_name');
            $table->enum('file_type', ['image', 'video']);
            $table->string('mime_type');
            $table->bigInteger('file_size'); // bytes
            $table->text('deskripsi')->nullable();
            $table->string('tags')->nullable();
            $table->string('uploader_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_galeris');
    }
};

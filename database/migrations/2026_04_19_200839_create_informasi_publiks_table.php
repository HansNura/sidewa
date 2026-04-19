<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informasi_publik', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->index(); // 'pengumuman' | 'agenda'
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content_html')->nullable();
            
            // Scheduling
            $table->dateTime('start_date')->index();
            $table->dateTime('end_date')->nullable()->index();
            
            // Valid only for agenda
            $table->string('location')->nullable();
            
            $table->string('image_path')->nullable();
            $table->string('status', 50)->default('publish')->index(); // 'draft' | 'publish' | 'archived'
            
            $table->timestamps();

            // Indexes for optimizing list mixed view
            $table->index(['status', 'type', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi_publik');
    }
};

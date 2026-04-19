<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');                       // 'Data Kependudukan'
            $table->string('slug')->unique();             // 'data-kependudukan'
            $table->string('icon')->default('fa-solid fa-cube');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_sensitive')->default(false); // system-level modules
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};

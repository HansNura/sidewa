<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();           // matches users.role enum
            $table->string('display_name');              // 'Administrator', 'Kepala Desa'
            $table->text('description')->nullable();
            $table->string('icon')->default('fa-solid fa-shield-halved');
            $table->string('color')->default('gray');    // blue, emerald, amber, gray, pink, teal, purple
            $table->boolean('is_system')->default(false); // system roles can't be deleted
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};

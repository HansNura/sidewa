<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jdih_documents', function (Blueprint $table) {
            $table->id();
            // Protect category from deletion if documents exist
            $table->foreignId('category_id')->constrained('jdih_categories')->restrictOnDelete();
            
            $table->string('title');
            $table->string('document_number');
            $table->date('established_date');
            
            // Berlaku, Dicabut, Draft
            $table->string('status')->default('berlaku'); 
            
            $table->text('description')->nullable();
            
            $table->string('file_path')->nullable();
            $table->integer('file_size')->default(0); // in bytes
            
            $table->foreignId('uploader_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jdih_documents');
    }
};

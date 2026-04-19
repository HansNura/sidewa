<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // User requested to protect delete on category if there are products
            $table->foreignId('category_id')->constrained('product_categories')->restrictOnDelete();
            
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('price')->default(0);
            $table->integer('stock')->nullable(); // null means infinite/dynamic
            
            $table->string('seller_name');
            $table->string('seller_phone');
            
            $table->longText('description_html');
            $table->string('image_path')->nullable();
            
            $table->string('status')->default('aktif'); // aktif, nonaktif
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

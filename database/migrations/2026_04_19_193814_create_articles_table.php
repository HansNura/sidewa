<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('kategori_id')->nullable()->constrained('article_categories')->onDelete('set null');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->longText('konten_html')->nullable();
            $table->text('ringkasan')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('status', ['draft', 'publish', 'schedule', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

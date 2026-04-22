<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Note: columns (is_important, contact_person, time_text, excerpt) were already added
     * via a previous migration or manual addition. This migration is kept for tracking.
     */
    public function up(): void
    {
        Schema::table('informasi_publik', function (Blueprint $table) {
            // Columns already exist — no-op
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informasi_publik', function (Blueprint $table) {
            // No-op
        });
    }
};

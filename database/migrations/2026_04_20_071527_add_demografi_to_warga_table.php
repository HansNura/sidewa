<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warga', function (Blueprint $table) {
            $table->string('pendidikan_terakhir')->nullable()->after('pekerjaan');
            $table->enum('kesejahteraan', ['sejahtera', 'pra-sejahtera'])->default('sejahtera')->after('pendidikan_terakhir');
            $table->boolean('is_stunting')->default(false)->after('kesejahteraan');
        });
    }

    public function down(): void
    {
        Schema::table('warga', function (Blueprint $table) {
            $table->dropColumn(['pendidikan_terakhir', 'kesejahteraan', 'is_stunting']);
        });
    }
};

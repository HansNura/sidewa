<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jdih_documents', function (Blueprint $table) {
            $table->unsignedInteger('download_count')->default(0)->after('file_size');
            $table->string('signer')->nullable()->after('description');
            $table->string('initiator')->nullable()->after('signer');
            $table->date('promulgated_date')->nullable()->after('established_date');
        });
    }

    public function down(): void
    {
        Schema::table('jdih_documents', function (Blueprint $table) {
            $table->dropColumn(['download_count', 'signer', 'initiator', 'promulgated_date']);
        });
    }
};

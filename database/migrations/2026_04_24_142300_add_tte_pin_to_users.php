<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * GAP-03: Add TTE PIN column for Kepala Desa digital signature.
     * The PIN is hashed (bcrypt) and verified during TTE approval.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tte_pin')->nullable()->after('jabatan');
        });

        // Set default TTE PIN for existing kades user (dev only)
        \App\Models\User::where('role', 'kades')->update([
            'tte_pin' => Hash::make('123456'),
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tte_pin');
        });
    }
};

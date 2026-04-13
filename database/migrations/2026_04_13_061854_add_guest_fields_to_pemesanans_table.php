<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            // Make user_id nullable for guest orders
            $table->foreignId('user_id')->nullable()->change();
            
            // Add guest fields
            $table->string('guest_nama')->nullable()->after('user_id');
            $table->string('guest_email')->nullable()->after('guest_nama');
            $table->string('guest_phone')->nullable()->after('guest_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->dropColumn(['guest_nama', 'guest_email', 'guest_phone']);
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};

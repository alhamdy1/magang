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
        // Add NIK to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->unique()->nullable()->after('email');
        });

        // Add guest fields and tracking number to permits table
        Schema::table('permits', function (Blueprint $table) {
            // Make user_id nullable for guest submissions
            $table->foreignId('user_id')->nullable()->change();
            
            // Tracking number for all permits (generated automatically)
            $table->string('tracking_number', 20)->unique()->nullable()->after('id');
            
            // Guest information (only filled when submitted without account)
            $table->boolean('is_guest')->default(false)->after('tracking_number');
            $table->string('guest_email')->nullable()->after('is_guest');
            $table->string('guest_phone', 20)->nullable()->after('guest_email');
            $table->string('guest_nik', 16)->nullable()->after('guest_phone');
            
            // NIK for the permit (for both guest and registered users)
            $table->string('nik_pemohon', 16)->nullable()->after('nama_pemohon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nik');
        });

        Schema::table('permits', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_number',
                'is_guest',
                'guest_email',
                'guest_phone',
                'guest_nik',
                'nik_pemohon'
            ]);
        });
    }
};

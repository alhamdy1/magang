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
        Schema::create('permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('permit_number')->unique()->nullable();
            
            // Applicant information
            $table->string('nama_pemohon'); // nama/badan/organisasi
            $table->text('alamat'); // alamat
            $table->string('nomor_telepon'); // nomor telepon
            $table->enum('klasifikasi', ['permanen', 'non_permanen']); // jenis/klasifikasi
            $table->string('ukuran_jumlah'); // ukuran/jumlah
            $table->text('narasi'); // narasi
            
            // Location with precise GPS coordinates
            $table->string('lokasi_alamat'); // alamat lokasi reklame
            $table->decimal('latitude', 10, 8); // koordinat latitude
            $table->decimal('longitude', 11, 8); // koordinat longitude
            
            // Status tracking
            $table->enum('status', [
                'draft',
                'submitted',
                'operator_review',
                'operator_approved',
                'operator_rejected',
                'kasi_review',
                'kasi_approved',
                'kasi_rejected',
                'kabid_review',
                'kabid_approved',
                'kabid_rejected',
                'completed'
            ])->default('draft');
            
            // Operator claim system (to prevent multiple operators checking same request)
            $table->foreignId('claimed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('claimed_at')->nullable();
            
            // Notes from reviewers
            $table->text('operator_notes')->nullable();
            $table->text('kasi_notes')->nullable();
            $table->text('kabid_notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permits');
    }
};

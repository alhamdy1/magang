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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permit_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', [
                'ktp',                      // foto kopi ktp berwarna
                'npwp',                     // foto copy npwp berwarna
                'akte_pendirian',           // foto copy akte pendirian
                'retribusi_pajak',          // foto copy retribusi pajak reklame
                'data_pemohon',             // data isian pemohon
                'surat_pernyataan',         // surat penyataan pertanggung jawaban konstruksi
                'foto_kondisi',             // foto kondisi dan gambar tampilan visualisasi reklame
                'gambar_konstruksi',        // gambar konstruksi bidangan
                'surat_permohonan',         // surat permohonan izin
                'surat_kuasa'               // surat kuasa (opsional)
            ]);
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

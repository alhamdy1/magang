<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'permit_id',
        'document_type',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
    ];

    /**
     * Get the permit that owns this document.
     */
    public function permit()
    {
        return $this->belongsTo(Permit::class);
    }

    /**
     * Get the document type label in Indonesian.
     */
    public function getTypeLabelAttribute()
    {
        return match($this->document_type) {
            'ktp' => 'Foto Kopi KTP Berwarna',
            'npwp' => 'Foto Copy NPWP Berwarna',
            'akte_pendirian' => 'Foto Copy Akte Pendirian',
            'retribusi_pajak' => 'Foto Copy Retribusi Pajak Reklame',
            'data_pemohon' => 'Data Isian Pemohon',
            'surat_pernyataan' => 'Surat Pernyataan Pertanggung Jawaban Konstruksi',
            'foto_kondisi' => 'Foto Kondisi dan Gambar Tampilan Visualisasi Reklame',
            'gambar_konstruksi' => 'Gambar Konstruksi Bidangan',
            'surat_permohonan' => 'Surat Permohonan Izin',
            'surat_kuasa' => 'Surat Kuasa (Opsional)',
            default => $this->document_type,
        };
    }

    /**
     * Check if document type is required.
     */
    public static function isRequired($type)
    {
        return $type !== 'surat_kuasa';
    }

    /**
     * Get all document types.
     */
    public static function getDocumentTypes()
    {
        return [
            'ktp' => 'Foto Kopi KTP Berwarna',
            'npwp' => 'Foto Copy NPWP Berwarna',
            'akte_pendirian' => 'Foto Copy Akte Pendirian',
            'retribusi_pajak' => 'Foto Copy Retribusi Pajak Reklame',
            'data_pemohon' => 'Data Isian Pemohon',
            'surat_pernyataan' => 'Surat Pernyataan Pertanggung Jawaban Konstruksi',
            'foto_kondisi' => 'Foto Kondisi dan Gambar Tampilan Visualisasi Reklame',
            'gambar_konstruksi' => 'Gambar Konstruksi Bidangan',
            'surat_permohonan' => 'Surat Permohonan Izin',
            'surat_kuasa' => 'Surat Kuasa (Opsional)',
        ];
    }
}

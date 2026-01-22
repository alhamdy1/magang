<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    protected $fillable = [
        'user_id',
        'permit_number',
        'nama_pemohon',
        'alamat',
        'nomor_telepon',
        'klasifikasi',
        'ukuran_jumlah',
        'narasi',
        'lokasi_alamat',
        'latitude',
        'longitude',
        'status',
        'claimed_by',
        'claimed_at',
        'operator_notes',
        'kasi_notes',
        'kabid_notes',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the user who created this permit.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the operator who claimed this permit.
     */
    public function claimedBy()
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }

    /**
     * Get the documents for this permit.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the approval history for this permit.
     */
    public function approvalHistories()
    {
        return $this->hasMany(ApprovalHistory::class);
    }

    /**
     * Get a specific document by type.
     */
    public function getDocument($type)
    {
        return $this->documents()->where('document_type', $type)->first();
    }

    /**
     * Check if permit is claimed by an operator.
     */
    public function isClaimed()
    {
        return !is_null($this->claimed_by);
    }

    /**
     * Check if permit is claimed by a specific user.
     */
    public function isClaimedBy($userId)
    {
        return $this->claimed_by === $userId;
    }

    /**
     * Generate permit number.
     */
    public static function generatePermitNumber()
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('IZIN-%s-%05d', $year, $count);
    }

    /**
     * Get status label in Indonesian.
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Menunggu Review Operator',
            'operator_review' => 'Sedang Direview Operator',
            'operator_approved' => 'Disetujui Operator',
            'operator_rejected' => 'Ditolak Operator',
            'kasi_review' => 'Sedang Direview Kasi',
            'kasi_approved' => 'Disetujui Kasi',
            'kasi_rejected' => 'Ditolak Kasi',
            'kabid_review' => 'Sedang Direview Kabid',
            'kabid_approved' => 'Disetujui Kabid',
            'kabid_rejected' => 'Ditolak Kabid',
            'completed' => 'Selesai - Silahkan Ambil Dokumen',
            default => $this->status,
        };
    }

    /**
     * Get status color for UI.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'submitted' => 'blue',
            'operator_review', 'kasi_review', 'kabid_review' => 'yellow',
            'operator_approved', 'kasi_approved', 'kabid_approved', 'completed' => 'green',
            'operator_rejected', 'kasi_rejected', 'kabid_rejected' => 'red',
            default => 'gray',
        };
    }
}

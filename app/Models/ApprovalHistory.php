<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    protected $fillable = [
        'permit_id',
        'user_id',
        'action',
        'level',
        'old_status',
        'new_status',
        'notes',
    ];

    /**
     * Get the permit that this history belongs to.
     */
    public function permit()
    {
        return $this->belongsTo(Permit::class);
    }

    /**
     * Get the user who performed this action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get action label in Indonesian.
     */
    public function getActionLabelAttribute()
    {
        return match($this->action) {
            'submitted' => 'Mengajukan Permohonan',
            'approved' => 'Menyetujui',
            'rejected' => 'Menolak',
            'claimed' => 'Mengambil untuk Review',
            'released' => 'Melepaskan Review',
            default => $this->action,
        };
    }

    /**
     * Get level label in Indonesian.
     */
    public function getLevelLabelAttribute()
    {
        return match($this->level) {
            'user' => 'Pemohon',
            'operator' => 'Operator',
            'kasi' => 'Kasi Perijinan',
            'kabid' => 'Kabid Penyelenggaraan',
            default => $this->level,
        };
    }
}

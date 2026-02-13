<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Permit extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_number',
        'permit_number',
        'is_guest',
        'guest_email',
        'guest_phone',
        'guest_nik',
        'nama_pemohon',
        'nik_pemohon',
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
        'is_guest' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when permit is updated
        static::saved(function ($permit) {
            Cache::forget("permit:{$permit->id}");
            Cache::forget("permit:tracking:{$permit->tracking_number}");
        });

        static::deleted(function ($permit) {
            Cache::forget("permit:{$permit->id}");
            Cache::forget("permit:tracking:{$permit->tracking_number}");
        });
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

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
        return $this->hasMany(ApprovalHistory::class)->orderBy('created_at', 'desc');
    }

    // ========================================
    // QUERY SCOPES
    // ========================================

    /**
     * Scope to filter by status.
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by multiple statuses.
     */
    public function scopeStatuses(Builder $query, array $statuses): Builder
    {
        return $query->whereIn('status', $statuses);
    }

    /**
     * Scope for pending operator review.
     */
    public function scopePendingOperator(Builder $query): Builder
    {
        return $query->where('status', 'submitted')->whereNull('claimed_by');
    }

    /**
     * Scope for pending kasi review.
     */
    public function scopePendingKasi(Builder $query): Builder
    {
        return $query->where('status', 'operator_approved');
    }

    /**
     * Scope for pending kabid review.
     */
    public function scopePendingKabid(Builder $query): Builder
    {
        return $query->where('status', 'kasi_approved');
    }

    /**
     * Scope for completed permits.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for rejected permits.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'like', '%_rejected');
    }

    /**
     * Scope to filter by guest email and NIK.
     */
    public function scopeForGuest(Builder $query, string $email, string $nik): Builder
    {
        return $query->where('is_guest', true)
            ->where('guest_email', strtolower($email))
            ->where('guest_nik', $nik);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope with eager loading for common relationships.
     */
    public function scopeWithDetails(Builder $query): Builder
    {
        return $query->with(['user', 'documents', 'approvalHistories.user', 'claimedBy']);
    }

    // ========================================
    // HELPER METHODS
    // ========================================

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
    public function isClaimed(): bool
    {
        return !is_null($this->claimed_by);
    }

    /**
     * Check if permit is claimed by a specific user.
     */
    public function isClaimedBy($userId): bool
    {
        return $this->claimed_by === $userId;
    }

    /**
     * Check if this is a guest submission.
     */
    public function isGuest(): bool
    {
        return (bool) $this->is_guest;
    }

    /**
     * Check if permit is in final state.
     */
    public function isFinal(): bool
    {
        return in_array($this->status, ['completed', 'operator_rejected', 'kasi_rejected', 'kabid_rejected']);
    }

    /**
     * Check if permit is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if permit is rejected.
     */
    public function isRejected(): bool
    {
        return str_ends_with($this->status, '_rejected');
    }

    // ========================================
    // STATIC METHODS
    // ========================================

    /**
     * Find permit by tracking number with caching.
     */
    public static function findByTracking(string $trackingNumber): ?self
    {
        return Cache::remember(
            "permit:tracking:{$trackingNumber}",
            now()->addMinutes(5),
            fn() => static::where('tracking_number', $trackingNumber)->first()
        );
    }

    /**
     * Generate permit number.
     */
    public static function generatePermitNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('IZIN-%s-%05d', $year, $count);
    }

    /**
     * Generate tracking number with collision prevention.
     */
    public static function generateTrackingNumber(): string
    {
        $maxAttempts = 10;
        $attempt = 0;
        
        do {
            $date = date('Ymd');
            $random = strtoupper(bin2hex(random_bytes(3)));
            $trackingNumber = sprintf('TRK-%s-%s', $date, $random);
            $attempt++;
        } while (self::where('tracking_number', $trackingNumber)->exists() && $attempt < $maxAttempts);

        return $trackingNumber;
    }

    /**
     * Get statistics for dashboard.
     */
    public static function getStatistics(): array
    {
        return Cache::remember('permit:statistics', now()->addMinutes(5), function () {
            return [
                'total' => self::count(),
                'pending' => self::whereIn('status', ['submitted', 'operator_review', 'operator_approved', 'kasi_review', 'kasi_approved', 'kabid_review'])->count(),
                'completed' => self::where('status', 'completed')->count(),
                'rejected' => self::where('status', 'like', '%_rejected')->count(),
                'today' => self::whereDate('created_at', today())->count(),
                'this_month' => self::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            ];
        });
    }

    // ========================================
    // ACCESSORS
    // ========================================

    /**
     * Get applicant email (from user or guest_email).
     */
    public function getApplicantEmailAttribute(): ?string
    {
        if ($this->is_guest) {
            return $this->guest_email;
        }
        return $this->user?->email;
    }

    /**
     * Get applicant NIK (from nik_pemohon or user NIK).
     */
    public function getApplicantNikAttribute(): ?string
    {
        return $this->nik_pemohon ?? ($this->is_guest ? $this->guest_nik : $this->user?->nik);
    }

    /**
     * Get status label in Indonesian.
     */
    public function getStatusLabelAttribute(): string
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
    public function getStatusColorAttribute(): string
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

    /**
     * Get progress percentage for UI.
     */
    public function getProgressPercentageAttribute(): int
    {
        return match($this->status) {
            'draft' => 0,
            'submitted' => 10,
            'operator_review' => 25,
            'operator_approved' => 40,
            'kasi_review' => 55,
            'kasi_approved' => 70,
            'kabid_review' => 85,
            'completed' => 100,
            'operator_rejected', 'kasi_rejected', 'kabid_rejected' => 100,
            default => 0,
        };
    }
}

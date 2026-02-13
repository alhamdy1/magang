<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'nik',
        'password',
        'role',
        'phone',
    ];

    /**
     * Get the permits created by the user.
     */
    public function permits()
    {
        return $this->hasMany(Permit::class);
    }

    /**
     * Get the permits claimed by this user (operator).
     */
    public function claimedPermits()
    {
        return $this->hasMany(Permit::class, 'claimed_by');
    }

    /**
     * Get the approval histories made by this user.
     */
    public function approvalHistories()
    {
        return $this->hasMany(ApprovalHistory::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an operator.
     */
    public function isOperator()
    {
        return $this->role === 'operator';
    }

    /**
     * Check if user is Kasi.
     */
    public function isKasi()
    {
        return $this->role === 'kasi';
    }

    /**
     * Check if user is Kabid.
     */
    public function isKabid()
    {
        return $this->role === 'kabid';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

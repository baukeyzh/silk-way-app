<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'password',
        'role',
        'approved',
    ];

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
            'approved' => 'boolean',
        ];
    }

    public function createdCargo(): HasMany
    {
        return $this->hasMany(Cargo::class, 'created_by');
    }

    public function pickedCargo(): HasMany
    {
        return $this->hasMany(Cargo::class, 'picked_by');
    }

    public function cargoApplications(): HasMany
    {
        return $this->hasMany(CargoApplication::class, 'driver_id');
    }

    public function approvedApplications(): HasMany
    {
        return $this->hasMany(CargoApplication::class, 'approved_by');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isWarehouseEmployee(): bool
    {
        return $this->role === 'warehouse_employee';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function scopePendingApproval($query)
    {
        return $query->where('approved', false);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    // Методы для водителей
    public function getPendingApplications()
    {
        return $this->cargoApplications()->pending()->with('cargo')->get();
    }

    public function getApprovedApplications()
    {
        return $this->cargoApplications()->approved()->with('cargo')->get();
    }

    public function getRejectedApplications()
    {
        return $this->cargoApplications()->rejected()->with('cargo')->get();
    }
}

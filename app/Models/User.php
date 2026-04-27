<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    // ── Role constants ────────────────────────────────────────────────────────
    public const ROLE_ADMIN              = 'admin';
    public const ROLE_WAREHOUSE_EMPLOYEE = 'warehouse_employee';
    public const ROLE_DRIVER             = 'driver';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
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

    public function driverDocuments(): HasMany
    {
        return $this->hasMany(\App\Models\DriverDocument::class);
    }

    public function fcmTokens(): HasMany
    {
        return $this->hasMany(FcmToken::class);
    }

    public function activeFcmTokens(): HasMany
    {
        // Firebase automatically invalidates stale tokens server-side;
        // we don't filter locally to avoid masking token churn in logs.
        return $this->hasMany(FcmToken::class);
    }

    /**
     * Document type codes that are required for this driver but not yet
     * admin-verified. Empty array means the driver is fully verified and
     * eligible to apply for cargo.
     *
     * Used as a precondition gate in CargoApplicationController::apply().
     *
     * @return array<string>
     */
    public function unverifiedRequiredDocumentTypes(): array
    {
        $required = array_diff(
            \App\Models\DriverDocument::DOCUMENT_TYPES,
            \App\Models\DriverDocument::OPTIONAL_TYPES,
        );

        $verifiedTypes = $this->driverDocuments()
            ->where('status', \App\Models\DriverDocument::STATUS_VERIFIED)
            ->whereIn('document_type', $required)
            ->pluck('document_type')
            ->all();

        return array_values(array_diff($required, $verifiedTypes));
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isWarehouseEmployee(): bool
    {
        return $this->role === self::ROLE_WAREHOUSE_EMPLOYEE;
    }

    public function isDriver(): bool
    {
        return $this->role === self::ROLE_DRIVER;
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

    public function getDeliveredApplications()
    {
        return $this->cargoApplications()->delivered()->with('cargo')->get();
    }

    /**
     * Returns true when the driver has an approved application whose linked cargo
     * is still in_progress — meaning they are actively hauling a load and must not
     * be allowed to bid on another cargo until CMR is confirmed (which transitions
     * both the application to delivered and the cargo to delivered).
     */
    public function hasActiveCargo(): bool
    {
        return $this->cargoApplications()
            ->approved()
            ->whereHas('cargo', fn (Builder $q) => $q->where('status', Cargo::STATUS_IN_PROGRESS))
            ->exists();
    }
}

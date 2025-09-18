<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    protected $table = 'cargo';

    protected $fillable = [
        'from_location',
        'from_location_rus',
        'from_location_kaz',
        'from_location_chn',
        'to_location',
        'to_location_rus',
        'to_location_kaz',
        'to_location_chn',
        'cargo_type',
        'cargo_type_rus',
        'cargo_type_kaz',
        'cargo_type_chn',
        'volume',
        'weight',
        'ready_date',
        'comment',
        'comment_rus',
        'comment_kaz',
        'comment_chn',
        'status',
        'created_by',
        'picked_by',
    ];

    protected $casts = [
        'ready_date' => 'datetime',
        'volume' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pickedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'picked_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(CargoApplication::class);
    }

    public function approvedApplication(): BelongsTo
    {
        return $this->belongsTo(CargoApplication::class, 'approved_application_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopePickedUp($query)
    {
        return $query->whereNotNull('picked_by');
    }

    public function hasPendingApplications(): bool
    {
        return $this->applications()->pending()->exists();
    }

    public function hasApprovedApplication(): bool
    {
        return $this->applications()->approved()->exists();
    }

    public function getApprovedApplication()
    {
        return $this->applications()->approved()->first();
    }

    public function getPendingApplications()
    {
        return $this->applications()->pending()->with('driver')->get();
    }

    /**
     * Получить локализованное значение для поля from_location
     */
    public function getLocalizedFromLocationAttribute(): string
    {
        $locale = app()->getLocale();
        $field = "from_location_{$locale}";
        
        return $this->$field ?: $this->from_location;
    }

    /**
     * Получить локализованное значение для поля to_location
     */
    public function getLocalizedToLocationAttribute(): string
    {
        $locale = app()->getLocale();
        $field = "to_location_{$locale}";
        
        return $this->$field ?: $this->to_location;
    }

    /**
     * Получить локализованное значение для поля cargo_type
     */
    public function getLocalizedCargoTypeAttribute(): string
    {
        $locale = app()->getLocale();
        $field = "cargo_type_{$locale}";
        
        return $this->$field ?: $this->cargo_type;
    }

    /**
     * Получить локализованное значение для поля comment
     */
    public function getLocalizedCommentAttribute(): string
    {
        $locale = app()->getLocale();
        $field = "comment_{$locale}";
        
        return $this->$field ?: $this->comment;
    }

    /**
     * Сохранить локализованные поля в зависимости от текущего языка
     */
    public function saveLocalizedFields(array $data): void
    {
        $locale = app()->getLocale();
        
        if (isset($data['from_location'])) {
            $this->{"from_location_{$locale}"} = $data['from_location'];
        }
        
        if (isset($data['to_location'])) {
            $this->{"to_location_{$locale}"} = $data['to_location'];
        }
        
        if (isset($data['cargo_type'])) {
            $this->{"cargo_type_{$locale}"} = $data['cargo_type'];
        }
        
        if (isset($data['comment'])) {
            $this->{"comment_{$locale}"} = $data['comment'];
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Warehouse;

class Cargo extends Model
{
    // ── Status constants ──────────────────────────────────────────────────────
    public const STATUS_AVAILABLE   = 'available';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_DELIVERED   = 'delivered';

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
        'price_usd',
        'ready_date',
        'comment',
        'comment_rus',
        'comment_kaz',
        'comment_chn',
        'status',
        'created_by',
        'picked_by',
        'picked_at',
        'from_warehouse_id',
        'to_warehouse_id',
    ];

    protected $casts = [
        'ready_date' => 'datetime',
        'picked_at' => 'datetime',
        'volume' => 'decimal:2',
        'weight' => 'decimal:2',
        'price_usd' => 'decimal:2',
    ];

    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

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
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    /**
     * Returns cargo that has ever been picked up (picked_by is set).
     * NOT equivalent to "currently in progress" — use scopeInProgress() for that.
     * This scope is useful for "has any driver ever been assigned" queries.
     */
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
    private function localeSuffix(): string
    {
        return ['ru' => 'rus', 'kz' => 'kaz', 'cn' => 'chn'][app()->getLocale()] ?? 'rus';
    }

    public function getLocalizedFromLocationAttribute(): string
    {
        $field = "from_location_{$this->localeSuffix()}";
        return $this->$field ?: $this->from_location;
    }

    /**
     * Получить локализованное значение для поля to_location
     */
    public function getLocalizedToLocationAttribute(): string
    {
        $field = "to_location_{$this->localeSuffix()}";
        return $this->$field ?: $this->to_location;
    }

    /**
     * Получить локализованное значение для поля cargo_type
     */
    public function getLocalizedCargoTypeAttribute(): string
    {
        $field = "cargo_type_{$this->localeSuffix()}";
        return $this->$field ?: $this->cargo_type;
    }

    /**
     * Получить локализованное значение для поля comment
     */
    public function getLocalizedCommentAttribute(): ?string
    {
        $field = "comment_{$this->localeSuffix()}";
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

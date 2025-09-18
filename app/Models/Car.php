<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    protected $fillable = [
        'user_id',
        'brand',
        'brand_rus',
        'brand_kaz',
        'brand_chn',
        'model',
        'model_rus',
        'model_kaz',
        'model_chn',
        'license_plate',
        'max_weight',
        'trailer_length',
        'trailer_width',
        'trailer_height',
        'trailer_volume',
        'trailer_type',
        'trailer_type_rus',
        'trailer_type_kaz',
        'trailer_type_chn',
        'vehicle_document',
        'is_active',
    ];

    protected $casts = [
        'max_weight' => 'decimal:2',
        'trailer_length' => 'decimal:2',
        'trailer_width' => 'decimal:2',
        'trailer_height' => 'decimal:2',
        'trailer_volume' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Типы прицепов
    const TRAILER_TYPE_TRAL = 'tral';
    const TRAILER_TYPE_REFRIGERATOR = 'refrigerator';
    const TRAILER_TYPE_TENT = 'tent';

    public static function getTrailerTypes(): array
    {
        return [
            self::TRAILER_TYPE_TRAL => 'Трал',
            self::TRAILER_TYPE_REFRIGERATOR => 'Рефрижератор',
            self::TRAILER_TYPE_TENT => 'Тент',
        ];
    }

    // Отношение к пользователю (водителю)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Получить полное название машины
    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model} ({$this->license_plate})";
    }

    // Получить габариты прицепа в текстовом виде
    public function getTrailerDimensionsAttribute(): string
    {
        return "{$this->trailer_length}м × {$this->trailer_width}м × {$this->trailer_height}м";
    }

    /**
     * Получить локализованное значение для поля brand
     */
    public function getLocalizedBrandAttribute(): string
    {
        $locale = app()->getLocale();
        $field = "brand_{$locale}";
        
        return $this->$field ?: $this->brand;
    }

    /**
     * Получить локализованное значение для поля model
     */
    public function getLocalizedModelAttribute(): string
    {
        $locale = app()->getLocale();
        $field = "model_{$locale}";
        
        return $this->$field ?: $this->model;
    }

    /**
     * Получить локализованное значение для поля trailer_type
     */
    public function getLocalizedTrailerTypeAttribute(): string
    {
        $locale = app()->getLocale();
        $field = "trailer_type_{$locale}";
        
        return $this->$field ?: $this->trailer_type;
    }

    /**
     * Сохранить локализованные поля в зависимости от текущего языка
     */
    public function saveLocalizedFields(array $data): void
    {
        $locale = app()->getLocale();
        
        if (isset($data['brand'])) {
            $this->{"brand_{$locale}"} = $data['brand'];
        }
        
        if (isset($data['model'])) {
            $this->{"model_{$locale}"} = $data['model'];
        }
        
        if (isset($data['trailer_type'])) {
            $this->{"trailer_type_{$locale}"} = $data['trailer_type'];
        }
    }
}

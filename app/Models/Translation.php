<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'rus',
        'kaz',
        'chn',
        'group',
        'description'
    ];

    /**
     * Получить перевод для текущего языка
     */
    public function getTranslation(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        
        return match($locale) {
            'rus' => $this->rus,
            'kaz' => $this->kaz,
            'chn' => $this->chn,
            default => $this->rus ?: $this->kaz ?: $this->chn ?: $this->key
        };
    }

    /**
     * Получить перевод для конкретного языка
     */
    public function getForLocale(string $locale): string
    {
        return $this->getTranslation($locale);
    }

    /**
     * Проверить, есть ли перевод для конкретного языка
     */
    public function hasLocale(string $locale): bool
    {
        return !empty($this->getTranslation($locale));
    }

    /**
     * Получить все переводы в виде массива
     */
    public function getAllTranslations(): array
    {
        return [
            'rus' => $this->rus,
            'kaz' => $this->kaz,
            'chn' => $this->chn,
        ];
    }

    /**
     * Scope для фильтрации по группе
     */
    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope для поиска по ключу
     */
    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }
}

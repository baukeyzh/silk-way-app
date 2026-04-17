<?php

namespace App\Services;

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;

class LocalizationService
{
    private const CACHE_KEY = 'translations';
    private const CACHE_TTL = 3600; // 1 час

    /**
     * Получить перевод по ключу
     */
    public function get(string $key, ?string $locale = null): string
    {
        $locale = $locale ?: App::getLocale();
        $translations = $this->getCachedTranslations();

        if (!isset($translations[$key])) {
            return $key;
        }
        
        return $translations[$key][$locale] ?? $translations[$key]['ru'] ?? $key;
    }

    /**
     * Получить перевод по ключу с параметрами
     */
    public function getWithParams(string $key, array $params = [], ?string $locale = null): string
    {
        $translation = $this->get($key, $locale);
        
        foreach ($params as $param => $value) {
            $translation = str_replace(":{$param}", $value, $translation);
        }
        
        return $translation;
    }

    /**
     * Получить все переводы для конкретного языка
     */
    public function getAllForLocale(?string $locale = null): array
    {
        $locale = $locale ?: App::getLocale();
        $translations = $this->getCachedTranslations();
        $result = [];
        
        foreach ($translations as $key => $values) {
            $result[$key] = $values[$locale] ?? $values['ru'] ?? $key;
        }
        
        return $result;
    }

    /**
     * Получить переводы по группе
     */
    public function getByGroup(string $group, ?string $locale = null): array
    {
        $locale = $locale ?: App::getLocale();
        $translations = $this->getCachedTranslations();
        $result = [];
        
        foreach ($translations as $key => $values) {
            if (($values['group'] ?? 'general') === $group) {
                $result[$key] = $values[$locale] ?? $values['ru'] ?? $key;
            }
        }
        
        return $result;
    }

    /**
     * Получить кэшированные переводы
     */
    private function getCachedTranslations(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Translation::all()->keyBy('key')->map(function ($translation) {
                return [
                    'ru' => $translation->ru,
                    'kz' => $translation->kz,
                    'cn' => $translation->cn,
                    'group' => $translation->group,
                    'description' => $translation->description,
                ];
            })->toArray();
        });
    }

    /**
     * Очистить кэш переводов
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Обновить перевод и очистить кэш
     */
    public function updateTranslation(string $key, array $data): bool
    {
        $translation = Translation::where('key', $key)->first();
        
        if (!$translation) {
            return false;
        }
        
        $updated = $translation->update($data);
        
        if ($updated) {
            $this->clearCache();
        }
        
        return $updated;
    }

    /**
     * Создать новый перевод
     */
    public function createTranslation(array $data): Translation
    {
        $translation = Translation::create($data);
        $this->clearCache();
        return $translation;
    }

    /**
     * Получить доступные языки
     */
    public function getAvailableLocales(): array
    {
        return [
            'ru' => 'Русский',
            'kz' => 'Қазақша',
            'cn' => '中文'
        ];
    }

    /**
     * Проверить, поддерживается ли язык
     */
    public function isLocaleSupported(string $locale): bool
    {
        return in_array($locale, array_keys($this->getAvailableLocales()));
    }
}

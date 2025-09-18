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
        
        // Отладочная информация
        \Log::info('LocalizationService::get', [
            'key' => $key,
            'locale' => $locale,
            'translations_keys' => array_keys($translations),
            'found_key' => isset($translations[$key]),
            'translation_data' => $translations[$key] ?? 'NOT_FOUND'
        ]);
        
        if (!isset($translations[$key])) {
            return $key;
        }
        
        return $translations[$key][$locale] ?? $translations[$key]['rus'] ?? $key;
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
            $result[$key] = $values[$locale] ?? $values['rus'] ?? $key;
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
                $result[$key] = $values[$locale] ?? $values['rus'] ?? $key;
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
            $translations = Translation::all()->keyBy('key')->map(function ($translation) {
                return [
                    'rus' => $translation->rus,
                    'kaz' => $translation->kaz,
                    'chn' => $translation->chn,
                    'group' => $translation->group,
                ];
            })->toArray();
            
            // Отладочная информация
            \Log::info('LocalizationService::getCachedTranslations', [
                'total_translations' => count($translations),
                'sample_keys' => array_slice(array_keys($translations), 0, 5),
                'cars_keys' => array_filter(array_keys($translations), function($key) { return str_starts_with($key, 'cars.'); })
            ]);
            
            return $translations;
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
            'rus' => 'Русский',
            'kaz' => 'Қазақша',
            'chn' => '中文'
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

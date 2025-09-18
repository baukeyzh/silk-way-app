<?php

namespace App\Helpers;

use App\Services\LocalizationService;

class LocalizationHelper
{
    private static ?LocalizationService $service = null;

    /**
     * Получить экземпляр сервиса локализации
     */
    private static function getService(): LocalizationService
    {
        if (self::$service === null) {
            self::$service = app(LocalizationService::class);
        }
        
        return self::$service;
    }

    /**
     * Получить перевод по ключу
     */
    public static function t(string $key, array $params = [], ?string $locale = null): string
    {
        return self::getService()->getWithParams($key, $params, $locale);
    }

    /**
     * Получить перевод по ключу (короткий синтаксис)
     */
    public static function __(string $key, array $params = [], ?string $locale = null): string
    {
        return self::t($key, $params, $locale);
    }

    /**
     * Получить перевод для текущего языка
     */
    public static function current(string $key, array $params = []): string
    {
        return self::t($key, $params);
    }

    /**
     * Получить перевод для русского языка
     */
    public static function rus(string $key, array $params = []): string
    {
        return self::t($key, $params, 'rus');
    }

    /**
     * Получить перевод для казахского языка
     */
    public static function kaz(string $key, array $params = []): string
    {
        return self::t($key, $params, 'kaz');
    }

    /**
     * Получить перевод для китайского языка
     */
    public static function chn(string $key, array $params = []): string
    {
        return self::t($key, $params, 'chn');
    }

    /**
     * Получить текущий язык
     */
    public static function getCurrentLocale(): string
    {
        return app()->getLocale();
    }

    /**
     * Получить название текущего языка
     */
    public static function getCurrentLocaleName(): string
    {
        $locales = [
            'rus' => 'Русский',
            'kaz' => 'Қазақша',
            'chn' => '中文'
        ];
        
        return $locales[app()->getLocale()] ?? 'Русский';
    }

    /**
     * Получить все доступные языки
     */
    public static function getAvailableLocales(): array
    {
        return self::getService()->getAvailableLocales();
    }

    /**
     * Проверить, является ли текущий язык русским
     */
    public static function isRussian(): bool
    {
        return app()->getLocale() === 'rus';
    }

    /**
     * Проверить, является ли текущий язык казахским
     */
    public static function isKazakh(): bool
    {
        return app()->getLocale() === 'kaz';
    }

    /**
     * Проверить, является ли текущий язык китайским
     */
    public static function isChinese(): bool
    {
        return app()->getLocale() === 'chn';
    }

    /**
     * Получить HTML для переключения языков
     */
    public static function getLanguageSwitcher(): string
    {
        $currentLocale = app()->getLocale();
        $locales = self::getAvailableLocales();
        $html = '<div class="language-switcher">';
        
        foreach ($locales as $code => $name) {
            $active = $code === $currentLocale ? ' active' : '';
            $url = request()->fullUrlWithQuery(['lang' => $code]);
            
            $html .= "<a href=\"{$url}\" class=\"lang-link{$active}\">{$name}</a>";
        }
        
        $html .= '</div>';
        
        return $html;
    }
}

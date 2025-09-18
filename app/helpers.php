<?php

use App\Helpers\LocalizationHelper;

if (!function_exists('translate')) {
    /**
     * Получить перевод по ключу
     */
    function translate(string $key, array $params = [], ?string $locale = null): string
    {
        return LocalizationHelper::t($key, $params, $locale);
    }
}

if (!function_exists('t')) {
    /**
     * Получить перевод по ключу (короткий синтаксис)
     */
    function t(string $key, array $params = [], ?string $locale = null): string
    {
        return LocalizationHelper::t($key, $params, $locale);
    }
}

if (!function_exists('current_locale')) {
    /**
     * Получить текущий язык
     */
    function current_locale(): string
    {
        return LocalizationHelper::getCurrentLocale();
    }
}

if (!function_exists('locale_name')) {
    /**
     * Получить название текущего языка
     */
    function locale_name(): string
    {
        return LocalizationHelper::getCurrentLocaleName();
    }
}

if (!function_exists('is_russian')) {
    /**
     * Проверить, является ли текущий язык русским
     */
    function is_russian(): bool
    {
        return LocalizationHelper::isRussian();
    }
}

if (!function_exists('is_kazakh')) {
    /**
     * Проверить, является ли текущий язык казахским
     */
    function is_kazakh(): bool
    {
        return LocalizationHelper::isKazakh();
    }
}

if (!function_exists('is_chinese')) {
    /**
     * Проверить, является ли текущий язык китайским
     */
    function is_chinese(): bool
    {
        return LocalizationHelper::isChinese();
    }
}

if (!function_exists('language_switcher')) {
    /**
     * Получить HTML для переключения языков
     */
    function language_switcher(): string
    {
        return LocalizationHelper::getLanguageSwitcher();
    }
}

if (!function_exists('user_role_name')) {
    /**
     * Получить переведенное название роли пользователя
     */
    function user_role_name($user = null): string
    {
        $user = $user ?: auth()->user();
        
        if (!$user) {
            return '';
        }
        
        if ($user->isAdmin()) {
            return LocalizationHelper::t('header.role_admin');
        } elseif ($user->isWarehouseEmployee()) {
            return LocalizationHelper::t('header.role_warehouse');
        } else {
            return LocalizationHelper::t('header.role_driver');
        }
    }
}

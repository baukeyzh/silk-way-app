<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Приоритет определения языка:
        // 1. Из URL параметра
        // 2. Из сессии
        // 3. Из заголовка Accept-Language
        // 4. По умолчанию русский
        
        $localeRaw = $request->get('lang') 
            ?: Session::get('locale') 
            ?: $this->getLocaleFromHeader($request) 
            ?: 'ru';

        // Нормализуем код языка: поддерживаем старые коды (rus/kaz/chn)
        $map = [
            'rus' => 'ru',
            'kaz' => 'kz',
            'chn' => 'cn',
            'ru' => 'ru',
            'kz' => 'kz',
            'cn' => 'cn',
        ];

        $locale = $map[$localeRaw] ?? 'ru';

        // Проверяем, поддерживается ли язык
        if (!in_array($locale, ['ru', 'kz', 'cn'])) {
            $locale = 'ru';
        }
        
        // Устанавливаем язык
        App::setLocale($locale);
        Session::put('locale', $locale);
        
        return $next($request);
    }

    /**
     * Получить язык из заголовка Accept-Language
     */
    private function getLocaleFromHeader(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');
        
        if (!$acceptLanguage) {
            return null;
        }
        
        // Парсим заголовок Accept-Language
        $languages = explode(',', $acceptLanguage);
        
        foreach ($languages as $language) {
            $locale = trim(explode(';', $language)[0]);
            
        // Маппинг языков
        $localeMap = [
            'ru' => 'ru',
            'ru-RU' => 'ru',
            'kk' => 'kz',
            'kk-KZ' => 'kz',
            'zh' => 'cn',
            'zh-CN' => 'cn',
            'zh-TW' => 'cn',
        ];
            
            if (isset($localeMap[$locale])) {
                return $localeMap[$locale];
            }
            
            // Проверяем префикс языка
            if (str_starts_with($locale, 'ru')) {
                return 'ru';
            }
            
            if (str_starts_with($locale, 'kk')) {
                return 'kz';
            }
            
            if (str_starts_with($locale, 'zh')) {
                return 'cn';
            }
        }
        
        return null;
    }
}

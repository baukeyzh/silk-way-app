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
        
        $locale = $request->get('lang') 
            ?: Session::get('locale') 
            ?: $this->getLocaleFromHeader($request) 
            ?: 'rus';
        
        // Проверяем, поддерживается ли язык
        if (!in_array($locale, ['rus', 'kaz', 'chn'])) {
            $locale = 'rus';
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
                'ru' => 'rus',
                'ru-RU' => 'rus',
                'kk' => 'kaz',
                'kk-KZ' => 'kaz',
                'zh' => 'chn',
                'zh-CN' => 'chn',
                'zh-TW' => 'chn',
            ];
            
            if (isset($localeMap[$locale])) {
                return $localeMap[$locale];
            }
            
            // Проверяем префикс языка
            if (str_starts_with($locale, 'ru')) {
                return 'rus';
            }
            
            if (str_starts_with($locale, 'kk')) {
                return 'kaz';
            }
            
            if (str_starts_with($locale, 'zh')) {
                return 'chn';
            }
        }
        
        return null;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use App\Services\LocalizationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TranslationController extends Controller
{
    public function __construct(
        private LocalizationService $localizationService
    ) {
        //
    }

    /**
     * Показать список всех переводов
     */
    public function index(Request $request): View
    {
        $query = Translation::query();
        
        // Поиск по ключу
        if ($request->has('search') && $request->search !== '') {
            $query->where('key', 'like', '%' . $request->search . '%');
        }
        
        $translations = $query->orderBy('key')->paginate(20);
        
        return view('admin.translations.index', compact('translations'));
    }

    /**
     * Показать форму создания перевода
     */
    public function create(): View
    {
        $locales = $this->localizationService->getAvailableLocales();
        
        return view('admin.translations.create', compact('locales'));
    }

    /**
     * Сохранить новый перевод
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:translations',
            'ru' => 'nullable|string|max:1000',
            'kz' => 'nullable|string|max:1000',
            'cn' => 'nullable|string|max:1000',
        ]);

        $translation = $this->localizationService->createTranslation($validated);

        return redirect()
            ->route('admin.translations.index')
            ->with('success', 'Перевод успешно создан');
    }

    /**
     * Показать форму редактирования перевода
     */
    public function edit(Translation $translation): View
    {
        $locales = $this->localizationService->getAvailableLocales();
        
        return view('admin.translations.edit', compact('translation', 'locales'));
    }

    /**
     * Обновить перевод
     */
    public function update(Request $request, Translation $translation): RedirectResponse
    {
        $validated = $request->validate([
            'ru' => 'nullable|string|max:1000',
            'kz' => 'nullable|string|max:1000',
            'cn' => 'nullable|string|max:1000',
        ]);

        $updated = $this->localizationService->updateTranslation($translation->key, $validated);

        if ($updated) {
            return redirect()
                ->route('admin.translations.index')
                ->with('success', 'Перевод успешно обновлен');
        }

        return back()->with('error', 'Ошибка при обновлении перевода');
    }

    /**
     * Показать перевод
     */
    public function show(Translation $translation): View
    {
        $locales = $this->localizationService->getAvailableLocales();
        
        return view('admin.translations.show', compact('translation', 'locales'));
    }

    /**
     * Очистить кэш переводов
     */
    public function clearCache(): RedirectResponse
    {
        $this->localizationService->clearCache();
        
        return redirect()
            ->route('admin.translations.index')
            ->with('success', 'Кэш переводов очищен');
    }

    /**
     * Экспорт переводов в JSON
     */
    public function export(): \Symfony\Component\HttpFoundation\Response
    {
        $translations = Translation::all()->keyBy('key')->map(function ($translation) {
            return [
                'ru' => $translation->ru,
                'kz' => $translation->kz,
                'cn' => $translation->cn,
            ];
        });

        $content = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        return response($content, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="translations.json"'
        ]);
    }
}

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
        
        // Фильтрация по группе
        if ($request->has('group') && $request->group !== '') {
            $query->where('group', $request->group);
        }
        
        // Поиск по ключу
        if ($request->has('search') && $request->search !== '') {
            $query->where('key', 'like', '%' . $request->search . '%');
        }
        
        $translations = $query->orderBy('group')->orderBy('key')->paginate(20);
        $groups = Translation::distinct()->pluck('group')->sort();
        
        return view('admin.translations.index', compact('translations', 'groups'));
    }

    /**
     * Показать форму создания перевода
     */
    public function create(): View
    {
        $groups = Translation::distinct()->pluck('group')->sort();
        $locales = $this->localizationService->getAvailableLocales();
        
        return view('admin.translations.create', compact('groups', 'locales'));
    }

    /**
     * Сохранить новый перевод
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:translations',
            'rus' => 'required|string|max:1000',
            'kaz' => 'required|string|max:1000',
            'chn' => 'required|string|max:1000',
            'group' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        // Обработка новой группы
        if ($validated['group'] === 'new_group' && $request->has('new_group_name')) {
            $validated['group'] = $request->input('new_group_name');
        }

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
        $groups = Translation::distinct()->pluck('group')->sort();
        $locales = $this->localizationService->getAvailableLocales();
        
        return view('admin.translations.edit', compact('translation', 'groups', 'locales'));
    }

    /**
     * Обновить перевод
     */
    public function update(Request $request, Translation $translation): RedirectResponse
    {
        $validated = $request->validate([
            'rus' => 'required|string|max:1000',
            'kaz' => 'required|string|max:1000',
            'chn' => 'required|string|max:1000',
            'group' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        // Обработка новой группы
        if ($validated['group'] === 'new_group' && $request->has('new_group_name')) {
            $validated['group'] = $request->input('new_group_name');
        }

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
        $translations = Translation::all()->groupBy('group')->map(function ($group) {
            return $group->keyBy('key')->map(function ($translation) {
                return [
                    'rus' => $translation->rus,
                    'kaz' => $translation->kaz,
                    'chn' => $translation->chn,
                ];
            });
        });

        $content = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        return response($content, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="translations.json"'
        ]);
    }
}

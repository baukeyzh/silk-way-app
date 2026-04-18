# Localize Blade View

Replace hardcoded strings in a Blade file with translation keys from the Silk Way i18n system.

**Target file:** $ARGUMENTS  
Format: path relative to `resources/views/` (e.g., `cargo/index.blade.php`)

## Context

- 3 languages: Russian (`ru`), Kazakh (`kz`), Chinese (`cn`)
- Helper function: `t('key')` — returns string for current locale
- Translations stored in `translations` DB table, managed via `app/Models/Translation.php`
- Admin UI at `/admin/translations` for managing keys
- Group keys by feature: `cargo.*`, `cars.*`, `auth.*`, `admin.*`, `common.*`

## Steps

1. **Read the target Blade file** to identify all hardcoded text strings

2. **Check existing translation keys** by reading `app/Models/Translation.php` and grepping:
   ```
   grep -r "Translation::" app/
   grep "t('" resources/views/ -r
   ```

3. **Map each hardcoded string** to an appropriate key:
   - Buttons/actions → `common.save`, `common.cancel`, `common.delete`, `common.edit`
   - Feature-specific → `{feature}.{description}` e.g., `cargo.from_location`
   - Headings → `{feature}.title`, `{feature}.list_title`
   - Messages → `{feature}.created_success`, `{feature}.not_found`

4. **Replace in Blade**: change `Текст` → `{{ t('key') }}` or `@lang('key')`

5. **Create missing translations** — for each new key, generate an insert:
   ```php
   Translation::create([
       'key' => 'feature.key_name',
       'ru' => 'Русский текст',
       'kz' => 'Қазақша мәтін',  // use best approximation if unknown
       'cn' => '中文文本',          // use best approximation if unknown
       'group' => 'feature',
       'description' => 'Label for X in Y view'
   ]);
   ```
   Collect all new keys into a single seeder or migration file.

6. **Run cache clear**: `php artisan cache:clear` to pick up new translations

7. **Report** a table of: old hardcoded text → new key → translations added/reused

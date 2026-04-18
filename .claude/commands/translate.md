# Add / Update Translation Key

Manage a translation key in the Silk Way multilingual system (ru/kz/cn).

**Key and values:** $ARGUMENTS
Format: `key.name "Russian text" "Казақша мәтін" "中文文本"`

## Context

- Translations live in the `translations` DB table (Model: `app/Models/Translation.php`)
- The service is `app/Services/LocalizationService.php` — it caches translations for 1 hour
- Helper: `app/Helpers/LocalizationHelper.php` — use `t('key')` or `__('key')` in Blade/PHP
- Groups organize keys (e.g., `cargo`, `auth`, `common`, `cars`, `admin`)

## Steps

1. **Parse arguments**: extract key, Russian text, Kazakh text, Chinese text from `$ARGUMENTS`

2. **Check if key exists**: search codebase with `grep -r "key.name" resources/views/` to understand context

3. **Create a database seeder or migration** to insert/update the translation:
   ```php
   Translation::updateOrCreate(
       ['key' => 'key.name'],
       [
           'ru' => 'Russian text',
           'kz' => 'Kazakh text', 
           'cn' => 'Chinese text',
           'group' => 'inferred_group',
           'description' => 'Brief description of where this is used'
       ]
   );
   ```

4. **If the key is hardcoded** in Blade files — replace hardcoded strings with `{{ t('key.name') }}` or `{{ __('key.name') }}`

5. **Clear translation cache**: remind user to run `php artisan cache:clear` or use the admin panel at `/admin/translations/cache/clear`

6. **Verify** the key appears correctly by checking `Translation::getTranslation('key.name')`

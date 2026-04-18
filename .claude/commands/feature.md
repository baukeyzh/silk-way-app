# Scaffold New Feature

Scaffold a complete new feature for the Silk Way Laravel app.

**Feature name:** $ARGUMENTS

## Steps

1. **Analyze** the existing codebase to understand patterns:
   - Check `app/Models/Cargo.php` for model conventions (multilingual fields, relationships, scopes)
   - Check `app/Http/Controllers/CargoController.php` for web controller patterns
   - Check `app/Http/Controllers/API/CargoController.php` for API controller patterns
   - Check `app/Http/Resources/` for Resource class patterns
   - Check `routes/web.php` and `routes/api.php` for route registration patterns

2. **Create Migration** in `database/migrations/` with:
   - Standard `id()`, `timestamps()`, `softDeletes()` if needed
   - Multilingual text columns use triple suffix: `{field}_rus`, `{field}_kaz`, `{field}_chn`
   - Foreign keys with proper `constrained()->cascadeOnDelete()`
   - Status enum columns where applicable

3. **Create Model** in `app/Models/` with:
   - `$fillable` array
   - Relationships (belongsTo, hasMany)
   - Multilingual accessor using `LocalizationHelper` (see `City::getLocalizedNameAttribute()`)
   - Status constants if applicable

4. **Create Web Controller** in `app/Http/Controllers/` with:
   - Full CRUD: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`
   - Role checks via `auth()->user()->role`
   - Blade views returning (use existing layouts/app.blade.php)
   - Flash messages on success/error

5. **Create API Controller** in `app/Http/Controllers/API/` with:
   - Full CRUD methods returning `response()->json()`
   - Swagger/OpenAPI annotations (see existing API controllers for format)
   - Use `auth('sanctum')->user()` for authenticated user
   - Return appropriate HTTP status codes

6. **Create API Resource** in `app/Http/Resources/` with:
   - `toArray()` method exposing safe fields
   - Multilingual fields resolved via `LocalizationHelper::t()`

7. **Register Routes**:
   - Web routes in `routes/web.php` inside appropriate middleware group
   - API routes in `routes/api.php` with `auth:sanctum` middleware
   - Apply role middleware where needed (e.g., `middleware('role:admin')`)

8. **Create Blade Views** in `resources/views/{feature}/`:
   - `index.blade.php` — list with pagination
   - `create.blade.php` — creation form
   - `edit.blade.php` — edit form
   - `show.blade.php` — detail view
   - Use `@extends('layouts.app')`, Tailwind CSS classes, translation via `__('key')` or `t('key')`

9. **Verify** everything compiles: `php artisan route:list | grep {feature}`

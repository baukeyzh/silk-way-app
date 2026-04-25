# Cities — lookup model

Standalone lookup table for the `from`/`to` columns on cargo. **Cargo store/update endpoints require `from_city_id` and `to_city_id`** as inputs; the controller resolves the city and writes the localized name fields onto `cargo` directly (denormalized).

## Schema

| column | type | notes |
|---|---|---|
| `id` | bigint PK | |
| `name` | string | canonical name (legacy column, unused in new code — uses localized) |
| `name_rus`, `name_kaz`, `name_chn` | string | localized names |
| `country` | string | groups cities by country in admin lists |

`$fillable = ['name', 'name_rus', 'name_kaz', 'name_chn', 'country']`.

## Why denormalize onto cargo

`cargo.from_location_*` and `cargo.to_location_*` columns store the city name **at write time**, not via FK. When admin renames a city, existing cargo rows keep the original spelling. Tradeoff: trades referential cleanliness for stable historical labels (a delivered cargo's address shouldn't shift if the city table is renamed).

The `from_city_id`/`to_city_id` only exist as **input** to the create/update controllers — they're not persisted on `cargo` rows. Only the localized name strings are persisted.

## Apply pattern (in CargoController::store)

```php
$fromCity = City::findOrFail($validated['from_city_id']);
$toCity   = City::findOrFail($validated['to_city_id']);
Cargo::create([
    'from_location'     => $fromCity->name,
    'from_location_rus' => $fromCity->name_rus,
    'from_location_kaz' => $fromCity->name_kaz,
    'from_location_chn' => $fromCity->name_chn,
    // ...
]);
```

## Routes (admin only)

- `GET /cities` — list (admin only via `role:admin`)
- `GET/POST /cities`, `GET/PUT/DELETE /cities/{city}` via `Route::resource('cities')->except(['show'])`
- No public API endpoint for cities currently — mobile clients pulling cities for a picker would need one added

## Mobile gap

The mobile cargo-creation form needs a city picker, but no public API endpoint exposes the cities list. If building a mobile create-cargo flow, add `GET /api/v1/public/cities` (returns localized name + country).

## Localization accessor

`$city->localized_name` returns the right field based on `app()->getLocale()`.

## See also

- [[claude__agent-memory__senior-laravel-dev__project_cars_domain]]
- [[claude__agent-memory__senior-laravel-dev__project_cargo_schema_extras]]

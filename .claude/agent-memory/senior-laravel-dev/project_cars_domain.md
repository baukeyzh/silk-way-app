# Cars (vehicles) — domain model

`cars` table holds driver vehicles. Required for cargo applications: `cargo_applications.car_id` is **NOT NULL** — apply flow blocks if driver has no car.

## Schema

| column | type | notes |
|---|---|---|
| `id` | bigint PK | |
| `user_id` | FK → users | the owning driver |
| `brand`, `model` | string | also has `_rus`/`_kaz`/`_chn` localized variants |
| `license_plate` | string, **unique** | gov plate |
| `max_weight` | decimal(8,2) | max payload, kg |
| `trailer_type` | string | enum-like: `tral`, `refrigerator`, `tent` (constants on `Car::getTrailerTypes()`) — also `_rus`/`_kaz`/`_chn` |
| `trailer_length`, `trailer_width`, `trailer_height` | decimal(8,2) | meters |
| `trailer_volume` | decimal(8,2) | computed from L×W×H in controllers, persisted |
| `is_active` | boolean | composite index `[user_id, is_active]` for `myCars()` query |

## Relations

- `Car::user()` → `User` (driver)
- `User::cars()` → `HasMany`
- `User::cars()->first()` is the default car attached to a `CargoApplication` when driver applies — no UI lets driver pick which car (yet)

## Removed (April 2026)

`vehicle_document` (PDF upload column) was added in `2026_03_25_193808_add_vehicle_document_to_cars_table.php` and **dropped** in `2026_04_25_091922_drop_vehicle_document_from_cars_table.php`.

**Why:** PDF storage on `cars` table didn't fit the actual document workflow (`DriverDocument` model is the canonical place for driver-side documents — passport, license, vehicle passport, etc.). Driver vehicle paperwork lives in `DriverDocument` with `document_type=vehicle_passport`, not on the `Car` row.

**How to apply:** Don't add per-car file uploads. Use `DriverDocument` slots if a per-vehicle document is needed (would require adding `car_id` FK to `driver_documents` — currently not designed for this).

## Apply-flow guard

`CargoApplicationController::apply()` (web + API) checks `$user->cars()->first()` before creating the application. If null:
- Web: `redirect()->route('cars.create')->with('warning', translate('applications.no_car_first'))`
- API: `422` with `{message: translate('applications.no_car_first')}`

This prevents the historical 500-error from null `car_id` insert.

## Routes

- `GET /cars` — list all (admin/WE only) or driver's own (redirected to `cars.my-cars`)
- `GET /cars/my-cars` — driver's own cars (`cars.my-cars`)
- `GET /cars/create`, `POST /cars` — driver creates a car
- `GET /cars/{car}/edit`, `PUT /cars/{car}` — driver edits own
- `DELETE /cars/{car}` — driver deletes own
- `POST /cars/{car}/toggle-status` — flips `is_active`
- API mirror under `/api/v1/cars` (Sanctum-gated)

## Multilingual fields

`brand_rus/kaz/chn`, `model_rus/kaz/chn`, `trailer_type_rus/kaz/chn` populated via `Car::saveLocalizedFields($validated)` based on current `app()->getLocale()`. The `localized_brand`, `localized_model`, `localized_trailer_type` accessors return the right column for the current locale.

## Resource shape (API)

`CarResource` exposes localized brand/model/trailer_type. Returns `driver` relation when loaded.

## See also

- [[claude__agent-memory__senior-laravel-dev__project_cargo_schema_extras]]
- [[claude__agent-memory__senior-laravel-dev__project_cmr_flow]]

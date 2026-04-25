# Cargo — schema additions and constraints (March-April 2026)

Cargo behavior columns and indexes that aren't covered in the main flow notes. These have side-effects that an agent building cargo features must know about.

## `cargo.picked_at` (timestamp, nullable)

**Migration:** `2026_03_25_125656_add_picked_at_to_cargo_table.php`

Set when WE/admin approves a driver's application. Used by `Cargo::scopePickedUp()` (`whereNotNull('picked_by')`) and indirectly drives any "in transit since X" timeline display.

Currently the implicit pair `picked_by + picked_at` mirrors `status='in_progress'` — they're updated together in `CargoApplicationController::approve()`. Don't update one without the other; treat them as a tuple.

## `cargo.price_usd` (decimal(8,2), nullable)

**Migration:** `2026_03_25_195746_add_price_usd_to_cargo_table.php`

The rate paid to the driver. **Privacy rule:** hidden from unauthenticated users.

Two enforcement points:
1. **`PublicCargoResource::toArray()`** — explicitly does NOT include `price_usd` field.
2. **`CargoResource::toArray()`** (auth'd) — uses `$this->when($request->user() !== null, fn() => $this->price_usd)` as a defensive guard even though the auth'd controller already requires a user.

When adding any new cargo resource (e.g. for a third-party API), check whether you need to apply the same `when` guard. **Default to hiding `price_usd` unless the consumer is explicitly trusted with it.**

Cast: `decimal:2`.

## Unique index `(cargo_id, driver_id)` on `cargo_applications`

**Migration:** `2026_04_19_181351_add_unique_cargo_driver_to_cargo_applications.php`

DB-level backstop preventing a driver from submitting two applications to the same cargo.

The application-layer guard (`->where('driver_id', $user->id)->exists()` in `apply()`) catches the common case. The unique index catches the race condition where two simultaneous POST /apply requests pass the `exists()` check before either commits.

Index name: `cargo_applications_cargo_id_driver_id_unique`. Constraint violation on insert returns `Integrity constraint violation: 1062 Duplicate entry`.

**How to surface this gracefully:** the apply controller wraps in `DB::transaction` + `lockForUpdate` so the second request blocks until the first commits, then sees the `exists()` and returns the friendly "уже подали заявку" message. The unique index only fires if both requests bypass the controller (e.g. raw SQL, or if the controller logic is changed).

## `cargo.weight`/`volume` precision fix

**Migration:** `2026_03_25_121620_fix_cargo_weight_volume_precision.php`

Original schema had `decimal(5,2)` capping weight/volume at 999.99. Migration widened to `decimal(11,2)` to allow loads up to 999_999_999.99 kg/m³. Validation rules in controllers updated to `max:99999999.99`.

Don't narrow these columns — multi-truck cargo records may exceed 1000 kg.

## See also

- [[claude__agent-memory__senior-laravel-dev__project_cars_domain]]
- [[claude__agent-memory__senior-laravel-dev__project_cities_domain]]
- [[claude__agent-memory__senior-laravel-dev__project_cmr_flow]]
- [[claude__agent-memory__senior-laravel-dev__project_status_audit]]

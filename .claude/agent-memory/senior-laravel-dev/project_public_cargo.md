---
name: Public Cargo Listing Architecture
description: How the public /cargo and /cargo/{id} pages are wired — critical for any future changes to cargo routing
type: project
---

`GET /cargo` and `GET /cargo/{cargo}` are served by `PublicCargoController` registered BEFORE the auth middleware group in `routes/web.php`. The existing `Route::resource('cargo', CargoController::class)` inside the auth group now uses `->except(['index', 'show'])` so it does not re-register those two route names.

`cargo.index` and `cargo.show` named routes both point to `PublicCargoController`.

**Why:** Guests need to browse cargo without auth. Auth users hitting the same URL get the full-featured view because `PublicCargoController` checks `auth()->check()` and delegates to private methods that replicate `CargoController` logic, returning `cargo.index` or `cargo.show` views. No redirect was used because redirect to `route('cargo.index')` would loop (same URL).

**How to apply:** Any future changes to cargo listing/show logic must be applied in BOTH `CargoController` (auth path, private methods in `PublicCargoController` replicate it) AND `PublicCargoController` (guest path). If CargoController's index/show logic changes, update `PublicCargoController::authenticatedIndex()` and `::authenticatedShow()` to match. Consider refactoring into a shared service if the duplication grows.

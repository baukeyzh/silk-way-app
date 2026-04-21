---
name: Public Cargo Routing Architecture
description: Route split between public /public/cargo (no auth) and auth-required /cargo; separate controller and resource for the public surface
type: project
---

Public cargo browse is served from `/api/v1/public/cargo` (and `/{cargo}`) via `PublicCargoController` using `PublicCargoResource`. No token required; middleware stack is `throttle:cargo-guest` only.

The authenticated list/show (`GET /api/v1/cargo`, `GET /api/v1/cargo/{cargo}`) live inside the `auth:sanctum + throttle:cargo-auth` group. The null-user branches in `CargoController::index` and `::show` were deleted — those methods now assume `$user !== null`.

**Why:** Separating access models by URL prefix (`/public/`) makes the access contract explicit for mobile consumers and Swagger. A dedicated `PublicCargoController` keeps the auth'd controller unaware of guest concerns (single-responsibility).

**How to apply:** When adding new public-facing API endpoints, place them under the `Route::prefix('public')->middleware('throttle:cargo-guest')` group in `routes/api.php`. Create a matching `Public*Resource` that explicitly omits sensitive fields rather than using `$this->when()`.

Key decisions:
- Controller: separate `PublicCargoController` (Option A) — different access models, different response shapes.
- `CargoResource` `$this->when()` guards retained as defensive belt-and-braces; annotated with `// defensive` comment.
- `status=` query param stripped from public endpoint (hardcoded to `Cargo::available()` scope, no override possible).
- No eager loading of `createdBy`/`pickedBy` in the public controller — avoids unnecessary JOINs.
- Swagger version bumped to 1.3.0; `PublicCargo` schema added; auth'd endpoints carry `security={{"sanctum":{}}}`.
- `/cargo/my` precedence: still registered first inside the auth group before the `/{cargo}` wildcard.
- Note: web routes (`routes/web.php`) and Blade views were NOT touched — they are a separate concern.

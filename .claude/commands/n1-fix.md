# Find and Fix N+1 Query Problems

Audit a controller or feature for N+1 Eloquent query issues and fix them.

**Target:** $ARGUMENTS  
Format: controller name, route prefix, or feature area (e.g., `CargoController`, `cargo`, `applications`)

## Context

- Models: User, Cargo, Car, CargoApplication, City, Translation
- Key relationships:
  - `Cargo` belongsTo `User` (created_by, picked_by) — always eager load both
  - `CargoApplication` belongsTo `Cargo`, `User` (driver), `Car`, `User` (approved_by)
  - `Car` belongsTo `User`
- Use `->with([...])` for eager loading on collections
- Use `->load([...])` on already-retrieved models

## Steps

1. **Read the target controller** (web and/or API version)

2. **Identify N+1 risks**: look for loops or Blade `@foreach` where inner calls access relationships:
   - `$cargo->creator->name` inside a loop without `->with('creator')`
   - `$application->cargo->from_location` without `->with('cargo')`
   - `$application->driver->name` without `->with('driver')`
   - `$car->user->name` without `->with('user')`

3. **Check corresponding Blade views** for relationship accesses not covered by eager loading

4. **Fix with eager loading**:
   ```php
   // Before
   $cargos = Cargo::all();
   
   // After
   $cargos = Cargo::with(['creator', 'picker'])->paginate(20);
   ```

5. **Fix nested relationships**:
   ```php
   $applications = CargoApplication::with([
       'cargo',
       'driver',
       'car',
       'approvedBy'
   ])->paginate(20);
   ```

6. **Add `$with` property** to Model if always needed:
   ```php
   protected $with = ['city']; // auto-loaded always
   ```
   Only do this if the relationship is needed in 90%+ of queries.

7. **Check API Resources** — Resources often access relationships; ensure they're covered by eager loads in the controller before passing to the Resource.

8. **Verify**: run `php artisan telescope:install` or use `DB::enableQueryLog()` temporarily to confirm query count drops.

9. **Report**: show before/after query count estimate per request.

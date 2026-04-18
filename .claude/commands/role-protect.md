# Add Role-Based Protection

Add role-based access control to routes or controllers in the Silk Way app.

**Target:** $ARGUMENTS  
Format: `route_prefix role` (e.g., `cargo admin`, `cars driver`, `applications admin,warehouse_employee`)

## Context

- 3 roles: `admin`, `warehouse_employee`, `driver`
- Middleware: `app/Http/Middleware/CheckRole.php` — registered as `role` in kernel
- Usage in routes: `->middleware(['auth', 'role:admin'])` or `->middleware(['auth', 'role:admin,warehouse_employee'])`
- Usage in controllers: `if (auth()->user()->role !== 'admin') abort(403);`
- `auth()->user()->approved` must also be true for non-admin users

## Steps

1. **Read the current route definitions** in `routes/web.php` and/or `routes/api.php` for the target prefix

2. **Read `app/Http/Middleware/CheckRole.php`** to understand how roles are checked

3. **Apply route-level middleware** (preferred approach):
   ```php
   // Single role
   Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
       Route::resource('cities', CityController::class);
   });
   
   // Multiple roles (comma-separated = any of these roles allowed)
   Route::prefix('applications')->middleware(['auth', 'role:admin,warehouse_employee'])->group(function () {
       Route::get('/', [ApplicationController::class, 'index']);
   });
   ```

4. **Add controller-level guard** for fine-grained checks within a controller:
   ```php
   public function destroy(Cargo $cargo): RedirectResponse
   {
       if (auth()->user()->role !== 'admin' && $cargo->created_by !== auth()->id()) {
           abort(403, 'Unauthorized');
       }
       // ...
   }
   ```

5. **For API routes**, add to the sanctum middleware group:
   ```php
   Route::middleware(['auth:sanctum', 'role:driver'])->group(function () {
       Route::post('/cars', [CarController::class, 'store']);
   });
   ```

6. **Check Blade views** — hide UI elements the user can't access:
   ```blade
   @if(auth()->user()->role === 'admin')
       <a href="{{ route('admin.users') }}">Admin Panel</a>
   @endif
   ```

7. **Verify all role combinations** are handled:
   - What can `admin` do? Everything
   - What can `warehouse_employee` do? View/manage cargo and applications, no cars
   - What can `driver` do? Manage own cars, apply for cargo, view own applications

8. **Test** by listing routes: `php artisan route:list --path={prefix}`

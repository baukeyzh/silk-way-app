# Add Swagger Documentation

Add or update OpenAPI/Swagger annotations for an API controller method.

**Target:** $ARGUMENTS  
Format: `ControllerName@method` or just `ControllerName` to document all methods

## Context

- This project uses `darkaonline/l5-swagger` ^11.0
- Swagger UI is at `/api/documentation`
- All API controllers are in `app/Http/Controllers/API/`
- Auth is via Laravel Sanctum (Bearer token) — tag secured routes with `@OA\SecurityScheme`
- Existing example: check `app/Http/Controllers/API/CargoController.php` for annotation style

## Steps

1. **Read the target controller** to understand what each method does, its parameters, and return shape

2. **Read an existing documented controller** (e.g., `CargoController`) to match annotation style

3. **Add/update annotations** using this pattern:
   ```php
   /**
    * @OA\Get(
    *     path="/api/resource",
    *     summary="Short description",
    *     description="Longer description if needed",
    *     tags={"ResourceTag"},
    *     security={{"sanctum":{}}},
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Success",
    *         @OA\JsonContent(ref="#/components/schemas/ResourceName")
    *     ),
    *     @OA\Response(response=401, description="Unauthenticated"),
    *     @OA\Response(response=403, description="Unauthorized"),
    *     @OA\Response(response=404, description="Not found")
    * )
    */
   ```

4. **Document request bodies** for POST/PUT using `@OA\RequestBody` with `@OA\JsonContent`

5. **Add schema** in the Resource class or Model if missing:
   ```php
   /**
    * @OA\Schema(
    *     schema="ResourceName",
    *     @OA\Property(property="id", type="integer"),
    *     ...
    * )
    */
   ```

6. **Regenerate docs**: run `php artisan l5-swagger:generate` and confirm no errors

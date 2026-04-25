<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Validation\Rule;

class CarController extends Controller
{
    /**
     * @OA\Get(
     *     path="/cars",
     *     tags={"Cars"},
     *     summary="Список всех активных машин",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", default=1)),
     *     @OA\Response(response=200, description="Список машин",
     *         @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Car")))
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=429, description="Слишком много запросов (120/мин)")
     * )
     */
    public function index(): ResourceCollection
    {
        $cars = Car::with('user')->where('is_active', true)->paginate(20);

        return CarResource::collection($cars);
    }

    /**
     * @OA\Get(
     *     path="/cars/my",
     *     tags={"Cars"},
     *     summary="Машины текущего водителя",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Список машин водителя",
     *         @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Car")))
     *     ),
     *     @OA\Response(response=401, description="Не авторизован")
     * )
     */
    public function myCars(Request $request): ResourceCollection
    {
        $cars = $request->user()->cars()->paginate(20);

        return CarResource::collection($cars);
    }

    /**
     * @OA\Get(
     *     path="/cars/{id}",
     *     tags={"Cars"},
     *     summary="Детали машины",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Детали машины",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/Car"))
     *     ),
     *     @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function show(Car $car): JsonResponse
    {
        $car->load('user');

        return response()->json(['data' => new CarResource($car)]);
    }

    /**
     * @OA\Post(
     *     path="/cars",
     *     tags={"Cars"},
     *     summary="Добавить машину",
     *     description="Только для водителей.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"brand","model","license_plate","max_weight","trailer_length","trailer_width","trailer_height","trailer_type"},
     *                 @OA\Property(property="brand",           type="string",  example="Volvo"),
     *                 @OA\Property(property="model",           type="string",  example="FH16"),
     *                 @OA\Property(property="license_plate",   type="string",  example="A123BC"),
     *                 @OA\Property(property="max_weight",      type="number",  example=20.0),
     *                 @OA\Property(property="trailer_length",  type="number",  example=13.6),
     *                 @OA\Property(property="trailer_width",   type="number",  example=2.4),
     *                 @OA\Property(property="trailer_height",  type="number",  example=2.7),
     *                 @OA\Property(property="trailer_type",    type="string",  enum={"tral","refrigerator","tent"})
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Машина добавлена",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/Car"))
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=403, description="Доступ только для водителей"),
     *     @OA\Response(response=422, description="Ошибка валидации"),
     *     @OA\Response(response=429, description="Слишком много запросов (120/мин)")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'brand'            => 'required|string|max:255',
            'model'            => 'required|string|max:255',
            'license_plate'    => 'required|string|max:20|unique:cars',
            'max_weight'       => 'required|numeric|min:0.1|max:100',
            'trailer_length'   => 'required|numeric|min:0.1|max:50',
            'trailer_width'    => 'required|numeric|min:0.1|max:10',
            'trailer_height'   => 'required|numeric|min:0.1|max:10',
            'trailer_type'     => ['required', Rule::in(array_keys(Car::getTrailerTypes()))],
        ]);

        $validated['trailer_volume']   = $validated['trailer_length'] * $validated['trailer_width'] * $validated['trailer_height'];
        $validated['trailer_type_rus'] = Car::getTrailerTypes()[$validated['trailer_type']];
        $validated['user_id']          = $request->user()->id;

        $car = Car::create($validated);
        $car->saveLocalizedFields($validated);

        return response()->json(['data' => new CarResource($car)], 201);
    }

    /**
     * @OA\Put(
     *     path="/cars/{id}",
     *     tags={"Cars"},
     *     summary="Обновить машину (form-data с _method=PUT)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"brand","model","license_plate","max_weight","trailer_length","trailer_width","trailer_height","trailer_type"},
     *                 @OA\Property(property="_method",         type="string",  example="PUT"),
     *                 @OA\Property(property="brand",           type="string"),
     *                 @OA\Property(property="model",           type="string"),
     *                 @OA\Property(property="license_plate",   type="string"),
     *                 @OA\Property(property="max_weight",      type="number"),
     *                 @OA\Property(property="trailer_length",  type="number"),
     *                 @OA\Property(property="trailer_width",   type="number"),
     *                 @OA\Property(property="trailer_height",  type="number"),
     *                 @OA\Property(property="trailer_type",    type="string", enum={"tral","refrigerator","tent"})
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Машина обновлена",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/Car"))
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=404, description="Не найдено"),
     *     @OA\Response(response=429, description="Слишком много запросов (120/мин)")
     * )
     */
    public function update(Request $request, Car $car): JsonResponse
    {
        if ($car->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Нет доступа к этой машине.'], 403);
        }

        $validated = $request->validate([
            'brand'            => 'required|string|max:255',
            'model'            => 'required|string|max:255',
            'license_plate'    => ['required', 'string', 'max:20', Rule::unique('cars')->ignore($car->id)],
            'max_weight'       => 'required|numeric|min:0.1|max:100',
            'trailer_length'   => 'required|numeric|min:0.1|max:50',
            'trailer_width'    => 'required|numeric|min:0.1|max:10',
            'trailer_height'   => 'required|numeric|min:0.1|max:10',
            'trailer_type'     => ['required', Rule::in(array_keys(Car::getTrailerTypes()))],
        ]);

        $validated['trailer_volume']   = $validated['trailer_length'] * $validated['trailer_width'] * $validated['trailer_height'];
        $validated['trailer_type_rus'] = Car::getTrailerTypes()[$validated['trailer_type']];

        $car->update($validated);
        $car->saveLocalizedFields($validated);

        return response()->json(['data' => new CarResource($car)]);
    }

    /**
     * @OA\Delete(
     *     path="/cars/{id}",
     *     tags={"Cars"},
     *     summary="Удалить машину",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Машина удалена",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Машина удалена."))
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=404, description="Не найдено"),
     *     @OA\Response(response=429, description="Слишком много запросов (120/мин)")
     * )
     */
    public function destroy(Request $request, Car $car): JsonResponse
    {
        if ($car->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Нет доступа к этой машине.'], 403);
        }

        $car->delete();

        return response()->json(['message' => 'Машина удалена.']);
    }

    /**
     * @OA\Post(
     *     path="/cars/{id}/toggle",
     *     tags={"Cars"},
     *     summary="Активировать / деактивировать машину",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Статус изменён",
     *         @OA\JsonContent(
     *             @OA\Property(property="message",   type="string"),
     *             @OA\Property(property="is_active", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function toggleStatus(Request $request, Car $car): JsonResponse
    {
        if ($car->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Нет доступа к этой машине.'], 403);
        }

        $car->update(['is_active' => !$car->is_active]);

        $status = $car->is_active ? 'активирована' : 'деактивирована';

        return response()->json([
            'message'   => "Машина {$status}.",
            'is_active' => $car->is_active,
        ]);
    }
}

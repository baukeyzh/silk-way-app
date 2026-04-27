<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CargoResource;
use App\Models\Cargo;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CargoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/cargo",
     *     tags={"Cargo"},
     *     summary="Список грузов (авторизованный)",
     *     description="Требует Sanctum-токен. Сотрудник склада видит свои грузы; все остальные — доступные. Поддерживает фильтрацию по статусу.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="search", in="query", description="Поиск по локации или типу", @OA\Schema(type="string")),
     *     @OA\Parameter(name="status", in="query", description="Фильтр по статусу", @OA\Schema(type="string", enum={"available","in_progress","delivered"})),
     *     @OA\Parameter(name="page",   in="query", description="Номер страницы", @OA\Schema(type="integer", default=1)),
     *     @OA\Response(
     *         response=200,
     *         description="Список грузов с полными данными (price_usd, created_by, picked_by включены)",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Cargo")),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page",    type="integer"),
     *                 @OA\Property(property="per_page",     type="integer"),
     *                 @OA\Property(property="total",        type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=429, description="Слишком много запросов (120/мин)")
     * )
     */
    public function index(Request $request): ResourceCollection
    {
        $user   = $request->user();
        $search = $request->query('search');
        $status = $request->query('status');

        if ($user->isWarehouseEmployee()) {
            $query = $user->createdCargo();
        } else {
            $query = Cargo::available();
        }

        if ($search) {
            $suffix = ['ru' => 'rus', 'kz' => 'kaz', 'cn' => 'chn'][app()->getLocale()] ?? 'rus';
            $query->where(function ($q) use ($search, $suffix) {
                $q->where("from_location_{$suffix}", 'like', "%{$search}%")
                  ->orWhere("to_location_{$suffix}", 'like', "%{$search}%")
                  ->orWhere('cargo_type', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $cargo = $query->latest()->paginate(15);

        return CargoResource::collection($cargo);
    }

    /**
     * @OA\Get(
     *     path="/cargo/{id}",
     *     tags={"Cargo"},
     *     summary="Детали груза (авторизованный)",
     *     description="Требует Sanctum-токен. Сотрудник склада может видеть только свои грузы. Возвращает полные данные, включая price_usd, created_by, picked_by.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Детали груза",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/Cargo"))
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=404, description="Не найдено"),
     *     @OA\Response(response=429, description="Слишком много запросов (120/мин)")
     * )
     */
    public function show(Request $request, Cargo $cargo): JsonResponse
    {
        $user = $request->user();

        if ($user->isWarehouseEmployee() && $cargo->created_by !== $user->id) {
            return response()->json(['message' => 'Нет доступа к этому грузу.'], 403);
        }

        $cargo->load(['createdBy', 'pickedBy', 'approvedApplication']);

        $myApplication = $user->isDriver()
            ? $cargo->applications()->where('driver_id', $user->id)->with('car')->first()
            : null;
        $cargo->setRelation('myApplication', $myApplication);

        return response()->json(['data' => new CargoResource($cargo)]);
    }

    /**
     * @OA\Post(
     *     path="/cargo",
     *     tags={"Cargo"},
     *     summary="Создать груз",
     *     description="Только для сотрудников склада и администраторов.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"from_city_id","to_city_id","cargo_type","volume","weight","ready_date"},
     *             @OA\Property(property="from_city_id", type="integer", example=1),
     *             @OA\Property(property="to_city_id",   type="integer", example=2),
     *             @OA\Property(property="cargo_type",   type="string",  example="Электроника"),
     *             @OA\Property(property="volume",       type="number",  example=20.5),
     *             @OA\Property(property="weight",       type="number",  example=5.0),
     *             @OA\Property(property="price_usd",    type="number",  nullable=true, example=1500),
     *             @OA\Property(property="ready_date",   type="string",  format="date", example="2026-05-01"),
     *             @OA\Property(property="comment",      type="string",  nullable=true, example="Хрупкий груз")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Груз создан",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/Cargo"))
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=422, description="Ошибка валидации"),
     *     @OA\Response(response=429, description="Слишком много запросов (120/мин)")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from_city_id' => 'required|exists:cities,id',
            'to_city_id'   => 'required|exists:cities,id',
            'cargo_type'   => 'required|string|max:255',
            'volume'       => 'required|numeric|min:0|max:99999999.99',
            'weight'       => 'required|numeric|min:0|max:99999999.99',
            'price_usd'    => 'nullable|numeric|min:0',
            'ready_date'   => 'required|date',
            'comment'      => 'nullable|string',
        ]);

        $fromCity = City::findOrFail($validated['from_city_id']);
        $toCity   = City::findOrFail($validated['to_city_id']);

        $cargo = Cargo::create([
            'from_location'     => $fromCity->name,
            'from_location_rus' => $fromCity->name_rus,
            'from_location_kaz' => $fromCity->name_kaz,
            'from_location_chn' => $fromCity->name_chn,
            'to_location'       => $toCity->name,
            'to_location_rus'   => $toCity->name_rus,
            'to_location_kaz'   => $toCity->name_kaz,
            'to_location_chn'   => $toCity->name_chn,
            'cargo_type'        => $validated['cargo_type'],
            'volume'            => $validated['volume'],
            'weight'            => $validated['weight'],
            'price_usd'         => $validated['price_usd'] ?? null,
            'ready_date'        => $validated['ready_date'],
            'comment'           => $validated['comment'] ?? null,
            'created_by'        => $request->user()->id,
            'status'            => Cargo::STATUS_AVAILABLE,
        ]);

        return response()->json(['data' => new CargoResource($cargo)], 201);
    }

    /**
     * @OA\Put(
     *     path="/cargo/{id}",
     *     tags={"Cargo"},
     *     summary="Обновить груз",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"from_city_id","to_city_id","cargo_type","volume","weight","ready_date"},
     *             @OA\Property(property="from_city_id", type="integer"),
     *             @OA\Property(property="to_city_id",   type="integer"),
     *             @OA\Property(property="cargo_type",   type="string"),
     *             @OA\Property(property="volume",       type="number"),
     *             @OA\Property(property="weight",       type="number"),
     *             @OA\Property(property="price_usd",    type="number", nullable=true),
     *             @OA\Property(property="ready_date",   type="string", format="date"),
     *             @OA\Property(property="comment",      type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Груз обновлён",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/Cargo"))
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=404, description="Не найдено"),
     *     @OA\Response(response=429, description="Слишком много запросов (120/мин)")
     * )
     */
    public function update(Request $request, Cargo $cargo): JsonResponse
    {
        $user = $request->user();

        if (!$user->isAdmin() && (!$user->isWarehouseEmployee() || $cargo->created_by !== $user->id)) {
            return response()->json(['message' => 'Нет доступа к редактированию этого груза.'], 403);
        }

        $validated = $request->validate([
            'from_city_id' => 'required|exists:cities,id',
            'to_city_id'   => 'required|exists:cities,id',
            'cargo_type'   => 'required|string|max:255',
            'volume'       => 'required|numeric|min:0',
            'weight'       => 'required|numeric|min:0',
            'price_usd'    => 'nullable|numeric|min:0',
            'ready_date'   => 'required|date',
            'comment'      => 'nullable|string',
        ]);

        $fromCity = City::findOrFail($validated['from_city_id']);
        $toCity   = City::findOrFail($validated['to_city_id']);

        $cargo->update([
            'from_location'     => $fromCity->name,
            'from_location_rus' => $fromCity->name_rus,
            'from_location_kaz' => $fromCity->name_kaz,
            'from_location_chn' => $fromCity->name_chn,
            'to_location'       => $toCity->name,
            'to_location_rus'   => $toCity->name_rus,
            'to_location_kaz'   => $toCity->name_kaz,
            'to_location_chn'   => $toCity->name_chn,
            'cargo_type'        => $validated['cargo_type'],
            'volume'            => $validated['volume'],
            'weight'            => $validated['weight'],
            'price_usd'         => $validated['price_usd'] ?? null,
            'ready_date'        => $validated['ready_date'],
            'comment'           => $validated['comment'] ?? null,
        ]);

        return response()->json(['data' => new CargoResource($cargo)]);
    }

    /**
     * @OA\Delete(
     *     path="/cargo/{id}",
     *     tags={"Cargo"},
     *     summary="Удалить груз",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Груз удалён",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Груз удалён."))
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=404, description="Не найдено"),
     *     @OA\Response(response=429, description="Слишком много запросов (120/мин)")
     * )
     */
    public function destroy(Request $request, Cargo $cargo): JsonResponse
    {
        $user = $request->user();

        if (!$user->isWarehouseEmployee() || $cargo->created_by !== $user->id) {
            return response()->json(['message' => 'Нет доступа к удалению этого груза.'], 403);
        }

        $cargo->delete();

        return response()->json(['message' => 'Груз удалён.']);
    }
}

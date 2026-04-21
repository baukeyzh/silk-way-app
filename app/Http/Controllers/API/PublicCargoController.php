<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicCargoResource;
use App\Models\Cargo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PublicCargoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/public/cargo",
     *     tags={"Public Cargo"},
     *     summary="Публичный список грузов",
     *     description="Доступно без авторизации. Возвращает только грузы со статусом available. Цена, создатель и исполнитель не возвращаются.",
     *     @OA\Parameter(name="search", in="query", description="Поиск по локации или типу", @OA\Schema(type="string")),
     *     @OA\Parameter(name="page",   in="query", description="Номер страницы", @OA\Schema(type="integer", default=1)),
     *     @OA\Response(
     *         response=200,
     *         description="Список доступных грузов",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PublicCargo")),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page",    type="integer"),
     *                 @OA\Property(property="per_page",     type="integer"),
     *                 @OA\Property(property="total",        type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=429, description="Слишком много запросов (30/мин)")
     * )
     */
    public function index(Request $request): ResourceCollection
    {
        $query  = Cargo::available();
        $search = $request->query('search');

        if ($search) {
            $suffix = ['ru' => 'rus', 'kz' => 'kaz', 'cn' => 'chn'][app()->getLocale()] ?? 'rus';
            $query->where(function ($q) use ($search, $suffix) {
                $q->where("from_location_{$suffix}", 'like', "%{$search}%")
                  ->orWhere("to_location_{$suffix}", 'like', "%{$search}%")
                  ->orWhere('cargo_type', 'like', "%{$search}%");
            });
        }

        $cargo = $query->latest()->paginate(15);

        return PublicCargoResource::collection($cargo);
    }

    /**
     * @OA\Get(
     *     path="/public/cargo/{id}",
     *     tags={"Public Cargo"},
     *     summary="Публичные детали груза",
     *     description="Доступно без авторизации. Возвращает только грузы со статусом available. Цена, создатель и исполнитель не возвращаются.",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Детали груза",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/PublicCargo"))
     *     ),
     *     @OA\Response(response=404, description="Не найдено или груз недоступен"),
     *     @OA\Response(response=429, description="Слишком много запросов (30/мин)")
     * )
     */
    public function show(Cargo $cargo): JsonResponse
    {
        if ($cargo->status !== Cargo::STATUS_AVAILABLE) {
            abort(404);
        }

        return response()->json(['data' => new PublicCargoResource($cargo)]);
    }
}

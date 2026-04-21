<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PublicCargo",
 *     description="Публичное представление груза. Поля price_usd, created_by, picked_by отсутствуют намеренно.",
 *     @OA\Property(property="id",         type="integer", example=1),
 *     @OA\Property(property="from",       type="string",  example="Алматы"),
 *     @OA\Property(property="to",         type="string",  example="Пекин"),
 *     @OA\Property(property="cargo_type", type="string",  example="Электроника"),
 *     @OA\Property(property="volume",     type="number",  example=20.5),
 *     @OA\Property(property="weight",     type="number",  example=5.0),
 *     @OA\Property(property="ready_date", type="string",  format="date",      example="2026-05-01"),
 *     @OA\Property(property="comment",    type="string",  nullable=true,      example="Хрупкий груз"),
 *     @OA\Property(property="status",     type="string",  enum={"available"},  example="available"),
 *     @OA\Property(property="created_at", type="string",  format="date-time", example="2026-04-18T12:00:00.000000Z")
 * )
 */
class PublicCargoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'from'       => $this->localized_from_location,
            'to'         => $this->localized_to_location,
            'cargo_type' => $this->localized_cargo_type,
            'volume'     => $this->volume,
            'weight'     => $this->weight,
            'ready_date' => $this->ready_date?->toDateString(),
            'comment'    => $this->localized_comment,
            'status'     => $this->status,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}

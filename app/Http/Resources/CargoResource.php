<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Cargo",
 *     @OA\Property(property="id",         type="integer", example=1),
 *     @OA\Property(property="from",       type="string",  example="Алматы"),
 *     @OA\Property(property="to",         type="string",  example="Пекин"),
 *     @OA\Property(property="cargo_type", type="string",  example="Электроника"),
 *     @OA\Property(property="volume",     type="number",  example=20.5),
 *     @OA\Property(property="weight",     type="number",  example=5.0),
 *     @OA\Property(property="price_usd",  type="number",  example=1500.00),
 *     @OA\Property(property="ready_date", type="string",  format="date", example="2026-05-01"),
 *     @OA\Property(property="comment",    type="string",  example="Хрупкий груз"),
 *     @OA\Property(property="status",     type="string",  enum={"available","in_progress","delivered"}),
 *     @OA\Property(property="created_at", type="string",  format="date-time")
 * )
 */
class CargoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'from'         => $this->localized_from_location,
            'to'           => $this->localized_to_location,
            'cargo_type'   => $this->localized_cargo_type,
            'volume'       => $this->volume,
            'weight'       => $this->weight,
            'price_usd'    => $this->price_usd,
            'ready_date'   => $this->ready_date?->toDateString(),
            'comment'      => $this->localized_comment,
            'status'       => $this->status,
            'created_by'   => $this->whenLoaded('createdBy', fn() => new UserResource($this->createdBy)),
            'picked_by'    => $this->whenLoaded('pickedBy',  fn() => new UserResource($this->pickedBy)),
            'created_at'   => $this->created_at?->toISOString(),
        ];
    }
}

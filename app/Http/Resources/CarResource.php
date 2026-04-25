<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Car",
 *     @OA\Property(property="id",             type="integer", example=1),
 *     @OA\Property(property="brand",          type="string",  example="Volvo"),
 *     @OA\Property(property="model",          type="string",  example="FH16"),
 *     @OA\Property(property="license_plate",  type="string",  example="A123BC"),
 *     @OA\Property(property="max_weight",     type="number",  example=20.0),
 *     @OA\Property(property="trailer_type",   type="string",  enum={"tral","refrigerator","tent"}),
 *     @OA\Property(property="trailer_length", type="number",  example=13.6),
 *     @OA\Property(property="trailer_width",  type="number",  example=2.4),
 *     @OA\Property(property="trailer_height", type="number",  example=2.7),
 *     @OA\Property(property="trailer_volume", type="number",  example=88.13),
 *     @OA\Property(property="is_active",      type="boolean", example=true),
 *     @OA\Property(property="created_at",     type="string",  format="date-time")
 * )
 */
class CarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'brand'          => $this->localized_brand,
            'model'          => $this->localized_model,
            'license_plate'  => $this->license_plate,
            'max_weight'     => $this->max_weight,
            'trailer_type'   => $this->localized_trailer_type,
            'trailer_length' => $this->trailer_length,
            'trailer_width'  => $this->trailer_width,
            'trailer_height' => $this->trailer_height,
            'trailer_volume' => $this->trailer_volume,
            'is_active'      => $this->is_active,
            'driver'         => $this->whenLoaded('user', fn() => new UserResource($this->user)),
            'created_at'     => $this->created_at?->toISOString(),
        ];
    }
}

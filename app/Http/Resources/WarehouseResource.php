<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Warehouse",
 *     @OA\Property(property="id",      type="integer", example=1),
 *     @OA\Property(property="name",    type="string",  example="Склад Алматы-Север"),
 *     @OA\Property(property="address", type="string",  example="ул. Панфилова, 14"),
 *     @OA\Property(property="phone",   type="string",  nullable=true, example="+7 727 000 00 00"),
 *     @OA\Property(property="city_id", type="integer", example=1)
 * )
 */
class WarehouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'name'    => $this->localized_name,
            'address' => $this->address,
            'phone'   => $this->phone,
            'city_id' => $this->city_id,
        ];
    }
}

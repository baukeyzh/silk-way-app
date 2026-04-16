<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id",          type="integer", example=1),
 *     @OA\Property(property="name",        type="string",  example="Иван Иванов"),
 *     @OA\Property(property="email",       type="string",  example="ivan@example.com"),
 *     @OA\Property(property="role",        type="string",  enum={"admin","warehouse_employee","driver"}),
 *     @OA\Property(property="is_approved", type="boolean", example=true),
 *     @OA\Property(property="created_at",  type="string",  format="date-time")
 * )
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'role'        => $this->role,
            'is_approved' => $this->isApproved(),
            'created_at'  => $this->created_at?->toISOString(),
        ];
    }
}

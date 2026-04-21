<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CargoApplication",
 *     @OA\Property(property="id",                    type="integer", example=1),
 *     @OA\Property(property="status",                type="string",  enum={"pending","approved","rejected","delivered"}),
 *     @OA\Property(property="driver_notes",          type="string",  nullable=true),
 *     @OA\Property(property="warehouse_notes",       type="string",  nullable=true),
 *     @OA\Property(property="contact_whatsapp",      type="string",  nullable=true, example="+77001234567"),
 *     @OA\Property(property="contact_wechat",        type="string",  nullable=true),
 *     @OA\Property(property="pickup_contact",        type="string",  nullable=true),
 *     @OA\Property(property="pickup_address",        type="string",  nullable=true),
 *     @OA\Property(property="delivery_contact",      type="string",  nullable=true),
 *     @OA\Property(property="delivery_address",      type="string",  nullable=true),
 *     @OA\Property(property="approved_at",           type="string",  format="date-time", nullable=true),
 *     @OA\Property(property="cmr_status",            type="string",  enum={"not_uploaded","pending_review","confirmed","rejected"}, example="not_uploaded"),
 *     @OA\Property(property="cmr_original_filename", type="string",  nullable=true),
 *     @OA\Property(property="cmr_uploaded_at",       type="string",  format="date-time", nullable=true),
 *     @OA\Property(property="cmr_confirmed_at",      type="string",  format="date-time", nullable=true),
 *     @OA\Property(property="cmr_rejection_reason",  type="string",  nullable=true),
 *     @OA\Property(property="cmr_rejected_at",       type="string",  format="date-time", nullable=true),
 *     @OA\Property(property="created_at",            type="string",  format="date-time")
 * )
 */
class CargoApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'status'                => $this->status,
            'driver_notes'          => $this->driver_notes,
            'warehouse_notes'       => $this->warehouse_notes,
            'contact_whatsapp'      => $this->contact_whatsapp,
            'contact_wechat'        => $this->contact_wechat,
            'pickup_contact'        => $this->pickup_contact,
            'pickup_address'        => $this->pickup_address,
            'delivery_contact'      => $this->delivery_contact,
            'delivery_address'      => $this->delivery_address,
            'approved_at'           => $this->approved_at?->toISOString(),
            // CMR fields
            'cmr_status'            => $this->cmr_status,
            'cmr_original_filename' => $this->cmr_original_filename,
            'cmr_uploaded_at'       => $this->cmr_uploaded_at?->toISOString(),
            'cmr_confirmed_at'      => $this->cmr_confirmed_at?->toISOString(),
            'cmr_rejection_reason'  => $this->cmr_rejection_reason,
            'cmr_rejected_at'       => $this->cmr_rejected_at?->toISOString(),
            'cargo'                 => $this->whenLoaded('cargo',  fn() => new CargoResource($this->cargo)),
            'driver'                => $this->whenLoaded('driver', fn() => new UserResource($this->driver)),
            'car'                   => $this->whenLoaded('car',    fn() => new CarResource($this->car)),
            'created_at'            => $this->created_at?->toISOString(),
        ];
    }
}

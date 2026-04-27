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
 *     @OA\Property(property="price_usd",  type="number",  nullable=true, example=1500.00, description="Only returned to authenticated users"),
 *     @OA\Property(property="ready_date", type="string",  format="date", example="2026-05-01"),
 *     @OA\Property(property="comment",    type="string",  example="Хрупкий груз"),
 *     @OA\Property(property="status",     type="string",  enum={"available","in_progress","delivered"}),
 *     @OA\Property(property="created_by", ref="#/components/schemas/User", nullable=true, description="Только для авторизованных пользователей, когда загружено отношение"),
 *     @OA\Property(property="picked_by",  ref="#/components/schemas/User", nullable=true, description="Только для авторизованных пользователей, когда загружено отношение"),
 *     @OA\Property(property="cmr",        type="object",  nullable=true, description="CMR info; null when no approved application exists",
 *         @OA\Property(property="status",           type="string", enum={"not_uploaded","pending_review","confirmed","rejected"}),
 *         @OA\Property(property="uploaded_at",      type="string", format="date-time", nullable=true),
 *         @OA\Property(property="confirmed_at",     type="string", format="date-time", nullable=true),
 *         @OA\Property(property="rejected_at",      type="string", format="date-time", nullable=true),
 *         @OA\Property(property="rejection_reason", type="string", nullable=true),
 *         @OA\Property(property="file_url",         type="string", nullable=true, description="Download URL; only present when requester is the driver-owner, WE who owns the cargo, or admin")
 *     ),
 *     @OA\Property(property="my_application", ref="#/components/schemas/CargoApplication", nullable=true, description="Заявка текущего авторизованного водителя на этот груз; null если пользователь не водитель или ещё не подавал заявку"),
 *     @OA\Property(property="created_at", type="string",  format="date-time")
 * )
 */
class CargoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // defensive: this resource is only served from auth-required endpoints,
        // but we keep the auth guard here so a future mis-route cannot leak
        // price/owner data to unauthenticated callers.
        $isAuth = $request->user() !== null;

        return [
            'id'           => $this->id,
            'from'         => $this->localized_from_location,
            'to'           => $this->localized_to_location,
            'cargo_type'   => $this->localized_cargo_type,
            'volume'       => $this->volume,
            'weight'       => $this->weight,
            'price_usd'    => $this->when($isAuth, fn () => $this->price_usd),           // defensive
            'ready_date'   => $this->ready_date?->toDateString(),
            'comment'      => $this->localized_comment,
            'status'       => $this->status,
            'created_by'   => $this->when(                                                // defensive
                $isAuth && $this->relationLoaded('createdBy'),
                fn () => new UserResource($this->createdBy)
            ),
            'picked_by'    => $this->when(                                                // defensive
                $isAuth && $this->relationLoaded('pickedBy'),
                fn () => new UserResource($this->pickedBy)
            ),
            'cmr'          => $this->when(
                $isAuth && $this->relationLoaded('approvedApplication'),
                fn () => $this->buildCmrPayload($request)
            ),
            'my_application' => $this->when(
                $isAuth && $this->relationLoaded('myApplication'),
                fn () => $this->myApplication ? new CargoApplicationResource($this->myApplication) : null
            ),
            'created_at'   => $this->created_at?->toISOString(),
        ];
    }

    /**
     * Build the cmr sub-object from the eager-loaded approvedApplication.
     * Returns null when no approved application exists yet (cargo still available).
     * file_url is only included when the requester matches the same auth rules
     * used by CargoApplicationController::cmrFile() (driver-owner, WE-of-cargo, admin).
     *
     * @return array<string, mixed>|null
     */
    private function buildCmrPayload(Request $request): ?array
    {
        $app = $this->approvedApplication;

        if ($app === null) {
            return null;
        }

        $user          = $request->user();
        $isDriverOwner = $user->isDriver()           && $app->driver_id    === $user->id;
        $isCargoOwner  = $user->isWarehouseEmployee() && $this->created_by === $user->id;
        $isAdmin       = $user->isAdmin();
        $canSeeFile    = ($isDriverOwner || $isCargoOwner || $isAdmin) && $app->cmr_file_path !== null;

        return [
            'status'           => $app->cmr_status,
            'uploaded_at'      => $app->cmr_uploaded_at?->toISOString(),
            'confirmed_at'     => $app->cmr_confirmed_at?->toISOString(),
            'rejected_at'      => $app->cmr_rejected_at?->toISOString(),
            'rejection_reason' => $app->cmr_rejection_reason,
            // Absolute URL to the Sanctum-protected download endpoint.
            // Caller must present their token — mirrors the auth check in
            // CargoApplicationController::cmrFile().
            'file_url'         => $canSeeFile
                ? url("/api/v1/applications/{$app->id}/cmr/file")
                : null,
        ];
    }
}

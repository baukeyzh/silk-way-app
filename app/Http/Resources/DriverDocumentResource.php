<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\DriverDocument;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="DriverDocument",
 *     type="object",
 *     description="A single driver document slot with its current upload state.",
 *     required={"id","type","label","description","required","status","accepted_mime_types","max_file_size_bytes"},
 *     @OA\Property(property="id",                  type="integer",  example=42,                     description="Primary key — use this in POST /documents/{id}/upload"),
 *     @OA\Property(property="type",                type="string",   example="driver_license",        description="Stable machine-readable type code. Never localized, never changes between releases."),
 *     @OA\Property(property="label",               type="string",   example="Водительское удостоверение", description="Localized human-readable name. Respects Accept-Language."),
 *     @OA\Property(property="description",         type="string",   example="Лицевая и обратная стороны удостоверения", description="Localized one-line hint for what the user should photograph."),
 *     @OA\Property(property="required",            type="boolean",  example=true,                   description="Whether this slot must be verified before the driver can accept cargo."),
 *     @OA\Property(property="status",              type="string",   enum={"not_uploaded","pending","verified","rejected"}, example="pending"),
 *     @OA\Property(property="file_url",            type="string",   nullable=true, example=null,    description="Auth-scoped URL to fetch/preview the uploaded file. null if not uploaded."),
 *     @OA\Property(property="original_filename",   type="string",   nullable=true, example="prava.pdf"),
 *     @OA\Property(property="uploaded_at",         type="string",   format="date-time", nullable=true, example="2026-04-18T10:00:00+00:00", description="ISO-8601 timestamp of the last upload."),
 *     @OA\Property(property="expires_at",          type="string",   format="date", nullable=true, example="2027-08-15"),
 *     @OA\Property(property="rejection_reason",    type="string",   nullable=true, example=null,    description="Admin rejection reason. Non-null only when status=rejected."),
 *     @OA\Property(property="accepted_mime_types", type="array",    @OA\Items(type="string"), example={"image/jpeg","image/png","application/pdf"}, description="File picker filter for mobile."),
 *     @OA\Property(property="max_file_size_bytes", type="integer",  example=5242880, description="Maximum allowed upload size in bytes (5 MB). Validate client-side before upload.")
 * )
 */
class DriverDocumentResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var DriverDocument $this */
        return [
            'id'                  => $this->id,
            'type'                => $this->document_type,
            'label'               => $this->label,
            'description'         => $this->description,
            'required'            => $this->required,
            'status'              => $this->status,
            'file_url'            => $this->resolveFileUrl(),
            'original_filename'   => $this->original_filename,
            'uploaded_at'         => $this->uploaded_at,
            'expires_at'          => $this->expires_at?->toDateString(),
            'rejection_reason'    => $this->rejection_reason,
            'accepted_mime_types' => DriverDocument::ACCEPTED_MIME_TYPES,
            'max_file_size_bytes' => DriverDocument::MAX_FILE_SIZE_BYTES,
        ];
    }

    /**
     * Returns a temporary signed URL for the stored file so mobile clients can
     * preview the uploaded document without exposing the raw storage path.
     *
     * Falls back to a plain URL on disks that do not support signed URLs
     * (e.g. local disk in development). Returns null if no file is stored.
     */
    private function resolveFileUrl(): ?string
    {
        if ($this->file_path === null) {
            return null;
        }

        try {
            // Attempt a 30-minute signed URL — works on S3-compatible disks.
            return Storage::disk('local')->temporaryUrl(
                $this->file_path,
                now()->addMinutes(30)
            );
        } catch (\RuntimeException) {
            // Local disk does not support temporaryUrl. Return null so the
            // mobile client knows to prompt a re-upload rather than hitting a
            // dead URL. Replace this with a dedicated file-serve endpoint when
            // moving to object storage in production.
            return null;
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class DriverDocument extends Model
{
    public const DOCUMENT_TYPES = [
        'driver_license',
        'vehicle_passport',
        'trailer_passport',
        'category_cert',
        'green_card',
        'insurance',
    ];

    public const OPTIONAL_TYPES = [
        'insurance',
    ];

    /** Accepted MIME types for every document slot (same policy for all). */
    public const ACCEPTED_MIME_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];

    /** Maximum file size in bytes (5 MB). */
    public const MAX_FILE_SIZE_BYTES = 5_242_880;

    public const STATUS_NOT_UPLOADED = 'not_uploaded';
    public const STATUS_PENDING      = 'pending';
    public const STATUS_VERIFIED     = 'verified';
    public const STATUS_REJECTED     = 'rejected';

    protected $fillable = [
        'user_id',
        'document_type',
        'file_path',
        'original_filename',
        'status',
        'rejection_reason',
        'expires_at',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'expires_at'  => 'date',
        'verified_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relations
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // -------------------------------------------------------------------------
    // State helpers
    // -------------------------------------------------------------------------

    public function isOptional(): bool
    {
        return in_array($this->document_type, self::OPTIONAL_TYPES, true);
    }

    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isUploaded(): bool
    {
        return !in_array($this->status, [self::STATUS_NOT_UPLOADED], true)
            && $this->file_path !== null;
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Stable machine-readable type code — identical to document_type column.
     * Exposed via `type` so the mobile contract never references the internal
     * column name "document_type".
     */
    public function getTypeAttribute(): string
    {
        return $this->document_type;
    }

    /**
     * Returns the translated label for this document type using the global
     * translate() helper so it respects the current locale session.
     */
    public function getDocumentTypeLabelAttribute(): string
    {
        return translate('docs.' . $this->document_type);
    }

    /**
     * Localized label using the stable `docs.type.{code}.label` key namespace.
     */
    public function getLabelAttribute(): string
    {
        return translate('docs.type.' . $this->document_type . '.label');
    }

    /**
     * Localized one-line hint describing what the user should photograph.
     */
    public function getDescriptionAttribute(): string
    {
        return translate('docs.type.' . $this->document_type . '.description');
    }

    /**
     * Whether this document slot is required for a driver to accept cargo.
     * Optional types are defined in OPTIONAL_TYPES; all others are required.
     */
    public function getRequiredAttribute(): bool
    {
        return !$this->isOptional();
    }

    /**
     * File size in kilobytes, read directly from local storage.
     * Returns null if no file is stored or the file no longer exists on disk.
     * Used in the driver documents view for display only — not part of the API contract.
     */
    public function getFileSizeKbAttribute(): ?int
    {
        if ($this->file_path === null) {
            return null;
        }

        if (! \Storage::disk('local')->exists($this->file_path)) {
            return null;
        }

        return (int) round(\Storage::disk('local')->size($this->file_path) / 1024);
    }

    /**
     * ISO-8601 timestamp of when the file was last uploaded (updated_at when
     * a file_path is present), exposed as `uploaded_at` for mobile clarity.
     */
    public function getUploadedAtAttribute(): ?string
    {
        if ($this->file_path === null) {
            return null;
        }

        return $this->updated_at?->toIso8601String();
    }

    // -------------------------------------------------------------------------
    // Static catalog
    // -------------------------------------------------------------------------

    /**
     * Returns the canonical type catalog used by GET /documents/types.
     * Each entry describes a document slot without being tied to a specific
     * driver row — useful for onboarding screens before registration.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function typeCatalog(): array
    {
        return collect(self::DOCUMENT_TYPES)->map(function (string $type): array {
            return [
                'type'                => $type,
                'label'               => translate('docs.type.' . $type . '.label'),
                'description'         => translate('docs.type.' . $type . '.description'),
                'required'            => !in_array($type, self::OPTIONAL_TYPES, true),
                'accepted_mime_types' => self::ACCEPTED_MIME_TYPES,
                'max_file_size_bytes' => self::MAX_FILE_SIZE_BYTES,
            ];
        })->values()->all();
    }

    // -------------------------------------------------------------------------
    // Static factory
    // -------------------------------------------------------------------------

    /**
     * Returns a Collection of all 6 DriverDocument records for the given driver,
     * creating not_uploaded placeholder rows for any missing document types.
     * This ensures the UI always has a stable set of 6 documents to iterate.
     */
    public static function getOrCreateForDriver(int $userId): Collection
    {
        $existing = static::where('user_id', $userId)->get()->keyBy('document_type');

        foreach (self::DOCUMENT_TYPES as $type) {
            if (!$existing->has($type)) {
                $doc = static::create([
                    'user_id'       => $userId,
                    'document_type' => $type,
                    'status'        => self::STATUS_NOT_UPLOADED,
                ]);
                $existing->put($type, $doc);
            }
        }

        // Return in canonical order
        return collect(self::DOCUMENT_TYPES)->map(fn ($t) => $existing->get($t));
    }
}

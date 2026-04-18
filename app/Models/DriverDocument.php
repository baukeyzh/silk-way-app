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
     * Returns the translated label for this document type using the global
     * translate() helper so it respects the current locale session.
     */
    public function getDocumentTypeLabelAttribute(): string
    {
        return translate('docs.' . $this->document_type);
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

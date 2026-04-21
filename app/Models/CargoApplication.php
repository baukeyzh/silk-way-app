<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CargoApplication extends Model
{
    // ── Application status constants ─────────────────────────────────────────
    public const STATUS_PENDING   = 'pending';
    public const STATUS_APPROVED  = 'approved';
    public const STATUS_REJECTED  = 'rejected';
    public const STATUS_DELIVERED = 'delivered'; // Set on the linked Cargo when CMR is confirmed

    // ── CMR status constants ──────────────────────────────────────────────────
    public const CMR_STATUS_NOT_UPLOADED   = 'not_uploaded';
    public const CMR_STATUS_PENDING_REVIEW = 'pending_review';
    public const CMR_STATUS_CONFIRMED      = 'confirmed';
    public const CMR_STATUS_REJECTED       = 'rejected';

    protected $fillable = [
        'cargo_id',
        'car_id',
        'driver_id',
        'status',
        'driver_notes',
        'warehouse_notes',
        'contact_whatsapp',
        'contact_wechat',
        'pickup_contact',
        'pickup_address',
        'delivery_contact',
        'delivery_address',
        'approved_at',
        'approved_by',
        // CMR fields
        'cmr_status',
        'cmr_file_path',
        'cmr_original_filename',
        'cmr_uploaded_at',
        'cmr_confirmed_by',
        'cmr_confirmed_at',
        'cmr_rejection_reason',
        'cmr_rejected_at',
    ];

    protected $casts = [
        'approved_at'     => 'datetime',
        'cmr_uploaded_at' => 'datetime',
        'cmr_confirmed_at' => 'datetime',
        'cmr_rejected_at' => 'datetime',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cmr_confirmed_by');
    }

    // ── Application status scopes ─────────────────────────────────────────────

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // ── CMR status scopes ─────────────────────────────────────────────────────

    public function scopeCmrPendingReview(Builder $query): Builder
    {
        return $query->where('cmr_status', self::CMR_STATUS_PENDING_REVIEW);
    }

    public function scopeCmrConfirmed(Builder $query): Builder
    {
        return $query->where('cmr_status', self::CMR_STATUS_CONFIRMED);
    }

    public function scopeCmrRejected(Builder $query): Builder
    {
        return $query->where('cmr_status', self::CMR_STATUS_REJECTED);
    }

    // ── Application status helpers ────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    // ── CMR state helpers ─────────────────────────────────────────────────────

    /**
     * Driver may upload a CMR when the application is approved (in transit)
     * and the CMR has not yet been confirmed (fresh upload or after rejection).
     */
    public function canDriverUploadCmr(): bool
    {
        return $this->isApproved()
            && in_array($this->cmr_status, [
                self::CMR_STATUS_NOT_UPLOADED,
                self::CMR_STATUS_REJECTED,
            ], true);
    }

    /**
     * A reviewer (WE who owns the cargo, or any admin) may act when the CMR
     * is awaiting their decision.
     */
    public function canReviewerActOnCmr(): bool
    {
        return $this->cmr_status === self::CMR_STATUS_PENDING_REVIEW;
    }

    /**
     * Once confirmed the CMR is immutable — no further uploads, deletions,
     * or re-confirmations are permitted.
     */
    public function isCmrLocked(): bool
    {
        return $this->cmr_status === self::CMR_STATUS_CONFIRMED;
    }
}

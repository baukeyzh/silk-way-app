<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'name_rus',
        'name_kaz',
        'name_chn',
        'address',
        'phone',
        'city_id',
        'created_by',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    /**
     * Returns the warehouse name in the current application locale,
     * falling back to name_rus and then name.
     * Mirrors the pattern in City::getLocalizedNameAttribute().
     */
    public function getLocalizedNameAttribute(): string
    {
        $suffix = ['ru' => 'rus', 'kz' => 'kaz', 'cn' => 'chn'][app()->getLocale()] ?? 'rus';
        $field  = "name_{$suffix}";

        return $this->$field ?: ($this->name_rus ?: $this->name);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Number of cargo rows that reference this warehouse as origin or destination.
     * Used before deletion to block removal of referenced warehouses.
     */
    public function cargoCount(): int
    {
        return (int) \DB::table('cargo')
            ->where('from_warehouse_id', $this->id)
            ->orWhere('to_warehouse_id', $this->id)
            ->count();
    }
}

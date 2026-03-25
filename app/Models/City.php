<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'name_rus', 'name_kaz', 'name_chn', 'country'];

    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();
        $field = "name_{$locale}";

        return $this->$field ?: $this->name;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
{
    /**
     * Policy enforcement happens in WarehouseController — authorize() always
     * returns true here so the controller decides who may create.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name_rus' => ['required', 'string', 'max:255'],
            'name_kaz' => ['nullable', 'string', 'max:255'],
            'name_chn' => ['nullable', 'string', 'max:255'],
            'address'  => ['required', 'string', 'max:500'],
            'phone'    => ['nullable', 'string', 'max:50'],
            'city_id'  => ['required', 'exists:cities,id'],
        ];
    }
}

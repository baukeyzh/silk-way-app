<?php

declare(strict_types=1);

namespace App\Http\Requests\DriverRegistration;

use Illuminate\Foundation\Http\FormRequest;

class RequestCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string'],
        ];
    }
}

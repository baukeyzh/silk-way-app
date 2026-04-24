<?php

declare(strict_types=1);

namespace App\Http\Requests\DriverLogin;

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
            'phone' => ['required', 'string'],
        ];
    }
}

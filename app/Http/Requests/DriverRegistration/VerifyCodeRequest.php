<?php

declare(strict_types=1);

namespace App\Http\Requests\DriverRegistration;

use Illuminate\Foundation\Http\FormRequest;

class VerifyCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string'],
            'code'  => ['required', 'string', 'digits:6'],
            'name'  => ['required', 'string', 'max:255'],
        ];
    }
}

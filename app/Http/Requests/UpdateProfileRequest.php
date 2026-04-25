<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Self-update only. Admins editing other users go through admin endpoints.
        return $this->user()->id === $this->route('user', $this->user())->id;
    }

    public function rules(): array
    {
        $user = $this->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        if (! $user->isDriver()) {
            $rules['email'] = [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ];

            // Password is optional on profile update — blank = leave unchanged.
            $rules['password'] = [
                'nullable',
                'string',
                Password::min(8),
                'confirmed',
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Имя обязательно для заполнения.',
            'name.max'            => 'Имя не должно превышать 255 символов.',
            'email.required'      => 'Email обязателен для заполнения.',
            'email.email'         => 'Введите корректный email.',
            'email.unique'        => 'Этот email уже занят.',
            'password.min'        => 'Пароль должен содержать не менее 8 символов.',
            'password.confirmed'  => 'Пароли не совпадают.',
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('profile.show', ['user' => $request->user()]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user      = $request->user();
        $validated = $request->validated();

        $payload = ['name' => $validated['name']];

        if (! $user->isDriver()) {
            $payload['email'] = $validated['email'];

            // Only hash and persist a new password when the field was actually filled.
            if (! empty($validated['password'])) {
                $payload['password'] = Hash::make($validated['password']);
            }
        }

        $user->update($payload);

        return redirect()->route('profile.show')
            ->with('success', 'Профиль обновлён');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverRegistration\RequestCodeRequest;
use App\Http\Requests\DriverRegistration\ResendCodeRequest;
use App\Http\Requests\DriverRegistration\VerifyCodeRequest;
use App\Services\DriverRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Web controller for the WhatsApp OTP driver registration flow.
 *
 * Session keys used to carry state between Step 1 and Step 2:
 *   driver_reg_phone – normalised phone digits, stored after a successful requestCode
 *   driver_reg_name  – the name submitted in Step 1 (UX agent renders as hidden field)
 *
 * The UX agent may read these keys from the session to pre-fill the Step 2 form.
 */
class DriverRegistrationController extends Controller
{
    public function __construct(
        private readonly DriverRegistrationService $service,
    ) {}

    /**
     * GET /register/driver — render the registration form.
     * Supports ?reset=1 to clear session state and return to Step 1.
     */
    public function showForm(Request $request): RedirectResponse
    {
        if ($request->query('reset') === '1') {
            session()->forget(['driver_reg_phone', 'driver_reg_name']);
            return redirect()->route('register', ['type' => 'driver']);
        }

        return redirect()->route('register', ['type' => 'driver']);
    }

    /**
     * Step 1 — POST /register/driver/request
     */
    public function requestCode(RequestCodeRequest $request): RedirectResponse
    {
        try {
            $result = $this->service->requestCode(
                $request->string('name')->toString(),
                $request->string('phone')->toString(),
            );
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (HttpException $e) {
            return $this->redirectWithHttpError($e);
        }

        // Persist normalised phone + name in session so Step 2 form can pre-fill them
        session([
            'driver_reg_phone' => $result['phone'],
            'driver_reg_name'  => $request->string('name')->toString(),
        ]);

        return redirect()->back()->with('driver_reg_code_sent', true);
    }

    /**
     * Step 2 — POST /register/driver/verify
     */
    public function verifyCode(VerifyCodeRequest $request): RedirectResponse
    {
        try {
            $this->service->verifyAndRegister(
                phone: $request->string('phone')->toString(),
                code:  $request->string('code')->toString(),
                name:  $request->string('name')->toString(),
            );
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (HttpException $e) {
            return $this->redirectWithHttpError($e);
        }

        // Clear registration session state on success
        session()->forget(['driver_reg_phone', 'driver_reg_name']);

        return redirect()->route('login')->with('success', translate('driver_reg.success'));
    }

    /**
     * Step 3 — POST /register/driver/resend
     */
    public function resendCode(ResendCodeRequest $request): RedirectResponse
    {
        try {
            $this->service->resendCode(
                $request->string('phone')->toString(),
            );
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (HttpException $e) {
            return $this->redirectWithHttpError($e);
        }

        return redirect()->back()->with('driver_reg_code_resent', true);
    }

    /**
     * Convert an HttpException from the service layer (abort(409/410/429/503))
     * into a redirect with errors scoped to the field the register.blade.php
     * error banner inspects. The view uses $errors->has('phone'|'code') plus
     * message-text heuristics to render context-aware banners.
     */
    private function redirectWithHttpError(HttpException $e): RedirectResponse
    {
        $field = match ($e->getStatusCode()) {
            409 => 'phone',      // phone already registered (409) → rose banner with login CTA
            410 => 'code',       // code expired (410) → rose banner with resend CTA
            429, 503 => 'phone', // rate-limit / service down → generic phone-row error
            default  => 'phone',
        };

        return back()->withErrors([$field => $e->getMessage()])->withInput();
    }
}

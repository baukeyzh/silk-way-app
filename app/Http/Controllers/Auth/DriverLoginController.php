<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverLogin\RequestCodeRequest;
use App\Http\Requests\DriverLogin\ResendCodeRequest;
use App\Http\Requests\DriverLogin\VerifyCodeRequest;
use App\Services\DriverLoginService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Web controller for the WhatsApp OTP driver login flow.
 *
 * Session keys:
 *   driver_login_phone — normalised phone digits, stored after a successful requestCode
 *
 * The UX agent may read driver_login_phone to pre-fill the Step 2 form and render
 * the "Код отправлен на <masked>" banner.
 */
class DriverLoginController extends Controller
{
    public function __construct(
        private readonly DriverLoginService $service,
    ) {}

    /**
     * GET /login/driver — redirect to the unified login page.
     * ?reset=1 clears session state before the redirect so the user lands on step 1.
     */
    public function showForm(Request $request): RedirectResponse
    {
        if ($request->query('reset') === '1') {
            session()->forget(['driver_login_phone', 'driver_login_phone_masked']);
        }

        return redirect()->route('login', ['type' => 'driver']);
    }

    /**
     * Step 1 — POST /login/driver/request
     */
    public function requestCode(RequestCodeRequest $request): RedirectResponse
    {
        try {
            $result = $this->service->requestCode(
                $request->string('phone')->toString(),
            );
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (HttpException $e) {
            return $this->redirectWithHttpError($e);
        }

        // Store normalised phone so Step 2 Blade can pre-fill the hidden field
        session([
            'driver_login_phone'        => $result['phone'],
            'driver_login_phone_masked' => $result['phone_masked'],
        ]);

        return redirect()->back()->with('driver_login_code_sent', true);
    }

    /**
     * Step 2 — POST /login/driver/verify
     */
    public function verifyCode(VerifyCodeRequest $request): RedirectResponse
    {
        try {
            $user = $this->service->verifyCode(
                phone: $request->string('phone')->toString(),
                code:  $request->string('code')->toString(),
            );
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (HttpException $e) {
            return $this->redirectWithHttpError($e);
        }

        Auth::login($user);
        $request->session()->regenerate();

        // Clear login session state on success
        session()->forget(['driver_login_phone', 'driver_login_phone_masked']);

        return redirect()->intended(route('cargo.index'));
    }

    /**
     * Step 3 — POST /login/driver/resend
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

        return redirect()->back()->with('driver_login_code_resent', true);
    }

    /**
     * Convert HttpException from service layer (abort(403/404/410/429/503))
     * into a redirect with errors. The login view surfaces these via the
     * standard $errors bag.
     */
    private function redirectWithHttpError(HttpException $e): RedirectResponse
    {
        $field = match ($e->getStatusCode()) {
            410 => 'code',       // expired code → inline resend CTA
            403, 404 => 'phone', // not approved / not found → phone-scoped error
            default => 'phone',  // 429, 503 and others → generic phone-row error
        };

        return back()->withErrors([$field => $e->getMessage()])->withInput();
    }
}

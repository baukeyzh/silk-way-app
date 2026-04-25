---
name: WhatsApp OTP Driver Login Flow
description: Architecture, contracts, and shared-logic decisions for the driver WhatsApp OTP login flow (API 1.7.0)
type: project
---

WhatsApp OTP login for existing drivers is implemented as a parallel path to email/password `/login`. It mirrors the registration flow structure exactly.

**Why:** Drivers register via phone OTP; they must also be able to log in via OTP without a password.

**How to apply:** When touching auth or OTP flows, keep registration and login isolated — they share a trait but have separate services, controllers, request classes, rate limiters, and translation groups.

## Key files
- `App\Services\DriverLoginService` — orchestrates the 3-step login flow
- `App\Services\Concerns\HandlesPhoneOtp` — shared trait (normalizePhone, generateCode, requireSessionReady, maskPhone) used by both DriverRegistrationService and DriverLoginService
- `App\Http\Controllers\Auth\DriverLoginController` — web (session-based)
- `App\Http\Controllers\API\DriverLoginController` — API (Sanctum token)
- `App\Http\Requests\DriverLogin\{RequestCodeRequest,VerifyCodeRequest,ResendCodeRequest}`

## Shared-logic decision
Trait extracted (`HandlesPhoneOtp`) — overlap was ~70 lines across normalizePhone, generateCode, requireSessionReady, and maskPhone. Threshold was 20 lines; extraction was justified. DriverRegistrationService now uses the same trait.

## Rate limiter
- `waha-driver-login`: 20 req/min by IP (more permissive than registration's 10/min — legitimate retry on bad code shouldn't lock out)
- Per-phone 60s throttle enforced at DB level via `phone_verifications.last_sent_at`

## PhoneVerification.PURPOSE_DRIVER_LOGIN = 'driver_login'
Composite unique `(phone, purpose)` allows simultaneous registration + login rows without collision.

## Session keys (web)
- `driver_login_phone` — normalised digits from step 1
- `driver_login_phone_masked` — pre-rendered masked string for step 2 banner

## Token name
`$user->createToken('mobile-wa')` — same name as registration to keep mobile token management uniform.

## WhatsApp message template
`driver_login.wa_message` — "Silk Way: код для входа :code. Действителен 10 минут. Не передавайте его никому."
Different from registration template (`driver_reg.wa_message`). `WhatsAppService::sendOtp()` now accepts optional `$messageOverride` param.

## Translation keys added: 33 unique driver_login.* keys
## API version bumped: 1.6.0 → 1.7.0

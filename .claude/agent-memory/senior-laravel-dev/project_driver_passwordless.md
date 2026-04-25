---
name: Driver Passwordless Auth (API 1.8.0)
description: Drivers have null password; legacy /register and /login block driver role; WhatsApp OTP is the sole auth path for drivers
type: project
---

Drivers authenticate exclusively via WhatsApp OTP. `users.password` is now nullable (migration `2026_04_18_100000_make_password_nullable_on_users_table`). Drivers created via the OTP flow are stored with `password = null`.

**Why:** Password field was vestigial for WhatsApp-registered drivers. Removing it closes a potential bypass where a driver could set a password via the legacy endpoint and log in without OTP.

**How to apply:**
- `DriverRegistrationService::verifyAndRegister()` takes no `$password` argument and passes `password: null` to `User::create()`.
- `VerifyCodeRequest` validates only `phone`, `code`, `name` — no password rules.
- Web `AuthController::login()` and API `AuthController::login()` check for driver role BEFORE `Auth::attempt()`, returning the redirect message unconditionally. This eliminates any timing oracle.
- Web `AuthController::register()` and API `AuthController::register()` reject `role=driver` before the validator runs.
- Legacy `/register` Blade view (`register.blade.php`) has a fixed hidden `role=warehouse_employee` field; the driver card UI is removed; a CTA banner replaces it.
- Driver-register Blade view (`driver-register.blade.php`) step 2 has no password or password_confirmation fields.
- Translation keys: `auth.driver_use_whatsapp_register` and `auth.driver_use_whatsapp_login` (both in `auth` group).
- Swagger: version bumped to 1.8.0. `POST /auth/register` enum only includes `warehouse_employee`. `POST /auth/driver/register/verify` no longer lists password fields.
- UserFactory still generates passwords — factory-created users (used in tests) will have passwords and non-driver roles by default. If tests create driver users via factory, callers must override `password: null` and `role: driver` explicitly.

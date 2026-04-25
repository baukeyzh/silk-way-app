---
name: WhatsApp OTP Driver Registration Architecture
description: Architecture decisions for the WAHA-based driver registration flow added in April 2026
type: project
---

Drivers register via a 3-step WhatsApp OTP flow at `/register/driver/*` (web) and `/api/v1/auth/driver/register/*` (API). The existing `/register` path is untouched.

**Why:** Drivers don't have email; WhatsApp is the primary channel in the target market.

**How to apply:** When touching auth or user creation, remember drivers may have `email=null`; phone is the unique identifier for driver accounts. Don't add NOT NULL email constraints without checking this flow.

Key files:
- `app/Services/DriverRegistrationService.php` — all business logic (shared by web + API)
- `app/Services/WhatsAppService.php` — WAHA HTTP client
- `app/Exceptions/WhatsAppServiceException.php` — thrown on WAHA 4xx/5xx
- `app/Models/PhoneVerification.php` — OTP rows; composite unique on (phone, purpose)
- `app/Http/Controllers/Auth/DriverRegistrationController.php` — web
- `app/Http/Controllers/API/DriverRegistrationController.php` — API

Schema additions:
- `users.phone` — nullable string(32), unique (MySQL treats NULLs as distinct — safe)
- `users.email` — changed to nullable for driver accounts
- `phone_verifications` table — composite unique (phone, purpose); MAX_ATTEMPTS=5, TTL=600s, resend throttle=60s enforced via `last_sent_at` column (not Laravel rate limiter, because it must be phone-scoped not IP-scoped)

Config: `config/services.php` key `waha` — reads `WAHA_URL`, `WAHA_API_KEY`, `WA_SESSION`.
Rate limiter: `waha-driver-register` — 10/min by IP, registered in `AppServiceProvider::boot()`.

Session keys (web flow): `driver_reg_phone`, `driver_reg_name` — set after step 1, cleared after step 2 success.

Swagger: API version bumped to 1.6.0; tag `DriverRegistration` added.

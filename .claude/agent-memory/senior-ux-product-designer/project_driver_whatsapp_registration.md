---
name: Project: Driver WhatsApp OTP Registration Flow
description: Single-view two-step driver registration via WhatsApp OTP — Blade, Alpine countdown, translation keys added
type: project
---

Single Blade view at `resources/views/auth/driver-register.blade.php` handles both steps via `@php $step = session('driver_reg_phone') ? 2 : 1; @endphp`.

**Step detection:** session key `driver_reg_phone` — presence = step 2, absence = step 1.

**Step 1:** name + phone inputs, POST to `driver.register.request`. WhatsApp icon (fab fa-whatsapp) on submit button and logo. Phone hint text below input. Backend sets session on success.

**Step 2:** emerald info banner showing phone from session + "Изменить номер" link to `route('driver.register.request')?reset=1` (Laravel agent must handle `?reset=1` to clear session). Hidden `phone` and `name` fields from session. OTP input: single `inputmode="numeric" maxlength="6" autocomplete="one-time-code" font-mono tracking-widest`. Password + confirmation with eye toggle (identical pattern to login/register). 60-second Alpine countdown before resend form becomes visible (separate form POSTing to `driver.register.resend`).

**Route names consumed:** `driver.register.request`, `driver.register.verify`, `driver.register.resend` — all provided by Laravel agent (not yet registered at build time).

**Translation keys added:** 14 keys under `driver_reg.*` namespace + 1 under `auth.phone`. All RU production-ready, KZ/CN machine-translated with TODO comments. Added at bottom of TranslationSeeder.php under `// Driver registration UI copy` label.

**Login + register views modified:** Added "Водитель? Регистрация через WhatsApp" link in emerald color below existing footer links on both views.

**Flags for Laravel agent:**
- Need GET handler for `/register/driver` to render step 1 (no redirect, just returns the view).
- Need to handle `?reset=1` query param to clear `driver_reg_phone` and `driver_reg_name` session keys and redirect back to step 1.
- Session must also store `driver_reg_name` (the view reads it for the hidden name field on step 2).

**Why:** WhatsApp OTP is the preferred onboarding channel for drivers in the target market (Kazakhstan); email-based registration has high drop-off.

**How to apply:** When touching auth views or driver onboarding, remember this is a separate flow from the email-based `register.blade.php`. Do not merge the two.

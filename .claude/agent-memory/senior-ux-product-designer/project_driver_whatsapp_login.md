---
name: Project: Driver WhatsApp OTP Login Flow
description: Driver login via WhatsApp OTP — mirror of driver-register flow but phone-only step 1 and code-only step 2, no name/password
type: project
---

Single Blade view at `resources/views/auth/driver-login.blade.php` mirrors `driver-register.blade.php` with two key omissions: no name field on step 1, no password/confirmation on step 2.

**Step detection:** session key `driver_login_phone` — presence = step 2, absence = step 1. (`@php $step = session('driver_login_phone') ? 2 : 1; @endphp`)

**Step 1:** phone only, POST to `driver.login.request`. Same phone input pattern (type="tel", autocomplete="tel"). Same indigo button with WhatsApp icon.

**Step 2:** emerald banner showing `session('driver_login_phone')` + "Изменить номер" link to `route('driver.login.show', ['reset' => 1])`. Hidden `phone` field from session. OTP input only: `inputmode="numeric" maxlength="6" autocomplete="one-time-code" font-mono tracking-widest`. Submit button uses `fa-sign-in-alt` icon, label "Войти". 60-second Alpine countdown before resend form (POSTs to `driver.login.resend`).

**Route names consumed:** `driver.login.show`, `driver.login.request`, `driver.login.verify`, `driver.login.resend`.

**Translation keys added:** 15 keys under `driver_login.*` namespace (title, subtitle_step1/2, phone_hint, request_button, code_sent_to, change_number, code_label, code_hint, verify_button, resend_prompt, resend_available_in, resend_button, no_account, register_link, login_page_prompt, login_page_link, already_have_account, login_whatsapp_link). All RU production-ready, KZ/CN machine-translated with TODO comments.

**Views modified:**
- `login.blade.php` — added "Водитель? Войти через WhatsApp" link (emerald) below existing driver registration link.
- `driver-register.blade.php` — added "Уже есть аккаунт? Войти через WhatsApp" link (emerald) below existing footer login link.

**Flags for Laravel agent:**
- `?reset=1` query param on `driver.login.show` must clear `driver_login_phone` from session and return step 1 view.
- Session key is `driver_login_phone` (distinct from registration's `driver_reg_phone`).
- On successful verify, redirect to `/cargo` or intended URL (standard Laravel auth redirect).
- Phone in session is shown verbatim in the info banner — consider passing `$phoneMasked` from the controller to show a masked format (e.g., "+7 ••• ••• **-67") rather than the raw number.

**Why:** Drivers who registered via WhatsApp OTP do not have an email/password and cannot use the standard login form. This flow provides the parallel login path.

**How to apply:** Keep driver login and driver registration as separate views and separate session key namespaces. Do not merge.

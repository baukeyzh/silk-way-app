---
name: Project: Unified Register Page
description: register.blade.php unified driver (WhatsApp OTP) + warehouse (email/password) tabs, server-side type toggle, improved error UX
type: project
---

Single `resources/views/auth/register.blade.php` now serves both registration flows toggled via `?type=driver` (default) or `?type=warehouse`.

**Why:** Reduces entry-point confusion; tab selector is the affordance replacing the old emerald "Вы водитель?" banner. Server-side toggle chosen over Alpine tabs because driver step 2 depends on session state — client-only tabs would fight session lifecycle.

**How to apply:** If adding a third registration role in future, add a third tab here and a third `@elseif($isDriver === 'newrole')` block. Do not create a new standalone Blade view.

Key decisions:
- Tab selector hidden entirely during driver step 2 (user committed to that path; showing toggle implies they can switch mid-flow and lose their pending OTP)
- `driver.register.show` controller (`DriverRegistrationController::showForm`) now always redirects to `/register?type=driver`; `driver-register.blade.php` is kept as a file but is never rendered — the controller no longer calls `view('auth.driver-register')`
- Error banner: context-aware heading (generic / wa_send / wa_verify), expired-code inline resend CTA, phone-taken inline login CTA
- Alpine `submitting` state on all submit buttons; `x-cloak` on spinner + loading label
- 17 new `auth.*` translation keys added to TranslationSeeder, seeded with --force
- `auth.already_have_account` key replaces the old hardcoded "Уже есть аккаунт?" string
- login.blade.php driver registration link updated from `driver.register.show` to `register?type=driver`

Flagged for backend agent: `abort(503)`, `abort(429)`, `abort(409)` in driver registration and login service controllers produce Laravel's default full-page error screen, not the styled in-page banners. To make the amber/rose contextual banners reachable, those controllers must switch to `back()->withErrors(...)` + redirect. Files to check: `DriverRegistrationController`, `DriverLoginController`, `DriverRegistrationService`.

---
name: Project: Unified Login Page
description: Unified /login page with driver (WhatsApp OTP) and warehouse/admin (email+password) tab toggle, mirroring the register page architecture
type: project
---

Single `resources/views/auth/login.blade.php` replaced the old single-flow email/password view.

**Architecture mirrors `register.blade.php` exactly:**
- `@php` block: `$driverLoginEnabled` from `config('features.web_driver_login', true)`, `$activeTab` from `?type=driver|warehouse`, `$isDriver`, `$driverStep` from `session('driver_login_phone')`, error heuristics
- Tab selector: 2-col grid, emerald for driver, indigo for warehouse/admin (`fa-user-tie` icon), hidden during step 2 and when flag is false
- Card: context-aware heading, rose error banner (expired-code path with inline resend CTA), emerald flash banners for `driver_login_code_sent` / `driver_login_code_resent`
- Driver step 1: phone input → `driver.login.request`; step 2: masked-phone banner + 6-digit code + `driver.login.verify` + Alpine 60s countdown resend
- Warehouse/admin: email + password (eye toggle) + remember-me checkbox + hidden redirect field + Alpine submitting state
- Footer: driver tab → `/register?type=driver` (emerald) if `web_driver_registration` enabled, else falls back to `/register?type=warehouse` (indigo); warehouse tab → `/register?type=warehouse` (indigo)

**Driver step submit buttons use emerald accent** (not indigo) to visually reinforce the WhatsApp brand colour throughout the driver flow.

**Controller change:** `DriverLoginController::showForm()` now returns `redirect()->route('login', ['type' => 'driver'])`. `?reset=1` forgets both `driver_login_phone` and `driver_login_phone_masked` before redirecting. `View` import removed.

**Deleted:** `resources/views/auth/driver-login.blade.php` — confirmed no remaining references.

**Translation keys added (10 new, `auth` group):**
- `auth.login_tab_driver_label`, `auth.login_tab_driver_sub`
- `auth.login_tab_warehouse_label`, `auth.login_tab_warehouse_sub`
- `auth.login_error_heading_generic`, `auth.login_error_heading_wa_send`, `auth.login_error_heading_wa_verify`
- `auth.login_card_heading`, `auth.login_card_heading_driver`, `auth.login_card_heading_driver_step2`
- `auth.login_submitting`

Reused without duplication: all `driver_login.*` keys, `auth.expired_code_cta`, `auth.sending_code`, `auth.verifying`, `auth.no_account`, `auth.register_link`, `auth.login_button`.

**Default tab with no `?type=`:** driver (when `web_driver_login` is true), warehouse (when flag is false). Same logic as register page.

**Why:** The `driver.login.show` route redirected to the old standalone view — existing WhatsApp links and register-page footer links all pointed there and now transparently redirect to the unified page.

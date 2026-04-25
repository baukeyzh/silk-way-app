---
name: Project: Driver & Staff Profile Page
description: Profile page UX — 2-col card, role-adaptive identity panel, driver-only phone lock, placeholder name detection, password section for non-drivers, 22 profile.* translation keys seeded
type: project
---

Profile page at `resources/views/profile/show.blade.php`.

**Why:** Users need to update their name (all roles), email/password (non-drivers). Drivers auto-registered via WhatsApp OTP often have placeholder names like "Водитель 7047" — page is designed to invite them to fill in a real name.

**Layout:** `@extends('layouts.app')`. 2-column on lg+ (left identity panel 280px / right form), stacked on mobile. Single `bg-white rounded-2xl shadow-sm border border-slate-200` card with internal grid.

**Key design decisions:**
- Avatar: 80px circle, indigo-100 bg / indigo-700 text, `mb_strtoupper(mb_substr(...))` multi-byte safe initial, ring color adapts per role (emerald/indigo/purple).
- Role badge colors: admin = purple-100/700, warehouse = indigo-100/700, driver = emerald-100/700 (consistent with existing dropdown in layouts.app).
- Approval status pill: emerald for approved, amber+fa-clock for pending — driver-only.
- Phone chip: slate-50 bg, locked read-only, visible only for drivers. Hint explains it's WhatsApp auth identity.
- Placeholder name detection: `str_starts_with($user->name, 'Водитель ')` — shows indigo callout + adaptive subtitle.
- Password section (non-drivers only): separated by section label divider, both inputs have Alpine eye-toggle mirroring login.blade.php exactly.
- Success banner: checks `session('profile_success')` first, falls back to `session('success')` — adapts to whatever key the Laravel agent uses.
- Error banner: card-level rose banner with bulleted error list + per-field `@error()` inline, same pattern as register/login.
- Submit button: `w-full sm:w-auto` — full-width on mobile, auto on sm+.

**Form contract:** POST to `route('profile.update')` with `@method('PATCH')`. Fields: `name` (always), `email` + `password` + `password_confirmation` (non-drivers only). Backend owns validation.

**Translation keys added:** 22 keys under `profile.*` group in TranslationSeeder.php. All have ru/kz/cn with TODO markers for KZ and CN.

**Render sizes:** driver = 12,656 bytes, warehouse_employee = 19,372 bytes.

**Flagged backend gap:** Routes (`profile.update`) not yet registered in `routes/web.php` — Laravel agent must add GET /profile (profile.show) and PATCH /profile (profile.update) behind `auth` middleware.

**How to apply:** When editing or extending the profile page, preserve the 2-col grid structure. The `$errors` ViewErrorBag is always injected by the framework's `ShareErrorsFromSession` middleware in real HTTP requests — tinker tests require passing it explicitly.

---
name: Driver Profile & Approval Notification (API 1.9.0)
description: Profile page, UpdateProfileRequest, API profile endpoints, WhatsApp approval notification hooks — shipped in API 1.9.0
type: project
---

Profile page and WhatsApp approval notification shipped as part of API 1.9.0.

**Why:** Drivers auto-register with placeholder names ("Водитель XXXX") via WhatsApp OTP and need a self-service way to update their real name. Non-drivers (WE/admin) also need name/email/password update.

**How to apply:** Reference these contracts when extending profile or approval flows.

## Key files
- `app/Http/Requests/UpdateProfileRequest` — single role-aware FormRequest; drivers get name-only, WE/admin get name+email+optional password
- `app/Http/Controllers/ProfileController` — web show/update; redirects to `profile.show` with `success` flash "Профиль обновлён"
- `app/Http/Controllers/API/ProfileController` — API show/update; returns `UserResource`
- `app/Services/WhatsAppService::sendNotification(string $phone, string $message): bool` — new method added; wraps sendText without OTP-specific concerns; callers must try/catch
- `app/Http/Controllers/AdminController::notifyDriverApproved(User)` — private helper; no-op for non-drivers or empty phone; catches all Throwable and logs warning only
- `app/Http/Controllers/API/AdminController::notifyDriverApproved(User)` — same pattern

## Notification fire points
- Web `AdminController::approveUser` — fires after `$user->update(['approved' => true])` (always fires for this method)
- Web `AdminController::toggleUserApproval` — fires only on false → true transition; captures `$wasApproved` before the update
- API `AdminController::approveUser` — same as web; always fires
- API `AdminController::toggleApproval` — same false → true gate

## Routes
- `GET  /profile` → `profile.show` (web, auth)
- `PUT  /profile` → `profile.update` (web, auth)
- `GET  /api/v1/profile` (auth:sanctum)
- `PUT  /api/v1/profile` (auth:sanctum)

## Translation keys added (spec keys)
`profile.title`, `profile.heading`, `profile.subtitle`, `profile.field_name`, `profile.field_email`, `profile.field_password`, `profile.field_password_confirmation`, `profile.field_password_hint`, `profile.field_phone_label`, `profile.field_phone_hint`, `profile.role_driver`, `profile.role_warehouse`, `profile.role_admin`, `profile.status_approved`, `profile.status_pending`, `profile.save_button`, `profile.update_success`, `notifications.driver_approved`, `nav.profile` — 19 new keys total (seeder uses updateOrCreate so idempotent)

## Existing profile keys
The seeder already contained many profile keys under different names (`profile.page_title`, `profile.phone_label`, `profile.phone_locked_hint`, etc.) from a prior pass. The new spec keys coexist; the view uses the spec keys exclusively.

## Swagger
`@OA\Info version` bumped to `1.9.0` in `API\AuthController`. New `@OA\Tag(name="Profile")` added. API\ProfileController has full `@OA\Get` + `@OA\Put` annotations.

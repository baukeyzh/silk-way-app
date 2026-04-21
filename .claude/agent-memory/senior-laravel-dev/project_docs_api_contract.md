---
name: Driver Document API Mobile Contract
description: Documents surface mobile API contract — DriverDocumentResource shape, type codes, translation key pattern, route order rules
type: project
---

As of 2026-04-18 the documents API surface was upgraded to a full mobile contract.

**Type codes** (stored in `document_type` column): `driver_license`, `vehicle_passport`, `trailer_passport`, `category_cert`, `green_card`, `insurance`. These are stable and must never be renamed. `insurance` is the only optional type.

**DriverDocumentResource** (`App\Http\Resources\DriverDocumentResource`) emits 13 fields: `id`, `type`, `label`, `description`, `required`, `status`, `file_url`, `original_filename`, `uploaded_at`, `expires_at`, `rejection_reason`, `accepted_mime_types`, `max_file_size_bytes`.

**Translation key pattern**: `docs.type.{code}.label` and `docs.type.{code}.description`. All 12 keys seeded in TranslationSeeder with ru values; kz/cn marked TODO for native speaker review.

**Route order invariant**: static document routes (`/documents/types`, `/documents/batch`, `/documents/by-type/{type}/upload`) must be registered BEFORE wildcard routes (`/documents/{document}/upload`, `/documents/{document}`) or Laravel will match static segments as ID/type path params.

**`documentShape()` private method was removed** — all shaping now goes through DriverDocumentResource. The `$this->documentShape()` call pattern must not be reintroduced.

**`file_url`**: attempts `Storage::temporaryUrl()` (works on S3); catches `RuntimeException` and returns `null` for local disk. Production migration to S3 will make this non-null.

**Why:** Mobile developer could not tell which document slot they were uploading into from a bare numeric ID. Contract adds stable type codes + localized labels + upload constraints so the mobile UI is self-documenting.

**How to apply:** Any new document-related API endpoint should use DriverDocumentResource. Any new document type must add entries to DOCUMENT_TYPES constant + translation seeder keys following the `docs.type.{code}.*` pattern.

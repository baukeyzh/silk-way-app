---
name: Driver Document Batch Upload Architecture
description: How the /documents page handles batch file upload — form= attr pattern, controller structure, translation key locations
type: project
---

The `/documents` page uses the HTML5 `form=` attribute pattern to associate per-card file inputs with a single `<form id="batch-upload-form">` declared OUTSIDE the card grid. This avoids nested-form HTML violations while keeping delete forms inline per card.

**Key decisions:**
- `<form id="batch-upload-form">` sits after the grid, contains only `@csrf` and the submit bar.
- Card file inputs use `name="files[{docId}]" form="batch-upload-form"` — keyed by document ID so the controller can look up ownership without a separate `document_ids[]` array.
- Card expiry inputs use `name="expires_at[{docId}]" form="batch-upload-form"`.
- Delete forms (`@method('DELETE')`) are per-card siblings, never inside the batch form.
- Alpine `batchForm()` scope sits on the outer page `<div>` (not on the `<form>`); it listens for the custom `file-selected` window event dispatched by each card's `@change` handler.
- Per-card `docSlot()` Alpine scope is on the card `<div>` — state is fully isolated per instance.

**Controller:**
- `batchUpload()` returns `RedirectResponse` (not JSON). Authorization is a pre-flight pass over ALL document IDs before any file is written.
- Private `uploadFileToSlot(DriverDocument, UploadedFile, ?string)` is shared by both `upload()` (single) and `batchUpload()` (loop).
- Flash keys: `flash.uploaded` (array of slot names), `flash.errors` (docId => message).

**Translation keys** live in a migration file (`2026_04_17_000004_add_document_translations.php`) for most docs.* keys. Batch-specific keys (`docs.batch_*`) were added to `TranslationSeeder.php`.

**Why:** prior implementation had one `<form>` per card — submitting one slot reloaded the page and lost other selections. Batch approach with `form=` attr solves this without AJAX.

**How to apply:** if adding new per-card inputs that must submit with the batch form, add `form="batch-upload-form"` to the input. Do not wrap the card grid in another `<form>`.

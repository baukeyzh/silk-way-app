---
name: Project: CMR Upload & Confirmation Flow
description: CMR Blade views built for driver upload and WE/admin review — data contract, UX decisions, and route dependencies
type: project
---

CMR (consignment note) flow built across three views. Data lives on `cargo_applications` table via new columns added by the Laravel agent (`cmr_status`, `cmr_file_path`, `cmr_original_filename`, `cmr_uploaded_at`, `cmr_confirmed_by`, `cmr_confirmed_at`, `cmr_rejection_reason`, `cmr_rejected_at`).

**Why:** Replaces the one-click "Доставлено" button with a multi-step, evidence-based delivery confirmation backed by an uploaded CMR document.

**Routes consumed (must exist when backend lands):**
- `applications.cmr.upload` — POST multipart, field `cmr_file`
- `applications.cmr.confirm` — POST
- `applications.cmr.reject` — POST, body: `rejection_reason`
- `applications.cmr.destroy` — DELETE
- `applications.cmr.file` — GET (streams file, driver-owner/WE-cargo-owner/admin)
- `my-cargo.applications.show-from-my-cargo` — GET, existing route for application detail

**UX decision — CMR upload placement:** Inline section inside `cargo/show-application.blade.php` (the application detail), not a modal. Rationale: upload involves a file and contextual form, not a two-click action — modals handle ephemeral confirmations, not file uploads. Driver navigates to detail first (already the pattern), sees the CMR panel below cargo info.

**my-cargo list change:** The "Доставлено" form button (which posted directly to mark-delivered) is replaced by a colored link to the application detail. Color changes with CMR status: indigo (not_uploaded), amber (pending_review), rose (rejected), emerald (confirmed). The old `cargo.mark-delivered` route in the mobile card was already broken (route doesn't exist) — now removed.

**TODO flagged for Laravel agent:** `markAsDelivered` should be triggered automatically when CMR is confirmed, not by a separate driver action. The old mark-delivered UI entry point has been removed from the driver flow.

**Translation keys:** 20 keys added, all in group `cmr`, namespaced as `cmr.label_*`, `cmr.action_*`, `cmr.banner_*`, `cmr.badge_*`. RU production-ready, KZ/CN machine-translated with TODO markers.

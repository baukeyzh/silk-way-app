---
name: CMR Upload & Confirmation Flow
description: Architecture decisions and contracts for the CMR (Consignment Note) delivery confirmation feature in cargo_applications
type: project
---

CMR flow is implemented end-to-end. The delivery transition is now driven by CMR confirmation, not a direct driver action.

**State machine** (cmr_status string column on cargo_applications):
- `not_uploaded` → `pending_review` (driver uploads)
- `pending_review` → `confirmed` (WE/admin confirms) — also sets cargo.status = delivered
- `pending_review` → `rejected` (WE/admin rejects) — driver can re-upload
- `pending_review` or `rejected` → `not_uploaded` (driver deletes before confirmation)

**Key decisions:**
- String column, not enum, so future statuses require no schema change
- Shared logic in `HandlesCmrUploads` trait (mirrors `UploadsDriverDocuments` pattern)
- `markAsDelivered` kept alive but redirects to CMR upload form (backwards compat for mobile deep-links)
- Authorization via inline `abort_unless()` — no Policy added (consistent with rest of codebase)
- Files stored under `local` disk at `cmr/{application_id}/{sha256_hash}.{ext}`
- CMR columns: cmr_status, cmr_file_path, cmr_original_filename, cmr_uploaded_at, cmr_confirmed_by (FK→users nullOnDelete), cmr_confirmed_at, cmr_rejection_reason, cmr_rejected_at

**Why:** Cargo stays `in_progress` until WE/admin explicitly confirms the CMR. This gives the warehouse an audit trail and a rejection/re-upload cycle.

**How to apply:** When building review queue queries use `CargoApplication::cmrPendingReview()`. Translation keys under `cmr.*` group. API version bumped to 1.5.0.

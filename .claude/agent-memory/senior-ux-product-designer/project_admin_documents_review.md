---
name: Project: Admin Document Review Page
description: Admin /admin/documents page — accordion layout with file previews, filter tabs, approve/reject controls, and private file-serving routes
type: project
---

Implemented a full SaaS-quality admin document review page at `/admin/documents`.

**Layout:** Option B stacked accordion — each driver is a collapsible row; expanding reveals a 3-col responsive card grid of the 6 document slots.

**File serving:** Files live in `storage/app/private/driver-documents/{userId}/` on the local disk. Two new routes stream files via `Storage::disk('local')->response()`:
- `GET /admin/documents/{document}/file` → `adminFile()` — admin only
- `GET /documents/{document}/file` → `driverFile()` — owner only

**Key design decisions:**
- Default filter is `pending` so admins land on the action queue immediately (reduces cognitive load)
- Reject button uses progressive disclosure: click shows textarea, second click submits — prevents accidental rejections
- Image files render as `<img loading="lazy">` inline; PDFs show a file-pdf icon + "Open" link to new tab; unknown types fall back to paperclip + link
- Accordion rows auto-expand when driver has pending or rejected docs
- Alpine `x-collapse` NOT available (only bare `alpinejs@3.x.x/dist/cdn.min.js` CDN); using `x-show` + `x-transition` instead

**Translation keys added (all under `docs.*` group):**
- `docs.admin_filter_all`, `docs.admin_filter_pending`, `docs.admin_filter_rejected`, `docs.admin_filter_verified`
- `docs.admin_search_placeholder`, `docs.admin_drivers_count`, `docs.admin_uploaded`
- `docs.admin_open_file`, `docs.admin_confirm_reject`, `docs.admin_not_uploaded_yet`
- `docs.admin_empty_title`, `docs.admin_empty_desc`, `docs.admin_clear_filters`

**Why:** The original page showed only a dot-matrix grid with a small tooltip popup — no actual file preview, no filtering, no search.

**How to apply:** When editing the admin documents page, remember the accordion pattern is intentional. Do not revert to the dot-matrix table.

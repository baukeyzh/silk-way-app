---
name: Status Handling Audit Fixes (S1–S10)
description: Complete set of status-handling fixes shipped in April 2026 — constants, transactions, enum→string, CMR deprecation
type: project
---

All 10 issues from the status-handling audit were fixed and shipped together in one coherent set of changes (2026-04-22).

**Why:** The audit identified race conditions in approval logic, no status constants (raw strings everywhere), enum columns that lock tables on ALTER, and semantic confusion in verified_by on rejection.

**How to apply:** These are done. Use the constants going forward. Do not reintroduce raw status strings in controllers.

Key outcomes:
- `Cargo::STATUS_*` and `CargoApplication::STATUS_*` constants added — use them everywhere
- `User::ROLE_*` constants added — use in `->where('role', ...)` queries and validation
- `approveApplication()` (web) and `approve()` (API) are now wrapped in `DB::transaction` with `lockForUpdate` + defensive re-check — concurrent approvals return 409
- `API::markAsDelivered` now returns 410 Gone (route kept alive for old mobile clients)
- `cargo.status` and `cargo_applications.status` are now `varchar(20)` (was ENUM)
- `cargo_status_index` index added on `cargo.status`
- `AdminController::dashboard()` uses `inProgress()` (not `pickedUp()`) for the in-progress count; `picked_up` key kept as alias for Blade view until view-layer batch
- `docs.status_*` translation entries are now in both the migration AND `TranslationSeeder` (updateOrCreate)
- `cargo.status_in_progress` = "В пути" added; `cargo.status_picked_up` kept as legacy alias
- `verified_by` is no longer written on document rejection — null on reject, set only on approve

Migration created: `2026_04_22_000001_change_status_columns_to_string`

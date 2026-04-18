---
name: Driver Documents Feature Design
description: UI design decisions for the driver documents section — 6 doc types, 4 states, card grid, inline upload, admin status grid
type: project
---

Driver-facing document management screen designed for the Silk Way logistics SaaS.

**Why:** Drivers must submit 6 types of documents for compliance; admins need to verify them. No prior UI existed for this workflow.

**How to apply:** When extending this feature, maintain the card grid pattern, the inline (no-modal) upload interaction, and the progress indicator convention in the page header.

## Document Types
1. Права (Driver's license) — required, expires
2. Техпаспорт (Vehicle technical passport) — required, expires
3. Техпаспорт прицепа (Trailer technical passport) — required, expires
4. С кат (Category certificate) — required, expires
5. Зеленая карта (International insurance card) — required, expires
6. Страховка (Insurance) — optional, expires

## Document States
- not_uploaded — upload CTA, dashed border accent
- pending — amber badge, file link visible, no expiry input yet
- verified — green badge, file link, expiry date displayed read-only
- rejected — red badge, rejection reason shown in rose callout, re-upload CTA

## Key Design Decisions
- Inline file input (no modal) using Alpine.js x-show toggle — reduces friction for mobile drivers
- Progress bar in header (verified count / total required) gives drivers a clear completion signal
- Optional badge on Страховка so drivers don't feel blocked by it
- Expiry date field shown only for uploaded documents (progressive disclosure)
- Admin view: compact icon-grid showing 6 doc dots per driver row in the users table — scannable at a glance
- Cards use `border-l-4` colored left border to reinforce state at a glance (amber/green/red/slate)

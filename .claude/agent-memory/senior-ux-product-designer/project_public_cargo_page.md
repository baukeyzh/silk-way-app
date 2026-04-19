---
name: Public Cargo Listing Page Design
description: UX proposal for the guest-accessible /cargo listing page — conversion funnel, card layout, price-hidden treatment, filter UX, and unapproved-driver state
type: project
---

Public cargo listing page designed as top-of-funnel for driver acquisition on Silk Way.

**Why:** Currently all cargo requires login. Making it publicly browseable converts organic visitors (drivers) into registered users.

**How to apply:** When implementing this page, follow these decisions exactly. This is a locked proposal reviewed and accepted by the team.

## Key Design Decisions

### Price treatment
- Replace price with a pill CTA: "Log in to see rate" in amber-100/amber-700 styling
- Exact class set: `inline-flex items-center bg-amber-100 text-amber-700 rounded-full text-xs font-medium px-2.5 py-1`
- Never use blur or lock icons — they signal "we're hiding something" which creates distrust
- The amber pill is warm, not threatening, and doubles as a conversion touchpoint
- The pill is a link: `href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}"`
- Return URL parameter name: `redirect`

### Card layout (mobile-first, top to bottom)
1. Header row: FROM city (text-base font-semibold text-slate-900) + fa-arrow-right + TO city (text-sm text-slate-500), status badge pinned top-right
2. Dense details row: cargo type · weight · volume (text-xs text-slate-500)
3. Ready date row (text-xs text-slate-500 + formatted date value)
4. Footer row: amber price pill (left) + "View details" indigo button (right-aligned, NOT full-width)

### Filter UX
- Sticky pill bar below the page header (not a bottom sheet, not a drawer)
- Pills: "Filters" trigger pill (with active-count badge) + quick-chip shortcuts for top cargo types
- On mobile "Filters" pill opens a bottom sheet (Alpine x-show + fixed overlay)
- Chips: Route (From/To city), Ready date range, Cargo type
- Status filter removed from guest view — show only "available" cargo to guests by default
- Active pill state: `bg-indigo-600 text-white`; inactive: `bg-white border border-slate-200 text-slate-600 hover:bg-slate-50`

### Conversion moments
- "Log in to see rate" amber pill — YES (high value, non-aggressive)
- "Log in to apply" sticky bottom bar on detail page — YES (gated at the natural action point)
- "Save this cargo" bookmark — NO (adds complexity, low return for a cold audience)
- Banner at top of list — NO (noise; the inline moments are enough)

### Detail page (public /cargo/{id})
- Hero route card: identical structure to authenticated show.blade.php
- 4-tile info grid: cargo type, volume/weight, price (amber pill replaces value only, label stays), ready date
- Bottom sticky bar: full-width "Apply for this cargo" indigo button → redirects to /login?redirect=<current-url>
- Guest does NOT see: created_by name, created_at, internal notes, applications section

### Login redirect
- Parameter name: `redirect`
- Construction: `route('login') . '?redirect=' . urlencode(url()->current())`
- After login, controller reads `request('redirect')` and redirects there
- Applies to: amber price pill AND "Apply for this cargo" button

### What NOT to build in v1
- Map view of cargo routes
- "Active applications count" social proof on cards
- Saved/bookmarked cargo for guests
- Page-level stats bar (total/available/in-transit counts) — not relevant to guests

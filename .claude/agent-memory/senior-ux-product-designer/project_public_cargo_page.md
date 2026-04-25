---
name: Public Cargo Listing Page Design
description: Redesigned guest /cargo page — hero, how-it-works strip, improved cards, footer, consolidated detail page. Shipped 2026-04-18.
type: project
---

Public cargo listing page redesigned as top-of-funnel driver acquisition on Silk Way. Shipped 2026-04-18.

**Why:** Page was stark functional output — no value prop, no conversion structure, weak card hierarchy. Needed to read as a product, not admin tooling.

**How to apply:** Implementation is live. Follow these conventions when editing either public view.

## Sections added (public-index)

- **Hero**: gradient blob bg, eyebrow count badge, `text-3xl sm:text-5xl font-bold` headline, subtitle, dual CTA (register primary / login ghost), 3 trust bullets with emerald check icons.
- **How it works**: 3-card strip, horizontal scroll on mobile (snap-x), 3-col grid on sm+. Each card: indigo-50 icon square, step label in `text-xs font-bold text-indigo-400 uppercase tracking-widest`, bold title, muted body.
- **Listings section**: heading + count badge + subtitle. Filter bar redesigned as active-chip display + "Filters" pill opening a single shared drawer (Alpine bottom-sheet on mobile, centered modal on sm+).
- **Empty state**: context-aware copy — filtered vs. no-cargo variants. Dual CTA: clear filters + register.
- **Footer**: 4-col grid (brand / product / drivers / legal). Language switcher in footer bottom bar. Copyright uses `str_replace(':year', date('Y'), ...)` pattern.

## Card redesign

- Status badge + amber rate pill share the **top** of the card header (not bottom). Rate pill anchored top-right.
- Route: full-width `flex items-center gap-2` row with FROM (font-bold) → indigo arrow bubble → TO (font-bold). Both truncate.
- Metadata in body: cargo type with fa-box, weight+volume with fa-weight-hanging/fa-cube, ready date in a `bg-slate-50 border border-slate-100 rounded-lg` inline badge.
- CTA footer: `bg-slate-900 hover:bg-indigo-600 group-hover:bg-indigo-600` — slate at rest, indigo on card hover.

## Detail page (public-show) fixes

- **Top bar consolidated**: logo + pipe + back link all in one 14px row. Saves ~40px vertical on mobile.
- **Duplicate CTA removed**: in-flow `bg-indigo-50` banner replaced with an informational "register nudge" card (no redundant button). Sticky bottom is the sole action point.
- **Sticky bottom**: two buttons side by side — `flex-1` indigo "Register" + secondary ghost "Log in". On mobile only the icon+label for login shows; full label on sm+.
- **Language switcher**: globe icon → dropdown on mobile (`sm:hidden`), pills on `sm+` (`hidden sm:flex`).
- **Hero card**: indigo-to-violet top accent bar. Status + amber pill in top row. FROM/TO with labeled `text-xs text-slate-400 uppercase tracking-wider` captions above each city name.

## Translation namespace

All new keys under `public.*` — 33 keys added in TranslationSeeder. Russian: production-ready. KZ/CN: machine-translated with `// TODO: verify with native speaker` markers.

## Preserved invariants

- Filter params: `from_city_id`, `to_city_id`, `ready_date_from`, `ready_date_to`, `search` unchanged.
- Price: never rendered. `price_usd` field only appears as the amber lock pill.
- Login redirect: `?redirect=` appended to every CTA link on both pages.
- Controller logic: untouched. Only `$totalCount = $cargo->total()` added as a view variable (derived from already-passed paginator, no extra query).
- Route names: `cargo.index`, `cargo.show`, `login`, `register` — unchanged.

---
name: Project: Admin Translation Detail Page
description: Design decisions, locale pill colors, empty-state pattern, and code snippet implementation for admin/translations/show.blade.php
type: project
---

Admin translation detail page (`resources/views/admin/translations/show.blade.php`) was redesigned to match the project design system.

**Key design decisions:**
- Locale pill color scheme: RU = slate (neutral, dominant language), KZ = emerald (primary local language), CN = amber (third language, warm tone). Avoided red/blue/yellow which are jarring together.
- Empty-state per locale card: amber icon + "Требуется перевод" heading + slate subtext — not blank card.
- Code snippet block: `bg-slate-900` dark card, Alpine `x-data` with per-snippet copy state (`copied.blade`, `copied.php`, `copied.helper`), `navigator.clipboard.writeText()` — no extra deps.
- The `{{`/`}}` literal trap is avoided by pre-computing `$snippetBlade` in `@php` using string concat: `'{' . '{ __(' . "'" . $key . "'" . ') }' . '}'`, then passing it to Alpine's `@json()` for clipboard and rendering it via `{{ $snippetBlade }}` in the dark card.
- Character count shown only when locale has a value; `mb_strlen((string) $val, 'UTF-8')` for multibyte safety.
- `ucfirst()` calls wrapped in `(string)` cast to prevent PHP 8.1 null deprecation warnings on records with null group.

**Translation keys added:** 21 keys under `admin.*` namespace in `TranslationSeeder.php`.

**Why:** Existing page used generic `bg-gray-50` shell, `bg-red-500`/`bg-blue-500`/`bg-yellow-500` circles, and `shadow-md` — none of which matched the project design system.

**How to apply:** Any future admin detail/show pages should follow this same pattern: hero card with mono key, locale grid with pill colors (slate/emerald/amber for RU/KZ/CN), metadata grid with `text-xs uppercase tracking-wider` labels, dark code block with Alpine clipboard.

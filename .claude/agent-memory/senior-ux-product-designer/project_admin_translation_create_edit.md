---
name: Admin Translation Create & Edit Pages
description: Design decisions, component patterns, and seeder keys for translation create/edit pages redesigned in April 2026
type: project
---

Both `create.blade.php` and `edit.blade.php` were redesigned in the same pass to match the design system from `show.blade.php`.

## Design decisions

- **Two-section card layout**: "Identifikatsiya" (Meta) and "Translations" sections divided by a border-t inside one `bg-white rounded-xl border border-slate-200 shadow-sm` card
- **Group field**: Changed from a `<select>` (old style) to a plain `<input>` with Alpine-driven dropdown autocomplete (`filteredGroups()`) — removes the "new_group" special-value hack while keeping full controller compatibility (controller already handles any string)
- **3-column locale grid** on `md:` breakpoint — RU/KZ/CN at equal width, no col-span-2 for CN
- **Character counter**: Alpine `x-data="{ count: initialLength }"` on each locale card, `@input` updates count, displayed as `N симв.` in the card header right slot
- **Optional badge**: KZ and CN show italic "необязательно" in the card header (controller validates all three as nullable)
- **Error banner**: rose-50/200/700 palette, shows count + list of all errors at top of form
- **Inline field errors**: rose-600 with `fas fa-circle-exclamation` icon prefix
- **Sticky footer action bar**: `bg-slate-50 border-t border-slate-100`, cancel ghost + save indigo, `min-h-[44px]` for tap targets
- **Key field**: `font-mono` input with helper hint below; readonly on edit page
- **No emoji flags**: replaced with pill-coded locale badges (RU/KZ/CN) matching show.blade.php

## Seeder keys added (26 total, all `admin` group)

`admin.translation_create_title`, `admin.translation_create_desc`, `admin.translation_edit_title`, `admin.translation_edit_desc`, `admin.translation_section_meta`, `admin.translation_section_locales`, `admin.translation_key_placeholder`, `admin.translation_key_hint`, `admin.translation_key_readonly_hint`, `admin.translation_group_placeholder`, `admin.translation_group_hint`, `admin.translation_description_label`, `admin.translation_description_placeholder`, `admin.translation_description_hint`, `admin.translation_ru_label`, `admin.translation_kz_label`, `admin.translation_cn_label`, `admin.translation_ru_placeholder`, `admin.translation_kz_placeholder`, `admin.translation_cn_placeholder`, `admin.translation_chars_suffix`, `admin.translation_optional_badge`, `admin.translation_error_summary`, `admin.translation_error_count`, `admin.translation_btn_save`, `admin.translation_btn_cancel`

KZ and CN strings marked `// TODO: verify with native speaker` throughout.

## Render test

`OK 30153 bytes` — view compiled and rendered cleanly via tinker with `ViewErrorBag` pre-shared (required because `$errors` is middleware-injected; in tinker it must be shared manually before render).

**Why:** `$errors->any()` on line 1 of the error banner block calls `.all()` on a null bag when rendered outside a real HTTP request. Not a view bug — expected tinker behavior.

**How to apply:** When running view render tests in tinker, always `view()->share('errors', new \Illuminate\Support\ViewErrorBag)` before calling `->render()`.

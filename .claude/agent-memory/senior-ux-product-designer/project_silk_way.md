---
name: Silk Way App Design System
description: Core design conventions for the Silk Way Laravel logistics SaaS — palette, layout patterns, component conventions
type: project
---

Silk Way is a Laravel logistics SaaS app serving admins, warehouse employees, and drivers.

**Why:** Design decisions must remain consistent with the existing system already in use by real users.

**How to apply:** Match these conventions exactly when designing new screens.

## Tech Stack
- Tailwind CSS (CDN), Alpine.js (CDN), Font Awesome 6 (CDN)
- System font stack: `-apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif`
- Background: `bg-slate-50`

## Layout Patterns
- **Admin/Warehouse**: Dark sidebar (`bg-slate-900`) + top nav + main content area. Sidebar collapses to icon-only on toggle.
- **Driver**: Top nav (white, sticky) + bottom tab bar (fixed, `md:hidden`) + centered max-w-5xl content.
- Flash messages: emerald (success), rose (error), each `rounded-xl` with icon.

## Card Conventions
- `bg-white rounded-xl border border-slate-200 shadow-sm` — the standard card
- Hover: `hover:shadow-md transition-shadow`
- Card header variant: `bg-gradient-to-br from-slate-50 to-slate-100` with `border-b border-slate-200`
- Dashed add-new card: `border-2 border-dashed border-slate-300 hover:border-indigo-400`

## Badge/Status Conventions
- **Pending/Amber**: `bg-amber-100 text-amber-700`
- **Approved/Emerald**: `bg-emerald-100 text-emerald-700`
- **Rejected/Rose**: `bg-rose-100 text-rose-700`
- **Neutral/Slate**: `bg-slate-200 text-slate-600`
- **Admin/Purple**: `bg-purple-100 text-purple-700`
- **Driver/Emerald**: `bg-emerald-100 text-emerald-700`
- All badges: `inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium`

## Color Palette
- Primary action: `indigo-600` (hover: `indigo-700`)
- Icon containers: `bg-indigo-100`, `bg-emerald-100`, `bg-amber-100`, `bg-rose-100`, `bg-purple-100`
- Body text: `text-slate-900` (headings), `text-slate-700` (body), `text-slate-500` (secondary), `text-slate-400` (tertiary)
- Borders: `border-slate-200` standard, `border-slate-100` subtle

## Button Conventions
- Primary: `bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg px-4 py-2`
- Secondary: `bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg`
- Small action: `px-3 py-1.5 text-xs font-medium rounded-lg`
- Danger: `bg-rose-600 hover:bg-rose-700 text-white` or `bg-rose-50 border border-rose-200 text-rose-700`

## Typography
- Page title: `text-2xl font-bold text-slate-900`
- Section label: `text-xs font-medium text-slate-500 uppercase tracking-wider`
- Table header: `text-xs font-medium text-slate-500 uppercase tracking-wider`
- Card title: `text-base font-bold text-slate-900`

## Grid Patterns
- Stats: `grid grid-cols-1 sm:grid-cols-3 gap-5`
- Cards: `grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5`
- Quick actions: `grid grid-cols-2 sm:grid-cols-4 gap-4`

## Icon Usage (Font Awesome 6)
- `fa-route` — Silk Way logo
- `fa-box` — cargo
- `fa-truck` — vehicles/transport
- `fa-clipboard-list` — applications
- `fa-check-circle` — success/verified
- `fa-clock` — pending
- `fa-times` / `fa-ban` — rejected/disabled
- `fa-users` — user management
- `fa-car` — driver's car

---
name: school-saas-design-system
description: Design system tokens and UI patterns for School SaaS — color palette, typography, spacing, shadows, radii, status colors, dark mode, responsive rules, component patterns. Use when creating Vue components, pages, layouts, or any UI code to ensure visual consistency across the platform.
---

# School SaaS Design System

Tailwind CSS 4 + Reka UI (shadcn-vue style). Green-based default palette. Dark mode supported. Per-school branding via CSS custom properties.

## CSS Theme Setup (Tailwind CSS 4)

Define all design tokens in `resources/css/app.css` using `@theme`:

```css
@import "tailwindcss";

@theme {
  /* --- Green Default Palette --- */
  --color-primary-50: #f0fdf4;
  --color-primary-100: #dcfce7;
  --color-primary-200: #bbf7d0;
  --color-primary-300: #86efac;
  --color-primary-400: #4ade80;
  --color-primary-500: #22c55e;
  --color-primary-600: #16a34a;
  --color-primary-700: #15803d;
  --color-primary-800: #166534;
  --color-primary-900: #14532d;
  --color-primary-950: #052e16;

  /* --- Neutral (Slate) --- */
  --color-neutral-50: #f8fafc;
  --color-neutral-100: #f1f5f9;
  --color-neutral-200: #e2e8f0;
  --color-neutral-300: #cbd5e1;
  --color-neutral-400: #94a3b8;
  --color-neutral-500: #64748b;
  --color-neutral-600: #475569;
  --color-neutral-700: #334155;
  --color-neutral-800: #1e293b;
  --color-neutral-900: #0f172a;
  --color-neutral-950: #020617;

  /* --- Semantic Status Colors --- */
  --color-success-50: #f0fdf4;
  --color-success-500: #22c55e;
  --color-success-700: #15803d;
  --color-warning-50: #fffbeb;
  --color-warning-500: #f59e0b;
  --color-warning-700: #b45309;
  --color-danger-50: #fef2f2;
  --color-danger-500: #ef4444;
  --color-danger-700: #b91c1c;
  --color-info-50: #eff6ff;
  --color-info-500: #3b82f6;
  --color-info-700: #1d4ed8;

  /* --- Typography --- */
  --font-sans: "Inter", "Noto Sans", ui-sans-serif, system-ui, sans-serif;
  --font-mono: "JetBrains Mono", ui-monospace, monospace;

  --text-xs: 0.75rem;     /* 12px — captions, badges */
  --text-sm: 0.875rem;    /* 14px — body small, table cells */
  --text-base: 1rem;      /* 16px — body default */
  --text-lg: 1.125rem;    /* 18px — section titles */
  --text-xl: 1.25rem;     /* 20px — page subtitles */
  --text-2xl: 1.5rem;     /* 24px — page titles */
  --text-3xl: 1.875rem;   /* 30px — dashboard hero numbers */

  /* --- Spacing Scale --- */
  /* Use Tailwind default (4px base). Key semantic sizes: */
  /* xs: 4px (0.25rem)  — tight gaps, icon padding */
  /* sm: 8px (0.5rem)   — inline spacing, badge padding */
  /* md: 16px (1rem)    — card padding, form gaps */
  /* lg: 24px (1.5rem)  — section spacing */
  /* xl: 32px (2rem)    — page padding */
  /* 2xl: 48px (3rem)   — section dividers */

  /* --- Border Radius --- */
  --radius-sm: 0.25rem;   /* 4px — badges, chips */
  --radius-md: 0.5rem;    /* 8px — cards, inputs, buttons */
  --radius-lg: 0.75rem;   /* 12px — dialogs, sheets */
  --radius-xl: 1rem;      /* 16px — large cards, modals */
  --radius-full: 9999px;  /* avatars, pills */

  /* --- Shadows --- */
  --shadow-xs: 0 1px 2px rgb(0 0 0 / 0.05);
  --shadow-sm: 0 1px 3px rgb(0 0 0 / 0.1), 0 1px 2px rgb(0 0 0 / 0.06);
  --shadow-md: 0 4px 6px rgb(0 0 0 / 0.1), 0 2px 4px rgb(0 0 0 / 0.06);
  --shadow-lg: 0 10px 15px rgb(0 0 0 / 0.1), 0 4px 6px rgb(0 0 0 / 0.05);

  /* --- Z-Index Scale --- */
  --z-dropdown: 50;
  --z-sticky: 100;
  --z-overlay: 200;
  --z-modal: 300;
  --z-toast: 400;
}
```

## Per-School Branding Override

Schools set `primary_color` and `accent_color` in their branding settings. Injected via Inertia middleware as CSS custom properties on `<html>`:

```css
/* Injected dynamically — overrides @theme defaults */
:root {
  --color-brand-primary: var(--school-primary, #16a34a);
  --color-brand-accent: var(--school-accent, #0ea5e9);
}
```

Use `brand-primary` for school-identity elements (topbar, sidebar active, branded buttons). Use the `primary-*` scale for standard UI components.

## Dark Mode

Strategy: class-based toggle (`dark` class on `<html>`). Respect `prefers-color-scheme` as default, allow manual override stored in `localStorage`.

```css
:root {
  --bg-page: var(--color-neutral-50);
  --bg-card: #ffffff;
  --bg-sidebar: #ffffff;
  --text-primary: var(--color-neutral-900);
  --text-secondary: var(--color-neutral-500);
  --border-default: var(--color-neutral-200);
}

.dark {
  --bg-page: var(--color-neutral-950);
  --bg-card: var(--color-neutral-900);
  --bg-sidebar: var(--color-neutral-900);
  --text-primary: var(--color-neutral-50);
  --text-secondary: var(--color-neutral-400);
  --border-default: var(--color-neutral-800);
}
```

Always use semantic variables for surfaces/text — NEVER hardcode `bg-white` or `text-black`.

### Dark Mode Rules

| Do | Don't |
|----|-------|
| `bg-(--bg-card)` or Tailwind `dark:` variant | Hardcode `bg-white` |
| `text-(--text-primary)` | Hardcode `text-gray-900` |
| Test both modes when building a component | Only test light mode |
| Use `dark:bg-neutral-900` for simple cases | Create custom CSS when Tailwind dark variants suffice |

## Semantic Color Usage

| Context | Light | Dark | Usage |
|---------|-------|------|-------|
| Success / Paid / Present | `success-500` on `success-50` bg | `success-500` on `success-950` bg | Payment complete, attendance present |
| Warning / Partial / Pending | `warning-500` on `warning-50` bg | `warning-500` on `warning-950` bg | Partial payment, pending approval |
| Danger / Overdue / Absent | `danger-500` on `danger-50` bg | `danger-500` on `danger-950` bg | Overdue fees, absent, errors |
| Info / Neutral info | `info-500` on `info-50` bg | `info-500` on `info-950` bg | Announcements, help text |
| Muted / Not yet due | `neutral-500` on `neutral-100` bg | `neutral-400` on `neutral-800` bg | Upcoming fees, inactive items |

## Typography Rules

| Element | Size | Weight | Color |
|---------|------|--------|-------|
| Page title | `text-2xl` (24px) | `font-bold` (700) | `text-primary` |
| Section title | `text-lg` (18px) | `font-semibold` (600) | `text-primary` |
| Body text | `text-sm` (14px) | `font-normal` (400) | `text-primary` |
| Table cells | `text-sm` (14px) | `font-normal` (400) | `text-primary` |
| Labels | `text-sm` (14px) | `font-medium` (500) | `text-secondary` |
| Captions / Badges | `text-xs` (12px) | `font-medium` (500) | varies |
| Dashboard hero numbers | `text-3xl` (30px) | `font-bold` (700) | `text-primary` |
| Empty state message | `text-sm` (14px) | `font-normal` (400) | `text-secondary` |

**Font loading:** Inter via Google Fonts with `font-display: swap`. Fallback to Noto Sans (Indonesian character support). System fonts as last resort.

## Spacing Conventions

| Context | Value | Tailwind Class |
|---------|-------|---------------|
| Card internal padding | 16px | `p-4` |
| Card internal padding (mobile) | 12px | `p-3` |
| Gap between form fields | 16px | `gap-4` or `space-y-4` |
| Gap between cards in a grid | 16px | `gap-4` |
| Page container horizontal padding | 16-24px | `px-4 lg:px-6` |
| Section vertical spacing | 24px | `mt-6` or `space-y-6` |
| Inline element gap (icon + text) | 8px | `gap-2` |
| Button internal padding | 8px 16px | `px-4 py-2` |
| Badge internal padding | 2px 8px | `px-2 py-0.5` |
| Touch target minimum (parent mobile) | 44px | `min-h-11` |

## Border & Radius Conventions

| Component | Radius | Border |
|-----------|--------|--------|
| Buttons | `rounded-md` (8px) | None (filled) or `border border-(--border-default)` (outline) |
| Inputs / Selects | `rounded-md` (8px) | `border border-(--border-default)` |
| Cards | `rounded-lg` (12px) | `border border-(--border-default)` |
| Dialogs / Sheets | `rounded-xl` (16px) | None (use shadow) |
| Badges / Chips | `rounded-sm` (4px) | None (use bg fill) |
| Avatars | `rounded-full` | None |
| DataTable rows | None (`rounded-none`) | Bottom border `border-b` |

## Shadow Conventions

| Component | Shadow |
|-----------|--------|
| Cards (default) | `shadow-xs` or no shadow (use border instead) |
| Cards (elevated / floating) | `shadow-sm` |
| Dropdowns / Popovers | `shadow-md` |
| Dialogs / Modals | `shadow-lg` |
| Sidebar (desktop) | `shadow-sm` or right border |
| Bottom nav (parent mobile) | `shadow-[0_-2px_8px_rgb(0_0_0/0.08)]` (top shadow) |

In dark mode, reduce shadow intensity — borders become more important for separation.

## Responsive Breakpoints

| Breakpoint | px | Target |
|------------|-----|--------|
| Default (mobile-first) | 0+ | Parent phones (budget Android) |
| `sm` | 640px | Large phones |
| `md` | 768px | Tablets (teacher) |
| `lg` | 1024px | Small laptops |
| `xl` | 1280px | Desktop (admin) |

### Layout Patterns by Breakpoint

| Pattern | Mobile (< md) | Tablet (md) | Desktop (lg+) |
|---------|--------------|-------------|----------------|
| DataTable | Card list | Table | Table with sidebar filters |
| Navigation | Bottom tabs (parent) | Sidebar collapsed | Sidebar expanded |
| Forms | Single column, full width | Two columns | Two columns with sidebar |
| Dashboard stats | 2-col grid | 3-col grid | 4-col grid |
| Page padding | `px-4` | `px-6` | `px-6` |

## Animation Rules

Budget Android phones (Redmi 9-class) are the primary target. Animations must be performant.

| Allowed | Duration | Property |
|---------|----------|----------|
| Page transitions (Inertia) | 150ms | `opacity`, `transform` |
| List item enter/exit | 150ms | `opacity`, `transform` |
| Button press feedback | 100ms | `scale`, `opacity` |
| Dropdown/dialog open | 150ms | `opacity`, `transform` |
| Skeleton loading pulse | 1.5s | `opacity` (CSS animation) |

| Forbidden |
|-----------|
| Decorative animations (bouncing icons, floating elements) |
| Parallax scrolling |
| Complex SVG animations |
| Auto-playing carousels |
| Animations on elements not in viewport |

Use `motion-vue` only for page transitions and list animations. Prefer CSS transitions for simple hover/focus states.

## Icon System

Use [Lucide Icons](https://lucide.dev/) via `lucide-vue-next`. Consistent, clean, MIT-licensed.

| Rule | Value |
|------|-------|
| Default size | 16px (`w-4 h-4`) for inline, 20px (`w-5 h-5`) for buttons |
| Stroke width | 2 (default) |
| Color | `currentColor` (inherits text color) |
| Large feature icons | 24px (`w-6 h-6`) — empty states, feature cards |

## Component Pattern Summary

Quick reference for common patterns. See [reference.md](reference.md) for full examples.

| Component | Key Rules |
|-----------|-----------|
| **Card** | `rounded-lg border bg-(--bg-card) p-4`, no excessive shadows |
| **Button (Primary)** | `bg-primary-600 hover:bg-primary-700 text-white rounded-md px-4 py-2 text-sm font-medium` |
| **Button (Outline)** | `border border-(--border-default) rounded-md px-4 py-2 text-sm font-medium hover:bg-neutral-50 dark:hover:bg-neutral-800` |
| **Button (Danger)** | `bg-danger-600 hover:bg-danger-700 text-white` — destructive actions only |
| **Input** | `rounded-md border border-(--border-default) px-3 py-2 text-sm bg-transparent focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500` |
| **StatusBadge** | `inline-flex items-center rounded-sm px-2 py-0.5 text-xs font-medium` + semantic color bg/text |
| **Empty State** | Centered, icon (24px muted), title (text-sm font-medium), description (text-sm text-secondary), optional CTA button |
| **Loading** | Skeleton shimmer (pulse animation), match the shape of content being loaded |
| **Toast** | Top-right (desktop), top-center (mobile). Auto-dismiss 5s. Semantic colors. |
| **DataTable** | Cards on mobile, table on desktop. Sticky header. Zebra striping optional (prefer clean). |

## Accessibility Baseline

| Rule | Implementation |
|------|---------------|
| Color contrast | WCAG AA minimum (4.5:1 text, 3:1 large text) |
| Focus indicators | `focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2` |
| Touch targets | Minimum 44x44px for parent mobile |
| Keyboard navigation | All interactive elements reachable via Tab |
| Screen reader | Use Reka UI primitives (handles ARIA automatically) |
| Reduced motion | `@media (prefers-reduced-motion: reduce)` — disable all transitions |

## Additional Resources

- Full component pattern examples: [reference.md](reference.md)
- Frontend conventions: [school-saas-frontend/SKILL.md](../school-saas-frontend/SKILL.md)
- Architecture overview: [architecture.md](../../../docs/architecture.md)

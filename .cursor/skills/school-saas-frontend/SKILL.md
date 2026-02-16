---
name: school-saas-frontend
description: School SaaS frontend conventions — Vue 3, Inertia.js, TypeScript, Tailwind CSS 4, Reka UI, layout strategy, component structure, performance for low-end devices. Use when creating Vue pages, components, composables, or any frontend code for the school platform.
---

# School SaaS Frontend Conventions

Vue 3 + TypeScript + Inertia.js (SSR enabled) + Tailwind CSS 4. Mobile-first for parents, desktop-optimized for admins.

## Directory Structure

```
resources/js/
├── app.ts                          # Inertia app bootstrap
├── ssr.ts                          # SSR entry point
├── types/
│   ├── index.d.ts                  # Global types
│   ├── models.d.ts                 # Auto-generated from Laravel (based/laravel-typescript)
│   ├── inertia.d.ts                # Inertia shared data types
│   └── enums.ts                    # Mirror of PHP enums
├── Layouts/
│   ├── AdminLayout.vue             # Sidebar + topbar (admin/staff, desktop)
│   ├── TeacherLayout.vue           # Simplified sidebar (tablet + desktop)
│   ├── ParentLayout.vue            # Bottom nav, mobile-first
│   ├── AuthLayout.vue              # Login/register pages
│   └── components/                 # Sidebar, BottomNav, TopBar, BreadCrumb
├── Pages/                          # Inertia pages organized by domain
│   ├── Auth/                       # Login, OtpLogin
│   ├── Dashboard/                  # AdminDashboard, TeacherDashboard, ParentDashboard
│   ├── Student/                    # Index, Show, Create, Import
│   ├── Attendance/                 # Index, Mark, Report
│   ├── Finance/                    # FeeManagement, PaymentDashboard, PaymentHistory
│   ├── Parent/                     # ChildAttendance, PaySpp, Grades, Announcements
│   └── ...
├── Components/
│   ├── ui/                         # Reka UI based (shadcn-vue style)
│   ├── DataTable/                  # DataTable, Pagination, Filter, ColumnHeader
│   ├── Forms/                      # FormField, ExcelImporter
│   └── Shared/                     # StatusBadge, CurrencyDisplay, DateDisplay, EmptyState
└── Composables/
    ├── usePermission.ts
    ├── useAttendance.ts
    ├── useCurrency.ts              # Rp formatting
    ├── useDebounce.ts
    ├── useOnlineStatus.ts          # Offline detection for PWA
    └── usePageTransition.ts
```

## Routing: Wayfinder (NOT Ziggy)

Use Wayfinder for type-safe route generation. Never use the legacy `route()` method from Ziggy.

```typescript
// Wayfinder generates typed route functions
import { students } from '@/actions/student'

// Usage in components
router.get(students.index.url())
router.get(students.show.url({ student: id }))
```

## Layout Strategy by Role

| Layout | Target | Navigation | Focus |
|--------|--------|------------|-------|
| AdminLayout | Desktop, responsive | Full sidebar | Data-heavy screens |
| TeacherLayout | Tablet + desktop | Simplified sidebar | Daily tasks (attendance, grading) |
| ParentLayout | Mobile-first | Bottom nav (4-5 tabs) | Large touch targets, minimal data |

Layout is determined by the user's active role. Teacher-parent can switch via role switcher UI.

## DataTable Component (Critical)

Used across 20+ pages. Built on TanStack Table (headless):

```vue
<DataTable
  :data="students.data"
  :columns="columns"
  :pagination="students.meta"
  :filters="filters"
  searchable
  :bulk-actions="['export', 'delete']"
  @page-change="(page) => router.get(students.index.url(), { page })"
/>
```

Features: server-side pagination (Inertia), sortable columns, filterable, searchable, bulk actions, responsive (cards on mobile, table on desktop), empty state.

## UI Components (Reka UI / shadcn-vue style)

Base components in `Components/ui/`: Button, Input, Select, Dialog, Sheet, etc.
All headless and accessible. Follow shadcn-vue patterns.

### Shared Components
- `StatusBadge` — color-coded status labels
- `CurrencyDisplay` — Rp formatting (Rupiah)
- `DateDisplay` — WIB timezone display
- `EmptyState` — consistent empty states
- `LoadingOverlay` — loading indicators

## Performance for Low-End Devices

Target: budget Android phones (Redmi 9-class) on slow 3G.

| Strategy | Implementation |
|----------|---------------|
| Code splitting | Inertia lazy-loads pages (each page = separate chunk) |
| SSR | Enabled for faster initial paint on slow 3G |
| Image optimization | Media library auto-resizes (200px list, 80px avatar) |
| Animation discipline | ONLY page transitions, list enter/exit, button feedback. NO decorative animations. |
| Bundle budget | Target < 200KB initial JS (gzipped). Monitor with `vite-plugin-visualizer` |

## Indonesian Localization

- Currency: always format as Rupiah (Rp) using `useCurrency` composable
- Dates: dayjs with locale `id`, timezone `Asia/Jakarta` (WIB)
- UI language: Bahasa Indonesia
- Flash messages in Bahasa: `'Absensi berhasil disimpan.'`, `'Data siswa berhasil diperbarui.'`

## Offline Support (PWA)

- `useOnlineStatus` composable detects offline state
- "Simpan saat online" button stores data in localStorage
- Service Worker background sync when connection restores
- Critical for attendance marking (teachers may lose internet)

## Additional Resources

- Full architecture document: [architecture.md](../../../docs/architecture.md)

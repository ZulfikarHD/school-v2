# Sprint 0 — Infrastructure & Project Skeleton

> **Epic Type:** Foundation (not a feature module)
> **Phase:** 0
> **Priority:** CORE — blocks everything
> **Sprint Target:** Sprint 0 (Week 1–2)
> **Total Story Points:** 24 SP
> **Dependencies:** None (this is the first thing built)

---

## Epic Overview

Set up the entire development foundation: Docker environment, Laravel 12 + Vue 3 + Inertia.js project skeleton, Tailwind CSS 4, CI/CD pipeline, and base infrastructure. Every subsequent Epic depends on this being complete.

---

## User Stories

### US-S0.1: Docker Development Environment

**As a** developer,
**I want** a fully containerized local development environment,
**so that** the project runs consistently across machines and mirrors production.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [x] `docker compose up` starts all services (app, nginx, postgres, redis, meilisearch)
- [x] Hot-reload works for both PHP and Vue/Vite
- [x] PostgreSQL 16 is configured with correct extensions
- [x] Redis 7 is available for cache/session/queue
- [x] Meilisearch container is running and accessible
- [x] MinIO (S3-compatible) is running for local file storage
- [x] All ports are configurable via `.env`

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `docker-compose.yml` with all services | Infrastructure | 3h |
| 2 | Create `docker-compose.dev.yml` override for development | Infrastructure | 1h |
| 3 | Create PHP 8.3 Dockerfile (`docker/app/Dockerfile`) with extensions (pdo_pgsql, redis, gd, intl, zip, pcntl) | Infrastructure | 2h |
| 4 | Create Nginx Dockerfile + `default.conf` (`docker/nginx/`) | Infrastructure | 1h |
| 5 | Create Scheduler Dockerfile (`docker/scheduler/Dockerfile`) — same as app, runs `schedule:work` | Infrastructure | 0.5h |
| 6 | Create Horizon Dockerfile (`docker/horizon/Dockerfile`) — same as app, runs `horizon` | Infrastructure | 0.5h |
| 7 | Create `.env.example` with all Docker-related variables | Infrastructure | 0.5h |
| 8 | Document setup steps in README.md | Infrastructure | 0.5h |

---

### US-S0.2: Laravel 12 Project Initialization

**As a** developer,
**I want** a clean Laravel 12 project with all core packages installed,
**so that** I have the correct foundation for the modular monolith.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Laravel 12 installed with PHP 8.3+ target
- [ ] PostgreSQL configured as default database
- [ ] Redis configured for cache, session, and queue
- [ ] All core packages installed: `stancl/tenancy`, `spatie/laravel-permission`, `spatie/laravel-media-library`, `spatie/laravel-activitylog`, `spatie/laravel-data`, `maatwebsite/laravel-excel`, `laravel/pennant`, `spatie/laravel-backup`, `based/laravel-typescript`
- [ ] Wayfinder installed and configured (not Ziggy)
- [ ] Laravel Horizon installed for queue management
- [ ] Laravel Reverb installed for WebSocket support
- [ ] `php artisan test` runs with zero errors on a blank install

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Install Laravel 12 via composer | Backend | 0.5h |
| 2 | Configure database (PostgreSQL), cache, session, queue (Redis) | Backend | 1h |
| 3 | Install all Laravel packages from tech stack | Backend | 2h |
| 4 | Configure Wayfinder for type-safe route generation | Backend | 0.5h |
| 5 | Install & configure Laravel Horizon | Backend | 0.5h |
| 6 | Install & configure Laravel Reverb | Backend | 0.5h |

---

### US-S0.3: Vue 3 + Inertia.js + Tailwind CSS 4 Setup

**As a** developer,
**I want** the frontend stack configured with Vue 3, TypeScript, Inertia.js (SSR), and Tailwind CSS 4,
**so that** I can build pages using the SPA-like Inertia pattern from the start.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Vue 3 + TypeScript configured with strict mode
- [ ] Inertia.js installed with SSR enabled
- [ ] Tailwind CSS 4 configured
- [ ] Reka UI installed as headless component library
- [ ] Vite configured with HMR working inside Docker
- [ ] `yarn build` produces optimized production bundles
- [ ] SSR entry point (`ssr.ts`) works correctly
- [ ] `@vueuse/core`, `dayjs`, `motion-vue` installed
- [ ] `vite-plugin-visualizer` configured for bundle size monitoring (target < 200KB initial JS gzipped)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Install Vue 3 + TypeScript + Inertia.js via Yarn | Frontend | 1h |
| 2 | Configure Vite for HMR inside Docker containers | Frontend | 1h |
| 3 | Enable Inertia SSR | Frontend | 1h |
| 4 | Install & configure Tailwind CSS 4 | Frontend | 0.5h |
| 5 | Install Reka UI, @vueuse/core, dayjs (locale: id), motion-vue, Chart.js + vue-chartjs, TanStack Table (Vue) | Frontend | 1h |
| 6 | Create `resources/js/types/` directory with base type definitions | Frontend | 0.5h |
| 7 | Install & configure `vite-plugin-visualizer` for bundle size monitoring | Frontend | 0.5h |

---

### US-S0.4: Base Layout Components

**As a** developer,
**I want** the three layout shells (AdminLayout, TeacherLayout, ParentLayout, AuthLayout) created as empty scaffolds,
**so that** all subsequent pages have consistent navigation structure.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [x] `AdminLayout.vue` — sidebar + topbar shell (desktop-first)
- [x] `TeacherLayout.vue` — simplified sidebar shell (tablet + desktop)
- [x] `ParentLayout.vue` — bottom navigation shell (mobile-first)
- [x] `AuthLayout.vue` — centered card layout for login/register
- [x] Layout auto-selected based on user's active role
- [x] Responsive breakpoints work correctly
- [x] Dark mode NOT implemented (keep it simple for MVP)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `AdminLayout.vue` with sidebar + topbar placeholder | Frontend | 2h |
| 2 | Create `TeacherLayout.vue` with simplified sidebar | Frontend | 1.5h |
| 3 | Create `ParentLayout.vue` with bottom nav (4–5 tabs) | Frontend | 2h |
| 4 | Create `AuthLayout.vue` centered card | Frontend | 0.5h |
| 5 | Create layout sub-components: Sidebar, BottomNav, TopBar, BreadCrumb | Frontend | 2h |
| 6 | Implement layout routing logic based on active role | Frontend | 1h |

---

### US-S0.5: CI/CD Pipeline

**As a** developer,
**I want** a GitHub Actions CI pipeline that runs linting, tests, and builds on every push,
**so that** code quality is enforced automatically.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] GitHub Actions workflow triggers on push to `main` and pull requests
- [ ] Pipeline runs: PHPStan, Pest (PHP tests), TypeScript strict build, `yarn build`
- [ ] Pipeline fails if any check fails
- [ ] Docker image build step included
- [ ] Tenant isolation scan included (Pest test: all tenant models must use `BelongsToSchool` trait)
- [ ] Pipeline completes in < 10 minutes

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `.github/workflows/ci.yml` with PHPStan + Pest | Infrastructure | 1.5h |
| 2 | Add TypeScript strict mode check step | Infrastructure | 0.5h |
| 3 | Add `yarn build` step | Infrastructure | 0.5h |
| 4 | Add Docker image build step | Infrastructure | 1h |
| 5 | Configure caching (composer, yarn, Docker layers) for speed | Infrastructure | 0.5h |
| 6 | Create tenant isolation CI scan (Pest test that scans all Eloquent models and asserts tenant models use `BelongsToSchool` trait) | Backend | 1h |

---

### US-S0.6: Base UI Component Library

**As a** developer,
**I want** the foundational Reka UI-based components (Button, Input, Select, Dialog, Sheet) scaffolded,
**so that** all subsequent pages use consistent, accessible UI primitives.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] `Components/ui/` directory with base components following shadcn-vue patterns
- [ ] Button component with variants (primary, secondary, destructive, outline, ghost)
- [ ] Input, Textarea, Select components with form field wrapper
- [ ] Dialog and Sheet components for modals/drawers
- [ ] StatusBadge, CurrencyDisplay, DateDisplay, EmptyState shared components
- [ ] All components are accessible (keyboard navigation, ARIA)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Scaffold `Components/ui/` with Reka UI base components | Frontend | 2h |
| 2 | Create shared components: StatusBadge, CurrencyDisplay, DateDisplay | Frontend | 1.5h |
| 3 | Create EmptyState and LoadingOverlay components | Frontend | 0.5h |
| 4 | Create `useCurrency` and `useOnlineStatus` composables | Frontend | 1h |

---

### US-S0.7: DataTable Component

**As a** developer,
**I want** a reusable, headless DataTable component built on TanStack Table,
**so that** all data-heavy pages (20+ pages) share a consistent, performant table with server-side pagination.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] `DataTable.vue` component built on TanStack Table (headless)
- [ ] Server-side pagination via Inertia (accepts `pagination` prop from Laravel paginator meta)
- [ ] Sortable columns with sort indicators
- [ ] Filterable with search input
- [ ] Bulk action support (select rows, apply action)
- [ ] Responsive: table on desktop, card list on mobile
- [ ] Empty state when no data
- [ ] Sub-components: `DataTablePagination.vue`, `DataTableFilter.vue`, `DataTableColumnHeader.vue`

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `Components/DataTable/DataTable.vue` with TanStack Table integration | Frontend | 3h |
| 2 | Create `DataTablePagination.vue` with page navigation | Frontend | 1h |
| 3 | Create `DataTableFilter.vue` with search + column filters | Frontend | 1h |
| 4 | Create `DataTableColumnHeader.vue` with sortable indicator | Frontend | 0.5h |
| 5 | Implement responsive layout (table → card on mobile) | Frontend | 1.5h |
| 6 | Add bulk selection and action support | Frontend | 1h |

---

## Technical Notes

- **Docker structure** follows the `school-saas-infrastructure` skill: `docker/` directory with separate Dockerfiles for app, nginx, scheduler, horizon
- **Wayfinder** replaces Ziggy — never install or use Ziggy's `route()` method
- **SSR** is enabled from day one to ensure fast initial paint on slow connections
- **Tailwind CSS 4** — use the new v4 configuration format
- **`based/laravel-typescript`** will auto-generate TypeScript types from Eloquent models starting from M1

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Docker HMR issues on Windows | Slow DX | Use WSL2 for development, configure Vite polling |
| Package version conflicts | Blocks setup | Pin versions in `composer.json` and `package.json` |
| SSR adds complexity | Debugging difficulty | Keep SSR simple, test early, can disable per-route if needed |

---

## Definition of Done (Epic Level)

- [ ] `docker compose up` starts entire stack from scratch
- [ ] `php artisan test` passes
- [ ] `yarn build` succeeds
- [ ] CI pipeline passes on GitHub Actions
- [ ] A "Hello World" Inertia page renders with each layout
- [ ] All three layouts display correctly on their target devices
- [ ] README.md documents local setup steps

---

### Related Files

- **Scrum Overview:** [`00_SCRUM_OVERVIEW.md`](00_SCRUM_OVERVIEW.md)
- **Next Epic:** [`M01_SCHOOL_PROFILE.md`](M01_SCHOOL_PROFILE.md)

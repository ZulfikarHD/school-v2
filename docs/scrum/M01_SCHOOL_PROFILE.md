# Epic M1 — School Profile & Multi-Tenancy

> **Epic ID:** M1
> **Phase:** 1 (MVP)
> **Priority:** CORE
> **Sprint Target:** Sprint 1
> **Total Story Points:** 21 SP
> **Dependencies:** Sprint 0 (Infrastructure)
> **Depends On:** —
> **Blocks:** All other modules (M2–M22)

---

## Epic Overview

Establish the multi-tenant foundation where each school has its own isolated environment. This is the **first functional module** — every other module depends on school context being resolved correctly.

---

## User Stories

### US-1.1: School Identity Management

**As an** Admin (Tata Usaha),
**I want** to configure my school's identity (name, logo, NPSN, address, vision/mission),
**so that** the platform is branded for my school.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] School profile form with fields: name, NPSN, address, phone, email, vision, mission
- [ ] Logo upload with preview (max 2MB, jpg/png)
- [ ] Profile data persists and is displayed in layouts (topbar, sidebar)
- [ ] Validation: NPSN format (8 digits), required fields enforced

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `schools` migration with identity columns + JSONB `settings` | Backend | 1h |
| 2 | Create `School` model with `Searchable` trait, media library registration | Backend | 1h |
| 3 | Create `SchoolService` with `updateProfile()` method | Backend | 1h |
| 4 | Create `SchoolProfileController` with `edit()` and `update()` | Backend | 1h |
| 5 | Create `UpdateSchoolProfileRequest` form request | Backend | 0.5h |
| 6 | Create `SchoolProfile/Edit.vue` page with form fields | Frontend | 2h |
| 7 | Integrate logo upload with preview using `spatie/media-library` | Frontend | 1h |
| 8 | Display school name + logo in AdminLayout topbar | Frontend | 0.5h |
| 9 | Configure `based/laravel-typescript` auto-generation command and verify TypeScript types are generated from `School` model | Backend | 0.5h |

---

### US-1.2: Custom Subdomain Routing

**As the** System,
**I want** each school to be accessible via its own subdomain (e.g., `sdnegeri5.platform.id`),
**so that** tenant context is automatically resolved from the URL.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] School slug is set during school creation (e.g., `sdnegeri5`)
- [ ] Visiting `{slug}.platform.id` resolves the correct school tenant
- [ ] Visiting a non-existent subdomain shows a 404 page
- [ ] SuperAdmin routes on `admin.platform.id` bypass tenancy
- [ ] All tenant routes are wrapped with tenancy middleware
- [ ] Data isolation verified: School A cannot access School B data

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Install & configure `stancl/tenancy` in single-database mode | Backend | 2h |
| 2 | Create `BelongsToSchool` trait with Global Scope + auto-fill `school_id` on creating | Backend | 1.5h |
| 3 | Configure `InitializeTenancyBySubdomain` middleware | Backend | 1h |
| 4 | Set up tenant route group (`routes/tenant.php`) vs. central routes | Backend | 1h |
| 5 | Create SuperAdmin route group on `admin.platform.id` | Backend | 0.5h |
| 6 | Configure cache key prefixing per tenant (`tenant_{school_id}_`) | Backend | 0.5h |
| 7 | Configure queue job tenant tagging | Backend | 0.5h |
| 8 | Write feature test: School A user cannot see School B data | Backend | 1h |
| 9 | Configure Nginx wildcard subdomain routing | Infrastructure | 1h |

---

### US-1.3: Academic Year Management

**As an** Admin,
**I want** to create and manage academic years (tahun ajaran) and semesters,
**so that** all school data is organized by academic period.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Admin can create academic year (e.g., "2025/2026") with start/end dates
- [ ] Each academic year has 2 semesters (Ganjil, Genap)
- [ ] One academic year is marked as "active" at a time
- [ ] Switching active year changes the context for attendance, grades, etc.
- [ ] Old academic years become read-only
- [ ] Academic year selector visible in admin topbar

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `academic_years` migration (school_id, name, start_date, end_date, is_active) | Backend | 0.5h |
| 2 | Create `semesters` migration (academic_year_id, type enum, start_date, end_date, is_active) | Backend | 0.5h |
| 3 | Create `AcademicYear` and `Semester` models with `BelongsToSchool` | Backend | 0.5h |
| 4 | Create `AcademicYearService` (create, activate, deactivate, getActive) | Backend | 1.5h |
| 5 | Create `AcademicYearController` CRUD | Backend | 1h |
| 6 | Create `AcademicYear/Index.vue` — list with active indicator | Frontend | 1.5h |
| 7 | Create `AcademicYear/Create.vue` — form with date pickers | Frontend | 1h |
| 8 | Add academic year selector dropdown in AdminLayout topbar | Frontend | 1h |
| 9 | Ensure only one active year at a time (database constraint + service logic) | Backend | 0.5h |

---

### US-1.4: School Type Configuration

**As an** Admin,
**I want** to set my school type (SD, SMP, SMA, SMK, Madrasah),
**so that** the platform configures grade levels, curriculum, and features accordingly.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] School type selectable: SD, SMP, SMA, SMK, MI, MTs, MA
- [ ] School type determines available grade levels (e.g., SD: 1–6, SMP: 7–9)
- [ ] School type determines attendance mode (daily for SD, per-subject for SMP/SMA)
- [ ] School type stored and used to conditionally show/hide features
- [ ] Cannot be changed after initial setup without SuperAdmin intervention

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `SchoolType` PHP backed enum (SD, SMP, SMA, SMK, MI, MTs, MA) | Backend | 0.5h |
| 2 | Add `type` column to `schools` table | Backend | 0.5h |
| 3 | Create `SchoolTypeConfig` value object mapping type → grade levels, attendance mode | Backend | 1h |
| 4 | Add school type selection to school setup flow | Frontend | 1h |
| 5 | Share school type config via Inertia shared data for conditional rendering | Frontend | 0.5h |

---

### US-1.5: Branding Customization

**As an** Admin,
**I want** to customize school colors and logo placement for rapor/documents,
**so that** printed documents look official and branded.

**Story Points:** 5
**Priority:** Should

**Acceptance Criteria:**
- [ ] Admin can set primary color and accent color
- [ ] Admin can upload header logo and sidebar logo (different sizes)
- [ ] Color scheme applies to admin interface dynamically
- [ ] Logo appears on rapor PDF and official documents
- [ ] Default color scheme provided if not customized
- [ ] Preview of branding changes before saving

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Add `branding` JSONB column to `schools` (primary_color, accent_color, etc.) | Backend | 0.5h |
| 2 | Create `BrandingService` to manage color/logo settings | Backend | 1h |
| 3 | Create `BrandingController` with `edit()` and `update()` | Backend | 0.5h |
| 4 | Share branding config via Inertia middleware for CSS variable injection | Backend | 1h |
| 5 | Create `Branding/Edit.vue` with color pickers and logo upload | Frontend | 2h |
| 6 | Apply CSS custom properties from branding data in layouts | Frontend | 1h |
| 7 | Create branding preview component | Frontend | 1h |

---

## Technical Notes

- **`BelongsToSchool` trait** is the most critical piece of this epic. It must be bulletproof — a single missed scope = data breach. CI test will scan all models to verify.
- **`stancl/tenancy`** single-database mode with `school_id` scoping — NOT database-per-tenant.
- **Cache prefixing**: All cache keys auto-prefixed with `tenant_{school_id}_` to prevent cross-tenant cache pollution.
- **Queue tagging**: `stancl/tenancy` auto-tags queued jobs with the current tenant context.
- **Academic year** is a critical concept — almost all data queries filter by active academic year + active semester.
- **`based/laravel-typescript`** auto-generation is configured in this epic. Run `php artisan typescript:generate` after creating models to generate `resources/js/types/models.d.ts`. Integrate into dev workflow (e.g., as a composer script or git hook).

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| `BelongsToSchool` missed on a model | Data breach — School A sees School B data | CI test scans all Eloquent models, asserts tenant models use the trait |
| Subdomain routing fails on local dev | Can't test multi-tenancy locally | Use `*.localhost` wildcard in hosts file + Nginx config |
| Academic year switch breaks data | Data appears to "disappear" for users | Clear UX indicator of active year, confirmation dialog on switch |

---

## Definition of Done (Epic Level)

- [ ] Multi-tenancy fully functional with subdomain resolution
- [ ] `BelongsToSchool` trait tested with at least 2 school tenants
- [ ] School profile CRUD complete with logo upload
- [ ] Academic year/semester CRUD with active year switching
- [ ] School type configuration determines grade levels and features
- [ ] Branding customization applies to admin UI
- [ ] CI test for `BelongsToSchool` trait enforcement passes
- [ ] Feature tests cover cross-tenant data isolation

---

### Related Files

- **Previous:** [`00_SPRINT_0_INFRASTRUCTURE.md`](00_SPRINT_0_INFRASTRUCTURE.md)
- **Next:** [`M02_USER_ROLE_MANAGEMENT.md`](M02_USER_ROLE_MANAGEMENT.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M1

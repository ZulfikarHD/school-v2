---
name: school-saas-architecture
description: School Management SaaS architecture overview — tech stack, design principles, key decisions. Use when asking about the overall architecture, tech stack choices, why a technology was chosen or rejected, or when starting new features that need architectural context.
---

# School SaaS Architecture Overview

Multi-tenant SaaS for Indonesian school management (SD, SMP, SMA, SMK). Modular monolith, single codebase.

## Key Architectural Decisions

| Decision | Choice | Rationale |
|----------|--------|-----------|
| Architecture | Modular Monolith | Simple deployment, extract services later only if needed |
| Multi-tenancy | Single DB + `school_id` scoping | Simpler ops, easier SuperAdmin cross-tenant queries |
| Frontend | Vue 3 + Inertia (NOT Filament) | Unified UX across all roles, full design control |
| Routing | Wayfinder (NOT Ziggy) | Type-safe route generation, officially supported |
| Mobile | PWA | No app store friction, offline support |
| Parent Auth | WhatsApp OTP | Most Indonesian parents don't have email |
| Database | PostgreSQL 16 | JSONB for flexible data, partitioning support |
| Search | Meilisearch | Full-text search for students, documents |
| Real-time | Laravel Reverb | WebSocket server for notifications |

## Design Principles

1. **Mobile-first for parents** — budget Android (Redmi 9-class) is primary target
2. **Pragmatic service pattern** — no unnecessary abstraction layers
3. **Indonesian market context** — Rupiah (Rp), WIB timezone, Bahasa Indonesia UI, Dapodik compatibility
4. **Data isolation is non-negotiable** — single missed tenant scope = data breach

## Tech Stack

### Backend
- Laravel 12, PHP 8.3+, PostgreSQL 16, Redis 7
- Laravel Horizon (queues), Laravel Reverb (WebSocket), Meilisearch (search)
- S3-compatible storage (MinIO dev, DO Spaces prod), DomPDF (PDF generation)
- **Wayfinder** for type-safe Vue route generation

### Frontend
- Vue 3 + TypeScript, Inertia.js (SSR enabled), Tailwind CSS 4
- Reka UI (headless accessible primitives), TanStack Table (headless data tables)
- motion-vue (sparse animations), @vueuse/core, dayjs (locale: id), Chart.js + vue-chartjs

### Key Laravel Packages
- `stancl/tenancy` — multi-tenancy (single-DB mode, subdomain resolution)
- `spatie/laravel-permission` — roles and permissions
- `spatie/laravel-media-library` — file uploads with conversions
- `spatie/laravel-activitylog` — audit trail (critical for financial data)
- `spatie/laravel-data` — typed DTOs
- `maatwebsite/laravel-excel` — bulk Excel import/export
- `laravel/pennant` — feature flags
- `based/laravel-typescript` — auto-generate TS types from Eloquent models

### Rejected Alternatives
- **Filament** — rejected for unified UX; mitigated by reusable Vue components
- **Ziggy** — replaced by Wayfinder (type-safe, officially supported)
- **Nuxt/Next.js** — Inertia is correct, no separate API layer needed
- **React Native / Flutter** — PWA first, add Capacitor later if needed

## System Layers

| Layer | Components | Responsibility |
|-------|-----------|----------------|
| Client | PWA, Web Apps | User interfaces per role |
| Edge | Cloudflare, Nginx | CDN, WAF, SSL, reverse proxy |
| Application | Laravel, Horizon, Scheduler, Reverb | Business logic, jobs, real-time |
| Data | PostgreSQL, Redis, S3, Meilisearch | Persistence, caching, files, search |
| External | Midtrans, Fonnte, Dapodik | Payments, messaging, gov data export |

## User Roles & Auth

| User Type | Auth Method | Session |
|-----------|------------|---------|
| Admin/Teacher | Email + Password + optional TOTP 2FA | 8 hours |
| Parent | WhatsApp OTP (6 digit) | 30 days |
| Student | NISN + Password (set by school admin) | 8 hours |

Users can have multiple roles (e.g., teacher + parent) with an active role switcher.

## Implementation Phases

1. Project Skeleton (Laravel + Vue + Inertia + Docker)
2. Multi-tenancy + Auth
3. UI Foundation (Layouts, DataTable, forms)
4. Student Management (CRUD + Excel import)
5. Class Management (Rombel, teachers, enrollment)
6. Attendance + Parent WhatsApp notification
7. Finance (SPP billing, payment gateway)
8. Communication (Announcements + WhatsApp blast)
9. Parent Portal
10. Dashboards

## Additional Resources

- For backend patterns, see the `school-saas-backend` skill
- For frontend architecture, see the `school-saas-frontend` skill
- For integration details, see the `school-saas-integrations` skill
- For infrastructure/deployment, see the `school-saas-infrastructure` skill
- Full architecture document: [architecture.md](../../../docs/architecture.md)
- Feature document: [FEATURE_DOCUMENT.md](../../../docs/FEATURE_DOCUMENT.md)

# Feature Document — School Management SaaS Platform

> **Version:** 1.0  
> **Date:** 16 Februari 2026  
> **Author:** Zulfikar Hidayatullah  
> **Status:** Draft  
> **Source:** School App Brainstorm Plan

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Target Users & Personas](#2-target-users--personas)
3. [Feature Modules Overview](#3-feature-modules-overview)
4. [Phase 1 — Core Modules (MVP)](#4-phase-1--core-modules-mvp)
5. [Phase 2 — Academic Excellence & Government Data Export](#5-phase-2--academic-excellence--government-data-export)
6. [Phase 3 — Complete School Operations](#6-phase-3--complete-school-operations)
7. [Phase 4 — Scale & Differentiate](#7-phase-4--scale--differentiate)
8. [Non-Functional Requirements](#8-non-functional-requirements)
9. [Tech Stack Summary](#9-tech-stack-summary)
10. [Business Model](#10-business-model)
11. [Key Edge Cases & Resilience](#11-key-edge-cases--resilience)
12. [Appendix: Module Dependency Map](#12-appendix-module-dependency-map)

---

## 1. Executive Summary

A multi-tenant SaaS school management platform targeting Indonesian schools (SD, SMP, SMA, SMK) that currently have **zero digitalization**. The platform is built as a **modular monolith** and replaces paper-based processes for student data, attendance, grading, report cards, fee collection, and parent communication.

### Problem Statement

Indonesian schools without digital tools:
- Use **paper ledgers** for all student data, grades, attendance, and finances
- Rely on **chaotic WhatsApp groups** as the only digital communication
- Spend **days to weeks** manually writing rapor (report cards) each semester
- Collect **cash SPP** (school fees) with no reliable tracking
- Struggle with **government reporting** (Dapodik, BOS accountability)
- Have staff with **limited IT literacy**
- Operate with **inconsistent internet connectivity**

### Vision

Provide the simplest, most affordable, WhatsApp-integrated school management platform that even non-tech-savvy Indonesian school staff can use — starting from replacing paper, all the way to full digital school operations.

### Key Design Principles

| # | Principle | Description |
|---|-----------|-------------|
| 1 | Mobile-First for Parents | Budget Android phones (Redmi 9-class) are the primary target |
| 2 | WhatsApp-First Communication | All critical notifications flow through WhatsApp, not just in-app |
| 3 | Extreme Simplicity | UI must be usable by staff with minimal IT literacy |
| 4 | Pragmatic Service Pattern | Not over-engineered, no unnecessary abstraction layers |
| 5 | Indonesian Market Context | Rupiah currency, WIB timezone, Bahasa Indonesia UI, Dapodik compatibility |
| 6 | Offline Resilient | Queue actions offline, sync when connectivity returns |
| 7 | Rapor Compliance | Kemendikbud Kurikulum Merdeka format — non-negotiable |
| 8 | Data Isolation | Single missed tenant scope = data breach — non-negotiable |
| 9 | Affordable | Must justify cost vs. free (paper) through time savings |

---

## 2. Target Users & Personas

| Persona | Indonesian Term | Role in Platform | Primary Access |
|---------|----------------|-----------------|----------------|
| Principal | Kepala Sekolah | Dashboard viewer, approval authority | Web |
| Admin Staff | Tata Usaha (TU) | Primary data operator, school config | Web |
| Teacher | Guru | Attendance input, grade input | Web / Mobile |
| Homeroom Teacher | Wali Kelas | Rapor writing, parent communication | Web |
| Treasurer | Bendahara | Fee management, financial reports | Web |
| Parent / Guardian | Orang Tua / Wali | View child info, pay fees, receive notifications | Mobile (PWA) |
| Student | Siswa | View schedule, grades (limited) | Mobile (PWA) |
| BK Counselor | Guru BK | Behavioral records, counseling logs | Web |
| School Committee | Komite Sekolah | Financial transparency reports | Web |
| Super Admin | Platform Owner | Multi-school management, tenant management | Web |

### Persona Pain Points

**Kepala Sekolah (Principal)**
- No real-time visibility into school operations
- Manual preparation for accreditation
- Cannot track teacher performance or student attendance trends

**Tata Usaha (Admin Staff)**
- Drowning in paper — student records, letters, archiving
- Hours spent on repetitive data entry
- Difficult to search/retrieve historical data

**Guru (Teacher)**
- Manual grading takes excessive time
- Rapor writing consumes weeks each semester
- No quick way to see attendance patterns

**Bendahara (Treasurer)**
- Cash-based SPP with no reliable audit trail
- BOS fund accountability is tedious
- Manual bookkeeping errors

**Orang Tua (Parent)**
- No visibility into child's progress
- Must physically visit school for every inquiry
- No easy way to pay fees

---

## 3. Feature Modules Overview

### Module Map by Phase

| ID | Module Name | Phase | Priority |
|----|------------|-------|----------|
| M1 | School Profile & Multi-Tenancy | Phase 1 | **CORE** |
| M2 | User & Role Management | Phase 1 | **CORE** |
| M3 | Student Information System | Phase 1 | **CORE** |
| M4 | Teacher & Staff Management | Phase 2 | **CORE** |
| M5 | Class & Schedule Management | Phase 1 (basic) / Phase 2 (full) | **CORE** |
| M6 | Attendance System | Phase 1 | **CORE** |
| M7 | Grading & Assessment | Phase 2 | **CORE** |
| M8 | Report Card (E-Rapor) | Phase 2 | **CORE** |
| M9 | Finance — SPP & Fees | Phase 1 | **CORE** |
| M10 | Communication | Phase 1 (basic) / Phase 2 (enhanced) | **CORE** |
| M11 | Parent Portal | Phase 1 | **CORE** |
| M12 | Online Exam | Phase 3 | Important |
| M13 | BOS Fund Management | Phase 3 | Important |
| M14 | Document & Letter Management | Phase 3 | Important |
| M15 | Library | Phase 3 | Important |
| M16 | Counseling / BK | Phase 3 | Important |
| M17 | Extracurricular | Phase 3 | Important |
| M18 | PPDB Online (Admission) | Phase 4 | Nice-to-Have |
| M19 | Inventory & Facilities | Phase 4 | Nice-to-Have |
| M20 | Analytics Dashboard | Phase 4 | Nice-to-Have |
| M21 | Government Compliance (Dapodik Export) | Phase 2 (basic export) / Phase 4 (full compliance) | **CORE** |
| M22 | Additional Features | Phase 4+ | Nice-to-Have |

---

## 4. Phase 1 — Core Modules (MVP)

> **Timeline:** 3–4 months  
> **Goal:** Get 5–10 pilot schools onboarded with core value  
> **Revenue:** Subscription + payment gateway commission from Day 1

---

### M1: School Profile & Multi-Tenancy

**Purpose:** Establish the multi-tenant foundation where each school has its own isolated environment.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 1.1 | School Identity | Name, logo, NPSN, address, vision/mission | Admin |
| 1.2 | Custom Subdomain | e.g., `sdnegeri5.platform.id` | System |
| 1.3 | Academic Year Management | Create/manage tahun ajaran & semester | Admin |
| 1.4 | School Type Config | SD, SMP, SMA, SMK, Madrasah | Admin |
| 1.5 | Branding Customization | Colors, logo placement on rapor/documents | Admin |

#### Technical Notes

- Multi-tenancy via `stancl/tenancy` in **single-database mode** with `school_id` scoping
- `BelongsToSchool` trait with Global Scope for automatic tenant filtering on all tenant models
- Subdomain-based tenant resolution (`{school_slug}.platform.id`) via `InitializeTenancyBySubdomain` middleware
- Queue context auto-tagged with tenant by stancl/tenancy
- Cache keys auto-prefixed with `tenant_{school_id}_` to prevent cross-tenant pollution
- SuperAdmin routes on main domain (`admin.platform.id`) without tenancy middleware

#### Acceptance Criteria

- [ ] Each school operates on its own subdomain
- [ ] Data is fully isolated between tenants
- [ ] Admin can configure school identity and branding
- [ ] Academic year/semester can be created and switched
- [ ] School type determines available features and configurations

---

### M2: User & Role Management

**Purpose:** Handle authentication, authorization, and user lifecycle for all platform personas.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 2.1 | Role System | SuperAdmin, Admin Sekolah, Kepala Sekolah, Guru, Wali Kelas, Bendahara, Orang Tua, Siswa, BK | All |
| 2.2 | Permission System | Fine-grained permissions per role (configurable) | Admin |
| 2.3 | Parent-Student Linking | Link parent accounts to student records | Admin |
| 2.4 | Teacher-Subject-Class Assignment | Map guru to mapel and kelas | Admin |
| 2.5 | Bulk User Import | Excel/CSV upload for mass onboarding | Admin |
| 2.6 | Admin/Teacher Auth | Email + Password + optional TOTP 2FA (8-hour session) | Admin, Teacher |
| 2.7 | Parent Auth (WhatsApp OTP) | Phone number + WhatsApp OTP 6-digit (30-day session with remember me) | Parent |
| 2.8 | Student Auth | NISN + Password, credentials set by school admin (8-hour session) | Student |
| 2.9 | Multi-Role Switching | Users with multiple roles can switch between layouts (e.g., teacher/parent) | All |
| 2.10 | User Profile Management | Edit profile, change password, upload photo | All |

#### Technical Notes

- Use `spatie/laravel-permission` for role/permission management
- WhatsApp OTP via configured WhatsApp API provider (Fonnte recommended for Phase 1)
- Bulk import via `maatwebsite/laravel-excel` with chunked processing, validation, preview, and error reporting
- Parent can have multiple children linked
- Multi-role support: a single user can have multiple roles (e.g., teacher who is also a parent) with role switcher in UI
- Permission format: `{module}.{action}` (e.g., `students.view`, `attendance.mark`, `payments.create`)

#### Acceptance Criteria

- [ ] All defined roles can be created and assigned
- [ ] Permissions restrict UI elements and API access
- [ ] Bulk import processes 500+ users from Excel without timeout
- [ ] Parents can log in via WhatsApp OTP (6-digit, 30-day session)
- [ ] Admin/Teacher can log in via email + password with optional 2FA
- [ ] Students can log in via NISN + password
- [ ] Users with multiple roles can switch between roles in the UI
- [ ] Parent sees all linked children in their portal

---

### M3: Student Information System (Data Siswa)

**Purpose:** Central repository for all student data, replacing paper-based student records.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 3.1 | Student Biodata | Name, NISN, NIK, birth date, religion, address, photo | Admin |
| 3.2 | Family Information | Father, mother, guardian details, occupation, income | Admin |
| 3.3 | Health Records | Allergies, blood type, medical conditions | Admin |
| 3.4 | Transfer History | Previous school records, transfer documentation | Admin |
| 3.5 | Student Status | Active, transferred (pindah), graduated (lulus), dropped out (keluar) | Admin |
| 3.6 | Class Assignment | Assign student to Rombel per academic year | Admin |
| 3.7 | Document Uploads | Akta kelahiran, KK, ijazah, and other documents | Admin |
| 3.8 | Alumni Tracking | Graduated student records and tracking | Admin |
| 3.9 | Student Search | Fast search by name, NISN, or class | All Staff |

#### Technical Notes

- Meilisearch integration via Laravel Scout for fast student search
- File uploads via `spatie/laravel-media-library` to S3-compatible storage (signed URLs, auto-resize)
- Student status changes logged via `spatie/laravel-activitylog` for audit trail
- Class assignment is per-academic-year (students move up yearly)
- JSONB columns for flexible data: `family_data`, `health_data`, `metadata` (school-specific custom fields)
- Student NIK encrypted at rest (`$casts = ['nik' => 'encrypted']`)

#### Acceptance Criteria

- [ ] Complete student profile can be created with all biodata fields
- [ ] Documents can be uploaded and previewed
- [ ] Student can be assigned to a class for the current academic year
- [ ] Status changes are logged with timestamp and reason
- [ ] Search returns results in < 200ms for up to 2,000 students

---

### M5: Class & Schedule Management (Basic)

**Purpose:** Manage class groups (rombel) and basic schedule configuration.

> Full timetable management and conflict detection will be added in Phase 2.

#### Features (Phase 1 Scope)

| # | Feature | Description | User |
|---|---------|-------------|------|
| 5.1 | Rombel Management | Create class groups (Rombongan Belajar) per academic year | Admin |
| 5.2 | Homeroom Assignment | Assign Wali Kelas to each rombel | Admin |
| 5.3 | Student Enrollment | Assign students to rombel | Admin |
| 5.4 | Basic Schedule | Manual schedule entry (day, time, subject, teacher, room) | Admin |
| 5.5 | Room Allocation | Basic room-to-class mapping | Admin |

#### Phase 2 Enhancements (Planned)

- Automatic timetable generation with conflict detection
- Teacher substitution management (guru pengganti)
- Schedule conflict alerts

#### Acceptance Criteria

- [ ] Rombel can be created per academic year with grade level
- [ ] Wali Kelas is assigned per rombel
- [ ] Students are listed under their assigned rombel
- [ ] Schedule is viewable by teachers and students
- [ ] Room double-booking is flagged (basic validation)

---

### M6: Attendance System (Absensi)

**Purpose:** Digitize daily attendance tracking with automatic parent notifications.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 6.1 | Daily Attendance Input | Mark students as Hadir, Sakit, Izin, Alpa (Alpha) | Teacher |
| 6.2 | Manual Entry (Primary Method) | Simple checklist UI — teacher marks each student | Teacher |
| 6.3 | QR Code Scan | Student scans QR from student card | Teacher |
| 6.4 | Attendance Type by Level | Daily attendance for SD/TK, per-subject for SMP/SMA | System |
| 6.5 | Auto Parent Notification | WhatsApp message to parent when child is absent | System |
| 6.6 | Monthly/Semester Recap | Attendance summary reports per student and per class | Admin, Teacher |
| 6.7 | Teacher/Staff Attendance | Track staff attendance separately | Admin |
| 6.8 | Late Tracking | Record late arrivals (keterlambatan) | Teacher |

#### Technical Notes

- Per-subject attendance (SMP/SMA): `subject_id` populated; daily attendance (SD): `subject_id` is NULL
- Unique index prevents duplicate records: `(school_id, student_id, date, subject_id)`
- `INSERT ON CONFLICT UPDATE` for concurrent marking (last write wins)
- WhatsApp notifications dispatched via `StudentMarkedAbsent` event → `NotifyParentOfAbsence` listener → queued
- Recap data feeds into rapor attendance section (M8)
- QR code scanning uses device camera (no special hardware needed)
- **Morning rush mitigation** (07:00–08:00 WIB): bulk upsert in single transaction, optimistic UI

#### User Flow — Teacher Daily Attendance

```
1. Teacher opens app → sees today's classes
2. Selects class → student list appears (all marked "Hadir" by default)
3. Taps students who are absent → selects reason (Sakit/Izin/Alpa)
4. Taps "Simpan" → attendance saved
5. System auto-sends WhatsApp to parents of absent students
```

#### Acceptance Criteria

- [ ] Teacher can mark attendance for a class in < 2 minutes
- [ ] All four status types (H, S, I, A) are supported
- [ ] Parents receive WhatsApp notification within 5 minutes of marking
- [ ] Monthly recap shows total days for each status per student
- [ ] Attendance data is viewable by Wali Kelas and Admin

---

### M9: Finance — SPP & School Fees (Keuangan)

**Purpose:** Replace cash-based fee collection with a fully digital payment system, complete with online payment gateway, auto-reconciliation, and reminders.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 9.1 | Fee Configuration | Set monthly SPP amounts per class, category, or individual student | Admin, Bendahara |
| 9.2 | Payment Status Dashboard | Visual overview of who has paid and who hasn't | Bendahara |
| 9.3 | Payment History | Per-student payment timeline | Bendahara, Parent |
| 9.4 | WhatsApp Payment Reminder | Auto-reminder before due date via WhatsApp | System |
| 9.5 | Payment Gateway Integration | Midtrans or Xendit for online payments | System |
| 9.6 | Virtual Account | BCA, BNI, Mandiri, BRI VA for bank transfer | Parent |
| 9.7 | QRIS | QR-based payment (universal) | Parent |
| 9.8 | E-Wallet | GoPay, OVO, DANA, ShopeePay | Parent |
| 9.9 | Convenience Store | Pay at Alfamart / Indomaret | Parent |
| 9.10 | Auto-Reconciliation | Automatic payment status update when gateway confirms | System |
| 9.11 | Digital Receipt | Generate and send payment receipt | System |
| 9.12 | Installment Plans | Cicilan plans for large payments | Admin |
| 9.13 | Other Fees | Uang gedung, seragam, kegiatan, wisuda, etc. | Admin |
| 9.14 | Payment Reports | Financial reports for bendahara | Bendahara |

#### Technical Notes

- **Gateway abstraction**: `PaymentGateway` interface with `MidtransGateway` and `XenditGateway` implementations (swap without code changes)
- **Recommendation**: Start with **Midtrans** — better documentation, wider payment method support, official Laravel SDK
- **Webhook safety**: Verify cryptographic signature on every webhook, idempotent processing, log ALL payloads in `payment_gateway_logs` (JSONB)
- **Daily reconciliation**: `ReconcilePayments` job compares gateway records with local DB
- **Financial audit**: All payment changes logged via `spatie/laravel-activitylog` with before/after values
- **Materialized summary**: `StudentFeeSummary` table (total_due, total_paid, outstanding, months_overdue) updated by `PaymentObserver`
- Payment amounts validated against `student_fees.amount` to prevent tampering

#### Revenue Model (Platform)

- **0.5–1% transaction fee** on every payment processed through the gateway
- This is on top of the gateway's own fees (transparent to school)

#### User Flow — Parent Pays SPP

```
1. Parent opens app → sees "Tagihan" (bills) section
2. Sees outstanding SPP for current month
3. Taps "Bayar" → selects payment method (VA/QRIS/E-Wallet/Minimarket)
4. Redirected to payment page or shown VA number / QR code
5. Parent completes payment
6. System receives webhook → auto-updates payment status
7. Digital receipt sent to parent (in-app + WhatsApp)
```

#### Acceptance Criteria

- [ ] Admin can configure fee types and amounts
- [ ] Dashboard shows real-time payment status per student
- [ ] At least 3 payment methods work (VA, QRIS, E-Wallet)
- [ ] Payment is auto-reconciled within 5 minutes of gateway confirmation
- [ ] WhatsApp reminders are sent 3 days before due date (configurable)
- [ ] Digital receipt is generated for every successful payment

---

### M10: Communication (Basic)

**Purpose:** Replace chaotic WhatsApp groups with structured announcements and targeted messaging.

#### Features (Phase 1 Scope)

| # | Feature | Description | User |
|---|---------|-------------|------|
| 10.1 | School Announcements | Post announcements visible to all or targeted groups | Admin |
| 10.2 | Read Receipts | Track who has seen the announcement | Admin |
| 10.3 | Targeted Messaging | Send to specific class, grade, or parent group | Admin |
| 10.4 | WhatsApp Blast | Send announcement via WhatsApp to parent groups | Admin |
| 10.5 | In-App Notifications | Notification center within the platform | All |
| 10.6 | Event/Calendar Sharing | School calendar with events | Admin |
| 10.7 | Emergency Broadcast | Urgent message to all parents/staff immediately | Admin |

#### Technical Notes

- **WhatsApp provider abstraction**: `WhatsAppProvider` interface with `FonnteProvider` and `WablasProvider` implementations
- **Recommendation**: Start with **Fonnte** — cheapest, decent reliability. Abstract for easy switching later.
- **Rate limiting**: Fonnte ~1000 messages/day on basic plan. Queue rate: `RateLimited::perMinute(30)`
- **Priority queues**: Absence alerts > Payment reminders > Announcements (`notifications-high`, `notifications-low`)
- **Blast strategy**: `Bus::chain()` with delays between batches to avoid provider rate limits
- **Message templates** stored in DB, editable by school admin
- **Real-time notifications** via Laravel Reverb (WebSocket) for in-app events
- **Dead letter queue** for failed WhatsApp messages — visible to admin for manual retry

#### Phase 2 Enhancements (Planned)

- Parent-teacher private messaging
- Chat-like messaging interface

#### Acceptance Criteria

- [ ] Admin can create and publish announcements
- [ ] Announcements can be targeted to specific classes/groups
- [ ] WhatsApp blast reaches parents within 10 minutes
- [ ] Read receipts show percentage of parents who viewed
- [ ] Emergency broadcast is delivered with highest priority

---

### M11: Parent Portal

**Purpose:** Mobile-first interface for parents to stay connected with their child's school life.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 11.1 | Attendance View | Real-time attendance status and monthly recap | Parent |
| 11.2 | Payment & Bills | View outstanding fees, pay online, view receipts | Parent |
| 11.3 | Announcements | Read school announcements and events | Parent |
| 11.4 | School Calendar | View upcoming events and schedule | Parent |
| 11.5 | Absence Request | Submit permission/izin for child's absence | Parent |
| 11.6 | Contact Wali Kelas | Send message to homeroom teacher | Parent |
| 11.7 | Child's Schedule | View daily/weekly schedule | Parent |
| 11.8 | Multi-Child Support | One parent account, multiple children | Parent |

#### Phase 2 Additions

- View grades and assessments
- View and download rapor (report card)

#### Technical Notes

- **Mobile-first design** — responsive, touch-optimized, large touch targets
- **ParentLayout**: Bottom navigation (4–5 tabs), optimized for one-handed use
- PWA (Progressive Web App) for app-like experience on phone — no app store friction
- Minimal data usage (low-bandwidth optimization): target < 200KB initial JS (gzipped)
- SSR enabled via Inertia for faster initial paint on slow 3G connections
- Works on low-end Android devices (Android 8+, Redmi 9-class)
- Parent data access enforced at query level (not just UI): `Student::whereHas('parents', ...)`
- **Offline resilience**: `useOnlineStatus` composable detects offline state, localStorage queue for pending actions, Service Worker background sync

#### Acceptance Criteria

- [ ] Parent can view child's attendance for today and historical
- [ ] Parent can pay SPP from the portal
- [ ] Announcements are visible within seconds of publishing
- [ ] Parent with 3 children can switch between them easily
- [ ] Portal loads in < 3 seconds on 3G connection

---

## 5. Phase 2 — Academic Excellence & Government Data Export

> **Timeline:** 2–3 months (after Phase 1 launch)  
> **Goal:** Handle the full academic cycle — grading through report cards — and establish Dapodik export as a key differentiator

---

### M4: Teacher & Staff Management

**Purpose:** Manage teacher/staff profiles, assignments, and employment data.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 4.1 | Teacher Biodata | Full profile: NUPTK, NIP, certification status, education | Admin |
| 4.2 | Teaching Load | Assign subjects (mapel) and classes (kelas) to teachers | Admin |
| 4.3 | Homeroom Assignment | Wali Kelas assignment per academic year | Admin |
| 4.4 | Staff Attendance | Daily attendance tracking for staff | Admin |
| 4.5 | Employment Status | Contract type, start date, status tracking | Admin |

#### Acceptance Criteria

- [ ] Complete teacher profile can be created with all required fields
- [ ] Teaching load (mapel + kelas) is clearly visible
- [ ] Staff attendance is tracked and reportable
- [ ] Employment status changes are logged

---

### M7: Grading & Assessment (Penilaian)

**Purpose:** Kurikulum Merdeka-compliant grading system that replaces manual grade books.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 7.1 | Kurikulum Merdeka Compliance | Support CP (Capaian Pembelajaran) and TP (Tujuan Pembelajaran) | System |
| 7.2 | Assessment Types | Asesmen Formatif and Sumatif | Teacher |
| 7.3 | Configurable Categories | Schools can define their own assessment categories | Admin |
| 7.4 | Grade Input per Subject | Enter grades for each subject per class | Teacher |
| 7.5 | Weighted Calculation | Configurable grade weighting | Admin |
| 7.6 | Remedial Tracking | Re-assessment for students below KKTP | Teacher |
| 7.7 | KKTP | Minimum completeness criteria configuration | Admin |
| 7.8 | Bulk Grade Input | Excel-like grid interface for fast input | Teacher |
| 7.9 | Grade Analytics | Distribution charts, trends, class comparison | Teacher, Admin |

#### User Flow — Teacher Inputs Grades

```
1. Teacher selects Subject → Class → Assessment type
2. Excel-like grid appears with student names in rows
3. Teacher inputs grades (keyboard-optimized, tab between cells)
4. System validates (within range, meets data type)
5. Teacher clicks "Simpan" → grades saved
6. System calculates weighted averages automatically
```

#### Acceptance Criteria

- [ ] Kurikulum Merdeka CP/TP structure is supported
- [ ] Teacher can input grades for 40 students in < 5 minutes via grid
- [ ] Weighted calculation matches school's configured formula
- [ ] Remedial students are flagged automatically
- [ ] Grade analytics show distribution per assessment

---

### M8: Report Card (E-Rapor)

**Purpose:** Generate Kemendikbud-compliant report cards automatically from grading data.

> **Competitive Advantage vs Government e-Rapor:** The government's official e-Rapor application is widely criticized by teachers for being buggy, slow (especially during end-of-semester server overload), inflexible, and having poor UX. Many teachers resort to filling rapor manually in Excel/Word. Our rapor module must be demonstrably superior in three areas: **(1)** speed — generate all class rapor in minutes, not hours; **(2)** UX — intuitive interface that auto-pulls grades and attendance; **(3)** reliability — works offline-first with local PDF generation, no dependency on government servers. This single module can be the #1 reason schools adopt the platform.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 8.1 | Kurikulum Merdeka Format | Fully compliant with Kemendikbud rapor format | System |
| 8.2 | Intrakurikuler Grades | Academic grades with narrative/descriptive assessment | Teacher |
| 8.3 | P5 Section | Projek Penguatan Profil Pelajar Pancasila | Teacher |
| 8.4 | Extracurricular Section | Ekskul participation and achievements (from M17) | Teacher |
| 8.5 | Attendance Summary | Auto-populated from attendance data (M6) | System |
| 8.6 | Teacher Notes | Catatan wali kelas (homeroom teacher narrative) | Wali Kelas |
| 8.7 | Digital Signatures | Kepala Sekolah and Wali Kelas digital signatures | System |
| 8.8 | Print-Ready PDF | PDF with school branding for printing | All |
| 8.9 | Online Parent Access | Parents view rapor digitally in portal | Parent |
| 8.10 | Level-Specific Format | Different format per school level (SD/SMP/SMA/SMK) | System |

#### Technical Notes

- PDF generation via **DomPDF** (sufficient for Kurikulum Merdeka format)
- Heavy operation: 30 rapor per class → processed via `Bus::batch()` on dedicated `rapor` queue
- Real-time progress via **Laravel Reverb**: "12 of 30 rapor generated..."
- Pre-cache grades/attendance data before generating PDFs for performance
- Retry policy: `$tries = 3`, `$timeout = 120` seconds per rapor
- Rapor template is configurable per school type
- Data auto-pulled from M6 (attendance) and M7 (grades)
- Digital signature uses uploaded signature images

#### Acceptance Criteria

- [ ] Generated rapor matches Kemendikbud Kurikulum Merdeka format
- [ ] PDF includes school logo, signatures, and all required sections
- [ ] Attendance data is auto-populated correctly
- [ ] Rapor is viewable by parents in the portal
- [ ] Different formats render correctly for SD, SMP, SMA, SMK

---

### M5 Enhancement: Full Schedule Management

**Purpose:** Complete timetable management with conflict detection.

#### Additional Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 5.6 | Auto Schedule Generation | Algorithm-assisted timetable creation | Admin |
| 5.7 | Conflict Detection | Alert when teacher/room double-booked | System |
| 5.8 | Teacher Substitution | Manage guru pengganti when teacher is absent | Admin |

---

### M10 Enhancement: Enhanced Communication

#### Additional Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 10.8 | Parent-Teacher Messaging | Private chat between parent and wali kelas | Parent, Teacher |

---

### M21: Government Compliance — Dapodik Export (Basic)

**Purpose:** Enable schools to export student and teacher data in Dapodik-compatible format, eliminating double data entry and positioning the platform as a **complement** to government systems (Dapodik, ARKAS, e-Rapor) — not a replacement.

> **Why Phase 2 (not Phase 4)?** This is a killer differentiator. Schools currently input data into the platform AND manually re-input the same data into Dapodik. If the platform can export Dapodik-ready data, it solves a major pain point and makes the platform indispensable — especially for sekolah negeri where Dapodik reporting is mandatory.

#### Features (Phase 2 Scope — Basic Export)

| # | Feature | Description | User |
|---|---------|-------------|------|
| 21.1 | Student Data Export | Export student biodata (NISN, NIK, name, birth date, family data, etc.) in Dapodik-compatible CSV/Excel format | Admin |
| 21.2 | Teacher Data Export | Export teacher biodata (NUPTK, NIP, certification, teaching assignments) in Dapodik-compatible format | Admin |
| 21.3 | Field Mapping Config | Map platform fields to Dapodik fields (configurable per school, since Dapodik format may change) | Admin |
| 21.4 | Export Validation | Validate data completeness and format before export — flag missing/invalid fields that Dapodik requires | System |
| 21.5 | Export History | Track when exports were generated and by whom | System |

#### Technical Notes

- Dapodik uses specific field names and formats — the export must match exactly (e.g., date format, gender codes, religion codes)
- Use a `DapodikExportService` with configurable field mapping stored in `school_settings` (JSONB)
- Validation runs before export: missing NISN, invalid NIK format, empty required fields are flagged with actionable error messages
- Export format: CSV (primary — Dapodik import-friendly) and Excel (for manual review)
- Field mapping is versioned — when Dapodik changes their format, we update the mapping template and schools can re-apply
- This is a one-way export (platform → Dapodik), NOT a two-way sync (too complex for Phase 2)

#### Phase 4 Enhancements (Planned)

- EMIS export for Madrasah (Kemenag)
- Accreditation document preparation helpers
- Two-way sync with Dapodik API (if/when government opens API)
- BOS reporting format export (complements M13)
- Standardized government report templates

#### Acceptance Criteria

- [ ] Admin can export student data in Dapodik-compatible CSV format
- [ ] Admin can export teacher data in Dapodik-compatible CSV format
- [ ] Export validation flags missing/invalid fields before generating the file
- [ ] Exported file can be imported into Dapodik without manual reformatting
- [ ] Field mapping is configurable by school admin

---

## 6. Phase 3 — Complete School Operations

> **Timeline:** 2–3 months  
> **Goal:** Digitize remaining school operations

---

### M12: Online Exam (Ujian Online)

**Purpose:** Digital examination system with question banks and auto-grading.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 12.1 | Question Types | Pilihan Ganda, Essay, Isian Singkat, Menjodohkan, Benar/Salah | Teacher |
| 12.2 | Question Bank | Store questions tagged by mapel, TP, and difficulty | Teacher |
| 12.3 | Randomization | Random question and answer order per student | System |
| 12.4 | Timer & Auto-Submit | Configurable time limit with auto-submit | System |
| 12.5 | Anti-Cheat | Tab-switch detection, fullscreen enforcement | System |
| 12.6 | Auto-Grading | Automatic scoring for objective questions | System |
| 12.7 | Manual Essay Grading | Grading interface for essay questions | Teacher |
| 12.8 | Exam Analytics | Item analysis, score distribution, difficulty index | Teacher |
| 12.9 | Practice Mode | Latihan/practice exams for students | Student |

#### Acceptance Criteria

- [ ] All question types can be created and used in exams
- [ ] Questions are randomized per student
- [ ] Objective questions are auto-graded instantly
- [ ] Tab-switch is detected and logged
- [ ] Analytics show item difficulty and score distribution

---

### M13: BOS Fund Management

**Purpose:** Manage government BOS (Bantuan Operasional Sekolah) funds with proper accountability and ARKAS-compatible reporting.

> **Context:** Every sekolah negeri receives BOS funds and MUST report usage via ARKAS (Aplikasi Rencana Kegiatan dan Anggaran Sekolah). This reporting is time-consuming and error-prone when done manually. If our platform simplifies BOS expense tracking and generates ARKAS-ready reports, it becomes a compelling reason for sekolah negeri to subscribe — even without the payment gateway revenue.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 13.1 | RKAS Planning | Create Rencana Kegiatan dan Anggaran Sekolah matching ARKAS format structure | Bendahara |
| 13.2 | Budget Allocation by 8 Standar | Allocate budget per 8 SNP categories: standar isi, proses, kompetensi lulusan, PTK, sarpras, pengelolaan, pembiayaan, penilaian | Bendahara |
| 13.3 | Expense Tracking | Record expenses with receipt/nota upload, tagged to budget category | Bendahara |
| 13.4 | BOS Reporting (Juknis-compliant) | Generate quarterly and annual BOS accountability reports in format required by Juknis BOS | Bendahara |
| 13.5 | ARKAS Export | Export financial data in ARKAS-compatible format for direct import into the government ARKAS application | Bendahara |
| 13.6 | Cash Flow Monitoring | Real-time cash flow: BOS received vs spent vs remaining per quarter | Kepala Sekolah |
| 13.7 | Audit Trail | Complete immutable log of all financial transactions with before/after values | System |
| 13.8 | Budget vs Actual | Compare RKAS planned budget against actual spending per category with variance alerts | Bendahara, Kepala Sekolah |
| 13.9 | SIPLah Purchase Tracking | Log purchases made through SIPLah marketplace for consolidated expense view | Bendahara |

#### Technical Notes

- BOS categories follow the 8 Standar Nasional Pendidikan (SNP) — these are configurable but pre-seeded with standard categories
- ARKAS export generates CSV/Excel matching ARKAS import template (field names, formats, category codes)
- Receipt images stored via `spatie/laravel-media-library` with OCR potential for future auto-extraction
- Budget vs Actual uses materialized summary table updated on each expense entry
- Quarterly reporting deadlines are tracked with reminders to bendahara

#### Acceptance Criteria

- [ ] RKAS can be created matching the 8 SNP categories
- [ ] Expenses can be recorded with receipt image upload and category tagging
- [ ] Quarterly BOS report can be generated in Juknis BOS format
- [ ] ARKAS-compatible export can be generated for import into government ARKAS app
- [ ] Budget vs Actual shows variance per category with alerts for overspending
- [ ] Audit trail is immutable and complete

---

### M14: Document & Letter Management (Surat-Menyurat)

**Purpose:** Digitize school correspondence and document management.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 14.1 | Surat Masuk/Keluar | Register incoming and outgoing letters | Admin |
| 14.2 | Letter Templates | Surat Keterangan, SK, Surat Izin, Surat Tugas, Surat Kelulusan | Admin |
| 14.3 | Auto-Numbering | Automatic sequential letter numbering | System |
| 14.4 | Digital Signature | Sign documents digitally | Kepala Sekolah |
| 14.5 | Archive & Search | Full-text search across all documents | Admin |

#### Acceptance Criteria

- [ ] Letters can be created from templates with auto-populated data
- [ ] Numbering follows school's format consistently
- [ ] Documents are searchable by keyword, date, or type
- [ ] Digitally signed documents are valid and downloadable

---

### M15: Library (Perpustakaan)

**Purpose:** Manage school library catalog, borrowing, and returns.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 15.1 | Book Catalog | ISBN, title, author, cover image, category | Admin |
| 15.2 | Borrowing System | Check-out and return tracking | Admin |
| 15.3 | Fine Calculation | Auto-calculate fines for late returns | System |
| 15.4 | Borrowing History | Per-student borrowing records | Admin, Student |
| 15.5 | Analytics | Popular books, borrowing trends | Admin |
| 15.6 | QR/Barcode Scan | Scan book barcode for quick operations | Admin |
| 15.7 | Digital Resources | PDF materials library | Admin, Student |

#### Acceptance Criteria

- [ ] Books can be cataloged with complete metadata
- [ ] Borrowing and return is tracked with due dates
- [ ] Fines are calculated automatically
- [ ] Students can view their borrowing history

---

### M16: Counseling / BK (Bimbingan Konseling)

**Purpose:** Confidential counseling and behavioral tracking system.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 16.1 | Counseling Records | Confidential student counseling logs | Guru BK |
| 16.2 | Behavioral Incidents | Report pelanggaran (violations) | Guru BK, Teacher |
| 16.3 | Point/Merit System | Kredit poin for behavior tracking | Guru BK |
| 16.4 | Home Visit Log | Documentation of home visits | Guru BK |
| 16.5 | Career Guidance | Career counseling records (SMP/SMA) | Guru BK |
| 16.6 | At-Risk Flagging | Automatic flagging of at-risk students | System |
| 16.7 | Intervention Tracking | Track interventions and outcomes | Guru BK |

#### Acceptance Criteria

- [ ] Counseling records are confidential (access restricted to BK only)
- [ ] Point system tracks behavior over time
- [ ] At-risk students are flagged based on configurable thresholds
- [ ] Home visit documentation includes photos and notes

---

### M17: Extracurricular (Ekskul)

**Purpose:** Manage extracurricular activities, enrollment, and achievements.

#### Features

| # | Feature | Description | User |
|---|---------|-------------|------|
| 17.1 | Ekskul Catalog | List of available extracurriculars with schedule | Admin |
| 17.2 | Student Enrollment | Students sign up for ekskul | Student, Admin |
| 17.3 | Ekskul Attendance | Per-session attendance tracking | Coach/Pembina |
| 17.4 | Achievement Tracking | Competition results and awards | Coach/Pembina |
| 17.5 | Coach Assignment | Assign pembina to each ekskul | Admin |
| 17.6 | Rapor Integration | Auto-feed ekskul data to rapor (M8) | System |

#### Acceptance Criteria

- [ ] Ekskul can be created with schedule and pembina
- [ ] Students can be enrolled in multiple ekskul
- [ ] Attendance per session is tracked
- [ ] Achievement data flows into rapor automatically

---

## 7. Phase 4 — Scale & Differentiate

> **Timeline:** 2–3 months  
> **Goal:** Advanced features, government compliance, and scaling

---

### M18: PPDB Online (New Student Admission)

**Purpose:** Digitize the new student admission process.

#### Features

| # | Feature | Description |
|---|---------|-------------|
| 18.1 | Online Registration Form | Parents fill in student data and upload documents |
| 18.2 | Document Upload | Upload required admission documents |
| 18.3 | Selection Criteria | Configurable scoring/selection rules |
| 18.4 | Acceptance Announcement | Online announcement of accepted students |
| 18.5 | Re-Registration | Online daftar ulang for accepted students |
| 18.6 | Quota Management | Set and track rombel capacity |

---

### M19: Inventory & Facilities (Sarana Prasarana)

**Purpose:** Track school assets and facility management.

#### Features

| # | Feature | Description |
|---|---------|-------------|
| 19.1 | Asset Registry | Inventaris barang with details and location |
| 19.2 | Room & Facility Management | Track rooms, labs, and facilities |
| 19.3 | Maintenance Requests | Submit and track maintenance needs |
| 19.4 | Procurement Workflow | Request → approval → purchase flow |
| 19.5 | Asset Depreciation | Track asset value over time |

---

### M20: Analytics Dashboard

**Purpose:** Executive dashboard for school leadership with actionable insights.

#### Features

| # | Feature | Description |
|---|---------|-------------|
| 20.1 | School KPI Dashboard | Key metrics at a glance for Kepala Sekolah |
| 20.2 | Attendance Trends | Patterns and anomalies in attendance data |
| 20.3 | Academic Performance | Grade trends, pass rates, subject comparisons |
| 20.4 | Financial Health | Revenue, outstanding fees, BOS utilization |
| 20.5 | Teacher Workload | Teaching load analysis and balance |
| 20.6 | Class Comparison | Comparative analytics across classes |
| 20.7 | Export | Export to Excel and PDF |

---

### M21: Government Compliance

**Purpose:** Ensure compatibility with government reporting requirements.

#### Features

| # | Feature | Description |
|---|---------|-------------|
| 21.1 | Dapodik Export | Data export compatible with Dapodik system |
| 21.2 | EMIS Export | Export for Madrasah (Kemenag) reporting |
| 21.3 | Accreditation Prep | Document preparation for accreditation |
| 21.4 | Government Report Formats | Standardized report templates |

---

### M22: Additional Future Features

| Feature | Description |
|---------|-------------|
| Mobile PWA / Native App | Full mobile app for parents and teachers |
| Offline Mode | Queue actions offline, sync when online |
| WhatsApp Bot | Parents check grades/attendance via WhatsApp chat |
| QR Code Student ID | Generate student ID cards with QR |
| Bus/Transport Tracking | School bus GPS tracking |
| Canteen Pre-ordering | Pre-order food from school canteen |
| Health / UKS Records | School health unit records |
| Visitor Management | Track school visitors |
| LMS Integration | Learning Management System features |
| Multi-Language | Bahasa Indonesia (primary) + English |

---

## 8. Non-Functional Requirements

### Performance

| Metric | Target |
|--------|--------|
| Page Load Time | < 3 seconds on 3G connection |
| API Response Time | < 500ms (p95) |
| Search Response | < 200ms for up to 5,000 records |
| Concurrent Users | Support 500 concurrent users per school |
| Uptime | 99.5% availability |

### Security

| Requirement | Implementation |
|-------------|----------------|
| Data Isolation | `BelongsToSchool` trait with Global Scope on all tenant models. CI test enforces usage. |
| Middleware Stack | `InitializeTenancy → EnsureSchoolActive → CheckSubscription → Auth → VerifyRole` |
| Authentication | Email+Password+2FA (admin/teacher), WhatsApp OTP (parent), NISN+Password (student) |
| Authorization | `spatie/laravel-permission` — role-based + permission-based access control |
| Data Encryption | HTTPS in transit. NIK, phone encrypted at rest (`$casts = ['nik' => 'encrypted']`) |
| File Security | S3 signed URLs (expire in 30 minutes) via `spatie/laravel-media-library` |
| Audit Trail | `spatie/laravel-activitylog` with before/after values on all financial data |
| Rate Limiting | 5 login attempts/min, 3 OTP attempts/5min |
| UU PDP Compliance | Indonesia Personal Data Protection Law — data portability, deletion capability |

### Scalability

| Requirement | Approach |
|-------------|----------|
| Multi-Tenancy | `stancl/tenancy` single-DB mode with `school_id` scoping. Database-per-tenant migration path if needed. |
| Queue Processing | Laravel Horizon with dedicated queues: `default`, `notifications-high`, `notifications-low`, `rapor` |
| File Storage | S3-compatible object storage with `spatie/laravel-media-library` auto-resize |
| Search | Laravel Scout + Meilisearch for full-text search |
| Caching | Redis for session, cache, and queue. Tag-based cache with per-school invalidation. |
| Real-Time | Laravel Reverb WebSocket for live notifications (payment status, rapor progress) |
| Database Pooling | PgBouncer in front of PostgreSQL when total workers exceed 50 |
| Future Partitioning | Range partition `attendances` and `payments` by date after ~50M rows |

### Accessibility & Localization

| Requirement | Details |
|-------------|---------|
| Language | Bahasa Indonesia (primary), no English jargon in UI |
| Mobile Responsiveness | Mobile-first ParentLayout (bottom nav), responsive AdminLayout (sidebar) |
| Low-Bandwidth Support | SSR enabled, < 200KB initial JS (gzipped), lazy-loaded pages, compressed images |
| Low-End Devices | Must work on budget Android phones (Android 8+, Redmi 9-class) |
| Animation Discipline | Only page transitions, list enter/exit, button feedback. NO decorative animations. |

---

## 9. Tech Stack Summary

### Backend

| Technology | Version | Purpose |
|-----------|---------|---------|
| Laravel | 12 | PHP framework |
| PHP | 8.3+ | Runtime |
| PostgreSQL | 16 | Primary database (JSONB, partitioning) |
| Redis | 7 | Cache, sessions, queue broker |
| Laravel Horizon | — | Queue dashboard |
| Laravel Reverb | — | WebSocket server (real-time notifications) |
| Meilisearch | — | Full-text search (students, documents) |
| S3-compatible | — | File storage (MinIO dev, DO Spaces / AWS S3 prod) |
| DomPDF | — | PDF generation (rapor, receipts) |
| Wayfinder | — | Type-safe route generation for Vue (not Ziggy) |

### Frontend

| Technology | Purpose |
|-----------|---------|
| Vue 3 + TypeScript | UI framework |
| Inertia.js (SSR enabled) | SPA-like experience without separate API |
| Tailwind CSS 4 | Styling |
| Reka UI | Headless, accessible UI primitives |
| TanStack Table (Vue) | Headless data tables, virtualizable |
| motion-vue | Animations (used sparingly for low-end devices) |
| @vueuse/core | Vue composables |
| dayjs (locale: id) | Date handling |
| Chart.js + vue-chartjs | Dashboard charts |

### Key Laravel Packages

| Package | Purpose |
|---------|---------|
| `stancl/tenancy` | Multi-tenancy (single-DB mode, subdomain resolution) |
| `spatie/laravel-permission` | Roles and permissions |
| `spatie/laravel-media-library` | File uploads with conversions |
| `spatie/laravel-activitylog` | Audit trail (critical for financial data) |
| `spatie/laravel-data` | Typed DTOs |
| `maatwebsite/laravel-excel` | Bulk Excel import/export |
| `laravel/pennant` | Feature flags (gradual rollout) |
| `spatie/laravel-backup` | Automated backups |
| `based/laravel-typescript` | Auto-generate TypeScript types from Eloquent models |

### External Integrations

| Integration | Recommendation |
|------------|----------------|
| Payment Gateway | **Midtrans** (start here) — better docs, wider payment methods, official Laravel SDK |
| WhatsApp API | **Fonnte** (start here) — cheapest, decent reliability. Abstract for easy switching. |
| CDN / WAF | Cloudflare (free tier) |

### Infrastructure

| Component | Details |
|-----------|---------|
| Containerization | Docker + Docker Compose |
| Package Manager | Yarn |
| CI/CD | GitHub Actions |
| Deployment | SSH + Docker to VPS (DigitalOcean SGP1/JKT) |

---

## 10. Business Model

### Revenue Streams

| Stream | Applies To | Details |
|--------|-----------|---------|
| **Subscription** | All schools | Monthly/yearly per school, tiered by school size or features |
| **Transaction Fee** | Sekolah Swasta (private) | 0.5–1% on every SPP payment through the platform |
| **Upsells** | All schools | Custom branding, priority support, advanced analytics, PPDB module |

> **Key Insight:** Sekolah negeri (public schools) generally do not charge SPP because operational costs are covered by BOS funds. This means the **payment gateway commission model does not apply** to sekolah negeri. Revenue from sekolah negeri comes purely from **subscriptions** — which can be funded from BOS allocation for school information system development (this is an allowable BOS expenditure category).

### Pricing — Sekolah Swasta (Private Schools)

| Tier | Price (Monthly) | Target | Revenue Sources |
|------|----------------|--------|-----------------|
| Basic | Rp 300.000 | Small (< 200 students) | Subscription + Transaction Fee |
| Standard | Rp 750.000 | Medium (200–500 students) | Subscription + Transaction Fee |
| Premium | Rp 1.500.000 | Large (500+ students) | Subscription + Transaction Fee |
| Yearly Discount | Pay 10 months, get 12 | All tiers | — |

### Pricing — Sekolah Negeri (Public Schools)

| Tier | Price (Monthly) | Target | Notes |
|------|----------------|--------|-------|
| Negeri Basic | Rp 200.000 | Small (< 200 students) | No payment gateway features. Focus: attendance, rapor, Dapodik export, communication |
| Negeri Standard | Rp 500.000 | Medium (200–500 students) | Includes BOS reporting/ARKAS export helper |
| Negeri Premium | Rp 1.000.000 | Large (500+ students) | Full features except payment gateway |
| Yearly Discount | Pay 10 months, get 12 | All tiers | Align with BOS disbursement cycle (quarterly) |

> Negeri pricing is lower because: (1) no payment gateway revenue to offset costs, (2) BOS budget is limited, (3) volume play — there are ~170,000+ sekolah negeri in Indonesia. Even at lower pricing, the TAM is massive.

### Future: Freemium Option

- **Free:** Basic attendance + communication (hook for both negeri and swasta)
- **Paid:** Grading + rapor + Dapodik export + payment gateway + BOS reporting + advanced features

---

## 11. Key Edge Cases & Resilience

> Aligned with architecture document — these scenarios must be handled.

| # | Edge Case | Solution |
|---|-----------|----------|
| 1 | **Teacher loses internet during attendance** | `useOnlineStatus` composable detects offline. Store in localStorage. Service Worker background sync when online. |
| 2 | **Payment webhook arrives late or never** | Status page polls gateway every 30s for 5min. Daily `ReconcilePayments` job catches missed webhooks. Manual verification fallback. |
| 3 | **Two teachers mark attendance for same class** | `INSERT ON CONFLICT UPDATE` prevents duplicates. Last write wins. Show "last marked by X at Y". |
| 4 | **Student transfers between schools on platform** | Transfer workflow: source initiates → target accepts → record cloned to new `school_id` → original marked "transferred". Academic history preserved. |
| 5 | **Academic year rollover** | Admin triggers "New Academic Year": creates record, prompts class promotions (bulk), resets counters, carries student data. Old year = read-only. |
| 6 | **Subscription expires** | 7-day grace period with banner. After grace: read-only. After 30 days: inaccessible (data preserved). Never auto-delete. |
| 7 | **WhatsApp provider is down** | Dead letter queue. 3 retries with exponential backoff. Failed messages visible to admin for manual retry. |

---

## 12. Appendix: Module Dependency Map

```
M1 (School Profile) ──────────────────────┐
                                           │
M2 (Users & Roles) ───────────────────────┤
                                           │
M3 (Student Data) ────────────────────────┤── Foundation (required by all)
                                           │
M5 (Class/Schedule) ──────────────────────┘
                                           
M6 (Attendance) ──── requires M3, M5 ────── feeds into ──► M8 (Rapor)
                                           
M7 (Grading) ──── requires M3, M5 ────────── feeds into ──► M8 (Rapor)
                                           
M8 (Rapor) ──── requires M6, M7, M17 ────── viewed in ──► M11 (Parent Portal)
                                           
M9 (Finance) ──── requires M3 ────────────── viewed in ──► M11 (Parent Portal)
                                           
M10 (Communication) ──── requires M2 ─────── viewed in ──► M11 (Parent Portal)
                                           
M11 (Parent Portal) ──── aggregates M6, M9, M10
                                           
M12 (Exams) ──── requires M3, M5, M7
                                           
M13 (BOS) ──── standalone (requires M1)
                                           
M14 (Documents) ──── standalone (requires M1, M2)
                                           
M15 (Library) ──── requires M3
                                           
M16 (BK) ──── requires M3
                                           
M17 (Ekskul) ──── requires M3 ──── feeds into ──► M8 (Rapor)
                                           
M18 (PPDB) ──── standalone (feeds into M3)
                                           
M19 (Inventory) ──── standalone (requires M1)
                                           
M20 (Analytics) ──── requires M6, M7, M9
                                           
M21 (Gov Compliance / Dapodik Export) ──── requires M3, M4 ──── Phase 2: basic export / Phase 4: full compliance
```

---

### Related Documents

- **Architecture Document:** `docs/architecture.md` — detailed technical architecture, database schemas, code organization, deployment, and security
- **Brainstorm Plan:** `.cursor/plans/school_app_brainstorm_e0bbd14b.plan.md` — original brainstorm with full business context

---

*End of Feature Document*

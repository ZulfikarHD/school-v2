# Epic M4 — Teacher & Staff Management

> **Epic ID:** M4
> **Phase:** 2 (Academic Excellence)
> **Priority:** CORE
> **Sprint Target:** Sprint 8
> **Total Story Points:** 16 SP
> **Dependencies:** M1 (Multi-Tenancy), M2 (User & Role Management), M5 (Class/Schedule — basic)
> **Blocks:** M21 (Government Compliance — Dapodik export)

---

## Epic Overview

Manage teacher/staff profiles, subject/class assignments, and employment data. While basic teacher-class assignment exists in M2 (US-2.4), this module adds full teacher profiles with NUPTK, NIP, certification status, education background, employment tracking, and staff attendance — data required for Dapodik reporting.

---

## User Stories

### US-4.1: Teacher Biodata

**As an** Admin,
**I want** to manage complete teacher profiles (NUPTK, NIP, certification status, education),
**so that** the school has comprehensive staff records for internal use and government reporting.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Teacher profile form with: NUPTK, NIP, full name, gender, birth date, birth place, religion, address, phone, email
- [ ] Education fields: degree, institution, major, graduation year
- [ ] Certification: sertifikasi status, certification number, certified subject
- [ ] Profile photo with auto-resize
- [ ] Teacher list page with DataTable (searchable, filterable)
- [ ] Teacher detail page with tabbed sections (biodata, teaching load, attendance)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `teachers` migration (or extend `users` with JSONB `teacher_data`) with NUPTK, NIP, education, certification fields | Backend | 1h |
| 2 | Create `TeacherService` with CRUD + profile management | Backend | 1.5h |
| 3 | Create `TeacherController` with full CRUD | Backend | 1h |
| 4 | Create `StoreTeacherRequest` / `UpdateTeacherRequest` | Backend | 0.5h |
| 5 | Create `Teacher/Index.vue` with DataTable | Frontend | 2h |
| 6 | Create `Teacher/Create.vue` form (biodata + education + certification) | Frontend | 2h |
| 7 | Create `Teacher/Show.vue` detail page with tabs | Frontend | 1.5h |

---

### US-4.2: Teaching Load Management

**As an** Admin,
**I want** to view and manage the complete teaching load for each teacher (subjects + classes),
**so that** I can ensure balanced workload and complete assignment visibility.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Teaching load dashboard showing all teacher assignments
- [ ] Total hours per week calculated per teacher
- [ ] Visual indicator for overloaded teachers (> configurable threshold)
- [ ] Teaching load printable as report
- [ ] Assignment changes trigger notification to affected teacher

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `TeachingLoadService` to calculate hours per teacher | Backend | 1h |
| 2 | Create teaching load overview endpoint (all teachers + their assignments) | Backend | 0.5h |
| 3 | Create `Teacher/TeachingLoad.vue` overview page with table/cards | Frontend | 2h |
| 4 | Add workload indicator (color-coded: normal, heavy, overloaded) | Frontend | 0.5h |

---

### US-4.3: Homeroom Assignment (Wali Kelas)

**As an** Admin,
**I want** to assign Wali Kelas to each rombel per academic year,
**so that** each class has a designated homeroom teacher for rapor and parent communication.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] One Wali Kelas per rombel per academic year
- [ ] Wali Kelas assignment visible on class detail and teacher profile
- [ ] Cannot assign same teacher as Wali Kelas to two classes (validation)
- [ ] Assignment history preserved across academic years
- [ ] Wali Kelas auto-gains additional permissions for their class

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Add `homeroom_teacher_id` to `class_groups` table (if not already in M5) | Backend | 0.5h |
| 2 | Create assignment/unassignment in `ClassGroupService` | Backend | 0.5h |
| 3 | Create Wali Kelas assignment UI on class management page | Frontend | 1h |
| 4 | Auto-assign `wali_kelas` scope permissions when assigned | Backend | 0.5h |

---

### US-4.4: Staff Attendance

**As an** Admin,
**I want** to track daily attendance for teachers and staff,
**so that** the school has a record of staff presence for reporting.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Daily attendance input for all staff (Hadir, Sakit, Izin, Dinas Luar, Cuti)
- [ ] Attendance can be marked by admin or by staff self-check-in
- [ ] Monthly recap per staff member
- [ ] Late arrival tracking (time-in recorded)
- [ ] Attendance data exportable to Excel

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `staff_attendances` migration (school_id, user_id, date, status, time_in, time_out, note) | Backend | 0.5h |
| 2 | Create `StaffAttendanceStatus` enum (present, sick, permitted, official_duty, leave) | Backend | 0.5h |
| 3 | Create `StaffAttendanceService` with mark and recap methods | Backend | 1h |
| 4 | Create `StaffAttendanceController` CRUD | Backend | 0.5h |
| 5 | Create `StaffAttendance/Index.vue` with daily input table | Frontend | 2h |
| 6 | Create `StaffAttendance/Recap.vue` monthly summary | Frontend | 1.5h |
| 7 | Add Excel export for staff attendance recap | Backend | 0.5h |

---

### US-4.5: Employment Status Tracking

**As an** Admin,
**I want** to track employment status for each staff member (contract type, start date, status changes),
**so that** the school has proper HR records.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Employment fields: contract type (PNS, honorer, P3K, kontrak), start date, end date (if applicable)
- [ ] Status: active, on leave, resigned, retired, terminated
- [ ] Status changes logged with date, reason, and supporting document
- [ ] Employment history visible on teacher detail page
- [ ] Notification before contract expiry (30 days ahead)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `EmploymentType` enum (pns, honorer, p3k, kontrak) and `EmploymentStatus` enum | Backend | 0.5h |
| 2 | Create `employment_histories` migration (user_id, type, status, start_date, end_date, reason, document) | Backend | 0.5h |
| 3 | Create `EmploymentService` with status change + audit logging | Backend | 1h |
| 4 | Create employment history tab on teacher detail page | Frontend | 1.5h |
| 5 | Create contract expiry notification job (runs daily, alerts 30 days before) | Backend | 1h |

---

## Technical Notes

- **Teacher data** extends the `users` table — teachers are users with the "Guru" role. Additional teacher-specific data (NUPTK, NIP, education) stored in a related `teacher_profiles` table or JSONB column on `users`.
- **Teaching load** is computed from the `teacher_subject_class` pivot table created in M2 (US-2.4).
- **Staff attendance** is separate from student attendance (M6) — different table, different statuses, different UI.
- **Employment history** uses `spatie/laravel-activitylog` for immutable audit trail of status changes.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Teacher data schema conflicts with user table | Migration issues | Use separate `teacher_profiles` table linked to `users` |
| Staff attendance confused with student attendance | UX confusion | Separate menu items, different page URLs, different icons |
| Contract expiry notification missed | Missed renewals | Daily job with 30-day and 7-day reminders |

---

## Definition of Done (Epic Level)

- [ ] Complete teacher profile CRUD with NUPTK, education, certification
- [ ] Teaching load dashboard shows all assignments with hour calculations
- [ ] Wali Kelas assignment works per rombel per academic year
- [ ] Staff attendance input and monthly recap functional
- [ ] Employment status tracking with audit trail
- [ ] All data scoped by `school_id` (BelongsToSchool enforced)
- [ ] DataTable pattern consistent with M3 (Student) pages

---

### Related Files

- **Previous:** [`M03_STUDENT_INFORMATION.md`](M03_STUDENT_INFORMATION.md)
- **Next:** [`M05_CLASS_SCHEDULE.md`](M05_CLASS_SCHEDULE.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M4

# Epic M17 — Extracurricular (Ekskul)

> **Epic ID:** M17
> **Phase:** 3 (Complete School Operations)
> **Priority:** Important
> **Sprint Target:** Sprint 17
> **Total Story Points:** 16 SP
> **Dependencies:** M3 (Students)
> **Blocks:** M8 (Rapor — extracurricular section)

---

## Epic Overview

Manage extracurricular activities, student enrollment, per-session attendance, achievement tracking, and coach (pembina) assignment. Extracurricular data feeds directly into E-Rapor (M8) — the ekskul section of the report card is auto-populated from this module.

---

## User Stories

### US-17.1: Ekskul Catalog

**As an** Admin,
**I want** to manage the list of extracurricular activities with schedules,
**so that** students and parents know what's available.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Create ekskul: name, description, category (olahraga, seni, akademik, lainnya), schedule (day + time), location
- [ ] Ekskul list page with DataTable
- [ ] Active/inactive status toggle
- [ ] Capacity limit per ekskul (optional)
- [ ] Ekskul visible to students and parents

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `extracurriculars` migration (school_id, name, description, category enum, schedule_day, schedule_time, location, capacity, status, academic_year_id) | Backend | 0.5h |
| 2 | Create `Extracurricular` model with `BelongsToSchool` | Backend | 0.5h |
| 3 | Create `ExtracurricularService` CRUD | Backend | 0.5h |
| 4 | Create `Ekskul/Index.vue` with DataTable | Frontend | 1.5h |
| 5 | Create `Ekskul/Create.vue` form | Frontend | 1h |

---

### US-17.2: Student Enrollment

**As a** Student or Admin,
**I want** to enroll students in extracurricular activities,
**so that** participation is tracked officially.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Admin can enroll students in ekskul
- [ ] Students can self-register (if enabled by admin)
- [ ] Students can join multiple ekskul
- [ ] Enrollment per academic year
- [ ] Enrollment count shown on ekskul list (e.g., "24/30")
- [ ] Unenroll option available

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `extracurricular_student` pivot migration (extracurricular_id, student_id, academic_year_id, enrolled_at) | Backend | 0.5h |
| 2 | Create enrollment/unenrollment in `ExtracurricularService` | Backend | 0.5h |
| 3 | Create enrollment management UI (admin: add/remove students) | Frontend | 1.5h |
| 4 | Create student self-registration UI (browse + join) | Frontend | 1h |

---

### US-17.3: Ekskul Attendance

**As a** Coach/Pembina,
**I want** to track per-session attendance for my ekskul,
**so that** student participation is documented.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Mark attendance per session (date, Hadir/Tidak Hadir)
- [ ] Session list with attendance counts
- [ ] Attendance summary per student (total sessions attended / total sessions)
- [ ] Attendance percentage feeds into rapor (M8)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `ekskul_sessions` migration (extracurricular_id, date, notes) | Backend | 0.5h |
| 2 | Create `ekskul_attendances` migration (session_id, student_id, status) | Backend | 0.5h |
| 3 | Create `EkskulAttendanceService` with mark and recap | Backend | 1h |
| 4 | Create session attendance marking UI (reuse attendance pattern from M6) | Frontend | 2h |
| 5 | Create attendance summary per student per ekskul | Frontend | 0.5h |

---

### US-17.4: Achievement Tracking

**As a** Coach/Pembina,
**I want** to record competition results and awards for ekskul members,
**so that** achievements are documented and appear on rapor.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] Record achievement: student(s), event name, date, level (sekolah/kecamatan/kota/provinsi/nasional), result (juara 1/2/3, partisipasi)
- [ ] Achievement list per ekskul and per student
- [ ] Achievement feeds into rapor extracurricular section (M8)
- [ ] Certificate/photo upload supported

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `ekskul_achievements` migration (school_id, extracurricular_id, student_ids JSONB, event_name, date, level enum, result, description) | Backend | 0.5h |
| 2 | Create `AchievementService` CRUD | Backend | 0.5h |
| 3 | Create achievement recording UI | Frontend | 1.5h |
| 4 | Create achievement list/history | Frontend | 0.5h |

---

### US-17.5: Coach Assignment

**As an** Admin,
**I want** to assign pembina (coach) to each ekskul,
**so that** each activity has a responsible teacher.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Assign one or more pembina per ekskul
- [ ] Pembina can manage attendance and achievements for their ekskul
- [ ] Assignment per academic year
- [ ] Pembina sees their assigned ekskul in teacher dashboard

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Add `coach_ids` (or pivot table) to extracurriculars | Backend | 0.5h |
| 2 | Create coach assignment UI on ekskul management page | Frontend | 1h |
| 3 | Filter ekskul data by assigned coach for teacher views | Backend | 0.5h |

---

### US-17.6: Rapor Integration

**As the** System,
**I want** ekskul data (participation, attendance %, achievements) to auto-feed into rapor,
**so that** the extracurricular section of the report card is populated automatically.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Rapor ekskul section auto-populated: ekskul name, attendance %, achievements
- [ ] Data aggregated per student per semester
- [ ] Manual override available if needed (Wali Kelas can edit)
- [ ] Integration tested with M8 rapor generation

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create ekskul data aggregation service for rapor (attendance %, achievements list) | Backend | 1h |
| 2 | Create rapor integration endpoint (called by M8 during rapor generation) | Backend | 0.5h |
| 3 | Test integration with M8 rapor template | Backend | 0.5h |

---

## Technical Notes

- **Ekskul data feeds into M8 (Rapor)** — this is the primary cross-module integration point. The rapor extracurricular section must auto-populate.
- **Coach permissions**: Pembina has access to their assigned ekskul only — enforced at query level.
- **Attendance pattern**: Reuse the same UI patterns as M6 (student attendance) for consistency.
- **Achievements** are per-student (not per-ekskul), as the same student could win awards in different events.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Rapor integration data mismatch | Wrong data on rapor | Verification step before rapor generation, manual override |
| Coach doesn't track attendance | Missing rapor data | Reminder notifications, require attendance for rapor completion |
| Too many ekskul options overwhelm students | Decision paralysis | Limit self-registration to 3 ekskul max (configurable) |

---

## Definition of Done (Epic Level)

- [ ] Ekskul catalog with schedule and pembina
- [ ] Student enrollment (admin + self-registration)
- [ ] Per-session attendance tracking
- [ ] Achievement recording with competition details
- [ ] Rapor integration auto-populates ekskul section
- [ ] Coach can only manage their assigned ekskul

---

### Related Files

- **Previous:** [`M16_COUNSELING_BK.md`](M16_COUNSELING_BK.md)
- **Next:** [`M18_PPDB.md`](M18_PPDB.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M17

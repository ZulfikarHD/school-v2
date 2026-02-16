# Epic M5 — Class & Schedule Management

> **Epic ID:** M5
> **Phase:** 1 (basic) + Phase 2 (full)
> **Priority:** CORE
> **Sprint Target:** Sprint 3 (basic) + Sprint 10 (full enhancements)
> **Total Story Points:** 26 SP (16 basic + 10 enhanced)
> **Dependencies:** M1 (Multi-Tenancy), M2 (Users), M3 (Students)
> **Blocks:** M6 (Attendance), M7 (Grading), M8 (Rapor), M12 (Exams)

---

## Epic Overview

Manage class groups (Rombongan Belajar / Rombel) and schedule configuration. Phase 1 delivers basic rombel management with manual schedule entry. Phase 2 adds automatic timetable generation and conflict detection.

---

## Phase 1 — Basic (Sprint 3)

### US-5.1: Rombel Management

**As an** Admin,
**I want** to create and manage class groups (Rombel) per academic year,
**so that** students and teachers are organized into classes.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Create rombel with: name (e.g., "7A"), grade level, academic year, capacity
- [ ] Grade levels determined by school type (SD: 1–6, SMP: 7–9, SMA: 10–12)
- [ ] Rombel list page with DataTable showing student count, Wali Kelas, grade
- [ ] Rombel detail page showing enrolled students and assigned teachers
- [ ] Rombel can be edited (name, capacity) and soft-deleted

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `class_groups` migration (school_id, name, grade_level, academic_year_id, homeroom_teacher_id, capacity) | Backend | 0.5h |
| 2 | Create `ClassGroup` model with `BelongsToSchool`, relationships to students, teachers, academic year | Backend | 0.5h |
| 3 | Create `ClassGroupService` with CRUD + enrollment management | Backend | 1h |
| 4 | Create `ClassGroupController` with full CRUD | Backend | 0.5h |
| 5 | Create `ClassGroup/Index.vue` with DataTable | Frontend | 2h |
| 6 | Create `ClassGroup/Create.vue` form | Frontend | 1h |
| 7 | Create `ClassGroup/Show.vue` detail with student list + teacher assignments | Frontend | 2h |

---

### US-5.2: Homeroom Assignment

**As an** Admin,
**I want** to assign a Wali Kelas (homeroom teacher) to each rombel,
**so that** each class has a designated teacher for rapor and parent communication.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Wali Kelas selectable from teacher list when creating/editing rombel
- [ ] One teacher can only be Wali Kelas for one rombel per academic year
- [ ] Wali Kelas displayed prominently on rombel detail page
- [ ] Wali Kelas auto-gains permissions for their class's students

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Add `homeroom_teacher_id` FK to `class_groups` with unique constraint per academic year | Backend | 0.5h |
| 2 | Add validation: teacher not already Wali Kelas in another class this year | Backend | 0.5h |
| 3 | Create Wali Kelas selector dropdown on rombel form | Frontend | 1h |
| 4 | Display Wali Kelas info on rombel detail page | Frontend | 0.5h |

---

### US-5.3: Student Enrollment in Rombel

**As an** Admin,
**I want** to assign students to a rombel for the current academic year,
**so that** students appear in class lists for attendance and grading.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Assign individual students to a rombel
- [ ] Bulk assign: select multiple students → assign to rombel
- [ ] Student can only be in one rombel per academic year
- [ ] Enrollment count shown on rombel list (e.g., "28/32")
- [ ] Unenroll student from rombel (e.g., transfer mid-year)
- [ ] Historical enrollments preserved across academic years

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `class_group_student` pivot migration (class_group_id, student_id, academic_year_id, enrolled_at) | Backend | 0.5h |
| 2 | Create enrollment/unenrollment methods in `ClassGroupService` | Backend | 1h |
| 3 | Add unique constraint: student + academic_year (one class per year) | Backend | 0.5h |
| 4 | Create student enrollment UI on rombel detail page (add/remove students) | Frontend | 2h |
| 5 | Create bulk enrollment dialog | Frontend | 1.5h |

---

### US-5.4: Basic Schedule Entry

**As an** Admin,
**I want** to manually enter the weekly schedule (day, time, subject, teacher, room) for each class,
**so that** teachers and students know their timetable.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Schedule entry per rombel: day, start time, end time, subject, teacher, room
- [ ] Weekly grid view showing the complete schedule
- [ ] Basic room double-booking validation (warning, not blocking)
- [ ] Schedule viewable by teachers (their classes) and students (their class)
- [ ] Print-friendly schedule view

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `subjects` migration (school_id, name, code, school_type, grade_levels) | Backend | 0.5h |
| 2 | Create `rooms` migration (school_id, name, type, capacity) | Backend | 0.5h |
| 3 | Create `schedules` migration (school_id, class_group_id, subject_id, teacher_id, room_id, day, start_time, end_time, academic_year_id) | Backend | 0.5h |
| 4 | Create `Subject`, `Room`, `Schedule` models with `BelongsToSchool` | Backend | 0.5h |
| 5 | Create `ScheduleService` with CRUD + basic conflict detection | Backend | 1.5h |
| 6 | Create `ScheduleController` CRUD | Backend | 0.5h |
| 7 | Create `Schedule/WeeklyGrid.vue` — visual weekly timetable | Frontend | 3h |
| 8 | Create schedule entry form (day, time, subject, teacher, room) | Frontend | 1.5h |
| 9 | Add basic room conflict warning | Frontend | 0.5h |

---

### US-5.5: Room Allocation

**As an** Admin,
**I want** to manage rooms and their allocation to classes,
**so that** rooms are utilized efficiently.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Room CRUD: name, type (classroom, lab, sports hall), capacity
- [ ] Room assignment in schedule entry
- [ ] Room utilization overview (which rooms are used when)
- [ ] Basic conflict flag if room double-booked

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `RoomType` enum (classroom, lab, library, sports_hall, multipurpose) | Backend | 0.5h |
| 2 | Create `RoomService` with CRUD | Backend | 0.5h |
| 3 | Create `Room/Index.vue` with DataTable | Frontend | 1h |
| 4 | Create `Room/Create.vue` form | Frontend | 0.5h |
| 5 | Create room utilization grid (shows occupied/free slots per day) | Frontend | 2h |

---

## Phase 2 — Full Enhancements (Sprint 10)

### US-5.6: Auto Schedule Generation

**As an** Admin,
**I want** the system to suggest a timetable based on teacher availability and room constraints,
**so that** I don't have to manually arrange 100+ schedule slots.

**Story Points:** 5
**Priority:** Should

**Acceptance Criteria:**
- [ ] Admin provides constraints: teacher availability, required hours per subject, room preferences
- [ ] System generates a suggested schedule respecting all constraints
- [ ] Admin can review, modify, and approve the generated schedule
- [ ] Generation handles: no teacher double-booking, no room double-booking, subject hours met
- [ ] Generation completes in < 30 seconds for a typical school

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Research timetable generation algorithms (constraint satisfaction) | Backend | 2h |
| 2 | Create `ScheduleGeneratorService` with constraint-based algorithm | Backend | 6h |
| 3 | Create teacher availability input (available/unavailable per slot) | Frontend | 2h |
| 4 | Create generation trigger with progress indicator | Frontend | 1h |
| 5 | Create generated schedule review/edit UI | Frontend | 2h |

---

### US-5.7: Conflict Detection

**As the** System,
**I want** to detect and alert when a teacher or room is double-booked,
**so that** schedule conflicts are caught before they cause problems.

**Story Points:** 3
**Priority:** Must (Phase 2)

**Acceptance Criteria:**
- [ ] Real-time validation when adding/editing schedule entries
- [ ] Visual conflict indicators (red highlight) on weekly grid
- [ ] Conflict details shown: who/what is conflicted, which slots overlap
- [ ] Option to force-save with conflict (with warning acknowledgment)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `ConflictDetectionService` (teacher conflicts, room conflicts) | Backend | 1.5h |
| 2 | Create conflict check endpoint (real-time validation on schedule form) | Backend | 0.5h |
| 3 | Add visual conflict highlighting to weekly grid | Frontend | 1.5h |
| 4 | Create conflict detail popover with explanation | Frontend | 1h |

---

### US-5.8: Teacher Substitution

**As an** Admin,
**I want** to manage guru pengganti (substitute teacher) when a teacher is absent,
**so that** classes are not left without a teacher.

**Story Points:** 2
**Priority:** Could

**Acceptance Criteria:**
- [ ] Admin can assign a substitute for a specific date/period
- [ ] Substitute receives notification
- [ ] Original schedule preserved, substitution shown as override
- [ ] Substitution history tracked

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `schedule_substitutions` migration (schedule_id, substitute_teacher_id, date, reason) | Backend | 0.5h |
| 2 | Create `SubstitutionService` with CRUD | Backend | 0.5h |
| 3 | Create substitution management UI on schedule page | Frontend | 1.5h |
| 4 | Send notification to substitute teacher | Backend | 0.5h |

---

## Technical Notes

- **Grade levels** are determined by `SchoolType` config from M1: SD (1–6), SMP (7–9), SMA (10–12), SMK (10–12/13).
- **Schedule** is per-rombel, per-academic-year. Changing academic year shows different schedules.
- **Auto schedule generation** (Phase 2) is a constraint satisfaction problem — consider using a greedy algorithm with backtracking for small schools (< 50 teachers). Full CSP solver may be needed for large SMK schools.
- **Subject seeding**: Standard subjects per school type can be seeded (Matematika, Bahasa Indonesia, etc.) but schools can add custom subjects.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Schedule generation too slow for large schools | Admin frustration | Set max timeout, provide partial results, allow manual adjustments |
| Complex constraint satisfaction bugs | Invalid schedules | Extensive testing, allow manual override, schedule validation endpoint |
| Room management rarely used by small schools | Wasted development | Make room optional in schedule — default to "no room assigned" |

---

## Definition of Done (Epic Level)

### Phase 1 (Sprint 3)
- [ ] Rombel CRUD with student enrollment
- [ ] Wali Kelas assignment functional
- [ ] Manual schedule entry with weekly grid view
- [ ] Room management with basic conflict warning
- [ ] Subject CRUD

### Phase 2 (Sprint 10)
- [ ] Auto schedule generation produces valid timetables
- [ ] Real-time conflict detection on schedule editing
- [ ] Teacher substitution management
- [ ] All schedule data accessible by teachers and students

---

### Related Files

- **Previous:** [`M04_TEACHER_STAFF.md`](M04_TEACHER_STAFF.md)
- **Next:** [`M06_ATTENDANCE.md`](M06_ATTENDANCE.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M5

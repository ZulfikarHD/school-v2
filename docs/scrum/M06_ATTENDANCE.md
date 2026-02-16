# Epic M6 — Attendance System (Absensi)

> **Epic ID:** M6
> **Phase:** 1 (MVP)
> **Priority:** CORE
> **Sprint Target:** Sprint 4
> **Total Story Points:** 26 SP
> **Dependencies:** M3 (Students), M5 (Class/Schedule — basic)
> **Blocks:** M8 (Rapor — attendance section), M11 (Parent Portal — attendance view), M20 (Analytics)

---

## Epic Overview

Digitize daily attendance tracking with automatic parent WhatsApp notifications. This is the highest-frequency daily operation — teachers use it every morning. The UI must be optimized for speed (< 2 minutes per class) and resilience (offline support for unstable internet). SD/TK uses daily attendance; SMP/SMA uses per-subject attendance.

---

## User Stories

### US-6.1: Daily Attendance Input

**As a** Teacher,
**I want** to mark each student as Hadir, Sakit, Izin, or Alpa for today,
**so that** attendance is recorded digitally instead of on paper.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Teacher sees their classes for today on the attendance page
- [ ] Selecting a class shows student list (all defaulted to "Hadir")
- [ ] Teacher taps absent students and selects reason (Sakit, Izin, Alpa)
- [ ] "Simpan" saves all records in a single transaction
- [ ] Confirmation message: "Absensi berhasil disimpan"
- [ ] Already-marked attendance shows as read-only with "Edit" option
- [ ] "Last marked by [teacher] at [time]" indicator shown

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `attendances` migration (school_id, student_id, class_group_id, subject_id nullable, date, status, marked_by, note) | Backend | 0.5h |
| 2 | Create `AttendanceStatus` enum (present, sick, permitted, absent) | Backend | 0.5h |
| 3 | Create `Attendance` model with `BelongsToSchool` + composite unique indexes | Backend | 0.5h |
| 4 | Create partial unique indexes: per-subject (SMP/SMA) and daily (SD) | Backend | 0.5h |
| 5 | Create `AttendanceService` with `markBulk()` using `INSERT ON CONFLICT UPDATE` | Backend | 2h |
| 6 | Create `AttendanceController` with `index()`, `store()`, `update()` | Backend | 1h |
| 7 | Create `MarkAttendanceRequest` with validation | Backend | 0.5h |
| 8 | Create `Attendance/Index.vue` — today's classes list for the teacher | Frontend | 1.5h |
| 9 | Create `Attendance/Mark.vue` — student checklist with status toggles | Frontend | 3h |
| 10 | Implement optimistic UI (show success immediately, sync in background) | Frontend | 1h |

---

### US-6.2: Manual Entry (Primary Method)

**As a** Teacher,
**I want** a simple checklist UI where all students default to "Hadir" and I only mark exceptions,
**so that** I can complete attendance in under 2 minutes.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Student list shows names with "Hadir" pre-selected
- [ ] Tapping a student toggles through: Hadir → Sakit → Izin → Alpa
- [ ] Touch targets are large (min 48px) for mobile use
- [ ] Optional note field per student (e.g., "demam", "acara keluarga")
- [ ] "Select All Hadir" button for quick confirmation when all present
- [ ] Keyboard shortcut support for desktop (Tab + H/S/I/A)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `AttendanceStudentRow.vue` — individual student row with status toggle | Frontend | 2h |
| 2 | Implement touch-optimized toggle (swipe or tap cycle) | Frontend | 1h |
| 3 | Add "Select All Hadir" quick action | Frontend | 0.5h |
| 4 | Add optional note field (collapsible per student) | Frontend | 0.5h |
| 5 | Add keyboard shortcuts for desktop usage | Frontend | 0.5h |

---

### US-6.3: QR Code Scan Attendance

**As a** Teacher,
**I want** to scan a QR code from a student's ID card to mark them present,
**so that** attendance for older students can be faster via self-check-in.

**Story Points:** 3
**Priority:** Could

**Acceptance Criteria:**
- [ ] QR code scanner uses device camera (no special hardware)
- [ ] Scanned QR resolves to student ID and marks as "Hadir"
- [ ] Audio/visual feedback on successful scan
- [ ] Invalid or duplicate scans handled gracefully
- [ ] Scanner works alongside manual entry (hybrid approach)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Integrate browser-based QR scanner library (e.g., `vue-qrcode-reader`) | Frontend | 2h |
| 2 | Create `Attendance/QrScan.vue` — camera view with scan feedback | Frontend | 2h |
| 3 | Create QR validation endpoint (resolve student ID, mark present) | Backend | 1h |
| 4 | Handle edge cases: duplicate scan, wrong class, expired QR | Backend | 0.5h |

---

### US-6.4: Attendance Type by School Level

**As the** System,
**I want** to automatically use daily attendance for SD/TK and per-subject attendance for SMP/SMA,
**so that** the attendance mode matches each school's operational reality.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] SD/TK: one attendance record per student per day (`subject_id` = NULL)
- [ ] SMP/SMA: one attendance record per student per subject per day (`subject_id` populated)
- [ ] School type config (from M1) determines the mode automatically
- [ ] Teachers see appropriate UI based on school type
- [ ] Unique indexes enforce correct behavior per type

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create conditional logic in `AttendanceService` based on school type | Backend | 1h |
| 2 | Create partial unique indexes for both modes (see technical notes) | Backend | 0.5h |
| 3 | Adjust attendance UI to show/hide subject selector based on school type | Frontend | 1h |

---

### US-6.5: Auto Parent WhatsApp Notification

**As the** System,
**I want** to automatically send a WhatsApp message to parents when their child is marked absent (Sakit/Izin/Alpa),
**so that** parents are immediately informed of their child's absence.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] WhatsApp notification sent within 5 minutes of marking
- [ ] Message includes: student name, date, absence type, teacher name
- [ ] Only sent for Sakit, Izin, and Alpa (not for Hadir)
- [ ] Message in Bahasa Indonesia using school's configured template
- [ ] Failed notifications queued for retry (3 attempts)
- [ ] Failed messages visible to admin in dead letter queue
- [ ] Parents can be opted out of notifications

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `StudentMarkedAbsent` event | Backend | 0.5h |
| 2 | Create `NotifyParentOfAbsence` listener (queued) | Backend | 1h |
| 3 | Create `AbsenceNotification` notification class with `WhatsAppChannel` | Backend | 1h |
| 4 | Create WhatsApp message template (stored in DB, editable by admin) | Backend | 0.5h |
| 5 | Implement retry logic (3 attempts, exponential backoff) | Backend | 0.5h |
| 6 | Create dead letter queue view for failed notifications (admin page) | Frontend | 1.5h |
| 7 | Write feature test with mocked Fonnte API | Backend | 1h |

---

### US-6.6: Monthly/Semester Attendance Recap

**As an** Admin or Teacher,
**I want** to view attendance summaries per student and per class for any period,
**so that** I can identify attendance patterns and generate reports.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Monthly recap: table showing days present/sick/permitted/absent per student
- [ ] Semester recap: aggregated totals per student (feeds into rapor M8)
- [ ] Class overview: percentage attendance per class per month
- [ ] Exportable to Excel
- [ ] Color-coded: students with > 3 days absent highlighted

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `AttendanceRecapService` with monthly and semester aggregation queries | Backend | 1.5h |
| 2 | Create recap API endpoint (class_group_id, month/semester) | Backend | 0.5h |
| 3 | Create `Attendance/Recap.vue` — monthly summary table with color coding | Frontend | 2h |
| 4 | Add Excel export for recap data | Backend | 1h |
| 5 | Add student-level drill-down (click student → daily breakdown) | Frontend | 1h |

---

### US-6.7: Teacher/Staff Attendance

**As an** Admin,
**I want** to track daily attendance for teachers and staff,
**so that** staff presence is recorded (handled primarily in M4, referenced here for completeness).

**Story Points:** 1
**Priority:** Should

> Note: Full implementation in M4 (US-4.4). This story is a placeholder to ensure integration point.

**Acceptance Criteria:**
- [ ] Staff attendance accessible from same attendance menu section
- [ ] Separate page and data from student attendance

---

### US-6.8: Late Tracking

**As a** Teacher,
**I want** to record when a student arrives late (keterlambatan),
**so that** late arrival patterns are tracked and visible.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] "Late" can be marked alongside "Hadir" (present but late)
- [ ] Late arrival time recorded
- [ ] Late count visible in monthly recap
- [ ] Repeated lateness (> configurable threshold) flags student for attention

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Add `is_late` boolean and `arrived_at` timestamp to `attendances` table | Backend | 0.5h |
| 2 | Update `AttendanceService` to handle late marking | Backend | 0.5h |
| 3 | Add "Late" toggle on attendance marking UI for present students | Frontend | 1h |
| 4 | Include late data in monthly recap | Frontend | 0.5h |
| 5 | Create late threshold alert (configurable per school) | Backend | 0.5h |

---

## Technical Notes

- **Morning rush mitigation** (07:00–08:00 WIB): Bulk upsert in single transaction. Optimistic UI. `INSERT ON CONFLICT UPDATE` for concurrent marking.
- **Unique indexes** prevent duplicates:
  ```sql
  -- Per-subject attendance (SMP/SMA)
  CREATE UNIQUE INDEX idx_attendance_unique
      ON attendances(school_id, student_id, date, subject_id)
      WHERE subject_id IS NOT NULL;
  -- Daily attendance (SD/TK)
  CREATE UNIQUE INDEX idx_attendance_unique_daily
      ON attendances(school_id, student_id, date)
      WHERE subject_id IS NULL;
  ```
- **Offline support**: `useOnlineStatus` composable detects offline. Store attendance in localStorage. Background sync via Service Worker when connectivity returns.
- **Event-driven notifications**: `StudentMarkedAbsent` event → `NotifyParentOfAbsence` listener → queued WhatsApp notification via `notifications-high` queue.
- **Recap data** feeds directly into rapor attendance section (M8).

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Teacher loses internet during attendance | Data lost | `useOnlineStatus` + localStorage fallback + Service Worker sync |
| Two teachers mark same class simultaneously | Data conflict | `INSERT ON CONFLICT UPDATE` — last write wins, show "last marked by" |
| WhatsApp notification flood (200+ parents at 07:30) | Rate limit hit | Queue with `RateLimited::perMinute(30)`, priority queue |
| Morning rush DB contention | Slow response | Bulk upsert in single transaction, DB connection pooling |

---

## Definition of Done (Epic Level)

- [ ] Teacher can mark attendance for a class in < 2 minutes
- [ ] All four status types (Hadir, Sakit, Izin, Alpa) supported
- [ ] Parents receive WhatsApp notification within 5 minutes of marking
- [ ] Monthly recap shows total days for each status per student
- [ ] Attendance data viewable by Wali Kelas and Admin
- [ ] Offline attendance works (store locally, sync when online)
- [ ] School type determines daily vs per-subject mode
- [ ] Late tracking records arrival time
- [ ] Cross-tenant isolation verified

---

### Related Files

- **Previous:** [`M05_CLASS_SCHEDULE.md`](M05_CLASS_SCHEDULE.md)
- **Next:** [`M07_GRADING_ASSESSMENT.md`](M07_GRADING_ASSESSMENT.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M6

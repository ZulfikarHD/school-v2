# Epic M16 — Counseling / BK (Bimbingan Konseling)

> **Epic ID:** M16
> **Phase:** 3 (Complete School Operations)
> **Priority:** Important
> **Sprint Target:** Sprint 17
> **Total Story Points:** 21 SP
> **Dependencies:** M3 (Students)
> **Blocks:** —

---

## Epic Overview

Confidential counseling and behavioral tracking system for Guru BK (counseling teachers). Tracks counseling sessions, behavioral incidents (pelanggaran), merit/demerit points, home visits, career guidance, and at-risk student flagging. Confidentiality is paramount — only Guru BK and authorized staff can access counseling data.

---

## User Stories

### US-16.1: Counseling Records

**As a** Guru BK,
**I want** to record confidential counseling sessions per student,
**so that** I have a documented history of each student's counseling interactions.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Create session record: student, date, type (individual/group), topic, notes, follow-up actions
- [ ] Sessions are confidential — only Guru BK and Kepala Sekolah can access
- [ ] Session history per student with timeline view
- [ ] Attachments supported (forms, consent documents)
- [ ] Session notes encrypted at rest

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `counseling_sessions` migration (school_id, student_id, counselor_id, date, type, topic, notes encrypted, follow_up, status) | Backend | 0.5h |
| 2 | Create `CounselingSession` model with `BelongsToSchool` + strict access policy | Backend | 0.5h |
| 3 | Create `CounselingPolicy` — only Guru BK + Kepala Sekolah | Backend | 0.5h |
| 4 | Create `CounselingService` CRUD | Backend | 1h |
| 5 | Create `BK/Sessions/Index.vue` — session list per student | Frontend | 2h |
| 6 | Create `BK/Sessions/Create.vue` form (type, topic, notes, follow-up) | Frontend | 1.5h |
| 7 | Create student counseling timeline view | Frontend | 1h |

---

### US-16.2: Behavioral Incidents (Pelanggaran)

**As a** Guru BK or Teacher,
**I want** to report student behavioral incidents/violations,
**so that** behavioral patterns are tracked and addressed.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Report incident: student, date, description, category, severity (ringan/sedang/berat), reporter
- [ ] Incident categories configurable by school (terlambat, bolos, berkelahi, merokok, etc.)
- [ ] Teachers can report; Guru BK manages and follows up
- [ ] Incident history per student
- [ ] Notification to Wali Kelas when incident reported

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `behavioral_incidents` migration (school_id, student_id, reporter_id, date, category, severity enum, description, action_taken) | Backend | 0.5h |
| 2 | Create `IncidentSeverity` enum (ringan, sedang, berat) | Backend | 0.5h |
| 3 | Create `IncidentService` with report + follow-up | Backend | 1h |
| 4 | Create `BK/Incidents/Index.vue` DataTable with severity color coding | Frontend | 1.5h |
| 5 | Create `BK/Incidents/Create.vue` report form (accessible by all teachers) | Frontend | 1h |
| 6 | Send notification to Wali Kelas on new incident | Backend | 0.5h |

---

### US-16.3: Point/Merit System

**As a** Guru BK,
**I want** a point system to track student behavior over time,
**so that** there's an objective measure of behavioral patterns.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Students start with base points (e.g., 100)
- [ ] Points deducted for violations (based on severity)
- [ ] Points added for positive behavior/achievements
- [ ] Current point total visible per student
- [ ] Point history timeline
- [ ] Threshold alerts: < 50 points → at-risk flagging

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `student_merit_points` migration (school_id, student_id, amount, reason, type enum credit/debit, reference_id) | Backend | 0.5h |
| 2 | Create `MeritPointService` with add, deduct, calculate balance | Backend | 1h |
| 3 | Create merit point dashboard per student (balance, history chart) | Frontend | 1.5h |
| 4 | Auto-deduct points on incident creation (based on severity config) | Backend | 0.5h |

---

### US-16.4: Home Visit Log

**As a** Guru BK,
**I want** to document home visit activities,
**so that** home visits are recorded with photos and notes.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] Record home visit: student, date, purpose, findings, notes, photos
- [ ] Photo upload from mobile device
- [ ] Home visit history per student
- [ ] GPS location optionally recorded

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `home_visits` migration (school_id, student_id, counselor_id, date, purpose, findings, notes, location) | Backend | 0.5h |
| 2 | Create `HomeVisitService` CRUD | Backend | 0.5h |
| 3 | Create `BK/HomeVisits/Create.vue` form with photo upload | Frontend | 1.5h |
| 4 | Create home visit history list | Frontend | 0.5h |

---

### US-16.5: Career Guidance

**As a** Guru BK,
**I want** to record career guidance sessions for SMP/SMA students,
**so that** career counseling is documented and tracked.

**Story Points:** 2
**Priority:** Could

**Acceptance Criteria:**
- [ ] Career counseling record: student, date, interests, aptitude notes, recommended paths
- [ ] Only visible for SMP/SMA/SMK students (not SD)
- [ ] Career guidance history per student
- [ ] Export as part of student profile

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `career_guidance` migration (student_id, date, interests JSONB, aptitude_notes, recommendations) | Backend | 0.5h |
| 2 | Create career guidance form and history view | Frontend | 1.5h |

---

### US-16.6: At-Risk Student Flagging

**As the** System,
**I want** to automatically flag students who show at-risk indicators,
**so that** Guru BK can proactively intervene.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] At-risk triggers (configurable): merit points below threshold, > X absences in a month, > Y incidents
- [ ] Flagged students appear in Guru BK dashboard
- [ ] Flag includes reason(s) for flagging
- [ ] Guru BK can acknowledge and create intervention plan
- [ ] Re-flag if triggers persist after acknowledgment

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `AtRiskDetectionService` with configurable threshold checks | Backend | 1.5h |
| 2 | Create `at_risk_flags` migration (student_id, reasons JSONB, flagged_at, acknowledged_by, intervention_notes) | Backend | 0.5h |
| 3 | Create scheduled job to run at-risk checks daily | Backend | 0.5h |
| 4 | Create Guru BK dashboard with at-risk student list | Frontend | 1.5h |
| 5 | Create intervention plan form (acknowledge + plan) | Frontend | 0.5h |

---

### US-16.7: Intervention Tracking

**As a** Guru BK,
**I want** to track interventions and their outcomes for at-risk students,
**so that** I can measure the effectiveness of counseling efforts.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Create intervention: student, type (counseling, parent meeting, referral), plan, expected outcome
- [ ] Track intervention progress (ongoing, completed, escalated)
- [ ] Record outcome and effectiveness rating
- [ ] Intervention timeline per student

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `interventions` migration (school_id, student_id, counselor_id, type, plan, status, outcome, effectiveness_rating) | Backend | 0.5h |
| 2 | Create `InterventionService` with CRUD + status transitions | Backend | 0.5h |
| 3 | Create intervention management UI (create, update status, record outcome) | Frontend | 2h |
| 4 | Create intervention timeline on student BK profile | Frontend | 0.5h |

---

## Technical Notes

- **Confidentiality is critical**: Counseling records are restricted to Guru BK and Kepala Sekolah via `CounselingPolicy`. Regular teachers and parents cannot access this data.
- **Counseling notes encrypted**: `$casts = ['notes' => 'encrypted']` for sensitive session content.
- **At-risk detection** runs as a daily scheduled job. Checks attendance (from M6), merit points, and incidents.
- **Behavioral incidents** can be reported by any teacher but managed by Guru BK — different permission levels.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Counseling data leaked | Privacy violation, trust breakdown | Strict policy enforcement, encrypted notes, audit logging |
| At-risk false positives | Unnecessary stigmatization | Configurable thresholds, Guru BK review before any action |
| Teachers don't report incidents | Incomplete behavioral data | Easy reporting UI, mobile-friendly, reminder in teacher dashboard |

---

## Definition of Done (Epic Level)

- [ ] Counseling records confidential (access restricted to BK only)
- [ ] Behavioral incidents reportable by teachers, managed by BK
- [ ] Point system tracks behavior over time
- [ ] At-risk students flagged based on configurable thresholds
- [ ] Home visit documentation with photos
- [ ] Intervention tracking with outcomes
- [ ] All counseling notes encrypted at rest

---

### Related Files

- **Previous:** [`M15_LIBRARY.md`](M15_LIBRARY.md)
- **Next:** [`M17_EXTRACURRICULAR.md`](M17_EXTRACURRICULAR.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M16

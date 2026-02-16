# Epic M18 — PPDB Online (New Student Admission)

> **Epic ID:** M18
> **Phase:** 4 (Scale & Differentiate)
> **Priority:** Nice-to-Have
> **Sprint Target:** Sprint 20
> **Total Story Points:** 24 SP
> **Dependencies:** M1 (School Profile) — feeds into M3 (Students)
> **Blocks:** —

---

## Epic Overview

Digitize the new student admission (PPDB — Penerimaan Peserta Didik Baru) process. Parents register online, upload required documents, and the school manages selection, announcements, and re-registration. Accepted students are automatically created in the Student Information System (M3).

---

## User Stories

### US-18.1: Online Registration Form

**As a** prospective parent,
**I want** to register my child online for admission,
**so that** I don't need to physically visit the school.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Public registration form (no login required) on school subdomain
- [ ] Student data: name, birth date, gender, previous school, NISN (if existing)
- [ ] Parent/guardian data: name, phone, address, occupation
- [ ] Form validates required fields and formats
- [ ] Confirmation page with registration number
- [ ] WhatsApp confirmation sent with registration number

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `ppdb_registrations` migration (school_id, registration_number, student_data JSONB, parent_data JSONB, status, score, academic_year_id) | Backend | 0.5h |
| 2 | Create `PpdbService` with register, validate, process | Backend | 1.5h |
| 3 | Create public registration form (multi-step: student → parent → review → submit) | Frontend | 4h |
| 4 | Create registration confirmation page with number | Frontend | 0.5h |
| 5 | Send WhatsApp confirmation with registration number | Backend | 0.5h |

---

### US-18.2: Document Upload

**As a** prospective parent,
**I want** to upload required admission documents,
**so that** the school has everything needed for selection.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Required documents: akta kelahiran, KK, foto, ijazah/SKHUN (if applicable)
- [ ] File validation (type, size — max 5MB per file)
- [ ] Upload progress indicator
- [ ] Documents reviewable by admission committee
- [ ] Missing document indicator

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Configure `spatie/media-library` collection for PPDB documents | Backend | 0.5h |
| 2 | Add document upload step to registration form | Frontend | 2h |
| 3 | Create document review panel for admin (thumbnail + full view) | Frontend | 1h |

---

### US-18.3: Selection Criteria

**As an** Admin,
**I want** to define selection criteria and score applicants,
**so that** student selection is fair and transparent.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Configurable criteria: age, distance, test score, previous grades, siblings, custom
- [ ] Each criteria has a weight/score range
- [ ] Auto-score applicants based on configurable rules
- [ ] Manual score override for committee review
- [ ] Ranked applicant list by total score

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `ppdb_criteria` migration (school_id, name, type, weight, scoring_rules JSONB) | Backend | 0.5h |
| 2 | Create `PpdbScoringService` with auto-scoring + manual override | Backend | 2h |
| 3 | Create criteria configuration UI | Frontend | 1.5h |
| 4 | Create applicant scoring/ranking page | Frontend | 2h |

---

### US-18.4: Acceptance Announcement

**As an** Admin,
**I want** to announce accepted students online and via WhatsApp,
**so that** results are communicated quickly and transparently.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Admin sets accepted/waitlisted/rejected status per applicant
- [ ] Batch accept by rank (top N applicants accepted)
- [ ] Public results page (search by registration number)
- [ ] WhatsApp notification sent to parents with result
- [ ] Results page shows next steps for accepted students

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create acceptance workflow (accept/waitlist/reject) | Backend | 1h |
| 2 | Create public results page (registration number lookup) | Frontend | 1.5h |
| 3 | Send WhatsApp acceptance/rejection notifications | Backend | 1h |
| 4 | Create batch acceptance by rank | Backend | 0.5h |

---

### US-18.5: Re-Registration (Daftar Ulang)

**As an** accepted student's parent,
**I want** to complete re-registration online,
**so that** I confirm enrollment without visiting the school.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Re-registration form for accepted students
- [ ] Collect additional data (uniform size, health info)
- [ ] Payment integration for registration fee (via M9)
- [ ] Deadline enforcement (auto-revoke if not re-registered by deadline)
- [ ] Confirmed re-registrations auto-create student record in M3

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create re-registration form and workflow | Backend | 1h |
| 2 | Create re-registration UI | Frontend | 1.5h |
| 3 | Integrate with M9 for registration fee payment | Backend | 1h |
| 4 | Create auto-student-creation on confirmed re-registration (feeds into M3) | Backend | 1h |

---

### US-18.6: Quota Management

**As an** Admin,
**I want** to set and track class capacity for each grade level,
**so that** we don't accept more students than capacity allows.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Set quota per grade level per academic year
- [ ] Real-time dashboard: accepted / quota per grade
- [ ] Warning when quota is nearing full
- [ ] Waitlist management when quota is full

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `ppdb_quotas` migration (school_id, grade_level, academic_year_id, capacity, accepted_count) | Backend | 0.5h |
| 2 | Create quota management service | Backend | 1h |
| 3 | Create quota dashboard with real-time counts | Frontend | 1.5h |
| 4 | Create waitlist management UI | Frontend | 1h |

---

## Technical Notes

- **Public registration form** is accessible without authentication on the school's subdomain (`sdnegeri5.platform.id/ppdb`).
- **Registration data stored as JSONB** to accommodate different requirements per school.
- **Auto-creation of student records**: On confirmed re-registration, the system creates a Student record (M3) and optionally a Parent user account.
- **PPDB is seasonal** — runs once per year during enrollment period. Active/inactive period managed by admin.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| High traffic during registration opening | System overload | Queue registrations, rate limit, cache public pages |
| Parents submit incomplete data | Selection delayed | Required field validation, document completeness check |
| Selection criteria disputes | Parent complaints | Transparent scoring visible to parents, clear criteria published upfront |

---

## Definition of Done (Epic Level)

- [ ] Online registration form accessible publicly
- [ ] Document upload functional
- [ ] Selection criteria configurable with auto-scoring
- [ ] Acceptance results published online and via WhatsApp
- [ ] Re-registration with payment integration
- [ ] Quota management with real-time tracking
- [ ] Accepted students auto-created in M3

---

### Related Files

- **Previous:** [`M17_EXTRACURRICULAR.md`](M17_EXTRACURRICULAR.md)
- **Next:** [`M19_INVENTORY.md`](M19_INVENTORY.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M18

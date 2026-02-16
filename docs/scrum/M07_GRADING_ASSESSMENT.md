# Epic M7 — Grading & Assessment (Penilaian)

> **Epic ID:** M7
> **Phase:** 2 (Academic Excellence)
> **Priority:** CORE
> **Sprint Target:** Sprint 9–10
> **Total Story Points:** 34 SP
> **Dependencies:** M3 (Students), M5 (Class/Schedule)
> **Blocks:** M8 (Rapor), M12 (Online Exam), M20 (Analytics)

---

## Epic Overview

Kurikulum Merdeka-compliant grading system that replaces manual grade books. Supports Capaian Pembelajaran (CP), Tujuan Pembelajaran (TP), Formatif/Sumatif assessment types, configurable weighting, remedial tracking, and an Excel-like grid for fast input. This module's data feeds directly into E-Rapor (M8).

---

## User Stories

### US-7.1: Kurikulum Merdeka Structure (CP/TP)

**As the** System,
**I want** to support the Kurikulum Merdeka structure with Capaian Pembelajaran (CP) and Tujuan Pembelajaran (TP),
**so that** grades are organized according to the national curriculum framework.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] CP (Capaian Pembelajaran) can be defined per subject per grade level
- [ ] TP (Tujuan Pembelajaran) can be created under each CP
- [ ] TP has descriptors and indicators
- [ ] Structure is configurable per school (schools can customize TP wording)
- [ ] Default CP/TP templates provided per school type and subject
- [ ] CP/TP hierarchy visible when setting up assessments

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `learning_outcomes` (CP) migration (school_id, subject_id, grade_level, code, description, phase) | Backend | 0.5h |
| 2 | Create `learning_objectives` (TP) migration (learning_outcome_id, code, description, indicators JSONB) | Backend | 0.5h |
| 3 | Create `LearningOutcome` and `LearningObjective` models with `BelongsToSchool` | Backend | 0.5h |
| 4 | Create `CurriculumService` for CP/TP CRUD | Backend | 1h |
| 5 | Create CP/TP seeder with Kemendikbud defaults per subject/grade | Backend | 2h |
| 6 | Create `Curriculum/Index.vue` — CP/TP tree view per subject | Frontend | 2h |
| 7 | Create CP/TP edit interface (customize descriptions, add/remove TP) | Frontend | 1.5h |

---

### US-7.2: Assessment Types (Formatif & Sumatif)

**As a** Teacher,
**I want** to create assessments categorized as Asesmen Formatif or Sumatif,
**so that** different types of evaluation are tracked separately.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Assessment types: Formatif (ongoing, formative) and Sumatif (summative, end-of-unit)
- [ ] Each assessment linked to subject, class, TP (optional), and semester
- [ ] Assessment has: name, type, date, max score, weight
- [ ] Teacher can create multiple assessments per subject per semester
- [ ] Assessment list page per subject per class

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `assessments` migration (school_id, subject_id, class_group_id, teacher_id, learning_objective_id nullable, name, type enum, date, max_score, weight, semester_id) | Backend | 0.5h |
| 2 | Create `AssessmentType` enum (formatif, sumatif) | Backend | 0.5h |
| 3 | Create `Assessment` model with `BelongsToSchool` | Backend | 0.5h |
| 4 | Create `AssessmentService` with CRUD | Backend | 1h |
| 5 | Create `Assessment/Index.vue` — list per subject per class | Frontend | 1.5h |
| 6 | Create `Assessment/Create.vue` form (type, name, date, max score, weight, linked TP) | Frontend | 1h |

---

### US-7.3: Configurable Assessment Categories

**As an** Admin,
**I want** to define custom assessment categories and their weights,
**so that** the grading formula matches our school's policy.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Admin defines categories (e.g., "Tugas", "Ulangan Harian", "UTS", "UAS")
- [ ] Each category has a weight percentage
- [ ] Weights per subject can differ (e.g., Olahraga: 60% praktik, 40% teori)
- [ ] Total weights per subject must sum to 100%
- [ ] Categories apply per academic year (can change between years)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `assessment_categories` migration (school_id, subject_id nullable, name, weight, academic_year_id) | Backend | 0.5h |
| 2 | Create `AssessmentCategory` model with `BelongsToSchool` | Backend | 0.5h |
| 3 | Create `GradingConfigService` for category management + weight validation | Backend | 1h |
| 4 | Create `GradingConfig/Categories.vue` — category management with weight inputs | Frontend | 2h |
| 5 | Add weight sum validation (must = 100%) with real-time feedback | Frontend | 0.5h |

---

### US-7.4: Grade Input per Subject

**As a** Teacher,
**I want** to enter grades for each student per assessment,
**so that** all scores are recorded digitally.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Teacher selects Subject → Class → Assessment
- [ ] Student list with score input field per student
- [ ] Score validated against max_score
- [ ] Save confirms with: "Nilai berhasil disimpan"
- [ ] Previously entered scores shown when re-opening

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `grades` migration (school_id, assessment_id, student_id, score, note, graded_by) | Backend | 0.5h |
| 2 | Create `Grade` model with `BelongsToSchool` | Backend | 0.5h |
| 3 | Create `GradeService` with bulk save/update | Backend | 1h |
| 4 | Create `GradeController` with store/update for bulk grades | Backend | 0.5h |
| 5 | Create `Grade/Input.vue` — student list with score inputs | Frontend | 2h |

---

### US-7.5: Weighted Calculation

**As the** System,
**I want** to automatically calculate weighted averages based on the school's configured formula,
**so that** final grades are computed consistently and correctly.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Weighted average calculated per student per subject per semester
- [ ] Calculation uses category weights defined in US-7.3
- [ ] Recalculated automatically when grades change
- [ ] Result displayed on grade summary page
- [ ] Edge cases handled: missing grades treated as 0 or excluded (configurable)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `GradeCalculationService` with weighted average logic | Backend | 2h |
| 2 | Create `student_grade_summaries` table (school_id, student_id, subject_id, semester_id, final_score, breakdown JSONB) | Backend | 0.5h |
| 3 | Create `GradeObserver` to trigger recalculation on grade change | Backend | 1h |
| 4 | Create grade summary endpoint (per student per subject per semester) | Backend | 0.5h |
| 5 | Display calculated averages on grade input page and summary page | Frontend | 1.5h |
| 6 | Write unit tests for calculation edge cases (missing grades, zero weights) | Backend | 1h |

---

### US-7.6: Remedial Tracking

**As a** Teacher,
**I want** to track students who score below KKTP and manage their re-assessment,
**so that** struggling students are identified and given opportunities to improve.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Students below KKTP threshold automatically flagged
- [ ] Teacher can create remedial assessment for flagged students
- [ ] Remedial score replaces original or takes higher (configurable)
- [ ] Remedial history tracked per student per assessment
- [ ] Remedial status visible in grade summary (before/after remedial)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `remedials` migration (school_id, grade_id, original_score, remedial_score, remedial_date) | Backend | 0.5h |
| 2 | Create `RemedialService` with flagging + score replacement logic | Backend | 1h |
| 3 | Create remedial management UI (flagged students list, remedial score input) | Frontend | 2h |
| 4 | Add remedial indicator in grade summary views | Frontend | 0.5h |

---

### US-7.7: KKTP Configuration

**As an** Admin,
**I want** to set the KKTP (Kriteria Ketercapaian Tujuan Pembelajaran) per subject,
**so that** the minimum completeness criteria is configured for remedial flagging.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] KKTP value configurable per subject per grade level
- [ ] Default KKTP value provided (e.g., 75)
- [ ] KKTP used by remedial flagging logic (US-7.6)
- [ ] KKTP displayed on grade input page as reference

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Add `kktp` column to `subjects` or create `subject_configs` table | Backend | 0.5h |
| 2 | Create KKTP management in subject settings | Frontend | 1h |
| 3 | Display KKTP reference line on grade input page | Frontend | 0.5h |

---

### US-7.8: Bulk Grade Input (Excel-like Grid)

**As a** Teacher,
**I want** an Excel-like grid interface to input grades for an entire class at once,
**so that** I can enter 40 students' grades quickly using keyboard navigation.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Grid with students as rows, assessments as columns
- [ ] Tab between cells, Enter to move down
- [ ] Inline validation (red cell if score > max_score)
- [ ] Auto-save on cell blur or explicit "Save All"
- [ ] Grid loads in < 2 seconds for 40 students × 10 assessments
- [ ] Column headers show assessment name + max score
- [ ] Sortable by student name or by score

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Build spreadsheet-like grid component using TanStack Table with editable cells | Frontend | 5h |
| 2 | Implement keyboard navigation (Tab, Enter, arrow keys) | Frontend | 2h |
| 3 | Implement inline validation with visual feedback | Frontend | 1h |
| 4 | Implement auto-save or batch save logic | Frontend | 1h |
| 5 | Create bulk grade save endpoint (array of student_id + score pairs) | Backend | 1h |

---

### US-7.9: Grade Analytics

**As a** Teacher or Admin,
**I want** to see grade distribution charts, trends, and class comparisons,
**so that** I can identify academic patterns and make data-driven decisions.

**Story Points:** 5
**Priority:** Should

**Acceptance Criteria:**
- [ ] Score distribution histogram per assessment
- [ ] Class average trend over time (per subject)
- [ ] Comparison between classes for the same subject
- [ ] Highest/lowest/average score statistics
- [ ] Top/bottom performing students listed
- [ ] Charts rendered via Chart.js + vue-chartjs

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `GradeAnalyticsService` with aggregation queries | Backend | 2h |
| 2 | Create analytics API endpoints (distribution, trends, comparison) | Backend | 1h |
| 3 | Create `Grade/Analytics.vue` page with Chart.js charts | Frontend | 3h |
| 4 | Create score distribution histogram component | Frontend | 1h |
| 5 | Create class comparison chart component | Frontend | 1h |

---

## Technical Notes

- **Kurikulum Merdeka** is Indonesia's current national curriculum. CP (Capaian Pembelajaran) ≈ learning standards. TP (Tujuan Pembelajaran) ≈ learning objectives. Schools customize TP under the national CP framework.
- **Weighted calculation** is the critical business logic — must be thoroughly unit tested. Different schools use different formulas.
- **Excel-like grid** is a complex frontend component — consider building on TanStack Table's editable cell capabilities. Performance must handle 40 rows × 10 columns smoothly on budget devices.
- **Grade summary** feeds directly into M8 (E-Rapor). The `student_grade_summaries` table is the data source for rapor generation.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Kurikulum Merdeka CP/TP structure changes | Schema redesign | Abstract CP/TP behind configurable layer; keep structure flexible |
| Weighted calculation bugs | Wrong grades on rapor | Extensive unit tests, manual verification by teachers before rapor generation |
| Excel grid performance on low-end devices | Unusable on Redmi 9 | Virtualize rows if > 50 students, lazy-load columns, test on target hardware |
| Teachers input wrong grades | Data quality issues | Confirmation before save, edit history, teacher can correct within grace period |

---

## Definition of Done (Epic Level)

- [ ] CP/TP structure configured and seeded per school type
- [ ] Formatif and Sumatif assessment types supported
- [ ] Assessment categories configurable with weights summing to 100%
- [ ] Grade input works per-student and via bulk grid
- [ ] Weighted averages calculated correctly
- [ ] KKTP configured and remedial students flagged
- [ ] Grade analytics with distribution charts and comparisons
- [ ] All grade data scoped by school_id
- [ ] Grade summaries ready for rapor generation (M8)

---

### Related Files

- **Previous:** [`M06_ATTENDANCE.md`](M06_ATTENDANCE.md)
- **Next:** [`M08_RAPOR.md`](M08_RAPOR.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M7

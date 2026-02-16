# Epic M8 — Report Card (E-Rapor)

> **Epic ID:** M8
> **Phase:** 2 (Academic Excellence)
> **Priority:** CORE
> **Sprint Target:** Sprint 11–12
> **Total Story Points:** 37 SP
> **Dependencies:** M6 (Attendance), M7 (Grading), M17 (Extracurricular — optional, data used if available)
> **Blocks:** M11 (Parent Portal — rapor view in Phase 2)

---

## Epic Overview

Generate Kemendikbud Kurikulum Merdeka-compliant report cards (rapor) automatically from grading and attendance data. This is the highest-value module for teachers — replacing the weeks-long manual rapor writing process with automated PDF generation. Different formats are required per school level (SD, SMP, SMA, SMK).

---

## User Stories

### US-8.1: Kurikulum Merdeka Rapor Format

**As the** System,
**I want** to generate rapor that fully comply with the Kemendikbud Kurikulum Merdeka format,
**so that** the rapor are officially valid and acceptable.

**Story Points:** 8
**Priority:** Must

**Acceptance Criteria:**
- [ ] Rapor structure matches the official Kemendikbud template
- [ ] Sections: school header, student identity, intrakurikuler grades, P5, extracurricular, attendance, teacher notes, signatures
- [ ] Grade display: score + descriptive narrative per subject
- [ ] Phase-based structure (Fase A/B/C/D/E/F matching grade levels)
- [ ] Rapor template configurable per school type

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Research and document exact Kemendikbud rapor format requirements per level | Backend | 3h |
| 2 | Create `rapor_templates` migration (school_type, version, sections JSONB, blade_template_path) | Backend | 0.5h |
| 3 | Create `RaporTemplate` model | Backend | 0.5h |
| 4 | Create Blade templates for rapor: SD, SMP, SMA, SMK variants | Backend | 6h |
| 5 | Create `RaporService` orchestrator (aggregate data → populate template → generate PDF) | Backend | 3h |
| 6 | Create rapor data aggregation queries (grades, attendance, ekskul, P5) | Backend | 2h |

---

### US-8.2: Intrakurikuler Grades Section

**As a** Wali Kelas,
**I want** the rapor to display academic grades with descriptive narrative per subject,
**so that** parents understand their child's academic achievement beyond just numbers.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] All subjects for the student's class displayed with final score
- [ ] Descriptive narrative auto-generated based on score range (configurable)
- [ ] Teacher can override/customize the narrative per student per subject
- [ ] Grade source: `student_grade_summaries` from M7

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `NarrativeGeneratorService` — score-to-narrative mapping (configurable) | Backend | 1.5h |
| 2 | Create `rapor_narratives` table for teacher overrides (student_id, subject_id, semester_id, narrative) | Backend | 0.5h |
| 3 | Create narrative editing UI per student per subject (pre-filled auto-generated, editable) | Frontend | 2h |
| 4 | Populate intrakurikuler section in rapor Blade template | Backend | 1h |

---

### US-8.3: P5 Section (Projek Penguatan Profil Pelajar Pancasila)

**As a** Wali Kelas,
**I want** the rapor to include the P5 project assessment section,
**so that** the Profil Pelajar Pancasila dimensions are reported per the curriculum mandate.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] P5 dimensions: Beriman, Mandiri, Gotong Royong, Berkebinekaan Global, Bernalar Kritis, Kreatif
- [ ] Each dimension rated: Mulai Berkembang, Sedang Berkembang, Berkembang Sesuai Harapan, Sangat Berkembang
- [ ] P5 project name and description included
- [ ] Input by Wali Kelas or designated P5 coordinator

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `p5_assessments` migration (school_id, student_id, semester_id, project_name, project_description, dimensions JSONB) | Backend | 0.5h |
| 2 | Create `P5Dimension` and `P5Rating` enums | Backend | 0.5h |
| 3 | Create `P5AssessmentService` CRUD | Backend | 0.5h |
| 4 | Create P5 input UI — project details + dimension ratings per student | Frontend | 2h |
| 5 | Populate P5 section in rapor Blade template | Backend | 0.5h |

---

### US-8.4: Extracurricular Section

**As the** System,
**I want** the rapor to auto-populate extracurricular participation and achievements from M17,
**so that** ekskul activities are reflected on the rapor.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] Ekskul name, attendance percentage, and achievement (if any) displayed
- [ ] Data pulled from M17 (Extracurricular module) if available
- [ ] Manual entry fallback if M17 not yet implemented
- [ ] Multiple ekskul per student supported

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create ekskul data aggregation for rapor (from M17 or manual entry) | Backend | 1h |
| 2 | Create manual ekskul entry form (fallback for before M17 exists) | Frontend | 1h |
| 3 | Populate ekskul section in rapor Blade template | Backend | 0.5h |

---

### US-8.5: Attendance Summary (Auto-populated)

**As the** System,
**I want** the rapor to automatically show the attendance summary from M6,
**so that** teachers don't need to manually count attendance days.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Total days: Hadir, Sakit, Izin, Alpa displayed on rapor
- [ ] Data sourced from `attendances` table filtered by student + semester
- [ ] Late count optionally included
- [ ] Data matches the monthly recap from M6 exactly

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create attendance aggregation query for rapor (semester totals per student) | Backend | 1h |
| 2 | Populate attendance section in rapor Blade template | Backend | 0.5h |
| 3 | Add verification display (admin can check attendance totals before rapor generation) | Frontend | 0.5h |

---

### US-8.6: Teacher Notes (Catatan Wali Kelas)

**As a** Wali Kelas,
**I want** to write a personal narrative note for each student on the rapor,
**so that** parents receive personalized feedback about their child.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Free-text field per student per semester
- [ ] Character limit: 500 characters
- [ ] Pre-populated suggestion based on attendance/grades (optional)
- [ ] Editable by Wali Kelas only

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `rapor_notes` migration (school_id, student_id, semester_id, teacher_note, principal_note) | Backend | 0.5h |
| 2 | Create notes input UI — batch entry for all students in class | Frontend | 1.5h |
| 3 | Populate teacher notes section in rapor Blade template | Backend | 0.5h |

---

### US-8.7: Digital Signatures

**As the** System,
**I want** to include digital signatures of Kepala Sekolah and Wali Kelas on the rapor,
**so that** the rapor looks official for printing.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Kepala Sekolah uploads signature image (once, reused for all rapor)
- [ ] Wali Kelas uploads signature image (once per year)
- [ ] Signatures placed in correct positions on rapor PDF
- [ ] Names and NIP printed under signatures
- [ ] School stamp/seal optionally included

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create signature upload for Kepala Sekolah and Wali Kelas (via media-library) | Backend | 0.5h |
| 2 | Create signature management UI (upload, preview, crop) | Frontend | 1h |
| 3 | Integrate signatures into rapor Blade template | Backend | 0.5h |

---

### US-8.8: Print-Ready PDF Generation

**As a** Wali Kelas or Admin,
**I want** to generate print-ready PDFs of rapor for an entire class,
**so that** rapor can be printed and distributed to parents.

**Story Points:** 8
**Priority:** Must

**Acceptance Criteria:**
- [ ] Generate PDF for individual student or bulk (entire class)
- [ ] PDF includes school logo, branding colors from M1
- [ ] A4 format, proper margins for printing
- [ ] Bulk generation (30 rapor) uses queued processing with progress tracking
- [ ] Real-time progress via Laravel Reverb: "12 of 30 rapor generated..."
- [ ] Generated PDFs downloadable as individual files or ZIP
- [ ] Retry policy: 3 attempts per rapor, 120-second timeout

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Configure DomPDF with A4, proper margins, font embedding (Indonesian characters) | Backend | 1h |
| 2 | Create `GenerateRaporPdf` job (single student) | Backend | 2h |
| 3 | Create batch generation using `Bus::batch()` on dedicated `rapor` queue | Backend | 2h |
| 4 | Create real-time progress broadcasting via Laravel Reverb | Backend | 1.5h |
| 5 | Create `Rapor/Generate.vue` — class selector, generate button, progress bar | Frontend | 2h |
| 6 | Create download page (individual PDF or ZIP download) | Frontend | 1h |
| 7 | Pre-cache all grade/attendance data before batch generation | Backend | 1h |
| 8 | Write feature test for PDF generation with mock data | Backend | 1h |

---

### US-8.9: Online Parent Access

**As a** Parent,
**I want** to view my child's rapor digitally in the parent portal,
**so that** I don't need to wait for the physical copy.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Parent sees rapor in portal after Wali Kelas publishes it
- [ ] Rapor displayed as HTML view (not just PDF download)
- [ ] PDF download option available
- [ ] Published rapor is read-only (cannot be modified after publishing)
- [ ] Publish/unpublish controlled by Wali Kelas or Admin

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create rapor publishing workflow (draft → reviewed → published) | Backend | 1h |
| 2 | Create `rapor_publications` table (student_id, semester_id, status, published_at, published_by) | Backend | 0.5h |
| 3 | Create parent-facing rapor HTML view in Parent Portal | Frontend | 2h |
| 4 | Create publish/unpublish controls for Wali Kelas | Frontend | 1h |

---

### US-8.10: Level-Specific Format

**As the** System,
**I want** to render different rapor formats per school level (SD, SMP, SMA, SMK),
**so that** each level gets the appropriate Kemendikbud format.

**Story Points:** 4
**Priority:** Must

**Acceptance Criteria:**
- [ ] SD: Descriptive narrative per subject (no numeric grades in Fase A/B)
- [ ] SMP: Numeric + descriptive per subject
- [ ] SMA: Numeric + descriptive + kelompok mapel structure
- [ ] SMK: Includes kompetensi kejuruan section
- [ ] Correct template auto-selected based on school type

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create SD rapor Blade template (narrative-focused) | Backend | 2h |
| 2 | Create SMP rapor Blade template | Backend | 1.5h |
| 3 | Create SMA rapor Blade template | Backend | 1.5h |
| 4 | Create SMK rapor Blade template (with kejuruan section) | Backend | 2h |
| 5 | Template selection logic based on school type | Backend | 0.5h |
| 6 | Test all templates with sample data | Backend | 1h |

---

## Technical Notes

- **DomPDF** is sufficient for the rapor format. No need for wkhtmltopdf or Puppeteer.
- **Batch processing**: 30 rapor per class → `Bus::batch()` on dedicated `rapor` queue. Pre-cache all grades, attendance, and ekskul data before starting batch to avoid N+1 queries during generation.
- **Real-time progress**: Laravel Reverb broadcasts `RaporBatchProgress` event with count/total.
- **Retry policy**: `$tries = 3`, `$timeout = 120` per individual rapor.
- **End-of-semester bottleneck**: All schools generate rapor at the same time. Temporary worker scale-up strategy needed (add queue worker container).
- **Rapor template** uses Blade views (not stored in DB). School-type-specific templates at `resources/views/rapor/{school_type}/semester.blade.php`.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| DomPDF performance for complex layouts | Slow generation | Pre-cache data, optimize Blade templates, 120s timeout per rapor |
| End-of-semester load spike | System overload | Dedicated `rapor` queue, temporary worker scale-up, batch processing |
| Kemendikbud format changes mid-year | Template redesign | Template versioning, keep old templates for historical rapor |
| Indonesian character rendering in PDF | Garbled text | Embed proper fonts (Noto Sans, Times New Roman) in DomPDF config |
| Grade calculation errors | Wrong rapor distributed | Verification step before publishing, teacher review workflow |

---

## Definition of Done (Epic Level)

- [ ] Generated rapor matches Kemendikbud Kurikulum Merdeka format
- [ ] PDF includes school logo, signatures, and all required sections
- [ ] Intrakurikuler grades with descriptive narratives
- [ ] P5 section with dimension ratings
- [ ] Attendance data auto-populated correctly from M6
- [ ] Ekskul data included (from M17 or manual entry)
- [ ] Teacher notes per student
- [ ] Batch generation with real-time progress
- [ ] Level-specific formats render correctly for SD, SMP, SMA, SMK
- [ ] Parents can view published rapor in the portal
- [ ] Publishing workflow prevents accidental distribution of draft rapor

---

### Related Files

- **Previous:** [`M07_GRADING_ASSESSMENT.md`](M07_GRADING_ASSESSMENT.md)
- **Next:** [`M09_FINANCE.md`](M09_FINANCE.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M8

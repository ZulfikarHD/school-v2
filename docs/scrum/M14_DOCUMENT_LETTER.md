# Epic M14 — Document & Letter Management (Surat-Menyurat)

> **Epic ID:** M14
> **Phase:** 3 (Complete School Operations)
> **Priority:** Important
> **Sprint Target:** Sprint 16
> **Total Story Points:** 18 SP
> **Dependencies:** M1 (School Profile), M2 (Users)
> **Blocks:** —

---

## Epic Overview

Digitize school correspondence and document management. Schools produce many types of official letters (Surat Keterangan, SK, Surat Izin, Surat Tugas, Surat Kelulusan). This module provides templates with auto-populated data, automatic sequential numbering, digital signatures, and a searchable archive.

---

## User Stories

### US-14.1: Surat Masuk/Keluar Register

**As an** Admin,
**I want** to register incoming and outgoing letters,
**so that** all correspondence is tracked and searchable.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Register incoming letter: sender, date, subject, reference number, scanned document upload
- [ ] Register outgoing letter: recipient, date, subject, letter number, document
- [ ] Letter list with filters (date range, direction, type)
- [ ] Search by subject, sender/recipient, or reference number
- [ ] Letter count and statistics per month

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `letters` migration (school_id, direction enum, type, number, date, subject, sender/recipient, reference_number, notes) | Backend | 0.5h |
| 2 | Create `LetterDirection` enum (incoming, outgoing) | Backend | 0.5h |
| 3 | Create `Letter` model with `BelongsToSchool`, `Searchable` | Backend | 0.5h |
| 4 | Create `LetterService` CRUD | Backend | 1h |
| 5 | Create `Documents/Letters/Index.vue` with filterable DataTable | Frontend | 2h |
| 6 | Create `Documents/Letters/Create.vue` form with document upload | Frontend | 1.5h |

---

### US-14.2: Letter Templates

**As an** Admin,
**I want** to generate official letters from templates with auto-populated student/school data,
**so that** letter creation is fast and consistent.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Template types: Surat Keterangan Aktif, Surat Keterangan Lulus, Surat Izin, Surat Tugas, SK Wali Kelas, Surat Kelulusan
- [ ] Templates use placeholders: `{student_name}`, `{nisn}`, `{school_name}`, `{principal_name}`, etc.
- [ ] Select student/teacher → placeholders auto-filled
- [ ] Generated letter editable before finalizing
- [ ] Download as PDF with school letterhead

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `letter_templates` migration (school_id, name, type, body_template, placeholders JSONB) | Backend | 0.5h |
| 2 | Create `LetterTemplateService` with placeholder resolution | Backend | 1.5h |
| 3 | Create default template seeder per letter type | Backend | 1h |
| 4 | Create template management UI (view/edit templates) | Frontend | 2h |
| 5 | Create letter generation flow (select template → select person → preview → generate PDF) | Frontend | 2.5h |
| 6 | Create PDF generation with school letterhead via DomPDF | Backend | 1.5h |

---

### US-14.3: Auto-Numbering

**As the** System,
**I want** to automatically generate sequential letter numbers following the school's format,
**so that** numbering is consistent and never duplicated.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Numbering format configurable per school (e.g., `{seq}/{type}/{month}/{year}`)
- [ ] Sequential counter per letter type per year (auto-increments)
- [ ] Counter resets each year
- [ ] No duplicate numbers (concurrent-safe)
- [ ] Number preview shown before letter is finalized

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `letter_counters` migration (school_id, type, year, last_number) | Backend | 0.5h |
| 2 | Create `LetterNumberingService` with format parsing and concurrent-safe increment | Backend | 1.5h |
| 3 | Create numbering format configuration UI | Frontend | 1h |
| 4 | Display auto-generated number in letter creation form | Frontend | 0.5h |

---

### US-14.4: Digital Signature

**As a** Kepala Sekolah,
**I want** to digitally sign documents,
**so that** letters can be distributed without waiting for physical signatures.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Kepala Sekolah uploads signature image (once, reused)
- [ ] Signature placed on generated letters/documents
- [ ] Signed documents marked as "signed" in the system
- [ ] Signature visible in PDF output

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create signature upload via `spatie/media-library` | Backend | 0.5h |
| 2 | Create signature management page (upload, preview, replace) | Frontend | 1h |
| 3 | Integrate signature image into letter PDF templates | Backend | 1h |
| 4 | Add "signed" status to letters | Backend | 0.5h |

---

### US-14.5: Archive & Search

**As an** Admin,
**I want** to search across all archived documents by keyword, date, or type,
**so that** I can retrieve any document quickly.

**Story Points:** 4
**Priority:** Must

**Acceptance Criteria:**
- [ ] Full-text search across letter subjects, content, and reference numbers
- [ ] Filter by: type, direction, date range, signed status
- [ ] Meilisearch integration for fast search
- [ ] Document preview without downloading
- [ ] Archive statistics: letters per month, per type

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Configure Meilisearch indexing for Letter model | Backend | 1h |
| 2 | Create advanced search endpoint with filters | Backend | 0.5h |
| 3 | Create `Documents/Archive.vue` with search + filters + document preview | Frontend | 2h |
| 4 | Create archive statistics dashboard | Frontend | 1h |

---

## Technical Notes

- **Letter templates** use Blade syntax with placeholders. Templates are editable by admin but a default set is seeded.
- **Auto-numbering** must be concurrent-safe — use database-level `FOR UPDATE` lock on the counter row.
- **Meilisearch** indexes letter data for fast full-text search (same approach as student search in M3).
- **PDF generation** reuses DomPDF setup from M8 (Rapor). School letterhead included automatically from M1 branding settings.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Numbering collision under concurrent access | Duplicate numbers | Database row-level lock (`FOR UPDATE`) on counter |
| Template customization too complex | Admin confused | Provide good defaults, WYSIWYG-like placeholder insertion |
| Large archive slows search | Slow retrieval | Meilisearch handles this well; paginate results |

---

## Definition of Done (Epic Level)

- [ ] Letters can be created from templates with auto-populated data
- [ ] Numbering follows school's format consistently
- [ ] Incoming and outgoing letters registered and tracked
- [ ] Documents searchable by keyword, date, or type
- [ ] Digital signatures on generated documents
- [ ] PDF export with school letterhead

---

### Related Files

- **Previous:** [`M13_BOS_FUND.md`](M13_BOS_FUND.md)
- **Next:** [`M15_LIBRARY.md`](M15_LIBRARY.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M14

# Epic M21 — Government Compliance

> **Epic ID:** M21
> **Phase:** 2 (basic Dapodik export) + 4 (EMIS, accreditation, full compliance)
> **Priority:** **CORE** (basic export) / Nice-to-Have (full compliance)
> **Sprint Target:** Sprint 13 (basic) + Sprint 23 (full)
> **Total Story Points:** 8 SP (Phase 2) + 8 SP (Phase 4) = 16 SP total
> **Dependencies:** M3 (Students), M4 (Teachers)
> **Blocks:** —

---

## Epic Overview

Ensure compatibility with Indonesian government reporting requirements. Schools must report data to Dapodik (Kementerian Pendidikan) and EMIS (Kementerian Agama for Madrasah). This module provides data export in the formats required by these systems, plus document preparation tools for school accreditation.

> **Why Phase 2 for basic Dapodik export?** This is a killer differentiator for sekolah negeri adoption. Schools currently input data into the platform AND manually re-input the same data into Dapodik. If the platform can export Dapodik-ready data, it solves a major pain point and makes the platform indispensable — especially for sekolah negeri where Dapodik reporting is mandatory. (See `FEATURE_DOCUMENT.md` § M21 for full rationale.)

---

## Phase 2 — Basic Dapodik Export (Sprint 13, 8 SP)

## User Stories

### US-21.1: Dapodik Export

**As an** Admin,
**I want** to export school data in a format compatible with the Dapodik system,
**so that** I can fulfill the mandatory government reporting requirement without manual re-entry.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Export student data matching Dapodik field mapping (NISN, NIK, name, birth data, family data, etc.)
- [ ] Export teacher data matching Dapodik field mapping (NUPTK, NIP, certification, etc.)
- [ ] Export school profile data
- [ ] Export format: Excel/CSV matching Dapodik import template
- [ ] Data validation: flag records missing required Dapodik fields before export
- [ ] Export history log (who exported, when)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Research current Dapodik data structure and required fields | Backend | 2h |
| 2 | Create `DapodikExportService` with field mapping (platform fields → Dapodik fields) | Backend | 3h |
| 3 | Create pre-export validation (check for missing required fields per student/teacher) | Backend | 1h |
| 4 | Create `Compliance/Dapodik/Export.vue` — entity selector, validation report, export button | Frontend | 2h |
| 5 | Create Excel export matching Dapodik template format | Backend | 1.5h |
| 6 | Create export history log | Backend | 0.5h |

---

## Phase 4 — Full Government Compliance (Sprint 23, 8 SP)

### US-21.2: EMIS Export

**As an** Admin of a Madrasah (MI, MTs, MA),
**I want** to export data in EMIS format for Kemenag reporting,
**so that** I can fulfill Madrasah-specific reporting requirements.

**Story Points:** 5
**Priority:** Should (only for Madrasah school types)

**Acceptance Criteria:**
- [ ] Only available for Madrasah school types (MI, MTs, MA)
- [ ] Export student and teacher data in EMIS field format
- [ ] EMIS-specific fields: madrasah accreditation, yayasan info, etc.
- [ ] Export as Excel/CSV matching EMIS template
- [ ] Pre-export validation for EMIS-specific required fields

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Research EMIS data structure and required fields | Backend | 2h |
| 2 | Create `EmisExportService` with field mapping | Backend | 2h |
| 3 | Create EMIS-specific validation rules | Backend | 0.5h |
| 4 | Create `Compliance/EMIS/Export.vue` UI (similar to Dapodik) | Frontend | 1.5h |
| 5 | Conditionally show EMIS export only for Madrasah school types | Frontend | 0.5h |

---

### US-21.3: Accreditation Preparation

**As an** Admin or Kepala Sekolah,
**I want** tools to help prepare documents for school accreditation,
**so that** accreditation preparation is less tedious and more organized.

**Story Points:** 3
**Priority:** Could

**Acceptance Criteria:**
- [ ] Checklist of required accreditation documents (based on 8 SNP standards)
- [ ] Document status tracking (available, in progress, missing)
- [ ] Link existing platform data to accreditation evidence (e.g., attendance reports → standar proses)
- [ ] Generate summary report of school data for accreditation team
- [ ] Print-friendly evidence compilation

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create accreditation checklist per 8 SNP standards | Backend | 1h |
| 2 | Create `Compliance/Accreditation/Checklist.vue` with status tracking | Frontend | 2h |
| 3 | Create automated evidence linking (platform data → accreditation items) | Backend | 1h |
| 4 | Create summary report generation | Backend | 1h |

---

### US-21.4: Government Report Formats

**As an** Admin,
**I want** to generate standardized reports in government-mandated formats,
**so that** I can submit reports without manual formatting.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Student statistics report (per grade, gender, religion)
- [ ] Teacher statistics report (per certification status, education level)
- [ ] School profile report (Profil Sekolah)
- [ ] Reports formatted to match government templates
- [ ] Export to Excel and PDF

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `GovernmentReportService` with student and teacher statistics | Backend | 1.5h |
| 2 | Create report templates matching government format | Backend | 1h |
| 3 | Create `Compliance/Reports/Index.vue` — report selector + download | Frontend | 1.5h |
| 4 | Create Excel and PDF exports | Backend | 1h |

---

## Technical Notes

- **Dapodik field mapping** is the key challenge — platform field names don't match Dapodik exactly. A mapping configuration (JSONB or config file) translates between systems.
- **Dapodik API**: Currently Dapodik does not have a public API for direct submission. Export as Excel matching their import template is the standard approach.
- **EMIS** is similar but for Kementerian Agama (Madrasah schools). Different field structure.
- **Accreditation** is a manual process but the platform can pre-populate data that serves as evidence.
- **This module is read-only** — it exports data from other modules but doesn't create new data.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Dapodik format changes yearly | Export breaks | Version mapping configuration, update annually |
| Incomplete data prevents clean export | Admin frustration | Pre-export validation with clear error messages per student/field |
| Schools don't fill all Dapodik fields | Export has gaps | Mark Dapodik-required fields in student/teacher forms, completeness indicator |
| EMIS format differs from Dapodik | Extra development | Separate service with own field mapping, shared export infrastructure |

---

## Definition of Done (Epic Level)

- [ ] Dapodik export generates valid Excel matching Dapodik template
- [ ] Pre-export validation flags missing required fields
- [ ] EMIS export available for Madrasah school types
- [ ] Accreditation checklist with document status tracking
- [ ] Government statistics reports in standard formats
- [ ] Export history maintained

---

### Related Files

- **Previous:** [`M20_ANALYTICS.md`](M20_ANALYTICS.md)
- **Next:** [`M22_ADDITIONAL_FEATURES.md`](M22_ADDITIONAL_FEATURES.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M21

# Epic M3 — Student Information System (Data Siswa)

> **Epic ID:** M3
> **Phase:** 1 (MVP)
> **Priority:** CORE
> **Sprint Target:** Sprint 3
> **Total Story Points:** 24 SP
> **Dependencies:** M1 (Multi-Tenancy), M2 (User & Role Management)
> **Blocks:** M5, M6, M7, M8, M9, M11, M12, M15, M16, M17, M18, M20, M21

---

## Epic Overview

Central repository for all student data, replacing paper-based student records. This is the first "full-stack CRUD" module — it exercises the entire stack (model, service, controller, Inertia page, DataTable, file upload, search, import) and sets the pattern for all subsequent modules.

---

## User Stories

### US-3.1: Student Biodata CRUD

**As an** Admin (Tata Usaha),
**I want** to create and manage student biodata (name, NISN, NIK, birth date, religion, address, photo),
**so that** student records are digitized and searchable.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Form with all biodata fields: name, NISN (10 digits), NIK (16 digits), birth date, birth place, gender, religion, address, photo
- [ ] NISN and NIK validated for format and uniqueness within school
- [ ] NIK encrypted at rest (`$casts = ['nik' => 'encrypted']`)
- [ ] Photo upload with auto-resize (200px list, 80px avatar) via `spatie/media-library`
- [ ] Student list page with DataTable (sortable, filterable, searchable)
- [ ] Student detail page showing complete profile
- [ ] Edit and soft-delete functionality

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `students` migration (school_id, name, nisn, nik, birth_date, birth_place, gender, religion, address, status, JSONB family_data, health_data, metadata) | Backend | 1h |
| 2 | Create `Student` model with `BelongsToSchool`, `Searchable`, encrypted NIK cast | Backend | 1h |
| 3 | Create `StudentService` (create, update, delete, changeStatus) | Backend | 1.5h |
| 4 | Create `StudentController` with full CRUD | Backend | 1h |
| 5 | Create `StoreStudentRequest` and `UpdateStudentRequest` form requests | Backend | 1h |
| 6 | Create `StudentPolicy` for authorization | Backend | 0.5h |
| 7 | Create `Student/Index.vue` with DataTable (columns: name, NISN, class, status, actions) | Frontend | 3h |
| 8 | Create `Student/Create.vue` multi-step form (biodata, family, health) | Frontend | 3h |
| 9 | Create `Student/Show.vue` detail page with tabbed sections | Frontend | 2h |
| 10 | Create `Student/Edit.vue` form (pre-filled from existing data) | Frontend | 1h |
| 11 | Configure `spatie/media-library` for student photo (200px, 80px conversions) | Backend | 0.5h |

---

### US-3.2: Family Information

**As an** Admin,
**I want** to record family details (father, mother, guardian) for each student,
**so that** the school has emergency contacts and required Dapodik data.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Father, mother, and guardian details stored (name, NIK, phone, occupation, income, education)
- [ ] At least one parent/guardian is required
- [ ] Data stored in JSONB `family_data` column for flexibility
- [ ] Family tab visible on student detail page
- [ ] Phone number auto-linked for parent portal access

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Define `family_data` JSONB schema (father, mother, guardian sub-objects) | Backend | 0.5h |
| 2 | Create validation rules for family data nested structure | Backend | 1h |
| 3 | Create family information tab in student create/edit form | Frontend | 2h |
| 4 | Display family information on student detail page | Frontend | 1h |

---

### US-3.3: Health Records

**As an** Admin,
**I want** to record student health information (allergies, blood type, medical conditions),
**so that** the school can respond appropriately in emergencies.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] Health data fields: blood type, allergies (list), medical conditions, special needs, insurance info
- [ ] Data stored in JSONB `health_data` column
- [ ] Health tab on student detail page
- [ ] Health data accessible by homeroom teacher (read-only)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Define `health_data` JSONB schema | Backend | 0.5h |
| 2 | Add health fields to student create/edit form | Frontend | 1h |
| 3 | Display health info on student detail page (with appropriate access control) | Frontend | 1h |

---

### US-3.4: Transfer History

**As an** Admin,
**I want** to record previous school history and transfer documentation,
**so that** we have a complete academic background for each student.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] Previous school details: name, NPSN, last grade, transfer date, reason
- [ ] Transfer documents can be uploaded (PDF/image)
- [ ] Transfer history visible on student detail page

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `student_transfers` migration (student_id, from_school, to_school, date, reason, documents) | Backend | 0.5h |
| 2 | Create `StudentTransfer` model with `BelongsToSchool` | Backend | 0.5h |
| 3 | Create transfer recording in `StudentService` | Backend | 0.5h |
| 4 | Create transfer history tab on student detail page | Frontend | 1h |
| 5 | Add document upload for transfer records | Frontend | 0.5h |

---

### US-3.5: Student Status Management

**As an** Admin,
**I want** to change student status (Active, Transferred, Graduated, Dropped Out),
**so that** the school accurately tracks student enrollment lifecycle.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Status transitions: Active → Transferred / Graduated / Dropped Out
- [ ] Status change requires a reason and effective date
- [ ] All status changes logged via `spatie/laravel-activitylog`
- [ ] Inactive students filtered from default views but accessible via filter
- [ ] Status change triggers appropriate downstream actions (e.g., transfer record creation)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `StudentStatus` PHP backed enum (active, transferred, graduated, dropped_out) | Backend | 0.5h |
| 2 | Create `changeStatus()` in `StudentService` with reason logging | Backend | 1h |
| 3 | Configure `spatie/laravel-activitylog` for student status changes | Backend | 0.5h |
| 4 | Create status change dialog with reason field and date picker | Frontend | 1.5h |
| 5 | Add status filter to student list DataTable | Frontend | 0.5h |

---

### US-3.6: Class Assignment

**As an** Admin,
**I want** to assign students to a Rombel (class group) per academic year,
**so that** students are organized into classes for attendance and grading.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Student can be assigned to one rombel per academic year
- [ ] Assignment visible on student detail page and class list
- [ ] Bulk assignment (move multiple students to a class) supported
- [ ] Historical assignments preserved (previous years)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `class_group_student` pivot migration (class_group_id, student_id, academic_year_id) | Backend | 0.5h |
| 2 | Create assignment methods in `StudentService` | Backend | 1h |
| 3 | Create class assignment UI on student detail page | Frontend | 1h |
| 4 | Create bulk assignment dialog (select students → assign to class) | Frontend | 1.5h |

---

### US-3.7: Document Uploads

**As an** Admin,
**I want** to upload student documents (akta kelahiran, KK, ijazah),
**so that** required documents are stored digitally and accessible.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Upload multiple documents per student (PDF, image)
- [ ] Document type selectable (Akta Kelahiran, KK, Ijazah, SKHUN, Other)
- [ ] Documents accessible via signed URLs (S3, expire in 30 minutes)
- [ ] Documents previewable in-browser (PDF viewer, image lightbox)
- [ ] Max file size: 5MB per document

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Configure `spatie/media-library` collections for student documents | Backend | 0.5h |
| 2 | Create `DocumentType` enum (akta_kelahiran, kk, ijazah, skhun, other) | Backend | 0.5h |
| 3 | Create document upload/delete endpoints | Backend | 1h |
| 4 | Create document management tab on student detail page | Frontend | 2h |
| 5 | Implement PDF preview and image lightbox | Frontend | 1h |

---

### US-3.8: Alumni Tracking

**As an** Admin,
**I want** to maintain records of graduated students,
**so that** the school can track alumni and generate alumni reports.

**Story Points:** 1
**Priority:** Could

**Acceptance Criteria:**
- [ ] Graduated students accessible via "Alumni" filter
- [ ] Alumni list shows graduation year, last class, and current contact
- [ ] Alumni data is read-only (no further edits to academic data)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Add alumni scope/filter on Student model | Backend | 0.5h |
| 2 | Create alumni list view with graduation year filter | Frontend | 1h |

---

### US-3.9: Student Search

**As a** staff member,
**I want** to quickly search students by name, NISN, or class,
**so that** I can find student records in seconds.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Search box on student list page with instant results (< 200ms)
- [ ] Search by name (partial match), NISN (exact), or class name
- [ ] Results scoped to current school tenant
- [ ] Works for up to 2,000 students without degradation
- [ ] Meilisearch indexes student data automatically

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Configure Laravel Scout + Meilisearch for Student model | Backend | 1h |
| 2 | Define searchable attributes (name, nisn, class_group_name) | Backend | 0.5h |
| 3 | Create search endpoint returning paginated results | Backend | 0.5h |
| 4 | Create search input with debounced query (300ms) in DataTable | Frontend | 1h |
| 5 | Write performance test: 2,000 students, search < 200ms | Backend | 0.5h |

---

## Technical Notes

- **JSONB columns** (`family_data`, `health_data`, `metadata`) allow flexibility — each school may collect slightly different data. Validation is still applied via form requests.
- **NIK encryption**: `$casts = ['nik' => 'encrypted']` — NIK is a sensitive personal identifier under UU PDP.
- **Meilisearch** indexes are per-tenant (prefixed with `school_id`). Scout auto-handles this.
- **DataTable pattern**: This is the first module to use `DataTable.vue` with server-side pagination via Inertia — this pattern will be reused across 20+ pages.
- **Soft deletes** used on students — data is never permanently deleted.
- **Class assignment** is a pivot table — students move classes each year but historical assignments are preserved.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Meilisearch index out of sync | Search returns stale data | Scout auto-syncs on model events; `scout:import` as fallback |
| JSONB schema inconsistency | Data quality issues | Validate JSONB structure via form request rules |
| Bulk import conflicts with existing students | Duplicate records | Duplicate detection by NISN before import |
| Large file uploads on slow connections | Timeouts, frustration | Client-side file size validation, chunked uploads for large batches |

---

## Definition of Done (Epic Level)

- [ ] Complete student CRUD with all biodata fields
- [ ] Family and health information stored and displayed
- [ ] Student status management with audit trail
- [ ] Document upload and preview working with S3 signed URLs
- [ ] Student search returns results in < 200ms
- [ ] DataTable with server-side pagination, sorting, filtering
- [ ] Class assignment working per academic year
- [ ] `BelongsToSchool` enforced — cross-tenant test passing
- [ ] NIK encrypted at rest

---

### Related Files

- **Previous:** [`M02_USER_ROLE_MANAGEMENT.md`](M02_USER_ROLE_MANAGEMENT.md)
- **Next:** [`M05_CLASS_SCHEDULE.md`](M05_CLASS_SCHEDULE.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M3

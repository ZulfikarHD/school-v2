# Epic M15 — Library (Perpustakaan)

> **Epic ID:** M15
> **Phase:** 3 (Complete School Operations)
> **Priority:** Important
> **Sprint Target:** Sprint 16
> **Total Story Points:** 21 SP
> **Dependencies:** M3 (Students)
> **Blocks:** —

---

## Epic Overview

Manage school library catalog, borrowing, and returns. Replaces paper-based library ledgers with digital book catalog, barcode/QR scanning for quick operations, automated fine calculation for late returns, and borrowing analytics.

---

## User Stories

### US-15.1: Book Catalog

**As a** Library Admin,
**I want** to manage a book catalog with complete metadata,
**so that** the school's book collection is digitized and searchable.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Book entry: ISBN, title, author, publisher, year, category, cover image
- [ ] Multiple copies tracked per title (each with unique copy number)
- [ ] Book list with DataTable (searchable, filterable by category/author)
- [ ] Book detail page with availability status
- [ ] Bulk import from Excel

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `books` migration (school_id, isbn, title, author, publisher, year, category, description, total_copies) | Backend | 0.5h |
| 2 | Create `book_copies` migration (book_id, copy_number, status enum, condition) | Backend | 0.5h |
| 3 | Create `Book`, `BookCopy` models with `BelongsToSchool` | Backend | 0.5h |
| 4 | Create `BookService` with CRUD + copy management | Backend | 1h |
| 5 | Create `Library/Books/Index.vue` with DataTable | Frontend | 2h |
| 6 | Create `Library/Books/Create.vue` form with cover image upload | Frontend | 1.5h |
| 7 | Create Excel import for bulk book catalog | Backend | 1h |

---

### US-15.2: Borrowing System

**As a** Library Admin,
**I want** to track book check-outs and returns,
**so that** every borrowed book is accounted for.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Check-out: select student + book copy → record borrow date and due date
- [ ] Due date configurable (default: 7 days)
- [ ] Return: scan or select book → record return date
- [ ] Overdue books highlighted in red
- [ ] Max books per student configurable (default: 3)
- [ ] Currently borrowed books list per student

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `book_borrowings` migration (school_id, student_id, book_copy_id, borrowed_at, due_at, returned_at, fine_amount, fine_paid) | Backend | 0.5h |
| 2 | Create `BorrowingService` with checkout, return, overdue check | Backend | 1.5h |
| 3 | Create `Library/Borrowing/Checkout.vue` — student + book selection | Frontend | 2h |
| 4 | Create `Library/Borrowing/Return.vue` — return processing | Frontend | 1h |
| 5 | Create `Library/Borrowing/Index.vue` — active borrowings list with overdue highlight | Frontend | 1.5h |

---

### US-15.3: Fine Calculation

**As the** System,
**I want** to auto-calculate fines for late returns,
**so that** students are held accountable for overdue books.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] Fine rate configurable per school (e.g., Rp 500/day)
- [ ] Fine auto-calculated on return based on days overdue
- [ ] Fine displayed to student/admin at return time
- [ ] Fine payment status tracked (paid/unpaid)
- [ ] Total outstanding fines per student viewable

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create fine configuration (rate per day) in school settings | Backend | 0.5h |
| 2 | Create fine calculation logic in `BorrowingService` | Backend | 0.5h |
| 3 | Display fine amount on return and in borrowing history | Frontend | 0.5h |
| 4 | Create fine payment recording | Frontend | 0.5h |

---

### US-15.4: Borrowing History

**As a** Library Admin or Student,
**I want** to see per-student borrowing records,
**so that** we have a complete history of library usage.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Per-student borrowing history (all-time)
- [ ] Filterable by date range, status (active/returned/overdue)
- [ ] Student can view own history in their portal
- [ ] Export to Excel

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create borrowing history endpoint with pagination and filters | Backend | 0.5h |
| 2 | Create `Library/History.vue` with DataTable | Frontend | 1.5h |
| 3 | Add student-facing borrowing history in student portal | Frontend | 1h |

---

### US-15.5: Library Analytics

**As a** Library Admin,
**I want** to see popular books, borrowing trends, and usage statistics,
**so that** I can make informed decisions about book purchases.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Most borrowed books (top 10)
- [ ] Monthly borrowing trend chart
- [ ] Active borrowers count
- [ ] Overdue rate percentage
- [ ] Category distribution of borrowed books

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `LibraryAnalyticsService` with aggregation queries | Backend | 1h |
| 2 | Create `Library/Analytics.vue` with Chart.js charts | Frontend | 2h |

---

### US-15.6: QR/Barcode Scan

**As a** Library Admin,
**I want** to scan book barcodes for quick checkout/return,
**so that** operations are faster than manual lookup.

**Story Points:** 3
**Priority:** Could

**Acceptance Criteria:**
- [ ] Camera-based barcode/QR scanner (no special hardware)
- [ ] Scan ISBN barcode → finds book in catalog
- [ ] Scan QR on book copy sticker → identifies specific copy
- [ ] Scan student QR card → identifies student for checkout

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Integrate barcode scanner library (e.g., `vue-barcode-reader`) | Frontend | 1.5h |
| 2 | Create scan-to-checkout flow (scan book → scan student → confirm) | Frontend | 2h |
| 3 | Create QR code generation for book copies | Backend | 0.5h |

---

### US-15.7: Digital Resources

**As a** Library Admin,
**I want** to upload and share digital materials (PDF textbooks, references),
**so that** students can access learning materials online.

**Story Points:** 3
**Priority:** Could

**Acceptance Criteria:**
- [ ] Upload PDF materials with metadata (title, subject, grade)
- [ ] Students can browse and download materials
- [ ] Materials organized by subject and grade
- [ ] Signed URLs for secure download (30-min expiry)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `digital_resources` migration (school_id, title, subject_id, grade_level, file) | Backend | 0.5h |
| 2 | Create upload/management UI for digital resources | Frontend | 1.5h |
| 3 | Create student-facing digital library browser | Frontend | 1.5h |

---

## Technical Notes

- **Book copies** are tracked individually — important for knowing which specific copy is borrowed.
- **Barcode scanning** uses device camera. ISBN barcodes are standard; QR codes printed on book stickers for copy-level tracking.
- **Fine system** is simple (flat rate per day). No complex fine rules needed for school libraries.
- **Digital resources** stored via `spatie/media-library` on S3 with signed URLs.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| ISBN barcode not available on old books | Can't scan | Manual lookup fallback always available |
| Students don't return books | Lost inventory | Overdue notifications, block new borrowing if overdue |
| Digital resource storage costs | Budget concern | Limit file size (50MB max), compress PDFs |

---

## Definition of Done (Epic Level)

- [ ] Books cataloged with complete metadata
- [ ] Borrowing and return tracked with due dates
- [ ] Fines calculated automatically for late returns
- [ ] Per-student borrowing history available
- [ ] Library analytics with charts
- [ ] QR/barcode scanning operational (if hardware supports camera)

---

### Related Files

- **Previous:** [`M14_DOCUMENT_LETTER.md`](M14_DOCUMENT_LETTER.md)
- **Next:** [`M16_COUNSELING_BK.md`](M16_COUNSELING_BK.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M15

# Epic M13 — BOS Fund Management

> **Epic ID:** M13
> **Phase:** 3 (Complete School Operations)
> **Priority:** Important
> **Sprint Target:** Sprint 15
> **Total Story Points:** 21 SP
> **Dependencies:** M1 (School Profile)
> **Blocks:** —

---

## Epic Overview

Manage government BOS (Bantuan Operasional Sekolah) funds with proper accountability. Schools receive BOS funds from the government and must report usage according to Juknis BOS. This module covers RKAS planning, budget allocation per 8 standar categories, expense tracking with receipt uploads, and BOS-compliant reporting.

---

## User Stories

### US-13.1: RKAS Planning

**As a** Bendahara,
**I want** to create the school's RKAS (Rencana Kegiatan dan Anggaran Sekolah),
**so that** budget planning follows the government-mandated format.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Create RKAS for academic year with total BOS allocation amount
- [ ] Activities organized by 8 SNP (Standar Nasional Pendidikan) categories
- [ ] Each activity: name, description, quantity, unit cost, total
- [ ] Auto-calculate totals per category and grand total
- [ ] RKAS cannot exceed total BOS allocation
- [ ] RKAS exportable to Excel in government format

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `bos_budgets` migration (school_id, academic_year_id, total_allocation, status) | Backend | 0.5h |
| 2 | Create `bos_budget_items` migration (budget_id, snp_category, activity_name, description, qty, unit_cost, total) | Backend | 0.5h |
| 3 | Create `SnpCategory` enum (8 categories: standar isi, proses, kompetensi_lulusan, PTK, sarana_prasarana, pengelolaan, pembiayaan, penilaian) | Backend | 0.5h |
| 4 | Create `BosBudgetService` with CRUD + allocation validation | Backend | 1.5h |
| 5 | Create `BOS/RKAS/Edit.vue` — budget planning form with category sections | Frontend | 3h |
| 6 | Create RKAS summary page with per-category totals | Frontend | 1h |
| 7 | Create Excel export in government-compliant format | Backend | 1.5h |

---

### US-13.2: Budget Allocation by Category

**As a** Bendahara,
**I want** to allocate the BOS budget per 8 SNP categories,
**so that** spending follows the government allocation rules.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] 8 SNP categories displayed with allocated vs spent amounts
- [ ] Visual progress bars showing utilization per category
- [ ] Warning when category spending approaches allocation limit
- [ ] Percentage breakdown across categories

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create budget allocation summary endpoint | Backend | 0.5h |
| 2 | Create `BOS/BudgetOverview.vue` with category cards and progress bars | Frontend | 2h |
| 3 | Add over-allocation warning indicators | Frontend | 0.5h |

---

### US-13.3: Expense Tracking

**As a** Bendahara,
**I want** to record expenses with receipt uploads,
**so that** every rupiah spent is documented for accountability.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Record expense: date, description, amount, SNP category, vendor/supplier
- [ ] Upload receipt image/PDF (required for each expense)
- [ ] Expense auto-deducted from category allocation
- [ ] Expense list with filters (date range, category, amount range)
- [ ] All expenses logged in audit trail (who entered, when, modifications)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `bos_expenses` migration (school_id, budget_id, snp_category, date, description, amount DECIMAL(15,2), vendor, receipt_media, created_by) | Backend | 0.5h |
| 2 | Create `BosExpenseService` with CRUD + allocation check | Backend | 1h |
| 3 | Configure `spatie/media-library` collection for expense receipts | Backend | 0.5h |
| 4 | Configure `spatie/laravel-activitylog` for expense changes | Backend | 0.5h |
| 5 | Create `BOS/Expenses/Index.vue` with filterable DataTable | Frontend | 2h |
| 6 | Create `BOS/Expenses/Create.vue` form with receipt upload | Frontend | 1.5h |

---

### US-13.4: BOS Reporting

**As a** Bendahara,
**I want** to generate BOS accountability reports in government-required format,
**so that** the school passes BOS audits.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Generate report per quarter (Triwulan I–IV)
- [ ] Report shows: allocation, realization, remaining per category
- [ ] Report format matches Juknis BOS template
- [ ] Exportable to Excel and PDF
- [ ] Supporting documents (receipts) linkable from report

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `BosReportService` with quarterly aggregation | Backend | 1.5h |
| 2 | Create BOS report Blade template matching Juknis format | Backend | 2h |
| 3 | Create `BOS/Reports/Index.vue` — quarter selector + generate/download | Frontend | 1.5h |
| 4 | Create Excel export for BOS report | Backend | 1h |
| 5 | Create PDF export via DomPDF | Backend | 1h |

---

### US-13.5: Cash Flow Monitoring

**As a** Kepala Sekolah,
**I want** real-time visibility into BOS fund cash flow,
**so that** I can monitor spending patterns and prevent misuse.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] Dashboard showing: total received, total spent, remaining balance
- [ ] Monthly spending trend chart
- [ ] Top spending categories
- [ ] Accessible by Kepala Sekolah (read-only) and Bendahara

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create cash flow summary endpoint | Backend | 0.5h |
| 2 | Create `BOS/CashFlow.vue` dashboard with Chart.js charts | Frontend | 1.5h |

---

### US-13.6: Audit Trail

**As the** System,
**I want** a complete, immutable log of all BOS financial transactions,
**so that** the school has a verifiable audit trail for government audits.

**Story Points:** 1
**Priority:** Must

**Acceptance Criteria:**
- [ ] Every create/update/delete on BOS data logged with: who, when, before/after values
- [ ] Audit log viewable by Kepala Sekolah and Bendahara
- [ ] Audit log cannot be modified or deleted
- [ ] Filterable by date range and action type

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Configure `spatie/laravel-activitylog` for all BOS models | Backend | 0.5h |
| 2 | Create `BOS/AuditLog.vue` — filterable log viewer | Frontend | 1h |

---

## Technical Notes

- **8 SNP categories** are mandated by the government for BOS fund allocation. Each category has percentage guidelines.
- **Receipt uploads** are critical — every expense must have supporting documentation for audit compliance.
- **Audit trail** uses `spatie/laravel-activitylog` with `before/after` values — same pattern as M9 Finance.
- **BOS reports** follow the Juknis BOS format which changes annually — template should be versioned.
- **Separate from M9**: M9 handles parent-to-school SPP payments. M13 handles government BOS fund management. Different money flows, different reporting requirements.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Juknis BOS format changes annually | Report templates break | Template versioning, configurable report structure |
| Receipt images too large | Storage costs, slow upload | Auto-compress images, max 5MB per receipt |
| Bendahara enters wrong amounts | Financial discrepancy | Double-entry confirmation for amounts > Rp 1.000.000, audit trail |

---

## Definition of Done (Epic Level)

- [ ] RKAS can be created matching government format
- [ ] Expenses recorded with uploaded receipt images
- [ ] BOS report generated in Juknis-compliant format
- [ ] Cash flow dashboard accessible by Kepala Sekolah
- [ ] Audit trail is immutable and complete
- [ ] Export to Excel and PDF functional

---

### Related Files

- **Previous:** [`M12_ONLINE_EXAM.md`](M12_ONLINE_EXAM.md)
- **Next:** [`M14_DOCUMENT_LETTER.md`](M14_DOCUMENT_LETTER.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M13

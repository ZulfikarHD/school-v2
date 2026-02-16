# Epic M20 — Analytics Dashboard

> **Epic ID:** M20
> **Phase:** 4 (Scale & Differentiate)
> **Priority:** Nice-to-Have
> **Sprint Target:** Sprint 22
> **Total Story Points:** 26 SP
> **Dependencies:** M6 (Attendance), M7 (Grading), M9 (Finance)
> **Blocks:** —

---

## Epic Overview

Executive dashboard for school leadership (Kepala Sekolah) with actionable insights. Aggregates data from Attendance (M6), Grading (M7), and Finance (M9) into KPI cards, trend charts, and comparative analytics. Designed for quick decision-making — the principal opens the dashboard and immediately sees the school's health.

---

## User Stories

### US-20.1: School KPI Dashboard

**As a** Kepala Sekolah,
**I want** key metrics at a glance,
**so that** I can quickly assess the school's operational health.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] KPI cards: total students, attendance rate (today/this month), fee collection rate, average grade
- [ ] Trend indicators: up/down arrows compared to last period
- [ ] Color-coded: green (good), yellow (attention), red (critical)
- [ ] Data refreshes within 5 minutes of changes
- [ ] Dashboard loads in < 3 seconds

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `DashboardAnalyticsService` aggregating attendance, finance, grading data | Backend | 2h |
| 2 | Create KPI endpoint with caching (5-min TTL) | Backend | 0.5h |
| 3 | Create `Dashboard/Principal.vue` with KPI cards and trend indicators | Frontend | 3h |
| 4 | Create reusable `KpiCard.vue` component (value, trend, color) | Frontend | 1h |

---

### US-20.2: Attendance Trends

**As a** Kepala Sekolah,
**I want** to see attendance patterns and anomalies over time,
**so that** I can identify and address attendance issues.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Daily attendance rate chart (line chart, past 30 days)
- [ ] Monthly comparison (this month vs last month vs same month last year)
- [ ] Worst-attendance classes ranked
- [ ] Day-of-week patterns (e.g., Mondays have lowest attendance)
- [ ] Drill-down: click class → see individual student attendance

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `AttendanceAnalyticsService` with trend queries | Backend | 1.5h |
| 2 | Create attendance trend chart (Chart.js line chart) | Frontend | 1.5h |
| 3 | Create class ranking table (lowest attendance) | Frontend | 1h |
| 4 | Create day-of-week pattern chart (bar chart) | Frontend | 0.5h |

---

### US-20.3: Academic Performance

**As a** Kepala Sekolah,
**I want** to see grade trends, pass rates, and subject comparisons,
**so that** I can track academic quality across the school.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Average grade trend per semester (school-wide)
- [ ] Pass rate (% above KKTP) per subject
- [ ] Subject comparison chart (which subjects have lowest performance)
- [ ] Grade distribution across school (histogram)
- [ ] Top/bottom performing classes per subject

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `AcademicAnalyticsService` with grade aggregation queries | Backend | 2h |
| 2 | Create grade trend and pass rate charts | Frontend | 2h |
| 3 | Create subject comparison chart (horizontal bar) | Frontend | 1h |
| 4 | Create class performance ranking | Frontend | 1h |

---

### US-20.4: Financial Health

**As a** Kepala Sekolah,
**I want** to see revenue, outstanding fees, and BOS utilization at a glance,
**so that** I understand the school's financial position.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Total revenue this month/semester/year
- [ ] Outstanding fee amount and percentage
- [ ] Fee collection trend (monthly chart)
- [ ] BOS fund utilization (if M13 is active)
- [ ] Revenue breakdown by fee type (pie chart)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `FinancialAnalyticsService` with revenue and outstanding queries | Backend | 1.5h |
| 2 | Create financial dashboard section with summary cards | Frontend | 1.5h |
| 3 | Create revenue trend chart and fee type breakdown pie chart | Frontend | 1.5h |
| 4 | Create outstanding fees aging report (0–30, 30–60, 60+ days) | Frontend | 1h |

---

### US-20.5: Teacher Workload Analysis

**As a** Kepala Sekolah,
**I want** to see teacher workload balance,
**so that** I can ensure fair distribution of teaching hours.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Teaching hours per teacher (bar chart)
- [ ] Average vs. max vs. min hours across teachers
- [ ] Teachers with excessive load highlighted
- [ ] Homeroom (Wali Kelas) assignments overview

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `WorkloadAnalyticsService` querying teacher assignments | Backend | 1h |
| 2 | Create workload chart (horizontal bar, sorted by hours) | Frontend | 1.5h |
| 3 | Highlight overloaded teachers | Frontend | 0.5h |

---

### US-20.6: Class Comparison

**As a** Kepala Sekolah or Teacher,
**I want** to compare metrics across classes,
**so that** I can identify classes that need extra attention.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Compare classes by: attendance rate, average grade, fee collection rate
- [ ] Side-by-side comparison (select 2–4 classes)
- [ ] Radar chart for multi-metric comparison
- [ ] Ranking table sortable by any metric

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `ClassComparisonService` with multi-metric queries | Backend | 1h |
| 2 | Create class comparison UI with radar chart (Chart.js) | Frontend | 2h |
| 3 | Create class ranking table (sortable columns) | Frontend | 1h |

---

### US-20.7: Export

**As a** Kepala Sekolah,
**I want** to export dashboard data to Excel and PDF,
**so that** I can share reports in meetings and presentations.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] Export current dashboard view as PDF (print-optimized)
- [ ] Export underlying data as Excel
- [ ] Export includes date range and filters applied

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create PDF export using DomPDF for dashboard summary | Backend | 1h |
| 2 | Create Excel export for analytics data tables | Backend | 1h |
| 3 | Add export buttons to each analytics section | Frontend | 0.5h |

---

## Technical Notes

- **Caching strategy**: Dashboard aggregations cached with 5-minute TTL. Cache invalidated on relevant data changes via event listeners.
- **Chart.js + vue-chartjs**: All charts rendered client-side for interactivity (hover tooltips, click drill-down).
- **Performance**: Complex aggregation queries should use materialized summaries where possible (e.g., `student_fee_summaries` from M9).
- **Role-specific dashboards**: Kepala Sekolah sees the full analytics. Teacher sees their class analytics only. Parent sees their child's data only.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Complex queries slow down dashboard | > 3s load time | Caching, materialized summaries, denormalized aggregation tables |
| Too much data overwhelms principal | Information overload | Default to most important KPIs, progressive disclosure for details |
| Inconsistent data between modules | Misleading analytics | Single source of truth per metric, automated consistency checks |

---

## Definition of Done (Epic Level)

- [ ] KPI dashboard with key metrics at a glance
- [ ] Attendance trend charts with anomaly identification
- [ ] Academic performance analysis across subjects and classes
- [ ] Financial health overview with collection rates
- [ ] Teacher workload visualization
- [ ] Class comparison with multi-metric radar chart
- [ ] Export to Excel and PDF
- [ ] Dashboard loads in < 3 seconds

---

### Related Files

- **Previous:** [`M19_INVENTORY.md`](M19_INVENTORY.md)
- **Next:** [`M21_GOV_COMPLIANCE.md`](M21_GOV_COMPLIANCE.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M20

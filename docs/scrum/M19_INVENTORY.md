# Epic M19 — Inventory & Facilities (Sarana Prasarana)

> **Epic ID:** M19
> **Phase:** 4 (Scale & Differentiate)
> **Priority:** Nice-to-Have
> **Sprint Target:** Sprint 21
> **Total Story Points:** 18 SP
> **Dependencies:** M1 (School Profile)
> **Blocks:** —

---

## Epic Overview

Track school assets and facility management. Schools need to manage inventaris barang (asset registry), room/facility status, maintenance requests, and procurement workflows. This data also supports accreditation preparation (M21).

---

## User Stories

### US-19.1: Asset Registry

**As an** Admin,
**I want** to maintain an inventory of all school assets,
**so that** we have accurate records for management and government reporting.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Register asset: name, code, category, purchase date, purchase price, condition, location, photo
- [ ] Asset categories: furniture, electronics, lab equipment, sports, vehicles, etc.
- [ ] Unique asset code (auto-generated or manual)
- [ ] Asset list with DataTable (filterable by category, condition, location)
- [ ] QR label generation for physical asset tagging
- [ ] Bulk import from Excel

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `assets` migration (school_id, code, name, category enum, purchase_date, purchase_price DECIMAL, condition enum, location, notes) | Backend | 0.5h |
| 2 | Create `AssetCategory` and `AssetCondition` enums | Backend | 0.5h |
| 3 | Create `Asset` model with `BelongsToSchool`, `Searchable` | Backend | 0.5h |
| 4 | Create `AssetService` CRUD with code generation | Backend | 1h |
| 5 | Create `Inventory/Assets/Index.vue` with DataTable | Frontend | 2h |
| 6 | Create `Inventory/Assets/Create.vue` form with photo upload | Frontend | 1.5h |
| 7 | Create QR label generation (printable) | Backend | 1h |
| 8 | Create Excel import for bulk asset registration | Backend | 1h |

---

### US-19.2: Room & Facility Management

**As an** Admin,
**I want** to track rooms, labs, and facilities with their status and assigned assets,
**so that** facility information is centralized.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Room/facility record: name, type, capacity, condition, assigned assets
- [ ] Room types: classroom, lab, library, sports hall, office, etc. (reuse from M5)
- [ ] Room condition tracking (baik, rusak ringan, rusak berat)
- [ ] Assets assigned to rooms are linked
- [ ] Room utilization data from schedule (M5) if available

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Extend room data from M5 with condition and assigned assets | Backend | 0.5h |
| 2 | Create room detail page showing condition + assigned assets | Frontend | 1.5h |
| 3 | Create room condition update flow | Frontend | 0.5h |

---

### US-19.3: Maintenance Requests

**As a** Teacher or Admin,
**I want** to submit and track maintenance requests for damaged facilities or equipment,
**so that** issues are documented and resolved systematically.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Submit request: item/room, description, urgency (low/medium/high), photo
- [ ] Request status workflow: Submitted → Reviewed → In Progress → Completed
- [ ] Assignee (who will handle the repair)
- [ ] Request list with status filters
- [ ] Notification to admin on new request

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `maintenance_requests` migration (school_id, asset_id nullable, room_id nullable, description, urgency enum, status enum, assigned_to, completed_at) | Backend | 0.5h |
| 2 | Create `MaintenanceService` with CRUD + status transitions | Backend | 1h |
| 3 | Create `Inventory/Maintenance/Index.vue` with status board/DataTable | Frontend | 1.5h |
| 4 | Create `Inventory/Maintenance/Create.vue` request form | Frontend | 1h |
| 5 | Send notification to admin on new request | Backend | 0.5h |

---

### US-19.4: Procurement Workflow

**As an** Admin,
**I want** a request → approval → purchase workflow for new items,
**so that** procurement is organized and traceable.

**Story Points:** 5
**Priority:** Could

**Acceptance Criteria:**
- [ ] Create procurement request: item name, quantity, estimated cost, justification
- [ ] Approval workflow: Requester → Kepala Sekolah approval
- [ ] Approved items can be marked as purchased with actual cost and vendor
- [ ] Purchased items auto-added to asset registry
- [ ] Procurement history with budget tracking

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `procurement_requests` migration (school_id, item_name, quantity, estimated_cost, justification, status enum, approved_by, purchased_at, actual_cost, vendor) | Backend | 0.5h |
| 2 | Create `ProcurementService` with request + approval + purchase workflow | Backend | 1.5h |
| 3 | Create procurement request form | Frontend | 1h |
| 4 | Create approval UI for Kepala Sekolah | Frontend | 1h |
| 5 | Auto-create asset record on purchase confirmation | Backend | 0.5h |

---

### US-19.5: Asset Depreciation

**As an** Admin,
**I want** to track asset value depreciation over time,
**so that** the school has accurate financial records for government reporting.

**Story Points:** 2
**Priority:** Could

**Acceptance Criteria:**
- [ ] Depreciation method: straight-line (configurable useful life per category)
- [ ] Current book value calculated automatically
- [ ] Depreciation report per year
- [ ] Exportable for financial reporting

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create depreciation calculation service (straight-line, useful life per category) | Backend | 1h |
| 2 | Create depreciation report page | Frontend | 1h |
| 3 | Add Excel export for depreciation report | Backend | 0.5h |

---

## Technical Notes

- **Asset codes** follow school-specific formats (e.g., `INV-ELK-001` for electronics). Auto-generated with configurable prefix per category.
- **QR labels** generated as printable PDF sheets (multiple labels per page) for physical tagging.
- **Procurement workflow** is a simple two-step approval (requester → Kepala Sekolah). No complex multi-level approval needed.
- **Depreciation** uses straight-line method. Useful life defaults configurable per asset category.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Schools have thousands of legacy assets | Tedious initial data entry | Excel bulk import, phased data entry |
| QR labels fall off or get damaged | Can't identify asset | QR code encoded with asset ID, manual lookup fallback |
| Procurement approval bottleneck | Kepala Sekolah too busy | Batch approval UI, configurable auto-approve for small amounts |

---

## Definition of Done (Epic Level)

- [ ] Asset registry with CRUD and QR label generation
- [ ] Room/facility management with condition tracking
- [ ] Maintenance request workflow functional
- [ ] Procurement request → approval → purchase flow
- [ ] Asset depreciation calculated and reportable
- [ ] Excel import/export for asset data

---

### Related Files

- **Previous:** [`M18_PPDB.md`](M18_PPDB.md)
- **Next:** [`M20_ANALYTICS.md`](M20_ANALYTICS.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M19

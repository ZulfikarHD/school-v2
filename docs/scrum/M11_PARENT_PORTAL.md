# Epic M11 — Parent Portal

> **Epic ID:** M11
> **Phase:** 1 (MVP)
> **Priority:** CORE
> **Sprint Target:** Sprint 6
> **Total Story Points:** 24 SP
> **Dependencies:** M6 (Attendance), M9 (Finance), M10 (Communication)
> **Blocks:** — (end-user consumer of other modules)

---

## Epic Overview

Mobile-first interface for parents to stay connected with their child's school life. Built as a PWA optimized for budget Android phones (Redmi 9-class) on slow 3G connections. This portal aggregates data from Attendance (M6), Finance (M9), and Communication (M10) into a unified parent experience. Phase 2 adds grades and rapor viewing.

---

## User Stories

### US-11.1: Attendance View

**As a** Parent,
**I want** to see my child's attendance status for today and historical records,
**so that** I know if my child is going to school.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Today's attendance status shown prominently (Hadir / Sakit / Izin / Alpa / Not yet marked)
- [ ] Monthly calendar view with color-coded attendance days
- [ ] Monthly summary: total Hadir, Sakit, Izin, Alpa
- [ ] Data matches what teacher entered in M6
- [ ] Works on 3G — loads in < 3 seconds

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create parent-scoped attendance endpoint (only linked children) | Backend | 1h |
| 2 | Create `Parent/ChildAttendance.vue` with today status card | Frontend | 1.5h |
| 3 | Create monthly calendar component with color-coded days | Frontend | 2h |
| 4 | Create monthly summary stats (H/S/I/A counts) | Frontend | 0.5h |

---

### US-11.2: Payment & Bills

**As a** Parent,
**I want** to view outstanding fees, pay online, and view receipts,
**so that** I can manage my child's school payments from my phone.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] "Tagihan" section shows outstanding fees with amount and due date
- [ ] Overdue fees highlighted in red
- [ ] "Bayar" button opens payment method selection (VA, QRIS, E-Wallet)
- [ ] Payment status page shows confirmation after payment
- [ ] Payment history with receipts downloadable
- [ ] Total outstanding amount shown at top

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create parent-scoped fee/payment endpoints | Backend | 1h |
| 2 | Create `Parent/Bills.vue` — outstanding fees list with "Bayar" buttons | Frontend | 2.5h |
| 3 | Create payment method selection flow (reuse M9 components) | Frontend | 1.5h |
| 4 | Create payment status/confirmation page | Frontend | 1h |
| 5 | Create `Parent/PaymentHistory.vue` with receipt download | Frontend | 1.5h |

---

### US-11.3: Announcements

**As a** Parent,
**I want** to read school announcements and events in my portal,
**so that** I stay informed about school activities.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Announcement feed with newest first
- [ ] Pinned announcements at top
- [ ] Unread badge count on bottom nav tab
- [ ] Announcement detail page with attachments
- [ ] Only shows announcements targeted at parent's class/grade

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create parent-scoped announcement endpoint (filtered by target audience) | Backend | 0.5h |
| 2 | Create `Parent/Announcements.vue` feed with unread indicators | Frontend | 1.5h |
| 3 | Create announcement detail view | Frontend | 0.5h |
| 4 | Track read status when parent views announcement | Backend | 0.5h |

---

### US-11.4: School Calendar

**As a** Parent,
**I want** to view upcoming school events and schedule,
**so that** I can plan around school activities.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] Calendar view showing school events for the month
- [ ] Upcoming events list (next 7 days) on dashboard
- [ ] Event details: title, date/time, location, description
- [ ] Syncs with school events from M10

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create parent-scoped events endpoint | Backend | 0.5h |
| 2 | Create `Parent/Calendar.vue` with monthly view | Frontend | 1.5h |
| 3 | Create upcoming events widget for parent dashboard | Frontend | 0.5h |

---

### US-11.5: Absence Request (Izin)

**As a** Parent,
**I want** to submit a permission/izin request for my child's absence,
**so that** I don't need to physically visit the school or call.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Form with: date(s), reason (Sakit, Izin, Keluarga), description, supporting document (optional)
- [ ] Request sent to Wali Kelas for acknowledgment
- [ ] Status tracking: Submitted → Acknowledged
- [ ] History of past requests visible
- [ ] Wali Kelas receives in-app notification of new request

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `absence_requests` migration (school_id, student_id, parent_id, dates, reason, description, status, document) | Backend | 0.5h |
| 2 | Create `AbsenceRequestService` with submit + acknowledge | Backend | 1h |
| 3 | Create `Parent/AbsenceRequest/Create.vue` form | Frontend | 1.5h |
| 4 | Create `Parent/AbsenceRequest/Index.vue` request history | Frontend | 1h |
| 5 | Create Wali Kelas notification + acknowledgment UI | Frontend | 1h |

---

### US-11.6: Contact Wali Kelas

**As a** Parent,
**I want** to send a message to my child's homeroom teacher,
**so that** I can communicate about my child's progress.

**Story Points:** 2
**Priority:** Should

> Note: Full chat implemented in M10 Phase 2 (US-10.8). Phase 1 provides a simple contact form.

**Acceptance Criteria:**
- [ ] "Hubungi Wali Kelas" button on parent dashboard
- [ ] Simple message form (subject, body)
- [ ] Message delivered as in-app notification to Wali Kelas
- [ ] Optional: forward via WhatsApp to Wali Kelas

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create simple message-to-wali-kelas endpoint | Backend | 0.5h |
| 2 | Create `Parent/ContactWaliKelas.vue` form | Frontend | 1h |
| 3 | Send notification to Wali Kelas (in-app + optional WhatsApp) | Backend | 0.5h |

---

### US-11.7: Child's Schedule

**As a** Parent,
**I want** to view my child's daily and weekly schedule,
**so that** I know what subjects they have each day.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] Daily schedule for today showing time, subject, teacher
- [ ] Weekly grid view
- [ ] Schedule from M5 data
- [ ] Simple, mobile-friendly layout

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create parent-scoped schedule endpoint (child's class schedule) | Backend | 0.5h |
| 2 | Create `Parent/Schedule.vue` with daily and weekly view | Frontend | 1.5h |

---

### US-11.8: Multi-Child Support

**As a** Parent with multiple children in the same school,
**I want** to switch between my children's views easily,
**so that** I can check each child's information without logging out.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Child selector at top of parent portal (dropdown or tab)
- [ ] All data updates when switching child (attendance, bills, schedule)
- [ ] Default to first child, remember last selected
- [ ] Child's name and photo displayed prominently
- [ ] Works smoothly even with 4+ children

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `ParentPortalService` — get linked children, get child data | Backend | 1h |
| 2 | Share linked children list via Inertia middleware | Backend | 0.5h |
| 3 | Create `ChildSelector.vue` component (dropdown with photos/names) | Frontend | 1.5h |
| 4 | Create `ParentDashboard.vue` — aggregated view per child | Frontend | 3h |
| 5 | Implement child switching with URL parameter persistence | Frontend | 1h |
| 6 | Test with parent who has 4 children | Backend | 0.5h |

---

## Technical Notes

- **ParentLayout**: Bottom navigation with 4–5 tabs (Beranda, Tagihan, Pengumuman, Jadwal, Profil). Large touch targets (min 48px). One-handed operation.
- **PWA**: Progressive Web App for app-like experience. Target < 200KB initial JS (gzipped). Service Worker for offline resilience.
- **SSR enabled** for faster initial paint on slow 3G.
- **Parent data access enforced at query level**: `Student::whereHas('parents', fn($q) => $q->where('user_id', auth()->id()))` — not just UI-level restriction.
- **Low-end device optimization**: No decorative animations. Lazy-load images. Compress all assets. Use `vite-plugin-visualizer` to monitor bundle size.
- **Offline resilience**: `useOnlineStatus` composable detects offline. Pending actions stored in localStorage. Service Worker background sync.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Portal too slow on budget phones | Parents won't use it | SSR, < 200KB JS, no animations, test on actual Redmi 9 |
| Data access bypass (parent sees other's child) | Privacy breach | Query-level enforcement, feature tests with 2+ parent accounts |
| Too many tabs/features overwhelm parents | Poor UX, confusion | Start with 4 tabs max, progressive disclosure, user testing |
| Offline usage edge cases | Data conflicts | Clear "offline" banner, queue actions, sync indicators |

---

## Definition of Done (Epic Level)

- [ ] Parent can view child's attendance (today + historical)
- [ ] Parent can view and pay SPP from the portal
- [ ] Announcements visible within seconds of publishing
- [ ] Absence request can be submitted
- [ ] Child's schedule viewable
- [ ] Multi-child switching works smoothly (3+ children)
- [ ] Portal loads in < 3 seconds on 3G connection
- [ ] All data queries enforce parent-child relationship at query level
- [ ] PWA installable on Android with offline indicator

---

### Related Files

- **Previous:** [`M10_COMMUNICATION.md`](M10_COMMUNICATION.md)
- **Next:** [`M12_ONLINE_EXAM.md`](M12_ONLINE_EXAM.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M11

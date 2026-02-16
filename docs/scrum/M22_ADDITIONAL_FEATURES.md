# Epic M22 — Additional Future Features

> **Epic ID:** M22
> **Phase:** 4+ (Future)
> **Priority:** Nice-to-Have
> **Sprint Target:** Sprint 24–25+
> **Total Story Points:** TBD (estimated per feature when prioritized)
> **Dependencies:** Various (per feature)
> **Blocks:** —

---

## Epic Overview

Collection of future feature ideas that are not part of the core platform but could add significant value. These features are parked in the backlog and will be prioritized based on user feedback from pilot schools and market demand. Each feature below is a potential User Story or sub-Epic that would be refined when prioritized.

---

## Feature Backlog

### US-22.1: Mobile PWA Enhancement / Native App

**As a** Parent or Teacher,
**I want** an installable mobile app experience,
**so that** I can access the platform like a native app on my phone.

**Story Points:** 8 (estimated)
**Priority:** Won't (Phase 4 — evaluate after PWA feedback)

**Scope:**
- PWA improvements: add-to-homescreen prompt, splash screen, app icon
- Consider Capacitor wrapper if native features needed (push notifications, camera)
- Evaluate based on PWA adoption rate from pilot schools

---

### US-22.2: Full Offline Mode

**As a** Teacher in an area with unstable internet,
**I want** the app to work fully offline and sync when online,
**so that** I can do my work regardless of connectivity.

**Story Points:** 13 (estimated)
**Priority:** Won't (Phase 4 — evaluate demand)

**Scope:**
- Service Worker caching for all critical pages
- IndexedDB for offline data storage
- Background sync queue for all write operations
- Conflict resolution for concurrent offline/online edits
- Offline indicator with sync status

---

### US-22.3: WhatsApp Bot

**As a** Parent,
**I want** to check my child's attendance and grades by chatting with a WhatsApp bot,
**so that** I don't need to open the app at all.

**Story Points:** 8 (estimated)
**Priority:** Won't (Phase 4+)

**Scope:**
- WhatsApp bot using Fonnte/Wablas webhook
- Commands: "absensi [nama anak]", "nilai [nama anak]", "tagihan"
- Natural language processing (basic keyword matching)
- Response with formatted text messages
- Auth via phone number matching

---

### US-22.4: QR Code Student ID

**As an** Admin,
**I want** to generate student ID cards with QR codes,
**so that** students have official identification cards.

**Story Points:** 3 (estimated)
**Priority:** Could

**Scope:**
- Student ID card template with: photo, name, NISN, class, school logo, QR code
- QR code encodes student ID for attendance scanning (M6)
- Batch print (A4 page with multiple cards)
- Customizable template per school

**Tasks (when prioritized):**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create ID card Blade template with QR code generation | Backend | 2h |
| 2 | Create batch print layout (8 cards per A4) | Backend | 1h |
| 3 | Create `StudentId/Generate.vue` — class selector, preview, print | Frontend | 1.5h |

---

### US-22.5: Bus/Transport Tracking

**As a** Parent,
**I want** to track the school bus location in real-time,
**so that** I know when to expect my child to arrive.

**Story Points:** 13 (estimated)
**Priority:** Won't (requires GPS hardware integration)

**Scope:**
- GPS tracker device integration
- Real-time location on map (Google Maps / OpenStreetMap)
- Route management with stops
- Estimated arrival time
- Notification when bus is approaching

---

### US-22.6: Canteen Pre-ordering

**As a** Parent or Student,
**I want** to pre-order food from the school canteen,
**so that** food is ready at break time.

**Story Points:** 8 (estimated)
**Priority:** Won't (requires canteen vendor cooperation)

**Scope:**
- Canteen menu management (daily menu)
- Order placement with payment integration (reuse M9)
- Order pickup notification
- Canteen vendor dashboard
- Order history and favorites

---

### US-22.7: Health / UKS Records

**As an** UKS (School Health Unit) staff,
**I want** to record student health visits and treatments,
**so that** health records are digitized.

**Story Points:** 5 (estimated)
**Priority:** Could

**Scope:**
- Record UKS visit: student, date, complaint, treatment, referral
- Link to student health records (M3)
- Monthly health statistics
- Common illness patterns

---

### US-22.8: Visitor Management

**As a** security or admin staff,
**I want** to track school visitors,
**so that** we have a record of who enters the school.

**Story Points:** 3 (estimated)
**Priority:** Could

**Scope:**
- Visitor registration: name, ID, purpose, host (teacher/staff), time in/out
- Visitor badge printing
- Visitor log searchable by date, name, purpose
- Pre-registered visitors (expected appointments)

---

### US-22.9: LMS Integration

**As a** Teacher,
**I want** Learning Management System features (assignments, materials, discussion),
**so that** online learning is supported within the platform.

**Story Points:** 13 (estimated)
**Priority:** Won't (major feature, evaluate market need)

**Scope:**
- Assignment creation and submission (with deadline)
- Learning material upload per subject
- Discussion forum per class
- Grade integration with M7
- Video conferencing integration (link to Zoom/Google Meet)

---

### US-22.10: Multi-Language Support

**As a** platform admin,
**I want** to support multiple languages (Bahasa Indonesia primary + English),
**so that** the platform can serve international schools.

**Story Points:** 8 (estimated)
**Priority:** Won't (Phase 5 — when targeting international market)

**Scope:**
- i18n framework setup (vue-i18n)
- Bahasa Indonesia as default, English as secondary
- Language switcher in UI
- All UI strings extracted to translation files
- Database content remains in school's language

---

## Prioritization Framework

When evaluating which features to build next, use this matrix:

| Factor | Weight | Scoring |
|--------|--------|---------|
| User demand (from pilot schools) | 40% | How many schools request it? |
| Revenue impact | 25% | Does it increase subscriptions or transactions? |
| Development effort | 20% | How many story points? |
| Strategic differentiation | 15% | Does it set us apart from competitors? |

### Scoring Scale

- 1 = Low (few schools want it / no revenue impact / very high effort)
- 3 = Medium
- 5 = High (many schools request / direct revenue impact / low effort)

---

## Technical Notes

- Features in this epic are **not estimated in detail** until they are moved to a sprint for implementation.
- Each feature should go through **Backlog Refinement** before being scheduled.
- Some features (WhatsApp Bot, Bus Tracking, LMS) are **large enough to be separate Epics** — they would be split when prioritized.
- **QR Student ID** and **Visitor Management** are lightweight and could be quick wins.
- **Full Offline Mode** is architecturally significant — requires careful planning as a spike first.

---

## Definition of Done (Epic Level)

This epic is never truly "done" — it's a living backlog. A feature is considered done when:

- [ ] Feature refined and estimated
- [ ] Implemented according to its own acceptance criteria
- [ ] Tested and deployed
- [ ] User feedback collected from pilot schools

---

### Related Files

- **Previous:** [`M21_GOV_COMPLIANCE.md`](M21_GOV_COMPLIANCE.md)
- **Scrum Overview:** [`00_SCRUM_OVERVIEW.md`](00_SCRUM_OVERVIEW.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M22

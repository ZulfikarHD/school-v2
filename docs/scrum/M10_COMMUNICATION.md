# Epic M10 — Communication

> **Epic ID:** M10
> **Phase:** 1 (basic) + Phase 2 (enhanced)
> **Priority:** CORE
> **Sprint Target:** Sprint 4 (basic) + Sprint 12 (enhanced)
> **Total Story Points:** 26 SP (18 basic + 8 enhanced)
> **Dependencies:** M2 (Users & Roles)
> **Blocks:** M11 (Parent Portal — announcements view)

---

## Epic Overview

Replace chaotic WhatsApp groups with structured announcements, targeted messaging, and WhatsApp blast capabilities. Phase 1 delivers one-way announcements and WhatsApp blast. Phase 2 adds parent-teacher private messaging.

---

## Phase 1 — Basic Communication (Sprint 4)

### US-10.1: School Announcements

**As an** Admin,
**I want** to create and publish announcements visible to all or targeted groups,
**so that** school information reaches the right people in an organized way.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Create announcement with: title, body (rich text), attachments, publish date
- [ ] Target audience: all, specific grade, specific class, specific role
- [ ] Draft/published status with scheduled publishing
- [ ] Announcement list with newest first
- [ ] Pinned announcements stay at top
- [ ] Announcement visible in all user portals (admin, teacher, parent)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `announcements` migration (school_id, title, body, target_type, target_ids JSONB, status, published_at, pinned, created_by) | Backend | 0.5h |
| 2 | Create `Announcement` model with `BelongsToSchool`, scopes for targeting | Backend | 0.5h |
| 3 | Create `AnnouncementService` with create, publish, target resolution | Backend | 1h |
| 4 | Create `AnnouncementController` CRUD | Backend | 0.5h |
| 5 | Create `Communication/Announcements/Index.vue` — list with search/filter | Frontend | 2h |
| 6 | Create `Communication/Announcements/Create.vue` — rich text editor, target selector, attachment upload | Frontend | 2.5h |
| 7 | Create `Communication/Announcements/Show.vue` — detail view | Frontend | 1h |

---

### US-10.2: Read Receipts

**As an** Admin,
**I want** to track who has seen each announcement,
**so that** I know the reach of important messages.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] Track when each user views an announcement
- [ ] Admin sees percentage: "85% of parents have read this"
- [ ] Drill-down: list of who read and who hasn't
- [ ] "Unread" badge on announcements in user portal

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `announcement_reads` pivot migration (announcement_id, user_id, read_at) | Backend | 0.5h |
| 2 | Create read tracking endpoint (called when user views announcement) | Backend | 0.5h |
| 3 | Create read statistics endpoint (percentage, read/unread lists) | Backend | 0.5h |
| 4 | Display read percentage on announcement list for admin | Frontend | 1h |
| 5 | Add "unread" badge indicator for users | Frontend | 0.5h |

---

### US-10.3: Targeted Messaging

**As an** Admin,
**I want** to send messages to specific classes, grades, or parent groups,
**so that** only relevant people receive each message.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Target options: All, specific grade(s), specific class(es), specific role(s)
- [ ] Preview recipient count before sending
- [ ] Target selection UI with checkboxes and "Select All" option
- [ ] Message delivered only to targeted users

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `AudienceResolver` service (resolve target type → user IDs) | Backend | 1h |
| 2 | Create target selector component (grade, class, role checkboxes) | Frontend | 1.5h |
| 3 | Show recipient count preview | Frontend | 0.5h |

---

### US-10.4: WhatsApp Blast

**As an** Admin,
**I want** to send announcements via WhatsApp to parent groups,
**so that** parents who don't open the app still receive the message.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Admin can send any announcement as WhatsApp blast to its target audience
- [ ] Messages queued and sent with rate limiting (30/minute for Fonnte)
- [ ] Delivery status tracked per recipient (sent, delivered, failed)
- [ ] Failed messages visible in dead letter queue for manual retry
- [ ] Blast progress visible: "150 of 300 sent"
- [ ] Priority queues: absence alerts > payment reminders > announcements

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `WhatsAppBlastService` using `Bus::chain()` with batched delays | Backend | 2h |
| 2 | Create `SendWhatsAppBlast` job with rate limiting (`RateLimited::perMinute(30)`) | Backend | 1h |
| 3 | Create `whatsapp_message_logs` migration (recipient, message, status, sent_at, error) | Backend | 0.5h |
| 4 | Create blast trigger UI on announcement detail page | Frontend | 1h |
| 5 | Create blast progress indicator (polling or Reverb) | Frontend | 1h |
| 6 | Create dead letter queue view for failed messages | Frontend | 1.5h |

---

### US-10.5: In-App Notifications

**As a** user,
**I want** to receive real-time in-app notifications for important events,
**so that** I see updates without refreshing the page.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Notification bell icon in topbar with unread count badge
- [ ] Notification dropdown showing recent notifications
- [ ] Real-time updates via Laravel Reverb (WebSocket)
- [ ] Notification types: announcement, attendance alert, payment confirmation, etc.
- [ ] Mark as read (individual or all)
- [ ] Notification center page with full history

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create Laravel notification system with database channel | Backend | 1h |
| 2 | Create Reverb broadcast channel for real-time notifications | Backend | 1h |
| 3 | Create `NotificationBell.vue` component with dropdown | Frontend | 2h |
| 4 | Create `Notifications/Index.vue` full notification center page | Frontend | 1h |
| 5 | Implement mark-as-read (individual and bulk) | Frontend | 0.5h |

---

### US-10.6: Event/Calendar Sharing

**As an** Admin,
**I want** to share school calendar events with all users,
**so that** everyone knows about upcoming activities, holidays, and deadlines.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Create events: title, description, date/time, location, target audience
- [ ] Calendar view (monthly) showing all events
- [ ] Upcoming events widget on dashboard
- [ ] Events can be recurring (e.g., weekly assembly)
- [ ] Events shareable via WhatsApp

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `school_events` migration (school_id, title, description, start_at, end_at, location, recurrence, target) | Backend | 0.5h |
| 2 | Create `SchoolEvent` model with `BelongsToSchool` | Backend | 0.5h |
| 3 | Create `SchoolEventService` CRUD with recurrence handling | Backend | 1h |
| 4 | Create `Calendar.vue` monthly calendar component | Frontend | 2.5h |
| 5 | Create upcoming events widget for dashboard | Frontend | 1h |

---

### US-10.7: Emergency Broadcast

**As an** Admin,
**I want** to send an urgent message to all parents and staff immediately,
**so that** critical information (e.g., school closure) reaches everyone fast.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Emergency broadcast bypasses normal queue — uses `notifications-high` priority queue
- [ ] Sent via both WhatsApp and in-app simultaneously
- [ ] Confirmation prompt: "This will send to ALL parents and staff. Proceed?"
- [ ] Delivery tracked with timestamp
- [ ] Emergency icon/styling distinguishes from regular announcements

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `EmergencyBroadcastService` using high-priority queue | Backend | 1h |
| 2 | Create emergency broadcast UI with double confirmation | Frontend | 1h |
| 3 | Create emergency notification styling (red banner, distinct icon) | Frontend | 0.5h |

---

## Phase 2 — Enhanced Communication (Sprint 12)

### US-10.8: Parent-Teacher Private Messaging

**As a** Parent or Wali Kelas,
**I want** to send private messages to each other,
**so that** we can discuss the child's progress privately without using personal WhatsApp.

**Story Points:** 8
**Priority:** Should

**Acceptance Criteria:**
- [ ] Parent can message their child's Wali Kelas
- [ ] Wali Kelas can message any parent in their class
- [ ] Chat-like interface with message history
- [ ] Real-time messaging via Laravel Reverb
- [ ] Unread message count visible
- [ ] Messages scoped: parent can only message Wali Kelas of their child
- [ ] Optional WhatsApp notification for new messages (configurable)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `conversations` migration (school_id, participant_ids, type, last_message_at) | Backend | 0.5h |
| 2 | Create `messages` migration (conversation_id, sender_id, body, read_at) | Backend | 0.5h |
| 3 | Create `MessageService` with send, read, list conversations | Backend | 1.5h |
| 4 | Create `MessageController` with conversation CRUD + message send/receive | Backend | 1h |
| 5 | Create Reverb channels for real-time message delivery | Backend | 1h |
| 6 | Create `Messaging/Conversations.vue` — conversation list | Frontend | 1.5h |
| 7 | Create `Messaging/Chat.vue` — chat interface with message bubbles | Frontend | 3h |
| 8 | Add new message notification (in-app + optional WhatsApp) | Backend | 1h |

---

## Technical Notes

- **WhatsApp provider abstraction**: `WhatsAppProvider` interface → `FonnteProvider` implementation. Rate limit: ~1000 messages/day on Fonnte basic plan.
- **Priority queues**: `notifications-high` for absence alerts + emergency, `notifications-low` for announcements + reminders.
- **Blast strategy**: `Bus::chain()` with delays between batches to avoid provider rate limits.
- **Message templates**: Stored in DB, editable by school admin. Templates use `{student_name}`, `{date}`, etc. placeholders.
- **Real-time**: Laravel Reverb for in-app notifications and private messaging (Phase 2).
- **Dead letter queue**: Failed WhatsApp messages stored in `whatsapp_message_logs` with status='failed'. Admin can retry manually.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| WhatsApp provider rate limit exceeded | Messages delayed | Rate limiting on queue, stagger by school, upgrade Fonnte plan |
| Provider downtime (Fonnte) | Messages not sent | Dead letter queue, 3 retries, admin manual retry UI |
| Message spam by parents | Overwhelms teachers | Rate limit per parent (e.g., 5 messages/day), report mechanism |
| Real-time messaging scalability | WebSocket overload | Reverb handles well for < 500 concurrent, scale later if needed |

---

## Definition of Done (Epic Level)

### Phase 1 (Sprint 4)
- [ ] Announcements CRUD with targeting and rich text
- [ ] Read receipts tracking percentage
- [ ] WhatsApp blast with rate limiting and progress tracking
- [ ] In-app notifications via Reverb (real-time)
- [ ] School calendar with events
- [ ] Emergency broadcast reaches all within minutes

### Phase 2 (Sprint 12)
- [ ] Parent-teacher private messaging functional
- [ ] Real-time chat via Reverb
- [ ] Message history preserved
- [ ] Appropriate access control (parent ↔ their child's Wali Kelas only)

---

### Related Files

- **Previous:** [`M09_FINANCE.md`](M09_FINANCE.md)
- **Next:** [`M11_PARENT_PORTAL.md`](M11_PARENT_PORTAL.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M10

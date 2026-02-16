# Scrum Workflow Overview — School Management SaaS

> **Version:** 1.0
> **Date:** 16 Februari 2026
> **Author:** Zulfikar Hidayatullah
> **Sprint Cadence:** 2-week sprints
> **Methodology:** Scrum with Kanban elements for ops/support

---

## Table of Contents

1. [Scrum Framework](#1-scrum-framework)
2. [Roles](#2-roles)
3. [Ceremonies](#3-ceremonies)
4. [Artifacts](#4-artifacts)
5. [Story Point Scale](#5-story-point-scale)
6. [Definition of Done (DoD)](#6-definition-of-done-dod)
7. [Priority System (MoSCoW)](#7-priority-system-moscow)
8. [Epic / Module Inventory](#8-epic--module-inventory)
9. [Sprint Roadmap](#9-sprint-roadmap)
10. [Module Dependency Map](#10-module-dependency-map)
11. [Velocity Tracking Template](#11-velocity-tracking-template)
12. [File Index](#12-file-index)

---

## 1. Scrum Framework

This project uses **Scrum** with **2-week sprints**. Each Epic corresponds to one Module (M1–M22) from the Feature Document. User Stories are derived from the feature rows within each module.

### Hierarchy

```
Release / Phase      → Phase 1 (MVP), Phase 2, Phase 3, Phase 4
  Epic               → Module (M1, M2, M3, ...)
    User Story       → Feature row (US-1.1, US-1.2, US-6.1, ...)
      Task           → Implementation work (backend, frontend, infra)
      Sub-task       → Granular unit (migration, API endpoint, component)
```

### Sprint Cadence

| Item | Duration |
|------|----------|
| Sprint length | 2 weeks (10 working days) |
| Sprint Planning | Monday morning, Sprint Day 1 (2 hours) |
| Daily Standup | Every day, 15 minutes |
| Sprint Review | Friday afternoon, Sprint Day 10 (1 hour) |
| Sprint Retrospective | Friday afternoon, Sprint Day 10 (1 hour) |
| Backlog Refinement | Wednesday, Sprint Day 8 (1 hour) |

---

## 2. Roles

| Role | Person | Responsibility |
|------|--------|----------------|
| **Product Owner** | Zulfikar Hidayatullah | Prioritize backlog, accept/reject stories, stakeholder communication |
| **Scrum Master** | TBD | Remove blockers, facilitate ceremonies, protect team capacity |
| **Development Team** | Full-stack developer(s) | Design, build, test, deploy all increments |

### RACI for Key Decisions

| Decision | PO | SM | Dev |
|----------|----|----|-----|
| Feature priority | **A** | C | I |
| Technical approach | C | I | **A** |
| Sprint scope | **A** | C | R |
| Architecture decisions | C | I | **A** |
| Release go/no-go | **A** | C | R |

> R = Responsible, A = Accountable, C = Consulted, I = Informed

---

## 3. Ceremonies

### Sprint Planning (Day 1 — 2 hours)

1. PO presents prioritized backlog items for the sprint
2. Team discusses, asks questions, clarifies acceptance criteria
3. Team estimates stories (if not already estimated in refinement)
4. Team commits to sprint backlog based on velocity
5. Tasks are created/assigned for each story

### Daily Standup (Daily — 15 min)

Each team member answers:
1. What did I complete yesterday?
2. What will I work on today?
3. Are there any blockers?

### Sprint Review / Demo (Day 10 — 1 hour)

1. Team demos completed stories to PO
2. PO accepts or rejects stories based on DoD
3. Feedback captured for backlog refinement
4. Release notes drafted for completed increment

### Sprint Retrospective (Day 10 — 1 hour)

1. What went well?
2. What didn't go well?
3. What can we improve?
4. Action items assigned with owners

### Backlog Refinement (Day 8 — 1 hour)

1. Review upcoming sprint's stories
2. Clarify acceptance criteria
3. Estimate story points
4. Break down large stories (> 8 SP)
5. Identify dependencies and risks

---

## 4. Artifacts

| Artifact | Location | Description |
|----------|----------|-------------|
| Product Backlog | This `docs/scrum/` directory | All epics and user stories |
| Sprint Backlog | Project board (GitHub Projects / Linear) | Stories committed for current sprint |
| Increment | `main` branch | Potentially shippable product at sprint end |
| Burndown Chart | Project board | Story points remaining vs. time |
| Feature Document | `docs/FEATURE_DOCUMENT.md` | Source of truth for feature details |
| Architecture Document | `docs/architecture.md` | Source of truth for technical decisions |

---

## 5. Story Point Scale

Fibonacci sequence. Points measure **complexity + uncertainty**, not hours.

| SP | Complexity | Example |
|----|-----------|---------|
| **1** | Trivial | Config change, copy update, simple UI tweak |
| **2** | Small | Simple CRUD endpoint, basic component, simple migration |
| **3** | Medium | CRUD with validation + form, component with state management |
| **5** | Large | Complex business logic, 3rd-party integration, bulk operations |
| **8** | Very Large | Payment gateway integration, PDF generation pipeline, complex algorithms |
| **13** | Epic-level | Full subsystem requiring spike/research, high uncertainty |

### Velocity Assumption

- **Solo developer:** ~20–25 SP per sprint
- **2-person team:** ~35–45 SP per sprint
- Adjust after Sprint 2 based on actual velocity

---

## 6. Definition of Done (DoD)

A User Story is "Done" when **ALL** of the following are true:

### Code Quality
- [ ] Code follows project conventions (backend skill + frontend skill)
- [ ] No PHPStan errors (level 6+)
- [ ] No TypeScript strict mode errors
- [ ] `yarn run lint` passes (functional impact fixes only)
- [ ] No `dd()`, `console.log()`, or debug artifacts left in code

### Testing
- [ ] Unit tests written for service methods with business logic
- [ ] Feature tests written for controller endpoints (happy path + key edge cases)
- [ ] Multi-tenancy isolation verified (School A cannot see School B data)
- [ ] Manual testing completed on target devices (mobile for parent features)

### Security
- [ ] `BelongsToSchool` trait used on all new tenant models
- [ ] Authorization checked (policy or middleware)
- [ ] Sensitive data encrypted where required (NIK, phone)
- [ ] No raw queries without explicit `school_id` WHERE clause

### Frontend
- [ ] Responsive design verified (mobile + desktop)
- [ ] Bahasa Indonesia used for all UI text
- [ ] Wayfinder routes used (not Ziggy `route()`)
- [ ] Loading states and error states handled
- [ ] Works on budget Android (Redmi 9-class) — no excessive animations

### Deployment
- [ ] Migrations are reversible (`down()` method present)
- [ ] No breaking changes to existing API contracts
- [ ] Environment variables documented if new ones added
- [ ] `yarn build` succeeds without errors

### Documentation
- [ ] Acceptance criteria from User Story are all met
- [ ] Technical decisions documented if deviating from architecture doc

---

## 7. Priority System (MoSCoW)

| Priority | Meaning | Sprint Impact |
|----------|---------|---------------|
| **Must** | Non-negotiable for the sprint/phase goal | Must be completed or sprint fails |
| **Should** | Important but sprint can succeed without it | Complete if capacity allows |
| **Could** | Nice-to-have enhancement | Only if all Must/Should are done |
| **Won't** | Explicitly excluded from this phase | Parked in backlog for future |

---

## 8. Epic / Module Inventory

| Epic ID | Epic Name | Phase | Priority | Est. SP | Sprint Target |
|---------|-----------|-------|----------|---------|---------------|
| SPRINT-0 | Infrastructure & Project Skeleton | Phase 0 | **CORE** | 24 | Sprint 0 |
| M1 | School Profile & Multi-Tenancy | Phase 1 | **CORE** | 21 | Sprint 1 |
| M2 | User & Role Management | Phase 1 | **CORE** | 34 | Sprint 1–2 |
| M3 | Student Information System | Phase 1 | **CORE** | 24 | Sprint 3 |
| M5 | Class & Schedule Management | Phase 1+2 | **CORE** | 26 | Sprint 3 (basic), 10 (full) |
| M6 | Attendance System | Phase 1 | **CORE** | 26 | Sprint 4 |
| M9 | Finance — SPP & Fees | Phase 1 | **CORE** | 42 | Sprint 5–6 |
| M10 | Communication | Phase 1+2 | **CORE** | 26 | Sprint 4 (basic), 12 (enhanced) |
| M11 | Parent Portal | Phase 1 | **CORE** | 24 | Sprint 6 |
| M4 | Teacher & Staff Management | Phase 2 | **CORE** | 16 | Sprint 8 |
| M7 | Grading & Assessment | Phase 2 | **CORE** | 34 | Sprint 9–10 |
| M8 | Report Card (E-Rapor) | Phase 2 | **CORE** | 37 | Sprint 11–12 |
| M12 | Online Exam | Phase 3 | Important | 34 | Sprint 14–15 |
| M13 | BOS Fund Management | Phase 3 | Important | 21 | Sprint 15 |
| M14 | Document & Letter Management | Phase 3 | Important | 18 | Sprint 16 |
| M15 | Library | Phase 3 | Important | 21 | Sprint 16 |
| M16 | Counseling / BK | Phase 3 | Important | 21 | Sprint 17 |
| M17 | Extracurricular | Phase 3 | Important | 16 | Sprint 17 |
| M18 | PPDB Online | Phase 4 | Nice-to-Have | 24 | Sprint 20 |
| M19 | Inventory & Facilities | Phase 4 | Nice-to-Have | 18 | Sprint 21 |
| M20 | Analytics Dashboard | Phase 4 | Nice-to-Have | 26 | Sprint 22 |
| M21 | Government Compliance (Dapodik Export) | Phase 2+4 | **CORE** (basic) / Nice-to-Have (full) | 8+8 | Sprint 13 (basic), 23 (full) |
| M22 | Additional Features | Phase 4+ | Nice-to-Have | TBD | Sprint 24–25 |

---

## 9. Sprint Roadmap

### Phase 1 — MVP (Sprint 0–7, ~4 months)

| Sprint | Duration | Epics | Goal |
|--------|----------|-------|------|
| **Sprint 0** | Week 1–2 | Infrastructure | Docker, Laravel 12, Vue 3, Inertia, Tailwind, CI/CD pipeline, DataTable component |
| **Sprint 1** | Week 3–4 | M1 + M2 (start) | Multi-tenancy foundation, auth system (email + password) |
| **Sprint 2** | Week 5–6 | M2 (complete) | WhatsApp OTP, bulk import, role/permission, multi-role switching |
| **Sprint 3** | Week 7–8 | M3 + M5 (basic) | Student CRUD + Excel import, Rombel management |
| **Sprint 4** | Week 9–10 | M6 + M10 (basic) | Attendance + WhatsApp parent notification, announcements |
| **Sprint 5** | Week 11–12 | M9 (part 1) | Fee config, payment gateway (Midtrans), VA + QRIS |
| **Sprint 6** | Week 13–14 | M9 (complete) + M11 | Auto-reconciliation, parent portal (attendance + payment) |
| **Sprint 7** | Week 15–16 | Dashboards + Polish | Basic role dashboards (Admin, Teacher, Parent landing pages with key daily metrics), monitoring setup (Sentry + UptimeRobot), bug fixes, integration testing, pilot school onboarding |

### Phase 2 — Academic Excellence (Sprint 8–13, ~3 months)

| Sprint | Duration | Epics | Goal |
|--------|----------|-------|------|
| **Sprint 8** | Week 17–18 | M4 | Teacher profiles, teaching load, staff attendance |
| **Sprint 9** | Week 19–20 | M7 (part 1) | Kurikulum Merdeka structure, assessment types, grade input |
| **Sprint 10** | Week 21–22 | M7 (complete) + M5 (full) | Remedial, analytics, auto-schedule, conflict detection |
| **Sprint 11** | Week 23–24 | M8 (part 1) | Rapor template, data aggregation, PDF generation pipeline |
| **Sprint 12** | Week 25–26 | M8 (complete) + M10 (enhanced) | Level-specific rapor, parent-teacher messaging |
| **Sprint 13** | Week 27–28 | M21 (basic) + Polish | Basic Dapodik export (student + teacher data), Phase 2 integration testing, parent portal grades + rapor |

### Phase 3 — Complete Operations (Sprint 14–19, ~3 months)

| Sprint | Duration | Epics | Goal |
|--------|----------|-------|------|
| **Sprint 14** | Week 29–30 | M12 (part 1) | Question bank, exam creation, timer + auto-submit |
| **Sprint 15** | Week 31–32 | M12 (complete) + M13 | Anti-cheat, analytics, BOS fund RKAS + expense tracking |
| **Sprint 16** | Week 33–34 | M14 + M15 | Document/letter management, library catalog + borrowing |
| **Sprint 17** | Week 35–36 | M16 + M17 | Counseling/BK records, extracurricular management |
| **Sprint 18** | Week 37–38 | Integration | Cross-module integration, rapor data feeds |
| **Sprint 19** | Week 39–40 | Stabilization | Phase 3 testing, performance optimization |

### Phase 4 — Scale & Differentiate (Sprint 20–25, ~3 months)

| Sprint | Duration | Epics | Goal |
|--------|----------|-------|------|
| **Sprint 20** | Week 41–42 | M18 | PPDB online registration + selection + announcement |
| **Sprint 21** | Week 43–44 | M19 | Inventory registry, room management, maintenance |
| **Sprint 22** | Week 45–46 | M20 | Analytics dashboard, KPIs, trend charts |
| **Sprint 23** | Week 47–48 | M21 (full) | EMIS export (Madrasah), accreditation prep, government report formats |
| **Sprint 24** | Week 49–50 | M22 | WhatsApp bot, QR student ID, additional features |
| **Sprint 25** | Week 51–52 | Final | Platform-wide polish, documentation, scaling prep |

---

## 10. Module Dependency Map

```
M1 (School Profile) ──────────────────────┐
                                           │
M2 (Users & Roles) ───────────────────────┤
                                           │
M3 (Student Data) ────────────────────────┤── Foundation (required by all)
                                           │
M5 (Class/Schedule) ──────────────────────┘

M6 (Attendance) ──── requires M3, M5 ────── feeds into ──► M8 (Rapor)

M7 (Grading) ──── requires M3, M5 ────────── feeds into ──► M8 (Rapor)

M8 (Rapor) ──── requires M6, M7, M17 ────── viewed in ──► M11 (Parent Portal)

M9 (Finance) ──── requires M3 ────────────── viewed in ──► M11 (Parent Portal)

M10 (Communication) ──── requires M2 ─────── viewed in ──► M11 (Parent Portal)

M11 (Parent Portal) ──── aggregates M6, M9, M10

M12 (Exams) ──── requires M3, M5, M7
M13 (BOS) ──── standalone (requires M1)
M14 (Documents) ──── standalone (requires M1, M2)
M15 (Library) ──── requires M3
M16 (BK) ──── requires M3
M17 (Ekskul) ──── requires M3 ──── feeds into ──► M8 (Rapor)
M18 (PPDB) ──── standalone (feeds into M3)
M19 (Inventory) ──── standalone (requires M1)
M20 (Analytics) ──── requires M6, M7, M9
M21 (Gov Compliance) ──── requires M3, M4
```

---

## 11. Velocity Tracking Template

Track after each sprint to improve estimation accuracy.

| Sprint | Planned SP | Completed SP | Velocity | Carryover Stories | Notes |
|--------|-----------|-------------|----------|-------------------|-------|
| Sprint 0 | — | — | — | — | — |
| Sprint 1 | — | — | — | — | — |
| Sprint 2 | — | — | — | — | — |
| Sprint 3 | — | — | — | — | — |
| ... | — | — | — | — | — |

### Velocity Rules

- Use **average of last 3 sprints** for planning
- If velocity drops > 20%, investigate in retro
- If a story carries over 2 sprints, break it down further
- Sprint 0–2 are "calibration sprints" — expect lower velocity

---

## 12. File Index

Each Epic has its own detailed file with User Stories, tasks, and acceptance criteria:

| File | Epic | Phase |
|------|------|-------|
| [`00_SPRINT_0_INFRASTRUCTURE.md`](00_SPRINT_0_INFRASTRUCTURE.md) | Project Skeleton & Infrastructure | Phase 0 |
| [`M01_SCHOOL_PROFILE.md`](M01_SCHOOL_PROFILE.md) | School Profile & Multi-Tenancy | Phase 1 |
| [`M02_USER_ROLE_MANAGEMENT.md`](M02_USER_ROLE_MANAGEMENT.md) | User & Role Management | Phase 1 |
| [`M03_STUDENT_INFORMATION.md`](M03_STUDENT_INFORMATION.md) | Student Information System | Phase 1 |
| [`M04_TEACHER_STAFF.md`](M04_TEACHER_STAFF.md) | Teacher & Staff Management | Phase 2 |
| [`M05_CLASS_SCHEDULE.md`](M05_CLASS_SCHEDULE.md) | Class & Schedule Management | Phase 1+2 |
| [`M06_ATTENDANCE.md`](M06_ATTENDANCE.md) | Attendance System | Phase 1 |
| [`M07_GRADING_ASSESSMENT.md`](M07_GRADING_ASSESSMENT.md) | Grading & Assessment | Phase 2 |
| [`M08_RAPOR.md`](M08_RAPOR.md) | Report Card (E-Rapor) | Phase 2 |
| [`M09_FINANCE.md`](M09_FINANCE.md) | Finance — SPP & Fees | Phase 1 |
| [`M10_COMMUNICATION.md`](M10_COMMUNICATION.md) | Communication | Phase 1+2 |
| [`M11_PARENT_PORTAL.md`](M11_PARENT_PORTAL.md) | Parent Portal | Phase 1 |
| [`M12_ONLINE_EXAM.md`](M12_ONLINE_EXAM.md) | Online Exam | Phase 3 |
| [`M13_BOS_FUND.md`](M13_BOS_FUND.md) | BOS Fund Management | Phase 3 |
| [`M14_DOCUMENT_LETTER.md`](M14_DOCUMENT_LETTER.md) | Document & Letter Management | Phase 3 |
| [`M15_LIBRARY.md`](M15_LIBRARY.md) | Library | Phase 3 |
| [`M16_COUNSELING_BK.md`](M16_COUNSELING_BK.md) | Counseling / BK | Phase 3 |
| [`M17_EXTRACURRICULAR.md`](M17_EXTRACURRICULAR.md) | Extracurricular | Phase 3 |
| [`M18_PPDB.md`](M18_PPDB.md) | PPDB Online | Phase 4 |
| [`M19_INVENTORY.md`](M19_INVENTORY.md) | Inventory & Facilities | Phase 4 |
| [`M20_ANALYTICS.md`](M20_ANALYTICS.md) | Analytics Dashboard | Phase 4 |
| [`M21_GOV_COMPLIANCE.md`](M21_GOV_COMPLIANCE.md) | Government Compliance (Dapodik Export) | Phase 2+4 |
| [`M22_ADDITIONAL_FEATURES.md`](M22_ADDITIONAL_FEATURES.md) | Additional Features | Phase 4+ |

---

### Related Documents

- **Feature Document:** [`../FEATURE_DOCUMENT.md`](../FEATURE_DOCUMENT.md)
- **Architecture Document:** [`../architecture.md`](../architecture.md)

---

*End of Scrum Overview*

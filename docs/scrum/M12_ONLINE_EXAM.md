# Epic M12 — Online Exam (Ujian Online)

> **Epic ID:** M12
> **Phase:** 3 (Complete School Operations)
> **Priority:** Important
> **Sprint Target:** Sprint 14–15
> **Total Story Points:** 34 SP
> **Dependencies:** M3 (Students), M5 (Class/Schedule), M7 (Grading)
> **Blocks:** —

---

## Epic Overview

Digital examination system with question banks, multiple question types, randomization, auto-grading for objective questions, timer with auto-submit, and basic anti-cheat measures. Exam results feed into the grading system (M7).

---

## User Stories

### US-12.1: Question Types

**As a** Teacher,
**I want** to create questions of various types (Pilihan Ganda, Essay, Isian Singkat, Menjodohkan, Benar/Salah),
**so that** I can build comprehensive exams.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Pilihan Ganda (multiple choice) — 4–5 options, one correct
- [ ] Essay — free-text answer with max word count
- [ ] Isian Singkat (short answer) — text input with accepted answer list
- [ ] Menjodohkan (matching) — pair items from two columns
- [ ] Benar/Salah (true/false)
- [ ] Each question has: content (rich text), point value, difficulty level
- [ ] Image support in question content and options

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `questions` migration (school_id, subject_id, type enum, content, options JSONB, answer JSONB, points, difficulty, learning_objective_id) | Backend | 0.5h |
| 2 | Create `QuestionType` enum and `DifficultyLevel` enum | Backend | 0.5h |
| 3 | Create `Question` model with `BelongsToSchool` | Backend | 0.5h |
| 4 | Create `QuestionService` CRUD with type-specific validation | Backend | 1.5h |
| 5 | Create question editor forms per type (MC, essay, short answer, matching, T/F) | Frontend | 4h |
| 6 | Create rich text editor for question content (with image upload) | Frontend | 1.5h |
| 7 | Create question preview component | Frontend | 1h |

---

### US-12.2: Question Bank

**As a** Teacher,
**I want** to store questions tagged by subject, TP, and difficulty,
**so that** I can reuse questions across exams.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Questions stored in bank per subject
- [ ] Filterable by: subject, TP (learning objective), difficulty, question type
- [ ] Search by question content
- [ ] Import questions from Excel template
- [ ] Question count per filter shown

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `Exam/QuestionBank/Index.vue` with filterable DataTable | Frontend | 2h |
| 2 | Create question filter sidebar (subject, TP, difficulty, type) | Frontend | 1h |
| 3 | Create Excel import for bulk question upload | Backend | 1.5h |

---

### US-12.3: Exam Creation with Randomization

**As a** Teacher,
**I want** to create an exam by selecting questions from the bank with random order per student,
**so that** each student gets a different arrangement to reduce cheating.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Create exam: name, subject, class, duration, start/end window
- [ ] Select questions manually or auto-generate from bank (by filters)
- [ ] Question order randomized per student
- [ ] Answer option order randomized for MC questions
- [ ] Random seed stored per student for reproducibility
- [ ] Preview exam as student would see it

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `exams` migration (school_id, subject_id, class_group_id, name, duration_minutes, start_at, end_at, settings JSONB) | Backend | 0.5h |
| 2 | Create `exam_questions` pivot (exam_id, question_id, order) | Backend | 0.5h |
| 3 | Create `exam_attempts` migration (exam_id, student_id, started_at, submitted_at, score, answers JSONB, random_seed) | Backend | 0.5h |
| 4 | Create `ExamService` with creation, question selection, randomization | Backend | 2h |
| 5 | Create `Exam/Create.vue` — exam setup + question selection from bank | Frontend | 3h |
| 6 | Create exam preview mode | Frontend | 1h |

---

### US-12.4: Timer & Auto-Submit

**As the** System,
**I want** to enforce a time limit with auto-submit when time expires,
**so that** exam duration is fair for all students.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Countdown timer visible throughout exam
- [ ] Warning at 5 minutes and 1 minute remaining
- [ ] Auto-submit when timer reaches zero
- [ ] Timer synced with server (not client-only)
- [ ] Resume capability if student loses connection (time keeps running)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create server-side timer tracking (started_at + duration = deadline) | Backend | 1h |
| 2 | Create auto-submit endpoint triggered by client or server fallback | Backend | 0.5h |
| 3 | Create `ExamTimer.vue` component with countdown and warnings | Frontend | 1.5h |
| 4 | Implement server-synced timer (prevent client manipulation) | Frontend | 1h |

---

### US-12.5: Anti-Cheat Measures

**As the** System,
**I want** to detect and log potential cheating behavior,
**so that** exam integrity is maintained.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Fullscreen enforcement (exam runs in fullscreen mode)
- [ ] Tab-switch/window-blur detection — logged with timestamp
- [ ] Number of violations shown to teacher
- [ ] Warning shown to student when violation detected
- [ ] Teacher can review violation log per student

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `exam_violations` migration (attempt_id, type, timestamp, details) | Backend | 0.5h |
| 2 | Create violation logging endpoint | Backend | 0.5h |
| 3 | Implement fullscreen mode with exit detection | Frontend | 1.5h |
| 4 | Implement tab-switch/blur detection and logging | Frontend | 1h |
| 5 | Create violation review page for teachers | Frontend | 1h |

---

### US-12.6: Auto-Grading (Objective Questions)

**As the** System,
**I want** to automatically grade objective questions (MC, short answer, T/F, matching),
**so that** teachers only need to manually grade essay questions.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] MC, T/F, matching auto-graded instantly on submit
- [ ] Short answer graded against accepted answer list (case-insensitive)
- [ ] Total score calculated from auto-graded questions
- [ ] Essay questions marked as "pending manual review"
- [ ] Results available immediately for objective-only exams

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `AutoGraderService` with grading logic per question type | Backend | 2h |
| 2 | Create score calculation after submit | Backend | 0.5h |
| 3 | Create student result page (auto-graded scores visible immediately) | Frontend | 1h |

---

### US-12.7: Manual Essay Grading

**As a** Teacher,
**I want** to grade essay questions with a rubric and feedback,
**so that** subjective answers are evaluated fairly.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Teacher sees list of ungraded essay answers per exam
- [ ] Student's answer displayed alongside the question and rubric
- [ ] Score input with optional feedback/comment
- [ ] Mark as graded → updates total score
- [ ] Progress: "15 of 30 essays graded"

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create essay grading queue endpoint (ungraded answers per exam) | Backend | 0.5h |
| 2 | Create `Exam/GradeEssay.vue` — answer + rubric + score input | Frontend | 2h |
| 3 | Create grading progress indicator | Frontend | 0.5h |
| 4 | Update total score when essay graded | Backend | 0.5h |

---

### US-12.8: Exam Analytics

**As a** Teacher,
**I want** to see exam analytics (score distribution, item difficulty, discrimination index),
**so that** I can improve my questions and identify struggling students.

**Story Points:** 5
**Priority:** Should

**Acceptance Criteria:**
- [ ] Score distribution histogram
- [ ] Average, median, highest, lowest scores
- [ ] Item difficulty index per question (% correct)
- [ ] Item discrimination index
- [ ] List of students below passing score
- [ ] Export results to Excel

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `ExamAnalyticsService` with score distribution, item analysis | Backend | 2h |
| 2 | Create analytics API endpoints | Backend | 0.5h |
| 3 | Create `Exam/Analytics.vue` with Chart.js charts | Frontend | 2.5h |
| 4 | Create Excel export for exam results | Backend | 1h |

---

### US-12.9: Practice Mode

**As a** Student,
**I want** to take practice exams,
**so that** I can prepare for the actual exam.

**Story Points:** 2
**Priority:** Could

**Acceptance Criteria:**
- [ ] Teacher can mark an exam as "practice" (unlimited attempts, no time limit option)
- [ ] Students can retry practice exams
- [ ] Correct answers shown after submission (configurable by teacher)
- [ ] Practice scores not counted in grades

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Add `is_practice` flag to exams with unlimited attempts logic | Backend | 0.5h |
| 2 | Create practice mode UI with answer reveal after submit | Frontend | 1.5h |

---

## Technical Notes

- **Exam attempt JSONB**: Student answers stored as JSONB `{question_id: answer_value}` in `exam_attempts` — efficient for both storage and grading.
- **Randomization**: Seeded random (stored `random_seed` per attempt) ensures consistent question order if student resumes.
- **Timer integrity**: Server tracks `started_at` + `duration_minutes`. Client timer is visual only. Final validation on server at submit time.
- **Anti-cheat is deterrent-level**, not enterprise-level proctoring. Appropriate for school context.
- **Exam results** can feed into M7 grading as an assessment type.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Student loses internet mid-exam | Lost answers | Auto-save answers every 60s to server, resume from last save |
| Client-side timer manipulation | Unfair extra time | Server-side deadline enforcement, reject submissions past deadline |
| Rich text editor bloats bundle | Slow on low-end devices | Lightweight editor (e.g., tiptap), lazy-load exam page |
| Anti-cheat false positives | Student frustration | Log violations but don't auto-penalize, teacher reviews |

---

## Definition of Done (Epic Level)

- [ ] All 5 question types can be created and used
- [ ] Question bank with filtering and search
- [ ] Randomized exam creation with timer
- [ ] Auto-grading for objective questions
- [ ] Manual essay grading interface
- [ ] Basic anti-cheat (fullscreen + tab detection)
- [ ] Exam analytics with charts and export
- [ ] Practice mode available

---

### Related Files

- **Previous:** [`M11_PARENT_PORTAL.md`](M11_PARENT_PORTAL.md)
- **Next:** [`M13_BOS_FUND.md`](M13_BOS_FUND.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M12

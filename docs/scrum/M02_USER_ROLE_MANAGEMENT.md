# Epic M2 — User & Role Management

> **Epic ID:** M2
> **Phase:** 1 (MVP)
> **Priority:** CORE
> **Sprint Target:** Sprint 1–2
> **Total Story Points:** 34 SP
> **Dependencies:** M1 (School Profile & Multi-Tenancy)
> **Blocks:** All modules that require authenticated users

---

## Epic Overview

Handle authentication, authorization, and user lifecycle for all platform personas. Three distinct auth flows (email+password for staff, WhatsApp OTP for parents, NISN+password for students), role-based access control via `spatie/laravel-permission`, and multi-role switching for users who hold more than one role (e.g., a teacher who is also a parent).

---

## User Stories

### US-2.1: Role System Setup

**As an** Admin,
**I want** predefined roles (SuperAdmin, Admin Sekolah, Kepala Sekolah, Guru, Wali Kelas, Bendahara, Orang Tua, Siswa, Guru BK),
**so that** each user type has appropriate access to the platform.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] All 9 roles are seeded via database seeder
- [ ] Roles are scoped per school (except SuperAdmin which is global)
- [ ] Roles can be assigned to users by Admin
- [ ] Roles are visible and manageable in the admin UI
- [ ] Role assignment is logged in activity log

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Install & configure `spatie/laravel-permission` with team (school) support | Backend | 1h |
| 2 | Create `RoleSeeder` with all 9 roles and default permissions | Backend | 1h |
| 3 | Create `UserRole` enum mirroring the roles | Backend | 0.5h |
| 4 | Create admin UI for viewing roles and their permissions | Frontend | 1.5h |

---

### US-2.2: Permission System

**As an** Admin,
**I want** fine-grained permissions per role that restrict both UI elements and API access,
**so that** users can only see and do what their role allows.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Permissions follow `{module}.{action}` format (e.g., `students.view`, `attendance.mark`)
- [ ] All permissions seeded with sensible defaults per role
- [ ] Permissions enforced on backend (middleware + policies)
- [ ] Permissions enforced on frontend (conditional rendering via `usePermission` composable)
- [ ] Admin can customize permissions per role (within their school)
- [ ] Permission changes take effect immediately (no re-login required)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Define complete permission list per module (seeder) | Backend | 1.5h |
| 2 | Map default permissions to each role | Backend | 1h |
| 3 | Create authorization policies for each domain (StudentPolicy, AttendancePolicy, etc.) | Backend | 2h |
| 4 | Create `CheckPermission` middleware | Backend | 0.5h |
| 5 | Share user permissions via Inertia shared data | Backend | 0.5h |
| 6 | Create `usePermission` composable (`can('students.view')`) | Frontend | 1h |
| 7 | Create permission management UI (role → permissions matrix) | Frontend | 2h |

---

### US-2.3: Parent-Student Linking

**As an** Admin,
**I want** to link parent accounts to one or more student records,
**so that** parents can view their children's data in the portal.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Admin can link a parent to one or more students
- [ ] Parent can have multiple children linked
- [ ] One student can have multiple parents/guardians linked
- [ ] Relationship type specified (Father, Mother, Guardian)
- [ ] Parent data access enforced at query level: `Student::whereHas('parents', ...)`
- [ ] Unlinking removes portal access for that child

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `parent_student` pivot migration (parent_user_id, student_id, relationship_type) | Backend | 0.5h |
| 2 | Create `RelationshipType` enum (father, mother, guardian) | Backend | 0.5h |
| 3 | Add `parents()` and `children()` relationships on User and Student models | Backend | 0.5h |
| 4 | Create `ParentStudentController` for linking/unlinking | Backend | 1h |
| 5 | Create UI for managing parent-student links (on student detail page) | Frontend | 1.5h |
| 6 | Write feature test verifying parent can only access their linked children | Backend | 1h |

---

### US-2.4: Teacher-Subject-Class Assignment

**As an** Admin,
**I want** to assign teachers to subjects (mapel) and classes (kelas),
**so that** teachers can only access attendance and grades for their assigned classes.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Admin can assign a teacher to one or more subjects
- [ ] Admin can assign a teacher to one or more classes per subject
- [ ] Assignment is per academic year (resets each year)
- [ ] Teacher can only see/modify data for assigned classes
- [ ] Assignment visible in teacher's profile

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `teacher_subject_class` pivot migration (user_id, subject_id, class_group_id, academic_year_id) | Backend | 0.5h |
| 2 | Create relationships on User, Subject, ClassGroup models | Backend | 0.5h |
| 3 | Create `TeacherAssignmentService` | Backend | 1h |
| 4 | Create assignment management UI (admin assigns teachers to subjects+classes) | Frontend | 2h |
| 5 | Enforce teacher scope in attendance/grade queries | Backend | 1h |

---

### US-2.5: Bulk User Import

**As an** Admin,
**I want** to import users (teachers, parents, students) from an Excel/CSV file,
**so that** I can onboard hundreds of users without manual entry.

**Story Points:** 8
**Priority:** Must

**Acceptance Criteria:**
- [ ] Admin uploads Excel file with user data
- [ ] System validates all rows and shows preview with errors highlighted
- [ ] Admin confirms import after reviewing preview
- [ ] Processing handles 500+ users without timeout (queued via `ShouldQueue`)
- [ ] Progress tracking visible during import (via cache key + polling)
- [ ] Downloadable template provided for each user type
- [ ] Duplicate detection (by email, phone, or NISN)
- [ ] Error report downloadable for rows that failed

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create import templates (Excel) for teachers, parents, students | Backend | 1h |
| 2 | Create `UserImport` class using `maatwebsite/laravel-excel` with chunked processing | Backend | 3h |
| 3 | Implement validation rules per user type (email format, phone format, NISN format) | Backend | 1.5h |
| 4 | Implement preview mode (validate without saving) | Backend | 1.5h |
| 5 | Implement queued processing with progress tracking via cache key | Backend | 2h |
| 6 | Create reusable `Components/Forms/ExcelImporter.vue` — shared file upload + preview table + confirm component (reused by M3 Student Import and future modules) | Frontend | 3h |
| 7 | Create progress indicator polling (check cache key every 2s) | Frontend | 1h |
| 8 | Create error report download (failed rows as Excel) | Backend | 1h |

---

### US-2.6: Admin/Teacher Authentication (Email + Password)

**As an** Admin or Teacher,
**I want** to log in using email and password with optional TOTP 2FA,
**so that** my account is secured with standard credentials.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Login form accepts email + password
- [ ] Session duration: 8 hours
- [ ] Optional TOTP 2FA setup (QR code + backup codes)
- [ ] 2FA verification on each login if enabled
- [ ] Rate limiting: 5 attempts per minute, lockout after 10 failed attempts
- [ ] Password reset via email
- [ ] "Forgot password" flow works

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `users` migration with auth fields (email, password, phone, two_factor_secret, etc.) | Backend | 1h |
| 2 | Create `LoginController` with email+password auth | Backend | 1h |
| 3 | Implement TOTP 2FA setup and verification (using `laravel/fortify` or manual) | Backend | 2h |
| 4 | Configure rate limiting (5/min login, lockout) | Backend | 0.5h |
| 5 | Create password reset flow | Backend | 1h |
| 6 | Create `Auth/Login.vue` with email+password form | Frontend | 1.5h |
| 7 | Create `Auth/TwoFactor.vue` for 2FA code entry | Frontend | 1h |
| 8 | Create 2FA setup UI (QR code display + backup codes) | Frontend | 1h |

---

### US-2.7: Parent Authentication (WhatsApp OTP)

**As a** Parent,
**I want** to log in using my phone number and a 6-digit OTP sent via WhatsApp,
**so that** I don't need an email or to remember a complex password.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Login form accepts phone number (Indonesian format: 08xx or +628xx)
- [ ] System sends 6-digit OTP via WhatsApp (Fonnte API)
- [ ] OTP expires after 5 minutes
- [ ] Rate limiting: 3 OTP requests per 5 minutes
- [ ] Session duration: 30 days with "remember me"
- [ ] Parent is auto-linked to their children after first login (if phone matches)
- [ ] Graceful handling if WhatsApp delivery fails (show retry option)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `WhatsAppProvider` interface with `sendOtp()` method | Backend | 0.5h |
| 2 | Create `FonnteProvider` implementation | Backend | 2h |
| 3 | Create `OtpService` (generate, store, verify, expire) | Backend | 1.5h |
| 4 | Create `OtpLoginController` (requestOtp, verifyOtp) | Backend | 1h |
| 5 | Configure OTP rate limiting (3 per 5 min) and expiry (5 min) | Backend | 0.5h |
| 6 | Create `Auth/OtpLogin.vue` — phone input → OTP input flow | Frontend | 2h |
| 7 | Handle delivery failure UX (retry button, countdown timer) | Frontend | 1h |
| 8 | Write feature test mocking Fonnte API | Backend | 1h |

---

### US-2.8: Student Authentication (NISN + Password)

**As a** Student,
**I want** to log in using my NISN and a password set by the school admin,
**so that** I can access my schedule and grades.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Login form accepts NISN + password
- [ ] Session duration: 8 hours
- [ ] Password is set by admin during student creation (or bulk import)
- [ ] Student can change their own password after first login
- [ ] NISN validated as 10-digit number

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `StudentLoginController` with NISN-based lookup | Backend | 1h |
| 2 | Add default password generation to student creation flow | Backend | 0.5h |
| 3 | Create password change endpoint for students | Backend | 0.5h |
| 4 | Create `Auth/StudentLogin.vue` — NISN + password form | Frontend | 1h |
| 5 | Create first-login password change prompt | Frontend | 0.5h |

---

### US-2.9: Multi-Role Switching

**As a** user with multiple roles (e.g., a teacher who is also a parent),
**I want** to switch between my roles without logging out,
**so that** I can access different features under each role.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Role switcher UI visible in topbar when user has 2+ roles
- [ ] Switching role changes the layout (e.g., AdminLayout → ParentLayout)
- [ ] Active role stored in session (persists across page loads)
- [ ] Permissions and UI adapt immediately to the switched role
- [ ] User can switch back without re-authentication

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Add `active_role` to session data | Backend | 0.5h |
| 2 | Create `SwitchRoleController` | Backend | 0.5h |
| 3 | Share active role + available roles via Inertia shared data | Backend | 0.5h |
| 4 | Create `RoleSwitcher.vue` dropdown component | Frontend | 1.5h |
| 5 | Implement layout switching based on active role | Frontend | 1h |
| 6 | Write feature test: teacher-parent switching preserves auth | Backend | 0.5h |

---

### US-2.10: User Profile Management

**As a** user,
**I want** to edit my profile, change my password, and upload a photo,
**so that** my account information is up to date.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] User can view their profile details
- [ ] User can update name, email (staff), phone (parent)
- [ ] User can change their password
- [ ] User can upload/change profile photo (auto-resized to 200px, 80px avatar)
- [ ] Changes are logged in activity log

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `ProfileController` with `show()`, `update()`, `updatePassword()` | Backend | 1h |
| 2 | Create `UpdateProfileRequest` with role-specific validation | Backend | 0.5h |
| 3 | Configure `spatie/media-library` for profile photo (200px, 80px conversions) | Backend | 0.5h |
| 4 | Create `Profile/Edit.vue` — profile form with photo upload | Frontend | 2h |
| 5 | Create `Profile/ChangePassword.vue` | Frontend | 0.5h |

---

## Technical Notes

- **`spatie/laravel-permission`** is configured with team support — `school_id` acts as the team, so roles/permissions are scoped per school.
- **WhatsApp OTP** uses the `WhatsAppProvider` interface → `FonnteProvider` implementation. Abstracting now allows swapping to Wablas or another provider later without code changes.
- **Bulk import** uses `maatwebsite/laravel-excel` with `ShouldQueue` for large files. Progress tracked via `Cache::put("import:{$importId}:progress", $percentage)` polled by frontend.
- **Permission format**: `{module}.{action}` — e.g., `students.view`, `students.create`, `attendance.mark`, `payments.create`.
- **Phone number normalization**: Convert all Indonesian formats (08xx, +628xx, 628xx) to consistent `+628xx` before storage.
- **`ExcelImporter.vue`**: Built as a shared component in `Components/Forms/` during this epic. It handles file upload, validation preview, confirmation, progress tracking, and error report download. Reused by M3 (Student Import), M4 (Teacher Import), and any future bulk import feature.

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| WhatsApp OTP delivery fails (Fonnte down) | Parents can't log in | Dead letter queue, retry with exponential backoff, admin manual OTP fallback |
| Bulk import with 1000+ rows times out | Admin frustrated, incomplete import | Queued processing (`ShouldQueue`), progress bar, chunked reads |
| Permission misconfiguration | User sees data they shouldn't | Default permissions are restrictive; CI test verifies policy coverage |
| Multi-role switching edge cases | Session corruption, wrong layout | Thorough feature testing, clear active role in session |

---

## Definition of Done (Epic Level)

- [ ] All 9 roles created and assignable
- [ ] Email+password login works for admin/teacher (with optional 2FA)
- [ ] WhatsApp OTP login works for parents
- [ ] NISN+password login works for students
- [ ] Permissions restrict both API and UI access
- [ ] Bulk import processes 500+ users from Excel
- [ ] Multi-role switching changes layout and permissions
- [ ] Parent-student linking enforced at query level
- [ ] Feature tests cover all auth flows and permission checks
- [ ] Rate limiting active on all login endpoints

---

### Related Files

- **Previous:** [`M01_SCHOOL_PROFILE.md`](M01_SCHOOL_PROFILE.md)
- **Next:** [`M03_STUDENT_INFORMATION.md`](M03_STUDENT_INFORMATION.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M2

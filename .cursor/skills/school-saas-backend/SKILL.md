---
name: school-saas-backend
description: School SaaS backend conventions — Laravel service pattern, multi-tenancy with BelongsToSchool trait, database schema, enums, directory structure. Use when creating controllers, services, models, migrations, or any backend PHP code for the school platform.
---

# School SaaS Backend Conventions

## Directory Structure

Domain-organized, pragmatic service pattern. Controllers are thin, services hold business logic.

```
app/
├── Http/
│   ├── Controllers/          # Thin: validate, authorize, delegate, return Inertia
│   │   ├── Auth/             # Login, OTP, Register
│   │   ├── Dashboard/
│   │   ├── Student/
│   │   ├── Attendance/
│   │   ├── Finance/          # Fee, Payment, PaymentWebhook
│   │   ├── Communication/    # Announcement, WhatsApp
│   │   ├── Academic/         # ClassGroup, Subject, Schedule
│   │   ├── Grading/          # Assessment, Grade
│   │   ├── Rapor/
│   │   ├── Parent/
│   │   └── SuperAdmin/       # School, Subscription
│   ├── Middleware/            # EnsureSchoolActive, CheckSubscription, TrackLastActive
│   └── Requests/             # Form requests organized by domain
├── Models/
│   └── Concerns/             # BelongsToSchool, HasAuditTrail, Searchable
├── Services/                 # Business logic
│   └── Integrations/         # Payment/, WhatsApp/ (interface + providers)
├── Enums/                    # PHP Backed Enums (string values)
├── Jobs/                     # GenerateRaporPdf, SendWhatsAppMessage, etc.
├── Events/                   # StudentMarkedAbsent, PaymentReceived, etc.
├── Listeners/                # NotifyParentOfAbsence, SendPaymentReceipt, etc.
├── Policies/                 # Authorization per domain
├── Notifications/
│   └── Channels/WhatsAppChannel.php
└── Observers/                # PaymentObserver, StudentObserver
```

## Controller-Service Pattern

Controllers: validate -> authorize -> delegate to service -> return Inertia response.

```php
class AttendanceController extends Controller
{
    public function __construct(private AttendanceService $attendanceService) {}

    public function store(MarkAttendanceRequest $request): RedirectResponse
    {
        $this->attendanceService->markBulk(
            classGroupId: $request->class_group_id,
            date: $request->date,
            records: $request->validated('attendance'),
        );
        return back()->with('success', 'Absensi berhasil disimpan.');
    }

    public function index(Request $request): InertiaResponse
    {
        return Inertia::render('Attendance/Index', [
            'classGroups' => fn () => ClassGroup::current()->with('homeroomTeacher')->get(),
            'todayAttendance' => fn () => $this->attendanceService->getTodaySummary(),
        ]);
    }
}
```

## Multi-Tenancy: BelongsToSchool Trait

**CRITICAL:** Every tenant model MUST use this trait. A single missed scope = data breach.

```php
trait BelongsToSchool
{
    public static function bootBelongsToSchool(): void
    {
        static::addGlobalScope('school', function (Builder $builder) {
            $builder->where(
                $builder->getModel()->qualifyColumn('school_id'),
                tenant('id')
            );
        });

        static::creating(function (Model $model) {
            if (!$model->school_id) {
                $model->school_id = tenant('id');
            }
        });
    }
}
```

- Tenant resolution: subdomain-based (`{school_slug}.platform.id`)
- Cache keys auto-prefixed: `tenant_{school_id}_`
- Queue jobs auto-tagged with current tenant
- SuperAdmin routes on `admin.platform.id` without tenancy middleware
- CI test scans all Eloquent models to verify tenant models use this trait

### Table Classification

**Shared (no `school_id`):** provinces, districts, religions, curriculum_templates, subscription_plans

**Tenant (has `school_id`):** users, students, attendances, payments, and all other domain tables

## Database Schema Patterns

### Indexing Rule
All composite indexes MUST lead with `school_id` for tenant-scoped queries.

### JSONB for Flexible Data
Use JSONB columns (`family_data`, `health_data`, `metadata`) when data structures vary between schools.

### Unique Constraints with Partial Indexes
```sql
-- Per-subject attendance (SMP/SMA)
CREATE UNIQUE INDEX idx_attendance_unique
    ON attendances(school_id, student_id, date, subject_id)
    WHERE subject_id IS NOT NULL;
-- Daily attendance (SD/TK)
CREATE UNIQUE INDEX idx_attendance_unique_daily
    ON attendances(school_id, student_id, date)
    WHERE subject_id IS NULL;
```

### Financial Tables
- `student_fees`: amount as DECIMAL(15,2), status, due_date
- `payments`: gateway_response as JSONB, verified_by, paid_at
- All payment changes logged via `spatie/activitylog`
- `StudentFeeSummary` materialized table updated by PaymentObserver

## PHP Backed Enums (String Values)

Stored as VARCHAR — readable in DB, easy to debug:

```php
enum AttendanceStatus: string {
    case Present = 'present';
    case Sick = 'sick';
    case Permitted = 'permitted';
    case Absent = 'absent';
}

enum PaymentStatus: string {
    case Pending = 'pending';
    case Success = 'success';
    case Failed = 'failed';
    case Expired = 'expired';
    case Refunded = 'refunded';
}

enum StudentStatus: string {
    case Active = 'active';
    case Transferred = 'transferred';
    case Graduated = 'graduated';
    case DroppedOut = 'dropped_out';
}
```

## Permission Structure

Format: `{module}.{action}`

```
students.view, students.create, students.edit, students.delete, students.import
attendance.view, attendance.mark, attendance.edit
grades.view, grades.input, grades.edit
payments.view, payments.create, payments.verify
announcements.view, announcements.create, announcements.publish
rapor.view, rapor.generate, rapor.publish
settings.manage
```

### Role Mapping
| Role | Key Permissions |
|------|----------------|
| Kepala Sekolah | All view + rapor.publish + settings.manage |
| Guru | attendance.mark, grades.input, students.view |
| Wali Kelas | All Guru + rapor.generate + students in their class |
| Bendahara | payments.*, finance reports |
| Orang Tua | View own children's data ONLY (enforced at query level) |

## Middleware Stack (Tenant Routes)

`InitializeTenancy -> EnsureSchoolActive -> CheckSubscription -> Auth -> VerifyRole`

## Caching Strategy

```php
// School config (rarely changes)
Cache::tags(["school:{$schoolId}"])->remember('settings', 3600, fn () => ...);

// Dashboard aggregations (5-min TTL)
Cache::tags(["school:{$schoolId}", 'dashboard'])->remember('attendance_today', 300, fn () => ...);

// Invalidate on relevant events
Cache::tags(["school:" . tenant('id'), 'dashboard'])->forget('payment_summary');
```

## Additional Resources

- Full database schema: [db-schema-reference.md](db-schema-reference.md)
- Full architecture document: [architecture.md](../../../docs/architecture.md)

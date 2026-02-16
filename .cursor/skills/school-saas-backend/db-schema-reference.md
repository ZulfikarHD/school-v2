# Database Schema Reference

## Students Table (JSONB for Flexible Data)

```sql
CREATE TABLE students (
    id BIGSERIAL PRIMARY KEY,
    school_id BIGINT NOT NULL REFERENCES schools(id),
    nisn VARCHAR(20),
    nik VARCHAR(20),
    name VARCHAR(255) NOT NULL,
    birth_date DATE,
    gender VARCHAR(1) CHECK (gender IN ('L', 'P')),
    religion VARCHAR(20),
    address TEXT,
    photo_path VARCHAR(500),
    status VARCHAR(20) DEFAULT 'active',
    family_data JSONB DEFAULT '{}',
    health_data JSONB DEFAULT '{}',
    metadata JSONB DEFAULT '{}',
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_students_school ON students(school_id);
CREATE INDEX idx_students_school_status ON students(school_id, status);
CREATE INDEX idx_students_nisn ON students(school_id, nisn);
```

> `family_data`, `health_data`, `metadata` use JSONB because structures vary between schools. Custom fields go into `metadata`.

## Attendances Table (Hottest Table)

```sql
CREATE TABLE attendances (
    id BIGSERIAL PRIMARY KEY,
    school_id BIGINT NOT NULL,
    student_id BIGINT NOT NULL,
    class_group_id BIGINT NOT NULL,
    subject_id BIGINT,
    date DATE NOT NULL,
    status VARCHAR(10) NOT NULL,
    marked_by BIGINT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Prevent duplicate attendance
CREATE UNIQUE INDEX idx_attendance_unique
    ON attendances(school_id, student_id, date, subject_id)
    WHERE subject_id IS NOT NULL;
CREATE UNIQUE INDEX idx_attendance_unique_daily
    ON attendances(school_id, student_id, date)
    WHERE subject_id IS NULL;

CREATE INDEX idx_attendance_class_date
    ON attendances(school_id, class_group_id, date);
```

> `subject_id` is NULL for SD/TK (daily attendance) and populated for SMP/SMA (per-subject attendance).

## Payment Tables (Financial Integrity)

```sql
CREATE TABLE student_fees (
    id BIGSERIAL PRIMARY KEY,
    school_id BIGINT NOT NULL,
    student_id BIGINT NOT NULL,
    fee_type_id BIGINT NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    due_date DATE NOT NULL,
    status VARCHAR(20) DEFAULT 'unpaid',
    academic_year_id BIGINT NOT NULL,
    month INT,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE payments (
    id BIGSERIAL PRIMARY KEY,
    school_id BIGINT NOT NULL,
    student_fee_id BIGINT NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    payment_method VARCHAR(50),
    gateway_provider VARCHAR(20),
    gateway_transaction_id VARCHAR(100),
    gateway_response JSONB DEFAULT '{}',
    status VARCHAR(20) DEFAULT 'pending',
    paid_at TIMESTAMP,
    verified_by BIGINT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_payments_school_status ON payments(school_id, status);
CREATE INDEX idx_payments_gateway ON payments(gateway_provider, gateway_transaction_id);
```

## Entity Relationships (Core)

```
schools -> users, students, academic_years, class_groups, subjects, fee_types, announcements
academic_years -> semesters -> report_cards
class_groups -> class_students, schedules; class_groups -> users (homeroom teacher)
students -> class_students, attendances, grades, student_fees, report_cards
users -> student_parents; students -> student_parents
users -> teacher_subjects; subjects -> teacher_subjects
subjects -> assessments -> grades
fee_types -> student_fees -> payments
```

## Materialized Summary Table

```php
class StudentFeeSummary extends Model
{
    // school_id, student_id, academic_year_id
    // total_due, total_paid, outstanding, months_overdue
    // Updated via PaymentObserver when payments change
}
```

## Future Partitioning (Post Year 1)

When `attendances` or `payments` exceed ~50M rows:

```sql
CREATE TABLE attendances (...) PARTITION BY RANGE (date);
CREATE TABLE attendances_2026_1 PARTITION OF attendances
    FOR VALUES FROM ('2026-01-01') TO ('2026-07-01');
```

## Encryption (UU PDP Compliance)

Student NIK and parent phone numbers are encrypted at rest:
```php
$casts = ['nik' => 'encrypted', 'phone' => 'encrypted'];
```

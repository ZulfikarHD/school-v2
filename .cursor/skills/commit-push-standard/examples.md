# Commit Message Examples

Contoh commit message menggunakan standar yang diperbarui.

---

## Contoh: Commit Deskriptif dengan Body

### Fitur UI components (multi-file)

```
feat(ui): tambah DataTable, EmptyState, CurrencyDisplay, dan FormField components

Mengapa:
- Dibutuhkan reusable components untuk halaman CRUD yang akan dibuat di sprint berikutnya
- Tanpa standar components, setiap developer akan buat implementasi sendiri
- DataTable harus support pagination, filtering, dan responsive card view di mobile

Perubahan:
- DataTable: DataTablePagination.vue, DataTableFilter.vue, types.ts
- Shared: EmptyState.vue, DateDisplay.vue, CurrencyDisplay.vue
- Forms: FormField.vue dengan label, error, dan hint support
- Composables: useCurrency.ts untuk format Rupiah, useOnlineStatus.ts

Refs: US-S0.6, US-S0.7
```

### Layout dan navigation

```
feat(layout): tambah sidebar navigation dan role-based layout untuk admin, guru, dan ortu

Mengapa:
- Setiap role membutuhkan navigasi yang berbeda sesuai fitur yang diakses
- Layout harus responsive: sidebar di desktop, bottom nav di mobile (ortu)
- Bagian dari sprint 0 setup infrastruktur frontend

Perubahan:
- AppLayout.vue sebagai base layout dengan slot sidebar dan content
- AdminLayout, TeacherLayout, ParentLayout dengan menu items sesuai role
- Sidebar.vue dengan collapse state dan active route indicator
- MobileBottomNav.vue untuk parent role di mobile

Testing:
- Manual test navigasi di setiap role
- Verifikasi responsive behavior di viewport 360px dan 1280px

Refs: US-S0.4
```

### CI/CD pipeline

```
feat(ci): tambah GitHub Actions CI/CD pipeline dengan tenant isolation scan

Mengapa:
- Belum ada automated testing dan deployment pipeline
- Perlu memastikan tenant data isolation tidak bocor antar sekolah
- Manual deployment rawan human error

Perubahan:
- .github/workflows/ci.yml: lint, test, build pipeline
- .github/workflows/deploy.yml: staging dan production deployment
- Tambah tenant isolation scan script di scripts/check-tenant-isolation.sh
- Docker build optimization dengan multi-stage build

Testing:
- Pipeline tested di branch feature, semua step passing
- Tenant isolation scan detect 0 violations

Refs: US-S0.5
```

### Fix bug

```
fix(auth): perbaiki redirect loop saat session expired di halaman dashboard

Mengapa:
- User yang session-nya expired tetap di halaman dashboard
- API call gagal 401 tapi frontend tidak handle redirect
- Dilaporkan 5 user dalam seminggu terakhir

Perubahan:
- Tambah Axios response interceptor untuk handle 401
- Auto-redirect ke login dengan flash message "Sesi berakhir"
- Simpan intended URL untuk redirect setelah re-login

Testing:
- Feature test simulasi expired session dengan assertRedirect
- Manual test dengan session lifetime diperpendek ke 1 menit
```

### Refactor

```
refactor(payment): ekstrak payment logic dari controller ke PaymentService

Mengapa:
- PaymentController sudah 400+ baris, sulit di-maintain
- Logic payment perlu reusable untuk fitur booking dan membership
- Tidak ada separation of concerns antara HTTP layer dan business logic

Perubahan:
- Buat PaymentService dengan method: process(), verify(), refund()
- Buat PaymentGatewayInterface untuk abstraksi Midtrans/Xendit
- Controller sekarang hanya handle request/response, delegate ke service
- Tidak ada perubahan behavior atau API response format

Testing:
- Semua existing test passing tanpa modifikasi
- Tambah unit test untuk PaymentService (12 test cases)
```

### Database migration

```
feat(akademik): tambah tabel mata pelajaran dan jadwal kelas

Mengapa:
- Guru membutuhkan manajemen jadwal mengajar
- Admin perlu assign mata pelajaran ke kelas dan guru
- Foundation untuk fitur absensi dan penilaian di sprint berikutnya

Perubahan:
- Migration: subjects, class_schedules, subject_teacher pivot
- Model: Subject, ClassSchedule dengan relasi dan factory
- Seeder: SubjectSeeder dengan mata pelajaran kurikulum merdeka
- Enum: SubjectCategory (Umum, Muatan Lokal, Ekstrakurikuler)

Testing:
- Feature test CRUD subjects
- Unit test validasi schedule conflict (tidak boleh bentrok)

Breaking Changes:
- Perlu jalankan migration: php artisan migrate

Refs: US-S1.3
```

---

## Contoh: Commit Trivial (Tanpa Body)

Hanya untuk perubahan yang BENAR-BENAR trivial:

```
fix(ui): perbaiki typo "pembayran" menjadi "pembayaran"
```

```
style(lint): format ulang file sesuai pint rules
```

```
chore(deps): bump yarn.lock setelah update
```

---

## Anti-Pattern (JANGAN Lakukan)

```
# Terlalu singkat — tidak ada konteks apa yang ditambah
feat(ui): tambah components

# Generic — update apa? kenapa?
update code

# WIP tanpa informasi
wip

# Menjelaskan APA yang sudah jelas dari diff, bukan MENGAPA
feat(user): tambah kolom phone_number di tabel users

# Summary ambigu — base apa? layout apa?
feat(layout): tambah base layout

# Body tidak menjelaskan alasan
feat(auth): tambah login page

Perubahan:
- Tambah LoginPage.vue
- Tambah LoginController.php
(^ ini cuma repeat summary, tidak ada nilai tambah)
```

---

## PR Description Contoh

```markdown
## Ringkasan
- Tambah reusable DataTable component dengan pagination dan responsive card view
- Tambah shared components: EmptyState, CurrencyDisplay, DateDisplay
- Tambah FormField component untuk standarisasi form layout

## Mengapa
Sprint berikutnya akan banyak halaman CRUD (siswa, guru, pembayaran).
Tanpa standar components, setiap halaman akan punya implementasi berbeda
yang menyulitkan maintenance. Components ini juga sudah dioptimasi untuk
budget Android device (Redmi 9-class) yang jadi target utama user parent.

## Perubahan Utama
- DataTable: support server-side pagination, search filter, mobile card layout
- CurrencyDisplay: format Rupiah dengan useCurrency composable
- EmptyState: consistent empty state dengan icon, title, description, CTA
- FormField: wrapper untuk label + input + error + hint

## Testing
- [x] Unit test useCurrency formatting
- [x] Manual test responsive DataTable di 360px viewport
- [ ] Integration test dengan real API data

## Catatan untuk Reviewer
- DataTable pagination menggunakan Inertia preserveState untuk UX
- CurrencyDisplay intentionally tidak pakai Intl.NumberFormat karena
  inconsistent di Android WebView lama
```

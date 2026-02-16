# Commit Message Examples

Contoh real-world commit message untuk referensi.

---

## Format RINGKAS

### Typo fix
```
fix(ui): perbaiki typo pada error message payment

Mengubah "pembayran" menjadi "pembayaran" di modal gagal bayar
```

### Simple style fix
```
style(lint): format ulang file controller sesuai pint
```

### Dependency update
```
chore(deps): update laravel framework ke v12.1
```

### Documentation
```
docs(readme): tambah instruksi setup environment lokal
```

---

## Format DETAIL

### Fitur baru
```
feat(booking): tambah flow booking lapangan dengan payment

Alasan:
- User belum bisa melakukan booking langsung dari aplikasi
- Proses manual via WhatsApp menyebabkan double booking
- Bagian dari sprint fase 2 growth features

Perubahan:
- Buat BookingController dengan store/show/cancel actions
- Tambah BookingService untuk business logic dan validasi slot
- Buat migration untuk tabel bookings dan booking_slots
- Implementasi Midtrans payment gateway integration
- Tambah BookingResource untuk API response

Testing:
- Feature test untuk full booking flow (happy + edge case)
- Unit test untuk kalkulasi harga dan slot availability
- Manual test payment di staging dengan Midtrans sandbox

Catatan:
- Midtrans server key perlu di-set di .env (MIDTRANS_SERVER_KEY)
- Rate limit 10 booking per user per jam untuk prevent abuse

Refs: #BOOK-123
```

### Bug fix kompleks
```
fix(auth): perbaiki session expired tidak redirect ke login

Alasan:
- User yang session-nya expired tetap di halaman dashboard
- API call gagal dengan 401 tapi tidak ada handling di frontend
- Sudah dilaporkan oleh 5 user dalam seminggu terakhir

Perubahan:
- Tambah Axios interceptor untuk handle response 401
- Implementasi auto-redirect ke login page dengan flash message
- Simpan intended URL untuk redirect setelah login ulang
- Tambah middleware CheckSessionExpiry untuk double protection

Testing:
- Feature test simulasi expired session
- Manual test dengan mempersingkat session lifetime
- Verifikasi redirect flow setelah re-login

Refs: #AUTH-89
```

### Database/API change
```
feat(membership): tambah sistem membership tier untuk venue

Alasan:
- Owner membutuhkan fitur loyalitas untuk retain customer
- Membership tier (Bronze/Silver/Gold) memberikan diskon berbeda
- Bagian dari epic E16 membership management

Perubahan:
- Buat migration: memberships, membership_tiers, user_memberships
- Tambah MembershipService dengan logic upgrade/downgrade tier
- Buat API endpoint untuk CRUD membership oleh owner
- Tambah MembershipResource dan MembershipTierResource
- Implementasi auto-upgrade berdasarkan total transaksi

Testing:
- Feature test untuk semua CRUD operations
- Unit test untuk kalkulasi tier upgrade threshold
- Test edge case: downgrade saat membership expired

Breaking Changes:
- Tabel users ditambah kolom current_membership_tier_id (nullable)

Catatan:
- Perlu jalankan migration: php artisan migrate
- Seeder tersedia: php artisan db:seed --class=MembershipTierSeeder

Refs: #MEM-201
```

### Refactor
```
refactor(payment): ekstrak payment logic ke dedicated service

Alasan:
- PaymentController sudah 400+ baris dan sulit di-maintain
- Logic payment tersebar di controller dan model
- Perlu reusable untuk fitur booking dan membership

Perubahan:
- Buat PaymentService dengan method: process, verify, refund
- Pindahkan semua payment logic dari controller ke service
- Tambah PaymentGatewayInterface untuk abstraksi gateway
- Update controller untuk menggunakan service layer
- Tidak ada perubahan behavior atau response format

Testing:
- Semua existing test tetap passing tanpa modifikasi
- Tambah unit test khusus untuk PaymentService

Refs: #TECH-55
```

---

## PR Description Contoh

### Fitur Baru
```markdown
## Ringkasan
- Implementasi sistem booking lapangan dengan payment integration
- User bisa pilih slot, bayar via Midtrans, dan terima konfirmasi
- Owner mendapat notifikasi real-time untuk setiap booking baru

## Alasan
Proses booking manual via WhatsApp menyebabkan double booking dan
pengalaman user yang buruk. Sistem otomatis mengurangi beban operasional
owner sekaligus meningkatkan conversion rate.

## Perubahan Utama
- BookingController + BookingService (create/cancel/reschedule)
- Midtrans payment gateway integration
- Real-time notification via broadcast
- Migration untuk bookings, booking_slots, payments
- Vue pages: BookingCreate, BookingDetail, BookingHistory

## Testing
- [x] Feature test: full booking flow
- [x] Feature test: cancellation dan refund
- [x] Feature test: slot conflict handling
- [x] Unit test: price calculation
- [ ] E2E test di staging environment

## Screenshot
(attach screenshot booking flow di mobile)

## Catatan untuk Reviewer
- Perlu setup MIDTRANS_SERVER_KEY di .env
- Cek khusus logic slot overlap di BookingService@validateSlot
- Payment webhook endpoint perlu di-whitelist di Midtrans dashboard
```

---

## Anti-Pattern (JANGAN Lakukan)

```
# Terlalu singkat, tidak ada konteks
fix stuff

# Terlalu generic
update code

# Work in progress tanpa detail
wip

# Menjelaskan WHAT yang sudah obvious dari diff
tambah variabel $name di UserController

# Menyalahkan orang lain
fix kode jelek dari developer sebelumnya

# Campuran bahasa tidak konsisten
feat: menambahkan new feature untuk user baru
```

# Laporan Sprint 0 — Infrastructure & Project Skeleton

> **Tanggal:** 16 Februari 2026 s.d. (masih berjalan)
> **Durasi:** 2 minggu
> **Author:** Zulfikar Hidayatullah
> **Epic:** SPRINT-0 Infrastructure & Project Skeleton

---

## Ringkasan

Sprint 0 berfokus pada pembangunan fondasi development environment dan project skeleton. US-S0.1 (Docker), US-S0.2 (Laravel 12 initialization), dan US-S0.3 (Vue 3 + Inertia.js + Tailwind CSS 4 setup) telah selesai sepenuhnya. Semua core packages ter-install, `php artisan test` berjalan dengan 41 test pass, dan `yarn build` menghasilkan bundle produksi (95KB gzipped initial JS). Sisa user story (US-S0.4 s.d. US-S0.7) belum dimulai.

---

## Status Backlog

### User Stories

| ID | User Story | SP | Status | Catatan |
|----|-----------|-----|--------|---------|
| US-S0.1 | Docker Development Environment | 5 | ✅ Selesai | Semua 7 acceptance criteria terpenuhi |
| US-S0.2 | Laravel 12 Project Initialization | 3 | ✅ Selesai | Semua 8 acceptance criteria terpenuhi |
| US-S0.3 | Vue 3 + Inertia.js + Tailwind CSS 4 Setup | 3 | ✅ Selesai | Semua 9 acceptance criteria terpenuhi |
| US-S0.4 | Base Layout Components | 5 | ❌ Belum | — |
| US-S0.5 | CI/CD Pipeline | 3 | ❌ Belum | File workflow sudah ada tapi belum sesuai spec |
| US-S0.6 | Base UI Component Library | 2 | ❌ Belum | — |
| US-S0.7 | DataTable Component | 3 | ❌ Belum | — |

### Acceptance Criteria yang Belum Terpenuhi

- **US-S0.4**: Semua layout (Admin, Teacher, Parent, Auth) belum dibuat sesuai spec
- **US-S0.5**: CI pipeline belum sesuai spec (PHPStan, Docker build step, tenant isolation scan)
- **US-S0.6**: Semua UI component belum di-scaffold
- **US-S0.7**: DataTable component belum dibuat

---

## Velocity

| Metrik | Nilai |
|--------|-------|
| SP Direncanakan | 24 |
| SP Selesai | 11 |
| SP Carry Over | 13 |
| Velocity | 45.8% |

### Analisis Velocity

Sprint 0 masih berjalan — velocity naik dari 20.8% ke 45.8% setelah penyelesaian US-S0.2 dan US-S0.3. Ini sesuai ekspektasi karena story-story ini merupakan dependency chain yang harus diselesaikan berurutan (Docker → Laravel → Frontend).

---

## Retrospektif

### Apa yang Berjalan Baik

- Docker environment berhasil dikonfigurasi dengan semua service yang dibutuhkan
- Healthcheck pada semua database service memastikan dependency ordering yang benar
- Semua core Laravel packages (10 packages) berhasil di-install tanpa konflik versi
- Wayfinder configured untuk type-safe route generation — menggantikan Ziggy sepenuhnya
- Horizon dan Reverb ter-install dan terkonfigurasi untuk queue dan WebSocket management
- `yarn build` menghasilkan bundle 95KB gzipped — di bawah target 200KB
- Vite plugin visualizer terkonfigurasi untuk monitoring bundle size
- Pre-existing test failures (41 tests) berhasil diperbaiki — semua pass

### Apa yang Perlu Diperbaiki

- File permission di Docker container perlu perbaikan (bind-mount menyebabkan ownership mismatch)
- `based/laravel-typescript` belum support Laravel 12 — perlu alternatif atau tunggu update
- Wayfinder Vite plugin tidak bisa jalan saat `build` karena container node tidak punya PHP — perlu conditional plugin
- Tenant route dari `stancl/tenancy` override central route `/` — perlu hati-hati saat konfigurasi multi-tenancy

### Action Items

| # | Action Item | PIC | Target Sprint |
|---|------------|-----|---------------|
| 1 | Lanjutkan US-S0.4 s.d. US-S0.7 | Dev | Sprint 0 |
| 2 | Evaluasi alternatif `based/laravel-typescript` untuk Laravel 12 | Dev | Sprint 1 |
| 3 | Konfigurasi tenant routes saat multi-tenancy dimulai | Dev | Sprint 1 |

---

## Risiko & Blocker

| Risiko/Blocker | Dampak | Status | Mitigasi |
|---------------|--------|--------|----------|
| Package version conflict (PHP 8.4 vs 8.3 di doc) | Sedang | Resolved | Dockerfile sudah di-update ke PHP 8.4 |
| Port conflict pada mesin lokal | Rendah | Resolved | Gunakan FORWARD_* prefix untuk port host yang bisa dikustomisasi |
| `based/laravel-typescript` tidak support Laravel 12 | Rendah | Aktif | Manual type definitions sementara, monitor package update |
| Docker bind-mount permission issues | Rendah | Resolved | Fix ownership via `chown` in container setup |

---

## Catatan untuk Sprint Berikutnya

- Lanjutkan US-S0.4: buat layout shells (Admin, Teacher, Parent, Auth)
- US-S0.5: setup CI/CD pipeline di GitHub Actions
- US-S0.6 dan US-S0.7: scaffold UI components dan DataTable
- Pastikan semua story Sprint 0 selesai sebelum masuk Sprint 1 (M1 + M2 depend pada infrastruktur ini)
- Saat mengaktifkan multi-tenancy, konfigurasi tenant routes di `routes/tenant.php`

---

*Laporan dibuat pada: 16 Februari 2026*

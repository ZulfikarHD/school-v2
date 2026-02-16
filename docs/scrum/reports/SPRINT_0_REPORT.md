# Laporan Sprint 0 — Infrastructure & Project Skeleton

> **Tanggal:** 16 Februari 2026 s.d. (masih berjalan)
> **Durasi:** 2 minggu
> **Author:** Zulfikar Hidayatullah
> **Epic:** SPRINT-0 Infrastructure & Project Skeleton

---

## Ringkasan

Sprint 0 berfokus pada pembangunan fondasi development environment. Saat ini US-S0.1 (Docker Development Environment) telah selesai sepenuhnya. Semua service berjalan dan terverifikasi — PostgreSQL 16, Redis 7, Meilisearch, MinIO, Nginx, Vite HMR, Mailpit, dan Laravel Scheduler. Sisa user story (US-S0.2 s.d. US-S0.7) belum dimulai.

---

## Status Backlog

### User Stories

| ID | User Story | SP | Status | Catatan |
|----|-----------|-----|--------|---------|
| US-S0.1 | Docker Development Environment | 5 | ✅ Selesai | Semua 7 acceptance criteria terpenuhi |
| US-S0.2 | Laravel 12 Project Initialization | 3 | ❌ Belum | Laravel sudah ter-install, tapi package tambahan belum |
| US-S0.3 | Vue 3 + Inertia.js + Tailwind CSS 4 Setup | 3 | ❌ Belum | Stack dasar sudah ada, tapi konfigurasi lanjutan belum |
| US-S0.4 | Base Layout Components | 5 | ❌ Belum | — |
| US-S0.5 | CI/CD Pipeline | 3 | ❌ Belum | File workflow sudah ada tapi belum sesuai spec |
| US-S0.6 | Base UI Component Library | 2 | ❌ Belum | — |
| US-S0.7 | DataTable Component | 3 | ❌ Belum | — |

### Acceptance Criteria yang Belum Terpenuhi

- **US-S0.2**: Semua criteria belum terpenuhi — package seperti stancl/tenancy, spatie/*, horizon, reverb belum di-install
- **US-S0.3**: Konfigurasi SSR, dayjs, motion-vue, Chart.js, TanStack Table, vite-plugin-visualizer belum
- **US-S0.4**: Semua layout (Admin, Teacher, Parent, Auth) belum dibuat
- **US-S0.5**: CI pipeline belum sesuai spec (PHPStan, Docker build step, tenant isolation scan)
- **US-S0.6**: Semua UI component belum di-scaffold
- **US-S0.7**: DataTable component belum dibuat

---

## Velocity

| Metrik | Nilai |
|--------|-------|
| SP Direncanakan | 24 |
| SP Selesai | 5 |
| SP Carry Over | 19 |
| Velocity | 20.8% |

### Analisis Velocity

Sprint 0 masih berjalan — velocity 20.8% mencerminkan penyelesaian story pertama. Ini sesuai ekspektasi karena US-S0.1 adalah fondasi yang harus selesai sebelum story lain bisa dikerjakan.

---

## Retrospektif

### Apa yang Berjalan Baik

- Docker environment berhasil dikonfigurasi dengan semua service yang dibutuhkan
- Healthcheck pada semua database service memastikan dependency ordering yang benar
- Konfigurasi port yang fleksibel via `.env` mempermudah development di berbagai mesin

### Apa yang Perlu Diperbaiki

- PHP version mismatch antara Dockerfile (8.3) dan composer.lock (butuh 8.4) — butuh pengecekan dependency lebih teliti di awal
- Port conflict (Redis 6379, Nginx 80) di host — perlu default port yang tidak bentrok dari awal
- Horizon container crash-loop karena package belum ter-install — perlu profiling service yang belum siap

### Action Items

| # | Action Item | PIC | Target Sprint |
|---|------------|-----|---------------|
| 1 | Lanjutkan US-S0.2 s.d. US-S0.7 | Dev | Sprint 0 |
| 2 | Sesuaikan doc sprint untuk PHP 8.4 (bukan 8.3) | Dev | Sprint 0 |

---

## Risiko & Blocker

| Risiko/Blocker | Dampak | Status | Mitigasi |
|---------------|--------|--------|----------|
| Package version conflict (PHP 8.4 vs 8.3 di doc) | Sedang | Resolved | Dockerfile sudah di-update ke PHP 8.4 |
| Port conflict pada mesin lokal | Rendah | Resolved | Gunakan FORWARD_* prefix untuk port host yang bisa dikustomisasi |

---

## Catatan untuk Sprint Berikutnya

- Lanjutkan US-S0.2: install semua Laravel package (horizon, tenancy, spatie/*)
- Setelah Horizon ter-install, aktifkan horizon container dengan `docker compose --profile horizon up -d`
- US-S0.3 bisa dikerjakan paralel dengan US-S0.2 (frontend tidak blocking backend)
- Pastikan semua story Sprint 0 selesai sebelum masuk Sprint 1 (M1 + M2 depend pada infrastruktur ini)

---

*Laporan dibuat pada: 16 Februari 2026*

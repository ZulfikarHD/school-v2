---
name: sprint-report
description: Membuat laporan akhir sprint dalam Bahasa Indonesia berdasarkan acceptance criteria di docs/scrum/. Gunakan saat user meminta laporan sprint, sprint report, review sprint, atau rekap sprint. Menghasilkan file markdown di docs/scrum/reports/.
---

# Laporan Akhir Sprint

Skill ini membuat laporan sprint standar dalam Bahasa Indonesia, disimpan di `docs/scrum/reports/`.

## Alur Kerja

Salin checklist ini dan lacak progres:

```
Progres Pembuatan Laporan:
- [ ] Langkah 1: Identifikasi sprint & epic terkait
- [ ] Langkah 2: Periksa acceptance criteria di docs/scrum/
- [ ] Langkah 3: Hitung velocity (SP selesai vs direncanakan)
- [ ] Langkah 4: Tulis laporan menggunakan template
- [ ] Langkah 5: Simpan file laporan
```

### Langkah 1: Identifikasi Sprint

1. Tanyakan user: **"Sprint berapa yang mau dilaporkan?"**
2. Buka `docs/scrum/00_SCRUM_OVERVIEW.md` bagian **Sprint Roadmap** untuk menentukan epic/modul mana yang masuk sprint tersebut.
3. Catat: nomor sprint, durasi (minggu), dan epic yang dijadwalkan.

### Langkah 2: Periksa Acceptance Criteria

1. Buka file epic yang relevan di `docs/scrum/` (contoh: `M01_SCHOOL_PROFILE.md`, `00_SPRINT_0_INFRASTRUCTURE.md`).
2. Periksa setiap **User Story** dan **acceptance criteria** (checklist `- [x]` / `- [ ]`).
3. Klasifikasikan setiap User Story:

| Status | Arti |
|--------|------|
| **Selesai** | Semua acceptance criteria ter-checklist `[x]` |
| **Sebagian** | Sebagian criteria selesai, sebagian belum |
| **Belum Dimulai** | Semua criteria masih `[ ]` |
| **Carry Over** | Sudah mulai tapi tidak selesai dalam sprint ini |

4. Periksa juga **Definition of Done (Epic Level)** di bagian bawah file epic.

### Langkah 3: Hitung Velocity

- **SP Direncanakan**: Total story points dari semua user story yang masuk sprint backlog.
- **SP Selesai**: Total story points dari user story yang berstatus **Selesai**.
- **SP Carry Over**: Total story points yang dibawa ke sprint berikutnya.
- **Velocity**: SP Selesai / SP Direncanakan × 100%.

### Langkah 4: Tulis Laporan

Gunakan template di bawah. **Seluruh isi laporan wajib dalam Bahasa Indonesia.**

### Langkah 5: Simpan File

- Lokasi: `docs/scrum/reports/SPRINT_<nomor>_REPORT.md`
- Contoh: `docs/scrum/reports/SPRINT_0_REPORT.md`
- Buat folder `reports/` jika belum ada.

---

## Template Laporan

```markdown
# Laporan Sprint <NOMOR> — <Judul Sprint>

> **Tanggal:** <tanggal mulai> s.d. <tanggal selesai>
> **Durasi:** 2 minggu
> **Author:** Zulfikar Hidayatullah
> **Epic:** <daftar epic yang dikerjakan>

---

## Ringkasan

<Paragraf singkat 2-4 kalimat: Apa tujuan sprint ini, apa yang berhasil dicapai, dan apakah target tercapai atau tidak.>

---

## Status Backlog

### User Stories

| ID | User Story | SP | Status | Catatan |
|----|-----------|-----|--------|---------|
| US-XX.X | <judul story> | <sp> | ✅ Selesai / ⚠️ Sebagian / ❌ Belum / 🔄 Carry Over | <catatan singkat> |

### Acceptance Criteria yang Belum Terpenuhi

<Jika ada criteria yang belum selesai, daftarkan di sini per user story.>

- **US-XX.X**: <criteria yang belum selesai>
- **US-XX.X**: <criteria yang belum selesai>

> Jika semua selesai, tulis: *Semua acceptance criteria terpenuhi.*

---

## Velocity

| Metrik | Nilai |
|--------|-------|
| SP Direncanakan | <angka> |
| SP Selesai | <angka> |
| SP Carry Over | <angka> |
| Velocity | <persentase>% |

### Analisis Velocity

<1-2 kalimat analisis. Apakah sesuai target? Kalau tidak, kenapa?>

---

## Retrospektif

### Apa yang Berjalan Baik

- <poin 1>
- <poin 2>

### Apa yang Perlu Diperbaiki

- <poin 1>
- <poin 2>

### Action Items

| # | Action Item | PIC | Target Sprint |
|---|------------|-----|---------------|
| 1 | <tindakan> | <penanggung jawab> | Sprint <nomor> |

---

## Risiko & Blocker

| Risiko/Blocker | Dampak | Status | Mitigasi |
|---------------|--------|--------|----------|
| <deskripsi> | Tinggi/Sedang/Rendah | Aktif/Resolved | <langkah mitigasi> |

> Jika tidak ada, tulis: *Tidak ada risiko atau blocker yang teridentifikasi.*

---

## Catatan untuk Sprint Berikutnya

- <Carry over stories jika ada>
- <Hal yang harus dipersiapkan>
- <Dependency yang perlu diselesaikan>

---

*Laporan dibuat pada: <tanggal pembuatan laporan>*
```

---

## Panduan Penulisan

1. **Bahasa**: Seluruh laporan dalam Bahasa Indonesia. Istilah teknis (sprint, backlog, carry over) boleh tetap dalam bahasa Inggris.
2. **Nada**: Profesional tapi ringkas. Hindari kalimat bertele-tele.
3. **Retrospektif**: Jika user tidak memberikan input spesifik, analisis berdasarkan:
   - Jumlah story yang carry over (banyak carry over = estimasi terlalu optimis)
   - Complexity dari task yang selesai vs yang gagal
   - Pattern dari acceptance criteria yang belum terpenuhi
4. **Risiko**: Periksa bagian **Risks & Mitigations** di file epic untuk referensi.
5. **Tanggal**: Gunakan format Indonesia: `16 Februari 2026`.

## Aturan Penting

- **JANGAN** mengubah file epic di `docs/scrum/`. Laporan hanya dibuat sebagai file terpisah di `reports/`.
- **JANGAN** mengarang data. Jika tidak bisa menentukan status suatu criteria, tanyakan ke user.
- **SELALU** periksa file epic asli sebelum menulis laporan — jangan mengandalkan asumsi.

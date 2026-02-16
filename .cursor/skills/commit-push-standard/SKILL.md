---
name: commit-push-standard
description: Generate standardized commit messages and PR descriptions using Conventional Commits format with Indonesian language. Use when committing code, creating pull requests, writing commit messages, or when the user mentions commit, push, PR, or pull request.
---

# Commit & Push Standard

Skill untuk menulis commit message dan PR description yang terstruktur,
menggunakan format Conventional Commits dengan bahasa Indonesia.

## Workflow

```
Diminta commit/push?
│
├─► Run: git status + git diff + git log (parallel)
├─► Analisis semua perubahan (staged + unstaged)
├─► Tentukan ukuran perubahan:
│   ├─► Kecil (1-2 file, simple fix) ──► Format RINGKAS
│   └─► Besar (3+ file, fitur baru) ──► Format DETAIL
├─► Tulis commit message dalam bahasa Indonesia
├─► Stage semua file relevan
├─► Commit dengan HEREDOC format
├─► Push ke remote
└─► Verifikasi dengan git status
```

## Commit Message Format

### Format RINGKAS (perubahan kecil)

```
<type>(<scope>): <ringkasan dalam present tense>

<penjelasan singkat 1-2 baris jika perlu>

Refs: #<ticket-number>
```

### Format DETAIL (perubahan besar)

```
<type>(<scope>): <ringkasan dalam present tense>

Alasan:
- <mengapa perubahan ini diperlukan>
- <masalah apa yang diselesaikan>

Perubahan:
- <perubahan utama yang dilakukan>
- <detail penting lainnya>

Testing:
- <cara testing yang dilakukan>

[Opsional:]
Breaking Changes:
- <hal yang mempengaruhi fungsionalitas existing>

Catatan:
- <info penting untuk reviewer>

Refs: #<ticket-number>
```

## Tipe Commit

| Type | Penggunaan | Contoh Scope |
|------|-----------|--------------|
| `feat` | Fitur baru | auth, booking, payment |
| `fix` | Perbaikan bug | validation, ui, api |
| `refactor` | Restrukturisasi tanpa ubah behavior | service, model |
| `docs` | Dokumentasi saja | readme, comments |
| `style` | Formatting, tanpa perubahan logic | lint, format |
| `test` | Penambahan/update test | unit, feature |
| `chore` | Dependency, build config, dll | deps, config |
| `perf` | Optimasi performa | query, cache |

## Aturan Penulisan

### Summary Line
- Maksimal 50 karakter
- Present tense: "tambah" bukan "menambahkan"
- Huruf kecil setelah colon
- Tanpa titik di akhir

### Body
- Wrap di 72 karakter per baris
- Bahasa Indonesia formal tapi ringkas
- Fokus pada MENGAPA, bukan APA (yang sudah terlihat di diff)
- Terminologi teknis boleh tetap English (controller, service, migration)

### Decision: Kapan Pakai Format Detail?

```
Perubahan kamu:
├─► Hanya typo/format/1 file kecil ────► RINGKAS
├─► Fix bug sederhana ─────────────────► RINGKAS + 1 baris penjelasan
├─► Fitur baru / multi-file ───────────► DETAIL
├─► Perubahan database/API ────────────► DETAIL (wajib)
├─► Breaking change ───────────────────► DETAIL (wajib)
└─► Config/environment change ─────────► DETAIL (wajib)
```

## PR Description Format

```markdown
## Ringkasan
<1-3 poin penjelasan perubahan utama>

## Alasan
<mengapa perubahan ini diperlukan, konteks bisnis/teknis>

## Perubahan Utama
- <list perubahan signifikan>

## Testing
- [ ] <langkah testing yang sudah dilakukan>
- [ ] <hal yang perlu di-test oleh reviewer>

## Screenshot (jika ada perubahan UI)

## Catatan untuk Reviewer
<hal penting yang perlu diperhatikan>
```

## Checklist Sebelum Commit

- [ ] Tipe commit sudah tepat (feat/fix/refactor/dll)
- [ ] Summary under 50 chars, present tense
- [ ] Ada penjelasan MENGAPA (untuk perubahan non-trivial)
- [ ] Tidak ada file rahasia (.env, credentials)
- [ ] Perubahan database/API? Pakai format DETAIL
- [ ] Referensi ticket jika ada

## Larangan

- JANGAN commit file .env, credentials, atau secrets
- JANGAN ubah git config
- JANGAN force push ke main/master
- JANGAN skip hooks (--no-verify)
- JANGAN commit --amend kecuali commit terakhir belum di-push
- JANGAN tulis "fix stuff", "update", "wip" tanpa konteks

## Contoh Lengkap

Lihat [examples.md](examples.md) untuk contoh real-world.

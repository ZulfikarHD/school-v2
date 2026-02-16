---
name: commit-push-standard
description: Generate standardized commit messages and PR descriptions using Conventional Commits format with Indonesian language. Use when committing code, creating pull requests, writing commit messages, or when the user mentions commit, push, PR, or pull request.
---

# Commit & Push Standard

Skill untuk menulis commit message yang jelas, informatif, dan konsisten.
Format Conventional Commits, bahasa Indonesia. Setiap commit HARUS menjawab: **apa yang berubah, mengapa, dan apa dampaknya**.

## Workflow

```
Diminta commit/push?
│
├─► Run: git status + git diff + git log (parallel)
├─► Analisis SEMUA perubahan (staged + unstaged)
├─► Tulis commit message (SELALU sertakan body)
├─► Stage file relevan (cek tidak ada .env/secrets)
├─► Commit dengan HEREDOC format
├─► Push ke remote
└─► Verifikasi dengan git status
```

## Prinsip Utama

1. **Selalu jelaskan MENGAPA** — diff sudah menunjukkan APA, commit message harus menjelaskan alasan dan konteks
2. **Summary harus cukup deskriptif** — pembaca harus paham garis besar tanpa buka diff
3. **Body WAJIB** — kecuali perubahan benar-benar trivial (typo 1 kata, format otomatis pint)
4. **Sebutkan file/komponen penting** — beri gambaran scope perubahan di body

## Commit Message Format

### Summary Line

```
<type>(<scope>): <ringkasan deskriptif present tense>
```

| Aturan | Detail |
|--------|--------|
| Panjang | Maksimal **100 karakter** (bukan 50 — deskriptif lebih penting) |
| Tense | Present tense: "tambah", "perbaiki", "refactor" |
| Huruf | Huruf kecil setelah colon |
| Titik | Tanpa titik di akhir |
| Bahasa | Indonesia, istilah teknis boleh English |

### Body (WAJIB kecuali trivial)

```
<type>(<scope>): <ringkasan deskriptif>

Mengapa:
- <alasan perubahan ini diperlukan>
- <masalah yang diselesaikan / kebutuhan bisnis>

Perubahan:
- <komponen/file utama yang ditambah/diubah>
- <detail arsitektur atau keputusan penting>

[Opsional jika ada:]
Testing:
- <test yang ditambah/dijalankan>

Breaking Changes:
- <hal yang mempengaruhi fungsionalitas existing>

Catatan:
- <info penting untuk developer lain>

Refs: #<ticket-number>
```

### Body Rules

- Wrap di 72 karakter per baris
- Bahasa Indonesia, istilah teknis boleh English (controller, service, migration, component)
- Setiap section yang dipakai minimal 1 bullet point
- Section "Mengapa" WAJIB ada jika body ada

## Tipe Commit

| Type | Penggunaan | Contoh Scope |
|------|-----------|--------------|
| `feat` | Fitur baru | auth, booking, payment, ui, layout |
| `fix` | Perbaikan bug | validation, ui, api |
| `refactor` | Restrukturisasi tanpa ubah behavior | service, model |
| `docs` | Dokumentasi saja | readme, sprint, comments |
| `style` | Formatting, tanpa perubahan logic | lint, format |
| `test` | Penambahan/update test | unit, feature |
| `chore` | Dependency, build config, CI/CD | deps, config, ci |
| `perf` | Optimasi performa | query, cache |

## Kapan Body Boleh Dilewati? (SANGAT JARANG)

Body hanya boleh kosong untuk perubahan yang **benar-benar trivial**:

```
TANPA body (trivial):
├─► Fix typo 1 kata
├─► Auto-format pint/prettier (tanpa logic change)
└─► Update version number di package.json

DENGAN body (SEMUA sisanya):
├─► Tambah file baru (apapun) ──────────► WAJIB body
├─► Ubah logic di file manapun ─────────► WAJIB body
├─► Tambah/ubah component ──────────────► WAJIB body
├─► Perubahan multi-file ───────────────► WAJIB body
├─► Fix bug (apapun) ──────────────────► WAJIB body
├─► Perubahan database/API ─────────────► WAJIB body
├─► Config/environment change ──────────► WAJIB body
└─► Apapun yang butuh > 5 detik dipahami ► WAJIB body
```

## Menulis Summary yang Baik

Summary harus membuat pembaca paham **apa yang terjadi** tanpa buka diff.

| Buruk (terlalu singkat) | Baik (deskriptif) |
|------------------------|-------------------|
| `feat(ui): tambah components` | `feat(ui): tambah DataTable, EmptyState, dan FormField components` |
| `fix(auth): perbaiki login` | `fix(auth): perbaiki redirect loop saat session expired` |
| `feat(layout): tambah layout` | `feat(layout): tambah sidebar navigation dan role-based layout` |
| `refactor(payment): refactor service` | `refactor(payment): ekstrak payment logic ke PaymentService` |
| `chore(deps): update deps` | `chore(deps): update laravel ke v12.1 dan vue ke v3.5` |
| `feat(ci): tambah pipeline` | `feat(ci): tambah GitHub Actions CI/CD dengan tenant isolation scan` |

## PR Description Format

```markdown
## Ringkasan
<1-3 poin penjelasan perubahan utama>

## Mengapa
<konteks bisnis/teknis mengapa perubahan ini diperlukan>

## Perubahan Utama
- <list perubahan signifikan dengan file/komponen>

## Testing
- [ ] <langkah testing yang sudah dilakukan>
- [ ] <hal yang perlu di-test oleh reviewer>

## Screenshot (jika ada perubahan UI)

## Catatan untuk Reviewer
<hal penting yang perlu diperhatikan>
```

## Checklist Sebelum Commit

- [ ] Summary deskriptif dan under 100 chars
- [ ] Body menjelaskan MENGAPA (kecuali trivial)
- [ ] Body menyebutkan komponen/file utama yang berubah
- [ ] Tipe commit sudah tepat
- [ ] Tidak ada file rahasia (.env, credentials)
- [ ] Referensi ticket jika ada

## Larangan

- JANGAN commit file .env, credentials, atau secrets
- JANGAN ubah git config
- JANGAN force push ke main/master
- JANGAN skip hooks (--no-verify)
- JANGAN commit --amend kecuali commit terakhir belum di-push
- JANGAN tulis summary generic: "fix stuff", "update", "wip", "tambah components"
- JANGAN buat summary yang butuh buka diff untuk dipahami
- JANGAN lewati body untuk perubahan non-trivial

## Contoh

Lihat [examples.md](examples.md) untuk contoh lengkap.

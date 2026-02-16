# Design System — Component Pattern Reference

Detailed Vue + Tailwind CSS 4 examples for the School SaaS platform. All examples support dark mode.

## Card Pattern

Standard card used across all pages:

```vue
<template>
  <div class="rounded-lg border border-(--border-default) bg-(--bg-card) p-4 dark:border-neutral-800">
    <div class="flex items-center justify-between">
      <h3 class="text-sm font-semibold text-(--text-primary)">{{ title }}</h3>
      <slot name="action" />
    </div>
    <div class="mt-3">
      <slot />
    </div>
  </div>
</template>
```

### Stat Card (Dashboard KPIs)

```vue
<template>
  <div class="rounded-lg border border-(--border-default) bg-(--bg-card) p-4">
    <div class="flex items-center gap-3">
      <div class="flex h-10 w-10 items-center justify-center rounded-lg"
           :class="iconBgClass">
        <component :is="icon" class="h-5 w-5" :class="iconColorClass" />
      </div>
      <div>
        <p class="text-xs font-medium text-(--text-secondary)">{{ label }}</p>
        <p class="text-2xl font-bold text-(--text-primary)">{{ value }}</p>
      </div>
    </div>
    <p v-if="subtitle" class="mt-2 text-xs text-(--text-secondary)">{{ subtitle }}</p>
  </div>
</template>
```

KPI color mapping:

| Stat | Icon bg | Icon color |
|------|---------|------------|
| Total students | `bg-primary-50 dark:bg-primary-950` | `text-primary-600 dark:text-primary-400` |
| Attendance rate | `bg-success-50 dark:bg-success-950` | `text-success-600 dark:text-success-400` |
| Unpaid fees | `bg-danger-50 dark:bg-danger-950` | `text-danger-600 dark:text-danger-400` |
| Pending items | `bg-warning-50 dark:bg-warning-950` | `text-warning-600 dark:text-warning-400` |

## Button Variants

```vue
<!-- Primary (main CTA) -->
<Button class="bg-primary-600 text-white hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600">
  Simpan
</Button>

<!-- Secondary / Outline -->
<Button variant="outline" class="border border-(--border-default) text-(--text-primary) hover:bg-neutral-50 dark:hover:bg-neutral-800">
  Batal
</Button>

<!-- Ghost (minimal) -->
<Button variant="ghost" class="text-(--text-secondary) hover:bg-neutral-100 dark:hover:bg-neutral-800 hover:text-(--text-primary)">
  Lihat Semua
</Button>

<!-- Danger (destructive) -->
<Button class="bg-danger-600 text-white hover:bg-danger-700">
  Hapus
</Button>

<!-- Icon-only -->
<Button variant="ghost" size="icon" class="h-9 w-9">
  <MoreHorizontal class="h-4 w-4" />
</Button>
```

### Button Sizing

| Size | Padding | Text | Min height | Use |
|------|---------|------|------------|-----|
| `sm` | `px-3 py-1.5` | `text-xs` | 32px | Table actions, inline |
| `default` | `px-4 py-2` | `text-sm` | 36px | Standard forms |
| `lg` | `px-6 py-2.5` | `text-sm` | 44px | Parent mobile CTAs |

Parent-facing buttons should always be `lg` size (44px min touch target).

## StatusBadge Pattern

```vue
<template>
  <span :class="[baseClass, variantClasses[variant]]">
    {{ label }}
  </span>
</template>

<script setup lang="ts">
const baseClass = 'inline-flex items-center rounded-sm px-2 py-0.5 text-xs font-medium'

const variantClasses: Record<string, string> = {
  success: 'bg-success-50 text-success-700 dark:bg-success-950 dark:text-success-400',
  warning: 'bg-warning-50 text-warning-700 dark:bg-warning-950 dark:text-warning-400',
  danger:  'bg-danger-50 text-danger-700 dark:bg-danger-950 dark:text-danger-400',
  info:    'bg-info-50 text-info-700 dark:bg-info-950 dark:text-info-400',
  muted:   'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400',
}
</script>
```

### Status → Variant Mapping

| Domain | Status | Variant | Label (Bahasa) |
|--------|--------|---------|----------------|
| Payment | success | `success` | Lunas |
| Payment | pending | `warning` | Menunggu |
| Payment | failed | `danger` | Gagal |
| Payment | expired | `muted` | Kedaluwarsa |
| Attendance | present | `success` | Hadir |
| Attendance | sick | `warning` | Sakit |
| Attendance | permitted | `info` | Izin |
| Attendance | absent | `danger` | Alfa |
| Student | active | `success` | Aktif |
| Student | transferred | `info` | Pindah |
| Student | graduated | `muted` | Lulus |
| Student | dropped_out | `danger` | Keluar |

## Form Layout Pattern

### Standard Form (Single Column)

```vue
<template>
  <form @submit.prevent="submit" class="space-y-4">
    <FormField label="Nama Lengkap" :error="form.errors.name" required>
      <Input v-model="form.name" placeholder="Masukkan nama lengkap" />
    </FormField>

    <FormField label="NISN" :error="form.errors.nisn">
      <Input v-model="form.nisn" placeholder="10 digit NISN" maxlength="10" />
    </FormField>

    <div class="flex items-center justify-end gap-3 pt-4 border-t border-(--border-default)">
      <Button variant="outline" type="button" @click="cancel">Batal</Button>
      <Button type="submit" :disabled="form.processing">Simpan</Button>
    </div>
  </form>
</template>
```

### Two-Column Form (Desktop)

```vue
<template>
  <form class="space-y-6">
    <!-- Section with title -->
    <div>
      <h3 class="text-lg font-semibold text-(--text-primary)">Data Pribadi</h3>
      <p class="mt-1 text-sm text-(--text-secondary)">Informasi dasar siswa</p>
      <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
        <FormField label="Nama" required>
          <Input v-model="form.name" />
        </FormField>
        <FormField label="Tanggal Lahir">
          <DatePicker v-model="form.birth_date" />
        </FormField>
      </div>
    </div>

    <!-- Next section -->
    <div class="border-t border-(--border-default) pt-6">
      <h3 class="text-lg font-semibold text-(--text-primary)">Data Keluarga</h3>
      <!-- ... -->
    </div>
  </form>
</template>
```

### FormField Wrapper

```vue
<template>
  <div>
    <label class="mb-1.5 block text-sm font-medium text-(--text-secondary)">
      {{ label }}
      <span v-if="required" class="text-danger-500">*</span>
    </label>
    <slot />
    <p v-if="error" class="mt-1 text-xs text-danger-500">{{ error }}</p>
    <p v-else-if="hint" class="mt-1 text-xs text-(--text-secondary)">{{ hint }}</p>
  </div>
</template>
```

## Empty State Pattern

```vue
<template>
  <div class="flex flex-col items-center justify-center py-12 text-center">
    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-800">
      <component :is="icon" class="h-6 w-6 text-neutral-400" />
    </div>
    <h3 class="mt-4 text-sm font-medium text-(--text-primary)">{{ title }}</h3>
    <p class="mt-1 max-w-sm text-sm text-(--text-secondary)">{{ description }}</p>
    <div v-if="$slots.action" class="mt-4">
      <slot name="action" />
    </div>
  </div>
</template>
```

Usage:

```vue
<EmptyState
  :icon="Users"
  title="Belum ada data siswa"
  description="Tambahkan siswa satu per satu atau import dari file Excel."
>
  <template #action>
    <Button>Tambah Siswa</Button>
  </template>
</EmptyState>
```

## DataTable Responsive Pattern

Desktop: standard table. Mobile: card list.

```vue
<template>
  <!-- Desktop table -->
  <div class="hidden overflow-x-auto md:block">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-(--border-default)">
          <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-(--text-secondary)">
            Nama
          </th>
          <!-- ... -->
        </tr>
      </thead>
      <tbody class="divide-y divide-(--border-default)">
        <tr v-for="item in data" :key="item.id"
            class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
          <td class="px-4 py-3 text-(--text-primary)">{{ item.name }}</td>
          <!-- ... -->
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Mobile cards -->
  <div class="space-y-3 md:hidden">
    <div v-for="item in data" :key="item.id"
         class="rounded-lg border border-(--border-default) bg-(--bg-card) p-3">
      <div class="flex items-center justify-between">
        <span class="text-sm font-medium text-(--text-primary)">{{ item.name }}</span>
        <StatusBadge :variant="item.statusVariant" :label="item.statusLabel" />
      </div>
      <div class="mt-2 flex items-center gap-4 text-xs text-(--text-secondary)">
        <span>{{ item.class }}</span>
        <span>{{ item.nisn }}</span>
      </div>
    </div>
  </div>
</template>
```

## Dialog / Modal Pattern

```vue
<template>
  <Dialog>
    <DialogContent class="rounded-xl bg-(--bg-card) p-0 sm:max-w-md">
      <!-- Header -->
      <div class="border-b border-(--border-default) px-6 py-4">
        <DialogTitle class="text-lg font-semibold text-(--text-primary)">
          {{ title }}
        </DialogTitle>
        <DialogDescription class="mt-1 text-sm text-(--text-secondary)">
          {{ description }}
        </DialogDescription>
      </div>

      <!-- Body -->
      <div class="px-6 py-4">
        <slot />
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-end gap-3 border-t border-(--border-default) px-6 py-4">
        <DialogClose as-child>
          <Button variant="outline">Batal</Button>
        </DialogClose>
        <Button @click="confirm">{{ confirmLabel }}</Button>
      </div>
    </DialogContent>
  </Dialog>
</template>
```

### Destructive Confirmation Dialog

```vue
<ConfirmDialog
  title="Hapus Siswa"
  description="Data siswa akan dihapus permanen. Tindakan ini tidak dapat dibatalkan."
  confirm-label="Hapus"
  variant="danger"
/>
```

Use `danger` variant: red confirm button, warning icon.

## Toast / Flash Message Pattern

Position: top-right (desktop), top-center (mobile). Auto-dismiss 5s.

```vue
<!-- Success toast -->
<div class="flex items-center gap-3 rounded-lg border border-success-200 bg-success-50 px-4 py-3 dark:border-success-800 dark:bg-success-950">
  <CheckCircle class="h-5 w-5 text-success-600 dark:text-success-400" />
  <p class="text-sm font-medium text-success-800 dark:text-success-200">{{ message }}</p>
</div>
```

## Sidebar Navigation Pattern

```vue
<template>
  <nav class="space-y-1">
    <a v-for="item in items" :key="item.href"
       :href="item.href"
       :class="[
         'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
         item.active
           ? 'bg-primary-50 text-primary-700 dark:bg-primary-950 dark:text-primary-400'
           : 'text-(--text-secondary) hover:bg-neutral-100 hover:text-(--text-primary) dark:hover:bg-neutral-800'
       ]">
      <component :is="item.icon" class="h-5 w-5" />
      {{ item.label }}
      <span v-if="item.badge"
            class="ml-auto rounded-full bg-danger-100 px-2 py-0.5 text-xs font-medium text-danger-700 dark:bg-danger-950 dark:text-danger-400">
        {{ item.badge }}
      </span>
    </a>
  </nav>
</template>
```

## Bottom Navigation (Parent Mobile)

```vue
<template>
  <nav class="fixed inset-x-0 bottom-0 z-50 flex items-center justify-around border-t border-(--border-default) bg-(--bg-card) px-2 pb-safe">
    <a v-for="tab in tabs" :key="tab.href" :href="tab.href"
       :class="[
         'flex min-h-[56px] flex-1 flex-col items-center justify-center gap-1 text-xs font-medium',
         tab.active
           ? 'text-primary-600 dark:text-primary-400'
           : 'text-(--text-secondary)'
       ]">
      <component :is="tab.icon" class="h-5 w-5" />
      <span>{{ tab.label }}</span>
    </a>
  </nav>
</template>
```

Bottom nav specs:
- Height: 56px + safe area inset (`pb-safe`)
- Max 5 tabs
- Active indicator: primary color on icon + label
- No labels truncation — keep labels short (1-2 words Bahasa)

## CurrencyDisplay Pattern

```vue
<template>
  <span :class="[
    'tabular-nums',
    negative ? 'text-danger-600 dark:text-danger-400' : ''
  ]">
    {{ formatted }}
  </span>
</template>

<script setup lang="ts">
import { useCurrency } from '@/Composables/useCurrency'
const { format } = useCurrency()
const formatted = computed(() => format(props.amount))
</script>
```

Always use `tabular-nums` for currency values to ensure columns align.

## Page Layout Template

Standard page structure used across all admin/teacher pages:

```vue
<template>
  <Head :title="pageTitle" />

  <div class="space-y-6">
    <!-- Page header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-bold text-(--text-primary)">{{ pageTitle }}</h1>
        <p v-if="pageDescription" class="mt-1 text-sm text-(--text-secondary)">
          {{ pageDescription }}
        </p>
      </div>
      <div class="flex items-center gap-3">
        <slot name="actions" />
      </div>
    </div>

    <!-- Page content -->
    <slot />
  </div>
</template>
```

## Skeleton Loading Pattern

Match the shape of the content being loaded:

```vue
<!-- Text skeleton -->
<div class="h-4 w-3/4 animate-pulse rounded bg-neutral-200 dark:bg-neutral-700" />

<!-- Card skeleton -->
<div class="rounded-lg border border-(--border-default) p-4">
  <div class="flex items-center gap-3">
    <div class="h-10 w-10 animate-pulse rounded-lg bg-neutral-200 dark:bg-neutral-700" />
    <div class="space-y-2">
      <div class="h-3 w-20 animate-pulse rounded bg-neutral-200 dark:bg-neutral-700" />
      <div class="h-5 w-16 animate-pulse rounded bg-neutral-200 dark:bg-neutral-700" />
    </div>
  </div>
</div>

<!-- Table row skeleton -->
<tr v-for="i in 5" :key="i">
  <td class="px-4 py-3">
    <div class="h-4 w-32 animate-pulse rounded bg-neutral-200 dark:bg-neutral-700" />
  </td>
</tr>
```

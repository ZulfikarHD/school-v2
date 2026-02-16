<script setup lang="ts">
/**
 * StatusBadge — badge berwarna berdasarkan status semantik.
 *
 * Digunakan di seluruh aplikasi untuk menampilkan status pembayaran,
 * kehadiran, nilai, dan status siswa secara konsisten.
 */
import type { HTMLAttributes } from "vue"
import { computed } from "vue"
import { cn } from "@/lib/utils"

export type StatusVariant =
  | "success"
  | "warning"
  | "danger"
  | "info"
  | "muted"

const props = withDefaults(defineProps<{
  /** Varian warna semantik */
  variant?: StatusVariant
  /** Label teks yang ditampilkan */
  label: string
  class?: HTMLAttributes["class"]
}>(), {
  variant: "muted",
})

const variantClasses = computed(() => {
  const map: Record<StatusVariant, string> = {
    success: "bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-400",
    warning: "bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-400",
    danger: "bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-400",
    info: "bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-400",
    muted: "bg-muted text-muted-foreground",
  }
  return map[props.variant]
})
</script>

<template>
  <span
    data-slot="status-badge"
    :class="cn(
      'inline-flex items-center rounded-sm px-2 py-0.5 text-xs font-medium',
      variantClasses,
      props.class,
    )"
  >
    {{ label }}
  </span>
</template>

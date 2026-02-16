<script setup lang="ts">
/**
 * DateDisplay — menampilkan tanggal dalam format Indonesia (WIB).
 *
 * Menggunakan dayjs dengan locale `id` dan timezone `Asia/Jakarta`.
 * Format default: "12 Februari 2026".
 */
import { computed } from "vue"
import dayjs from "dayjs"
import "dayjs/locale/id"
import relativeTime from "dayjs/plugin/relativeTime"
import timezone from "dayjs/plugin/timezone"
import utc from "dayjs/plugin/utc"

dayjs.extend(utc)
dayjs.extend(timezone)
dayjs.extend(relativeTime)
dayjs.locale("id")

const props = withDefaults(defineProps<{
  /** ISO date string atau timestamp */
  date: string | Date
  /** Format dayjs — default "D MMMM YYYY" */
  format?: string
  /** Tampilkan waktu relatif (mis. "2 jam yang lalu") */
  relative?: boolean
  /** Tampilkan juga waktu (jam:menit WIB) */
  withTime?: boolean
}>(), {
  format: "D MMMM YYYY",
  relative: false,
  withTime: false,
})

const formatted = computed(() => {
  const d = dayjs(props.date).tz("Asia/Jakarta")

  if (props.relative) {
    return d.fromNow()
  }

  const fmt = props.withTime ? `${props.format} HH:mm [WIB]` : props.format
  return d.format(fmt)
})
</script>

<template>
  <time data-slot="date-display" :datetime="String(date)">
    {{ formatted }}
  </time>
</template>

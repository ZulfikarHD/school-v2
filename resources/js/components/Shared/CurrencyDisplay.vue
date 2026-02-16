<script setup lang="ts">
/**
 * CurrencyDisplay — menampilkan angka dalam format Rupiah (Rp).
 *
 * Menggunakan composable useCurrency untuk formatting yang konsisten.
 * Mendukung compact mode untuk angka besar (1.5 Jt, 2.3 M).
 */
import { computed } from "vue"
import { useCurrency } from "@/composables/useCurrency"

const props = withDefaults(defineProps<{
  /** Nilai dalam satuan terkecil (rupiah) */
  amount: number
  /** Compact format untuk angka besar (1.5 Jt) */
  compact?: boolean
}>(), {
  compact: false,
})

const { formatRupiah, formatCompact } = useCurrency()

const formatted = computed(() =>
  props.compact ? formatCompact(props.amount) : formatRupiah(props.amount),
)
</script>

<template>
  <span data-slot="currency-display">{{ formatted }}</span>
</template>

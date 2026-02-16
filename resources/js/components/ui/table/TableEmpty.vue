<script setup lang="ts">
/**
 * TableEmpty — row placeholder ketika tabel tidak memiliki data.
 */
import type { HTMLAttributes } from "vue"
import { cn } from "@/lib/utils"

const props = withDefaults(defineProps<{
  colspan?: number
  class?: HTMLAttributes["class"]
}>(), {
  colspan: 1,
})
</script>

<template>
  <tr data-slot="table-empty">
    <td
      :colspan="props.colspan"
      :class="cn('h-24 text-center text-muted-foreground', props.class)"
    >
      <slot>
        Tidak ada data.
      </slot>
    </td>
  </tr>
</template>

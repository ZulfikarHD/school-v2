<script setup lang="ts">
/**
 * DataTableColumnHeader — header kolom yang bisa di-sort.
 *
 * Menampilkan arrow indicator sesuai arah sorting aktif.
 * Klik toggle antara asc → desc → unsorted.
 */
import type { Column } from "@tanstack/vue-table"
import { ArrowDown, ArrowUp, ChevronsUpDown } from "lucide-vue-next"
import type { HTMLAttributes } from "vue"
import { Button } from "@/components/ui/button"
import { cn } from "@/lib/utils"

const props = defineProps<{
  /** TanStack Column instance */
  column: Column<unknown, unknown>
  /** Judul kolom */
  title: string
  class?: HTMLAttributes["class"]
}>()
</script>

<template>
  <div
    v-if="!column.getCanSort()"
    :class="cn('flex items-center', props.class)"
  >
    {{ title }}
  </div>

  <Button
    v-else
    variant="ghost"
    size="sm"
    :class="cn('-ml-3 h-8 data-[state=open]:bg-accent', props.class)"
    @click="column.toggleSorting()"
  >
    <span>{{ title }}</span>
    <ArrowDown
      v-if="column.getIsSorted() === 'desc'"
      class="ml-1 size-3.5"
    />
    <ArrowUp
      v-else-if="column.getIsSorted() === 'asc'"
      class="ml-1 size-3.5"
    />
    <ChevronsUpDown
      v-else
      class="ml-1 size-3.5"
    />
  </Button>
</template>

<script setup lang="ts">
/**
 * DataTablePagination — navigasi halaman yang bekerja dengan Laravel paginator.
 *
 * Menampilkan info "Menampilkan X–Y dari Z", tombol prev/next,
 * dan select per-page. Responsive: simplified di mobile.
 */
import {
  ChevronLeft,
  ChevronRight,
  ChevronsLeft,
  ChevronsRight,
} from "lucide-vue-next"
import type { AcceptableValue } from "reka-ui"
import { computed } from "vue"
import { Button } from "@/components/ui/button"
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import type { PaginationMeta } from "./types"

const props = withDefaults(defineProps<{
  /** Pagination meta dari Laravel paginator */
  meta: PaginationMeta
  /** Opsi jumlah per halaman */
  perPageOptions?: number[]
  /** Jumlah row yang dipilih (untuk bulk selection info) */
  selectedCount?: number
  /** Total row di halaman ini */
  pageRowCount?: number
}>(), {
  perPageOptions: () => [10, 25, 50, 100],
  selectedCount: 0,
  pageRowCount: 0,
})

const emit = defineEmits<{
  (e: "page-change", page: number): void
  (e: "per-page-change", perPage: number): void
}>()

const isFirstPage = computed(() => props.meta.current_page === 1)
const isLastPage = computed(() => props.meta.current_page === props.meta.last_page)

function goToPage(page: number): void {
  if (page >= 1 && page <= props.meta.last_page) {
    emit("page-change", page)
  }
}

function handlePerPageChange(value: AcceptableValue): void {
  emit("per-page-change", Number(value))
}
</script>

<template>
  <div
    data-slot="data-table-pagination"
    class="flex flex-col gap-3 px-2 py-4 sm:flex-row sm:items-center sm:justify-between"
  >
    <!-- Info kiri: selected count / showing info -->
    <div class="text-muted-foreground text-sm">
      <template v-if="selectedCount > 0">
        {{ selectedCount }} dari {{ pageRowCount }} baris dipilih.
      </template>
      <template v-else-if="meta.from && meta.to">
        Menampilkan {{ meta.from }}–{{ meta.to }} dari {{ meta.total }} data.
      </template>
      <template v-else>
        {{ meta.total }} data.
      </template>
    </div>

    <!-- Kontrol kanan -->
    <div class="flex items-center gap-4">
      <!-- Per page selector -->
      <div class="hidden items-center gap-2 sm:flex">
        <span class="text-muted-foreground text-sm">Per halaman</span>
        <Select
          :model-value="String(meta.per_page)"
          @update:model-value="handlePerPageChange"
        >
          <SelectTrigger class="h-8 w-18">
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem
              v-for="option in perPageOptions"
              :key="option"
              :value="String(option)"
            >
              {{ option }}
            </SelectItem>
          </SelectContent>
        </Select>
      </div>

      <!-- Page info -->
      <span class="text-muted-foreground text-sm">
        Hal. {{ meta.current_page }} / {{ meta.last_page }}
      </span>

      <!-- Navigation buttons -->
      <div class="flex items-center gap-1">
        <Button
          variant="outline"
          size="icon-sm"
          :disabled="isFirstPage"
          @click="goToPage(1)"
        >
          <ChevronsLeft class="size-4" />
          <span class="sr-only">Halaman pertama</span>
        </Button>
        <Button
          variant="outline"
          size="icon-sm"
          :disabled="isFirstPage"
          @click="goToPage(meta.current_page - 1)"
        >
          <ChevronLeft class="size-4" />
          <span class="sr-only">Halaman sebelumnya</span>
        </Button>
        <Button
          variant="outline"
          size="icon-sm"
          :disabled="isLastPage"
          @click="goToPage(meta.current_page + 1)"
        >
          <ChevronRight class="size-4" />
          <span class="sr-only">Halaman berikutnya</span>
        </Button>
        <Button
          variant="outline"
          size="icon-sm"
          :disabled="isLastPage"
          @click="goToPage(meta.last_page)"
        >
          <ChevronsRight class="size-4" />
          <span class="sr-only">Halaman terakhir</span>
        </Button>
      </div>
    </div>
  </div>
</template>

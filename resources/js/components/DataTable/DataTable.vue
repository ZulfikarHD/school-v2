<script setup lang="ts" generic="TData extends Record<string, unknown>">
/**
 * DataTable — reusable data table berbasis TanStack Table (headless).
 *
 * Digunakan di 20+ halaman aplikasi. Fitur utama:
 * - Server-side pagination via Inertia (Laravel paginator meta)
 * - Sortable columns dengan sort indicator
 * - Filterable dengan search input (debounced)
 * - Bulk action support (select rows, apply action)
 * - Responsive: table di desktop, card list di mobile
 * - Empty state otomatis ketika tidak ada data
 *
 * @example
 * <DataTable
 *   :data="students.data"
 *   :columns="columns"
 *   :pagination="students.meta"
 *   searchable
 *   :search-value="filters.search"
 *   :bulk-actions="[{ key: 'export', label: 'Export' }]"
 *   @page-change="(page) => router.get(url(), { page })"
 *   @search="(q) => router.get(url(), { search: q })"
 * />
 */
import {
  FlexRender,
  getCoreRowModel,
  getSortedRowModel,
  useVueTable,
} from "@tanstack/vue-table"
import type { ColumnDef, SortingState, RowSelectionState } from "@tanstack/vue-table"

import { ChevronDown } from "lucide-vue-next"
import { computed, ref } from "vue"
import { EmptyState } from "@/components/Shared"
import { Button } from "@/components/ui/button"
import { Checkbox } from "@/components/ui/checkbox"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
  TableEmpty,
} from "@/components/ui/table"

import DataTableFilter from "./DataTableFilter.vue"
import DataTablePagination from "./DataTablePagination.vue"
import type { PaginationMeta, BulkAction } from "./types"

const props = withDefaults(defineProps<{
  /** Data array untuk tabel */
  data: TData[]
  /** Definisi kolom TanStack Table */
  columns: ColumnDef<TData, unknown>[]
  /** Pagination meta dari Laravel paginator */
  pagination?: PaginationMeta
  /** Tampilkan search input */
  searchable?: boolean
  /** Nilai search awal */
  searchValue?: string
  /** Placeholder search */
  searchPlaceholder?: string
  /** Daftar bulk actions yang tersedia */
  bulkActions?: BulkAction[]
  /** Key field untuk identifikasi row (default: 'id') */
  rowKey?: string
  /** Pesan empty state */
  emptyTitle?: string
  /** Deskripsi empty state */
  emptyDescription?: string
}>(), {
  pagination: undefined,
  searchable: false,
  searchValue: "",
  searchPlaceholder: "Cari...",
  bulkActions: () => [],
  rowKey: "id",
  emptyTitle: "Tidak ada data",
  emptyDescription: undefined,
})

const emit = defineEmits<{
  (e: "page-change", page: number): void
  (e: "sort-change", sort: { column: string; direction: "asc" | "desc" } | null): void
  (e: "search", query: string): void
  (e: "bulk-action", action: string, selectedIds: unknown[]): void
  (e: "per-page-change", perPage: number): void
}>()

const sorting = ref<SortingState>([])
const rowSelection = ref<RowSelectionState>({})

const hasBulkActions = computed(() => props.bulkActions.length > 0)

const selectColumn: ColumnDef<TData, unknown> = {
  id: "select",
  header: ({ table }) => {
    return {
      component: Checkbox,
      props: {
        checked: table.getIsAllPageRowsSelected()
          || (table.getIsSomePageRowsSelected() && "indeterminate"),
        "onUpdate:checked": (value: boolean) =>
          table.toggleAllPageRowsSelected(!!value),
        ariaLabel: "Pilih semua",
      },
    }
  },
  cell: ({ row }) => {
    return {
      component: Checkbox,
      props: {
        checked: row.getIsSelected(),
        "onUpdate:checked": (value: boolean) => row.toggleSelected(!!value),
        ariaLabel: "Pilih baris",
      },
    }
  },
  enableSorting: false,
  enableHiding: false,
  size: 40,
}

const tableColumns = computed<ColumnDef<TData, unknown>[]>(() => {
  if (hasBulkActions.value) {
    return [selectColumn, ...props.columns]
  }
  return props.columns
})

const table = useVueTable({
  get data() {
    return props.data
  },
  get columns() {
    return tableColumns.value
  },
  getCoreRowModel: getCoreRowModel(),
  getSortedRowModel: getSortedRowModel(),
  manualPagination: true,
  manualSorting: true,
  state: {
    get sorting() {
      return sorting.value
    },
    get rowSelection() {
      return rowSelection.value
    },
  },
  onSortingChange: (updaterOrValue) => {
    const newSorting = typeof updaterOrValue === "function"
      ? updaterOrValue(sorting.value)
      : updaterOrValue

    sorting.value = newSorting

    if (newSorting.length > 0) {
      emit("sort-change", {
        column: newSorting[0].id,
        direction: newSorting[0].desc ? "desc" : "asc",
      })
    } else {
      emit("sort-change", null)
    }
  },
  onRowSelectionChange: (updaterOrValue) => {
    rowSelection.value = typeof updaterOrValue === "function"
      ? updaterOrValue(rowSelection.value)
      : updaterOrValue
  },
  getRowId: (row) => String((row as Record<string, unknown>)[props.rowKey]),
})

const selectedRowIds = computed(() => {
  return Object.keys(rowSelection.value).filter(
    (key) => rowSelection.value[key],
  )
})

function handleBulkAction(actionKey: string): void {
  emit("bulk-action", actionKey, selectedRowIds.value)
}

function handleSearch(query: string): void {
  emit("search", query)
}

function handlePageChange(page: number): void {
  emit("page-change", page)
}

function handlePerPageChange(perPage: number): void {
  emit("per-page-change", perPage)
}
</script>

<template>
  <div data-slot="data-table" class="space-y-4">
    <!-- Toolbar: search + bulk actions -->
    <div
      v-if="searchable || hasBulkActions"
      class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
    >
      <DataTableFilter
        v-if="searchable"
        :model-value="searchValue"
        :placeholder="searchPlaceholder"
        @search="handleSearch"
      />

      <!-- Bulk actions dropdown -->
      <div v-if="hasBulkActions && selectedRowIds.length > 0" class="flex items-center gap-2">
        <span class="text-muted-foreground text-sm">
          {{ selectedRowIds.length }} dipilih
        </span>
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="outline" size="sm">
              Aksi
              <ChevronDown class="ml-1 size-3.5" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end">
            <DropdownMenuItem
              v-for="action in bulkActions"
              :key="action.key"
              :class="action.variant === 'destructive' ? 'text-destructive' : ''"
              @click="handleBulkAction(action.key)"
            >
              {{ action.label }}
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>

      <slot name="toolbar" />
    </div>

    <!-- Desktop: Table view (hidden on mobile) -->
    <div class="hidden rounded-lg border md:block">
      <Table>
        <TableHeader>
          <TableRow
            v-for="headerGroup in table.getHeaderGroups()"
            :key="headerGroup.id"
          >
            <TableHead
              v-for="header in headerGroup.headers"
              :key="header.id"
              :style="{ width: header.getSize() !== 150 ? `${header.getSize()}px` : undefined }"
            >
              <template v-if="!header.isPlaceholder">
                <FlexRender
                  :render="header.column.columnDef.header"
                  :props="header.getContext()"
                />
              </template>
            </TableHead>
          </TableRow>
        </TableHeader>

        <TableBody>
          <template v-if="table.getRowModel().rows.length">
            <TableRow
              v-for="row in table.getRowModel().rows"
              :key="row.id"
              :data-state="row.getIsSelected() ? 'selected' : undefined"
            >
              <TableCell
                v-for="cell in row.getVisibleCells()"
                :key="cell.id"
              >
                <FlexRender
                  :render="cell.column.columnDef.cell"
                  :props="cell.getContext()"
                />
              </TableCell>
            </TableRow>
          </template>
          <TableEmpty
            v-else
            :colspan="tableColumns.length"
          >
            <EmptyState
              :title="emptyTitle"
              :description="emptyDescription"
              class="py-8"
            >
              <template v-if="$slots['empty-action']" #action>
                <slot name="empty-action" />
              </template>
            </EmptyState>
          </TableEmpty>
        </TableBody>
      </Table>
    </div>

    <!-- Mobile: Card view (visible on mobile only) -->
    <div class="space-y-3 md:hidden">
      <template v-if="data.length > 0">
        <div
          v-for="(item, index) in data"
          :key="(item as Record<string, unknown>)[rowKey] as string ?? index"
          class="rounded-lg border bg-card p-4"
        >
          <slot name="mobile-card" :item="item" :index="index">
            <!-- Default mobile card: tampilkan semua field -->
            <div class="space-y-2">
              <div
                v-for="col in columns.filter(c => c.id !== 'select' && c.id !== 'actions')"
                :key="String(col.id ?? col.header)"
                class="flex items-start justify-between gap-2 text-sm"
              >
                <span class="text-muted-foreground shrink-0">
                  {{ typeof col.header === 'string' ? col.header : col.id }}
                </span>
                <span class="text-right font-medium">
                  {{ (item as Record<string, unknown>)[String(col.id ?? (col as { accessorKey?: string }).accessorKey)] }}
                </span>
              </div>
            </div>
          </slot>
        </div>
      </template>
      <EmptyState
        v-else
        :title="emptyTitle"
        :description="emptyDescription"
      >
        <template v-if="$slots['empty-action']" #action>
          <slot name="empty-action" />
        </template>
      </EmptyState>
    </div>

    <!-- Pagination -->
    <DataTablePagination
      v-if="pagination && pagination.last_page > 1"
      :meta="pagination"
      :selected-count="selectedRowIds.length"
      :page-row-count="data.length"
      @page-change="handlePageChange"
      @per-page-change="handlePerPageChange"
    />
  </div>
</template>

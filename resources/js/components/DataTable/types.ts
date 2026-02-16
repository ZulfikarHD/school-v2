/**
 * Types untuk DataTable — mendefinisikan contract antara
 * Laravel paginator meta dan TanStack Table frontend.
 */

/**
 * Meta pagination dari Laravel paginator (via Inertia).
 *
 * Sesuai dengan LengthAwarePaginator::toArray() format.
 */
export interface PaginationMeta {
    current_page: number
    from: number | null
    last_page: number
    per_page: number
    to: number | null
    total: number
    path: string
    links: PaginationLink[]
}

export interface PaginationLink {
    url: string | null
    label: string
    active: boolean
}

/**
 * Props sorting yang dikirim ke server via Inertia.
 */
export interface SortingState {
    column: string
    direction: "asc" | "desc"
}

/**
 * Config untuk bulk action.
 */
export interface BulkAction {
    /** Unique key untuk action */
    key: string
    /** Label yang ditampilkan */
    label: string
    /** Icon component (opsional) */
    icon?: unknown
    /** Variant warna */
    variant?: "default" | "destructive"
}

/**
 * Event yang di-emit DataTable ke parent.
 */
export interface DataTableEmits {
    (e: "page-change", page: number): void
    (e: "sort-change", sort: SortingState | null): void
    (e: "search", query: string): void
    (e: "bulk-action", action: string, selectedIds: unknown[]): void
    (e: "per-page-change", perPage: number): void
}

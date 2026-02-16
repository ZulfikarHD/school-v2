<script setup lang="ts">
/**
 * DataTableFilter — search input dengan debounce untuk filtering data.
 *
 * Emit event `search` setelah user berhenti mengetik (300ms debounce).
 * Mendukung clear button dan keyboard shortcut.
 */
import { useDebounceFn } from "@vueuse/core"
import { Search, X } from "lucide-vue-next"
import { ref, watch } from "vue"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"

const props = withDefaults(defineProps<{
  /** Nilai search awal (dari query param) */
  modelValue?: string
  /** Placeholder teks */
  placeholder?: string
  /** Debounce delay dalam ms */
  debounce?: number
}>(), {
  modelValue: "",
  placeholder: "Cari...",
  debounce: 300,
})

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void
  (e: "search", value: string): void
}>()

const localValue = ref(props.modelValue)

const debouncedSearch = useDebounceFn((value: string) => {
  emit("search", value)
}, props.debounce)

watch(localValue, (value) => {
  emit("update:modelValue", value)
  debouncedSearch(value)
})

watch(() => props.modelValue, (value) => {
  localValue.value = value
})

function clearSearch(): void {
  localValue.value = ""
  emit("search", "")
}
</script>

<template>
  <div data-slot="data-table-filter" class="relative w-full max-w-sm">
    <Search class="text-muted-foreground pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2" />
    <Input
      v-model="localValue"
      :placeholder="placeholder"
      class="pl-9 pr-8"
    />
    <Button
      v-if="localValue"
      variant="ghost"
      size="icon-sm"
      class="absolute right-1 top-1/2 size-6 -translate-y-1/2"
      @click="clearSearch"
    >
      <X class="size-3.5" />
      <span class="sr-only">Hapus pencarian</span>
    </Button>
  </div>
</template>

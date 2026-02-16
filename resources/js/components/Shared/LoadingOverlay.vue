<script setup lang="ts">
/**
 * LoadingOverlay — overlay semi-transparan dengan spinner.
 *
 * Digunakan di atas konten yang sedang loading (tabel, card, dll).
 * Mencegah interaksi user saat data sedang diproses.
 */
import { Spinner } from "@/components/ui/spinner"

withDefaults(defineProps<{
  /** Tampilkan/sembunyikan overlay */
  show?: boolean
  /** Teks loading opsional */
  text?: string
}>(), {
  show: false,
  text: undefined,
})
</script>

<template>
  <Transition
    enter-active-class="transition-opacity duration-150"
    leave-active-class="transition-opacity duration-150"
    enter-from-class="opacity-0"
    leave-to-class="opacity-0"
  >
    <div
      v-if="show"
      data-slot="loading-overlay"
      class="bg-background/60 absolute inset-0 z-10 flex flex-col items-center justify-center gap-2 backdrop-blur-[1px]"
    >
      <Spinner class="size-6" />
      <p v-if="text" class="text-muted-foreground text-sm">
        {{ text }}
      </p>
    </div>
  </Transition>
</template>

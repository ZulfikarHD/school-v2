<script setup lang="ts">
/**
 * EmptyState — placeholder ketika tidak ada data yang ditampilkan.
 *
 * Mengikuti design system: centered, icon 24px muted, title text-sm font-medium,
 * description text-sm text-secondary, optional CTA button.
 */
import { InboxIcon } from "lucide-vue-next"
import type { Component, HTMLAttributes } from "vue"
import { cn } from "@/lib/utils"

withDefaults(defineProps<{
  /** Judul utama empty state */
  title?: string
  /** Deskripsi tambahan */
  description?: string
  /** Custom icon component (default: InboxIcon) */
  icon?: Component
  class?: HTMLAttributes["class"]
}>(), {
  title: "Tidak ada data",
  description: undefined,
  icon: undefined,
})
</script>

<template>
  <div
    data-slot="empty-state"
    :class="cn(
      'flex flex-col items-center justify-center py-12 text-center',
      $attrs.class as string,
    )"
  >
    <component
      :is="icon ?? InboxIcon"
      class="text-muted-foreground mb-3 size-10 stroke-1"
    />

    <p class="text-sm font-medium">
      {{ title }}
    </p>

    <p
      v-if="description"
      class="text-muted-foreground mt-1 max-w-sm text-sm"
    >
      {{ description }}
    </p>

    <div v-if="$slots.action" class="mt-4">
      <slot name="action" />
    </div>
  </div>
</template>

<script setup lang="ts">
/**
 * FormField — wrapper untuk field form yang menyatukan Label, Input, dan Error message.
 *
 * Menghasilkan konsistensi spacing dan accessibility (htmlFor + aria-describedby)
 * di seluruh form aplikasi.
 */
import type { HTMLAttributes } from "vue"
import { computed, useId } from "vue"
import { Label } from "@/components/ui/label"
import { cn } from "@/lib/utils"

const props = withDefaults(defineProps<{
  /** Label teks untuk field */
  label?: string
  /** Pesan error dari validasi (biasanya dari Inertia form.errors) */
  error?: string
  /** HTML id untuk input — auto-generated jika tidak diberikan */
  htmlFor?: string
  /** Apakah field required */
  required?: boolean
  /** Hint text di bawah input */
  hint?: string
  class?: HTMLAttributes["class"]
}>(), {
  required: false,
})

const fieldId = computed(() => props.htmlFor ?? useId())
const errorId = computed(() => `${fieldId.value}-error`)
const hintId = computed(() => `${fieldId.value}-hint`)

const describedBy = computed(() => {
  const ids: string[] = []
  if (props.error) {
    ids.push(errorId.value)
  }
  if (props.hint) {
    ids.push(hintId.value)
  }
  return ids.length > 0 ? ids.join(" ") : undefined
})
</script>

<template>
  <div :class="cn('space-y-2', props.class)">
    <Label
      v-if="label"
      :for="fieldId"
      class="text-sm font-medium"
    >
      {{ label }}
      <span v-if="required" class="text-destructive ml-0.5">*</span>
    </Label>

    <slot
      :id="fieldId"
      :error="error"
      :aria-invalid="!!error"
      :aria-describedby="describedBy"
    />

    <p
      v-if="hint && !error"
      :id="hintId"
      class="text-muted-foreground text-xs"
    >
      {{ hint }}
    </p>

    <p
      v-if="error"
      :id="errorId"
      class="text-destructive text-xs"
      role="alert"
    >
      {{ error }}
    </p>
  </div>
</template>

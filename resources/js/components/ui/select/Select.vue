<script setup lang="ts">
/**
 * Select — root wrapper untuk Reka UI SelectRoot.
 */
import { SelectRoot, useForwardPropsEmits } from "reka-ui"
import type { SelectRootEmits, SelectRootProps } from "reka-ui"

const props = defineProps<SelectRootProps>()
const emits = defineEmits<SelectRootEmits>()

const forwarded = useForwardPropsEmits(props, emits)
</script>

<template>
  <SelectRoot v-bind="forwarded">
    <slot />
  </SelectRoot>
</template>

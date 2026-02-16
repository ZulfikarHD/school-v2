<script setup lang="ts">
/**
 * AppLogo — Menampilkan logo dan nama sekolah di sidebar header.
 *
 * Jika school data tersedia (dari Inertia shared props), tampilkan
 * logo thumbnail + nama sekolah. Fallback ke app icon + app name.
 */
import { usePage } from '@inertiajs/vue3';
import { Building2 } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const page = usePage();
const school = computed(() => page.props.school as { name: string; logo_thumbnail_url: string | null } | null);
const appName = computed(() => (page.props.name as string) || 'Sekolah');
</script>

<template>
    <div
        class="flex aspect-square size-8 items-center justify-center overflow-hidden rounded-md bg-sidebar-primary text-sidebar-primary-foreground"
    >
        <img
            v-if="school?.logo_thumbnail_url"
            :src="school.logo_thumbnail_url"
            :alt="school.name"
            class="size-8 object-cover"
        />
        <Building2
            v-else-if="school"
            class="size-5"
        />
        <AppLogoIcon
            v-else
            class="size-5 fill-current text-white dark:text-black"
        />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold">
            {{ school?.name ?? appName }}
        </span>
    </div>
</template>

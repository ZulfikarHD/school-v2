<script setup lang="ts">
/**
 * ParentLayout — Layout untuk role orang tua dan siswa (mobile-first).
 *
 * Struktur: top bar + scrollable content + fixed bottom navigation.
 * Digunakan oleh: OrangTua, Siswa.
 * Dioptimalkan untuk budget Android phones dengan touch target besar.
 */
import BottomNav from '@/layouts/components/BottomNav.vue';
import TopBar from '@/layouts/components/TopBar.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div class="flex min-h-svh flex-col bg-background">
        <TopBar />

        <main class="flex-1 px-4 py-4 pb-20">
            <slot />
        </main>

        <BottomNav />
    </div>
</template>

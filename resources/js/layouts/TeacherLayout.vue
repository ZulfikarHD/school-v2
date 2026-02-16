<script setup lang="ts">
/**
 * TeacherLayout — Layout untuk role guru (tablet + desktop).
 *
 * Struktur: sidebar kiri (simplified, collapsible) + topbar dengan breadcrumbs.
 * Digunakan oleh: Guru, WaliKelas, GuruBk.
 * Dioptimalkan untuk tablet dan desktop, responsive ke mobile.
 */
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import TeacherSidebar from '@/layouts/components/TeacherSidebar.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <TeacherSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
    </AppShell>
</template>

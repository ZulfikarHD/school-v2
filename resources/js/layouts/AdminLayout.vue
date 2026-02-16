<script setup lang="ts">
/**
 * AdminLayout — Layout utama untuk role admin/staff (desktop-first).
 *
 * Struktur: sidebar kiri (collapsible) + topbar dengan breadcrumbs.
 * Digunakan oleh: SuperAdmin, AdminSekolah, KepalaSekolah, Bendahara.
 */
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import AdminSidebar from '@/layouts/components/AdminSidebar.vue';
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
        <AdminSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
    </AppShell>
</template>

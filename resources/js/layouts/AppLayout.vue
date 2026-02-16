<script setup lang="ts">
/**
 * AppLayout — Role-based layout resolver.
 *
 * Menentukan layout yang dirender berdasarkan `activeRole` dari Inertia shared props.
 * Menggunakan async component agar setiap layout di-code-split (separate chunk).
 *
 * Mapping:
 * - Admin/KepalaSekolah/Bendahara → AdminLayout (sidebar + topbar)
 * - Guru/WaliKelas/GuruBk → TeacherLayout (simplified sidebar)
 * - OrangTua/Siswa → ParentLayout (bottom nav)
 * - Default (null/unknown) → AdminLayout
 */
import { usePage } from '@inertiajs/vue3';
import { computed, defineAsyncComponent } from 'vue';
import type { BreadcrumbItem } from '@/types';
import { PARENT_LAYOUT_ROLES, TEACHER_LAYOUT_ROLES } from '@/types/enums';

const AdminLayout = defineAsyncComponent(
    () => import('@/layouts/AdminLayout.vue'),
);
const TeacherLayout = defineAsyncComponent(
    () => import('@/layouts/TeacherLayout.vue'),
);
const ParentLayout = defineAsyncComponent(
    () => import('@/layouts/ParentLayout.vue'),
);

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const activeRole = computed(() => page.props.activeRole);

const layoutComponent = computed(() => {
    const role = activeRole.value;

    if (role && PARENT_LAYOUT_ROLES.some((r) => r === role)) {
        return ParentLayout;
    }

    if (role && TEACHER_LAYOUT_ROLES.some((r) => r === role)) {
        return TeacherLayout;
    }

    return AdminLayout;
});
</script>

<template>
    <component :is="layoutComponent" :breadcrumbs="breadcrumbs">
        <slot />
    </component>
</template>

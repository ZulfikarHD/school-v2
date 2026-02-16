<script setup lang="ts">
/**
 * TeacherSidebar — Sidebar navigasi sederhana untuk role guru.
 *
 * Digunakan oleh TeacherLayout. Menampilkan modul yang relevan
 * untuk guru: Dashboard, Absensi, Nilai, Jadwal.
 */
import { Link } from '@inertiajs/vue3';
import {
    BookOpen,
    Calendar,
    ClipboardCheck,
    LayoutGrid,
    Users,
} from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Absensi',
        href: '/attendance',
        icon: ClipboardCheck,
    },
    {
        title: 'Nilai',
        href: '/grades',
        icon: BookOpen,
    },
    {
        title: 'Jadwal',
        href: '/schedule',
        icon: Calendar,
    },
    {
        title: 'Kelas Saya',
        href: '/my-class',
        icon: Users,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
</template>

<script setup lang="ts">
/**
 * AdminSidebar — Sidebar navigasi lengkap untuk role admin.
 *
 * Digunakan oleh AdminLayout. Menampilkan semua modul yang dapat
 * diakses admin: Dashboard, Siswa, Guru, Keuangan, Akademik, dll.
 */
import { Link } from '@inertiajs/vue3';
import {
    BookOpen,
    CreditCard,
    GraduationCap,
    LayoutGrid,
    Megaphone,
    Settings,
    Users,
} from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
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
        title: 'Siswa',
        href: '/students',
        icon: GraduationCap,
    },
    {
        title: 'Guru & Staff',
        href: '/teachers',
        icon: Users,
    },
    {
        title: 'Akademik',
        href: '/academic',
        icon: BookOpen,
    },
    {
        title: 'Keuangan',
        href: '/finance',
        icon: CreditCard,
    },
    {
        title: 'Pengumuman',
        href: '/announcements',
        icon: Megaphone,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Pengaturan',
        href: '/settings/profile',
        icon: Settings,
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
            <NavFooter :items="footerNavItems" class="hidden" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
</template>

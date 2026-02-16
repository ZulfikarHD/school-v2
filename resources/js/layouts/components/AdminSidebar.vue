<script setup lang="ts">
/**
 * AdminSidebar — Sidebar navigasi lengkap untuk role admin.
 *
 * Digunakan oleh AdminLayout. Menampilkan semua modul yang dapat
 * diakses admin: Dashboard, Siswa, Guru, Keuangan, Akademik, dll.
 */
import { Link, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    Building2,
    CreditCard,
    GraduationCap,
    LayoutGrid,
    Megaphone,
    Settings,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { edit as schoolProfileEdit } from '@/actions/App/Http/Controllers/SchoolProfile/SchoolProfileController';
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

const page = usePage();
const school = computed(() => page.props.school as { id: number } | null);

const mainNavItems = computed<NavItem[]>(() => [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    ...(school.value
        ? [
              {
                  title: 'Profil Sekolah',
                  href: schoolProfileEdit.url(school.value.id),
                  icon: Building2,
              },
          ]
        : []),
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
]);

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

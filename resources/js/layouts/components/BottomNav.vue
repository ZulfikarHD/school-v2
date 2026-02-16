<script setup lang="ts">
/**
 * BottomNav — Navigasi bawah untuk ParentLayout (mobile-first).
 *
 * 5 tab utama: Beranda, Anak, Tagihan, Info, Profil.
 * Touch target minimum 44x44px sesuai accessibility guidelines.
 * Fixed di bawah layar dengan safe area padding untuk notched phones.
 */
import { Link } from '@inertiajs/vue3';
import {
    Bell,
    Home,
    Receipt,
    User,
    Users,
} from 'lucide-vue-next';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const { isCurrentUrl } = useCurrentUrl();

const navItems: NavItem[] = [
    {
        title: 'Beranda',
        href: dashboard(),
        icon: Home,
    },
    {
        title: 'Anak',
        href: '/children',
        icon: Users,
    },
    {
        title: 'Tagihan',
        href: '/bills',
        icon: Receipt,
    },
    {
        title: 'Info',
        href: '/announcements',
        icon: Bell,
    },
    {
        title: 'Profil',
        href: '/settings/profile',
        icon: User,
    },
];
</script>

<template>
    <nav
        class="fixed inset-x-0 bottom-0 z-50 border-t border-border bg-background/95 backdrop-blur-sm supports-backdrop-filter:bg-background/60"
        role="navigation"
        aria-label="Navigasi utama"
    >
        <div
            class="flex h-16 items-center justify-around pb-[env(safe-area-inset-bottom,0px)]"
        >
            <Link
                v-for="item in navItems"
                :key="item.title"
                :href="item.href"
                class="flex min-h-11 min-w-11 flex-col items-center justify-center gap-0.5 rounded-lg px-3 py-1.5 transition-colors duration-150"
                :class="
                    isCurrentUrl(item.href)
                        ? 'text-primary'
                        : 'text-muted-foreground hover:text-foreground'
                "
            >
                <component :is="item.icon" class="size-5" />
                <span class="text-[10px] font-medium leading-tight">
                    {{ item.title }}
                </span>
            </Link>
        </div>
    </nav>
</template>

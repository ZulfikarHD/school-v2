<script setup lang="ts">
/**
 * TopBar — Header bar untuk ParentLayout (mobile-first).
 *
 * Menampilkan logo sekolah, nama aplikasi, dan user menu.
 * Dioptimalkan untuk tampilan mobile dengan touch target yang cukup besar.
 */
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';

const page = usePage();
const auth = computed(() => page.props.auth);
const appName = computed(() => (page.props.name as string) || 'Sekolah');
</script>

<template>
    <header
        class="sticky top-0 z-40 flex h-14 items-center justify-between border-b border-border bg-background/95 px-4 backdrop-blur-sm supports-backdrop-filter:bg-background/60"
    >
        <div class="flex items-center gap-2.5">
            <AppLogoIcon
                class="size-7 fill-current text-foreground"
            />
            <span class="text-sm font-semibold text-foreground">
                {{ appName }}
            </span>
        </div>

        <DropdownMenu>
            <DropdownMenuTrigger :as-child="true">
                <Button
                    variant="ghost"
                    size="icon"
                    class="relative size-10 rounded-full p-1"
                >
                    <Avatar class="size-8 overflow-hidden rounded-full">
                        <AvatarImage
                            v-if="auth.user.avatar"
                            :src="auth.user.avatar"
                            :alt="auth.user.name"
                        />
                        <AvatarFallback
                            class="rounded-full bg-neutral-200 text-xs font-semibold text-black dark:bg-neutral-700 dark:text-white"
                        >
                            {{ getInitials(auth.user?.name) }}
                        </AvatarFallback>
                    </Avatar>
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" class="w-56">
                <UserMenuContent :user="auth.user" />
            </DropdownMenuContent>
        </DropdownMenu>
    </header>
</template>

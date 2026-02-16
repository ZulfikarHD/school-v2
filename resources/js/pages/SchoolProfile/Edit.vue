<script setup lang="ts">
/**
 * SchoolProfile/Edit — Halaman edit profil identitas sekolah.
 *
 * Features: form field lengkap (nama, NPSN, alamat, kontak, visi/misi),
 * upload logo dengan preview, validasi real-time dari server.
 */
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Building2, ImagePlus, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { edit, update } from '@/actions/App/Http/Controllers/SchoolProfile/SchoolProfileController';
import { FormField } from '@/components/Forms';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

type SchoolData = {
    id: number;
    name: string;
    npsn: string;
    slug: string;
    address: string | null;
    phone: string | null;
    email: string | null;
    vision: string | null;
    mission: string | null;
    logo_url: string | null;
};

type Props = {
    school: SchoolData;
};

const props = defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Profil Sekolah', href: edit.url(props.school.id) },
];

const page = usePage();

const form = useForm({
    name: props.school.name,
    npsn: props.school.npsn,
    address: props.school.address ?? '',
    phone: props.school.phone ?? '',
    email: props.school.email ?? '',
    vision: props.school.vision ?? '',
    mission: props.school.mission ?? '',
    logo: null as File | null,
    remove_logo: false,
});

const logoPreview = ref<string | null>(props.school.logo_url);
const fileInput = ref<HTMLInputElement | null>(null);

const flashSuccess = computed(() => {
    const flash = page.props.flash as { success: string | null } | undefined;
    return flash?.success ?? null;
});

function onLogoChange(event: Event): void {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (!file) {
        return;
    }

    // Validasi client-side: max 2MB, format gambar
    if (file.size > 2 * 1024 * 1024) {
        form.setError('logo', 'Ukuran logo maksimal 2MB.');
        return;
    }

    if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
        form.setError('logo', 'Logo harus berupa file JPG, PNG, atau WebP.');
        return;
    }

    form.clearErrors('logo');
    form.logo = file;
    form.remove_logo = false;

    const reader = new FileReader();
    reader.onload = (e) => {
        logoPreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
}

function removeLogo(): void {
    form.logo = null;
    form.remove_logo = true;
    logoPreview.value = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
}

function triggerFileInput(): void {
    fileInput.value?.click();
}

function submit(): void {
    form.post(update.url(props.school.id), {
        forceFormData: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profil Sekolah" />

        <div class="mx-auto max-w-3xl space-y-6 px-4 py-6 lg:px-0">
            <!-- Page header -->
            <div>
                <h1 class="text-2xl font-bold text-foreground">
                    Profil Sekolah
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Kelola identitas sekolah yang ditampilkan di seluruh
                    platform.
                </p>
            </div>

            <!-- Success flash -->
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 -translate-y-1"
                leave-active-class="transition ease-in duration-150"
                leave-to-class="opacity-0 -translate-y-1"
            >
                <div
                    v-if="flashSuccess"
                    class="flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200"
                >
                    {{ flashSuccess }}
                </div>
            </Transition>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- Logo section -->
                <div
                    class="rounded-lg border border-border bg-card p-4 sm:p-6"
                >
                    <Heading
                        variant="small"
                        title="Logo Sekolah"
                        description="Upload logo sekolah (maks. 2MB, format JPG/PNG/WebP)"
                    />

                    <div class="mt-4 flex items-start gap-4">
                        <!-- Logo preview -->
                        <div
                            class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-lg border-2 border-dashed border-border bg-muted"
                        >
                            <img
                                v-if="logoPreview"
                                :src="logoPreview"
                                alt="Logo sekolah"
                                class="h-full w-full object-cover"
                            />
                            <Building2
                                v-else
                                class="h-8 w-8 text-muted-foreground"
                            />
                        </div>

                        <!-- Upload controls -->
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    @click="triggerFileInput"
                                >
                                    <ImagePlus class="mr-1.5 h-4 w-4" />
                                    {{
                                        logoPreview
                                            ? 'Ganti Logo'
                                            : 'Upload Logo'
                                    }}
                                </Button>
                                <Button
                                    v-if="logoPreview"
                                    type="button"
                                    variant="ghost"
                                    size="sm"
                                    class="text-destructive hover:text-destructive"
                                    @click="removeLogo"
                                >
                                    <Trash2 class="mr-1.5 h-4 w-4" />
                                    Hapus
                                </Button>
                            </div>
                            <p
                                v-if="form.errors.logo"
                                class="text-xs text-destructive"
                                role="alert"
                            >
                                {{ form.errors.logo }}
                            </p>
                        </div>

                        <input
                            ref="fileInput"
                            type="file"
                            accept="image/jpeg,image/png,image/webp"
                            class="hidden"
                            @change="onLogoChange"
                        />
                    </div>
                </div>

                <!-- Identity section -->
                <div
                    class="rounded-lg border border-border bg-card p-4 sm:p-6"
                >
                    <Heading
                        variant="small"
                        title="Identitas Sekolah"
                        description="Informasi dasar yang ditampilkan di header dan dokumen"
                    />

                    <div
                        class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2"
                    >
                        <FormField
                            label="Nama Sekolah"
                            :error="form.errors.name"
                            required
                        >
                            <Input
                                v-model="form.name"
                                placeholder="Contoh: SD Negeri 5 Bandung"
                            />
                        </FormField>

                        <FormField
                            label="NPSN"
                            :error="form.errors.npsn"
                            required
                            hint="8 digit angka"
                        >
                            <Input
                                v-model="form.npsn"
                                placeholder="Contoh: 20219876"
                                maxlength="8"
                                inputmode="numeric"
                            />
                        </FormField>
                    </div>
                </div>

                <!-- Contact section -->
                <div
                    class="rounded-lg border border-border bg-card p-4 sm:p-6"
                >
                    <Heading
                        variant="small"
                        title="Kontak"
                        description="Alamat dan informasi kontak sekolah"
                    />

                    <div class="mt-4 space-y-4">
                        <FormField
                            label="Alamat"
                            :error="form.errors.address"
                        >
                            <Textarea
                                v-model="form.address"
                                placeholder="Alamat lengkap sekolah"
                                class="min-h-20"
                            />
                        </FormField>

                        <div
                            class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                        >
                            <FormField
                                label="Telepon"
                                :error="form.errors.phone"
                            >
                                <Input
                                    v-model="form.phone"
                                    placeholder="Contoh: 022-1234567"
                                    type="tel"
                                />
                            </FormField>

                            <FormField
                                label="Email"
                                :error="form.errors.email"
                            >
                                <Input
                                    v-model="form.email"
                                    placeholder="Contoh: info@sekolah.sch.id"
                                    type="email"
                                />
                            </FormField>
                        </div>
                    </div>
                </div>

                <!-- Vision & Mission section -->
                <div
                    class="rounded-lg border border-border bg-card p-4 sm:p-6"
                >
                    <Heading
                        variant="small"
                        title="Visi & Misi"
                        description="Visi dan misi sekolah yang ditampilkan di profil"
                    />

                    <div class="mt-4 space-y-4">
                        <FormField
                            label="Visi"
                            :error="form.errors.vision"
                        >
                            <Textarea
                                v-model="form.vision"
                                placeholder="Visi sekolah"
                                class="min-h-24"
                            />
                        </FormField>

                        <FormField
                            label="Misi"
                            :error="form.errors.mission"
                        >
                            <Textarea
                                v-model="form.mission"
                                placeholder="Misi sekolah (satu misi per baris)"
                                class="min-h-32"
                            />
                        </FormField>
                    </div>
                </div>

                <!-- Submit -->
                <div
                    class="flex items-center justify-end gap-3 border-t border-border pt-6"
                >
                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p
                            v-show="form.recentlySuccessful"
                            class="text-sm text-muted-foreground"
                        >
                            Tersimpan.
                        </p>
                    </Transition>
                    <Button
                        type="submit"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Profil' }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

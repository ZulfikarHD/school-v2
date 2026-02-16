/**
 * Auto-generated model types — akan diganti oleh based/laravel-typescript
 * ketika sudah support Laravel 12. Untuk sementara, maintain secara manual.
 */

export type School = {
    id: number;
    name: string;
    npsn: string;
    slug: string;
    address: string | null;
    phone: string | null;
    email: string | null;
    vision: string | null;
    mission: string | null;
    settings: Record<string, unknown> | null;
    is_active: boolean;
    logo_url: string | null;
    logo_thumbnail_url: string | null;
    created_at: string;
    updated_at: string;
};

/** School data yang dishare via Inertia shared props */
export type SharedSchool = {
    id: number;
    name: string;
    npsn: string;
    logo_thumbnail_url: string | null;
};

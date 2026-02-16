/**
 * Mirror of PHP Backed Enums — keep in sync with app/Enums/.
 *
 * These will be auto-generated once `based/laravel-typescript`
 * supports Laravel 12. For now, maintain manually.
 */

/**
 * Mirror dari PHP App\Enums\UserRole.
 *
 * Menentukan layout yang digunakan berdasarkan role aktif user.
 */
export enum UserRole {
    SuperAdmin = 'super_admin',
    AdminSekolah = 'admin_sekolah',
    KepalaSekolah = 'kepala_sekolah',
    Guru = 'guru',
    WaliKelas = 'wali_kelas',
    Bendahara = 'bendahara',
    OrangTua = 'orang_tua',
    Siswa = 'siswa',
    GuruBk = 'guru_bk',
}

/** Role yang menggunakan AdminLayout */
export const ADMIN_LAYOUT_ROLES: UserRole[] = [
    UserRole.SuperAdmin,
    UserRole.AdminSekolah,
    UserRole.KepalaSekolah,
    UserRole.Bendahara,
];

/** Role yang menggunakan TeacherLayout */
export const TEACHER_LAYOUT_ROLES: UserRole[] = [
    UserRole.Guru,
    UserRole.WaliKelas,
    UserRole.GuruBk,
];

/** Role yang menggunakan ParentLayout */
export const PARENT_LAYOUT_ROLES: UserRole[] = [
    UserRole.OrangTua,
    UserRole.Siswa,
];

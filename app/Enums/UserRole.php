<?php

namespace App\Enums;

/**
 * Role pengguna dalam sistem sekolah.
 *
 * Menentukan hak akses dan layout yang digunakan.
 * Layout mapping: Admin/KepalaSekolah/Bendahara → AdminLayout,
 * Guru/WaliKelas/GuruBk → TeacherLayout,
 * OrangTua/Siswa → ParentLayout.
 */
enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case AdminSekolah = 'admin_sekolah';
    case KepalaSekolah = 'kepala_sekolah';
    case Guru = 'guru';
    case WaliKelas = 'wali_kelas';
    case Bendahara = 'bendahara';
    case OrangTua = 'orang_tua';
    case Siswa = 'siswa';
    case GuruBk = 'guru_bk';

    /**
     * Role yang menggunakan AdminLayout (sidebar + topbar, desktop-first).
     *
     * @return array<self>
     */
    public static function adminLayoutRoles(): array
    {
        return [
            self::SuperAdmin,
            self::AdminSekolah,
            self::KepalaSekolah,
            self::Bendahara,
        ];
    }

    /**
     * Role yang menggunakan TeacherLayout (simplified sidebar, tablet+desktop).
     *
     * @return array<self>
     */
    public static function teacherLayoutRoles(): array
    {
        return [
            self::Guru,
            self::WaliKelas,
            self::GuruBk,
        ];
    }

    /**
     * Role yang menggunakan ParentLayout (bottom nav, mobile-first).
     *
     * @return array<self>
     */
    public static function parentLayoutRoles(): array
    {
        return [
            self::OrangTua,
            self::Siswa,
        ];
    }

    /**
     * Menentukan layout group untuk role ini.
     */
    public function layoutGroup(): string
    {
        return match (true) {
            in_array($this, self::teacherLayoutRoles()) => 'teacher',
            in_array($this, self::parentLayoutRoles()) => 'parent',
            default => 'admin',
        };
    }

    /**
     * Label yang ditampilkan di UI (Bahasa Indonesia).
     */
    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::AdminSekolah => 'Admin Sekolah',
            self::KepalaSekolah => 'Kepala Sekolah',
            self::Guru => 'Guru',
            self::WaliKelas => 'Wali Kelas',
            self::Bendahara => 'Bendahara',
            self::OrangTua => 'Orang Tua',
            self::Siswa => 'Siswa',
            self::GuruBk => 'Guru BK',
        };
    }
}

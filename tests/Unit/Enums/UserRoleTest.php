<?php

namespace Tests\Unit\Enums;

use App\Enums\UserRole;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    public function test_all_roles_have_string_values(): void
    {
        foreach (UserRole::cases() as $role) {
            $this->assertIsString($role->value);
            $this->assertNotEmpty($role->value);
        }
    }

    public function test_admin_layout_roles_contains_expected_roles(): void
    {
        $roles = UserRole::adminLayoutRoles();

        $this->assertContains(UserRole::SuperAdmin, $roles);
        $this->assertContains(UserRole::AdminSekolah, $roles);
        $this->assertContains(UserRole::KepalaSekolah, $roles);
        $this->assertContains(UserRole::Bendahara, $roles);
        $this->assertCount(4, $roles);
    }

    public function test_teacher_layout_roles_contains_expected_roles(): void
    {
        $roles = UserRole::teacherLayoutRoles();

        $this->assertContains(UserRole::Guru, $roles);
        $this->assertContains(UserRole::WaliKelas, $roles);
        $this->assertContains(UserRole::GuruBk, $roles);
        $this->assertCount(3, $roles);
    }

    public function test_parent_layout_roles_contains_expected_roles(): void
    {
        $roles = UserRole::parentLayoutRoles();

        $this->assertContains(UserRole::OrangTua, $roles);
        $this->assertContains(UserRole::Siswa, $roles);
        $this->assertCount(2, $roles);
    }

    public function test_every_role_belongs_to_exactly_one_layout_group(): void
    {
        $allGroupedRoles = [
            ...UserRole::adminLayoutRoles(),
            ...UserRole::teacherLayoutRoles(),
            ...UserRole::parentLayoutRoles(),
        ];

        $this->assertCount(
            count(UserRole::cases()),
            $allGroupedRoles,
            'Setiap role harus masuk tepat satu layout group.'
        );

        $uniqueValues = array_unique(array_map(fn ($r) => $r->value, $allGroupedRoles));
        $this->assertCount(
            count($allGroupedRoles),
            $uniqueValues,
            'Tidak boleh ada role yang duplikat antar layout group.'
        );
    }

    public function test_layout_group_returns_correct_group_for_each_role(): void
    {
        foreach (UserRole::adminLayoutRoles() as $role) {
            $this->assertEquals('admin', $role->layoutGroup(), "{$role->value} harus admin layout group");
        }

        foreach (UserRole::teacherLayoutRoles() as $role) {
            $this->assertEquals('teacher', $role->layoutGroup(), "{$role->value} harus teacher layout group");
        }

        foreach (UserRole::parentLayoutRoles() as $role) {
            $this->assertEquals('parent', $role->layoutGroup(), "{$role->value} harus parent layout group");
        }
    }

    public function test_all_roles_have_labels(): void
    {
        foreach (UserRole::cases() as $role) {
            $this->assertIsString($role->label());
            $this->assertNotEmpty($role->label());
        }
    }
}

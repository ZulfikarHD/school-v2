<?php

namespace Tests\Feature\SchoolProfile;

use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SchoolProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        // Spatie Media Library default: storage_path('media-library/temp').
        // Di Docker, storage/ bisa not writable oleh appuser. Override ke /tmp.
        config(['media-library.temporary_directory_path' => '/tmp']);
    }

    public function test_school_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('school.profile.edit', $school));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('SchoolProfile/Edit')
            ->has('school')
            ->where('school.id', $school->id)
            ->where('school.name', $school->name)
            ->where('school.npsn', $school->npsn)
        );
    }

    public function test_school_profile_can_be_updated(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('school.profile.update', $school), [
                'name' => 'SD Negeri 1 Jakarta',
                'npsn' => '12345678',
                'address' => 'Jl. Sudirman No. 1, Jakarta',
                'phone' => '021-1234567',
                'email' => 'sdnegeri1@jakarta.sch.id',
                'vision' => 'Menjadi sekolah unggulan.',
                'mission' => 'Mencerdaskan anak bangsa.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $school->refresh();

        $this->assertSame('SD Negeri 1 Jakarta', $school->name);
        $this->assertSame('12345678', $school->npsn);
        $this->assertSame('Jl. Sudirman No. 1, Jakarta', $school->address);
        $this->assertSame('021-1234567', $school->phone);
        $this->assertSame('sdnegeri1@jakarta.sch.id', $school->email);
        $this->assertSame('Menjadi sekolah unggulan.', $school->vision);
        $this->assertSame('Mencerdaskan anak bangsa.', $school->mission);
    }

    public function test_school_name_is_required(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('school.profile.update', $school), [
                'name' => '',
                'npsn' => '12345678',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_npsn_must_be_8_digits(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create();

        // Too short
        $response = $this
            ->actingAs($user)
            ->post(route('school.profile.update', $school), [
                'name' => 'SD Test',
                'npsn' => '1234',
            ]);

        $response->assertSessionHasErrors('npsn');

        // Non-numeric
        $response = $this
            ->actingAs($user)
            ->post(route('school.profile.update', $school), [
                'name' => 'SD Test',
                'npsn' => 'abcdefgh',
            ]);

        $response->assertSessionHasErrors('npsn');
    }

    public function test_npsn_must_be_unique(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create(['npsn' => '11111111']);
        School::factory()->create(['npsn' => '22222222']);

        $response = $this
            ->actingAs($user)
            ->post(route('school.profile.update', $school), [
                'name' => 'SD Test',
                'npsn' => '22222222',
            ]);

        $response->assertSessionHasErrors('npsn');
    }

    public function test_school_can_keep_own_npsn(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create(['npsn' => '11111111']);

        $response = $this
            ->actingAs($user)
            ->post(route('school.profile.update', $school), [
                'name' => 'SD Test Updated',
                'npsn' => '11111111',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_logo_upload(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('school.profile.update', $school), [
                'name' => $school->name,
                'npsn' => $school->npsn,
                'logo' => UploadedFile::fake()->image('logo.jpg', 200, 200)->size(1024),
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $school->refresh();
        $this->assertCount(1, $school->getMedia('logo'));
    }

    public function test_logo_must_be_max_2mb(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('school.profile.edit', $school))
            ->post(route('school.profile.update', $school), [
                'name' => $school->name,
                'npsn' => $school->npsn,
                'logo' => UploadedFile::fake()->image('logo.jpg')->size(3000),
            ]);

        $response->assertSessionHasErrors('logo');
    }

    public function test_logo_must_be_image(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('school.profile.edit', $school))
            ->post(route('school.profile.update', $school), [
                'name' => $school->name,
                'npsn' => $school->npsn,
                'logo' => UploadedFile::fake()->create('document.pdf', 500),
            ]);

        $response->assertSessionHasErrors('logo');
    }

    public function test_logo_can_be_removed(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create();

        $school->addMedia(UploadedFile::fake()->image('logo.jpg', 200, 200))
            ->toMediaCollection('logo');
        $this->assertCount(1, $school->getMedia('logo'));

        $response = $this
            ->actingAs($user)
            ->post(route('school.profile.update', $school), [
                'name' => $school->name,
                'npsn' => $school->npsn,
                'remove_logo' => true,
            ]);

        $response->assertRedirect();
        $school->refresh();
        $this->assertCount(0, $school->getMedia('logo'));
    }

    public function test_optional_fields_can_be_empty(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('school.profile.update', $school), [
                'name' => 'SD Test',
                'npsn' => '12345678',
                'address' => '',
                'phone' => '',
                'email' => '',
                'vision' => '',
                'mission' => '',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_email_must_be_valid_format(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('school.profile.update', $school), [
                'name' => 'SD Test',
                'npsn' => '12345678',
                'email' => 'bukan-email',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_unauthenticated_user_cannot_access_school_profile(): void
    {
        $school = School::factory()->create();

        $response = $this->get(route('school.profile.edit', $school));

        $response->assertRedirect('/login');
    }

    public function test_school_data_is_shared_via_inertia(): void
    {
        $user = User::factory()->create();
        School::factory()->create(['name' => 'SD Shared Test', 'npsn' => '99999999']);

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->has('school')
            ->where('school.name', 'SD Shared Test')
            ->where('school.npsn', '99999999')
        );
    }
}

<?php

namespace App\Http\Controllers\SchoolProfile;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolProfile\UpdateSchoolProfileRequest;
use App\Models\School;
use App\Services\SchoolService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/*
|--------------------------------------------------------------------------
| School Profile Controller
|--------------------------------------------------------------------------
|
| Controller untuk mengelola profil identitas sekolah.
| Menampilkan halaman edit dan memproses update profil via SchoolService.
|
*/

class SchoolProfileController extends Controller
{
    public function __construct(private SchoolService $schoolService) {}

    /**
     * Tampilkan halaman edit profil sekolah.
     */
    public function edit(School $school): Response
    {
        return Inertia::render('SchoolProfile/Edit', [
            'school' => [
                'id' => $school->id,
                'name' => $school->name,
                'npsn' => $school->npsn,
                'slug' => $school->slug,
                'address' => $school->address,
                'phone' => $school->phone,
                'email' => $school->email,
                'vision' => $school->vision,
                'mission' => $school->mission,
                'logo_url' => $school->logo_url,
            ],
        ]);
    }

    /**
     * Update profil sekolah.
     */
    public function update(UpdateSchoolProfileRequest $request, School $school): RedirectResponse
    {
        $validated = $request->safe()->except(['logo', 'remove_logo']);

        if ($request->boolean('remove_logo')) {
            $this->schoolService->removeLogo($school);
        }

        $this->schoolService->updateProfile(
            school: $school,
            data: $validated,
            logo: $request->file('logo'),
        );

        return back()->with('success', 'Profil sekolah berhasil diperbarui.');
    }
}

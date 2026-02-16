<?php

namespace App\Services;

use App\Models\School;
use Illuminate\Http\UploadedFile;

/*
|--------------------------------------------------------------------------
| School Service
|--------------------------------------------------------------------------
|
| Service layer untuk business logic School profile management.
| Menangani update profil sekolah termasuk upload logo via Spatie Media Library.
|
*/

class SchoolService
{
    /**
     * Update profil identitas sekolah.
     *
     * @param  School  $school  Sekolah yang akan diupdate
     * @param  array{name: string, npsn: string, address?: string, phone?: string, email?: string, vision?: string, mission?: string}  $data  Data profil yang sudah tervalidasi
     * @param  UploadedFile|null  $logo  File logo baru (opsional)
     */
    public function updateProfile(School $school, array $data, ?UploadedFile $logo = null): School
    {
        $school->update($data);

        if ($logo) {
            $school->addMedia($logo)
                ->toMediaCollection('logo');
        }

        return $school->fresh();
    }

    /**
     * Hapus logo sekolah dari media collection.
     */
    public function removeLogo(School $school): void
    {
        $school->clearMediaCollection('logo');
    }
}

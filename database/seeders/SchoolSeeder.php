<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Seed sample schools untuk development.
     */
    public function run(): void
    {
        School::factory()->create([
            'name' => 'SD Negeri 5 Bandung',
            'npsn' => '20219876',
            'slug' => 'sd-negeri-5-bandung',
            'address' => 'Jl. Merdeka No. 10, Bandung, Jawa Barat',
            'phone' => '022-1234567',
            'email' => 'sdnegeri5@bandung.sch.id',
            'vision' => 'Menjadi sekolah dasar unggulan yang mencetak generasi berkarakter, cerdas, dan berakhlak mulia.',
            'mission' => "1. Menyelenggarakan pendidikan yang berkualitas\n2. Membentuk karakter peserta didik yang berakhlak mulia\n3. Mengembangkan potensi dan bakat siswa secara optimal",
        ]);
    }
}

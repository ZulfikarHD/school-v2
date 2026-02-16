<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'SD Negeri '.fake()->numberBetween(1, 99).' '.fake()->city();

        return [
            'name' => $name,
            'npsn' => (string) fake()->unique()->numerify('########'),
            'slug' => Str::slug($name),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'vision' => 'Menjadi sekolah unggul yang berkarakter dan berprestasi.',
            'mission' => 'Menyelenggarakan pendidikan berkualitas dan membentuk karakter peserta didik.',
            'is_active' => true,
        ];
    }

    /**
     * State: sekolah tidak aktif.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

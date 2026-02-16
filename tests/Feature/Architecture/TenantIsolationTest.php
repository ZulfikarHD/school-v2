<?php

namespace Tests\Feature\Architecture;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;
use Tests\TestCase;

/**
 * Scan arsitektural — memastikan semua model tenant menggunakan
 * BelongsToSchool trait agar tidak terjadi kebocoran data antar sekolah.
 */
class TenantIsolationTest extends TestCase
{
    /**
     * Model yang secara desain TIDAK memiliki school_id (shared tables).
     *
     * @var array<int, string>
     */
    private array $sharedModels = [
        'App\\Models\\User',
        'App\\Models\\School',
    ];

    /**
     * Memastikan trait BelongsToSchool tersedia dan dapat di-resolve.
     */
    public function test_belongs_to_school_trait_exists(): void
    {
        $this->assertTrue(
            trait_exists(BelongsToSchool::class),
            'Trait BelongsToSchool harus ada di App\\Models\\Concerns\\BelongsToSchool'
        );
    }

    /**
     * Memastikan semua model yang memiliki kolom school_id
     * menggunakan trait BelongsToSchool.
     */
    public function test_all_tenant_models_use_belongs_to_school_trait(): void
    {
        $modelClasses = $this->discoverModelClasses();
        $violations = [];

        foreach ($modelClasses as $modelClass) {
            if (in_array($modelClass, $this->sharedModels, true)) {
                continue;
            }

            $reflection = new ReflectionClass($modelClass);

            if ($reflection->isAbstract()) {
                continue;
            }

            $traits = $this->getAllTraits($reflection);

            $hasSchoolIdProperty = $this->modelHasSchoolIdColumn($modelClass);

            if ($hasSchoolIdProperty && ! in_array(BelongsToSchool::class, $traits, true)) {
                $violations[] = $modelClass;
            }
        }

        $this->assertEmpty(
            $violations,
            'Model berikut memiliki school_id tapi TIDAK menggunakan BelongsToSchool trait: '
            .implode(', ', $violations)
            .'. Tambahkan `use BelongsToSchool;` atau masukkan ke $sharedModels jika memang shared.'
        );
    }

    /**
     * Memastikan model shared TIDAK menggunakan trait BelongsToSchool.
     */
    public function test_shared_models_do_not_use_belongs_to_school_trait(): void
    {
        foreach ($this->sharedModels as $modelClass) {
            if (! class_exists($modelClass)) {
                continue;
            }

            $reflection = new ReflectionClass($modelClass);
            $traits = $this->getAllTraits($reflection);

            $this->assertNotContains(
                BelongsToSchool::class,
                $traits,
                "Model shared {$modelClass} seharusnya TIDAK menggunakan BelongsToSchool trait."
            );
        }
    }

    /**
     * Menemukan semua class model Eloquent di app/Models/.
     *
     * @return array<int, string>
     */
    private function discoverModelClasses(): array
    {
        $modelPath = app_path('Models');
        $classes = [];

        if (! File::isDirectory($modelPath)) {
            return $classes;
        }

        $files = File::allFiles($modelPath);

        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();

            if (Str::startsWith($relativePath, 'Concerns/')) {
                continue;
            }

            if ($file->getExtension() !== 'php') {
                continue;
            }

            $className = 'App\\Models\\'.Str::replace(
                ['/', '.php'],
                ['\\', ''],
                $relativePath
            );

            if (! class_exists($className)) {
                continue;
            }

            $reflection = new ReflectionClass($className);

            if ($reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class)) {
                $classes[] = $className;
            }
        }

        return $classes;
    }

    /**
     * Mengambil semua trait yang digunakan oleh class (termasuk parent traits).
     *
     * @return array<int, string>
     */
    private function getAllTraits(ReflectionClass $reflection): array
    {
        $traits = [];

        do {
            foreach ($reflection->getTraitNames() as $trait) {
                $traits[] = $trait;
            }
        } while ($reflection = $reflection->getParentClass());

        return array_unique($traits);
    }

    /**
     * Memeriksa apakah model memiliki kolom school_id di fillable atau tabel.
     * Menggunakan pengecekan properti $fillable dan source code.
     */
    private function modelHasSchoolIdColumn(string $modelClass): bool
    {
        $reflection = new ReflectionClass($modelClass);
        $source = File::get($reflection->getFileName());

        return Str::contains($source, 'school_id');
    }
}

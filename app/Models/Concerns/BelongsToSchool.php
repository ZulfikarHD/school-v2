<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait multi-tenancy untuk model yang terikat ke satu sekolah.
 *
 * Secara otomatis menambahkan global scope `school_id` pada query
 * dan mengisi `school_id` saat pembuatan record baru.
 * WAJIB digunakan pada setiap model tenant — model tanpa trait ini
 * berpotensi menyebabkan kebocoran data antar sekolah.
 */
trait BelongsToSchool
{
    public static function bootBelongsToSchool(): void
    {
        static::addGlobalScope('school', function (Builder $builder): void {
            if (function_exists('tenant') && tenant('id')) {
                $builder->where(
                    $builder->getModel()->qualifyColumn('school_id'),
                    tenant('id')
                );
            }
        });

        static::creating(function (Model $model): void {
            if (! $model->school_id && function_exists('tenant') && tenant('id')) {
                $model->school_id = tenant('id');
            }
        });
    }
}

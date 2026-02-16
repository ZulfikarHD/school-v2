<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/*
|--------------------------------------------------------------------------
| School Model
|--------------------------------------------------------------------------
|
| Model utama untuk entitas sekolah (tenant). Menyimpan identitas sekolah
| termasuk nama, NPSN, alamat, visi/misi, dan logo via Spatie Media Library.
| Menjadi pusat multi-tenancy — semua data tenant merujuk ke school_id.
|
*/

class School extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\SchoolFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'npsn',
        'slug',
        'address',
        'phone',
        'email',
        'vision',
        'mission',
        'settings',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Register media collections untuk logo sekolah.
     *
     * Collection 'logo' hanya menerima 1 file (single file collection).
     * Ukuran maksimal divalidasi di form request, bukan di sini.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /**
     * Register media conversions untuk optimasi gambar.
     *
     * Thumbnail (80px) untuk sidebar/topbar, medium (200px) untuk halaman profil.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(80)
            ->height(80)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(200)
            ->height(200)
            ->sharpen(10)
            ->nonQueued();
    }

    /**
     * Get URL logo sekolah (medium conversion).
     */
    public function getLogoUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('logo');

        return $media?->getUrl('medium');
    }

    /**
     * Get URL logo thumbnail untuk sidebar/topbar.
     */
    public function getLogoThumbnailUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('logo');

        return $media?->getUrl('thumbnail');
    }

    /**
     * Users yang terdaftar di sekolah ini.
     *
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}

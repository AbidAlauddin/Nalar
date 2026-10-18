<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MaterialCategory extends Model
{
    use HasFactory;

    public const LEVEL_SMA = 'SMA';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'education_level',
        'description',
        'is_active',
    ];

    protected $attributes = [
        'education_level' => self::LEVEL_SMA,
        'is_active' => true,
    ];

    protected static function booted(): void
    {
        static::saving(function (self $category): void {
            if ($category->user_id === null) {
                $category->user_id = User::query()
                    ->where('role', User::ROLE_SUPER_ADMIN)
                    ->orderBy('id')
                    ->value('id');
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creatorName(): string
    {
        if ($this->relationLoaded('creator') && $this->creator !== null) {
            return $this->creator->name;
        }

        if ($this->user_id !== null && $this->creator !== null) {
            return $this->creator->name;
        }

        return User::query()
            ->where('role', User::ROLE_SUPER_ADMIN)
            ->orderBy('id')
            ->value('name') ?? 'Super Admin';
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'material_category_id');
    }

    public function publishedMaterials(): HasMany
    {
        return $this->materials()->where('status', Material::STATUS_PUBLISHED);
    }

    public function scopeSma(Builder $query): Builder
    {
        return $query->where('education_level', self::LEVEL_SMA);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        if ($baseSlug === '') {
            $baseSlug = 'kategori';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (
            static::query()
                ->where('slug', $slug)
                ->when($ignoreId !== null, fn (Builder $q) => $q->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}

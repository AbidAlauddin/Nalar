<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Material extends Model
{
    use HasFactory;

    public const LEVEL_SMA = 'SMA';

    public const GRADE_10 = '10';
    public const GRADE_11 = '11';
    public const GRADE_12 = '12';

    public const GRADES = [
        self::GRADE_10 => 'Kelas X (10)',
        self::GRADE_11 => 'Kelas XI (11)',
        self::GRADE_12 => 'Kelas XII (12)',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    public const STATUSES = [
        self::STATUS_PUBLISHED => 'Dipublikasikan',
        self::STATUS_DRAFT => 'Draf',
    ];

    protected $fillable = [
        'material_category_id',
        'user_id',
        'title',
        'slug',
        'education_level',
        'grade_level',
        'description',
        'image_path',
        'image_caption',
        'content',
        'status',
        'estimated_minutes',
        'published_at',
    ];

    protected $attributes = [
        'education_level' => self::LEVEL_SMA,
        'grade_level' => self::GRADE_10,
        'status' => self::STATUS_PUBLISHED,
        'estimated_minutes' => 15,
    ];

    protected function casts(): array
    {
        return [
            'estimated_minutes' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeSma(Builder $query): Builder
    {
        return $query->where('education_level', self::LEVEL_SMA);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function gradeLabel(): string
    {
        return self::GRADES[$this->grade_level] ?? "Kelas {$this->grade_level}";
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function hasImage(): bool
    {
        return is_string($this->image_path) && trim($this->image_path) !== '';
    }

    public function imageUrl(): ?string
    {
        if (! $this->hasImage()) {
            return null;
        }

        $path = trim((string) $this->image_path);

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Parse raw content into structured sections (headings, paragraphs, bullet lists, and formula callouts)
     * so detail views can present a rich, structured reading layout instead of a plain wall of text.
     *
     * @return array<int, array{id: string, number: string, title: string, blocks: array<int, array<string, mixed>>}>
     */
    public function contentSections(): array
    {
        $raw = str_replace(["\r\n", "\r"], "\n", trim((string) $this->content));
        if ($raw === '') {
            return [];
        }

        $lines = explode("\n", $raw);
        $sections = [];
        $currentSection = null;
        $currentList = [];

        $flushList = function () use (&$currentSection, &$currentList): void {
            if ($currentList !== [] && $currentSection !== null) {
                $currentSection['blocks'][] = [
                    'type' => 'list',
                    'items' => $currentList,
                ];
                $currentList = [];
            }
        };

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '') {
                $flushList();
                continue;
            }

            // Detect section heading: e.g. "1. Pengertian Eksponen" or "## Pengertian Eksponen"
            if (preg_match('/^(?:(\d+)[\.\)]\s+|#{1,3}\s+)(.+)$/u', $trimmed, $matches) === 1 && mb_strlen($trimmed) <= 110) {
                $flushList();
                if ($currentSection !== null) {
                    $sections[] = $currentSection;
                }

                $secIndex = count($sections) + 1;
                $number = $matches[1] !== '' ? str_pad($matches[1], 2, '0', STR_PAD_LEFT) : str_pad((string) $secIndex, 2, '0', STR_PAD_LEFT);

                $currentSection = [
                    'id' => 'bagian-' . $secIndex,
                    'number' => $number,
                    'title' => trim($matches[2]),
                    'blocks' => [],
                ];

                continue;
            }

            if ($currentSection === null) {
                $currentSection = [
                    'id' => 'bagian-1',
                    'number' => '01',
                    'title' => 'Pokok Pembahasan',
                    'blocks' => [],
                ];
            }

            // Detect bullet list item: "- ...", "* ...", "• ..."
            if (preg_match('/^[-*•]\s+(.+)$/u', $trimmed, $listMatch) === 1) {
                $currentList[] = trim($listMatch[1]);
                continue;
            }

            $flushList();

            // Detect standalone formula / equation line
            $isFormula = mb_strlen($trimmed) <= 95
                && ! str_ends_with($trimmed, '.')
                && ! str_ends_with($trimmed, ':')
                && (
                    str_contains($trimmed, ' = ')
                    || str_contains($trimmed, 'Σ')
                    || str_contains($trimmed, 'Δ')
                    || str_contains($trimmed, '^')
                );

            if ($isFormula) {
                $currentSection['blocks'][] = [
                    'type' => 'formula',
                    'text' => $trimmed,
                ];
            } else {
                $currentSection['blocks'][] = [
                    'type' => 'paragraph',
                    'text' => $trimmed,
                ];
            }
        }

        $flushList();
        if ($currentSection !== null) {
            $sections[] = $currentSection;
        }

        return $sections;
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        if ($baseSlug === '') {
            $baseSlug = 'materi';
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

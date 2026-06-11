<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Traits\Auditable;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use Auditable, HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'summary',
        'description',
        'status',
        'featured_image',
        'tech_stack',
        'repository_url',
        'demo_url',
        'started_at',
        'finished_at',
        'is_featured',
        'seo_title',
        'seo_description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'tech_stack' => 'array',
            'started_at' => 'date',
            'finished_at' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', ProjectStatus::Published);
    }

    public function scopeFilterBySearch($query, ?string $search)
    {
        return $query->when($search, function ($builder) use ($search) {
            $term = '%'.$search.'%';
            $builder->where(function ($nested) use ($term) {
                $nested->where('title', 'like', $term)
                    ->orWhere('slug', 'like', $term)
                    ->orWhere('summary', 'like', $term);
            });
        });
    }

    public function scopeFilterByCategory($query, ?int $categoryId)
    {
        return $query->when($categoryId, fn ($builder) => $builder->where('category_id', $categoryId));
    }

    public function scopeFilterByStatus($query, ?string $status)
    {
        return $query->when($status, fn ($builder) => $builder->where('status', $status));
    }

    public function scopeFilterByFeatured($query, $isFeatured)
    {
        return $query->when($isFeatured !== null, fn ($builder) => $builder->where('is_featured', (bool) $isFeatured));
    }

    public function scopeFilterByDateRange($query, ?string $from, ?string $to)
    {
        return $query->when($from, fn ($builder) => $builder->whereDate('created_at', '>=', $from))
            ->when($to, fn ($builder) => $builder->whereDate('created_at', '<=', $to));
    }

    public function scopeFilterByFinishedRange($query, ?string $from, ?string $to)
    {
        return $query->when($from, fn ($builder) => $builder->whereDate('finished_at', '>=', $from))
            ->when($to, fn ($builder) => $builder->whereDate('finished_at', '<=', $to));
    }
}

<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use Auditable, HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'body',
        'is_published',
        'published_at',
        'featured_image',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeFilterBySearch($query, ?string $search)
    {
        return $query->when($search, function ($builder) use ($search) {
            $term = '%'.$search.'%';
            $builder->where(function ($nested) use ($term) {
                $nested->where('title', 'like', $term)
                    ->orWhere('slug', 'like', $term);
            });
        });
    }

    public function scopeFilterByCategory($query, ?int $categoryId)
    {
        return $query->when($categoryId, fn ($builder) => $builder->where('category_id', $categoryId));
    }

    public function scopeFilterByPublished($query, $isPublished)
    {
        return $query->when($isPublished !== null, fn ($builder) => $builder->where('is_published', (bool) $isPublished));
    }

    public function scopeFilterByDateRange($query, ?string $from, ?string $to)
    {
        return $query->when($from, fn ($builder) => $builder->whereDate('created_at', '>=', $from))
            ->when($to, fn ($builder) => $builder->whereDate('created_at', '<=', $to));
    }

    public function scopeFilterByPublishedRange($query, ?string $from, ?string $to)
    {
        return $query->when($from, fn ($builder) => $builder->whereDate('published_at', '>=', $from))
            ->when($to, fn ($builder) => $builder->whereDate('published_at', '<=', $to));
    }

    public function scopeFilterByTag($query, ?string $tag)
    {
        return $query->when($tag, function ($builder) use ($tag) {
            $builder->whereHas('tags', fn ($tagQuery) => $tagQuery->where('slug', $tag)->orWhere('name', $tag));
        });
    }
}

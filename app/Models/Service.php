<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use Auditable, HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'short_description',
        'full_description',
        'price_from',
        'delivery_time',
        'is_active',
        'call_to_action',
        'featured_image',
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
            'price_from' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeListed(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeFilterBySearch($query, ?string $search)
    {
        return $query->when($search, function ($builder) use ($search) {
            $term = '%'.$search.'%';
            $builder->where(function ($nested) use ($term) {
                $nested->where('name', 'like', $term)
                    ->orWhere('slug', 'like', $term)
                    ->orWhere('short_description', 'like', $term);
            });
        });
    }

    public function scopeFilterByCategory($query, ?int $categoryId)
    {
        return $query->when($categoryId, fn ($builder) => $builder->where('category_id', $categoryId));
    }

    public function scopeFilterByActive($query, $isActive)
    {
        return $query->when($isActive !== null, fn ($builder) => $builder->where('is_active', (bool) $isActive));
    }

    public function scopeFilterByDateRange($query, ?string $from, ?string $to)
    {
        return $query->when($from, fn ($builder) => $builder->whereDate('created_at', '>=', $from))
            ->when($to, fn ($builder) => $builder->whereDate('created_at', '<=', $to));
    }
}

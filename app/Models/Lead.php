<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use Auditable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'service_interest',
        'message',
        'internal_notes',
        'payment_link',
        'quoted_value',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quoted_value' => 'decimal:2',
        ];
    }

    public function scopeFilterByStatus($query, ?string $status)
    {
        return $query->when($status, fn ($builder) => $builder->where('status', $status));
    }

    public function scopeFilterByServiceInterest($query, ?string $interest)
    {
        return $query->when($interest, fn ($builder) => $builder->where('service_interest', $interest));
    }

    public function scopeFilterBySearch($query, ?string $search)
    {
        return $query->when($search, function ($builder) use ($search) {
            $term = '%'.$search.'%';
            $builder->where(function ($nested) use ($term) {
                $nested->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term);
            });
        });
    }

    public function scopeFilterByDateRange($query, ?string $from, ?string $to)
    {
        return $query->when($from, fn ($builder) => $builder->whereDate('created_at', '>=', $from))
            ->when($to, fn ($builder) => $builder->whereDate('created_at', '<=', $to));
    }
}

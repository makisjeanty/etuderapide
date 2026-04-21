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

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}

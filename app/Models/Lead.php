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
}

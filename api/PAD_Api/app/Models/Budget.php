<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'month',
        'year',
        'original_amount',
        'current_amount',
    ];

    protected function casts(): array
    {
        return [
            'original_amount' => 'decimal:2',
            'current_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

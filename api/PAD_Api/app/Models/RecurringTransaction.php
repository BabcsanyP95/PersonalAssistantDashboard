<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\RecurringFrequency;
use App\Enums\TransactionType;

class RecurringTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'amount',
        'description',
        'frequency',
        'next_run_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'next_run_date' => 'date',
            'frequency' => RecurringFrequency::class,
            'type' => TransactionType::class,
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

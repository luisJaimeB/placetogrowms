<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'periodicity',
        'interval',
        'amount',
        'next_payment',
        'due_date',
        'microsite_id',
        'user_id'
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Suscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payer',
        'token',
        'plan'
    ];

    protected $casts = [
        'payer' => 'array',
        'plan' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

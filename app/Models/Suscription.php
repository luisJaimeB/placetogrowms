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
        'plan_id',
        'microsite_id',
        'payment_id',
        'status'
    ];

    protected $casts = [
        'payer' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function suscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SuscriptionPlan::class, 'plan_id');
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}

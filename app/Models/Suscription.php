<?php

namespace App\Models;

use App\Constants\SuscriptionsStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'status',
        'next_billing_date',
    ];

    protected $casts = [
        'payer' => 'array',
        'status' => SuscriptionsStatus::class,
        'next_billing_date' => 'date'
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

    public function initialPayment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'subscription_id');
    }
}

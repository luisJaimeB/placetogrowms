<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'request_id',
        'type',
        'amount',
        'currency_id',
        'reference',
        'description',
        'date',
        'buyer',
        'payer',
        'return_url',
        'proccess_url',
        'ip_address',
        'user_agent',
        'microsite_id',
    ];

    protected $casts = [
        'buyer' => 'array',
        'payer' => 'array',
        'optional_fields' => 'array',
        'plan' => 'array',
    ];

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Suscription::class, 'subscription_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}

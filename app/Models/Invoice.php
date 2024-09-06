<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'microsite_id',
        'order_number',
        'identification_type_id',
        'identification_number',
        'debtor_name',
        'email',
        'description',
        'currency_id',
        'amount',
        'expiration_date',
        'user_id',
        'payment_id'
        ];

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function buyeridtype(): BelongsTo
    {
        return $this->belongsTo(BuyerIdType::class);
    }
}

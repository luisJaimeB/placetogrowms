<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Permission\Traits\HasRoles;

class Microsite extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'name',
        'category_id',
        'expiration',
        'type_site_id',
        'logo',
    ];

    protected $casts = [
        'optional_fields' => 'array',
    ];

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function typeSite(): BelongsTo
    {
        return $this->belongsTo(TypeSite::class, 'type_site_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function optionalField(): BelongsTo
    {
        return $this->belongsTo(OptionalField::class);
    }

    public function suscriptionPlanes(): HasMany
    {
        return $this->hasMany(SuscriptionPlan::class);
    }

    public function suscriptions(): HasMany
    {
        return $this->hasMany(Suscription::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function acls(): MorphMany
    {
        return $this->morphMany(Acl::class, 'model');
    }
}

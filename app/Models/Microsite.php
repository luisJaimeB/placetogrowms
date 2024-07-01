<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
}

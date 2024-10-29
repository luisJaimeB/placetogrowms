<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function microsites(): HasMany
    {
        return $this->hasMany(Microsite::class);
    }
}

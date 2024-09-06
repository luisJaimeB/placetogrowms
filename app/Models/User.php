<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function microsites(): HasMany
    {
        return $this->hasMany(Microsite::class);
    }

    public function suscriptionPlanes(): HasMany
    {
        return $this->hasMany(SuscriptionPlan::class);
    }

    public function suscription(): HasMany
    {
        return $this->hasMany(Suscription::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function givePermissionToObject($permission, $object)
    {
        return Acl::create([
            'user_id' => $this->id,
            'permission_id' => $permission->id,
            'object_type' => get_class($object),
            'object_id' => $object->id,
        ]);
    }

    public function hasPermissionForObject($permission, $object)
    {
        return Acl::where('user_id', $this->id)
            ->where('permission_id', $permission->id)
            ->where('object_type', get_class($object))
            ->where('object_id', $object->id)
            ->exists();
    }

    public function revokePermissionForObject($permission, $object)
    {
        return Acl::where('user_id', $this->id)
            ->where('permission_id', $permission->id)
            ->where('object_type', get_class($object))
            ->where('object_id', $object->id)
            ->delete();
    }
}

<?php

namespace App\Actions;

use App\Contracts\Executable;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRoleAction implements Executable
{
    protected User|Authenticatable $user;

    public function __construct(private array $data)
    {
        $this->user = auth()->user();
    }

    public static function execute(array $data, ?Model $model = null): Model|false
    {
        $rol = new Role;
        $rol->name = $data['name'];
        $rol->guard_name = config('auth.defaults.guard');
        $rol->save();

        $permissions = Permission::whereIn('id', $data['permissions'])->get();
        $rol->syncPermissions($permissions);

        return $rol;
    }
}

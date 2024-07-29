<?php

namespace App\Actions;

use App\Contracts\Executable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdateRoleAction implements Executable
{
    protected array $data;

    protected Role $role;

    public function __construct(array $data, Role $role)
    {
        $this->data = $data;
        $this->role = $role;
    }

    public static function execute(array $data, ?Model $role = null): \Illuminate\Database\Eloquent\Model|false
    {
        self::updateRoleName($role, $data['name']);
        self::updateRolePermissions($role, $data['permissions']);

        return $role;
    }

    public static function updateRoleName(Role $role, string $name)
    {
        $role->name = $name;
        $role->save();
    }

    public static function updateRolePermissions(Role $role, array $permissions)
    {
        $permissions = Permission::whereIn('id', $permissions)->get();
        $role->syncPermissions($permissions);
    }
}

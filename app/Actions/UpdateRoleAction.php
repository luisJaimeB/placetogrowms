<?php

namespace App\Actions;

use App\Contracts\Executable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UpdateRoleAction implements Executable
{
    protected array $data;
    protected Role $role;

    public function __construct(array $data, Role $role)
    {
        $this->data = $data;
        $this->role = $role;
    }

    public function execute()
    {
        self::updateRoleName($this->role, $this->data['name']);
        self::updateRolePermissions($this->role, $this->data['permissions']);
        
        return $this->role;
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

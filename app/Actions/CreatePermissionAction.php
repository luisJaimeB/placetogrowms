<?php

namespace App\Actions;

use App\Contracts\Create;
use Spatie\Permission\Models\Permission;

class CreatePermissionAction implements Create
{

    public static function execute(array $data): Permission
    {
        $permission = new Permission();
        $permission->name = $data['name'];
        $permission->save();

        return $permission;
    }
}

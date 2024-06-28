<?php

namespace App\Actions;

use App\Contracts\Executable;
use Spatie\Permission\Models\Permission;

class CreatePermissionAction implements Executable
{
    public function __construct(private array $data)
    {
        
    }

    public function execute(): Permission
    {
        $permission = new Permission();
        $permission->name = $this->data['name'];
        $permission->save();

        return $permission;     
    }
}
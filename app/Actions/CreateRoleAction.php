<?php

namespace App\Actions;

use App\Contracts\Executable;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\Permission\Models\Role;

class CreateRoleAction implements Executable
{
    protected User|Authenticatable $user;
    
    public function __construct(private array $data)
    {
        $this->user = auth()->user();
    }

    public function execute()
    {
        $rol = new Role();
        $rol->name = $this->data['name'];
        $rol->guard_name = config('auth.defaults.guard');
        $rol->save();

        return $rol;
    }
}
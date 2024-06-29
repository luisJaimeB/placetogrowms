<?php

namespace App\Actions;

use App\Contracts\Executable;
use App\Models\User;

class CreateUserAction implements Executable
{
    public function __construct(private array $data)
    {
        
    }

    public function execute(): User
    {
        $user = new User();
        $user->name = $this->data['name'];
        $user->email = $this->data['email'];
        $user->password = bcrypt($this->data['password']);
        $user->email_verified_at = now();
        $user->save();

        if (!empty($this->data['roles'])) {
            $user->roles()->sync($this->data['roles']);
        }
        return $user;
    }
}
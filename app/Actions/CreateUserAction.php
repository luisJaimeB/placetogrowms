<?php

namespace App\Actions;

use App\Contracts\Executable;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CreateUserAction implements Executable
{
    public static function execute(array $data, Model|null $model = null): Model|false
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->email_verified_at = now();
        $user->save();

        if (!empty($data['rol'])) {
            $user->roles()->sync($data['rol']);
        } else {
            $user->assignRole('customer');
        }
        return $user;
    }
}

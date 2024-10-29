<?php

namespace App\Policies;

use App\Models\Acl;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AclPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Acl $acl)
    {
        return $acl->status === 'allowed' && $acl->user_id === $user->id;
    }
}

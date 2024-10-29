<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Models\Microsite;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class MicrositePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    public function viewPayments(User $user, Microsite $microsite)
    {
        $permission = Permission::where('name', Permissions::MICROSITES_SHOW)->first();

        return $user->hasPermissionForObject($permission, $microsite);
    }

    /**
     * Determine whether the user can create models.
     */
    public function createMicrosites(User $user, Microsite $microsite): bool
    {
        $permission = Permission::where('name', Permissions::MICROSITES_CREATE)->first();

        return $user->givePermissionToObject($permission, $microsite);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Microsite $microsite): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Microsite $microsite): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Microsite $microsite): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Microsite $microsite): bool
    {
        //
    }
}

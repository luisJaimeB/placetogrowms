<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreateRoleAction;
use App\Actions\UpdateRoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()
            ->paginate(25);

        return inertia('Admin/Roles/Index', ['roles' => $roles]);
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('.', $permission->name)[0];
        });

        return Inertia('Admin/Roles/Create', ['permissions' => $permissions]);
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $createAction = new CreateRoleAction($request->validated());
        $createAction->execute();

        return redirect()->route('roles.index');
    }

    public function edit(Role $role, Permission $permission)
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('.', $permission->name)[0];
        });
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return inertia('Admin/Roles/Edit', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $updateRoleAction = new UpdateRoleAction($request->validated(), $role);
        $role = $updateRoleAction->execute();
        return redirect()->route('roles.index');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();
        return redirect()->route('roles.index');
    }
}

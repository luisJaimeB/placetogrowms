<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreateRoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()
            ->paginate(3);

        return inertia('Admin/Roles/Index', ['roles' => $roles]);
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('.', $permission->name)[0];
        });

        return Inertia('Admin/Roles/Create', ['permissions' => $permissions]);
    }

    public function store(CreateRoleRequest $request): RedirectResponse
    {
        $createAction = new CreateRoleAction($request->validated());
        $createAction->execute();

        return redirect()->route('roles.index');
    }
}

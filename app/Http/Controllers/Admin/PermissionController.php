<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreatePermissionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(): Response
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return inertia('Admin/Permissions/Index', ['permissions' => $permissions]);
    }

    public function create(): Response
    {
        return Inertia('Admin/Permissions/Create');
    }

    public function store(PermissionRequest $request): RedirectResponse
    {
        CreatePermissionAction::execute($request->validated());

        return redirect()->route('permissions.index');
    }

    public function edit(Permission $permission): Response
    {
        return inertia('Admin/Permissions/Edit', ['permission' => $permission]);
    }

    public function update(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        $permission->update($request->validated());

        return redirect()->route('permissions.index');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('permissions.index');
    }
}

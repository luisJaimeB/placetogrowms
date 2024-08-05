<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreatePermissionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::latest()->orderBy('id', 'desc')
            ->paginate(25);

        return inertia('Admin/Permissions/Index', ['permissions' => $permissions]);
    }

    public function create()
    {
        return Inertia('Admin/Permissions/Create');
    }

    public function store(PermissionRequest $request): RedirectResponse
    {
        CreatePermissionAction::execute($request->validated());

        return redirect()->route('permissions.index');
    }

    public function edit(Permission $permission)
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

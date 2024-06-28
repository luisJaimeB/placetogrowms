<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;


class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()
            ->paginate(3);

        return inertia('Admin/Users/Index', ['users' => $users]);
    }

    public function create()
    {
        return Inertia('Admin/Users/Create');
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $createAction = new CreateUserAction($request->validated());
        $createAction->execute();

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        $userPermissions = $user->getAllPermissions()->pluck('name');
        return inertia('Admin/Users/Edit', [
            'user' => $user,
            'userPermissions' => $userPermissions,
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());
        return redirect()->route('users.index');
    }

    
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}

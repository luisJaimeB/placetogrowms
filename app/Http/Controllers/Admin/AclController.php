<?php

namespace App\Http\Controllers\Admin;

use App\Constants\AclActions;
use App\Constants\AclModels;
use App\Http\Controllers\Controller;
use App\Http\Requests\AclCreateRequest;
use App\Models\Acl;
use App\Models\User;
use Inertia\Response;

class AclController extends Controller
{
    public function __construct()
    {
        $this->modelMap = config('modelmap');
    }

    public function index(): Response
    {
        $user = auth()->user();

        $acls = Acl::where('user_id', $user->id)
            ->where('status', 'allowed')
            ->with(['user', 'model'])
            ->get();

        return inertia('Admin/Acls/Index', ['acls' => $acls]);
    }

    public function create(): Response
    {
        return Inertia('Admin/Acls/Create', [
            'users' => User::all(),
            'models' => AclModels::toOptions(),
            'actions' => AclActions::toArray(),
        ]);
    }

    public function store(AclCreateRequest $request)
    {
        $acl = new Acl;

        $acl->user_id = $request->input('user_id');
        $acl->status = $request->input('status');

        $modelType = $this->modelMap[$request->input('model_type')];
        $modelId = $request->input('model_id');

        $model = $modelType::find($modelId);

        $acl->model()->associate($model);

        $acl->save();

        return redirect()->route('acls.index');
    }
}

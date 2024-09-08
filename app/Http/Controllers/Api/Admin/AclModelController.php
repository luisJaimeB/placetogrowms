<?php

namespace App\Http\Controllers\Api\Admin;

use App\Constants\AclModels;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetAclModelRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AclModelController extends Controller
{
    public function index(GetAclModelRequest $request): JsonResponse
    {
        $model = AclModels::from($request->input('model'));
        $result = DB::table($model->value)->get($model->columns());

        $aliasedResult = $result->map(function ($item) use ($model) {
            return (object) $model->applyColumnAliases((array) $item);
        });

        return response()->json($aliasedResult);
    }
}

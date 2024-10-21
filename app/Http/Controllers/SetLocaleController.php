<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class SetLocaleController extends Controller
{
    public function setLang(string $locale): JsonResponse
    {
        App::setLocale($locale);

        $common = trans('common');
        $acls = trans('acls');
        $permissions = trans('permissions');
        $roles = trans('roles');
        $users = trans('users');
        $imports = trans('imports');
        $invoices = trans('invoices');
        $microsites = trans('microsites');
        $payments = trans('payments');
        $subscriptions = trans('subscriptions');

        $messages = array_replace_recursive($common, $users, $payments, $imports, $invoices, $subscriptions, $microsites, $roles, $permissions, $acls);

        return response()->json([
            'locale' => $locale,
            'messages' => $messages,
        ]);
    }
}

<?php

namespace App\Actions;

use App\Contracts\Create;
use App\Models\SuscriptionPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateSuscriptionPlanAction implements Create
{
    public static function execute(array $data): Model|false
    {
        $plan = new SuscriptionPlan;

        $plan->name = $data['name'];
        $plan->microsite_id = $data['microsite_id'];
        $plan->user_id = Auth::id();
        $plan->periodicity = $data['periodicity'];
        $plan->amount = $data['amount'];
        $plan->subscriptionTerm = $data['subscriptionTerm'];
        $plan->attempts = $data['attempts'];
        $plan->lapse = $data['lapse'];
        $plan->items = $data['items'] ?? ['Debits automatics'];

        $plan->save();

        return $plan;
    }
}

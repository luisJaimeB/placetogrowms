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
        $plan = new SuscriptionPlan();

        $plan->name = $data['name'];
        $plan->microsite_id = $data['microsite_id'];
        $plan->user_id = Auth::id();
        $plan->periodicity = $data['periodicity'];
        $plan->interval = $data['interval'];
        $plan->next_payment = $data['next_payment'];
        $plan->amount = $data['amount'];
        $plan->due_date = $data['due_date'];
        $plan->items = $data['items'];
        $plan->save();

        return $plan;
    }

}
